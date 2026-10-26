<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $partner->name }} | MotoHub Workshop</title>

    <style>
        * { box-sizing: border-box; }

        :root {
            --bg: #070b14;
            --panel: #101827;
            --panel2: #0c1422;
            --border: #24344c;
            --text: #edf4ff;
            --muted: #8fa1bb;
            --blue: #2587ff;
            --green: #43d9a3;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Arial, sans-serif;
        }

        a { color: inherit; text-decoration: none; }

        .layout {
            display: grid;
            grid-template-columns: 235px minmax(0, 1fr);
            min-height: 100vh;
        }

        aside {
            padding: 28px 18px;
            background: #0a111e;
            border-right: 1px solid var(--border);
        }

        .logo {
            font-size: 23px;
            font-weight: 900;
            letter-spacing: -1px;
            padding: 0 10px 30px;
        }

        .logo span { color: var(--blue); }

        .nav-label {
            color: #61738d;
            font-size: 11px;
            letter-spacing: 1.5px;
            margin: 18px 10px 10px;
        }

        .nav-link {
            display: block;
            padding: 12px;
            margin: 5px 0;
            border-radius: 10px;
            color: #aab9ce;
            font-size: 14px;
        }

        .nav-link.active {
            background: #10294a;
            color: #72b6ff;
            border: 1px solid #1e4778;
        }

        .nav-link:hover { background: #142136; }

        main { min-width: 0; }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 22px 32px;
            border-bottom: 1px solid var(--border);
            background: #0a111c;
        }

        .eyebrow {
            color: var(--blue);
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        header h1 {
            font-size: 20px;
            margin: 7px 0 0;
        }

        .online {
            color: var(--green);
            font-size: 12px;
            border: 1px solid #225843;
            background: #0e251f;
            padding: 9px 12px;
            border-radius: 30px;
            white-space: nowrap;
        }

        .content {
            max-width: 1400px;
            margin: auto;
            padding: 32px;
        }

        .welcome {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 28px;
        }

        .welcome h2 {
            font-size: clamp(24px, 3vw, 34px);
            margin: 0 0 9px;
            letter-spacing: -1px;
        }

        .muted { color: var(--muted); line-height: 1.6; }

        .button {
            display: inline-block;
            background: var(--blue);
            color: white;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: bold;
            border: 1px solid #4098ff;
        }

        .button:hover { background: #0873ed; }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 15px;
            margin-bottom: 28px;
        }

        .card, .section {
            background: linear-gradient(145deg, var(--panel), #0d1523);
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        .card { padding: 21px; }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .card-label { color: var(--muted); font-size: 13px; }

        .icon {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 11px;
            color: #75b9ff;
            background: #10294a;
            font-size: 18px;
        }

        .number {
            font-size: 30px;
            font-weight: 800;
            margin: 19px 0 8px;
            letter-spacing: -1px;
            overflow-wrap: anywhere;
        }

        .card-note { color: #70839e; font-size: 12px; }

        .columns {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(270px, 0.8fr);
            gap: 18px;
            align-items: start;
        }

        .section { padding: 22px; min-width: 0; }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .section h3 { margin: 0; font-size: 16px; }

        .count {
            color: #8bbfff;
            font-size: 12px;
            background: #112747;
            padding: 6px 9px;
            border-radius: 20px;
        }

        .service {
            padding: 15px 0;
            border-bottom: 1px solid #202e42;
        }

        .service:last-child { border-bottom: 0; }

        .service-title {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            font-size: 14px;
            font-weight: bold;
        }

        .service-meta {
            color: var(--muted);
            font-size: 12px;
            margin-top: 8px;
            line-height: 1.6;
        }

        .price {
            color: var(--green);
            white-space: nowrap;
        }

        .empty {
            text-align: center;
            padding: 28px 10px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .action {
            min-height: 104px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 12px;
            background: var(--panel2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 15px;
            transition: .2s;
        }

        .action:hover {
            border-color: var(--blue);
            transform: translateY(-2px);
        }

        .action-icon { color: #67b1ff; font-size: 21px; }
        .action-title { font-size: 13px; font-weight: bold; }
        .action-arrow { color: #7188a7; }

        .business-info {
            margin-top: 18px;
            padding: 18px;
            background: #0b1422;
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        .business-info strong { display: block; margin-bottom: 8px; }
        .business-info p { margin: 5px 0; color: var(--muted); font-size: 13px; }

        .footer {
            text-align: center;
            color: #50617a;
            font-size: 11px;
            padding: 28px 0 5px;
        }

        @media (max-width: 1100px) {
            .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .columns { grid-template-columns: 1fr; }
        }

        @media (max-width: 700px) {
            .layout { grid-template-columns: 1fr; }
            aside {
                padding: 16px;
                border-right: 0;
                border-bottom: 1px solid var(--border);
            }
            .logo { padding: 0 4px 12px; }
            .nav-label { display: none; }
            aside nav {
                display: flex;
                gap: 6px;
                overflow-x: auto;
            }
            .nav-link { white-space: nowrap; margin: 0; padding: 10px; }
            header { padding: 18px; }
            .content { padding: 20px 15px; }
            .welcome { align-items: flex-start; flex-direction: column; }
            .stats { gap: 10px; }
            .card { padding: 15px; }
            .number { font-size: 25px; }
            .section { padding: 16px; }
        }
    </style>
</head>
<body>
<div class="layout">

    <aside>
        <div class="logo">MOTO<span>HUB</span></div>

        <div class="nav-label">WORKSPACE</div>

        <nav>
            <a class="nav-link active" href="{{ route('workshop.dashboard') }}">
                ◈ &nbsp; მთავარი გვერდი
            </a>

            <a class="nav-link" href="{{ route('workshop.customers.index') }}">
                ◎ &nbsp; კლიენტები
            </a>

            <a class="nav-link" href="{{ route('home') }}">
                ↗ &nbsp; საიტის ნახვა
            </a>
        </nav>

        <div class="business-info">
            <strong>{{ $partner->name }}</strong>

            @if ($partner->phone)
                <p>☎ {{ $partner->phone }}</p>
            @endif

            @if ($partner->address)
                <p>⌖ {{ $partner->address }}</p>
            @endif

            <p style="color:#43d9a3">● აქტიური სახელოსნო</p>
        </div>
    </aside>

    <main>
        <header>
            <div>
                <div class="eyebrow">MOTOHUB / WORKSHOP</div>
                <h1>მართვის პანელი</h1>
            </div>

            <div class="online">● სისტემა აქტიურია</div>
        </header>

        <div class="content">
            <div class="welcome">
                <div>
                    <h2>მოგესალმებით, {{ $partner->name }}</h2>
                    <div class="muted">
                        შენი სახელოსნოს მიმდინარე მდგომარეობა ერთ სივრცეში.
                    </div>
                </div>

                <a class="button" href="{{ route('workshop.customers.index') }}">
                    + კლიენტების მართვა
                </a>
            </div>

            <div class="stats">
                <div class="card">
                    <div class="card-top">
                        <span class="card-label">კლიენტები</span>
                        <span class="icon">◎</span>
                    </div>
                    <div class="number">{{ number_format($customersCount) }}</div>
                    <div class="card-note">რეგისტრირებული კლიენტები</div>
                </div>

                <div class="card">
                    <div class="card-top">
                        <span class="card-label">მოტოციკლები</span>
                        <span class="icon">🏍</span>
                    </div>
                    <div class="number">{{ number_format($motorcyclesCount) }}</div>
                    <div class="card-note">კლიენტებზე მიბმული მოტოციკლები</div>
                </div>

                <div class="card">
                    <div class="card-top">
                        <span class="card-label">მომსახურებები</span>
                        <span class="icon">⚙</span>
                    </div>
                    <div class="number">{{ number_format($servicesCount) }}</div>
                    <div class="card-note">მომსახურების ისტორიის ჩანაწერები</div>
                </div>

                <div class="card">
                    <div class="card-top">
                        <span class="card-label">ამ თვის მომსახურებები</span>
                        <span class="icon">₾</span>
                    </div>
                    <div class="number" style="font-size:25px">
                        ₾{{ number_format((float) $monthlyRevenue, 2) }}
                    </div>
                    <div class="card-note">მომსახურების ჩანაწერების ღირებულება</div>
                </div>
            </div>

            <div class="columns">
                <section class="section">
                    <div class="section-head">
                        <h3>ბოლო მომსახურებები</h3>
                        <span class="count">{{ $recentServices->count() }} ჩანაწერი</span>
                    </div>

                    @forelse ($recentServices as $service)
                        <div class="service">
                            <div class="service-title">
                                <span>
                                    {{ $service->title ?: ($service->category ?: 'მომსახურება') }}
                                </span>

                                <span class="price">
                                    ₾{{ number_format((float) $service->cost, 2) }}
                                </span>
                            </div>

                            <div class="service-meta">
                                მოტოციკლი:
                                {{ $service->motorcycle?->title
                                    ?: trim(($service->motorcycle?->brand ?? '') . ' ' . ($service->motorcycle?->model ?? ''))
                                    ?: 'მითითებული არ არის' }}

                                <br>

                                კლიენტი:
                                {{ $service->motorcycle?->customer?->name ?? 'მითითებული არ არის' }}

                                <br>

                                თარიღი:
                                {{ $service->service_date
                                    ? \Illuminate\Support\Carbon::parse($service->service_date)->format('d.m.Y')
                                    : 'თარიღი მითითებული არ არის' }}
                            </div>
                        </div>
                    @empty
                        <div class="empty">
                            <div style="font-size:30px;margin-bottom:10px">⚙</div>
                            მომსახურების ჩანაწერები ჯერ არ არის.<br>
                            ახალი ჩანაწერის დამატების შემდეგ აქ გამოჩნდება ისტორია.
                        </div>
                    @endforelse
                </section>

                <section class="section">
                    <div class="section-head">
                        <h3>სწრაფი მოქმედებები</h3>
                    </div>

                    <div class="actions">
                        <a class="action" href="{{ route('workshop.customers.index') }}">
                            <span class="action-icon">＋</span>
                            <span class="action-title">კლიენტები</span>
                            <span class="action-arrow">მართვა →</span>
                        </a>

                        <a class="action" href="{{ route('workshop.customers.index') }}">
                            <span class="action-icon">🏍</span>
                            <span class="action-title">მოტოციკლები</span>
                            <span class="action-arrow">ნახვა →</span>
                        </a>

                        <a class="action" href="{{ route('workshop.customers.index') }}">
                            <span class="action-icon">⚙</span>
                            <span class="action-title">მომსახურების ისტორია</span>
                            <span class="action-arrow">ნახვა →</span>
                        </a>

                        <a class="action" href="{{ route('home') }}">
                            <span class="action-icon">↗</span>
                            <span class="action-title">საიტის ნახვა</span>
                            <span class="action-arrow">გადასვლა →</span>
                        </a>
                    </div>
                </section>
            </div>

            <div class="footer">
                MotoHub Workshop Management · {{ now()->format('Y') }}
            </div>
        </div>
    </main>
</div>
</body>
</html>
