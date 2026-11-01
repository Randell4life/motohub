<?php

namespace Tests\Feature;

use App\Models\RewardTransaction;
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
