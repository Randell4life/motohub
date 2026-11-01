<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RewardRedemption;
use App\Models\RewardTransaction;
use App\Models\User;
use App\Services\RewardsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RewardsApiController extends Controller
{
    public function show(Request $request, RewardsService $rewards)
    {
        $user = $request->user();
        $code = $rewards->ensureReferralCode($user);
        $dailyCheckIn = $rewards->dailyCheckIn($user);
        return response()->json([
            'balance' => $rewards->balance($user),
            'points_per_gel' => $rewards->setting('points_per_gel', 100),
            'minimum_redemption_points' => $rewards->setting('minimum_redemption_points', 500),
            'daily_checkin' => $dailyCheckIn,
            'membership_tier' => $rewards->membershipTier($user),
            'achievements' => $rewards->achievementBadges($user),
            'leaderboards' => [
                'week' => $rewards->leaderboard($user, 'week'),
                'month' => $rewards->leaderboard($user, 'month'),
            ],
            'referral_code' => $code,
            'referral_url' => url('/invite/' . $code),
            'referrals_count' => User::where('referred_by_user_id', $user->id)->count(),
            'transactions' => RewardTransaction::where('user_id', $user->id)->latest()->limit(30)->get(),
            'redemptions' => RewardRedemption::where('user_id', $user->id)->latest()->limit(15)->get(),
            'ways_to_earn' => [
                ['title' => 'რეგისტრაცია', 'points' => $rewards->setting('signup_points', 100)],
                ['title' => 'პირველი პოსტი', 'points' => $rewards->setting('first_post_points', 150)],
                ['title' => 'პირველი მოტოციკლის განცხადება', 'points' => $rewards->setting('first_listing_points', 150)],
                ['title' => 'მეგობრის მოწვევა', 'points' => $rewards->setting('referral_signup_points', 100)],
                ['title' => 'მეგობრის პირველი პოსტი', 'points' => $rewards->setting('referral_first_post_points', 150)],
            ],
        ]);
    }

    public function redeem(Request $request, RewardsService $rewards)
    {
        $data = $request->validate([
            'reward_type' => ['required', 'in:store,service'],
            'points' => ['required', 'integer', 'min:1', 'max:1000000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        $user = $request->user();
        $minimum = $rewards->setting('minimum_redemption_points', 500);
        if ($data['points'] < $minimum) throw ValidationException::withMessages(['points' => 'მინიმუმ ' . $minimum . ' ქულაა საჭირო.']);

        $redemption = DB::transaction(function () use ($data, $user, $rewards) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $balance = $rewards->balance($user);
            if ($data['points'] > $balance) throw ValidationException::withMessages(['points' => 'ბალანსი არასაკმარისია.']);
            $rate = max(1, $rewards->setting('points_per_gel', 100));
            $item = RewardRedemption::create([
                'user_id' => $user->id, 'points' => $data['points'],
                'value_gel' => round($data['points'] / $rate, 2),
                'reward_type' => $data['reward_type'], 'notes' => $data['notes'] ?? null,
            ]);
            RewardTransaction::create([
                'user_id' => $user->id, 'points' => -$data['points'], 'type' => 'redemption',
                'description' => 'ქულების გამოყენების მოთხოვნა #' . $item->id,
                'action_key' => 'redemption:' . $item->id,
            ]);
            return $item;
        });

        return response()->json(['message' => 'მოთხოვნა გაიგზავნა.', 'redemption' => $redemption], 201);
    }

    public function adminIndex(Request $request)
    {
        abort_unless((bool) $request->user()->is_admin, 403);
        return response()->json([
            'redemptions' => RewardRedemption::with('user:id,name,email')->latest()->limit(100)->get(),
            'settings' => DB::table('reward_settings')->pluck('value', 'key'),
        ]);
    }

    public function adminUpdate(Request $request, int $redemption, RewardsService $rewards)
    {
        abort_unless((bool) $request->user()->is_admin, 403);
        $data = $request->validate(['status' => ['required', 'in:approved,rejected'], 'admin_note' => ['nullable', 'string', 'max:1000']]);
        DB::transaction(function () use ($redemption, $data) {
            $item = RewardRedemption::lockForUpdate()->findOrFail($redemption);
            if ($item->status !== 'pending') abort(422, 'ეს მოთხოვნა უკვე დამუშავებულია.');
            $item->update(['status' => $data['status'], 'admin_note' => $data['admin_note'] ?? null, 'processed_at' => now()]);
            if ($data['status'] === 'rejected') {
                RewardTransaction::create([
                    'user_id' => $item->user_id, 'points' => $item->points, 'type' => 'refund',
                    'description' => 'უარყოფილი მოთხოვნის ქულების დაბრუნება #' . $item->id,
                    'action_key' => 'redemption-refund:' . $item->id,
                ]);
            }
        });
        return response()->json(['message' => 'მოთხოვნა განახლდა.']);
    }

    public function adminSettings(Request $request)
    {
        abort_unless((bool) $request->user()->is_admin, 403);
        $data = $request->validate(['settings' => ['required', 'array'], 'settings.*' => ['required', 'integer', 'min:0', 'max:1000000']]);
        foreach ($data['settings'] as $key => $value) {
            if (!in_array($key, ['points_per_gel','minimum_redemption_points','signup_points','first_post_points','referral_signup_points','referral_first_post_points','first_listing_points','daily_checkin_points','weekly_streak_bonus_points','silver_tier_points','gold_tier_points','platinum_tier_points','badge_welcome_points','badge_first_post_points','badge_first_listing_points','badge_invite_points','badge_streak_points'], true)) continue;
            DB::table('reward_settings')->updateOrInsert(['key' => $key], ['value' => (string) $value, 'updated_at' => now(), 'created_at' => now()]);
        }
        return response()->json(['message' => 'Rewards-ის წესები განახლდა.']);
    }
}
