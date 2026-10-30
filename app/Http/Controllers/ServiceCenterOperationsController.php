<?php

namespace App\Http\Controllers;

use App\Models\ServiceCenter;
use App\Models\ServiceCenterActivityLog;
use App\Models\ServiceCenterBay;
use App\Models\ServiceCenterInventoryItem;
use App\Models\ServiceCenterPayment;
use App\Models\ServiceCenterTechnician;
use App\Models\ServiceCenterWorkOrderPart;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceCenterOperationsController extends Controller
{
    private function center(): ServiceCenter
    {
        return ServiceCenter::where('owner_id', auth()->id())->firstOrFail();
    }

    public function index(Request $request)
    {
        $center = $this->center();
        $from = $request->date('from')?->startOfDay() ?? now()->startOfWeek();
        $to = $request->date('to')?->endOfDay() ?? now()->endOfWeek();

        $orders = WorkOrder::where('service_center_id', $center->id)
            ->with(['serviceBooking', 'assignedTechnician', 'technician', 'bay', 'parts.inventoryItem', 'payments'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('q'), fn ($q) => $q->where(function ($sub) use ($request) {
                $term = '%'.$request->string('q').'%';
                $sub->where('order_number', 'like', $term)
                    ->orWhereHas('serviceBooking', fn ($b) => $b->where('customer_name', 'like', $term)->orWhere('motorcycle', 'like', $term));
            }))
            ->latest()->paginate(30)->withQueryString();

        $calendar = WorkOrder::where('service_center_id', $center->id)
            ->whereHas('serviceBooking', fn ($q) => $q->whereBetween('scheduled_at', [$from, $to]))
            ->with(['serviceBooking', 'assignedTechnician', 'technician', 'bay'])
            ->get();

        $technicians = $center->technicians()->where('is_active', true)->orderBy('name')->get();
        $bays = $center->bays()->where('is_active', true)->orderBy('name')->get();
        $inventory = $center->inventory()->where('is_active', true)->orderBy('name')->get();
        $lowStock = $center->inventory()->where('is_active', true)->whereColumn('quantity', '<=', 'min_quantity')->count();

        $stats = [
            'open' => $center->workOrders()->whereNotIn('status', ['handed_over', 'cancelled'])->count(),
            'repair' => $center->workOrders()->where('status', 'in_repair')->count(),
            'revenue' => $center->payments()->whereBetween('paid_at', [$from, $to])->sum('amount'),
            'outstanding' => DB::table('work_orders')->where('service_center_id', $center->id)
                ->whereNotIn('status', ['cancelled'])->selectRaw('COALESCE(SUM(total_cost),0) - COALESCE((SELECT SUM(amount) FROM service_center_payments WHERE service_center_payments.work_order_id = work_orders.id),0) as due')->value('due'),
        ];

        $payments = $center->payments()->with('workOrder')->latest('paid_at')->limit(12)->get();

        return view('my-service-center.operations', compact(
            'center', 'orders', 'calendar', 'technicians', 'bays', 'inventory',
            'lowStock', 'stats', 'payments', 'from', 'to'
        ));
    }

    public function storeTechnician(Request $request)
    {
        $center = $this->center();
        $data = $request->validate([
            'name' => ['required','string','max:160'],
            'phone' => ['nullable','string','max:40'],
            'specialty' => ['nullable','string','max:160'],
        ]);
        $center->technicians()->create($data);
        return back()->with('success', 'ხელოსანი დაემატა.');
    }

    public function storeBay(Request $request)
    {
        $center = $this->center();
        $data = $request->validate(['name'=>['required','string','max:100'],'description'=>['nullable','string','max:255']]);
        $center->bays()->create($data);
        return back()->with('success', 'სამუშაო ბოქსი დაემატა.');
    }

    public function storeInventory(Request $request)
    {
        $center = $this->center();
        $data = $request->validate([
            'sku'=>['nullable','string','max:80'],'name'=>['required','string','max:180'],
            'brand'=>['nullable','string','max:120'],'category'=>['nullable','string','max:120'],
            'fitment'=>['nullable','string','max:255'],'quantity'=>['required','integer','min:0'],
            'min_quantity'=>['required','integer','min:0'],'cost_price'=>['required','numeric','min:0'],
            'sale_price'=>['required','numeric','min:0'],'location'=>['nullable','string','max:120'],
        ]);
        DB::transaction(function () use ($center, $data) {
            $item = $center->inventory()->create($data);
            if ($item->quantity > 0) {
                DB::table('service_center_inventory_movements')->insert([
                    'service_center_id'=>$center->id,'inventory_item_id'=>$item->id,'user_id'=>auth()->id(),
                    'type'=>'in','quantity'=>$item->quantity,'unit_cost'=>$item->cost_price,
                    'note'=>'საწყობის საწყისი ნაშთი','created_at'=>now(),'updated_at'=>now(),
                ]);
            }
        });
        return back()->with('success', 'ნაწილი დაემატა საწყობს.');
    }

    public function assign(Request $request, WorkOrder $workOrder)
    {
        $center = $this->center();
        abort_unless($workOrder->service_center_id === $center->id, 404);
        $data = $request->validate([
            'assigned_technician_id'=>['nullable','integer', 'exists:service_center_technicians,id'],
            'service_center_bay_id'=>['nullable','integer','exists:service_center_bays,id'],
        ]);
        if (!empty($data['assigned_technician_id'])) abort_unless($center->technicians()->whereKey($data['assigned_technician_id'])->exists(), 422);
        if (!empty($data['service_center_bay_id'])) abort_unless($center->bays()->whereKey($data['service_center_bay_id'])->exists(), 422);
        $workOrder->update([
            'service_center_technician_id' => $data['assigned_technician_id'] ?? null,
            'service_center_bay_id' => $data['service_center_bay_id'] ?? null,
        ]);
        return back()->with('success', 'ხელოსანი და ბოქსი მიენიჭა შეკვეთას.');
    }

    public function addPart(Request $request, WorkOrder $workOrder)
    {
        $center = $this->center();
        abort_unless($workOrder->service_center_id === $center->id, 404);
        $data = $request->validate(['inventory_item_id'=>['required','integer'],'quantity'=>['required','integer','min:1']]);
        DB::transaction(function () use ($center, $workOrder, $data) {
            $item = $center->inventory()->whereKey($data['inventory_item_id'])->lockForUpdate()->firstOrFail();
            $quantity = (int)$data['quantity'];
            if ($item->quantity < $quantity) throw \Illuminate\Validation\ValidationException::withMessages(['quantity'=>'საწყობში საკმარისი რაოდენობა არ არის.']);
            $line = ServiceCenterWorkOrderPart::firstOrNew(['work_order_id'=>$workOrder->id,'inventory_item_id'=>$item->id]);
            $line->quantity = ($line->exists ? $line->quantity : 0) + $quantity;
            $line->unit_price = $item->sale_price;
            $line->total = $line->quantity * $item->sale_price;
            $line->save();
            $item->decrement('quantity', $quantity);
            DB::table('service_center_inventory_movements')->insert([
                'service_center_id'=>$center->id,'inventory_item_id'=>$item->id,'work_order_id'=>$workOrder->id,
                'user_id'=>auth()->id(),'type'=>'out','quantity'=>$quantity,'unit_cost'=>$item->cost_price,
                'note'=>'სამუშაო შეკვეთა '.$workOrder->order_number,'created_at'=>now(),'updated_at'=>now(),
            ]);
            $partsTotal = $workOrder->parts()->sum('total');
            $workOrder->update(['parts_cost'=>$partsTotal,'total_cost'=>(float)$workOrder->labor_cost + (float)$partsTotal]);
        });
        return back()->with('success', 'ნაწილი შეკვეთას მიება და საწყობიდან ჩამოიწერა.');
    }

    public function payment(Request $request, WorkOrder $workOrder)
    {
        $center = $this->center();
        abort_unless($workOrder->service_center_id === $center->id, 404);
        $data = $request->validate([
            'amount'=>['required','numeric','gt:0'],'method'=>['required','in:cash,card,transfer,other'],
            'reference'=>['nullable','string','max:120'],'note'=>['nullable','string','max:2000'],
        ]);
        $paid = (float)$workOrder->payments()->sum('amount');
        $due = round((float)$workOrder->total_cost - $paid, 2);
        if ((float)$data['amount'] > $due) return back()->withErrors(['amount'=>'გადასახდელ ნაშთზე მეტი თანხა არ შეიძლება.']);
        ServiceCenterPayment::create([
            ...$data,'service_center_id'=>$center->id,'work_order_id'=>$workOrder->id,
            'received_by'=>auth()->id(),'paid_at'=>now(),
        ]);
        return back()->with('success', 'გადახდა აღირიცხა.');
    }

    public function customerStatus(string $token)
    {
        $order = WorkOrder::where('public_token', $token)->with(['serviceCenter','serviceBooking','parts.inventoryItem','payments'])->firstOrFail();
        return view('service-centers.work-order-status', compact('order'));
    }

    public function notify(Request $request, WorkOrder $workOrder)
    {
        $center = $this->center();
        abort_unless($workOrder->service_center_id === $center->id, 404);
        $data = $request->validate(['message'=>['required','string','max:2000']]);
        $booking = $workOrder->serviceBooking;
        ServiceCenterActivityLog::create([
            'service_center_id'=>$center->id,'work_order_id'=>$workOrder->id,
            'channel'=>'customer_portal','event'=>'manual_update',
            'recipient'=>$booking?->customer_phone,'message'=>$data['message'],
            'status'=>'logged',
        ]);
        return back()->with('success', 'განახლება დაემატა შეკვეთის ისტორიას. SMS/Email ავტომატურად არ გაგზავნილა.');
    }
}
