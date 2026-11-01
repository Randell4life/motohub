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

    /** Return the user's permanent membership tier based on lifetime positive points earned. */
    public function membershipTier(User $user): array
    {
        $earned = (int) RewardTransaction::where('user_id', $user->id)->where('points', '>', 0)->sum('points');
        $tiers = [
            ['key' => 'bronze', 'name' => 'Bronze', 'name_ka' => 'ბრინჯაო', 'threshold' => 0, 'next_setting' => 'silver_tier_points'],
            ['key' => 'silver', 'name' => 'Silver', 'name_ka' => 'ვერცხლი', 'threshold' => $this->setting('silver_tier_points', 1000), 'next_setting' => 'gold_tier_points'],
            ['key' => 'gold', 'name' => 'Gold', 'name_ka' => 'ოქრო', 'threshold' => $this->setting('gold_tier_points', 5000), 'next_setting' => 'platinum_tier_points'],
            ['key' => 'platinum', 'name' => 'Platinum', 'name_ka' => 'პლატინა', 'threshold' => $this->setting('platinum_tier_points', 15000), 'next_setting' => null],
        ];
        $tiers[2]['threshold'] = max($tiers[1]['threshold'] + 1, $tiers[2]['threshold']);
        $tiers[3]['threshold'] = max($tiers[2]['threshold'] + 1, $tiers[3]['threshold']);

        $currentIndex = 0;
        foreach ($tiers as $index => $tier) {
            if ($earned >= $tier['threshold']) $currentIndex = $index;
        }
        $current = $tiers[$currentIndex];
        $next = $tiers[$currentIndex + 1] ?? null;
        $floor = $current['threshold'];
        $ceiling = $next['threshold'] ?? $floor;
        $progress = $next ? (int) min(100, floor((($earned - $floor) / max(1, $ceiling - $floor)) * 100)) : 100;

        return [
            'key' => $current['key'],
            'name' => $current['name'],
            'name_ka' => $current['name_ka'],
            'lifetime_earned' => $earned,
            'threshold' => $current['threshold'],
            'next_tier' => $next ? ['key' => $next['key'], 'name' => $next['name'], 'name_ka' => $next['name_ka'], 'threshold' => $next['threshold']] : null,
            'points_to_next' => $next ? max(0, $next['threshold'] - $earned) : 0,
            'progress_percent' => $progress,
            'tiers' => array_map(fn ($tier) => ['key' => $tier['key'], 'name' => $tier['name'], 'name_ka' => $tier['name_ka'], 'threshold' => $tier['threshold']], $tiers),
        ];
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

    /** Evaluate one-time achievements from trusted Rewards ledger events. */
    public function achievementBadges(User $user): array
    {
        $has = fn (string $key) => RewardTransaction::where('user_id', $user->id)->where('action_key', $key)->exists();
        $referrals = RewardTransaction::where('user_id', $user->id)->where('action_key', 'like', 'referral-signup-referrer:%')->count();

        $streak = 0;
        $date = now()->startOfDay();
        for ($i = 0; $i < 7; $i++) {
            if (!$has('daily-checkin:' . $user->id . ':' . $date->toDateString())) break;
            $streak++;
            $date->subDay();
        }
        if ($streak === 0 && !$has('daily-checkin:' . $user->id . ':' . now()->toDateString())) {
            $date = now()->subDay()->startOfDay();
            for ($i = 0; $i < 7; $i++) {
                if (!$has('daily-checkin:' . $user->id . ':' . $date->toDateString())) break;
                $streak++;
                $date->subDay();
            }
        }

        $definitions = [
            ['key' => 'welcome', 'title' => 'პირველი ნაბიჯი', 'description' => 'შექმენი MotoHub ანგარიში', 'icon' => '🏁', 'progress' => $has('signup:' . $user->id) ? 1 : 0, 'target' => 1, 'points' => $this->setting('badge_welcome_points', 25)],
            ['key' => 'first_post', 'title' => 'ხმის ამოღება', 'description' => 'გამოაქვეყნე პირველი საზოგადოებრივი პოსტი', 'icon' => '💬', 'progress' => $has('first-community-post:' . $user->id) ? 1 : 0, 'target' => 1, 'points' => $this->setting('badge_first_post_points', 50)],
            ['key' => 'first_listing', 'title' => 'მოტო მარკეტერი', 'description' => 'განათავსე პირველი მოტოციკლის განცხადება', 'icon' => '🏍️', 'progress' => $has('first-motorcycle-listing:' . $user->id) ? 1 : 0, 'target' => 1, 'points' => $this->setting('badge_first_listing_points', 50)],
            ['key' => 'invite_one', 'title' => 'მეგობრების გამაერთიანებელი', 'description' => 'მოიწვიე ერთი მეგობარი, რომელიც დარეგისტრირდება', 'icon' => '🤝', 'progress' => min(1, $referrals), 'target' => 1, 'points' => $this->setting('badge_invite_points', 75)],
            ['key' => 'streak_seven', 'title' => 'შვიდდღიანი მრბოლელი', 'description' => 'შედი MotoHub-ში ზედიზედ 7 დღე', 'icon' => '🔥', 'progress' => min(7, $streak), 'target' => 7, 'points' => $this->setting('badge_streak_points', 100)],
        ];

        foreach ($definitions as &$badge) {
            $badge['unlocked'] = $badge['progress'] >= $badge['target'];
            $badge['rewarded'] = false;
            if ($badge['unlocked']) {
                $badge['rewarded'] = $this->award(
                    $user,
                    $badge['points'],
                    'achievement',
                    'მიღწევა: ' . $badge['title'],
                    'achievement-badge:' . $user->id . ':' . $badge['key'],
                    ['badge' => $badge['key']]
                );
                $badge['rewarded'] = $badge['rewarded'] || $has('achievement-badge:' . $user->id . ':' . $badge['key']);
            }
            $badge['progress_percent'] = (int) min(100, floor(($badge['progress'] / max(1, $badge['target'])) * 100));
        }
        unset($badge);

        return [
            'unlocked_count' => count(array_filter($definitions, fn ($badge) => $badge['unlocked'])),
            'total_count' => count($definitions),
            'badges' => $definitions,
        ];
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
