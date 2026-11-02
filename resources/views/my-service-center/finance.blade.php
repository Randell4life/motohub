@extends('my-service-center.layout')

@section('title','ფინანსური ანგარიშგება | MotoHub')
@section('page-title','ფინანსური ანგარიშგება')
@section('page-subtitle','შემოსავლები, ნაწილების თვითღირებულება, ხარჯები და შეკვეთების მოგებიანობა')

@section('content')
<style>
.fin-hero{padding:22px;margin-bottom:18px;border:1px solid #28466a;border-radius:15px;background:radial-gradient(ellipse at 95% 0,rgba(22,119,255,.2),transparent 42%),linear-gradient(130deg,#111f31,#0b121d)}
.fin-filter{display:flex;gap:10px;flex-wrap:wrap;align-items:end}
.fin-filter label,.fin-form label{display:grid;gap:6px;color:#9aadc4;font-size:11px}
.fin-filter input,.fin-form input,.fin-form select{min-height:41px;padding:9px 11px;border:1px solid #2b4059;border-radius:8px;background:#080e17;color:#f2f7ff}
.fin-btn{min-height:41px;padding:9px 14px;border:1px solid #1677ff;border-radius:8px;background:#1269df;color:#fff;font-weight:800;cursor:pointer}
.fin-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin:17px 0}
.fin-card,.fin-section{min-width:0;padding:17px;border:1px solid #263b54;border-radius:13px;background:linear-gradient(145deg,#111d2c,#0a111b)}
.fin-card small{display:block;color:#91a5be;font-size:11px}.fin-card strong{display:block;margin-top:8px;font-size:clamp(19px,2vw,25px);overflow-wrap:anywhere}
.fin-positive{color:#55d9a7}.fin-negative{color:#ff9aab}.fin-blue{color:#72baff}
.fin-section{margin:17px 0}.fin-section h3{margin:0 0 14px;font-size:16px}
.fin-muted{color:#91a5be;font-size:11px;line-height:1.8}
.fin-table-wrap{overflow-x:auto}.fin-table{width:100%;min-width:720px;border-collapse:collapse;font-size:12px}
.fin-table th,.fin-table td{padding:11px 10px;border-bottom:1px solid #23364c;text-align:left;vertical-align:top}
.fin-table th{color:#91a5be;font-size:10px}
.fin-form{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px}
.fin-form button{align-self:end}
.fin-tag{display:inline-block;padding:4px 8px;border:1px solid #2b5685;border-radius:30px;background:#102541;color:#8fc8ff;font-size:10px}
 .fin-note{padding:12px 14px;border:1px solid #344a64;border-radius:9px;background:#0a1421;color:#a9bad0;font-size:11px;line-height:1.8}
@media print{body{background:#fff!important;color:#111!important}.mh-sidebar,.mh-topbar,.fin-filter,.fin-form,.fin-no-print,button{display:none!important}.mh-main,.mh-content,.mh-page{margin:0!important;padding:0!important;width:100%!important}.fin-section,.fin-card,.fin-hero{background:#fff!important;color:#111!important;border:1px solid #bbb!important;break-inside:avoid}.fin-muted,.fin-card small,.fin-note{color:#333!important}.fin-table th,.fin-table td{color:#111!important;border-color:#bbb!important}.fin-positive,.fin-negative,.fin-blue{color:#111!important}.fin-grid{grid-template-columns:repeat(3,1fr)!important}}
@media(max-width:900px){.fin-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:620px){.fin-grid,.fin-form{grid-template-columns:1fr}.fin-section{padding:14px}.fin-filter>*{flex:1 1 130px}}
</style>

<section class="fin-hero">
 <div style="display:flex;justify-content:space-between;gap:15px;flex-wrap:wrap;align-items:flex-start">
  <div><div class="fin-tag">FINANCIAL CONTROL</div><h2 style="margin:10px 0 5px;font-size:23px">ფინანსური შედეგები</h2><p class="fin-muted" style="margin:0">პერიოდი: {{ $from->format('d.m.Y') }} — {{ $to->format('d.m.Y') }}</p></div>
  <div class="fin-no-print"><button type="button" class="fin-btn" onclick="window.print()">PDF / ბეჭდვა</button></div>
  <form method="GET" action="{{ route('my-service-center.finance') }}" class="fin-filter">
   <label>დან<input type="date" name="from" value="{{ $from->toDateString() }}" required></label>
   <label>მდე<input type="date" name="to" value="{{ $to->toDateString() }}" required></label>
   <button class="fin-btn">ანგარიშის ნახვა</button>
  </form>
 </div>
</section>

<div class="fin-grid">
 <div class="fin-card"><small>დარიცხული შემოსავალი</small><strong class="fin-blue">{{ number_format($billedRevenue,2) }} ₾</strong><div class="fin-muted">შეკვეთების სრული ღირებულება</div></div>
 <div class="fin-card"><small>მიღებული თანხა</small><strong>{{ number_format($collected,2) }} ₾</strong><div class="fin-muted">პერიოდში აღრიცხული გადახდები</div></div>
 <div class="fin-card"><small>ნაწილების თვითღირებულება</small><strong>{{ number_format($partsCost,2) }} ₾</strong><div class="fin-muted">საწყობიდან შეკვეთებზე გაცემული</div></div>
 <div class="fin-card"><small>გაუნაღდებელი ნაშთი</small><strong>{{ number_format($outstanding,2) }} ₾</strong><div class="fin-muted">პერიოდის შეკვეთებზე</div></div>
 <div class="fin-card"><small>ხელობის შემოსავალი</small><strong>{{ number_format($laborRevenue,2) }} ₾</strong><div class="fin-muted">შეკვეთების ხელობის ღირებულება</div></div>
 <div class="fin-card"><small>ნაწილების გაყიდვა</small><strong>{{ number_format($partsRevenue,2) }} ₾</strong><div class="fin-muted">კლიენტისთვის დარიცხული</div></div>
 <div class="fin-card"><small>მთლიანი მოგება ნაწილების შემდეგ</small><strong class="{{ $grossProfit >= 0 ? 'fin-positive' : 'fin-negative' }}">{{ number_format($grossProfit,2) }} ₾</strong><div class="fin-muted">შემოსავალი მინუს ნაწილების თვითღირებულება</div></div>
 <div class="fin-card"><small>ხელოსნების ანაზღაურება</small><strong class="fin-negative">{{ number_format($payrollTotal,2) }} ₾</strong><div class="fin-muted">ავტომატური დარიცხვა დასრულებული შეკვეთებიდან</div></div>
 <div class="fin-card"><small>საერთო ხარჯები</small><strong class="fin-negative">{{ number_format($expenseTotal,2) }} ₾</strong><div class="fin-muted">ხელით აღრიცხული ხარჯები</div></div>
 <div class="fin-card"><small>სავარაუდო წმინდა შედეგი</small><strong class="{{ $netProfit >= 0 ? 'fin-positive' : 'fin-negative' }}">{{ number_format($netProfit,2) }} ₾</strong><div class="fin-muted">დარიცხული შემოსავალი − ნაწილების თვითღირებულება − ხარჯები</div></div>
</div>

<div class="fin-note">ანგარიში ითვლის არჩეულ პერიოდში შექმნილ შეკვეთებს. წმინდა შედეგიდან ცალკე აკლდება ავტომატურად დარიცხული ხელოსნის ანაზღაურება და ქვემოთ ხელით აღრიცხული საოპერაციო ხარჯები. ერთი და იგივე ხელოსნის ანაზღაურება აღარ უნდა შეიყვანო მეორედ ხელით ხარჯებში. დაუფიქსირებელი ხარჯები მოგებაში არ აისახება; ეს არ არის საგადასახადო დეკლარაცია.</div>

<section class="fin-section">
 <h3>ახალი ხარჯის აღრიცხვა</h3>
 <form method="POST" action="{{ route('my-service-center.expenses.store') }}" class="fin-form">
  @csrf
  <label>კატეგორია<select name="category" required>@foreach(\App\Models\ServiceCenterExpense::CATEGORIES as $key=>$label)<option value="{{ $key }}" @selected(old('category')===$key)>{{ $label }}</option>@endforeach</select></label>
  <label>აღწერა<input name="description" maxlength="180" value="{{ old('description') }}" placeholder="მაგ. ხელოსნის ანაზღაურება" required></label>
  <label>თანხა (₾)<input name="amount" type="number" min="0.01" max="999999999" step=".01" value="{{ old('amount') }}" required></label>
  <label>თარიღი<input name="expense_date" type="date" value="{{ old('expense_date', now()->toDateString()) }}" required></label>
  <label>სამუშაო შეკვეთა (არასავალდებულო)<select name="work_order_id"><option value="">ზოგადი ხარჯი</option>@foreach($orders as $order)<option value="{{ $order->id }}" @selected((string)old('work_order_id')===(string)$order->id)>{{ $order->order_number }} — {{ $order->serviceBooking?->customer_name ?? 'კლიენტი' }}</option>@endforeach</select></label>
  <label>ქვითრის / ტრანზაქციის №<input name="reference" maxlength="120" value="{{ old('reference') }}" placeholder="არასავალდებულო"></label>
  <button class="fin-btn">ხარჯის შენახვა</button>
 </form>
</section>

<section class="fin-section">
 <h3>ხარჯები კატეგორიების მიხედვით</h3>
 @forelse($categoryTotals as $category=>$amount)
 <div style="display:flex;justify-content:space-between;gap:12px;padding:10px 0;border-bottom:1px solid #203249"><span>{{ \App\Models\ServiceCenterExpense::CATEGORIES[$category] ?? $category }}</span><strong>{{ number_format($amount,2) }} ₾</strong></div>
 @empty<p class="fin-muted">არჩეულ პერიოდში ხარჯები არ არის აღრიცხული.</p>@endforelse
</section>

<section class="fin-section">
 <h3>ხელოსნების პროდუქტიულობა</h3>
 <div class="fin-grid">
  <div class="fin-card"><small>დარიცხული ანაზღაურება</small><strong>{{ number_format($payrollTotal,2) }} ₾</strong></div>
  <div class="fin-card"><small>გადასახდელი ხელოსნებს</small><strong class="fin-negative">{{ number_format($payrollPending,2) }} ₾</strong></div>
  <div class="fin-card"><small>უკვე გადახდილი</small><strong class="fin-positive">{{ number_format($payrollPaid,2) }} ₾</strong></div>
 </div>
 <div class="fin-table-wrap"><table class="fin-table"><thead><tr><th>ხელოსანი</th><th>დასრულებული შეკვეთები</th><th>ხელობის ბაზა</th><th>დარიცხული</th><th>გადახდილი</th><th>დარჩენილი</th></tr></thead><tbody>
 @forelse($technicianPerformance as $row)
 <tr><td><strong>{{ $row['technician']?->name ?? 'ხელოსანი წაშლილია' }}</strong></td><td>{{ $row['completed_orders'] }}</td><td>{{ number_format($row['labor_base'],2) }} ₾</td><td>{{ number_format($row['earned'],2) }} ₾</td><td>{{ number_format($row['paid'],2) }} ₾</td><td>{{ number_format($row['pending'],2) }} ₾</td></tr>
 @empty<tr><td colspan="6" class="fin-muted">არჩეულ პერიოდში დასრულებული შეკვეთები ან დარიცხული ანაზღაურება ჯერ არ არის.</td></tr>@endforelse
 </tbody></table></div>
</section>

<section class="fin-section">
 <h3>ხელოსნის ანაზღაურების რეესტრი</h3>
 <div class="fin-table-wrap"><table class="fin-table"><thead><tr><th>თარიღი</th><th>ხელოსანი / შეკვეთა</th><th>ტარიფი</th><th>ხელობის ბაზა</th><th>ანაზღაურება</th><th>სტატუსი</th><th class="fin-no-print">მოქმედება</th></tr></thead><tbody>
 @forelse($payroll as $entry)
 <tr><td>{{ $entry->earned_at?->format('d.m.Y') }}</td><td>{{ $entry->technician?->name ?? 'ხელოსანი წაშლილია' }}<div class="fin-muted">{{ $entry->workOrder?->order_number }}</div></td><td>{{ $entry->rate_type === 'fixed_per_order' ? number_format((float)$entry->rate_value,2).' ₾ / შეკვეთა' : number_format((float)$entry->rate_value,2).'% ხელობიდან' }}</td><td>{{ number_format((float)$entry->labor_base,2) }} ₾</td><td><strong>{{ number_format((float)$entry->amount,2) }} ₾</strong></td><td>{{ $entry->status === 'paid' ? 'გადახდილია' : 'გადასახდელია' }} @if($entry->paid_at)<div class="fin-muted">{{ $entry->paid_at->format('d.m.Y H:i') }}</div>@endif</td><td class="fin-no-print">@if($entry->status !== 'paid')<form method="POST" action="{{ route('my-service-center.technician-payroll.paid',$entry) }}">@csrf @method('PATCH')<button class="fin-btn">გადახდილად მონიშვნა</button></form>@else — @endif</td></tr>
 @empty<tr><td colspan="7" class="fin-muted">ანაზღაურება ჯერ არ დარიცხულა. დარიცხვა ხდება შეკვეთის დასრულებისას, თუ ხელოსანს ტარიფი აქვს მითითებული.</td></tr>@endforelse
 </tbody></table></div>
</section>

<section class="fin-section">
 <h3>შეკვეთების მოგებიანობა</h3>
 <div class="fin-table-wrap"><table class="fin-table"><thead><tr><th>შეკვეთა / კლიენტი</th><th>სტატუსი</th><th>დარიცხული</th><th>ნაწილების თვითღ.</th><th>პირდაპირი ხარჯი</th><th>ხელოსნის ანაზღ.</th><th>გადახდილი</th><th>სავარაუდო მოგება</th></tr></thead><tbody>
 @forelse($orderProfitability as $row)
 <tr>
  <td><strong>{{ $row['order']->order_number }}</strong><div class="fin-muted">{{ $row['order']->serviceBooking?->customer_name ?? '—' }} · {{ $row['order']->serviceBooking?->motorcycle ?? '—' }}</div></td>
  <td>{{ $row['order']->status_label }}</td>
  <td>{{ number_format((float)$row['order']->total_cost,2) }} ₾</td>
  <td>{{ number_format($row['parts_cost'],2) }} ₾</td>
  <td>{{ number_format($row['direct_expenses'],2) }} ₾</td>
  <td>{{ number_format($row['technician_payroll'],2) }} ₾</td>
  <td>{{ number_format($row['paid'],2) }} ₾</td>
  <td class="{{ $row['profit'] >= 0 ? 'fin-positive' : 'fin-negative' }}"><strong>{{ number_format($row['profit'],2) }} ₾</strong></td>
 </tr>
 @empty<tr><td colspan="8" class="fin-muted">არჩეულ პერიოდში შეკვეთები არ არის.</td></tr>@endforelse
 </tbody></table></div>
</section>

<section class="fin-section">
 <h3>ხარჯების ისტორია</h3>
 <div class="fin-table-wrap"><table class="fin-table"><thead><tr><th>თარიღი</th><th>კატეგორია</th><th>აღწერა</th><th>შეკვეთა</th><th>თანხა</th><th>რეფერენსი</th></tr></thead><tbody>
 @forelse($expenses as $expense)
 <tr><td>{{ $expense->expense_date?->format('d.m.Y') }}</td><td>{{ \App\Models\ServiceCenterExpense::CATEGORIES[$expense->category] ?? $expense->category }}</td><td>{{ $expense->description }}</td><td>{{ $expense->workOrder?->order_number ?? 'ზოგადი' }}</td><td>{{ number_format((float)$expense->amount,2) }} ₾</td><td>{{ $expense->reference ?? '—' }}</td></tr>
 @empty<tr><td colspan="6" class="fin-muted">ხარჯები ჯერ არ არის.</td></tr>@endforelse
 </tbody></table></div>
</section>

<section class="fin-section">
 <h3>გადახდების ისტორია</h3>
 <div class="fin-table-wrap"><table class="fin-table"><thead><tr><th>თარიღი</th><th>შეკვეთა</th><th>მეთოდი</th><th>თანხა</th><th>შენიშვნა</th></tr></thead><tbody>
 @forelse($payments as $payment)
 <tr><td>{{ $payment->paid_at?->format('d.m.Y H:i') }}</td><td>{{ $payment->workOrder?->order_number ?? '—' }}</td><td>{{ ['cash'=>'ნაღდი','card'=>'ბარათი','transfer'=>'გადარიცხვა','other'=>'სხვა'][$payment->method] ?? $payment->method }}</td><td>{{ number_format((float)$payment->amount,2) }} ₾</td><td>{{ $payment->note ?? '—' }}</td></tr>
 @empty<tr><td colspan="5" class="fin-muted">გადახდები არ არის.</td></tr>@endforelse
 </tbody></table></div>
</section>
@endsection
