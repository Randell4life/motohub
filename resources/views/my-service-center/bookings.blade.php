@extends('my-service-center.layout')

@section('title', 'ჯავშნები | MotoHub')
@section('page-title', 'ჯავშნების მართვა')
@section('page-subtitle', 'ჯავშნების სია და მათი მიმდინარე სტატუსები')

@section('content')
    <style>
        .bk-hero {
            padding: 24px;
            margin-bottom: 22px;
            border: 1px solid #25486d;
            border-radius: 16px;
            background:
                radial-gradient(ellipse at 100% 0%, rgba(8,124,255,.2), transparent 45%),
                linear-gradient(120deg, #111d2c, #0a101a);
        }

        .bk-eyebrow {
            color: #54b2ff;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .bk-hero h2 {
            margin: 10px 0 6px;
            font-size: 25px;
        }

        .bk-hero p {
            margin: 0;
            line-height: 1.7;
        }

        .bk-count {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
            padding: 8px 12px;
            border: 1px solid #294c73;
            border-radius: 8px;
            background: rgba(8,124,255,.1);
            color: #b9dcff;
            font-size: 12px;
        }

        .bk-table-wrap {
            overflow-x: auto;
            border: 1px solid #26384e;
            border-radius: 13px;
        }

        .bk-table {
            width: 100%;
            min-width: 850px;
            border-collapse: collapse;
        }

        .bk-table th {
            padding: 15px 16px;
            background: #101a28;
            color: #8fa6c2;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
            text-align: left;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .bk-table td {
            padding: 16px;
            border-top: 1px solid #202f42;
            color: #dce7f6;
            font-size: 13px;
            vertical-align: middle;
        }

        .bk-table tbody tr {
            transition: background .18s ease;
        }

        .bk-table tbody tr:hover {
            background: rgba(22,119,255,.055);
        }

        .bk-customer {
            font-weight: 750;
            color: #f4f8ff;
        }

        .bk-sub {
            margin-top: 5px;
            color: #8fa3bb;
            font-size: 12px;
        }

        .bk-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 10px;
            border: 1px solid #34445a;
            border-radius: 7px;
            background: #111a27;
            color: #c6d4e7;
            font-size: 11px;
            font-weight: 750;
            white-space: nowrap;
        }

        .bk-status::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #91a2b9;
        }

        .bk-status.pending {
            color: #ffcf78;
            border-color: #66502d;
            background: #211a10;
        }

        .bk-status.pending::before {
            background: #ffbd4a;
        }

        .bk-status.confirmed {
            color: #65b7ff;
            border-color: #244d77;
            background: #0d1d30;
        }

        .bk-status.confirmed::before {
            background: #39a3ff;
        }

        .bk-status.completed {
            color: #58e0ae;
            border-color: #235b49;
            background: #0c201b;
        }

        .bk-status.completed::before {
            background: #43d9a5;
        }

        .bk-status.declined,
        .bk-status.cancelled {
            color: #ff8799;
            border-color: #653443;
            background: #24121a;
        }

        .bk-status.declined::before,
        .bk-status.cancelled::before {
            background: #ff7187;
        }

        .bk-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            min-width: 155px;
        }

        .bk-btn {
            padding: 8px 10px;
            border: 1px solid #31547b;
            border-radius: 7px;
            background: #10233a;
            color: #8ec9ff;
            font-size: 11px;
            font-weight: 750;
            cursor: pointer;
        }

        .bk-btn:hover {
            border-color: #54b2ff;
            background: #163457;
        }

        .bk-btn.success {
            border-color: #28634f;
            background: #10271f;
            color: #69e5b5;
        }

        .bk-btn.danger {
            border-color: #643543;
            background: #28141b;
            color: #ff91a2;
        }

        .bk-empty {
            padding: 48px 20px;
            text-align: center;
        }

        .bk-empty-icon {
            display: inline-grid;
            place-items: center;
            width: 54px;
            height: 54px;
            margin-bottom: 14px;
            border: 1px solid #294c73;
            border-radius: 15px;
            background: rgba(8,124,255,.1);
            color: #69b8ff;
            font-size: 25px;
        }

        .bk-pagination {
            padding: 18px;
            border-top: 1px solid #243449;
        }

        .bk-pagination nav {
            display: flex;
            justify-content: center;
        }

        .bk-pagination nav p {
            color: #91a5be;
        }

        @media (max-width: 600px) {
            .bk-hero {
                padding: 19px;
            }

            .bk-hero h2 {
                font-size: 21px;
            }
        }
    </style>

    <section class="bk-hero">
        <div class="bk-eyebrow">MOTOHUB / BOOKINGS</div>
        <h2>ჯავშნების მართვა</h2>
        <p class="mh-muted">
            აკონტროლე მომხმარებლების ჯავშნები და დროულად განაახლე სტატუსები.
        </p>

        <div class="bk-count">
            <span>▤</span>
            ჯავშნები სულ: {{ $bookings->total() }}
        </div>
    </section>

    <section class="mh-card" style="padding:0;overflow:hidden">
        <div style="padding:20px 20px 16px">
            <h3 style="margin:0 0 6px">ჯავშნების სია</h3>
            <p class="mh-muted" style="margin:0;font-size:13px">
                თითოეული ჯავშნის დეტალები და ხელმისაწვდომი მოქმედებები.
            </p>
        </div>

        @if($bookings->count())
            <div class="bk-table-wrap" style="border-radius:0;border-left:0;border-right:0">
                <table class="bk-table">
                    <thead>
                        <tr>
                            <th>მომხმარებელი</th>
                            <th>მომსახურება</th>
                            <th>მოტოციკლი</th>
                            <th>თარიღი</th>
                            <th>ფასი</th>
                            <th>სტატუსი</th>
                            <th>მოქმედებები</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($bookings as $booking)
                            @php
                                $status = strtolower((string) $booking->status);
                            @endphp

                            <tr>
                                <td>
                                    <div class="bk-customer">
                                        {{ $booking->customer_name }}
                                    </div>
                                    <div class="bk-sub">
                                        {{ $booking->customer_phone ?: 'ტელეფონი მითითებული არ არის' }}
                                    </div>
                                </td>

                                <td>
                                    {{ $booking->service_name ?: 'მომსახურება' }}
                                </td>

                                <td>
                                    {{ $booking->motorcycle ?: '—' }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $booking->scheduled_at?->format('d.m.Y') ?? '—' }}
                                    </strong>
                                    <div class="bk-sub">
                                        {{ $booking->scheduled_at?->format('H:i') ?? '' }}
                                    </div>
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format((float) $booking->price_at_booking, 2) }}
                                    </strong>
                                    <span class="bk-sub">₾</span>
                                </td>

                                <td>
                                    <span class="bk-status {{ $status }}">
                                        {{ $booking->status_label ?? $booking->status }}
                                    </span>
                                </td>

                                <td>
                                    <div class="bk-actions">
                                        @if($booking->status === 'pending')
                                            <form method="POST"
                                                  action="{{ route('my-service-center.bookings.status', $booking) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button class="bk-btn success" type="submit">
                                                    ✓ დადასტურება
                                                </button>
                                            </form>

                                            <form method="POST"
                                                  action="{{ route('my-service-center.bookings.status', $booking) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="declined">
                                                <button class="bk-btn danger" type="submit">
                                                    ✕ უარყოფა
                                                </button>
                                            </form>
                                        @elseif($booking->status === 'confirmed')
                                            <form method="POST"
                                                  action="{{ route('my-service-center.bookings.status', $booking) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button class="bk-btn success" type="submit">
                                                    ✓ დასრულება
                                                </button>
                                            </form>

                                            <form method="POST"
                                                  action="{{ route('my-service-center.bookings.status', $booking) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button class="bk-btn danger" type="submit">
                                                    გაუქმება
                                                </button>
                                            </form>
                                        @else
                                            <span class="bk-sub">მოქმედება მიუწვდომელია</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bk-pagination">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="bk-empty">
                <div class="bk-empty-icon">▤</div>
                <h3 style="margin:0 0 8px">ჯავშნები ჯერ არ არის</h3>
                <p class="mh-muted" style="margin:0">
                    ახალი ჯავშნები ამ გვერდზე გამოჩნდება.
                </p>
            </div>
        @endif
    </section>
@endsection
