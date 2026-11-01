<?php

namespace App\Services;

use App\Models\RewardTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RewardsService
{
    public function ensureReferralCode(User $user): string
    {
        if ($user->referral_code) return $user->referral_code;

        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        $user->forceFill(['referral_code' => $code])->save();
        return $code;
    }

    public function setting(string $key, int $default): int
    {
        return max(0, (int) (DB::table('reward_settings')->where('key', $key)->value('value') ?? $default));
    }

    public function balance(User $user): int
    {
        return (int) RewardTransaction::where('user_id', $user->id)->sum('points');
    }

    public function award(User $user, int $points, string $type, string $description, string $actionKey, array $metadata = []): bool
    {
        if ($points <= 0) return false;

        return DB::transaction(function () use ($user, $points, $type, $description, $actionKey, $metadata) {
            if (RewardTransaction::where('action_key', $actionKey)->exists()) return false;
            RewardTransaction::create([
                'user_id' => $user->id, 'points' => $points, 'type' => $type,
                'description' => $description, 'action_key' => $actionKey, 'metadata' => $metadata,
            ]);
            return true;
        });
    }

    /** Award one check-in per day and a bonus every seventh consecutive day. */
    public function dailyCheckIn(User $user): array
    {
        return DB::transaction(function () use ($user) {
            $lockedUser = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $today = now()->toDateString();
            $todayKey = 'daily-checkin:' . $lockedUser->id . ':' . $today;
            $alreadyCheckedIn = RewardTransaction::where('action_key', $todayKey)->exists();

            $streak = 0;
            $date = now()->startOfDay();
            if (!$alreadyCheckedIn) {
                $date = $date->subDay();
            }
            for ($i = 0; $i < 366; $i++) {
                $key = 'daily-checkin:' . $lockedUser->id . ':' . $date->toDateString();
                if (!RewardTransaction::where('action_key', $key)->exists()) break;
                $streak++;
                $date->subDay();
            }

            $awarded = 0;
            $bonus = 0;
            if (!$alreadyCheckedIn) {
                $base = $this->setting('daily_checkin_points', 10);
                $nextStreak = $streak + 1;
                $bonus = $nextStreak % 7 === 0 ? $this->setting('weekly_streak_bonus_points', 50) : 0;
                $awarded = $base + $bonus;
                if ($awarded > 0) {
                    RewardTransaction::create([
                        'user_id' => $lockedUser->id,
                        'points' => $awarded,
                        'type' => 'daily_checkin',
                        'description' => $bonus > 0 ? 'ყოველდღიური შესვლა · ' . $nextStreak . '-დღიანი სერია · ბონუსი +' . $bonus : 'ყოველდღიური შესვლა MotoHub-ში',
                        'action_key' => $todayKey,
                        'metadata' => ['streak' => $nextStreak, 'bonus_points' => $bonus],
                    ]);
                }
                $streak = $nextStreak;
            }

            return [
                'checked_in' => true,
                'already_checked_in' => $alreadyCheckedIn,
                'streak' => $streak,
                'points_awarded' => $awarded,
                'bonus_points' => $bonus,
                'daily_points' => $this->setting('daily_checkin_points', 10),
                'weekly_bonus_points' => $this->setting('weekly_streak_bonus_points', 50),
            ];
        });
    }

    public function onRegistration(User $user, ?string $referralCode): void
    {
        $this->ensureReferralCode($user);
        $this->award($user, $this->setting('signup_points', 100), 'signup', 'MotoHub-ში რეგისტრაცია', 'signup:' . $user->id);

        if (!$referralCode) return;
        $referrer = User::where('referral_code', strtoupper(trim($referralCode)))->where('id', '!=', $user->id)->first();
        if (!$referrer) return;

        $user->forceFill(['referred_by_user_id' => $referrer->id])->save();
        $this->award($user, $this->setting('referral_signup_points', 100), 'referral', 'მეგობრის მოწვევით რეგისტრაცია', 'referral-signup:' . $user->id, ['referrer_id' => $referrer->id]);
        $this->award($referrer, $this->setting('referral_signup_points', 100), 'referral', 'მეგობრის მოწვევა', 'referral-signup-referrer:' . $user->id, ['referred_user_id' => $user->id]);
    }

    public function onFirstMotorcycleListing(User $user, int $motorcycleId): void
    {
        $this->award($user, $this->setting('first_listing_points', 150), 'activity', 'პირველი მოტოციკლის განცხადების დამატება', 'first-motorcycle-listing:' . $user->id, ['motorcycle_id' => $motorcycleId]);
    }

    public function onFirstCommunityPost(User $user, int $postId): void
    {
        $this->award($user, $this->setting('first_post_points', 150), 'activity', 'პირველი პოსტის გამოქვეყნება', 'first-community-post:' . $user->id, ['post_id' => $postId]);
        if ($user->referred_by_user_id) {
            $referrer = User::find($user->referred_by_user_id);
            if ($referrer) $this->award($referrer, $this->setting('referral_first_post_points', 150), 'referral', 'მოწვეულმა მეგობარმა პირველი პოსტი გამოაქვეყნა', 'referral-first-post:' . $user->id, ['referred_user_id' => $user->id]);
        }
    }
}
