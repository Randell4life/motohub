
<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#030916">
    <title>მომსახურების ბარათი | MotoHub</title>

    <style>
        :root {
            --bg: #030916;
            --surface: #071226;
            --surface-2: #0a1931;
            --border: #17385f;
            --blue: #087cff;
            --blue-light: #46a5ff;
            --text: #edf4ff;
            --muted: #8ca5c8;
            --green: #43d9a3;
            --red: #ff5474;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background:
                radial-gradient(ellipse at 70% 0%, rgba(8, 72, 153, .13), transparent 42%),
                var(--bg);
            color: var(--text);
            font-family: Arial, "Noto Sans Georgian", sans-serif;
            font-size: 14px;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button, input, select, textarea {
            font: inherit;
        }

        button, a, input, select, textarea {
            -webkit-tap-highlight-color: transparent;
        }

        button:focus-visible, a:focus-visible,
        input:focus-visible, select:focus-visible,
        textarea:focus-visible {
            outline: 2px solid var(--blue-light);
            outline-offset: 3px;
        }

        .topbar {
            min-height: 76px;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            background: linear-gradient(105deg, #050b19, #07172f 65%, #050d1d);
            border-bottom: 1px solid var(--border);
        }

        .brand-wrap {
            display: flex;
            align-items: center;
            gap: 22px;
        }

        .brand {
            font-size: 29px;
            font-weight: 900;
            letter-spacing: -1.4px;
            white-space: nowrap;
        }

        .brand span {
            color: var(--blue);
        }

        .brand-caption {
            padding-left: 22px;
            border-left: 1px solid #25466e;
        }

        .brand-caption strong {
            display: block;
            font-size: 13px;
            letter-spacing: 4px;
        }

        .brand-caption small {
            display: block;
            margin-top: 5px;
            color: var(--muted);
            font-size: 9px;
            letter-spacing: 2px;
        }

        .top-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #b9d5fb;
            font-size: 13px;
            white-space: nowrap;
        }

        .top-icon {
            color: var(--blue-light);
            font-size: 22px;
        }

        .layout {
            display: flex;
            min-height: calc(100vh - 76px);
        }

        .sidebar {
            width: 226px;
            flex: 0 0 226px;
            display: flex;
            flex-direction: column;
            padding: 22px 0;
            background: rgba(4, 11, 24, .96);
            border-right: 1px solid var(--border);
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 13px;
            min-height: 57px;
            margin: 3px 10px 3px 0;
            padding: 13px 20px;
            color: #9bb4d9;
            border-left: 3px solid transparent;
            border-radius: 0 10px 10px 0;
            transition: .2s ease;
        }

        .nav:hover {
            color: white;
            background: rgba(8, 124, 255, .09);
        }

        .nav.active {
            color: #39a1ff;
            background: linear-gradient(90deg, rgba(8,124,255,.22), rgba(8,124,255,.025));
            border-left-color: var(--blue);
        }

        .nav-icon {
            width: 24px;
            text-align: center;
            font-size: 20px;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 28px 22px 8px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.8;
        }

        .sidebar-footer strong {
            color: var(--blue-light);
            font-size: 15px;
        }

        .main {
            flex: 1;
            min-width: 0;
            padding: 28px;
        }

        .page-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 21px;
        }

        h1 {
            margin: 18px 0 0;
            font-size: clamp(23px, 2.4vw, 29px);
            line-height: 1.3;
            letter-spacing: -.6px;
        }

        h2 {
            margin: 0;
            font-size: 17px;
        }

        h3 {
            margin: 0 0 12px;
            font-size: 15px;
        }

        .muted {
            color: var(--muted);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 38px;
            padding: 9px 13px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #09172c;
            color: #d9eaff;
            cursor: pointer;
            transition: .2s ease;
            white-space: nowrap;
        }

        .btn:hover {
            border-color: var(--blue);
            box-shadow: 0 0 16px rgba(8,124,255,.13);
        }

        .btn-primary {
            background: linear-gradient(135deg, #1687ff, #075bdf);
            border-color: #1687ff;
            color: white;
            box-shadow: 0 5px 18px rgba(8,124,255,.15);
        }

        .btn-danger {
            color: #ffb3c2;
            background: rgba(104, 22, 47, .28);
            border-color: #71304a;
        }

        .btn-danger:hover {
            border-color: var(--red);
        }

        .panel {
            min-width: 0;
            padding: 22px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: linear-gradient(145deg, rgba(9,27,53,.98), rgba(4,12,27,.98));
            box-shadow: 0 12px 32px rgba(0,0,0,.12);
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #75baff;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .circle-icon {
            display: inline-flex;
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            align-items: center;
            justify-content: center;
            border: 1px solid #164c8e;
            border-radius: 50%;
            background: linear-gradient(145deg, #123c79, #081b39);
            color: #62b1ff;
            font-size: 20px;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: minmax(240px, .95fr) minmax(300px, 1.4fr);
            gap: 18px;
        }

        .customer-card {
            border-left: 4px solid var(--blue);
        }

        .card-heading {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .person-name {
            margin: 18px 0;
            font-size: 24px;
            font-weight: 800;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        .info {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 14px 0;
            color: #c5d9f4;
            overflow-wrap: anywhere;
        }

        .info-icon {
            width: 20px;
            flex: 0 0 20px;
            color: #71aaff;
            text-align: center;
        }

        .motorcycle-card {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(ellipse at 92% 42%, rgba(8,124,255,.19), transparent 43%),
                linear-gradient(145deg, #081a34, #040d1d);
        }

        .motorcycle-card::after {
            position: absolute;
            top: -100px;
            right: -100px;
            width: 250px;
            height: 250px;
            content: "";
            border: 24px solid rgba(8,124,255,.07);
            transform: rotate(35deg);
            pointer-events: none;
        }

        .bike-title {
            margin: 18px 0 8px;
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -.5px;
            overflow-wrap: anywhere;
        }

        .bike-meta {
            color: #a5c0e5;
        }

        .bike-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .bike-stat {
            min-width: 75px;
        }

        .bike-stat span {
            display: block;
            color: var(--muted);
            font-size: 11px;
        }

        .bike-stat strong {
            display: block;
            margin-top: 7px;
            font-size: 13px;
        }

        .summary {
            display: grid;
            grid-template-columns: 1.15fr 1fr 1fr 1fr;
            gap: 14px;
            margin-top: 18px;
        }

        .summary-card {
            min-width: 0;
            min-height: 112px;
            padding: 18px;
            transition: transform .2s ease, border-color .2s ease;
        }

        .summary-card:hover {
            transform: translateY(-2px);
            border-color: #1768bd;
        }

        .summary-label {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #a9c6ed;
            font-size: 12px;
            line-height: 1.5;
        }

        .summary-value {
            display: block;
            margin-top: 13px;
            font-size: clamp(19px, 2vw, 25px);
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .total-card {
            border-left: 4px solid var(--blue);
            background: linear-gradient(120deg, #082a58, #07152a);
        }

        .total-card .summary-value {
            color: #1687ff;
            font-size: clamp(23px, 2.2vw, 30px);
        }

        .history {
            margin-top: 20px;
        }

        .section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title .tool-icon {
            color: var(--blue-light);
            font-size: 25px;
        }

        .head-actions {
            display: flex;
            align-items: center;
            gap: 9px;
            flex-wrap: wrap;
        }

        .table-wrap {
            overflow-x: auto;
            border: 1px solid #142f51;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 800px;
        }

        thead th {
            padding: 16px 12px;
            background: #091a32;
            color: #83baff;
            font-size: 12px;
            font-weight: 700;
            text-align: left;
            white-space: nowrap;
        }

        tbody tr {
            background: rgba(5,17,35,.58);
            transition: background .18s ease;
        }

        tbody tr:hover {
            background: rgba(8,124,255,.08);
        }

        tbody td {
            padding: 13px 12px;
            color: #d8e6fa;
            border-top: 1px solid rgba(36,70,112,.5);
            font-size: 12px;
            vertical-align: middle;
        }

        .row-number {
            color: #a8c7f0;
            font-weight: 700;
        }

        .work-title {
            min-width: 145px;
            font-weight: 700;
            line-height: 1.5;
        }

        .work-description {
            margin-top: 5px;
            color: var(--muted);
            font-weight: 400;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 9px;
            border: 1px solid #174b8a;
            border-radius: 20px;
            background: #0a2342;
            color: #85c5ff;
            white-space: nowrap;
            font-size: 11px;
        }

        .tag::before {
            width: 8px;
            height: 8px;
            flex: 0 0 8px;
            border-radius: 50%;
            background: var(--blue);
            content: "";
        }

        .tag.brakes::before { background: #a56cff; }
        .tag.tires::before { background: #23d1c1; }
        .tag.engine::before { background: #1687ff; }
        .tag.other::before { background: #ffbd57; }

        .actions {
            display: flex;
            gap: 6px;
            flex-wrap: nowrap;
        }

        .actions .btn {
            min-height: 32px;
            padding: 6px 9px;
            font-size: 11px;
        }

        .table-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            padding-top: 15px;
            color: var(--muted);
            font-size: 12px;
        }

        .pagination {
            overflow-x: auto;
        }

        .pagination nav > div:first-child {
            display: none;
        }

        .pagination nav > div:last-child {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pagination a,
        .pagination span[aria-current="page"] span,
        .pagination span[aria-disabled="true"] span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            min-height: 32px;
            padding: 6px 10px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: #08172c;
            color: #c9ddfa;
            font-size: 12px;
        }

        .pagination span[aria-current="page"] span {
            border-color: var(--blue);
            background: var(--blue);
            color: white;
        }

        .empty {
            padding: 30px 15px;
            color: var(--muted);
            text-align: center;
        }

        .new-service {
            background:
                radial-gradient(ellipse at 0% 100%, rgba(8,124,255,.10), transparent 45%),
                linear-gradient(145deg, #08172c, #050d1d);
        }

        .service-form {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .field {
            min-width: 0;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        .field label {
            display: block;
            margin-bottom: 7px;
            color: #b9cfee;
            font-size: 12px;
            font-weight: 700;
        }

        .required {
            color: #49a9ff;
        }

        input, select, textarea {
            width: 100%;
            min-width: 0;
            padding: 12px 13px;
            border: 1px solid #25466e;
            border-radius: 9px;
            background: #050f20;
            color: white;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(8,124,255,.13);
        }

        input::placeholder, textarea::placeholder {
            color: #617a9d;
        }

        textarea {
            min-height: 88px;
            resize: vertical;
        }

        select option {
            background: #071226;
            color: white;
        }

        .form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 17px;
        }

        .success {
            margin: 0 0 18px;
            padding: 13px 15px;
            border: 1px solid #17694e;
            border-radius: 9px;
            background: #103b30;
            color: #9df2cf;
        }

        .error-box {
            margin-bottom: 18px;
            padding: 15px;
            border: 1px solid #71304a;
            border-radius: 10px;
            background: #24101c;
            color: #ffb3c2;
        }

        .error-box ul {
            margin-bottom: 0;
        }

        .print-only {
            display: none;
        }

        @media (max-width: 1250px) {
            .main {
                padding: 22px;
            }

            .sidebar {
                width: 190px;
                flex-basis: 190px;
            }

            .summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 850px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }

            .service-form {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .sidebar {
                width: 165px;
                flex-basis: 165px;
            }

            .nav {
                padding: 12px;
                font-size: 12px;
            }

            .brand-caption {
                display: none;
            }
        }

        @media (max-width: 620px) {
            .topbar {
                padding: 14px;
            }

            .brand {
                font-size: 23px;
            }

            .top-link {
                font-size: 11px;
            }

            .sidebar {
                display: none;
            }

            .main {
                padding: 15px;
            }

            .summary {
                grid-template-columns: 1fr 1fr;
                gap: 9px;
            }

            .summary-card {
                padding: 13px;
                min-height: 100px;
            }

            .summary-label {
                align-items: flex-start;
                font-size: 11px;
            }

            .summary-value {
                font-size: 20px;
            }

            .service-form {
                grid-template-columns: 1fr;
            }

            .field.full {
                grid-column: auto;
            }

            .panel {
                padding: 17px;
            }

            .section-head {
                align-items: flex-start;
            }

            .head-actions {
                width: 100%;
            }

            .head-actions .btn {
                flex: 1;
            }

            .person-name {
                font-size: 21px;
            }
        }

        @media print {
            @page {
                size: landscape;
                margin: 12mm;
            }

            body {
                background: white !important;
                color: #111 !important;
                font-size: 11px;
            }

            .topbar, .sidebar, .no-print, .actions,
            .new-service, .back {
                display: none !important;
            }

            .print-only {
                display: block;
            }

            .layout {
                display: block;
                min-height: 0;
            }

            .main {
                padding: 0;
            }

            .panel, .motorcycle-card, .total-card {
                background: white !important;
                color: #111 !important;
                border: 1px solid #ccc !important;
                box-shadow: none !important;
                break-inside: avoid;
            }

            .muted, .info, .bike-meta, .summary-label,
            .summary-value, .person-name, .bike-title,
            tbody td, thead th, .eyebrow, .bike-stat span,
            .bike-stat strong, .work-description {
                color: #222 !important;
            }

            .profile-grid {
                grid-template-columns: 1fr 1.3fr;
            }

            .summary {
                grid-template-columns: repeat(4, 1fr);
            }

            .history {
                margin-top: 12px;
            }

            table {
                min-width: 0;
            }

            thead th, tbody td {
                padding: 8px;
                font-size: 10px;
            }

            .table-wrap {
                overflow: visible;
            }

            .tag {
                color: #222 !important;
                border-color: #ccc !important;
                background: white !important;
            }

            .tag::before {
                display: none;
            }
        }
    </style>
</head>
<body>

<header class="topbar">
    <div class="brand-wrap">
        <a href="{{ route('workshop.dashboard') }}" class="brand">
            MOTO<span>HUB</span>
        </a>

        <div class="brand-caption">
            <strong>WORKSHOP</strong>
            <small>RIDE. SERVICE. REPEAT.</small>
        </div>
    </div>

    <a class="top-link" href="{{ route('workshop.dashboard') }}">
        <span class="top-icon">🔧</span>
        <span>სახელოსნო</span>
        <span style="color:#25466e">│</span>
        <span>● პროფილი</span>
    </a>
</header>

<div class="layout">
    <aside class="sidebar">
        <a class="nav" href="{{ route('workshop.dashboard') }}">
            <span class="nav-icon">⌂</span> მთავარი
        </a>

        <a class="nav active" href="{{ route('workshop.customers.index') }}">
            <span class="nav-icon">♙</span> კლიენტები
        </a>

        <a class="nav" href="{{ route('workshop.customers.index') }}">
            <span class="nav-icon">🏍</span> მოტოციკლები
        </a>

        <a class="nav active" href="{{ route('workshop.customers.index') }}">
            <span class="nav-icon">🔧</span> მომსახურება
        </a>

        <a class="nav" href="{{ route('workshop.customers.index') }}">
            <span class="nav-icon">▤</span> ანგარიშები
        </a>

        <div class="sidebar-footer">
            <strong>MotoHub</strong><br>
            © {{ date('Y') }}. ყველა უფლება დაცულია.
        </div>
    </aside>

    <main class="main">
        <div class="page-top">
            <div>
                <a class="btn back" href="{{ route('workshop.customers.index') }}">
                    ← უკან დაბრუნება
                </a>

                <h1>კლიენტის მომსახურების ბარათი</h1>
            </div>

            <button class="btn no-print" type="button" onclick="window.print()">
                ▤ ბეჭდვა
            </button>
        </div>

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="error-box">
                <strong>შეამოწმე შეყვანილი მონაცემები:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="profile-grid">
            <article class="panel customer-card">
                <div class="card-heading">
                    <span class="circle-icon">♙</span>
                    <div class="eyebrow">კლიენტი</div>
                </div>

                <div class="person-name">
                    {{ $customer->name ?? 'კლიენტი' }}
                </div>

                <div class="info">
                    <span class="info-icon">☎</span>
                    <span>{{ $customer->phone ?: 'ტელეფონი მითითებული არ არის' }}</span>
                </div>

                <div class="info">
                    <span class="info-icon">✉</span>
                    <span>{{ $customer->email ?: 'ელფოსტა მითითებული არ არის' }}</span>
                </div>

                <div class="info">
                    <span class="info-icon">⌖</span>
                    <span>{{ $customer->address ?: 'მისამართი მითითებული არ არის' }}</span>
                </div>
            </article>

            <article class="panel motorcycle-card">
                <div class="card-heading">
                    <span class="circle-icon">🏍</span>
                    <div class="eyebrow">მოტოციკლი</div>
                </div>

                <div class="bike-title">
                    {{ $motorcycle->title ?: trim(($motorcycle->brand ?? '') . ' ' . ($motorcycle->model ?? '')) }}
                </div>

                <div class="bike-meta">
                    {{ $motorcycle->year ?? '—' }}
                    &nbsp; · &nbsp;
                    {{ $motorcycle->engine_cc ? $motorcycle->engine_cc . ' cc' : 'ძრავის მოცულობა —' }}
                </div>

                <div class="bike-stats">
                    <div class="bike-stat">
                        <span>ბრენდი</span>
                        <strong>{{ $motorcycle->brand ?: '—' }}</strong>
                    </div>

                    <div class="bike-stat">
                        <span>მოდელი</span>
                        <strong>{{ $motorcycle->model ?: '—' }}</strong>
                    </div>

                    <div class="bike-stat">
                        <span>მდგომარეობა</span>
                        <strong>{{ $motorcycle->condition ?: '—' }}</strong>
                    </div>

                    <div class="bike-stat">
                        <span>ბოლო ჩაწერილი გარბენი</span>
                        <strong>
                            @php
                                $latestMileage = $motorcycle->serviceRecords()
                                    ->whereNotNull('mileage')
                                    ->max('mileage');
                            @endphp
                            {{ $latestMileage !== null ? number_format($latestMileage) . ' კმ' : '—' }}
                        </strong>
                    </div>
                </div>
            </article>
        </section>

        <section class="summary">
            <article class="panel summary-card total-card">
                <div class="summary-label">
                    <span class="circle-icon">₾</span>
                    <span>სულ დახარჯული თანხა</span>
                </div>
                <strong class="summary-value">
                    {{ number_format((float) $totalCost, 2) }} ₾
                </strong>
            </article>

            <article class="panel summary-card">
                <div class="summary-label">
                    <span class="circle-icon">🔧</span>
                    <span>სულ მომსახურებები</span>
                </div>
                <strong class="summary-value">
                    {{ number_format($totalServices) }}
                </strong>
            </article>

            <article class="panel summary-card">
                <div class="summary-label">
                    <span class="circle-icon">◉</span>
                    <span>ბოლო ჩაწერილი გარბენი</span>
                </div>
                <strong class="summary-value" style="font-size:21px">
                    {{ $latestMileage !== null ? number_format($latestMileage) . ' კმ' : '—' }}
                </strong>
            </article>

            <article class="panel summary-card">
                <div class="summary-label">
                    <span class="circle-icon">▦</span>
                    <span>ბოლო მომსახურება</span>
                </div>
                <strong class="summary-value" style="font-size:17px">
                    {{ $latestService ? \Illuminate\Support\Carbon::parse($latestService)->format('d.m.Y') : 'ჯერ არ არის' }}
                </strong>
            </article>
        </section>

        <section class="panel history">
            <div class="section-head">
                <div class="section-title">
                    <span class="tool-icon">🔧</span>
                    <h2>მომსახურების ისტორია</h2>
                </div>

                <div class="head-actions no-print">
                    <a class="btn btn-primary" href="#new-service">
                        ＋ ახალი ჩანაწერი
                    </a>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>თარიღი</th>
                            <th>შესრულებული სამუშაო</th>
                            <th>კატეგორია</th>
                            <th>გარბენი (კმ)</th>
                            <th>ღირებულება</th>
                            <th class="no-print">მოქმედება</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse ($serviceRecords as $record)
                        <tr>
                            <td class="row-number">
                                {{ $serviceRecords->firstItem() + $loop->index }}
                            </td>

                            <td>
                                {{ \Illuminate\Support\Carbon::parse($record->service_date)->format('d.m.Y') }}
                            </td>

                            <td>
                                <div class="work-title">{{ $record->title }}</div>

                                @if ($record->description)
                                    <div class="work-description">
                                        {{ $record->description }}
                                    </div>
                                @endif

                                @if ($record->notes)
                                    <div class="work-description">
                                        შენიშვნა: {{ $record->notes }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                @php
                                    $categoryClass = match ($record->category) {
                                        'მუხრუჭები' => 'brakes',
                                        'საბურავები' => 'tires',
                                        'ძრავი' => 'engine',
                                        default => 'other',
                                    };
                                @endphp

                                <span class="tag {{ $categoryClass }}">
                                    {{ $record->category }}
                                </span>
                            </td>

                            <td>
                                {{ $record->mileage !== null ? number_format($record->mileage) : '—' }}
                            </td>

                            <td style="white-space:nowrap;font-weight:700">
                                {{ number_format((float) $record->cost, 2) }} ₾
                            </td>

                            <td class="no-print">
                                <div class="actions">
                                    <a class="btn"
                                       href="{{ route('workshop.customers.motorcycles.service.edit', [
                                           'customer' => $customerId,
                                           'motorcycle' => $motorcycle->id,
                                           'record' => $record->id,
                                       ]) }}">
                                        ✎ რედაქტირება
                                    </a>

                                    <form method="POST"
                                          action="{{ route('workshop.customers.motorcycles.service.destroy', [
                                              'customer' => $customerId,
                                              'motorcycle' => $motorcycle->id,
                                              'record' => $record->id,
                                          ]) }}"
                                          onsubmit="return confirm('ნამდვილად გსურს ამ მომსახურების ჩანაწერის წაშლა?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger" type="submit">
                                            ▤ წაშლა
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty">
                                    მომსახურების ჩანაწერები ჯერ არ არის.
                                    შეგიძლია პირველი ჩანაწერი ქვემოთ დაამატო.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <span>
                    სულ ჩანაწერები: {{ number_format($totalServices) }}
                </span>

                <div class="pagination">
                    {{ $serviceRecords->links() }}
                </div>
            </div>
        </section>

        <section class="panel history new-service" id="new-service">
            <div class="section-head">
                <div class="section-title">
                    <span class="circle-icon">＋</span>
                    <h2>ახალი მომსახურების ჩანაწერი</h2>
                </div>
                <span class="muted" style="font-size:12px">* სავალდებულო ველი</span>
            </div>

            <form method="POST"
                  action="{{ route('workshop.customers.motorcycles.service.store', [
                      'customer' => $customerId,
                      'motorcycle' => $motorcycle->id,
                  ]) }}">
                @csrf

                <div class="service-form">
                    <div class="field">
                        <label for="service_date">
                            თარიღი <span class="required">*</span>
                        </label>
                        <input id="service_date"
                               type="date"
                               name="service_date"
                               value="{{ old('service_date', date('Y-m-d')) }}"
                               max="{{ date('Y-m-d') }}"
                               required>
                    </div>

                    <div class="field">
                        <label for="category">
                            კატეგორია <span class="required">*</span>
                        </label>
                        <select id="category" name="category" required>
                            <option value="">აირჩიე კატეგორია</option>
                            @foreach (['ზეთის შეცვლა', 'ძრავი', 'მუხრუჭები', 'საბურავები', 'ელექტროობა', 'გადაცემათა სისტემა', 'სხვა'] as $category)
                                <option value="{{ $category }}" @selected(old('category') === $category)>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label for="title">
                            შესრულებული სამუშაო <span class="required">*</span>
                        </label>
                        <input id="title"
                               name="title"
                               maxlength="255"
                               value="{{ old('title') }}"
                               placeholder="მაგ. ზეთისა და ფილტრის შეცვლა"
                               required>
                    </div>

                    <div class="field">
                        <label for="mileage">გარბენი (კმ)</label>
                        <input id="mileage"
                               type="number"
                               name="mileage"
                               min="0"
                               value="{{ old('mileage') }}"
                               placeholder="მაგ. 12500">
                    </div>

                    <div class="field">
                        <label for="cost">
                            ღირებულება (₾) <span class="required">*</span>
                        </label>
                        <input id="cost"
                               type="number"
                               name="cost"
                               min="0"
                               max="99999999.99"
                               step="0.01"
                               value="{{ old('cost', '0.00') }}"
                               required>
                    </div>

                    <div class="field">
                        <label for="description">სამუშაოს აღწერა</label>
                        <input id="description"
                               name="description"
                               value="{{ old('description') }}"
                               maxlength="5000"
                               placeholder="დამატებითი ინფორმაცია">
                    </div>

                    <div class="field full">
                        <label for="notes">შენიშვნები</label>
                        <textarea id="notes"
                                  name="notes"
                                  maxlength="5000"
                                  placeholder="ნაწილები, რეკომენდაციები ან შემდეგი ვიზიტის ინფორმაცია">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="form-footer">
                    <span class="muted" style="font-size:12px">
                        მონაცემები შეინახება ამ მოტოციკლის მომსახურების ისტორიაში.
                    </span>

                    <button class="btn btn-primary" type="submit">
                        ▣ ჩანაწერის შენახვა
                    </button>
                </div>
            </form>
        </section>

        <div class="print-only" style="margin-top:20px">
            MotoHub · სახელოსნოს მომსახურების ბარათი
        </div>
    </main>
</div>

</body>
</html>
