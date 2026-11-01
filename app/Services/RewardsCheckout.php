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
    public function refundServiceBooking(ServiceBooking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $item = ServiceBooking::whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if ((int) $item->points_used <= 0) return;
            $key = 'service-booking-refund:' . $item->id;
            if (RewardTransaction::where('action_key', $key)->exists()) return;
            RewardTransaction::create([
                'user_id' => $item->user_id,
                'points' => (int) $item->points_used,
                'type' => 'refund',
                'description' => 'გაუქმებული სერვისის ჯავშნის ქულების დაბრუნება #' . $item->id,
                'action_key' => $key,
                'metadata' => ['service_booking_id' => $item->id],
            ]);
        });
    }

}
