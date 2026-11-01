@extends('my-service-center.layout')
@section('title','სერვის ცენტრის მართვა | MotoHub')
@section('page-title','სერვის ცენტრის მართვა')
@section('page-subtitle','სამუშაოები, კალენდარი, გუნდი, საწყობი და ფინანსები ერთ სივრცეში')

@section('content')
<style>
.ops-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:13px;margin-bottom:20px}
.ops-card{background:linear-gradient(145deg,#111d2c,#0a111b);border:1px solid #263b54;border-radius:13px;padding:17px;min-width:0}
.ops-card small{color:#91a5be;font-size:11px}.ops-card strong{display:block;font-size:24px;margin-top:8px;color:#f2f7ff}
.ops-section{margin:18px 0;padding:20px;background:#0d1623;border:1px solid #263b54;border-radius:14px}
.ops-section h3{margin:0 0 14px;font-size:16px}
.ops-form{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px}
.ops-form input,.ops-form select,.ops-form button,.ops-inline select,.ops-inline input{width:100%;padding:11px;border-radius:8px;border:1px solid #2d435e;background:#080e17;color:#edf4ff;font:inherit;font-size:12px}
.ops-form button,.ops-inline button{background:#1269df;border-color:#2787ff;font-weight:800;cursor:pointer}
.ops-table{width:100%;border-collapse:collapse;min-width:700px;font-size:12px}
.ops-table th,.ops-table td{padding:12px;border-bottom:1px solid #23364c;text-align:left;vertical-align:top}
.ops-table th{color:#91a5be;font-size:10px;text-transform:uppercase}
.ops-table-wrap{overflow:auto}
.ops-muted{color:#91a5be;font-size:11px;line-height:1.7}
.ops-pill{display:inline-block;border:1px solid #2c5685;background:#102541;color:#8fc8ff;border-radius:20px;padding:5px 8px;font-size:10px}
.ops-inline{display:flex;gap:7px;align-items:center;min-width:180px}
.ops-inline>*{min-width:0}
@media(max-width:850px){.ops-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.ops-form{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.ops-grid,.ops-form{grid-template-columns:1fr}.ops-section{padding:14px}}
</style>

@if(session('success'))<div class="ops-section" style="color:#5ce0ad">{{ session('success') }}</div>@endif
@if($errors->any())<div class="ops-section" style="color:#ff9aa9">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif


<section class="ops-section">
 <h3>გამოწერის მართვა</h3>
 <div class="ops-grid">
  <div class="ops-card"><small>მიმდინარე პაკეტი</small><strong>{{ strtoupper($center->subscription_plan ?? 'START') }}</strong></div>
  <div class="ops-card"><small>სტატუსი</small><strong>{{ match($center->effectiveSubscriptionStatus()) { 'active' => 'აქტიური', 'pending_payment' => 'გადახდის მოლოდინში', 'past_due' => 'ვადაგადაცილებული', 'expired' => 'ვადაგასული', 'cancelled' => 'გაუქმებული', default => $center->effectiveSubscriptionStatus() } }}</strong></div>
  <div class="ops-card"><small>ღირებულება</small><strong>{{ number_format((float)$center->subscription_price, 2) }} ₾ / თვე</strong></div>
  <div class="ops-card"><small>ვადის დასრულება</small><strong style="font-size:16px">{{ $center->subscription_ends_at?->format('d.m.Y') ?? 'უვადო / START' }}</strong></div>
 </div>
 @if($center->subscription_requested_plan)
  <p class="ops-muted">მოთხოვნილია {{ strtoupper($center->subscription_requested_plan) }} პაკეტი · {{ $center->subscription_requested_at?->format('d.m.Y H:i') }}. {{ $center->subscription_request_note }}</p>
 @endif
 <form method="POST" action="{{ route('my-service-center.subscription.request') }}" class="ops-form">
  @csrf
  <select name="subscription_plan" required>
   @foreach(\App\Models\ServiceCenter::subscriptionPlans() as $key => $plan)
    <option value="{{ $key }}" @selected(($center->subscription_requested_plan ?: $center->subscription_plan) === $key)>{{ $plan['name'] }} — {{ $plan['price'] }} ₾ / თვე</option>
   @endforeach
  </select>
  <input name="subscription_request_note" maxlength="500" placeholder="შენიშვნა / გადახდის მითითება (არასავალდებულო)">
  <button>პაკეტის მოთხოვნა</button>
 </form>
 <p class="ops-muted">ფასიანი პაკეტის მოთხოვნის შემდეგ MotoHub-ის გუნდი დაგიკავშირდება გადახდის ინსტრუქციისთვის. პაკეტი გააქტიურდება ადმინისტრატორის მიერ გადახდის დადასტურების შემდეგ. ეს ეტაპი ჯერ ავტომატურ საბანკო გადახდას არ ასრულებს.</p>
 <div class="ops-muted">ხელმისაწვდომი ფუნქციები: {{ collect($features)->filter()->keys()->map(fn($x) => str_replace('_',' ', $x))->join(' · ') ?: 'მხოლოდ საბაზისო ფუნქციები' }}</div>
</section>

<div class="ops-grid">
 <div class="ops-card"><small>აქტიური შეკვეთები</small><strong>{{ $stats['open'] }}</strong></div>
 <div class="ops-card"><small>შეკეთების პროცესში</small><strong>{{ $stats['repair'] }}</strong></div>
 <div class="ops-card"><small>შემოსავალი არჩეულ პერიოდში</small><strong>{{ number_format((float)$stats['revenue'],2) }} ₾</strong></div>
 <div class="ops-card"><small>გადასახდელი ნაშთი</small><strong>{{ number_format(max(0,(float)$stats['outstanding']),2) }} ₾</strong></div>
</div>

@if($features['work_orders'])
<section class="ops-section">
 <h3>სამუშაო შეკვეთები · კანბანის სტატუსები</h3>
 <form method="GET" class="ops-form" style="margin-bottom:14px">
  <input name="q" value="{{ request('q') }}" placeholder="შეკვეთა, კლიენტი ან მოტოციკლი">
  <select name="status"><option value="">ყველა სტატუსი</option>@foreach(\App\Models\WorkOrder::STATUSES as $status)<option value="{{ $status }}" @selected(request('status')===$status)>{{ \Illuminate\Support\Str::headline($status) }}</option>@endforeach</select>
  <button>ფილტრაცია</button>
 </form>
 <div class="ops-table-wrap"><table class="ops-table"><thead><tr><th>შეკვეთა</th><th>კლიენტი / მოტო</th><th>სტატუსი</th><th>ხელოსანი / ბოქსი</th><th>ღირებულება</th><th>მოქმედება</th></tr></thead><tbody>
 @forelse($orders as $order)
 <tr>
  <td><a style="color:#73baff" href="{{ route('my-service-center.work-orders.show',$order) }}">{{ $order->order_number }}</a><div class="ops-muted">{{ $order->created_at->format('d.m.Y H:i') }}</div></td>
  <td>{{ $order->serviceBooking?->customer_name ?? 'კლიენტი არ არის მითითებული' }}<div class="ops-muted">{{ $order->serviceBooking?->motorcycle ?? '—' }}</div></td>
  <td><span class="ops-pill">{{ $order->status_label }}</span></td>
  <td>{{ $order->technician?->name ?? 'მიუნიჭებელია' }}<div class="ops-muted">{{ $order->bay?->name ?? 'ბოქსი —' }}</div></td>
  <td>{{ number_format((float)$order->total_cost,2) }} ₾<div class="ops-muted">გადახდილი: {{ number_format((float)$order->payments->sum('amount'),2) }} ₾</div></td>
  <td>
   <form class="ops-inline" method="POST" action="{{ route('my-service-center.work-orders.assignment',$order) }}">@csrf @method('PATCH')
    <select name="assigned_technician_id"><option value="">ხელოსანი</option>@foreach($technicians as $tech)<option value="{{ $tech->id }}" @selected($order->service_center_technician_id===$tech->id)>{{ $tech->name }}</option>@endforeach</select>
    <select name="service_center_bay_id"><option value="">ბოქსი</option>@foreach($bays as $bay)<option value="{{ $bay->id }}" @selected($order->service_center_bay_id===$bay->id)>{{ $bay->name }}</option>@endforeach</select>
    <button>შენახვა</button>
   </form>
  </td>
 </tr>
 @empty<tr><td colspan="6" class="ops-muted">ჯერ სამუშაო შეკვეთები არ არის.</td></tr>@endforelse
 </tbody></table></div>
 <div style="margin-top:14px">{{ $orders->links() }}</div>
</section>
@else
 <div class="ops-section"><h3>PRO პაკეტი საჭიროა</h3><p class="ops-muted">ეს განყოფილება ხელმისაწვდომია PRO პაკეტიდან. გამოწერის შეცვლა შეგიძლია ამ გვერდის ზედა ნაწილში.</p></div>
@endif

@if($features['work_orders'])
<section class="ops-section">
 <h3>კალენდარი · {{ $from->format('d.m.Y') }} — {{ $to->format('d.m.Y') }}</h3>
 <div class="ops-table-wrap"><table class="ops-table"><thead><tr><th>დრო</th><th>შეკვეთა</th><th>კლიენტი</th><th>ხელოსანი</th><th>ბოქსი</th></tr></thead><tbody>
 @forelse($calendar as $entry)<tr><td>{{ $entry->serviceBooking?->scheduled_at?->format('d.m.Y H:i') ?? 'დრო არაა' }}</td><td>{{ $entry->order_number }}</td><td>{{ $entry->serviceBooking?->customer_name ?? '—' }}</td><td>{{ $entry->technician?->name ?? $entry->assignedTechnician?->name ?? '—' }}</td><td>{{ $entry->bay?->name ?? '—' }}</td></tr>@empty<tr><td colspan="5" class="ops-muted">ამ კვირაში დაგეგმილი სამუშაოები არ არის.</td></tr>@endforelse
 </tbody></table></div>
</section>
@else
 <div class="ops-section"><h3>PRO პაკეტი საჭიროა</h3><p class="ops-muted">ეს განყოფილება ხელმისაწვდომია PRO პაკეტიდან. გამოწერის შეცვლა შეგიძლია ამ გვერდის ზედა ნაწილში.</p></div>
@endif

@if($features['team'])
<div class="ops-grid" style="grid-template-columns:repeat(2,minmax(0,1fr))">
 <section class="ops-section"><h3>ხელოსნის დამატება</h3><form class="ops-form" method="POST" action="{{ route('my-service-center.technicians.store') }}">@csrf<input name="name" required placeholder="სახელი და გვარი"><input name="phone" placeholder="ტელეფონი"><input name="specialty" placeholder="სპეციალიზაცია"><button>დამატება</button></form>
 @foreach($technicians as $tech)<p class="ops-muted">{{ $tech->name }} · {{ $tech->specialty ?? 'სპეციალიზაცია არაა' }}</p>@endforeach</section>
 <section class="ops-section"><h3>სამუშაო ბოქსის დამატება</h3><form class="ops-form" method="POST" action="{{ route('my-service-center.bays.store') }}">@csrf<input name="name" required placeholder="მაგ. ბოქსი №1"><input name="description" placeholder="აღწერა"><button>დამატება</button></form>
 @foreach($bays as $bay)<p class="ops-muted">{{ $bay->name }}</p>@endforeach</section>
</div>
@else
 <div class="ops-section"><h3>PRO პაკეტი საჭიროა</h3><p class="ops-muted">ეს განყოფილება ხელმისაწვდომია PRO პაკეტიდან. გამოწერის შეცვლა შეგიძლია ამ გვერდის ზედა ნაწილში.</p></div>
@endif

@if($features['inventory'])
<section class="ops-section">
 <h3>საწყობი @if($lowStock)<span class="ops-pill">დაბალი ნაშთი: {{ $lowStock }}</span>@endif</h3>
 <form class="ops-form" method="POST" action="{{ route('my-service-center.inventory.store') }}">@csrf
  <input name="name" required placeholder="ნაწილის დასახელება"><input name="sku" placeholder="SKU / კოდი"><input name="brand" placeholder="ბრენდი">
  <input name="quantity" type="number" min="0" required placeholder="რაოდენობა"><input name="min_quantity" type="number" min="0" value="1" required placeholder="მინ. ნაშთი"><input name="cost_price" type="number" min="0" step=".01" required placeholder="თვითღირებულება">
  <input name="sale_price" type="number" min="0" step=".01" required placeholder="გასაყიდი ფასი"><input name="category" placeholder="კატეგორია"><input name="fitment" placeholder="თავსებადობა">
  <button>ნაწილის დამატება</button>
 </form>
 <div class="ops-table-wrap" style="margin-top:15px"><table class="ops-table"><thead><tr><th>ნაწილი</th><th>კოდი</th><th>ნაშთი</th><th>მინიმუმი</th><th>თვითღირებულება</th><th>ფასი</th></tr></thead><tbody>
 @forelse($inventory as $item)<tr><td>{{ $item->name }}<div class="ops-muted">{{ $item->brand }}</div></td><td>{{ $item->sku ?? '—' }}</td><td>{{ $item->quantity }} @if($item->quantity <= $item->min_quantity)<span class="ops-pill">შესავსებია</span>@endif</td><td>{{ $item->min_quantity }}</td><td>{{ number_format((float)$item->cost_price,2) }} ₾</td><td>{{ number_format((float)$item->sale_price,2) }} ₾</td></tr>@empty<tr><td colspan="6" class="ops-muted">საწყობი ცარიელია.</td></tr>@endforelse
 </tbody></table></div>
</section>
@else
 <div class="ops-section"><h3>BUSINESS პაკეტი საჭიროა</h3><p class="ops-muted">ეს განყოფილება ხელმისაწვდომია BUSINESS პაკეტიდან. გამოწერის შეცვლა შეგიძლია ამ გვერდის ზედა ნაწილში.</p></div>
@endif

@if($features['finance'])
<section class="ops-section">
 <h3>ბოლო გადახდები</h3><div class="ops-table-wrap"><table class="ops-table"><thead><tr><th>თარიღი</th><th>შეკვეთა</th><th>თანხა</th><th>მეთოდი</th></tr></thead><tbody>
 @forelse($payments as $payment)<tr><td>{{ $payment->paid_at?->format('d.m.Y H:i') }}</td><td>{{ $payment->workOrder?->order_number }}</td><td>{{ number_format((float)$payment->amount,2) }} ₾</td><td>{{ $payment->method }}</td></tr>@empty<tr><td colspan="4" class="ops-muted">გადახდები ჯერ არ არის.</td></tr>@endforelse
 </tbody></table></div>
</section>
@else
 <div class="ops-section"><h3>PRO პაკეტი საჭიროა</h3><p class="ops-muted">ეს განყოფილება ხელმისაწვდომია PRO პაკეტიდან. გამოწერის შეცვლა შეგიძლია ამ გვერდის ზედა ნაწილში.</p></div>
@endif
@endsection
