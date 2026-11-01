<?php

namespace App\Http\Controllers;

use App\Models\RewardRedemption;
use App\Models\RewardPrize;
use App\Models\RewardTransaction;
use App\Models\StoreOrder;
use App\Models\User;
use App\Services\RewardsService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RewardsAdminController extends Controller
{
    private function authorizeAdmin(Request $request): void
    {
        abort_unless((bool) $request->user()?->is_admin, 403);
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin($request);

        $redemptionsQuery = RewardRedemption::with('user:id,name,email')
            ->when($request->filled('status') && in_array($request->input('status'), ['pending', 'approved', 'rejected'], true),
                fn ($query) => $query->where('status', $request->input('status')))
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest();

        $redemptions = $redemptionsQuery->paginate(30)->withQueryString();
        $storeOrders = StoreOrder::with('user:id,name,email', 'items:id,store_order_id,title,quantity')
            ->where('points_used', '>', 0)
            ->latest()
            ->paginate(20, ['*'], 'orders_page')
            ->withQueryString();

        $settings = DB::table('reward_settings')->pluck('value', 'key');
        $prizes = RewardPrize::with('winner:id,name,email', 'admin:id,name')->latest('period_start')->latest('rank')->limit(60)->get();
        $weeklyCandidates = app(RewardsService::class)->leaderboard($request->user(), 'week')['entries'];
        $monthlyCandidates = app(RewardsService::class)->leaderboard($request->user(), 'month')['entries'];
        $totals = [
            'issued' => (int) DB::table('reward_transactions')->where('points', '>', 0)->sum('points'),
            'redeemed' => (int) DB::table('reward_transactions')->where('type', 'redemption')->sum(DB::raw('ABS(points)')),
            'store_used' => (int) StoreOrder::sum('points_used'),
            'pending' => RewardRedemption::where('status', 'pending')->count(),
        ];

        $users = User::query()
            ->select(['id', 'name', 'email'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim((string) $request->input('q'));
                $query->where(function ($sub) use ($term) {
                    $sub->where('name', 'like', '%' . $term . '%')
                        ->orWhere('email', 'like', '%' . $term . '%');
                    if (ctype_digit($term)) {
                        $sub->orWhere('id', (int) $term);
                    }
                });
            })
            ->orderBy('name')
            ->paginate(20, ['*'], 'users_page')
            ->withQueryString();

        $balances = RewardTransaction::query()
            ->whereIn('user_id', $users->getCollection()->pluck('id'))
            ->selectRaw('user_id, SUM(points) as balance')
            ->groupBy('user_id')
            ->pluck('balance', 'user_id');

        return view('rewards.admin', compact('redemptions', 'storeOrders', 'settings', 'totals', 'users', 'balances', 'prizes', 'weeklyCandidates', 'monthlyCandidates'));
    }

    public function update(Request $request, RewardRedemption $redemption)
    {
        $this->authorizeAdmin($request);
        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($redemption, $data) {
            $item = RewardRedemption::lockForUpdate()->findOrFail($redemption->id);
            abort_unless($item->status === 'pending', 422, 'მოთხოვნა უკვე დამუშავებულია.');

            $item->update([
                'status' => $data['status'],
                'admin_note' => $data['admin_note'] ?? null,
                'processed_at' => now(),
            ]);

            if ($data['status'] === 'rejected') {
                $refundKey = 'redemption-refund:' . $item->id;
                if (!RewardTransaction::where('action_key', $refundKey)->exists()) {
                    RewardTransaction::create([
                        'user_id' => $item->user_id,
                        'points' => $item->points,
                        'type' => 'refund',
                        'description' => 'უარყოფილი მოთხოვნის ქულების დაბრუნება #' . $item->id,
                        'action_key' => $refundKey,
                    ]);
                }
            }
        });

        return back()->with('success', 'მოთხოვნა განახლდა.');
    }

    public function adjust(Request $request, User $user)
    {
        $this->authorizeAdmin($request);
        $data = $request->validate([
            'operation' => ['required', 'in:add,subtract'],
            'points' => ['required', 'integer', 'min:1', 'max:1000000'],
            'reason' => ['required', 'string', 'min:3', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $user, $data) {
            $lockedUser = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $balance = (int) RewardTransaction::where('user_id', $lockedUser->id)->sum('points');

            if ($data['operation'] === 'subtract') {
                abort_if($data['points'] > $balance, 422, 'მომხმარებელს ამდენი ქულა არ აქვს.');
            }

            $points = $data['operation'] === 'add' ? (int) $data['points'] : -(int) $data['points'];
            RewardTransaction::create([
                'user_id' => $lockedUser->id,
                'points' => $points,
                'type' => 'admin_adjustment',
                'description' => 'ადმინისტრატორის კორექტირება: ' . trim($data['reason']),
                'action_key' => 'admin-adjustment:' . Str::uuid(),
                'metadata' => [
                    'admin_id' => $request->user()->id,
                    'operation' => $data['operation'],
                ],
            ]);
        });

        return back()->with('success', 'მომხმარებლის ქულების ბალანსი განახლდა.');
    }

    public function awardPrize(Request $request, RewardsService $rewards)
    {
        $this->authorizeAdmin($request);
        $data = $request->validate([
            'period_type' => ['required', 'in:week,month'],
            'rank' => ['required', 'integer', 'between:1,10'],
            'winner_user_id' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'points_awarded' => ['nullable', 'integer', 'min:0', 'max:1000000'],
        ]);

        $periodStart = $data['period_type'] === 'month' ? now()->startOfMonth()->toDateString() : now()->startOfWeek()->toDateString();
        $board = $rewards->leaderboard($request->user(), $data['period_type']);
        $winner = collect($board['entries'])->firstWhere('rank', (int) $data['rank']);
        if (!$winner || (int) $winner['user_id'] !== (int) $data['winner_user_id']) {
            throw ValidationException::withMessages([
                'winner_user_id' => 'არჩეული მომხმარებელი ამ პერიოდის ამ ადგილზე არ არის.',
            ]);
        }

        DB::transaction(function () use ($request, $data, $periodStart, $winner) {
            $prize = RewardPrize::create([
                'period_type' => $data['period_type'],
                'period_start' => $periodStart,
                'rank' => $data['rank'],
                'winner_user_id' => $winner['user_id'],
                'awarded_by' => $request->user()->id,
                'title' => trim($data['title']),
                'description' => $data['description'] ?? null,
                'points_awarded' => (int) ($data['points_awarded'] ?? 0),
                'awarded_at' => now(),
            ]);

            if ($prize->points_awarded > 0) {
                RewardTransaction::create([
                    'user_id' => $prize->winner_user_id,
                    'points' => $prize->points_awarded,
                    'type' => 'prize',
                    'description' => 'Rewards პრიზი: ' . $prize->title,
                    'action_key' => 'leaderboard-prize:' . $prize->period_type . ':' . $prize->period_start->toDateString() . ':' . $prize->rank,
                    'metadata' => ['prize_id' => $prize->id, 'period_type' => $prize->period_type, 'rank' => $prize->rank],
                ]);
            }
        });

        return back()->with('success', 'პრიზი გადაეცა გამარჯვებულს.');
    }

    public function settings(Request $request, RewardsService $rewards)
    {
        $this->authorizeAdmin($request);
        $data = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*' => ['required', 'integer', 'min:0', 'max:1000000'],
        ]);

        $allowed = [
            'points_per_gel', 'minimum_redemption_points', 'signup_points',
            'first_post_points', 'first_listing_points', 'referral_signup_points',
            'referral_first_post_points', 'daily_checkin_points',
            'weekly_streak_bonus_points', 'silver_tier_points',
            'gold_tier_points', 'platinum_tier_points',
            'badge_welcome_points', 'badge_first_post_points',
            'badge_first_listing_points', 'badge_invite_points', 'badge_streak_points',
        ];

        $current = DB::table('reward_settings')->pluck('value', 'key');
        $silver = (int) ($data['settings']['silver_tier_points'] ?? $current['silver_tier_points'] ?? 1000);
        $gold = (int) ($data['settings']['gold_tier_points'] ?? $current['gold_tier_points'] ?? 5000);
        $platinum = (int) ($data['settings']['platinum_tier_points'] ?? $current['platinum_tier_points'] ?? 15000);
        if ($silver < 1 || $gold <= $silver || $platinum <= $gold) {
            return back()->withErrors(['settings' => 'დონეების ზღვარი უნდა იზრდებოდეს: Silver < Gold < Platinum.'])->withInput();
        }

        foreach ($data['settings'] as $key => $value) {
            if (!in_array($key, $allowed, true)) {
                continue;
            }

            DB::table('reward_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => (string) $value, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        return back()->with('success', 'Rewards-ის წესები განახლდა.');
    }
}
