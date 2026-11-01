<?php

namespace Tests\Feature;

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

    public function test_invalid_referral_code_does_not_prevent_signup_award(): void
    {
        $service = app(RewardsService::class);
        $user = User::factory()->create();

        $service->onRegistration($user, 'NOT-REAL');

        $this->assertSame(100, $service->balance($user));
        $this->assertNull($user->fresh()->referred_by_user_id);
    }
}
