<!doctype html>
<html lang="ka"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>შეკვეთის სტატუსი | MotoHub</title>
<style>
*{box-sizing:border-box}body{margin:0;background:#070a12;color:#f3f7ff;font-family:Arial,sans-serif}.wrap{max-width:900px;margin:0 auto;padding:28px 18px}.brand{color:#65b4ff;font-weight:900;letter-spacing:3px}.panel{margin-top:18px;padding:22px;border:1px solid #263b55;border-radius:15px;background:linear-gradient(145deg,#111d2c,#0b111a)}h1{font-size:clamp(24px,5vw,36px);margin:12px 0}.muted{color:#92a6bf;font-size:13px;line-height:1.8}.pill{display:inline-block;border:1px solid #2863a1;background:#102747;color:#9bceff;border-radius:30px;padding:8px 12px;font-size:12px}.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}.grid div{padding:14px;border:1px solid #263b55;border-radius:10px;background:#0a121e}.grid small{display:block;color:#91a5be;margin-bottom:8px}.grid strong{font-size:15px}.line{padding:14px 0;border-bottom:1px solid #23364c}.line:last-child{border:0}.workflow{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin:18px 0}.workflow-step{display:flex;align-items:center;gap:9px;padding:12px;border:1px solid #263b55;border-radius:10px;color:#91a5be;font-size:13px}.workflow-dot{width:10px;height:10px;flex:0 0 10px;border-radius:50%;background:#42546b}.workflow-step.is-done{border-color:#2863a1;background:#102747;color:#c8e4ff}.workflow-step.is-done .workflow-dot{background:#42a5ff;box-shadow:0 0 12px #1677ff88}@media(max-width:600px){.workflow{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:600px){.grid{grid-template-columns:1fr}.panel{padding:16px}}
</style></head><body><main class="wrap"><div class="brand">MOTOHUB · SERVICE TRACKING</div>
<section class="panel"><div class="pill">{{ $order->status_label }}</div><h1>{{ $order->order_number }}</h1><p class="muted">{{ $order->serviceCenter->name }} · {{ $order->serviceCenter->city }}</p>
<div class="grid"><div><small>მოტოციკლი</small><strong>{{ $order->serviceBooking?->motorcycle ?? '—' }}</strong></div><div><small>მიღების გარბენი</small><strong>{{ $order->intake_odometer ? number_format($order->intake_odometer).' კმ' : '—' }}</strong></div><div><small>სულ ღირებულება</small><strong>{{ number_format((float)($order->customer_approval_status === 'pending' ? $order->quoted_total_cost : $order->total_cost),2) }} ₾</strong></div></div></section>
@php
$workflowSteps = [
    'received' => 'მიღებულია',
    'diagnostics' => 'დიაგნოსტიკა',
    'waiting_for_parts' => 'ნაწილების მოლოდინი',
    'in_repair' => 'შეკეთება',
    'completed' => 'შეკეთება დასრულდა',
    'handed_over' => 'მოტოციკლი გადაცემულია',
];
$workflowKeys = array_keys($workflowSteps);
$currentStep = array_search($order->status, $workflowKeys, true);
$isCancelled = $order->status === 'cancelled';
@endphp
<section class="panel"><h2>შეკვეთის ეტაპები</h2>
@if($isCancelled)
<div class="pill" style="border-color:#8a3c4a;background:#321820;color:#ffc0c8">შეკვეთა გაუქმებულია</div>
@else
<div class="workflow">
@foreach($workflowSteps as $key => $label)
<div class="workflow-step {{ $currentStep !== false && array_search($key, $workflowKeys, true) <= $currentStep ? 'is-done' : '' }}">
<span class="workflow-dot"></span><span>{{ $label }}</span>
</div>
@endforeach
</div>
@endif
<p class="muted">ინფორმაცია განახლდება ავტომატურად, როგორც კი სერვისცენტრი შეკვეთის ეტაპს შეცვლის.</p>
</section>
<section class="panel"><h2>შეკეთების ინფორმაცია</h2><div class="line"><div class="muted">დიაგნოსტიკა</div>{{ $order->diagnosis ?: 'დიაგნოსტიკა ჯერ არ არის დამატებული.' }}</div><div class="line"><div class="muted">შესრულებული სამუშაო</div>{{ $order->work_description ?: 'სამუშაო ჯერ არ დასრულებულა.' }}</div></section>
@if($order->quote_sent_at)
<section class="panel"><h2>დიაგნოსტიკა და ხარჯთაღრიცხვა</h2>
<div class="line"><div class="muted">დიაგნოსტიკა</div>{{ $order->diagnosis ?: '—' }}</div>
<div class="line"><div class="muted">შესასრულებელი სამუშაო</div>{{ $order->work_description ?: '—' }}</div>
<div class="grid">
<div><small>ხელობა</small><strong>{{ number_format((float)$order->quoted_labor_cost,2) }} ₾</strong></div>
<div><small>ნაწილები</small><strong>{{ number_format((float)$order->quoted_parts_cost,2) }} ₾</strong></div>
<div><small>სულ</small><strong>{{ number_format((float)$order->quoted_total_cost,2) }} ₾</strong></div>
</div>
@if($order->customer_approval_status === 'pending')
<p class="muted">შეკეთება დაიწყება მხოლოდ თქვენი თანხმობის შემდეგ.</p>
<form method="POST" action="{{ route('service-centers.work-orders.approval',$order->public_token) }}" style="display:flex;gap:10px;flex-wrap:wrap;margin-top:16px">
@csrf
<button name="decision" value="approved" style="padding:13px 18px;border:0;border-radius:9px;background:#1677ff;color:white;font-weight:700">ვეთანხმები · შეკეთების დაწყება</button>
<button name="decision" value="declined" style="padding:13px 18px;border:1px solid #52647a;border-radius:9px;background:#172233;color:#f3f7ff">არ ვეთანხმები</button>
</form>
@elseif($order->customer_approval_status === 'approved')
<div class="pill">ხარჯთაღრიცხვა დადასტურებულია · {{ $order->customer_approved_at?->format('d.m.Y H:i') }}</div>
@elseif($order->customer_approval_status === 'declined')
<div class="pill">ხარჯთაღრიცხვა უარყოფილია · სერვისცენტრი დაგიკავშირდებათ</div>
@endif
</section>
@endif
<section class="panel"><h2>ხარჯთაღრიცხვის ისტორია</h2>
@forelse($order->quoteRevisions as $revision)
<div class="line">
 <div class="muted">ვერსია #{{ $revision->revision_number }} · {{ $revision->type === 'initial' ? 'საწყისი შეთავაზება' : 'დამატებითი ხარჯი' }} · {{ $revision->sent_at?->format('d.m.Y H:i') }}</div>
 <strong>{{ number_format((float)$revision->total_cost,2) }} ₾</strong>
 <div class="muted">ხელობა {{ number_format((float)$revision->labor_cost,2) }} ₾ · ნაწილები {{ number_format((float)$revision->parts_cost,2) }} ₾ · {{ match($revision->status) { 'approved' => 'დადასტურებულია', 'declined' => 'უარყოფილია', default => 'პასუხს ელოდება' } }}</div>
 @foreach($revision->additions ?? [] as $addition)
 <div class="muted">დამატება: {{ $addition['name'] ?? $addition['type'] }} · {{ $addition['quantity'] ?? 1 }} ც. · {{ number_format((float)($addition['total'] ?? 0),2) }} ₾</div>
 @endforeach
 @if($revision->status === 'pending')<div class="muted">კლიენტის პასუხს ველოდებით.</div>@endif
</div>
@empty
<p class="muted">ხარჯთაღრიცხვის ისტორია ჯერ ცარიელია.</p>
@endforelse
</section>
<section class="panel"><h2>ნაწილები</h2>@forelse($order->parts as $part)<div class="line">{{ $part->inventoryItem->name }} · {{ $part->quantity }} ც. · {{ number_format((float)$part->total,2) }} ₾</div>@empty<p class="muted">ნაწილები ჯერ არ არის დამატებული.</p>@endforelse</section>
<section class="panel"><h2>გადახდები</h2><div class="grid"><div><small>გადახდილი</small><strong>{{ number_format((float)$order->payments->sum('amount'),2) }} ₾</strong></div><div><small>დარჩენილი</small><strong>{{ number_format(max(0,(float)$order->total_cost-(float)$order->payments->sum('amount')),2) }} ₾</strong></div><div><small>სერვის ცენტრი</small><strong>{{ $order->serviceCenter->phone }}</strong></div></div></section>
<section class="panel"><h2>განახლებები</h2>@forelse($order->activityLogs()->latest()->get() as $log)<div class="line"><div class="muted">{{ $log->created_at->format('d.m.Y H:i') }}</div>{{ $log->message }}</div>@empty<p class="muted">განახლებები ჯერ არ არის.</p>@endforelse</section>
<p class="muted" style="text-align:center;margin:24px 0">MotoHub · მოტოციკლის სერვისის ციფრული ისტორია</p></main></body></html>
