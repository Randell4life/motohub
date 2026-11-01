<?php

namespace App\Http\Controllers;

use App\Models\RewardRedemption;
use App\Models\RewardTransaction;
use App\Models\User;
use App\Services\RewardsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RewardsController extends Controller
{
    public function index(Request $request, RewardsService $rewards)
    {
        $user = $request->user();
        $code = $rewards->ensureReferralCode($user);
        $balance = $rewards->balance($user);
        $transactions = RewardTransaction::where('user_id', $user->id)->latest()->limit(30)->get();
        $referrals = User::where('referred_by_user_id', $user->id)->withCount('communityPosts')->latest()->limit(20)->get(['id', 'name', 'created_at']);

        return view('rewards.index', compact('user', 'code', 'balance', 'transactions', 'referrals'));
    }

    public function redeem(Request $request, RewardsService $rewards)
    {
        $data = $request->validate([
            'reward_type' => ['required', 'in:store,service'],
            'points' => ['required', 'integer', 'min:500', 'max:1000000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        $minimum = $rewards->setting('minimum_redemption_points', 500);
        $user = $request->user();

        $redemption = DB::transaction(function () use ($data, $minimum, $user, $rewards) {
            $balance = $rewards->balance($user);
            if ($data['points'] < $minimum || $data['points'] > $balance) {
                throw ValidationException::withMessages(['points' => 'ქულების რაოდენობა არასწორია ან ბალანსი არასაკმარისია.']);
            }
            $rate = max(1, $rewards->setting('points_per_gel', 100));
            $redemption = RewardRedemption::create([
                'user_id' => $user->id, 'points' => $data['points'],
                'value_gel' => round($data['points'] / $rate, 2),
                'reward_type' => $data['reward_type'], 'notes' => $data['notes'] ?? null,
            ]);
            RewardTransaction::create([
                'user_id' => $user->id, 'points' => -$data['points'], 'type' => 'redemption',
                'description' => 'ქულების გამოყენების მოთხოვნა #' . $redemption->id,
                'action_key' => 'redemption:' . $redemption->id,
            ]);
            return $redemption;
        });

        return back()->with('success', 'მოთხოვნა მიღებულია. MotoHub-ის გუნდი დაგიკავშირდება.');
    }
}
