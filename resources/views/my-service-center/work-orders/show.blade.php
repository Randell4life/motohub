@extends('my-service-center.layout')

@section('title', 'შეკვეთის დეტალები | MotoHub')
@section('page-title', 'შეკვეთის დეტალები')
@section('page-subtitle', 'სამუშაოს მიმდინარეობა, მოტოციკლის მონაცემები და ხარჯები')

@section('content')
@php
    $labels = [
        'received' => 'მიღებული',
        'diagnostics' => 'დიაგნოსტიკა',
        'waiting_for_parts' => 'ნაწილების მოლოდინი',
        'in_repair' => 'შეკეთების პროცესში',
        'completed' => 'დასრულებული',
        'handed_over' => 'ჩაბარებული',
        'cancelled' => 'გაუქმებული',
    ];

    $booking = $workOrder->serviceBooking;

    $statusColors = [
        'received' => '#60a5fa',
        'diagnostics' => '#a78bfa',
        'waiting_for_parts' => '#fbbf24',
        'in_repair' => '#38bdf8',
        'completed' => '#4ade80',
        'handed_over' => '#94a3b8',
        'cancelled' => '#f87171',
    ];

    $statusColor = $statusColors[$workOrder->status] ?? '#60a5fa';

    $photos = array_values(array_filter(
        $workOrder->intake_photos ?? [],
        fn ($photo) => is_string($photo)
            && str_starts_with($photo, 'work-orders/')
    ));
@endphp

<style>
    .od-hero {
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        flex-wrap:wrap;
        gap:20px;
        padding:24px;
        margin-bottom:20px;
        border:1px solid #25476a;
        border-radius:15px;
        background:
            radial-gradient(ellipse at 90% 0%,rgba(22,119,255,.2),transparent 45%),
            linear-gradient(120deg,#111e2d,#0a1019);
    }

    .od-eyebrow {
        color:#64b5ff;
        font-size:10px;
        font-weight:800;
        letter-spacing:2px;
    }

    .od-title {
        margin:9px 0;
        font-size:clamp(23px,3vw,31px);
        overflow-wrap:anywhere;
    }

    .od-muted {
        color:#91a5be;
        font-size:12px;
        line-height:1.8;
    }

    .od-actions {
        display:flex;
        flex-wrap:wrap;
        gap:9px;
        margin-top:17px;
    }

    .od-btn {
        display:inline-flex;
        justify-content:center;
        align-items:center;
        gap:8px;
        min-height:41px;
        padding:10px 14px;
        border:1px solid #2b4059;
        border-radius:9px;
        background:#101c2b;
        color:#dceaff;
        font-size:12px;
        font-weight:750;
        text-decoration:none;
        cursor:pointer;
    }

    .od-btn:hover { border-color:#398fff; }

    .od-btn.primary {
        border-color:#1677ff;
        background:linear-gradient(135deg,#1677ff,#0758c7);
        color:white;
    }

    .od-status {
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:9px 12px;
        border:1px solid color-mix(in srgb,var(--od-color) 35%,transparent);
        border-radius:9px;
        background:color-mix(in srgb,var(--od-color) 10%,transparent);
        color:var(--od-color);
        font-size:12px;
        font-weight:800;
    }

    .od-dot {
        width:7px;
        height:7px;
        border-radius:50%;
        background:currentColor;
    }

    .od-summary {
        display:grid;
        grid-template-columns:1.5fr repeat(3,minmax(0,1fr));
        gap:0;
        align-items:center;
        margin-bottom:20px;
        padding:19px;
        border:1px solid #254365;
        border-radius:13px;
        background:radial-gradient(ellipse at 0 0,#112744,#0a121e 70%);
    }

    .od-bike {
        display:flex;
        align-items:center;
        gap:13px;
        min-width:0;
        padding-right:14px;
    }

    .od-bike-icon {
        display:grid;
        place-items:center;
        flex:0 0 46px;
        height:46px;
        border:1px solid #2d5480;
        border-radius:11px;
        background:#102746;
        color:#69b8ff;
        font-size:23px;
    }

    .od-bike strong {
        display:block;
        overflow-wrap:anywhere;
        font-size:14px;
    }

    .od-bike small {
        display:block;
        margin-top:5px;
        color:#8195ad;
        font-size:11px;
    }

    .od-metric {
        min-width:0;
        padding:5px 13px;
        border-left:1px solid #26374b;
    }

    .od-metric span {
        display:block;
        margin-bottom:8px;
        color:#91a5be;
        font-size:10px;
    }

    .od-metric strong {
        display:block;
        overflow-wrap:anywhere;
        font-size:14px;
    }

    .od-blue { color:#55a7ff; }

    .od-grid {
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:18px;
    }

    .od-card {
        min-width:0;
        padding:21px;
        margin-bottom:18px;
        border:1px solid #263a52;
        border-radius:13px;
        background:linear-gradient(145deg,#111c2a,#0b111a);
    }

    .od-card-head {
        display:flex;
        align-items:center;
        gap:11px;
        padding-bottom:15px;
        margin-bottom:16px;
        border-bottom:1px solid #243449;
    }

    .od-card-icon {
        display:grid;
        place-items:center;
        flex:0 0 36px;
        height:36px;
        border:1px solid #294c73;
        border-radius:9px;
        background:rgba(8,124,255,.12);
        color:#69b8ff;
        font-size:17px;
    }

    .od-card-head h3 {
        margin:0;
        font-size:15px;
    }

    .od-card-head p {
        margin:5px 0 0;
        color:#8195ad;
        font-size:11px;
    }

    .od-fields {
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:19px 22px;
    }

    .od-field { min-width:0; }

    .od-field label {
        display:block;
        margin-bottom:7px;
        color:#91a5be;
        font-size:11px;
    }

    .od-value {
        color:#e3edfc;
        font-size:13px;
        line-height:1.8;
        overflow-wrap:anywhere;
    }

    .od-text {
        padding:13px;
        border:1px solid #24374d;
        border-radius:9px;
        background:#080e17;
        color:#d4e1f2;
        font-size:13px;
        line-height:1.9;
        white-space:pre-wrap;
        overflow-wrap:anywhere;
    }

    .od-photo-grid {
        display:grid;
        grid-template-columns:repeat(3,minmax(0,1fr));
        gap:10px;
    }

    .od-photo {
        display:block;
        overflow:hidden;
        aspect-ratio:4/3;
        border:1px solid #263a52;
        border-radius:9px;
        background:#080e17;
    }

    .od-photo img {
        width:100%;
        height:100%;
        display:block;
        object-fit:cover;
        transition:transform .2s;
    }

    .od-photo:hover img { transform:scale(1.04); }

    .od-empty {
        padding:22px 12px;
        border:1px dashed #344963;
        border-radius:9px;
        color:#91a5be;
        font-size:12px;
        text-align:center;
        line-height:1.8;
    }

    .od-cost {
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:12px;
        padding:14px 0;
        border-bottom:1px solid #1e2d40;
        font-size:12px;
    }

    .od-cost:last-child { border-bottom:0; }

    .od-cost span { color:#9aafc8; }

    .od-cost strong {
        font-variant-numeric:tabular-nums;
        white-space:nowrap;
    }

    .od-total {
        padding-top:18px;
        margin-top:3px;
    }

    .od-total strong {
        color:#55a7ff;
        font-size:22px;
    }

    .od-status-form {
        display:flex;
        align-items:center;
        flex-wrap:wrap;
        gap:10px;
    }

    .od-select {
        width:100%;
        max-width:320px;
        min-height:43px;
        padding:10px 12px;
        border:1px solid #2b4059;
        border-radius:9px;
        background:#080e17;
        color:#edf4ff;
        font-size:12px;
    }

    .od-timeline {
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:12px;
    }

    .od-time-item {
        padding:14px;
        border:1px solid #263a52;
        border-radius:9px;
        background:#0a121e;
    }

    .od-time-item span {
        display:block;
        margin-bottom:8px;
        color:#91a5be;
        font-size:11px;
    }

    .od-time-item strong {
        font-size:12px;
        line-height:1.7;
    }

    @media(max-width:950px) {
        .od-summary { grid-template-columns:repeat(2,minmax(0,1fr)); gap:18px; }
        .od-bike { padding-right:0; }
        .od-metric { border-left:0; padding:5px 0; }
    }

    @media(max-width:650px) {
        .od-hero,.od-card { padding:16px; }
        .od-summary { padding:15px; gap:15px; }
        .od-grid,.od-fields,.od-timeline { grid-template-columns:1fr; }
        .od-photo-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .od-actions .od-btn { flex:1; }
        .od-status-form { align-items:stretch; }
        .od-select { max-width:none; }
        .od-status-form .od-btn { width:100%; }
    }
</style>

@if(session('success'))
    <div class="mh-card" style="margin-bottom:18px;color:#58e0ae">
        ✓ {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mh-card" style="margin-bottom:18px;border-color:#743847;color:#ffabb8">
        <strong>შეამოწმე შეყვანილი მონაცემები:</strong>
        <ul style="margin:8px 0 0;padding-left:20px">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="od-hero">
    <div>
        <div class="od-eyebrow">MOTOHUB / WORKSHOP ORDER</div>
        <h2 class="od-title">
            შეკვეთა #{{ $workOrder->order_number }}
        </h2>
        <div class="od-muted">
            შექმნის თარიღი:
            {{ $workOrder->created_at?->format('d.m.Y · H:i') ?? '—' }}
        </div>

        <div class="od-actions">
            <a class="od-btn"
               href="{{ route('my-service-center.work-orders.index') }}">
                ← შეკვეთების სია
            </a>

            <a class="od-btn primary"
               href="{{ route('my-service-center.work-orders.edit', $workOrder) }}">
                ✎ შეკვეთის რედაქტირება
            </a>
        </div>
    </div>

    <span class="od-status" style="--od-color:{{ $statusColor }}">
        <span class="od-dot"></span>
        {{ $labels[$workOrder->status] ?? $workOrder->status }}
    </span>
</div>

<section class="od-summary">
    <div class="od-bike">
        <div class="od-bike-icon">⚙</div>
        <div>
            <strong>{{ $booking?->motorcycle ?: 'მოტოციკლი' }}</strong>
            <small>შეკვეთის ID: {{ $workOrder->id }}</small>
        </div>
    </div>

    <div class="od-metric">
        <span>მიღების გარბენი</span>
        <strong>
            {{ $workOrder->intake_odometer !== null
                ? number_format($workOrder->intake_odometer) . ' კმ'
                : '—' }}
        </strong>
    </div>

    <div class="od-metric">
        <span>მიმდინარე სტატუსი</span>
        <strong style="color:{{ $statusColor }}">
            {{ $labels[$workOrder->status] ?? $workOrder->status }}
        </strong>
    </div>

    <div class="od-metric">
        <span>სრული ღირებულება</span>
        <strong class="od-blue">
            {{ number_format((float)$workOrder->total_cost, 2) }} ₾
        </strong>
    </div>
</section>

<div class="od-grid">
    <section class="od-card">
        <div class="od-card-head">
            <div class="od-card-icon">▧</div>
            <div>
                <h3>მოტოციკლის ფოტოები</h3>
                <p>{{ count($photos) }} ფოტო</p>
            </div>
        </div>

        @if(count($photos))
            <div class="od-photo-grid">
                @foreach($photos as $photo)
                    <a class="od-photo"
                       href="{{ asset('storage/' . $photo) }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="ფოტოს სრულად ნახვა">
                        <img src="{{ asset('storage/' . $photo) }}"
                             alt="სამუშაო შეკვეთის ფოტო"
                             loading="lazy">
                    </a>
                @endforeach
            </div>
        @else
            <div class="od-empty">ფოტოები ჯერ არ არის დამატებული.</div>
        @endif
    </section>

    <section class="od-card">
        <div class="od-card-head">
            <div class="od-card-icon">₾</div>
            <div>
                <h3>სამუშაოს ღირებულება</h3>
                <p>ხელობა და ნაწილები</p>
            </div>
        </div>

        <div class="od-cost">
            <span>ხელობის ღირებულება</span>
            <strong>{{ number_format((float)$workOrder->labor_cost, 2) }} ₾</strong>
        </div>

        <div class="od-cost">
            <span>ნაწილების ღირებულება</span>
            <strong>{{ number_format((float)$workOrder->parts_cost, 2) }} ₾</strong>
        </div>

        <div class="od-cost od-total">
            <span>სრული ღირებულება</span>
            <strong>{{ number_format((float)$workOrder->total_cost, 2) }} ₾</strong>
        </div>
    </section>
</div>

<section class="od-card">
    <div class="od-card-head">
        <div class="od-card-icon">↻</div>
        <div>
            <h3>შეკვეთის სტატუსი</h3>
            <p>განაახლე სამუშაოს მიმდინარე ეტაპი</p>
        </div>
    </div>

    @if(!in_array($workOrder->status, ['handed_over', 'cancelled']))
        <form method="POST"
              action="{{ route('my-service-center.work-orders.status', $workOrder) }}"
              class="od-status-form">
            @csrf
            @method('PATCH')

            <select name="status" required class="od-select"
                    aria-label="შეკვეთის ახალი სტატუსი">
                @foreach($labels as $value => $label)
                    <option value="{{ $value }}"
                        @selected($workOrder->status === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="od-btn primary">
                სტატუსის შენახვა →
            </button>
        </form>
    @else
        <div class="od-empty">
            ეს შეკვეთა საბოლოო სტატუსშია და სტატუსის შეცვლა ამ ფორმით გამორთულია.
        </div>
    @endif
</section>

<section class="od-card">
    <div class="od-card-head">
        <div class="od-card-icon">♙</div>
        <div>
            <h3>მომხმარებელი და მოტოციკლი</h3>
            <p>ჯავშნის ინფორმაცია</p>
        </div>
    </div>

    <div class="od-fields">
        <div class="od-field">
            <label>მომხმარებელი</label>
            <div class="od-value">
                {{ $booking?->customer_name ?: 'არ არის მითითებული' }}
            </div>
        </div>

        <div class="od-field">
            <label>ტელეფონი</label>
            <div class="od-value">
                {{ $booking?->customer_phone ?: 'არ არის მითითებული' }}
            </div>
        </div>

        <div class="od-field">
            <label>მოტოციკლი</label>
            <div class="od-value">
                {{ $booking?->motorcycle ?: 'არ არის მითითებული' }}
            </div>
        </div>

        <div class="od-field">
            <label>სერვისი</label>
            <div class="od-value">
                {{ $booking?->service_name ?: 'არ არის მითითებული' }}
            </div>
        </div>

        <div class="od-field">
            <label>დაგეგმილი ვიზიტი</label>
            <div class="od-value">
                {{ $booking?->scheduled_at?->format('d.m.Y H:i') ?? '—' }}
            </div>
        </div>

        <div class="od-field">
            <label>ჯავშნის ნომერი</label>
            <div class="od-value">{{ $booking?->id ?? 'ჯავშნის გარეშე' }}</div>
        </div>
    </div>
</section>

<div class="od-grid">
    <section class="od-card">
        <div class="od-card-head">
            <div class="od-card-icon">✓</div>
            <div>
                <h3>მდგომარეობა მიღებისას</h3>
                <p>მოტოციკლის საწყისი მდგომარეობა</p>
            </div>
        </div>

        <div class="od-text">{{ $workOrder->intake_condition ?: 'ინფორმაცია არ არის დამატებული.' }}</div>
    </section>

    <section class="od-card">
        <div class="od-card-head">
            <div class="od-card-icon">⚒</div>
            <div>
                <h3>დიაგნოსტიკა</h3>
                <p>აღმოჩენილი გაუმართაობები</p>
            </div>
        </div>

        <div class="od-text">{{ $workOrder->diagnosis ?: 'დიაგნოსტიკა ჯერ არ არის დამატებული.' }}</div>
    </section>

    <section class="od-card">
        <div class="od-card-head">
            <div class="od-card-icon">☰</div>
            <div>
                <h3>შესასრულებელი სამუშაო</h3>
                <p>შეკეთების აღწერა</p>
            </div>
        </div>

        <div class="od-text">{{ $workOrder->work_description ?: 'სამუშაოს აღწერა ჯერ არ არის დამატებული.' }}</div>
    </section>

    <section class="od-card">
        <div class="od-card-head">
            <div class="od-card-icon">✎</div>
            <div>
                <h3>შენიშვნები</h3>
                <p>დამატებითი ინფორმაცია</p>
            </div>
        </div>

        <div class="od-text">{{ $workOrder->notes ?: 'შენიშვნა არ არის.' }}</div>
    </section>
</div>

<section class="od-card">
    <div class="od-card-head">
        <div class="od-card-icon">◷</div>
        <div>
            <h3>შეკვეთის ქრონოლოგია</h3>
            <p>დასრულებისა და ჩაბარების დრო</p>
        </div>
    </div>

    <div class="od-timeline">
        <div class="od-time-item">
            <span>შეკეთების დასრულება</span>
            <strong>
                {{ $workOrder->completed_at?->format('d.m.Y H:i') ?? 'ჯერ არ დასრულებულა' }}
            </strong>
        </div>

        <div class="od-time-item">
            <span>მომხმარებლისთვის ჩაბარება</span>
            <strong>
                {{ $workOrder->handed_over_at?->format('d.m.Y H:i') ?? 'ჯერ არ ჩაბარებულა' }}
            </strong>
        </div>
    </div>
</section>
@endsection
