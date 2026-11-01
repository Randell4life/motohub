<?php

namespace Tests\Feature;

use App\Models\RewardTransaction;
use App\Models\RewardPrize;
use App\Models\User;
use App\Services\RewardsCheckout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RewardsCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('reward_settings')->updateOrInsert(
            ['key' => 'points_per_gel'],
            ['value' => '100', 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function test_daily_checkin_awards_once_per_day_and_weekly_streak_bonus(): void
    {
        $user = User::factory()->create();
        DB::table('reward_settings')->updateOrInsert(['key' => 'daily_checkin_points'], ['value' => '10', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('reward_settings')->updateOrInsert(['key' => 'weekly_streak_bonus_points'], ['value' => '50', 'created_at' => now(), 'updated_at' => now()]);

        $service = app(\App\Services\RewardsService::class);
        $first = $service->dailyCheckIn($user);
        $again = $service->dailyCheckIn($user);
        $this->assertSame(10, $first['points_awarded']);
        $this->assertFalse($first['already_checked_in']);
        $this->assertSame(0, $again['points_awarded']);
        $this->assertTrue($again['already_checked_in']);
        $this->assertSame(10, $service->balance($user));

        for ($day = 1; $day <= 6; $day++) {
            \Illuminate\Support\Carbon::setTestNow(now()->addDay());
            $service->dailyCheckIn($user);
        }
        $this->assertSame(7, $service->dailyCheckIn($user)['streak']);
        $this->assertSame(120, $service->balance($user));
        \Illuminate\Support\Carbon::setTestNow();
    }

    public function test_membership_tier_uses_lifetime_earned_points_not_current_balance(): void
    {
        $user = User::factory()->create();
        DB::table('reward_settings')->updateOrInsert(['key' => 'silver_tier_points'], ['value' => '1000', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('reward_settings')->updateOrInsert(['key' => 'gold_tier_points'], ['value' => '5000', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('reward_settings')->updateOrInsert(['key' => 'platinum_tier_points'], ['value' => '15000', 'created_at' => now(), 'updated_at' => now()]);

        RewardTransaction::create(['user_id' => $user->id, 'points' => 6000, 'type' => 'test', 'description' => 'earned', 'action_key' => 'tier-earned:' . $user->id]);
        RewardTransaction::create(['user_id' => $user->id, 'points' => -5500, 'type' => 'test', 'description' => 'spent', 'action_key' => 'tier-spent:' . $user->id]);

        $tier = app(\\App\\Services\\RewardsService::class)->membershipTier($user);
        $this->assertSame('gold', $tier['key']);
        $this->assertSame(6000, $tier['lifetime_earned']);
        $this->assertSame(9000, $tier['points_to_next']);
        $this->assertSame(10, $tier['progress_percent']);
    }

    public function test_achievement_badges_unlock_and_award_bonus_points_only_once(): void
    {
        $user = User::factory()->create();
        RewardTransaction::create(['user_id' => $user->id, 'points' => 100, 'type' => 'signup', 'description' => 'signup', 'action_key' => 'signup:' . $user->id]);
        RewardTransaction::create(['user_id' => $user->id, 'points' => 150, 'type' => 'activity', 'description' => 'post', 'action_key' => 'first-community-post:' . $user->id]);

        $service = app(\\App\\Services\\RewardsService::class);
        $first = $service->achievementBadges($user);
        $second = $service->achievementBadges($user);

        $this->assertSame(2, $first['unlocked_count']);
        $this->assertSame(2, $second['unlocked_count']);
        $this->assertSame(175, $service->balance($user));
        $this->assertSame(2, RewardTransaction::where('user_id', $user->id)->where('type', 'achievement')->count());
        $this->assertTrue(collect($second['badges'])->firstWhere('key', 'welcome')['unlocked']);
        $this->assertTrue(collect($second['badges'])->firstWhere('key', 'first_post')['unlocked']);
    }

    public function test_registration_accepts_referral_code_and_rewards_both_users(): void
    {
        $referrer = User::factory()->create();
        $referrer->forceFill(['referral_code' => 'RIDER2026'])->save();

        $response = $this->post(route('register.store'), [
            'name' => 'New Rider',
            'email' => 'new-rider@example.com',
            'phone' => '+995555123456',
            'password' => 'StrongPass123!',
            'password_confirmation' => 'StrongPass123!',
            'referral_code' => ' rider2026 ',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $newUser = User::where('email', 'new-rider@example.com')->firstOrFail();
        $this->assertSame($referrer->id, $newUser->referred_by_user_id);
        $this->assertDatabaseHas('reward_transactions', [
            'user_id' => $newUser->id,
            'action_key' => 'referral-signup:' . $newUser->id,
        ]);
        $this->assertDatabaseHas('reward_transactions', [
            'user_id' => $referrer->id,
            'action_key' => 'referral-signup-referrer:' . $newUser->id,
        ]);
    }

    public function test_registration_rejects_unknown_referral_code(): void
    {
        $response = $this->from(route('register'))->post(route('register.store'), [
            'name' => 'Unknown Referral Rider',
            'email' => 'unknown-referral@example.com',
            'password' => 'StrongPass123!',
            'password_confirmation' => 'StrongPass123!',
            'referral_code' => 'NOTREAL123',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('referral_code');
        $this->assertDatabaseMissing('users', ['email' => 'unknown-referral@example.com']);
    }

    public function test_mobile_registration_accepts_referral_code_and_rejects_unknown_code(): void
    {
        $referrer = User::factory()->create();
        $referrer->forceFill(['referral_code' => 'MOBILE2026'])->save();

        $response = $this->postJson('/api/mobile/register', [
            'name' => 'Mobile Rider',
            'email' => 'mobile-rider@example.com',
            'password' => 'StrongPass123!',
            'password_confirmation' => 'StrongPass123!',
            'referral_code' => 'mobile2026',
        ]);

        $response->assertCreated()->assertJsonPath('user.email', 'mobile-rider@example.com');
        $newUser = User::where('email', 'mobile-rider@example.com')->firstOrFail();
        $this->assertSame($referrer->id, $newUser->referred_by_user_id);

        $invalid = $this->postJson('/api/mobile/register', [
            'name' => 'Invalid Mobile Rider',
            'email' => 'invalid-mobile@example.com',
            'password' => 'StrongPass123!',
            'password_confirmation' => 'StrongPass123!',
            'referral_code' => 'UNKNOWN123',
        ]);
        $invalid->assertUnprocessable()->assertJsonValidationErrors('referral_code');
        $this->assertDatabaseMissing('users', ['email' => 'invalid-mobile@example.com']);
    }

    public function test_weekly_and_monthly_leaderboards_rank_earned_points(): void
    {
        $first = User::factory()->create(['name' => 'Top Rider']);
        $second = User::factory()->create(['name' => 'Second Rider']);
        RewardTransaction::create(['user_id' => $first->id, 'points' => 300, 'type' => 'test', 'description' => 'weekly', 'action_key' => 'leaderboard-first:' . $first->id]);
        RewardTransaction::create(['user_id' => $second->id, 'points' => 150, 'type' => 'test', 'description' => 'weekly', 'action_key' => 'leaderboard-second:' . $second->id]);
        RewardTransaction::create(['user_id' => $first->id, 'points' => -100, 'type' => 'test', 'description' => 'spent', 'action_key' => 'leaderboard-spent:' . $first->id]);

        $service = app(\\App\\Services\\RewardsService::class);
        $weekly = $service->leaderboard($first, 'week');
        $monthly = $service->leaderboard($first, 'month');

        $this->assertSame('Top Rider', $weekly['entries'][0]['name']);
        $this->assertSame(300, $weekly['entries'][0]['points']);
        $this->assertSame(1, $weekly['current_user']['rank']);
        $this->assertSame(1, $monthly['current_user']['rank']);
    }

    public function test_admin_can_award_a_leaderboard_prize_and_points_are_credited_once(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $winner = User::factory()->create(['name' => 'Weekly Champion']);
        RewardTransaction::create([
            'user_id' => $winner->id,
            'points' => 500,
            'type' => 'test',
            'description' => 'weekly leaderboard',
            'action_key' => 'prize-leaderboard:' . $winner->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.rewards.prizes.award'), [
            'period_type' => 'week',
            'rank' => 1,
            'winner_user_id' => $winner->id,
            'title' => 'კვირის ჩემპიონი',
            'description' => 'MotoHub weekly winner',
            'points_awarded' => 250,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reward_prizes', [
            'period_type' => 'week',
            'rank' => 1,
            'winner_user_id' => $winner->id,
            'title' => 'კვირის ჩემპიონი',
            'points_awarded' => 250,
        ]);
        $this->assertDatabaseHas('reward_transactions', [
            'user_id' => $winner->id,
            'points' => 250,
            'type' => 'prize',
        ]);
        $this->assertSame(750, (int) RewardTransaction::where('user_id', $winner->id)->sum('points'));
    }

    public function test_admin_cannot_award_a_prize_to_a_user_outside_selected_rank(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $first = User::factory()->create();
        $second = User::factory()->create();
        RewardTransaction::create(['user_id' => $first->id, 'points' => 500, 'type' => 'test', 'description' => 'first', 'action_key' => 'prize-rank-first:' . $first->id]);
        RewardTransaction::create(['user_id' => $second->id, 'points' => 200, 'type' => 'test', 'description' => 'second', 'action_key' => 'prize-rank-second:' . $second->id]);

        $response = $this->actingAs($admin)->from(route('admin.rewards.index'))->post(route('admin.rewards.prizes.award'), [
            'period_type' => 'week',
            'rank' => 1,
            'winner_user_id' => $second->id,
            'title' => 'არასწორი გამარჯვებული',
            'points_awarded' => 100,
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('reward_prizes', 0);
        $this->assertDatabaseMissing('reward_transactions', ['type' => 'prize', 'user_id' => $second->id]);
    }

    public function test_points_discount_is_capped_at_thirty_percent(): void
    {
        $user = User::factory()->create();
        RewardTransaction::create([
            'user_id' => $user->id, 'points' => 5000, 'type' => 'test',
            'description' => 'test balance', 'action_key' => 'test-balance:' . $user->id,
        ]);

        $result = app(RewardsCheckout::class)->apply($user, 100, 9000, 'test-purchase:' . $user->id, 'test purchase');

        $this->assertSame(3000, $result['points']);
        $this->assertSame(30.0, $result['discount']);
        $this->assertSame(70.0, $result['total']);
        $this->assertSame(2000, (int) RewardTransaction::where('user_id', $user->id)->sum('points'));
    }

    public function test_checkout_rejects_spending_more_points_than_balance(): void
    {
        $user = User::factory()->create();
        try {
            app(RewardsCheckout::class)->apply($user, 100, 1000, 'test-insufficient:' . $user->id, 'test purchase');
            $this->fail('Expected insufficient points validation error.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('points', $exception->errors());
        }
    }
}
