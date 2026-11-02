@extends('my-service-center.layout')

@section('title', 'კალენდარი | MotoHub')
@section('page-title', 'ჯავშნების კალენდარი')
@section('page-subtitle', 'კვირის განრიგი და სერვისცენტრის დატვირთვა')

@section('content')
<style>
    .cal-hero{display:flex;justify-content:space-between;align-items:center;gap:18px;flex-wrap:wrap;padding:22px;margin-bottom:20px;border:1px solid #25486d;border-radius:15px;background:radial-gradient(ellipse at 100% 0%,rgba(8,124,255,.2),transparent 45%),linear-gradient(120deg,#111d2c,#0a101a)}
    .cal-eyebrow{color:#54b2ff;font-size:10px;font-weight:800;letter-spacing:2px}
    .cal-hero h2{margin:8px 0 4px;font-size:23px}
    .cal-muted{color:#91a5be;font-size:12px}
    .cal-nav{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
    .cal-nav a,.cal-today{display:inline-flex;align-items:center;justify-content:center;padding:10px 13px;border:1px solid #2b4563;border-radius:8px;background:#101d2d;color:#c9e3ff;text-decoration:none;font-size:12px;font-weight:750}
    .cal-today{background:#1268c9;border-color:#2688f5;color:white}
    .cal-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-bottom:18px}
    .cal-stat{padding:16px;border:1px solid #263a52;border-radius:11px;background:#0d1623}
    .cal-stat strong{display:block;font-size:23px;color:#f3f8ff}
    .cal-stat span{color:#91a5be;font-size:11px}
    .cal-week{display:grid;grid-template-columns:repeat(7,minmax(0,1fr));gap:9px}
    .cal-day{min-width:0;min-height:220px;padding:11px;border:1px solid #263a52;border-radius:11px;background:linear-gradient(160deg,#111b29,#0b121d)}
    .cal-day.today{border-color:#2688f5;box-shadow:inset 0 2px #2688f5}
    .cal-day-head{padding-bottom:10px;margin-bottom:10px;border-bottom:1px solid #26384d}
    .cal-day-name{display:block;color:#a8bdd5;font-size:11px;font-weight:750}
    .cal-day-date{display:block;margin-top:4px;color:#f4f8ff;font-size:17px;font-weight:850}
    .cal-event{display:block;margin:8px 0;padding:9px;border:1px solid #294c73;border-radius:8px;background:rgba(8,124,255,.09);color:#dcecff;text-decoration:none}
    .cal-event.pending{border-color:#66502d;background:#211a10}
    .cal-event-time{display:block;color:#72bdff;font-size:12px;font-weight:850}
    .cal-event.pending .cal-event-time{color:#ffcf78}
    .cal-event-name{display:block;margin-top:4px;font-size:11px;font-weight:750;overflow-wrap:anywhere}
    .cal-event-meta{display:block;margin-top:4px;color:#91a5be;font-size:10px}
    .cal-empty{padding:12px 4px;color:#71849b;font-size:11px;text-align:center}
    .cal-legend{display:flex;gap:14px;flex-wrap:wrap;margin-top:15px;color:#91a5be;font-size:11px}
    .cal-dot{display:inline-block;width:7px;height:7px;border-radius:50%;margin-right:5px;background:#39a3ff}
    .cal-dot.wait{background:#ffbd4a}
    @media(max-width:1100px){.cal-week{grid-template-columns:repeat(4,minmax(0,1fr))}}
    @media(max-width:650px){.cal-week{grid-template-columns:repeat(2,minmax(0,1fr))}.cal-stats{grid-template-columns:1fr 1fr}.cal-day{min-height:170px}}
    @media(max-width:380px){.cal-week{grid-template-columns:1fr}.cal-stats{grid-template-columns:1fr 1fr}}
</style>

<section class="cal-hero">
    <div>
        <div class="cal-eyebrow">MOTOHUB / WORKSHOP CALENDAR</div>
        <h2>კვირის ჯავშნები</h2>
        <div class="cal-muted">{{ $weekStart->format('d.m.Y') }} — {{ $weekEnd->format('d.m.Y') }}</div>
    </div>
    <nav class="cal-nav" aria-label="კალენდრის ნავიგაცია">
        <a href="{{ route('my-service-center.calendar', ['week' => $weekStart->copy()->subWeek()->toDateString()]) }}">← წინა კვირა</a>
        <a class="cal-today" href="{{ route('my-service-center.calendar') }}">დღეს</a>
        <a href="{{ route('my-service-center.calendar', ['week' => $weekStart->copy()->addWeek()->toDateString()]) }}">შემდეგი კვირა →</a>
    </nav>
</section>

<div class="cal-stats">
    <div class="cal-stat"><strong>{{ $bookingCount }}</strong><span>აქტიური ჯავშანი ამ კვირაში</span></div>
    <div class="cal-stat"><strong>{{ $pendingCount }}</strong><span>დადასტურებას ელოდება</span></div>
    <div class="cal-stat"><strong>{{ 7 }}</strong><span>დღე კალენდარში</span></div>
</div>

<section class="mh-card" style="padding:18px">
    <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap;margin-bottom:16px">
        <h3 style="margin:0">კვირის განრიგი</h3>
        <a class="mh-btn" href="{{ route('my-service-center.bookings') }}" style="text-decoration:none">ყველა ჯავშანი →</a>
    </div>

    <div class="cal-week">
        @foreach($days as $day)
            <article class="cal-day {{ $day['date']->isToday() ? 'today' : '' }}">
                <header class="cal-day-head">
                    <span class="cal-day-name">{{ $day['name'] }}</span>
                    <span class="cal-day-date">{{ $day['date']->format('d.m') }}</span>
                    <span class="cal-muted">{{ $day['bookings']->count() }} ჯავშანი</span>
                </header>

                @forelse($day['bookings'] as $booking)
                    <a class="cal-event {{ $booking->status === 'pending' ? 'pending' : '' }}" href="{{ route('my-service-center.bookings') }}">
                        <span class="cal-event-time">{{ $booking->scheduled_at?->format('H:i') ?? '—' }} · {{ $booking->duration_minutes ?? 60 }} წთ</span>
                        <span class="cal-event-name">{{ $booking->customer_name }}</span>
                        <span class="cal-event-meta">{{ $booking->service_name ?: 'მომსახურება' }}</span>
                        @if($booking->motorcycle)
                            <span class="cal-event-meta">{{ $booking->motorcycle }}</span>
                        @endif
                        <span class="cal-event-meta">{{ $booking->status === 'pending' ? 'მოლოდინში' : 'დადასტურებული' }}</span>
                        @if($booking->workOrder)
                            <span class="cal-event-meta">შეკვეთა: {{ $booking->workOrder->order_number }}</span>
                        @endif
                    </a>
                @empty
                    <div class="cal-empty">ჯავშანი არ არის</div>
                @endforelse
            </article>
        @endforeach
    </div>

    <div class="cal-legend">
        <span><i class="cal-dot"></i> დადასტურებული</span>
        <span><i class="cal-dot wait"></i> მოლოდინში</span>
        <span>ბარათზე დაჭერით გადახვალ ჯავშნების სრულ სიაში</span>
    </div>
</section>
@endsection
