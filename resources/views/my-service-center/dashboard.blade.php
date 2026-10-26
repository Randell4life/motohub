@extends('my-service-center.layout')

@section('title', 'მთავარი პანელი | MotoHub')
@section('page-title', 'მთავარი პანელი')
@section('page-subtitle', 'სერვისცენტრის ოპერაციების მართვა')

@section('public-link')
    <a class="mh-public-link"
       href="{{ route('service-centers.show', $center->slug) }}"
       target="_blank" rel="noopener">
        საჯარო გვერდი ↗
    </a>
@endsection

@section('content')
    <style>
        .cp-hero {
            position: relative;
            overflow: hidden;
            padding: 28px;
            margin-bottom: 24px;
            border: 1px solid #25486d;
            border-radius: 18px;
            background:
                radial-gradient(ellipse at 90% 10%, rgba(0,124,255,.24), transparent 42%),
                linear-gradient(120deg, #111d2c, #0a101a);
        }

        .cp-hero::after {
            content: "MH";
            position: absolute;
            right: 24px;
            bottom: -35px;
            font-size: 150px;
            font-weight: 900;
            font-style: italic;
            line-height: 1;
            color: rgba(75, 158, 255, .055);
            pointer-events: none;
        }

        .cp-eyebrow {
            color: #54b2ff;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .cp-hero h2 {
            margin: 12px 0 8px;
            font-size: clamp(24px, 3vw, 34px);
            letter-spacing: -.8px;
        }

        .cp-hero p {
            max-width: 560px;
            line-height: 1.8;
        }

        .cp-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .cp-stat {
            position: relative;
            overflow: hidden;
            padding: 22px;
            border: 1px solid #263a52;
            border-radius: 15px;
            background: linear-gradient(145deg, #121e2d, #0b111a);
        }

        .cp-stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .cp-stat-icon {
            display: grid;
            place-items: center;
            width: 40px;
            height: 40px;
            border: 1px solid #294c73;
            border-radius: 11px;
            background: rgba(8,124,255,.12);
            color: #69b8ff;
            font-size: 20px;
        }

        .cp-stat-number {
            display: block;
            margin: 20px 0 5px;
            font-size: 36px;
            font-weight: 850;
            line-height: 1;
            letter-spacing: -1px;
        }

        .cp-stat-label {
            color: #9aacc2;
            font-size: 13px;
        }

        .cp-section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
        }

        .cp-section-title h3 {
            margin: 0;
            font-size: 17px;
        }

        .cp-panel-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(280px, .85fr);
            gap: 20px;
            margin-bottom: 22px;
        }

        .cp-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 10px;
            border: 1px solid #29415b;
            border-radius: 8px;
            background: #0b1420;
            color: #d6e5f8;
            font-size: 12px;
        }

        .cp-status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #43d9a5;
            box-shadow: 0 0 10px rgba(67,217,165,.55);
        }

        .cp-status-dot.off {
            background: #ff7187;
            box-shadow: 0 0 10px rgba(255,113,135,.4);
        }

        .cp-center-name {
            margin: 12px 0 6px;
            font-size: 23px;
            font-weight: 800;
        }

        .cp-center-details {
            display: grid;
            gap: 15px;
            margin-top: 22px;
        }

        .cp-detail-label {
            display: block;
            margin-bottom: 5px;
            color: #8fa3bd;
            font-size: 12px;
        }

        .cp-detail-value {
            overflow-wrap: anywhere;
            font-size: 14px;
            font-weight: 600;
        }

        .cp-actions {
            display: grid;
            gap: 10px;
        }

        .cp-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 15px;
            border: 1px solid #263a52;
            border-radius: 10px;
            background: #0b121d;
            color: #edf5ff;
            text-decoration: none;
            transition: .2s ease;
        }

        .cp-action:hover {
            border-color: #338fff;
            background: #101f32;
            transform: translateX(2px);
        }

        .cp-action small {
            display: block;
            margin-top: 4px;
            color: #91a5be;
        }

        .cp-action-arrow {
            color: #54b2ff;
            font-size: 18px;
        }

        .cp-next {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .cp-next-icon {
            display: grid;
            place-items: center;
            flex: 0 0 44px;
            height: 44px;
            border: 1px solid #294c73;
            border-radius: 12px;
            background: rgba(8,124,255,.12);
            color: #69b8ff;
            font-size: 20px;
        }

        @media (max-width: 850px) {
            .cp-panel-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 560px) {
            .cp-stats {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .cp-stat {
                padding: 18px;
            }

            .cp-stat-number {
                margin-top: 14px;
                font-size: 30px;
            }

            .cp-hero {
                padding: 21px;
            }
        }
    </style>

    <section class="cp-hero">
        <div class="cp-eyebrow">MOTOHUB / SERVICE OPERATIONS</div>

        <h2>მოგესალმები, {{ $center->name }}</h2>

        <p class="mh-muted" style="margin:0">
            მართე ჯავშნები, მომსახურებები და სამუშაო პროცესი
            ერთი სივრციდან.
        </p>
    </section>

    <div class="cp-stats">
        <section class="cp-stat">
            <div class="cp-stat-top">
                <span class="cp-stat-label">მოლოდინში</span>
                <span class="cp-stat-icon">◷</span>
            </div>
            <strong class="cp-stat-number">
                {{ $bookingStats['pending'] ?? 0 }}
            </strong>
            <span class="cp-stat-label">დასადასტურებელი ჯავშნები</span>
        </section>

        <section class="cp-stat">
            <div class="cp-stat-top">
                <span class="cp-stat-label">დადასტურებული</span>
                <span class="cp-stat-icon">✓</span>
            </div>
            <strong class="cp-stat-number">
                {{ $bookingStats['confirmed'] ?? 0 }}
            </strong>
            <span class="cp-stat-label">დაგეგმილი ჯავშნები</span>
        </section>

        <section class="cp-stat">
            <div class="cp-stat-top">
                <span class="cp-stat-label">დასრულებული</span>
                <span class="cp-stat-icon">↗</span>
            </div>
            <strong class="cp-stat-number">
                {{ $bookingStats['completed'] ?? 0 }}
            </strong>
            <span class="cp-stat-label">დასრულებული ჯავშნები</span>
        </section>
    </div>

    <div class="cp-panel-grid">
        <section class="mh-card" style="padding:22px">
            <div class="cp-section-title">
                <h3>სერვისცენტრის მდგომარეობა</h3>
                <span style="color:#54b2ff;font-size:11px;font-weight:800">
                    PROFILE
                </span>
            </div>

            <div class="cp-status">
                <span class="cp-status-dot {{ $center->is_active ? '' : 'off' }}"></span>
                {{ $center->is_active ? 'აქტიურია' : 'არააქტიურია' }}
            </div>

            <h3 class="cp-center-name">{{ $center->name }}</h3>

            <p class="mh-muted" style="margin:0">
                {{ $center->city ?: 'ქალაქი მითითებული არ არის' }}
                @if($center->address)
                    · {{ $center->address }}
                @endif
            </p>

            <div class="cp-center-details">
                <div>
                    <span class="cp-detail-label">დამტკიცების სტატუსი</span>
                    <span class="cp-detail-value">
                        {{ $center->approval_status === 'approved'
                            ? 'დამტკიცებულია'
                            : ($center->approval_status ?: 'უცნობი') }}
                    </span>
                </div>

                <div>
                    <span class="cp-detail-label">ტელეფონი</span>
                    <span class="cp-detail-value">
                        {{ $center->phone ?: 'არ არის მითითებული' }}
                    </span>
                </div>

                <div>
                    <span class="cp-detail-label">საჯარო პროფილი</span>
                    <a href="{{ route('service-centers.show', $center->slug) }}"
                       target="_blank" rel="noopener"
                       style="color:#54b2ff;text-decoration:none;font-weight:700">
                        პროფილის გახსნა ↗
                    </a>
                </div>
            </div>
        </section>

        <section class="mh-card" style="padding:22px">
            <div class="cp-section-title">
                <h3>სწრაფი მოქმედებები</h3>
                <span style="color:#8297b1;font-size:12px">SHORTCUTS</span>
            </div>

            <div class="cp-actions">
                <a class="cp-action" href="{{ route('my-service-center.bookings') }}">
                    <span>
                        <strong>ჯავშნების მართვა</strong>
                        <small>ნახე და განაახლე ჯავშნები</small>
                    </span>
                    <span class="cp-action-arrow">↗</span>
                </a>

                <a class="cp-action" href="{{ route('my-service-center.work-orders.index') }}">
                    <span>
                        <strong>სამუშაო შეკვეთები</strong>
                        <small>მართე სერვისის სამუშაოები</small>
                    </span>
                    <span class="cp-action-arrow">↗</span>
                </a>

                <a class="cp-action" href="{{ route('my-service-center.services') }}">
                    <span>
                        <strong>მომსახურებები</strong>
                        <small>ფასები და მომსახურების სია</small>
                    </span>
                    <span class="cp-action-arrow">↗</span>
                </a>

                <a class="cp-action" href="{{ route('my-service-center.hours') }}">
                    <span>
                        <strong>სამუშაო საათები</strong>
                        <small>კვირის სამუშაო განრიგი</small>
                    </span>
                    <span class="cp-action-arrow">↗</span>
                </a>

                <a class="cp-action" href="{{ route('my-service-center.profile') }}">
                    <span>
                        <strong>სერვისცენტრის პროფილი</strong>
                        <small>საკონტაქტო ინფორმაცია და სტატუსი</small>
                    </span>
                    <span class="cp-action-arrow">↗</span>
                </a>
            </div>
        </section>
    </div>

    <section class="mh-card" style="padding:22px">
        <div class="cp-next">
            <div class="cp-next-icon">⚡</div>
            <div>
                <h3 style="margin:0 0 6px">შენი შემდეგი ნაბიჯი</h3>
                <p class="mh-muted" style="margin:0;line-height:1.7">
                    რეგულარულად შეამოწმე ახალი ჯავშნები და განაახლე
                    მათი სტატუსი, რათა მომხმარებლებს ზუსტი ინფორმაცია ჰქონდეთ.
                </p>
            </div>
        </div>
    </section>
@endsection
