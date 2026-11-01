<?php

namespace App\Http\Controllers;

use App\Models\RewardCatalogItem;
use App\Models\RewardCatalogOrder;
use App\Models\RewardTransaction;
use App\Models\User;
use App\Services\RewardsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RewardCatalogController extends Controller
{
    public function index(Request $request, RewardsService $rewards)
    {
        $items = RewardCatalogItem::where('is_active', true)->where('stock', '>', 0)
            ->orderBy('sort_order')->orderBy('points_cost')->get();
        $orders = RewardCatalogOrder::where('user_id', $request->user()->id)->latest()->limit(30)->get();
        return view('rewards.catalog', ['items' => $items, 'orders' => $orders, 'balance' => $rewards->balance($request->user())]);
    }

    public function redeem(Request $request, RewardCatalogItem $item, RewardsService $rewards)
    {
        $order = DB::transaction(function () use ($request, $item, $rewards) {
            $user = User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            $product = RewardCatalogItem::whereKey($item->id)->lockForUpdate()->firstOrFail();
            abort_unless($product->is_active, 404);
            if ($product->stock < 1) {
                throw ValidationException::withMessages(['item' => 'ეს საჩუქარი ამჟამად მარაგში აღარ არის.']);
            }
            $balance = $rewards->balance($user);
            if ($balance < $product->points_cost) {
                throw ValidationException::withMessages(['points' => 'ქულების ბალანსი არასაკმარისია. საჭიროა ' . $product->points_cost . ' ქულა.']);
            }
            $product->decrement('stock');
            $order = RewardCatalogOrder::create([
                'user_id' => $user->id,
                'item_id' => $product->id,
                'item_title' => $product->title,
                'points_spent' => $product->points_cost,
                'status' => 'pending',
            ]);
            RewardTransaction::create([
                'user_id' => $user->id,
                'points' => -$product->points_cost,
                'type' => 'reward_catalog',
                'description' => 'Rewards საჩუქარი: ' . $product->title . ' (#' . $order->id . ')',
                'action_key' => 'reward-catalog-order:' . $order->id,
                'metadata' => ['order_id' => $order->id, 'item_id' => $product->id],
            ]);
            return $order;
        });

        return back()->with('success', 'საჩუქრის მოთხოვნა მიღებულია. ქულები რეზერვირებულია, შეკვეთის სტატუსს Rewards გვერდზე ნახავ.');
    }

    public function adminIndex(Request $request)
    {
        $this->authorizeAdmin($request);
        $items = RewardCatalogItem::withCount('orders')->orderBy('sort_order')->orderByDesc('created_at')->get();
        $orders = RewardCatalogOrder::with('user:id,name,email', 'item:id,title')->latest()->paginate(30);
        return view('rewards.catalog-admin', compact('items', 'orders'));
    }

    public function storeItem(Request $request)
    {
        $this->authorizeAdmin($request);
        $data = $this->validateItem($request);
        $data['slug'] = Str::slug($data['title']) . '-' . Str::lower(Str::random(5));
        RewardCatalogItem::create($data);
        return back()->with('success', 'საჩუქარი კატალოგში დაემატა.');
    }

    public function updateItem(Request $request, RewardCatalogItem $item)
    {
        $this->authorizeAdmin($request);
        $data = $this->validateItem($request);
        $item->update($data);
        return back()->with('success', 'საჩუქრის ინფორმაცია განახლდა.');
    }

    public function deleteItem(Request $request, RewardCatalogItem $item)
    {
        $this->authorizeAdmin($request);
        abort_if($item->orders()->whereIn('status', ['pending', 'approved', 'processing'])->exists(), 422, 'ამ საჩუქარს აქტიური შეკვეთები აქვს. ჯერ დაამუშავე ისინი.');
        $item->delete();
        return back()->with('success', 'საჩუქარი წაიშალა.');
    }

    public function updateOrder(Request $request, RewardCatalogOrder $order)
    {
        $this->authorizeAdmin($request);
        $data = $request->validate([
            'status' => ['required', 'in:approved,processing,shipped,fulfilled,rejected,cancelled'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($order, $data) {
            $locked = RewardCatalogOrder::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $allowedTransitions = match ($locked->status) {
                'pending' => ['approved', 'rejected', 'cancelled'],
                'approved' => ['processing', 'shipped', 'fulfilled', 'rejected', 'cancelled'],
                'processing' => ['shipped', 'fulfilled', 'rejected', 'cancelled'],
                'shipped' => ['fulfilled'],
                default => [],
            };
            abort_unless(in_array($data['status'], $allowedTransitions, true), 422, 'შეკვეთის ეს სტატუსის ცვლილება დაუშვებელია.');
            if (in_array($data['status'], ['rejected', 'cancelled'], true)) {
                $key = 'reward-catalog-refund:' . $locked->id;
                if (!RewardTransaction::where('action_key', $key)->exists()) {
                    RewardTransaction::create([
                        'user_id' => $locked->user_id,
                        'points' => $locked->points_spent,
                        'type' => 'refund',
                        'description' => 'Rewards საჩუქრის შეკვეთის დაბრუნება #' . $locked->id,
                        'action_key' => $key,
                        'metadata' => ['order_id' => $locked->id],
                    ]);
                    $wasRefunded = true;
                }
                if ($locked->item_id) {
                    RewardCatalogItem::whereKey($locked->item_id)->lockForUpdate()->increment('stock');
                }
            }
            $locked->update([
                'status' => $data['status'],
                'admin_note' => $data['admin_note'] ?? null,
                'fulfilled_at' => $data['status'] === 'fulfilled' ? now() : null,
            ]);
        });

        return back()->with('success', 'საჩუქრის შეკვეთის სტატუსი განახლდა.');
    }

    public function mobileIndex(Request $request, RewardsService $rewards)
    {
        return response()->json([
            'balance' => $rewards->balance($request->user()),
            'items' => RewardCatalogItem::where('is_active', true)->where('stock', '>', 0)->orderBy('sort_order')->orderBy('points_cost')->get(),
            'orders' => RewardCatalogOrder::where('user_id', $request->user()->id)->latest()->limit(30)->get(),
        ]);
    }

    public function mobileRedeem(Request $request, RewardCatalogItem $item, RewardsService $rewards)
    {
        $order = DB::transaction(function () use ($request, $item, $rewards) {
            $user = User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            $product = RewardCatalogItem::whereKey($item->id)->lockForUpdate()->firstOrFail();
            abort_unless($product->is_active, 404);
            if ($product->stock < 1) throw ValidationException::withMessages(['item' => 'საჩუქარი მარაგში აღარ არის.']);
            if ($rewards->balance($user) < $product->points_cost) throw ValidationException::withMessages(['points' => 'ქულების ბალანსი არასაკმარისია.']);
            $product->decrement('stock');
            $order = RewardCatalogOrder::create(['user_id' => $user->id, 'item_id' => $product->id, 'item_title' => $product->title, 'points_spent' => $product->points_cost, 'status' => 'pending']);
            RewardTransaction::create(['user_id' => $user->id, 'points' => -$product->points_cost, 'type' => 'reward_catalog', 'description' => 'Rewards საჩუქარი: ' . $product->title . ' (#' . $order->id . ')', 'action_key' => 'reward-catalog-order:' . $order->id, 'metadata' => ['order_id' => $order->id, 'item_id' => $product->id]]);
            return $order;
        });
        return response()->json(['message' => 'საჩუქრის მოთხოვნა მიღებულია.', 'order' => $order, 'balance' => $rewards->balance($request->user())], 201);
    }

    public function mobileAdminIndex(Request $request)
    {
        $this->authorizeAdmin($request);
        return response()->json([
            'items' => RewardCatalogItem::withCount('orders')->orderBy('sort_order')->get(),
            'orders' => RewardCatalogOrder::with('user:id,name,email')->latest()->limit(100)->get(),
        ]);
    }

    public function mobileAdminStore(Request $request)
    {
        $this->authorizeAdmin($request);
        $data = $this->validateItem($request);
        $data['slug'] = Str::slug($data['title']) . '-' . Str::lower(Str::random(5));
        $item = RewardCatalogItem::create($data);
        return response()->json(['message' => 'საჩუქარი დაემატა.', 'item' => $item], 201);
    }

    public function mobileAdminUpdate(Request $request, RewardCatalogItem $item)
    {
        $this->authorizeAdmin($request);
        $item->update($this->validateItem($request));
        return response()->json(['message' => 'საჩუქარი განახლდა.', 'item' => $item->fresh()]);
    }

    public function mobileAdminDelete(Request $request, RewardCatalogItem $item)
    {
        $this->authorizeAdmin($request);
        abort_if($item->orders()->whereIn('status', ['pending', 'approved', 'processing'])->exists(), 422, 'საჩუქარს აქტიური შეკვეთები აქვს.');
        $item->delete();
        return response()->json(['message' => 'საჩუქარი წაიშალა.']);
    }

    public function mobileAdminOrderUpdate(Request $request, RewardCatalogOrder $order)
    {
        $this->authorizeAdmin($request);
        $data = $request->validate(['status' => ['required', 'in:approved,processing,shipped,fulfilled,rejected,cancelled'], 'admin_note' => ['nullable', 'string', 'max:1000']]);
        DB::transaction(function () use ($order, $data) {
            $locked = RewardCatalogOrder::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if (in_array($locked->status, ['rejected', 'cancelled', 'fulfilled'], true)) abort(422, 'შეკვეთა უკვე დასრულებულია.');
            if (in_array($data['status'], ['rejected', 'cancelled'], true)) {
                $key = 'reward-catalog-refund:' . $locked->id;
                if (!RewardTransaction::where('action_key', $key)->exists()) {
                    RewardTransaction::create(['user_id' => $locked->user_id, 'points' => $locked->points_spent, 'type' => 'refund', 'description' => 'Rewards საჩუქრის შეკვეთის დაბრუნება #' . $locked->id, 'action_key' => $key, 'metadata' => ['order_id' => $locked->id]]);
                    if ($locked->item_id) RewardCatalogItem::whereKey($locked->item_id)->lockForUpdate()->increment('stock');
                }
            }
            $locked->update(['status' => $data['status'], 'admin_note' => $data['admin_note'] ?? null, 'fulfilled_at' => $data['status'] === 'fulfilled' ? now() : null]);
        });
        return response()->json(['message' => 'შეკვეთის სტატუსი განახლდა.']);
    }

    private function validateItem(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:3000'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'points_cost' => ['required', 'integer', 'min:1', 'max:10000000'],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active'), 'sort_order' => (int) $request->input('sort_order', 0)];
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless((bool) $request->user()?->is_admin, 403);
    }
}
