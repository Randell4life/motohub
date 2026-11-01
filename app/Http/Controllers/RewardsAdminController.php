<?php

namespace App\Http\Controllers;

use App\Models\RewardRedemption;
use App\Services\RewardsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RewardsAdminController extends Controller
{
    private function authorizeAdmin(Request $request): void
    {
        abort_unless((bool) $request->user()?->is_admin, 403);
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin($request);
        $redemptions = RewardRedemption::with('user:id,name,email')->latest()->paginate(30);
        $settings = DB::table('reward_settings')->pluck('value', 'key');
        $totals = [
            'issued' => (int) DB::table('reward_transactions')->where('points', '>', 0)->sum('points'),
            'redeemed' => (int) DB::table('reward_transactions')->where('type', 'redemption')->sum(DB::raw('ABS(points)')),
            'pending' => RewardRedemption::where('status', 'pending')->count(),
        ];
        return view('rewards.admin', compact('redemptions', 'settings', 'totals'));
    }

    public function update(Request $request, RewardRedemption $redemption)
    {
        $this->authorizeAdmin($request);
        $data = $request->validate(['status' => ['required', 'in:approved,rejected'], 'admin_note' => ['nullable', 'string', 'max:1000']]);
        DB::transaction(function () use ($redemption, $data) {
            $item = RewardRedemption::lockForUpdate()->findOrFail($redemption->id);
            abort_unless($item->status === 'pending', 422, 'მოთხოვნა უკვე დამუშავებულია.');
            $item->update(['status' => $data['status'], 'admin_note' => $data['admin_note'] ?? null, 'processed_at' => now()]);
            if ($data['status'] === 'rejected') {
                DB::table('reward_transactions')->insert([
                    'user_id' => $item->user_id, 'points' => $item->points, 'type' => 'refund',
                    'description' => 'უარყოფილი მოთხოვნის ქულების დაბრუნება #' . $item->id,
                    'action_key' => 'redemption-refund:' . $item->id,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        });
        return back()->with('success', 'მოთხოვნა განახლდა.');
    }

    public function settings(Request $request, RewardsService $rewards)
    {
        $this->authorizeAdmin($request);
        $data = $request->validate(['settings' => ['required', 'array'], 'settings.*' => ['required', 'integer', 'min:0', 'max:1000000']]);
        $allowed = ['points_per_gel','minimum_redemption_points','signup_points','first_post_points','first_listing_points','referral_signup_points','referral_first_post_points'];
        foreach ($data['settings'] as $key => $value) {
            if (!in_array($key, $allowed, true)) continue;
            DB::table('reward_settings')->updateOrInsert(['key' => $key], ['value' => (string) $value, 'updated_at' => now(), 'created_at' => now()]);
        }
        return back()->with('success', 'Rewards-ის წესები განახლდა.');
    }
}
