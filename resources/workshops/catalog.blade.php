<!DOCTYPE html>
<!-- WORKSHOPS-CATALOG-CHECK -->
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>სახელოსნოები | MotoHub</title>
    <meta name="description" content="შეარჩიე მოტოციკლის სახელოსნო MotoHub-ზე.">
    <style>
        :root {
            --bg: #070b14;
            --panel: #101827;
            --panel-light: #151f31;
            --blue: #1687ff;
            --text: #f4f7fb;
            --muted: #95a3b8;
            --border: #253248;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Arial, "Noto Sans Georgian", sans-serif;
        }

        a { color: inherit; text-decoration: none; }

        .container {
            width: min(1160px, calc(100% - 36px));
            margin: auto;
        }

        header {
            border-bottom: 1px solid var(--border);
            background: rgba(7, 11, 20, .95);
        }

        .nav {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -1px;
        }

        .logo span { color: var(--blue); }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 22px;
            color: var(--muted);
            font-size: 14px;
        }

        .nav-links a:hover { color: white; }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--blue);
            border-radius: 8px;
            padding: 12px 17px;
            color: white;
            background: var(--blue);
            font-weight: 700;
            cursor: pointer;
        }

        .hero {
            padding: 68px 0 38px;
            background:
                radial-gradient(ellipse at 80% 10%, rgba(22,135,255,.16), transparent 42%),
                var(--bg);
        }

        .eyebrow {
            color: #56a9ff;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        h1 {
            max-width: 760px;
            margin: 16px 0;
            font-size: clamp(34px, 6vw, 58px);
            line-height: 1.12;
            letter-spacing: -2px;
        }

        h1 span { color: var(--blue); }

        .hero p {
            max-width: 620px;
            color: var(--muted);
            font-size: 16px;
            line-height: 1.8;
        }

        .search {
            display: flex;
            gap: 10px;
            max-width: 700px;
            margin-top: 28px;
        }

        .search input {
            min-width: 0;
            flex: 1;
            padding: 15px 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--panel);
            color: white;
            outline: none;
            font-size: 15px;
        }

        .search input:focus { border-color: var(--blue); }

        .section { padding: 28px 0 76px; }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        h2 { margin: 0; font-size: 25px; }

        .count {
            color: var(--muted);
            font-size: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .card {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--panel);
            transition: border-color .2s, transform .2s;
        }

        .card:hover {
            border-color: var(--blue);
            transform: translateY(-3px);
        }

        .card-top {
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background:
                radial-gradient(circle at 50% 40%, rgba(22,135,255,.20), transparent 65%),
                var(--panel-light);
            border-bottom: 1px solid var(--border);
        }

        .workshop-logo {
            width: 82px;
            height: 82px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid #30445f;
            border-radius: 18px;
            background: #0b1423;
            color: #5badff;
            font-size: 30px;
            font-weight: 900;
        }

        .workshop-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .verified {
            position: absolute;
            top: 14px;
            right: 14px;
            border: 1px solid rgba(22,135,255,.35);
            border-radius: 20px;
            padding: 6px 10px;
            color: #8cc6ff;
            background: #102540;
            font-size: 11px;
            font-weight: 700;
        }

        .card-body { padding: 21px; }

        .card h3 {
            margin: 0 0 12px;
            font-size: 20px;
        }

        .detail {
            display: flex;
            gap: 9px;
            margin: 11px 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .description {
            min-height: 48px;
            margin: 15px 0 20px;
            color: #c2ccda;
            font-size: 14px;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .status {
            color: #70dca3;
            font-size: 12px;
        }

        .empty {
            padding: 55px 20px;
            border: 1px dashed var(--border);
            border-radius: 14px;
            text-align: center;
        }

        .empty h3 { margin-top: 0; }

        .empty p {
            color: var(--muted);
            line-height: 1.7;
        }

        .pagination {
            margin-top: 30px;
            overflow-x: auto;
        }

        .pagination nav > div:first-child { display: none; }

        .pagination nav > div:last-child {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .pagination a, .pagination span {
            display: inline-block;
            padding: 9px 13px;
            border: 1px solid var(--border);
            border-radius: 7px;
            color: var(--muted);
        }

        .pagination [aria-current="page"] span {
            color: white;
            border-color: var(--blue);
            background: var(--blue);
        }

        footer {
            padding: 25px 0;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 13px;
        }

        @media (max-width: 850px) {
            .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .nav-links { gap: 12px; }
        }

        @media (max-width: 580px) {
            .nav { min-height: 68px; }
            .nav-links a:not(.button) { display: none; }
            .hero { padding-top: 45px; }
            h1 { letter-spacing: -1px; }
            .search { flex-direction: column; }
            .search .button { width: 100%; }
            .grid { grid-template-columns: 1fr; }
            .section-heading { align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>
<body>

<header>
    <div class="container nav">
        <a href="{{ route('home') }}" class="logo">Moto<span>Hub</span></a>

        <nav class="nav-links">
            <a href="{{ route('home') }}">მთავარი</a>
            <a href="{{ url('/motorcycles') }}">მოტოციკლები</a>
            <a href="{{ url('/parts') }}">ნაწილები</a>
            <a href="{{ route('workshops.index') }}" style="color:white">სახელოსნოები</a>
            <a href="{{ route('login') }}" class="button">შესვლა</a>
        </nav>
    </div>
</header>

<main>
    <section class="hero">
        <div class="container">
            <div class="eyebrow">MotoHub / Workshop Network</div>

            <h1>იპოვე შენი მოტოციკლის <span>სახელოსნო.</span></h1>

            <p>
                დაათვალიერე დადასტურებული სახელოსნოები, ნახე მათი საკონტაქტო
                ინფორმაცია და შეარჩიე შენთვის მოსახერხებელი ადგილი.
            </p>

            <form class="search" action="{{ route('workshops.index') }}" method="GET">
                <input
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="სახელოსნოს სახელი, მისამართი..."
                    aria-label="სახელოსნოს ძიება"
                >
                <button class="button" type="submit">ძიება →</button>
            </form>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <h2>სახელოსნოების კატალოგი</h2>
                <div class="count">
                    {{ $workshops->total() }} სახელოსნო
                </div>
            </div>

            @if ($workshops->count())
                <div class="grid">
                    @foreach ($workshops as $workshop)
                        <article class="card">
                            <div class="card-top">
                                <div class="workshop-logo">
                                    @if ($workshop->logo_path)
                                        <img
                                            src="{{ asset('storage/' . $workshop->logo_path) }}"
                                            alt="{{ $workshop->name }}"
                                        >
                                    @else
                                        {{ mb_strtoupper(mb_substr($workshop->name, 0, 1)) }}
                                    @endif
                                </div>

                                <span class="verified">✓ დადასტურებული</span>
                            </div>

                            <div class="card-body">
                                <h3>{{ $workshop->name }}</h3>

                                <div class="detail">
                                    <span>📍</span>
                                    <span>{{ $workshop->address ?: 'მისამართი მითითებული არ არის' }}</span>
                                </div>

                                <div class="detail">
                                    <span>☎</span>
                                    <span>{{ $workshop->phone ?: 'ტელეფონი მითითებული არ არის' }}</span>
                                </div>

                                <p class="description">
                                    {{ $workshop->description ?: 'სახელოსნოს დამატებითი ინფორმაცია მალე დაემატება.' }}
                                </p>

                                <div class="card-footer">
                                    <span class="status">● აქტიური სახელოსნო</span>
                                    @if ($workshop->phone)
                                        <a class="button" href="tel:{{ $workshop->phone }}">
                                            დაკავშირება
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="pagination">
                    {{ $workshops->links() }}
                </div>
            @else
                <div class="empty">
                    <h3>სახელოსნოები ჯერ არ მოიძებნა</h3>

                    <p>
                        @if (request('q'))
                            სცადე სხვა საძიებო სიტყვა.
                        @else
                            დადასტურებული სახელოსნოები აქ გამოჩნდება, როგორც კი ხელმისაწვდომი გახდება.
                        @endif
                    </p>

                    @if (request('q'))
                        <a href="{{ route('workshops.index') }}" class="button">
                            ყველა სახელოსნოს ნახვა
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </section>
</main>

<footer>
    <div class="container">
        © {{ date('Y') }} MotoHub — მოტოციკლის სამყარო ერთ სივრცეში.
    </div>
</footer>

</body>
</html>
