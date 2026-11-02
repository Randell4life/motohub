<?php

namespace App\Http\Controllers;

use App\Models\ServiceCenter;
use App\Models\ServiceCenterActivityLog;
use App\Models\ServiceCenterBay;
use App\Models\ServiceCenterInventoryItem;
use App\Models\ServiceCenterPayment;
use App\Models\ServiceCenterSupplier;
use App\Models\ServiceCenterSupplierPayment;
use App\Models\ServiceCenterSupplierInvoiceItem;
use App\Models\ServiceCenterTechnician;
use App\Models\ServiceCenterWorkOrderPart;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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
        $features = $center->featureAccess();
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
        $suppliers = $center->suppliers()->where('is_active', true)->orderBy('name')->get();
        $supplierPurchases = DB::table('service_center_inventory_movements')
            ->where('service_center_id', $center->id)->whereNotNull('supplier_id')->where('type', 'in')
            ->select('supplier_id')->selectRaw('COALESCE(SUM(quantity * unit_cost), 0) as total')
            ->groupBy('supplier_id')->pluck('total', 'supplier_id');
        $supplierPayments = $center->supplierPayments()
            ->select('supplier_id')->selectRaw('COALESCE(SUM(amount), 0) as total')
            ->groupBy('supplier_id')->pluck('total', 'supplier_id');
        $supplierPaymentHistory = $center->supplierPayments()->with('supplier')->latest('paid_at')->limit(30)->get();
        $supplierInvoices = $center->supplierInvoices()->with(['supplier', 'items.inventoryItem'])->latest('issue_date')->latest()->limit(50)->get();
        $suppliers->each(function ($supplier) use ($supplierPurchases, $supplierPayments) {
            $supplier->purchased_total = (float) ($supplierPurchases[$supplier->id] ?? 0);
            $supplier->paid_total = (float) ($supplierPayments[$supplier->id] ?? 0);
            $supplier->balance = round($supplier->purchased_total - $supplier->paid_total, 2);
        });
        $stockMovements = DB::table('service_center_inventory_movements')
            ->join('service_center_inventory', 'service_center_inventory.id', '=', 'service_center_inventory_movements.inventory_item_id')
            ->leftJoin('work_orders', 'work_orders.id', '=', 'service_center_inventory_movements.work_order_id')
            ->leftJoin('users', 'users.id', '=', 'service_center_inventory_movements.user_id')
            ->leftJoin('service_center_suppliers', 'service_center_suppliers.id', '=', 'service_center_inventory_movements.supplier_id')
            ->where('service_center_inventory_movements.service_center_id', $center->id)
            ->latest('service_center_inventory_movements.created_at')
            ->limit(40)
            ->select('service_center_inventory_movements.*', 'service_center_inventory.name as item_name', 'work_orders.order_number', 'users.name as user_name', 'service_center_suppliers.name as supplier_name', 'service_center_inventory_movements.supplier_reference')
            ->get();
        $lowStock = $center->inventory()->where('is_active', true)->whereColumn('quantity', '<=', 'min_quantity')->count();

        $stats = [
            'open' => $center->workOrders()->whereNotIn('status', ['handed_over', 'cancelled'])->count(),
            'repair' => $center->workOrders()->where('status', 'in_repair')->count(),
            'revenue' => $center->payments()->whereBetween('paid_at', [$from, $to])->sum('amount'),
            'outstanding' => (function () use ($center) {
                $paidByOrder = DB::table('service_center_payments')
                    ->select('work_order_id')
                    ->selectRaw('SUM(amount) as paid')
                    ->groupBy('work_order_id');

                return DB::table('work_orders')
                    ->leftJoinSub($paidByOrder, 'payments_by_order', fn ($join) => $join->on('payments_by_order.work_order_id', '=', 'work_orders.id'))
                    ->where('work_orders.service_center_id', $center->id)
                    ->whereNotIn('work_orders.status', ['cancelled'])
                    ->selectRaw('COALESCE(SUM(COALESCE(work_orders.total_cost, 0) - COALESCE(payments_by_order.paid, 0)), 0) as due')
                    ->value('due');
            })(),
        ];

        $payments = $center->payments()->with('workOrder')->latest('paid_at')->limit(12)->get();

        return view('my-service-center.operations', compact(
            'center', 'orders', 'calendar', 'technicians', 'bays', 'inventory',
            'lowStock', 'stats', 'payments', 'from', 'to', 'features', 'stockMovements', 'suppliers', 'supplierPaymentHistory', 'supplierInvoices'
        ));
    }

    public function financialReports(Request $request)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('financial_reports'), 403, $center->featureLockedMessage('financial_reports'));

        $data = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ]);
        $from = isset($data['from']) ? \Illuminate\Support\Carbon::parse($data['from'])->startOfDay() : now()->startOfMonth();
        $to = isset($data['to']) ? \Illuminate\Support\Carbon::parse($data['to'])->endOfDay() : now()->endOfMonth();
        if ($to->lt($from)) {
            throw \Illuminate\Validation\ValidationException::withMessages(['to' => 'დასრულების თარიღი დაწყების თარიღზე ადრე ვერ იქნება.']);
        }

        $orders = WorkOrder::where('service_center_id', $center->id)
            ->where(function ($query) use ($from, $to) {
                $query->whereBetween('created_at', [$from, $to])
                    ->orWhereBetween('completed_at', [$from, $to]);
            })
            ->whereNotIn('status', ['cancelled'])
            ->with(['serviceBooking', 'technician', 'parts.inventoryItem', 'payments', 'expenses', 'technicianPayroll'])
            ->latest()
            ->get();

        $payments = $center->payments()->with('workOrder')
            ->whereBetween('paid_at', [$from, $to])
            ->latest('paid_at')->get();

        $expenses = $center->expenses()->with('workOrder')
            ->whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->latest('expense_date')->latest()->get();

        $partsCost = (float) DB::table('service_center_inventory_movements')
            ->where('service_center_id', $center->id)
            ->where('type', 'out')
            ->whereNotNull('work_order_id')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('COALESCE(SUM(quantity * unit_cost), 0) as total')
            ->value('total');

        $billedRevenue = (float) $orders->sum(fn ($order) => (float) $order->total_cost);
        $laborRevenue = (float) $orders->sum(fn ($order) => (float) $order->labor_cost);
        $partsRevenue = (float) $orders->sum(fn ($order) => (float) $order->parts_cost);
        $collected = (float) $payments->sum(fn ($payment) => (float) $payment->amount);
        $expenseTotal = (float) $expenses->sum(fn ($expense) => (float) $expense->amount);
        $outstanding = (float) $orders->sum(fn ($order) => max(0, round((float) $order->total_cost - (float) $order->payments->sum('amount'), 2)));
        $grossProfit = round($billedRevenue - $partsCost, 2);
        $netProfit = round($grossProfit - $expenseTotal, 2);

        $categoryTotals = $expenses->groupBy('category')->map(fn ($rows) => (float) $rows->sum('amount'));

        $payroll = $center->technicianPayroll()
            ->with(['technician', 'workOrder'])
            ->whereBetween('earned_at', [$from, $to])
            ->latest('earned_at')->get();
        $payrollPending = (float) $payroll->where('status', 'pending')->sum('amount');
        $payrollPaid = (float) $payroll->where('status', 'paid')->sum('amount');
        $payrollTotal = (float) $payroll->sum('amount');
        $netProfit = round($grossProfit - $expenseTotal - $payrollTotal, 2);
        $payrollByTechnician = $payroll->groupBy('service_center_technician_id');
        $completedByTechnician = WorkOrder::where('service_center_id', $center->id)
            ->whereIn('status', ['completed', 'handed_over'])
            ->whereBetween('completed_at', [$from, $to])
            ->whereNotNull('service_center_technician_id')
            ->with('technician')
            ->get()
            ->groupBy('service_center_technician_id');

        $technicianPerformance = $completedByTechnician->map(function ($completedOrders, $technicianId) use ($payrollByTechnician) {
            $entries = $payrollByTechnician->get($technicianId, collect());
            return [
                'technician' => $completedOrders->first()->technician,
                'completed_orders' => $completedOrders->count(),
                'labor_base' => (float) $completedOrders->sum('labor_cost'),
                'earned' => (float) $entries->sum('amount'),
                'paid' => (float) $entries->where('status', 'paid')->sum('amount'),
                'pending' => (float) $entries->where('status', 'pending')->sum('amount'),
            ];
        })->sortByDesc('completed_orders')->values();
        $partsCostByOrder = DB::table('service_center_inventory_movements')
            ->whereIn('work_order_id', $orders->pluck('id'))
            ->where('type', 'out')
            ->select('work_order_id')
            ->selectRaw('COALESCE(SUM(quantity * unit_cost), 0) as total')
            ->groupBy('work_order_id')
            ->pluck('total', 'work_order_id');

        $orderProfitability = $orders->map(function ($order) use ($partsCostByOrder) {
            $partCost = (float) ($partsCostByOrder[$order->id] ?? 0);
            $directExpenses = (float) $order->expenses->sum('amount');
            return [
                'order' => $order,
                'parts_cost' => $partCost,
                'direct_expenses' => $directExpenses,
                'profit' => round((float) $order->total_cost - $partCost - $directExpenses - (float) $order->technicianPayroll?->amount, 2),
                'paid' => (float) $order->payments->sum('amount'),
                'technician_payroll' => (float) $order->technicianPayroll?->amount,
            ];
        });

        return view('my-service-center.finance', compact(
            'center', 'from', 'to', 'orders', 'payments', 'expenses',
            'billedRevenue', 'laborRevenue', 'partsRevenue', 'collected',
            'partsCost', 'expenseTotal', 'outstanding', 'grossProfit',
            'netProfit', 'categoryTotals', 'orderProfitability', 'payroll', 'payrollPending', 'payrollPaid', 'payrollTotal', 'technicianPerformance'
        ));
    }

    public function storeExpense(Request $request)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('finance'), 403, $center->featureLockedMessage('finance'));

        $data = $request->validate([
            'work_order_id' => ['nullable', 'integer'],
            'category' => ['required', 'in:'.implode(',', array_keys(\App\Models\ServiceCenterExpense::CATEGORIES))],
            'description' => ['required', 'string', 'max:180'],
            'amount' => ['required', 'numeric', 'gt:0', 'max:999999999'],
            'expense_date' => ['required', 'date'],
            'reference' => ['nullable', 'string', 'max:120'],
        ]);

        $workOrder = null;
        if (! empty($data['work_order_id'])) {
            $workOrder = $center->workOrders()->whereKey($data['work_order_id'])->firstOrFail();
        }

        $center->expenses()->create([
            ...$data,
            'work_order_id' => $workOrder?->id,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'ხარჯი წარმატებით აღირიცხა.');
    }

    public function storeTechnician(Request $request)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('team'), 403, $center->featureLockedMessage('team'));
        $limit = $center->technicianLimit();
        abort_if($limit !== null && $center->technicians()->count() >= $limit, 422, 'შენი პაკეტის ლიმიტია '.$limit.' ხელოსანი. პაკეტის გასაზრდელად გადადი გამოწერის მართვაში.');
        $data = $request->validate([
            'name' => ['required','string','max:160'],
            'phone' => ['nullable','string','max:40'],
            'specialty' => ['nullable','string','max:160'],
        ]);

        if ($center->canUseFeature('financial_reports')) {
            $pay = $request->validate([
                'pay_type' => ['required', 'in:percentage_labor,fixed_per_order'],
                'pay_rate' => ['required', 'numeric', 'min:0', 'max:1000000'],
            ]);
            if ($pay['pay_type'] === 'percentage_labor' && (float) $pay['pay_rate'] > 100) {
                throw \Illuminate\Validation\ValidationException::withMessages(['pay_rate' => 'ხელობის პროცენტი 100%-ზე მეტი ვერ იქნება.']);
            }
            $data = [...$data, ...$pay];
        } else {
            $data = [...$data, 'pay_type' => 'percentage_labor', 'pay_rate' => 0];
        }

        $center->technicians()->create($data);
        return back()->with('success', 'ხელოსანი დაემატა.');
    }

    public function updateTechnicianPay(Request $request, ServiceCenterTechnician $technician)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('financial_reports'), 403, $center->featureLockedMessage('financial_reports'));
        abort_unless($technician->service_center_id === $center->id, 404);

        $data = $request->validate([
            'pay_type' => ['required', 'in:percentage_labor,fixed_per_order'],
            'pay_rate' => ['required', 'numeric', 'min:0', 'max:1000000'],
        ]);
        if ($data['pay_type'] === 'percentage_labor' && (float) $data['pay_rate'] > 100) {
            throw \Illuminate\Validation\ValidationException::withMessages(['pay_rate' => 'ხელობის პროცენტი 100%-ზე მეტი ვერ იქნება.']);
        }
        $technician->update($data);

        return back()->with('success', 'ხელოსნის ანაზღაურების ტარიფი განახლდა. ახალი ტარიფი გავრცელდება მომავალ დასრულებულ შეკვეთებზე.');
    }

    public function markTechnicianPayrollPaid(Request $request, \App\Models\ServiceCenterTechnicianPayroll $payroll)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('financial_reports'), 403, $center->featureLockedMessage('financial_reports'));
        abort_unless($payroll->service_center_id === $center->id, 404);

        DB::transaction(function () use ($payroll, $center) {
            $locked = \App\Models\ServiceCenterTechnicianPayroll::query()
                ->where('service_center_id', $center->id)
                ->lockForUpdate()
                ->findOrFail($payroll->id);

            if ($locked->status === 'paid') {
                return;
            }

            $locked->update([
                'status' => 'paid',
                'paid_at' => now(),
                'paid_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'ხელოსნის ანაზღაურება გადახდილად მოინიშნა.');
    }

    public function storeBay(Request $request)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('team'), 403, $center->featureLockedMessage('team'));
        $limit = $center->bayLimit();
        abort_if($limit !== null && $center->bays()->count() >= $limit, 422, 'შენი პაკეტის ლიმიტია '.$limit.' სამუშაო ბოქსი. პაკეტის გასაზრდელად გადადი გამოწერის მართვაში.');
        $data = $request->validate(['name'=>['required','string','max:100'],'description'=>['nullable','string','max:255']]);
        $center->bays()->create($data);
        return back()->with('success', 'სამუშაო ბოქსი დაემატა.');
    }

    public function storeInventory(Request $request)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('inventory'), 403, $center->featureLockedMessage('inventory'));
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

    public function storeSupplier(Request $request)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('inventory'), 403, $center->featureLockedMessage('inventory'));
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'contact_person' => ['nullable', 'string', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:180'],
            'tax_id' => ['nullable', 'string', 'max:80'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:3000'],
        ]);
        $center->suppliers()->create($data);
        return back()->with('success', 'მომწოდებელი დაემატა.');
    }

    public function storeSupplierInvoice(Request $request)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('inventory'), 403, $center->featureLockedMessage('inventory'));
        $data = $request->validate([
            'supplier_id' => ['required', 'integer', 'exists:service_center_suppliers,id'],
            'invoice_number' => ['required', 'string', 'max:120', Rule::unique('service_center_supplier_invoices', 'invoice_number')->where(fn ($query) => $query->where('supplier_id', $request->input('supplier_id')))],
            'issue_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'total_amount' => ['required', 'numeric', 'gt:0', 'max:999999999'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.inventory_item_id' => ['required', 'integer', 'distinct', 'exists:service_center_inventory,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:1000000'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0', 'max:999999999'],
        ]);
        $supplier = $center->suppliers()->findOrFail($data['supplier_id']);
        $linesTotal = collect($data['items'])->sum(fn ($line) => (int) $line['quantity'] * (float) $line['unit_cost']);
        if (abs($linesTotal - (float) $data['total_amount']) > 0.01) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'total_amount' => 'ინვოისის ჯამი უნდა ემთხვეოდეს ნაწილების რაოდენობა × ერთეულის ფასის ჯამს ('.number_format($linesTotal, 2).' ₾).',
            ]);
        }
        foreach ($data['items'] as $line) {
            $center->inventory()->whereKey($line['inventory_item_id'])->firstOrFail();
        }
        $path = isset($data['document'])
            ? $data['document']->store('service-center-invoices/'.$center->id, 'public')
            : null;

        try {
            $invoice = DB::transaction(function () use ($center, $supplier, $data, $path) {
                $invoice = $center->supplierInvoices()->create([
                    'supplier_id' => $supplier->id,
                    'user_id' => auth()->id(),
                    'invoice_number' => trim($data['invoice_number']),
                    'issue_date' => $data['issue_date'],
                    'due_date' => $data['due_date'] ?? null,
                    'total_amount' => $data['total_amount'],
                    'file_path' => $path,
                    'notes' => $data['notes'] ?? null,
                ]);

                foreach ($data['items'] as $line) {
                    $invoice->items()->create([
                        'inventory_item_id' => $line['inventory_item_id'],
                        'quantity' => $line['quantity'],
                        'unit_cost' => $line['unit_cost'],
                    ]);
                }

                return $invoice->load(['supplier', 'items.inventoryItem']);
            });
        } catch (\Throwable $exception) {
            if ($path) \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            throw $exception;
        }

        return back()->with('success', 'ინვოისი და მისი ნაწილები შეინახა. მარაგში ჩაირიცხება მიღების დადასტურებისას.');
    }

    public function receiveSupplierInvoiceItem(Request $request, int $line)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('inventory'), 403, $center->featureLockedMessage('inventory'));
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:1000000']]);
        $record = ServiceCenterSupplierInvoiceItem::whereHas('invoice', fn ($query) => $query->where('service_center_id', $center->id))
            ->findOrFail($line);
        app(\App\Services\ServiceCenterSupplierInvoiceService::class)->receive($center, $record, (int) $data['quantity'], auth()->id());

        return back()->with('success', 'ინვოისის მიღებული რაოდენობა ჩაირიცხა საწყობში.');
    }

    public function paySupplier(Request $request, ServiceCenterSupplier $supplier)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('inventory'), 403, $center->featureLockedMessage('inventory'));
        abort_unless($supplier->service_center_id === $center->id, 404);
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0', 'max:999999999'],
            'method' => ['required', 'in:cash,bank_transfer,card,other'],
            'reference' => ['nullable', 'string', 'max:120'],
            'note' => ['nullable', 'string', 'max:255'],
            'paid_at' => ['required', 'date'],
        ]);
        DB::transaction(function () use ($center, $supplier, $data) {
            $locked = ServiceCenterSupplier::where('service_center_id', $center->id)->whereKey($supplier->id)->lockForUpdate()->firstOrFail();
            $purchased = (float) DB::table('service_center_inventory_movements')
                ->where('service_center_id', $center->id)->where('supplier_id', $locked->id)->where('type', 'in')
                ->selectRaw('COALESCE(SUM(quantity * unit_cost), 0) as total')->value('total');
            $paid = (float) $center->supplierPayments()->where('supplier_id', $locked->id)->sum('amount');
            if ((float) $data['amount'] > round($purchased - $paid, 2)) {
                throw \Illuminate\Validation\ValidationException::withMessages(['amount' => 'გადახდა მომწოდებლის მიმდინარე დავალიანებაზე მეტი ვერ იქნება.']);
            }
            $center->supplierPayments()->create([
                'supplier_id' => $locked->id, 'user_id' => auth()->id(),
                'amount' => $data['amount'], 'method' => $data['method'],
                'reference' => $data['reference'] ?? null, 'note' => $data['note'] ?? null,
                'paid_at' => $data['paid_at'],
            ]);
        });
        return back()->with('success', 'მომწოდებლის გადახდა აღირიცხა.');
    }

    public function receiveInventory(Request $request, ServiceCenterInventoryItem $item)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('inventory'), 403, $center->featureLockedMessage('inventory'));
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:1000000'],
            'unit_cost' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'note' => ['nullable', 'string', 'max:255'],
            'supplier_id' => ['nullable', 'integer', 'exists:service_center_suppliers,id'],
            'supplier_reference' => ['nullable', 'string', 'max:120'],
        ]);
        if (!empty($data['supplier_id'])) $center->suppliers()->findOrFail($data['supplier_id']);
        $updated = app(\App\Services\ServiceCenterStockService::class)->receive(
            $center, $item, (int) $data['quantity'], (float) $data['unit_cost'], auth()->id(), $data['note'] ?? null,
            isset($data['supplier_id']) ? (int) $data['supplier_id'] : null, $data['supplier_reference'] ?? null
        );
        return back()->with('success', 'მარაგი შეივსო. ახალი საშუალო თვითღირებულება: '.number_format((float) $updated->cost_price, 2).' ₾');
    }

    public function adjustInventory(Request $request, ServiceCenterInventoryItem $item)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('inventory'), 403, $center->featureLockedMessage('inventory'));
        $data = $request->validate([
            'direction' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000000'],
            'note' => ['required', 'string', 'max:255'],
        ]);
        app(\App\Services\ServiceCenterStockService::class)->adjust(
            $center, $item, $data['direction'], (int) $data['quantity'], auth()->id(), $data['note']
        );
        return back()->with('success', 'საწყობის ნაშთი დაკორექტირდა და მოძრაობა ისტორიაში ჩაიწერა.');
    }

    public function assign(Request $request, WorkOrder $workOrder)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('work_orders'), 403, $center->featureLockedMessage('work_orders'));
        abort_unless($workOrder->service_center_id === $center->id, 404);
        $data = $request->validate([
            'assigned_technician_id'=>['nullable','integer', 'exists:service_center_technicians,id'],
            'service_center_bay_id'=>['nullable','integer','exists:service_center_bays,id'],
        ]);
        app(\App\Services\WorkOrderAssignmentService::class)->assign(
            $center,
            $workOrder,
            isset($data['assigned_technician_id']) ? (int) $data['assigned_technician_id'] : null,
            isset($data['service_center_bay_id']) ? (int) $data['service_center_bay_id'] : null,
        );
        return back()->with('success', 'ხელოსანი და ბოქსი მიენიჭა შეკვეთას.');
    }

    public function addPart(Request $request, WorkOrder $workOrder)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('inventory'), 403, $center->featureLockedMessage('inventory'));
        abort_unless($workOrder->service_center_id === $center->id, 404);
        $data = $request->validate([
            'inventory_item_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($workOrder->quote_sent_at && ! in_array($workOrder->status, ['completed', 'handed_over', 'cancelled'], true)) {
            if ($workOrder->customer_approval_status === 'pending') {
                throw \Illuminate\Validation\ValidationException::withMessages(['quote' => 'ჯერ დაელოდეთ კლიენტის პასუხს მიმდინარე ხარჯთაღრიცხვაზე.']);
            }
            $item = $center->inventory()->whereKey($data['inventory_item_id'])->firstOrFail();
            $revision = app(\App\Services\WorkOrderQuoteWorkflow::class)->sendSupplemental(
                $workOrder,
                ['description' => $data['description'] ?: ('დამატებითი ნაწილი: '.$item->name), 'labor_cost' => 0, 'parts_cost' => 0],
                ['inventory_item_id' => $item->id, 'quantity' => (int) $data['quantity']]
            );
            app(\App\Services\ServiceCenterCustomerNotifier::class)->send(
                $workOrder->fresh(),
                'supplemental_quote_sent',
                'დამატებითი ხარჯი დასადასტურებელია',
                'სერვისცენტრმა გამოგიგზავნათ დამატებითი ნაწილის შეთავაზება. გთხოვთ, დაადასტუროთ ან უარყოთ.'
            );
            return back()->with('success', 'დამატებითი ნაწილი კლიენტს გაეგზავნა დასადასტურებლად. საწყობიდან ჩამოიწერება მხოლოდ თანხმობის შემდეგ.');
        }

        DB::transaction(function () use ($center, $workOrder, $data) {
            $lockedOrder = WorkOrder::whereKey($workOrder->id)->where('service_center_id', $center->id)->lockForUpdate()->firstOrFail();
            $item = $center->inventory()->whereKey($data['inventory_item_id'])->lockForUpdate()->firstOrFail();
            $quantity = (int) $data['quantity'];
            if ($item->quantity < $quantity) {
                throw \Illuminate\Validation\ValidationException::withMessages(['quantity' => 'საწყობში საკმარისი რაოდენობა არ არის.']);
            }
            $line = ServiceCenterWorkOrderPart::firstOrNew(['work_order_id' => $lockedOrder->id, 'inventory_item_id' => $item->id]);
            $line->quantity = ($line->exists ? $line->quantity : 0) + $quantity;
            $line->unit_price = $item->sale_price;
            $line->total = $line->quantity * $item->sale_price;
            $line->save();
            $item->decrement('quantity', $quantity);
            DB::table('service_center_inventory_movements')->insert([
                'service_center_id' => $center->id, 'inventory_item_id' => $item->id, 'work_order_id' => $workOrder->id,
                'user_id' => auth()->id(), 'type' => 'out', 'quantity' => $quantity, 'unit_cost' => $item->cost_price,
                'note' => 'სამუშაო შეკვეთა '.$workOrder->order_number, 'created_at' => now(), 'updated_at' => now(),
            ]);
            $partsTotal = $lockedOrder->parts()->sum('total');
            $lockedOrder->update(['parts_cost' => $partsTotal, 'total_cost' => (float) $lockedOrder->labor_cost + (float) $partsTotal]);
        });
        return back()->with('success', 'ნაწილი შეკვეთას მიება და საწყობიდან ჩამოიწერა.');
    }

    public function sendSupplementalQuote(Request $request, WorkOrder $workOrder)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('work_orders'), 403, $center->featureLockedMessage('work_orders'));
        abort_unless($workOrder->service_center_id === $center->id, 404);
        $data = $request->validate([
            'description' => ['required', 'string', 'max:2000'],
            'labor_cost' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'parts_cost' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
        ]);
        $revision = app(\App\Services\WorkOrderQuoteWorkflow::class)->sendSupplemental($workOrder, $data);
        app(\App\Services\ServiceCenterCustomerNotifier::class)->send(
            $workOrder->fresh(), 'supplemental_quote_sent', 'დამატებითი ხარჯი დასადასტურებელია',
            'სერვისცენტრმა გამოგიგზავნათ დამატებითი სამუშაოს ხარჯთაღრიცხვა. გთხოვთ, გაეცნოთ და უპასუხოთ.'
        );
        return back()->with('success', 'დამატებითი ხარჯი კლიენტს გაეგზავნა დასადასტურებლად.');
    }

    public function payment(Request $request, WorkOrder $workOrder)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('finance'), 403, $center->featureLockedMessage('finance'));
        abort_unless($workOrder->service_center_id === $center->id, 404);
        $data = $request->validate([
            'amount'=>['required','numeric','gt:0'],'method'=>['required','in:cash,card,transfer,other'],
            'reference'=>['nullable','string','max:120'],'note'=>['nullable','string','max:2000'],
        ]);
        DB::transaction(function () use ($center, $workOrder, $data) {
            // ყველა გადახდა ჯერ სამუშაო შეკვეთას ბლოკავს; ასე პარალელური მოთხოვნები
            // ერთსა და იმავე ნაშთს ვეღარ გამოიყენებენ.
            $lockedOrder = WorkOrder::whereKey($workOrder->id)
                ->where('service_center_id', $center->id)
                ->lockForUpdate()
                ->firstOrFail();

            $paid = (float) $lockedOrder->payments()->sum('amount');
            $due = round((float) $lockedOrder->total_cost - $paid, 2);
            $amount = round((float) $data['amount'], 2);

            if ($due <= 0 || $amount > $due) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'amount' => 'გადასახდელ ნაშთზე მეტი თანხა არ შეიძლება.',
                ]);
            }

            $lockedOrder->payments()->create([
                ...$data,
                'service_center_id' => $center->id,
                'received_by' => auth()->id(),
                'paid_at' => now(),
            ]);
        });

        $workOrder->refresh();
        $paidTotal = (float) $workOrder->payments()->sum('amount');
        $remaining = max(0, round((float) $workOrder->total_cost - $paidTotal, 2));
        app(\App\Services\ServiceCenterCustomerNotifier::class)->send(
            $workOrder,
            'payment_recorded',
            'გადახდა აღირიცხა',
            'გადახდა მიღებულია. გადახდილი ჯამი: ' . number_format($paidTotal, 2) . ' ₾. დარჩენილი თანხა: ' . number_format($remaining, 2) . ' ₾.'
        );

        return back()->with('success', 'გადახდა აღირიცხა.');
    }

    public function sendQuote(Request $request, WorkOrder $workOrder)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('work_orders'), 403, $center->featureLockedMessage('work_orders'));
        abort_unless($workOrder->service_center_id === $center->id, 404);
        $data = $request->validate([
            'diagnosis' => ['required', 'string', 'max:10000'],
            'work_description' => ['required', 'string', 'max:10000'],
            'labor_cost' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'parts_cost' => ['required', 'numeric', 'min:0', 'max:99999999'],
        ]);
        app(\App\Services\WorkOrderQuoteWorkflow::class)->sendInitial($workOrder, $data);
        app(\App\Services\ServiceCenterCustomerNotifier::class)->send(
            $workOrder->fresh(), 'quote_sent', 'ხარჯთაღრიცხვა დასადასტურებელია',
            'დიაგნოსტიკა და ხარჯთაღრიცხვა მზად არის. გთხოვთ, გაეცნოთ დეტალებს და დაადასტუროთ ან უარყოთ შეკეთება.'
        );
        return back()->with('success', 'ხარჯთაღრიცხვა კლიენტს გაეგზავნა დასადასტურებლად.');
    }

    public function customerApproval(Request $request, string $token)
    {
        $data = $request->validate(['decision' => ['required', 'in:approved,declined']]);
        $order = app(\App\Services\WorkOrderQuoteWorkflow::class)->decide($token, $data['decision']);
        $approved = $data['decision'] === 'approved';
        app(\App\Services\ServiceCenterQuoteDecisionNotifier::class)->send($order, $data['decision']);
        return back()->with('success', $approved ? 'ხარჯთაღრიცხვა დადასტურდა.' : 'უარი დაფიქსირდა. სერვისცენტრი დაგიკავშირდებათ.');
    }

    public function customerStatus(string $token)
    {
        $order = WorkOrder::where('public_token', $token)->with(['serviceCenter','serviceBooking','parts.inventoryItem','payments'])->firstOrFail();
        return view('service-centers.work-order-status', compact('order'));
    }

    public function notify(Request $request, WorkOrder $workOrder)
    {
        $center = $this->center();
        abort_unless($center->canUseFeature('work_orders'), 403, $center->featureLockedMessage('work_orders'));
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
