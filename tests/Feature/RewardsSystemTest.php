<?php

namespace Tests\Feature;

use App\Models\RewardCatalogItem;
use App\Models\RewardCatalogOrder;
use App\Models\RewardTransaction;
use App\Models\User;
use App\Services\RewardsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RewardsSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_awards_points_and_referral_bonus_to_both_users(): void
    {
        $service = app(RewardsService::class);
        $referrer = User::factory()->create();
        $code = $service->ensureReferralCode($referrer);
        $friend = User::factory()->create();

        $service->onRegistration($friend, $code);

        $this->assertSame(200, $service->balance($friend));
        $this->assertSame(100, $service->balance($referrer));
        $this->assertSame($referrer->id, $friend->fresh()->referred_by_user_id);
    }

    public function test_first_community_post_awards_once_and_rewards_referrer(): void
    {
        $service = app(RewardsService::class);
        $referrer = User::factory()->create();
        $code = $service->ensureReferralCode($referrer);
        $friend = User::factory()->create();
        $service->onRegistration($friend, $code);

        $service->onFirstCommunityPost($friend, 123);
        $service->onFirstCommunityPost($friend, 124);

        $this->assertSame(350, $service->balance($friend));
        $this->assertSame(250, $service->balance($referrer));
        $this->assertSame(1, RewardTransaction::where('action_key', 'first-community-post:' . $friend->id)->count());
        $this->assertSame(1, RewardTransaction::where('action_key', 'referral-first-post:' . $friend->id)->count());
    }

    public function test_first_motorcycle_listing_awards_points_only_once(): void
    {
        $service = app(RewardsService::class);
        $user = User::factory()->create();
        $service->onFirstMotorcycleListing($user, 10);
        $service->onFirstMotorcycleListing($user, 11);
        $this->assertSame(150, $service->balance($user));
    }

    public function test_leaderboard_excludes_refunds_admin_adjustments_and_prize_bonuses(): void
    {
        $service = app(RewardsService::class);
        $user = User::factory()->create();

        foreach ([
            ['type' => 'activity', 'points' => 120, 'key' => 'valid-activity'],
            ['type' => 'refund', 'points' => 300, 'key' => 'refund-credit'],
            ['type' => 'admin_adjustment', 'points' => 500, 'key' => 'manual-credit'],
            ['type' => 'prize', 'points' => 700, 'key' => 'previous-prize'],
        ] as $entry) {
            RewardTransaction::create([
                'user_id' => $user->id,
                'points' => $entry['points'],
                'type' => $entry['type'],
                'description' => $entry['type'],
                'action_key' => $entry['key'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $board = $service->leaderboard($user, 'week');

        $this->assertSame(120, $board['current_user']['points']);
        $this->assertSame(120, $board['entries'][0]['points']);
    }

    public function test_membership_lifetime_earned_excludes_refunds_and_manual_adjustments(): void
    {
        $service = app(RewardsService::class);
        $user = User::factory()->create();

        RewardTransaction::create(['user_id' => $user->id, 'points' => 100, 'type' => 'activity', 'description' => 'earned', 'action_key' => 'tier-earned']);
        RewardTransaction::create(['user_id' => $user->id, 'points' => 250, 'type' => 'refund', 'description' => 'refund', 'action_key' => 'tier-refund']);
        RewardTransaction::create(['user_id' => $user->id, 'points' => 400, 'type' => 'admin_adjustment', 'description' => 'manual', 'action_key' => 'tier-manual']);

        $tier = $service->membershipTier($user);

        $this->assertSame(100, $tier['lifetime_earned']);
    }

    public function test_member_can_redeem_a_gift_and_stock_and_points_are_updated_atomically(): void
    {
        $user = User::factory()->create();
        RewardTransaction::create(['user_id' => $user->id, 'points' => 1200, 'type' => 'activity', 'description' => 'earned', 'action_key' => 'gift-shop-earned']);
        $item = RewardCatalogItem::create(['title' => 'MotoHub Gloves', 'slug' => 'motohub-gloves', 'points_cost' => 500, 'stock' => 2, 'is_active' => true]);

        $response = $this->actingAs($user)->post(route('rewards.catalog.redeem', $item), ['customer_name' => $user->name, 'phone' => '555123456', 'address' => 'თბილისი, საქართველო']);

        $response->assertRedirect();
        $this->assertSame(1, $item->fresh()->stock);
        $this->assertSame(700, app(RewardsService::class)->balance($user));
        $this->assertDatabaseHas('reward_catalog_orders', ['user_id' => $user->id, 'item_id' => $item->id, 'points_spent' => 500, 'status' => 'pending']);
        $this->assertDatabaseHas('reward_transactions', ['user_id' => $user->id, 'points' => -500, 'type' => 'reward_catalog']);
    }

    public function test_member_cannot_redeem_gift_without_enough_points_or_stock(): void
    {
        $user = User::factory()->create();
        RewardTransaction::create(['user_id' => $user->id, 'points' => 100, 'type' => 'activity', 'description' => 'earned', 'action_key' => 'gift-shop-low-balance']);
        $item = RewardCatalogItem::create(['title' => 'Helmet', 'slug' => 'helmet-gift', 'points_cost' => 500, 'stock' => 1, 'is_active' => true]);

        $this->actingAs($user)->from(route('rewards.catalog'))->post(route('rewards.catalog.redeem', $item), ['customer_name' => $user->name, 'phone' => '555123456', 'address' => 'თბილისი'])->assertSessionHasErrors();
        $this->assertSame(1, $item->fresh()->stock);
        $this->assertDatabaseCount('reward_catalog_orders', 0);
    }

    public function test_rejected_gift_order_refunds_points_and_restores_stock_once(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $user = User::factory()->create();
        $item = RewardCatalogItem::create(['title' => 'Oil', 'slug' => 'oil-gift', 'points_cost' => 400, 'stock' => 0, 'is_active' => true]);
        $order = RewardCatalogOrder::create(['user_id' => $user->id, 'item_id' => $item->id, 'item_title' => 'Oil', 'customer_name' => $user->name, 'phone' => '555123456', 'address' => 'თბილისი', 'points_spent' => 400, 'status' => 'pending']);
        RewardTransaction::create(['user_id' => $user->id, 'points' => -400, 'type' => 'reward_catalog', 'description' => 'claimed', 'action_key' => 'reward-catalog-order:' . $order->id]);

        $this->actingAs($admin)->patch(route('admin.rewards.catalog.orders.update', $order), ['status' => 'rejected']);
        $this->assertSame(1, $item->fresh()->stock);
        $this->assertSame(0, app(RewardsService::class)->balance($user));
        $this->assertDatabaseHas('reward_transactions', ['user_id' => $user->id, 'points' => 400, 'type' => 'refund', 'action_key' => 'reward-catalog-refund:' . $order->id]);
    }

    public function test_shipped_gift_order_cannot_be_rejected_or_refunded(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $user = User::factory()->create();
        $item = RewardCatalogItem::create(['title' => 'Helmet', 'slug' => 'shipped-helmet', 'points_cost' => 800, 'stock' => 0, 'is_active' => true]);
        $order = RewardCatalogOrder::create(['user_id' => $user->id, 'item_id' => $item->id, 'item_title' => 'Helmet', 'customer_name' => $user->name, 'phone' => '555123456', 'address' => 'თბილისი', 'points_spent' => 800, 'status' => 'shipped']);
        RewardTransaction::create(['user_id' => $user->id, 'points' => -800, 'type' => 'reward_catalog', 'description' => 'claimed', 'action_key' => 'reward-catalog-order:' . $order->id]);

        $this->actingAs($admin)->patch(route('admin.rewards.catalog.orders.update', $order), ['status' => 'rejected'])->assertStatus(422);

        $this->assertSame(0, $item->fresh()->stock);
        $this->assertDatabaseMissing('reward_transactions', ['action_key' => 'reward-catalog-refund:' . $order->id]);
        $this->assertSame('shipped', $order->fresh()->status);
    }

    public function test_invalid_referral_code_does_not_prevent_signup_award(): void
    {
        $service = app(RewardsService::class);
        $user = User::factory()->create();

        $service->onRegistration($user, 'NOT-REAL');

        $this->assertSame(100, $service->balance($user));
        $this->assertNull($user->fresh()->referred_by_user_id);
    }
}
