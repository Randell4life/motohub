@extends('my-service-center.layout')

@section('title', 'სერვისცენტრის მიმოხილვა | MotoHub')
@section('page-title', 'სერვისცენტრის მიმოხილვა')
@section('page-subtitle', 'მართე ჯავშნები, სამუშაო შეკვეთები და ყოველდღიური ოპერაციები')

@section('public-link')
    <a class="mh-public-link" href="{{ route('service-centers.show', $center->slug) }}" target="_blank" rel="noopener">საჯარო გვერდი ↗</a>
@endsection

@section('content')
<style>
    .sc-welcome{position:relative;overflow:hidden;display:flex;align-items:flex-end;justify-content:space-between;gap:20px;padding:30px;margin-bottom:20px;border:1px solid #284d78;border-radius:16px;background:radial-gradient(ellipse at 85% 15%,rgba(22,119,255,.28),transparent 40%),linear-gradient(120deg,#111e31,#0a111d)}
    .sc-welcome:after{content:"MH";position:absolute;right:24px;bottom:-45px;color:rgba(93,166,255,.06);font-size:190px;font-weight:950;font-style:italic;line-height:1;pointer-events:none}
    .sc-kicker{color:#67b7ff;font-size:10px;font-weight:900;letter-spacing:2.3px}
    .sc-welcome h2{position:relative;z-index:1;margin:9px 0 5px;font-size:clamp(24px,3vw,35px);font-weight:900;letter-spacing:-1px}
    .sc-welcome p{position:relative;z-index:1;margin:0;color:#a8b8cf}
    .sc-live{position:relative;z-index:1;display:inline-flex;align-items:center;gap:8px;padding:9px 12px;border:1px solid #315276;border-radius:9px;background:rgba(6,14,25,.65);white-space:nowrap;font-size:12px;font-weight:800}
    .sc-live i{width:8px;height:8px;border-radius:50%;background:{{ $center->is_active ? '#43d9a5' : '#ff7187' }};box-shadow:0 0 12px currentColor}
    .sc-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-bottom:20px}
    .sc-stat{position:relative;overflow:hidden;padding:19px;border:1px solid #263a55;border-radius:13px;background:linear-gradient(145deg,#111d2e,#0b121e)}
    .sc-stat-top{display:flex;align-items:center;justify-content:space-between;gap:10px;color:#9bacc3;font-size:12px;font-weight:700}
    .sc-stat-icon{display:grid;place-items:center;width:37px;height:37px;border:1px solid #294c73;border-radius:10px;background:rgba(22,119,255,.12);color:#70bcff;font-size:17px}
    .sc-stat strong{display:block;margin:14px 0 2px;font-size:34px;line-height:1;font-weight:900;letter-spacing:-1px}
    .sc-stat small{color:#7f91aa;font-size:11px}
    .sc-columns{display:grid;grid-template-columns:minmax(0,1.1fr) minmax(280px,.9fr);gap:16px;margin-bottom:18px}
    .sc-card-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:17px}
    .sc-card-head h3{margin:0;font-size:16px}
    .sc-tag{color:#66b5ff;font-size:10px;font-weight:900;letter-spacing:1.3px}
    .sc-details{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:15px;margin-top:20px}
    .sc-detail{padding:12px;border:1px solid #243650;border-radius:10px;background:#0a111d}
    .sc-detail span{display:block;margin-bottom:4px;color:#8093ad;font-size:11px}
    .sc-detail strong{display:block;overflow-wrap:anywhere;font-size:13px}
    .sc-actions{display:grid;gap:8px}
    .sc-action{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 13px;border:1px solid #263a52;border-radius:10px;background:#0a111d;transition:.18s ease}
    .sc-action:hover{border-color:#3989ed;background:#101e31;transform:translateX(2px)}
    .sc-action strong{display:block;font-size:13px}
    .sc-action small{display:block;margin-top:3px;color:#8fa2bc;font-size:11px}
    .sc-arrow{color:#62b5ff;font-size:18px}
    .sc-tip{display:flex;align-items:center;gap:13px;padding:17px 19px;border:1px solid #29466a;border-radius:12px;background:linear-gradient(100deg,rgba(22,119,255,.12),rgba(13,20,34,.9))}
    .sc-tip-icon{display:grid;place-items:center;width:40px;height:40px;flex:0 0 40px;border:1px solid #315d8d;border-radius:11px;background:rgba(22,119,255,.13);color:#72bdff;font-size:19px}
    .sc-tip strong{display:block;margin-bottom:2px}
    .sc-tip p{margin:0;color:#98a8c2;font-size:12px}
    @media(max-width:850px){.sc-columns{grid-template-columns:1fr}}
    @media(max-width:600px){.sc-welcome{align-items:flex-start;flex-direction:column;padding:21px}.sc-live{font-size:11px}.sc-stats{grid-template-columns:1fr;gap:9px}.sc-stat{padding:15px}.sc-stat strong{font-size:29px}.sc-details{grid-template-columns:1fr 1fr}}
</style>

<section class="sc-welcome">
    <div>
        <div class="sc-kicker">MOTOHUB / SERVICE OPERATIONS</div>
        <h2>მოგესალმები, {{ $center->name }}</h2>
        <p>ყველაფერი, რაც სერვისცენტრის ყოველდღიური მართვისთვის გჭირდება — ერთ სივრცეში.</p>
    </div>
    <div class="sc-live"><i></i>{{ $center->is_active ? 'სერვისცენტრი აქტიურია' : 'სერვისცენტრი არააქტიურია' }}</div>
</section>

<div class="sc-stats">
    <section class="sc-stat"><div class="sc-stat-top"><span>მოლოდინში</span><span class="sc-stat-icon">◷</span></div><strong>{{ $bookingStats['pending'] ?? 0 }}</strong><small>დასადასტურებელი ჯავშნები</small></section>
    <section class="sc-stat"><div class="sc-stat-top"><span>დადასტურებული</span><span class="sc-stat-icon">✓</span></div><strong>{{ $bookingStats['confirmed'] ?? 0 }}</strong><small>დაგეგმილი ვიზიტები</small></section>
    <section class="sc-stat"><div class="sc-stat-top"><span>დასრულებული</span><span class="sc-stat-icon">↗</span></div><strong>{{ $bookingStats['completed'] ?? 0 }}</strong><small>დასრულებული ჯავშნები</small></section>
</div>

<div class="sc-columns">
    <section class="mh-card">
        <div class="sc-card-head"><h3>სერვისცენტრის ინფორმაცია</h3><span class="sc-tag">CENTER PROFILE</span></div>
        <div class="sc-details">
            <div class="sc-detail"><span>დამტკიცების სტატუსი</span><strong>{{ $center->approval_status === 'approved' ? 'დამტკიცებულია' : ($center->approval_status ?: 'უცნობი') }}</strong></div>
            <div class="sc-detail"><span>ტელეფონი</span><strong>{{ $center->phone ?: 'არ არის მითითებული' }}</strong></div>
            <div class="sc-detail"><span>ქალაქი</span><strong>{{ $center->city ?: 'არ არის მითითებული' }}</strong></div>
            <div class="sc-detail"><span>მისამართი</span><strong>{{ $center->address ?: 'არ არის მითითებული' }}</strong></div>
        </div>
        <div style="margin-top:16px"><a class="mh-btn secondary" href="{{ route('my-service-center.profile') }}">პროფილის მართვა ↗</a></div>
    </section>

    <section class="mh-card">
        <div class="sc-card-head"><h3>სწრაფი მოქმედებები</h3><span class="sc-tag">SHORTCUTS</span></div>
        <div class="sc-actions">
            <a class="sc-action" href="{{ route('my-service-center.bookings') }}"><span><strong>ჯავშნების მართვა</strong><small>ნახე და განაახლე ჯავშნები</small></span><span class="sc-arrow">↗</span></a>
            <a class="sc-action" href="{{ route('my-service-center.work-orders.index') }}"><span><strong>სამუშაო შეკვეთები</strong><small>მართე მიმდინარე სამუშაოები</small></span><span class="sc-arrow">↗</span></a>
            <a class="sc-action" href="{{ route('my-service-center.services') }}"><span><strong>მომსახურებები</strong><small>სერვისების სია, ფასები და ხანგრძლივობა</small></span><span class="sc-arrow">↗</span></a>
            <a class="sc-action" href="{{ route('my-service-center.hours') }}"><span><strong>სამუშაო საათები</strong><small>განაახლე კვირის განრიგი</small></span><span class="sc-arrow">↗</span></a>
            <a class="sc-action" href="{{ route('my-service-center.passport-records.create') }}"><span><strong>პასპორტის ჩანაწერი</strong><small>დაამატე შესრულებული მომსახურება</small></span><span class="sc-arrow">↗</span></a>
        </div>
    </section>
</div>

<section class="sc-tip">
    <div class="sc-tip-icon">⚡</div>
    <div><strong>რჩევა სამუშაო პროცესისთვის</strong><p>ახალი ჯავშნის მიღებისთანავე დაადასტურე ვიზიტი, ხოლო სამუშაოს დაწყებისას შექმენი შესაბამისი სამუშაო შეკვეთა.</p></div>
</section>
@endsection
