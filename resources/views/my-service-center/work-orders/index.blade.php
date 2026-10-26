@extends('my-service-center.layout')

@section('title', 'სამუშაო შეკვეთები | MotoHub')
@section('page-title', 'სამუშაო შეკვეთები')
@section('page-subtitle', 'მოტოციკლების მიღება, დიაგნოსტიკა და შეკეთების კონტროლი')

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

    $currentOrders = $workOrders->getCollection();

    $activeCount = $currentOrders
        ->whereNotIn('status', ['handed_over', 'cancelled'])
        ->count();

    $repairCount = $currentOrders
        ->where('status', 'in_repair')
        ->count();

    $pageTotal = (float) $currentOrders->sum('total_cost');
@endphp

<style>
    .wo-hero {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        padding: 25px;
        margin-bottom: 22px;
        border: 1px solid #25476a;
        border-radius: 16px;
        background:
            radial-gradient(ellipse at 90% 0%, rgba(22,119,255,.22), transparent 45%),
            linear-gradient(120deg, #111e2d, #0a1019);
    }

    .wo-eyebrow {
        color: #64b5ff;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .wo-hero h2 {
        margin: 9px 0;
        font-size: clamp(22px, 3vw, 29px);
    }

    .wo-hero p {
        max-width: 570px;
        margin: 0;
        color: #94a7bf;
        font-size: 13px;
        line-height: 1.8;
    }

    .wo-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
    }

    .wo-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 14px;
        border: 1px solid #314762;
        border-radius: 9px;
        color: #d9e9ff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 750;
        transition: .2s;
    }

    .wo-link:hover {
        border-color: #398fff;
        color: #fff;
    }

    .wo-link.primary {
        border-color: #2587ff;
        background: linear-gradient(135deg, #1677ff, #0758c7);
        color: #fff;
    }

    .wo-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }

    .wo-stat {
        position: relative;
        overflow: hidden;
        padding: 19px;
        border: 1px solid #263a52;
        border-radius: 13px;
        background: linear-gradient(145deg, #121e2d, #0b111a);
    }

    .wo-stat:after {
        position: absolute;
        top: 0;
        left: 0;
        width: 35px;
        height: 2px;
        background: #2488ff;
        content: "";
    }

    .wo-stat-label {
        display: block;
        margin-bottom: 13px;
        color: #94a7bf;
        font-size: 11px;
    }

    .wo-stat-value {
        display: block;
        color: #f1f6ff;
        font-size: clamp(21px, 2.5vw, 29px);
        font-weight: 850;
        overflow-wrap: anywhere;
    }

    .wo-stat-note {
        display: block;
        margin-top: 7px;
        color: #657c98;
        font-size: 10px;
    }

    .wo-panel {
        margin-bottom: 22px;
        padding: 23px;
        border: 1px solid #263a52;
        border-radius: 15px;
        background: linear-gradient(145deg, #111b29, #0b111a);
    }

    .wo-panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding-bottom: 18px;
        margin-bottom: 20px;
        border-bottom: 1px solid #26374c;
    }

    .wo-panel-head h3 {
        margin: 0;
        font-size: 17px;
    }

    .wo-panel-head p {
        margin: 6px 0 0;
        color: #91a5be;
        font-size: 12px;
        line-height: 1.7;
    }

    .wo-count {
        padding: 8px 11px;
        border: 1px solid #29486b;
        border-radius: 8px;
        background: rgba(22,119,255,.09);
        color: #8ec9ff;
        font-size: 11px;
        font-weight: 750;
    }

    .wo-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px;
    }

    .wo-field {
        min-width: 0;
    }

    .wo-field.full {
        grid-column: 1 / -1;
    }

    .wo-field label {
        display: block;
        margin-bottom: 8px;
        color: #c5d5e9;
        font-size: 12px;
        font-weight: 700;
    }

    .wo-field input,
    .wo-field select,
    .wo-field textarea {
        display: block;
        width: 100%;
        min-height: 44px;
        padding: 11px 12px;
        border: 1px solid #2b3d53;
        border-radius: 9px;
        outline: none;
        background: #080e17;
        color: #edf4ff;
        font: inherit;
        font-size: 13px;
        transition: border-color .2s, box-shadow .2s;
    }

    .wo-field textarea {
        min-height: 105px;
        resize: vertical;
        line-height: 1.7;
    }

    .wo-field input:focus,
    .wo-field select:focus,
    .wo-field textarea:focus {
        border-color: #338fff;
        box-shadow: 0 0 0 3px rgba(22,119,255,.12);
    }

    .wo-field input::placeholder,
    .wo-field textarea::placeholder {
        color: #5e7189;
    }

    .wo-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 44px;
        padding: 12px 19px;
        border: 1px solid #338fff;
        border-radius: 9px;
        background: linear-gradient(135deg, #1677ff, #0758c7);
        color: #fff;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        transition: .2s;
    }

    .wo-submit:hover {
        box-shadow: 0 5px 20px rgba(22,119,255,.22);
        transform: translateY(-1px);
    }

    .wo-empty {
        padding: 25px 18px;
        border: 1px dashed #344963;
        border-radius: 11px;
        color: #91a5be;
        text-align: center;
        font-size: 13px;
        line-height: 1.9;
    }

    .wo-table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #24364b;
        border-radius: 10px;
    }

    .wo-table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
        font-size: 12px;
    }

    .wo-table th {
        padding: 14px;
        background: #0a121e;
        color: #8ea5c0;
        text-align: left;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .5px;
        white-space: nowrap;
    }

    .wo-table td {
        padding: 15px 14px;
        border-top: 1px solid #1e2d40;
        color: #e3edfa;
        vertical-align: top;
    }

    .wo-table tbody tr:hover {
        background: rgba(22,119,255,.035);
    }

    .wo-order-link {
        color: #66b6ff;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
    }

    .wo-order-link:hover {
        color: #fff;
    }

    .wo-muted {
        display: block;
        margin-top: 6px;
        color: #8195ad;
        font-size: 11px;
        line-height: 1.6;
    }

    .wo-status {
        display: inline-flex;
        padding: 6px 9px;
        border: 1px solid #344963;
        border-radius: 7px;
        background: #131f2e;
        color: #c5d8ef;
        font-size: 10px;
        font-weight: 750;
    }

    .wo-status.received,
    .wo-status.diagnostics {
        border-color: #66502d;
        background: #211a10;
        color: #ffcf78;
    }

    .wo-status.in_repair {
        border-color: #28518a;
        background: #101e35;
        color: #8bc3ff;
    }

    .wo-status.completed,
    .wo-status.handed_over {
        border-color: #235b49;
        background: #0c201b;
        color: #58e0ae;
    }

    .wo-status.cancelled {
        border-color: #643543;
        background: #24121a;
        color: #ff91a2;
    }

    .wo-status-form {
        display: grid;
        gap: 8px;
        min-width: 145px;
        margin-top: 10px;
    }

    .wo-status-form select {
        width: 100%;
        padding: 9px;
        border: 1px solid #2b3d53;
        border-radius: 8px;
        background: #080e17;
        color: #eaf2ff;
        font-size: 11px;
    }

    .wo-status-button {
        padding: 9px;
        border: 1px solid #2e5f96;
        border-radius: 8px;
        background: #112846;
        color: #9ed0ff;
        font-size: 11px;
        font-weight: 750;
        cursor: pointer;
    }

    .wo-status-button:hover {
        border-color: #398fff;
        color: #fff;
    }

    .wo-pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .wo-pagination nav > div:first-child {
        display: none;
    }

    .wo-pagination a,
    .wo-pagination span {
        color: #c5d8ef;
    }

    .wo-errors {
        padding: 14px 16px;
        margin-bottom: 18px;
        border: 1px solid #743847;
        border-radius: 10px;
        background: #24121a;
        color: #ffabb8;
        font-size: 12px;
        line-height: 1.8;
    }

    .wo-success {
        padding: 13px 16px;
        margin-bottom: 18px;
        border: 1px solid #235b49;
        border-radius: 10px;
        background: #0c201b;
        color: #70e6ba;
        font-size: 12px;
    }

    @media (max-width: 900px) {
        .wo-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 560px) {
        .wo-hero,
        .wo-panel {
            padding: 17px;
        }

        .wo-stats {
            gap: 9px;
        }

        .wo-stat {
            padding: 14px;
        }

        .wo-form-grid {
            grid-template-columns: 1fr;
        }

        .wo-field.full {
            grid-column: auto;
        }

        .wo-actions,
        .wo-link {
            width: 100%;
        }
    }
</style>

@if(session('success'))
    <div class="wo-success">
        ✓ {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="wo-errors">
        <strong>შეამოწმე შეყვანილი მონაცემები:</strong>
        <ul style="margin:8px 0 0;padding-left:20px">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<section class="wo-hero">
    <div>
        <div class="wo-eyebrow">MOTOHUB / WORKSHOP MANAGEMENT</div>
        <h2>სამუშაო შეკვეთები<span style="color:#55a7ff">.</span></h2>
        <p>
            მართე მოტოციკლების მიღება, დიაგნოსტიკა, შეკეთება და
            სამუშაოების ფინანსური მხარე ერთ სივრცეში.
        </p>
    </div>

    <div class="wo-actions">
        <a href="{{ route('my-service-center.dashboard') }}"
           class="wo-link">← მთავარი პანელი</a>

        <a href="#new-order" class="wo-link primary">
            + ახალი შეკვეთა
        </a>
    </div>
</section>

<div class="wo-stats">
    <div class="wo-stat">
        <span class="wo-stat-label">ყველა შეკვეთა</span>
        <strong class="wo-stat-value">{{ $workOrders->total() }}</strong>
        <span class="wo-stat-note">ყველა გვერდის ჯამი</span>
    </div>

    <div class="wo-stat">
        <span class="wo-stat-label">აქტიური შეკვეთები</span>
        <strong class="wo-stat-value">{{ $activeCount }}</strong>
        <span class="wo-stat-note">მიმდინარე გვერდზე</span>
    </div>

    <div class="wo-stat">
        <span class="wo-stat-label">შეკეთების პროცესში</span>
        <strong class="wo-stat-value">{{ $repairCount }}</strong>
        <span class="wo-stat-note">მიმდინარე გვერდზე</span>
    </div>

    <div class="wo-stat">
        <span class="wo-stat-label">შეკვეთების ღირებულება</span>
        <strong class="wo-stat-value">{{ number_format($pageTotal, 2) }} ₾</strong>
        <span class="wo-stat-note">მიმდინარე გვერდის ჯამი</span>
    </div>
</div>

<section class="wo-panel" id="new-order">
    <div class="wo-panel-head">
        <div>
            <h3>＋ ახალი სამუშაო შეკვეთა</h3>
            <p>აირჩიე სახელოსნოს ხელმისაწვდომი ჯავშანი და შეავსე მიღების მონაცემები.</p>
        </div>

        <span class="wo-count">
            {{ $bookings->count() }} ხელმისაწვდომი ჯავშანი
        </span>
    </div>

    @if($bookings->isEmpty())
        <div class="wo-empty">
            <div style="font-size:25px;margin-bottom:8px">▤</div>
            <strong style="color:#dce9f9">ხელმისაწვდომი ჯავშნები არ არის</strong>
            <div>
                ახალი შეკვეთის შესაქმნელად საჭიროა შესაბამისი ჯავშანი,
                რომელზეც სამუშაო შეკვეთა ჯერ არ შექმნილა.
            </div>
        </div>
    @else
        <form method="POST"
              action="{{ route('my-service-center.work-orders.store') }}">
            @csrf

            <div class="wo-form-grid">
                <div class="wo-field full">
                    <label for="service_booking_id">ჯავშანი / მომხმარებელი *</label>
                    <select name="service_booking_id"
                            id="service_booking_id" required>
                        <option value="">აირჩიე ჯავშანი</option>

                        @foreach($bookings as $booking)
                            <option value="{{ $booking->id }}"
                                @selected(old('service_booking_id') == $booking->id)>
                                {{ $booking->customer_name }}
                                — {{ $booking->motorcycle ?: 'მოტოციკლი მითითებული არ არის' }}
                                — {{ $booking->scheduled_at?->format('d.m.Y H:i') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="wo-field">
                    <label for="intake_odometer">გარბენი (კმ)</label>
                    <input type="number"
                           name="intake_odometer"
                           id="intake_odometer"
                           min="0"
                           value="{{ old('intake_odometer') }}"
                           placeholder="მაგ. 24500">
                </div>

                <div class="wo-field">
                    <label for="intake_condition">მდგომარეობა მიღებისას</label>
                    <input name="intake_condition"
                           id="intake_condition"
                           value="{{ old('intake_condition') }}"
                           maxlength="5000"
                           placeholder="ნაკაწრები, დაზიანებები...">
                </div>

                <div class="wo-field full">
                    <label for="diagnosis">დიაგნოსტიკა</label>
                    <textarea name="diagnosis"
                              id="diagnosis"
                              maxlength="10000"
                              placeholder="აღმოჩენილი გაუმართაობა...">{{ old('diagnosis') }}</textarea>
                </div>

                <div class="wo-field full">
                    <label for="work_description">შესასრულებელი სამუშაო</label>
                    <textarea name="work_description"
                              id="work_description"
                              maxlength="10000"
                              placeholder="შეკეთების დეტალები...">{{ old('work_description') }}</textarea>
                </div>

                <div class="wo-field">
                    <label for="labor_cost">ხელობის ღირებულება (₾)</label>
                    <input type="number"
                           name="labor_cost"
                           id="labor_cost"
                           min="0"
                           step="0.01"
                           value="{{ old('labor_cost', '0') }}">
                </div>

                <div class="wo-field">
                    <label for="parts_cost">ნაწილების ღირებულება (₾)</label>
                    <input type="number"
                           name="parts_cost"
                           id="parts_cost"
                           min="0"
                           step="0.01"
                           value="{{ old('parts_cost', '0') }}">
                </div>

                <div class="wo-field full">
                    <label for="notes">შენიშვნა</label>
                    <textarea name="notes"
                              id="notes"
                              maxlength="10000"
                              placeholder="დამატებითი ინფორმაცია...">{{ old('notes') }}</textarea>
                </div>

                <div class="wo-field full">
                    <button class="wo-submit" type="submit">
                        შეკვეთის შექმნა →
                    </button>
                </div>
            </div>
        </form>
    @endif
</section>

<section class="wo-panel">
    <div class="wo-panel-head">
        <div>
            <h3>შეკვეთების ისტორია</h3>
            <p>შენს სახელოსნოში შექმნილი სამუშაო შეკვეთები და მათი მიმდინარე სტატუსები.</p>
        </div>

        <span class="wo-count">
            {{ $workOrders->total() }} ჩანაწერი
        </span>
    </div>

    @if($workOrders->isEmpty())
        <div class="wo-empty">
            <div style="font-size:25px;margin-bottom:8px">⚙</div>
            სამუშაო შეკვეთები ჯერ არ შექმნილა.
        </div>
    @else
        <div class="wo-table-wrap">
            <table class="wo-table">
                <thead>
                    <tr>
                        <th>შეკვეთა</th>
                        <th>მომხმარებელი / მოტოციკლი</th>
                        <th>სტატუსი</th>
                        <th>ხარჯები</th>
                        <th>შექმნის თარიღი</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($workOrders as $order)
                        <tr>
                            <td>
                                <a class="wo-order-link"
                                   href="{{ route('my-service-center.work-orders.show', $order) }}">
                                    {{ $order->order_number }} →
                                </a>

                                @if($order->serviceBooking)
                                    <span class="wo-muted">
                                        {{ $order->serviceBooking->service_name }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($order->serviceBooking)
                                    <strong>{{ $order->serviceBooking->customer_name }}</strong>
                                    <span class="wo-muted">
                                        {{ $order->serviceBooking->motorcycle ?: 'მოტოციკლი მითითებული არ არის' }}
                                    </span>
                                @else
                                    <span class="wo-muted">ჯავშნის გარეშე</span>
                                @endif
                            </td>

                            <td>
                                <span class="wo-status {{ $order->status }}">
                                    {{ $labels[$order->status] ?? $order->status }}
                                </span>

                                @if(!in_array($order->status, ['handed_over', 'cancelled']))
                                    <form method="POST"
                                          action="{{ route('my-service-center.work-orders.status', $order) }}"
                                          class="wo-status-form">
                                        @csrf
                                        @method('PATCH')

                                        <select name="status"
                                                aria-label="შეკვეთის ახალი სტატუსი">
                                            @foreach($labels as $value => $label)
                                                <option value="{{ $value }}"
                                                    @selected($order->status === $value)>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="submit" class="wo-status-button">
                                            სტატუსის შენახვა
                                        </button>
                                    </form>
                                @endif
                            </td>

                            <td>
                                <strong style="font-size:13px">
                                    {{ number_format((float) $order->total_cost, 2) }} ₾
                                </strong>

                                <span class="wo-muted">
                                    ხელობა: {{ number_format((float) $order->labor_cost, 2) }} ₾
                                </span>

                                <span class="wo-muted">
                                    ნაწილები: {{ number_format((float) $order->parts_cost, 2) }} ₾
                                </span>
                            </td>

                            <td>
                                {{ $order->created_at?->format('d.m.Y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="wo-pagination">
            {{ $workOrders->links() }}
        </div>
    @endif
</section>
@endsection
