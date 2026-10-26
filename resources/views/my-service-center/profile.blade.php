@extends('my-service-center.layout')

@section('title', 'სერვისცენტრის პროფილი | MotoHub')
@section('page-title', 'სერვისცენტრის პროფილი')
@section('page-subtitle', 'სერვისცენტრის ინფორმაცია და საჯარო პროფილი')

@section('public-link')
    <a class="mh-public-link"
       href="{{ route('service-centers.show', $center->slug) }}"
       target="_blank" rel="noopener">
        საჯარო გვერდი ↗
    </a>
@endsection

@section('content')
    <style>
        .pf-hero {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 22px;
            flex-wrap: wrap;
            padding: 28px;
            margin-bottom: 22px;
            border: 1px solid #25486d;
            border-radius: 17px;
            background:
                radial-gradient(ellipse at 90% 0%, rgba(8,124,255,.24), transparent 43%),
                linear-gradient(120deg, #111d2c, #0a101a);
        }

        .pf-avatar {
            display: grid;
            place-items: center;
            flex: 0 0 78px;
            height: 78px;
            border: 1px solid #59b3ff;
            border-radius: 17px;
            background: linear-gradient(135deg, #087cff, #12315a);
            color: #fff;
            font-size: 34px;
            font-weight: 900;
            box-shadow: 0 0 30px rgba(8,124,255,.2);
        }

        .pf-eyebrow {
            color: #54b2ff;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .pf-name {
            margin: 7px 0;
            font-size: clamp(23px, 3vw, 31px);
            letter-spacing: -.6px;
            overflow-wrap: anywhere;
        }

        .pf-location {
            margin: 0;
            color: #91a5be;
            font-size: 13px;
        }

        .pf-hero-info {
            flex: 1;
            min-width: 180px;
        }

        .pf-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 16px;
        }

        .pf-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 10px;
            border: 1px solid #2b4059;
            border-radius: 8px;
            background: #0b1420;
            color: #c7d8ec;
            font-size: 11px;
            font-weight: 750;
        }

        .pf-badge.approved {
            border-color: #235b49;
            background: #0c201b;
            color: #58e0ae;
        }

        .pf-badge.pending {
            border-color: #66502d;
            background: #211a10;
            color: #ffcf78;
        }

        .pf-badge.inactive {
            border-color: #643543;
            background: #24121a;
            color: #ff91a2;
        }

        .pf-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 20px;
            margin-bottom: 22px;
        }

        .pf-card {
            padding: 23px;
            border: 1px solid #263a52;
            border-radius: 14px;
            background: linear-gradient(145deg, #111c2a, #0b111a);
        }

        .pf-card-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 17px;
            margin-bottom: 19px;
            border-bottom: 1px solid #243449;
        }

        .pf-card-icon {
            display: grid;
            place-items: center;
            flex: 0 0 40px;
            height: 40px;
            border: 1px solid #294c73;
            border-radius: 10px;
            background: rgba(8,124,255,.12);
            color: #69b8ff;
            font-size: 19px;
        }

        .pf-card-head h3 {
            margin: 0;
            font-size: 16px;
        }

        .pf-card-head p {
            margin: 4px 0 0;
            color: #8195ad;
            font-size: 11px;
        }

        .pf-details {
            display: grid;
            gap: 20px;
        }

        .pf-detail-label {
            display: block;
            margin-bottom: 6px;
            color: #8195ad;
            font-size: 11px;
            letter-spacing: .4px;
        }

        .pf-detail-value {
            display: block;
            color: #eaf2ff;
            font-size: 14px;
            font-weight: 650;
            overflow-wrap: anywhere;
        }

        .pf-url {
            padding: 12px;
            border: 1px solid #263a52;
            border-radius: 9px;
            background: #080e17;
            color: #8ec9ff;
            font-size: 12px;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .pf-public-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            margin-top: 16px;
            padding: 12px 16px;
            border: 1px solid #338fff;
            border-radius: 9px;
            background: linear-gradient(135deg, #087cff, #0757c7);
            color: #fff;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
            box-shadow: 0 5px 18px rgba(8,124,255,.15);
            transition: .2s ease;
        }

        .pf-public-button:hover {
            box-shadow: 0 7px 24px rgba(8,124,255,.3);
            transform: translateY(-1px);
        }

        .pf-notice {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 20px;
            border: 1px solid #29415b;
            border-radius: 12px;
            background: rgba(8,124,255,.055);
        }

        .pf-notice-icon {
            color: #69b8ff;
            font-size: 21px;
        }

        .pf-notice h3 {
            margin: 0 0 7px;
            font-size: 15px;
        }

        .pf-notice p {
            margin: 0;
            color: #91a5be;
            font-size: 13px;
            line-height: 1.8;
        }

        @media (max-width: 700px) {
            .pf-grid {
                grid-template-columns: 1fr;
            }

            .pf-hero {
                padding: 21px;
            }

            .pf-card {
                padding: 19px;
            }
        }
    </style>

    @php
        $approval = $center->approval_status ?? 'unknown';

        $approvalLabel = match ($approval) {
            'approved' => 'დამტკიცებულია',
            'pending' => 'განხილვის მოლოდინში',
            'rejected' => 'უარყოფილია',
            default => $approval,
        };
    @endphp

    <section class="pf-hero">
        <div class="pf-avatar">
            {{ mb_strtoupper(mb_substr($center->name, 0, 1)) }}
        </div>

        <div class="pf-hero-info">
            <div class="pf-eyebrow">MOTOHUB / CENTER IDENTITY</div>
            <h2 class="pf-name">{{ $center->name }}</h2>
            <p class="pf-location">
                {{ $center->city ?: 'ქალაქი მითითებული არ არის' }}
                @if($center->address)
                    · {{ $center->address }}
                @endif
            </p>

            <div class="pf-badges">
                <span class="pf-badge {{ $approval === 'approved' ? 'approved' : ($approval === 'pending' ? 'pending' : '') }}">
                    ● {{ $approvalLabel }}
                </span>

                <span class="pf-badge {{ $center->is_active ? 'approved' : 'inactive' }}">
                    {{ $center->is_active ? '● აქტიურია' : '○ არააქტიურია' }}
                </span>
            </div>
        </div>
    </section>

    <div class="pf-grid">
        <section class="pf-card">
            <div class="pf-card-head">
                <div class="pf-card-icon">▣</div>
                <div>
                    <h3>საკონტაქტო ინფორმაცია</h3>
                    <p>ძირითადი მონაცემები</p>
                </div>
            </div>

            <div class="pf-details">
                <div>
                    <span class="pf-detail-label">სერვისცენტრის სახელი</span>
                    <span class="pf-detail-value">{{ $center->name }}</span>
                </div>

                <div>
                    <span class="pf-detail-label">ქალაქი</span>
                    <span class="pf-detail-value">
                        {{ $center->city ?: 'არ არის მითითებული' }}
                    </span>
                </div>

                <div>
                    <span class="pf-detail-label">მისამართი</span>
                    <span class="pf-detail-value">
                        {{ $center->address ?: 'არ არის მითითებული' }}
                    </span>
                </div>

                <div>
                    <span class="pf-detail-label">ტელეფონი</span>
                    <span class="pf-detail-value">
                        {{ $center->phone ?: 'არ არის მითითებული' }}
                    </span>
                </div>
            </div>
        </section>

        <section class="pf-card">
            <div class="pf-card-head">
                <div class="pf-card-icon">↗</div>
                <div>
                    <h3>საჯარო პროფილი</h3>
                    <p>როგორ ხედავენ მომხმარებლები შენს ცენტრს</p>
                </div>
            </div>

            <div class="pf-details">
                <div>
                    <span class="pf-detail-label">საჯარო ბმული</span>
                    <div class="pf-url">
                        {{ route('service-centers.show', $center->slug) }}
                    </div>
                </div>

                <div>
                    <span class="pf-detail-label">პროფილის სტატუსი</span>
                    <span class="pf-detail-value">
                        {{ $center->is_active ? 'სერვისცენტრი აქტიურია' : 'სერვისცენტრი არააქტიურია' }}
                    </span>
                </div>
            </div>

            <a class="pf-public-button"
               href="{{ route('service-centers.show', $center->slug) }}"
               target="_blank" rel="noopener">
                საჯარო გვერდის ნახვა ↗
            </a>
        </section>
    </div>

    <section class="pf-notice">
        <div class="pf-notice-icon">ⓘ</div>
        <div>
            <h3>პროფილის რედაქტირება</h3>
            <p>
                ამ გვერდზე ამჟამად ნაჩვენებია შენახული ინფორმაცია.
                რედაქტირების ფორმას დავამატებთ ცალკე, არსებული Controller-ის
                ვალიდაციისა და შენახვის წესების შემოწმების შემდეგ.
            </p>
        </div>
    </section>
@endsection
