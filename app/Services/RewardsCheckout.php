<?php

namespace App\Services;

use App\Models\RewardTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RewardsCheckout
{
    public function apply(User $user, float $amount, int $requestedPoints, string $actionKey, string $description, array $metadata = []): array
    {
        $rate = max(1, app(RewardsService::class)->setting('points_per_gel', 100));
        $maxDiscount = round(max(0, $amount) * 0.30, 2);
        $maxPoints = (int) floor($maxDiscount * $rate);
        $points = min(max(0, $requestedPoints), $maxPoints);
        if ($points > 0) {
            $locked = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $balance = (int) RewardTransaction::where('user_id', $locked->id)->sum('points');
            if ($points > $balance) throw ValidationException::withMessages(['points' => 'ქულების ბალანსი არასაკმარისია.']);
            RewardTransaction::create([
                'user_id' => $user->id, 'points' => -$points, 'type' => 'purchase',
                'description' => $description, 'action_key' => $actionKey,
                'metadata' => $metadata + ['discount_gel' => round($points / $rate, 2)],
            ]);
        }
        return ['points' => $points, 'discount' => round($points / $rate, 2), 'total' => max(0, round($amount - ($points / $rate), 2)), 'max_discount' => $maxDiscount];
    }
}
