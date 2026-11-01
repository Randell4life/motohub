<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\RewardTransaction;
use App\Models\StoreOrder;
use App\Services\RewardsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StoreCheckoutController extends Controller
{
    public function store(Request $request, RewardsService $rewards)
    {
        $data = $request->validate([
            'part_id' => ['required','integer','exists:parts,id'],
            'quantity' => ['required','integer','min:1','max:20'],
            'points' => ['nullable','integer','min:0','max:1000000'],
            'customer_name' => ['required','string','max:160'],
            'phone' => ['required','string','max:40'],
            'address' => ['required','string','max:1000'],
            'notes' => ['nullable','string','max:1500'],
        ]);
        $user = $request->user();
        $order = DB::transaction(function () use ($data, $user, $rewards) {
            $part = Part::whereKey($data['part_id'])->lockForUpdate()->where('is_active', true)->firstOrFail();
            if ($part->stock < $data['quantity']) throw ValidationException::withMessages(['quantity' => 'მარაგში საკმარისი რაოდენობა არ არის.']);
            $subtotal = round((float) $part->price * $data['quantity'], 2);
            $rate = max(1, $rewards->setting('points_per_gel', 100));
            $maxDiscount = round($subtotal * 0.30, 2);
            $maxPoints = (int) floor($maxDiscount * $rate);
            $requestedPoints = (int) ($data['points'] ?? 0);
            if ($requestedPoints > $maxPoints) {
                throw ValidationException::withMessages(['points' => 'ამ შეკვეთაზე მაქსიმუმ ' . $maxPoints . ' ქულის გამოყენება შეგიძლია (ღირებულების 30%).']);
            }
            $points = $requestedPoints;
            if ($points > 0) {
                $lockedUser = \App\Models\User::whereKey($user->id)->lockForUpdate()->firstOrFail();
                $availablePoints = $rewards->balance($lockedUser);
                if ($points > $availablePoints) {
                    throw ValidationException::withMessages(['points' => 'ბალანსი არასაკმარისია. ხელმისაწვდომია ' . $availablePoints . ' ქულა.']);
                }
            }
            $discount = min($maxDiscount, round($points / $rate, 2));
            $order = StoreOrder::create([
                'user_id' => $user->id, 'order_number' => 'MH-' . strtoupper(Str::random(10)),
                'subtotal' => $subtotal, 'points_used' => $points, 'points_discount' => $discount,
                'total' => max(0, round($subtotal - $discount, 2)),
                'customer_name' => $data['customer_name'], 'phone' => $data['phone'],
                'address' => $data['address'], 'notes' => $data['notes'] ?? null,
            ]);
            $order->items()->create(['part_id' => $part->id, 'title' => $part->title, 'quantity' => $data['quantity'], 'unit_price' => $part->price, 'line_total' => $subtotal]);
            $part->decrement('stock', $data['quantity']);
            if ($points > 0) RewardTransaction::create([
                'user_id' => $user->id, 'points' => -$points, 'type' => 'store_purchase',
                'description' => 'მაღაზიის შეკვეთა ' . $order->order_number,
                'action_key' => 'store-order:' . $order->id,
                'metadata' => ['order_id' => $order->id, 'discount_gel' => $discount],
            ]);
            return $order;
        });
        if ($request->expectsJson()) return response()->json(['message' => 'შეკვეთა მიღებულია.', 'order' => $order->load('items'), 'rewards_balance' => $rewards->balance($user)], 201);
        return redirect()->route('parts.show', ['source' => 'store', 'id' => $data['part_id']])->with('success', 'შეკვეთა მიღებულია. ნომერი: ' . $order->order_number);
    }

    public function index(Request $request)
    {
        return response()->json(['orders' => StoreOrder::with('items')->where('user_id', $request->user()->id)->latest()->limit(30)->get()]);
    }
}
