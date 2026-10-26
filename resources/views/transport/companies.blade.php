<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>გადამზიდავი კომპანიები | MotoHub</title>
    <meta name="description" content="იპოვე მოტოციკლის გადამზიდავი კომპანია MotoHub-ზე.">
    <style>
        :root {
            --bg: #070a12;
            --panel: #0d1422;
            --panel2: #121d30;
            --blue: #1677ff;
            --light-blue: #42a5ff;
            --text: #f4f8ff;
            --muted: #98a8c2;
            --border: #243653;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Arial, sans-serif;
        }

        a { color: inherit; text-decoration: none; }

        .container {
            width: min(1120px, 92%);
            margin: auto;
        }

        header {
            border-bottom: 1px solid var(--border);
            background: #090e19;
        }

        .nav {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .logo {
            font-size: 25px;
            font-weight: 900;
            letter-spacing: -1px;
        }

        .logo span { color: var(--light-blue); }

        .nav-link {
            color: var(--muted);
            font-size: 14px;
        }

        .hero { padding: 58px 0 34px; }

        .eyebrow {
            color: var(--light-blue);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        h1 {
            max-width: 760px;
            margin: 14px 0;
            font-size: clamp(32px, 5vw, 56px);
            line-height: 1.08;
            letter-spacing: -1.8px;
        }

        .subtitle {
            max-width: 650px;
            color: var(--muted);
            line-height: 1.8;
        }

        .search {
            display: flex;
            gap: 10px;
            margin-top: 26px;
            max-width: 680px;
        }

        input {
            flex: 1;
            min-width: 0;
            padding: 15px;
            color: var(--text);
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 9px;
            outline: none;
        }

        input:focus { border-color: var(--blue); }

        button, .button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 13px 17px;
            border: 0;
            border-radius: 9px;
            background: var(--blue);
            color: white;
            font-weight: 800;
            cursor: pointer;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin: 24px 0 18px;
        }

        .section-head h2 { font-size: 22px; }

        .count { color: var(--muted); font-size: 13px; }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            padding-bottom: 60px;
        }

        .company {
            display: flex;
            flex-direction: column;
            min-width: 0;
            padding: 22px;
            background: linear-gradient(145deg, var(--panel2), var(--panel));
            border: 1px solid var(--border);
            border-radius: 14px;
            transition: border-color .2s, transform .2s;
        }

        .company:hover {
            border-color: var(--blue);
            transform: translateY(-3px);
        }

        .company-top {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 58px;
            height: 58px;
            overflow: hidden;
            border-radius: 12px;
            background: #172b49;
            color: var(--light-blue);
            font-size: 22px;
            font-weight: 900;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .company h3 {
            margin: 0;
            font-size: 18px;
            overflow-wrap: anywhere;
        }

        .verified {
            margin-top: 7px;
            color: var(--light-blue);
            font-size: 12px;
        }

        .description {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
            margin: 20px 0;
            overflow-wrap: anywhere;
            flex: 1;
        }

        .detail {
            padding: 11px 0;
            border-top: 1px solid var(--border);
            color: #d4e0f3;
            font-size: 13px;
            overflow-wrap: anywhere;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 18px;
        }

        .actions .button { flex: 1; font-size: 13px; }

        .secondary {
            border: 1px solid var(--border);
            background: transparent;
            color: var(--light-blue);
        }

        .empty {
            grid-column: 1 / -1;
            padding: 38px 20px;
            text-align: center;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        .empty p { color: var(--muted); line-height: 1.7; }

        .pagination { padding-bottom: 50px; }
        .pagination nav > div { margin-bottom: 14px; }
        .pagination a, .pagination span {
            display: inline-block;
            padding: 9px 12px;
            margin: 3px;
            border: 1px solid var(--border);
            border-radius: 7px;
            color: var(--text);
        }

        footer {
            border-top: 1px solid var(--border);
            padding: 24px 0;
            color: var(--muted);
            font-size: 13px;
        }

        @media (max-width: 850px) {
            .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 580px) {
            .grid { grid-template-columns: 1fr; }
            .hero { padding-top: 38px; }
            .search { flex-direction: column; }
            .search button { width: 100%; }
            .nav { min-height: 65px; }
        }
    </style>
</head>
<body>
<header>
    <div class="container nav">
        <a class="logo" href="{{ route('home') }}">MOTO<span>HUB</span></a>
        <a class="nav-link" href="{{ route('transport.index') }}">← ტრანსპორტის მოთხოვნები</a>
    </div>
</header>

<main class="container">
    <section class="hero">
        <div class="eyebrow">MotoHub Transport</div>
        <h1>მოძებნე შენი მოტოციკლის გადამზიდავი.</h1>
        <p class="subtitle">
            გაეცანი გადამზიდავ კომპანიებს, შეადარე საკონტაქტო ინფორმაცია
            და გაგზავნე ტრანსპორტირების მოთხოვნა.
        </p>

        <form class="search" action="{{ route('transport.companies') }}" method="GET">
            <input
                type="search"
                name="q"
                value="{{ request('q') }}"
                placeholder="კომპანიის სახელი, ქალაქი..."
                aria-label="კომპანიის ძებნა"
            >
            <button type="submit">ძებნა ↗</button>
        </form>
    </section>

    <div class="section-head">
        <h2>გადამზიდავი კომპანიები</h2>
        <span class="count">{{ $companies->total() }} კომპანია</span>
    </div>

    <section class="grid">
        @forelse ($companies as $company)
            <article class="company">
                <div class="company-top">
                    <div class="avatar">
                        @if ($company->logo_path)
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::url($company->logo_path) }}"
                                alt="{{ $company->name }}"
                                loading="lazy"
                            >
                        @else
                            {{ mb_strtoupper(mb_substr($company->name, 0, 1)) }}
                        @endif
                    </div>

                    <div>
                        <h3>{{ $company->name }}</h3>
                        <div class="verified">✓ დამტკიცებული კომპანია</div>
                    </div>
                </div>

                <p class="description">
                    {{ $company->description ?: 'დაუკავშირდი კომპანიას მოტოციკლის ტრანსპორტირების პირობების გასარკვევად.' }}
                </p>

                @if ($company->address)
                    <div class="detail">📍 {{ $company->address }}</div>
                @endif

                @if ($company->phone)
                    <div class="detail">☎ {{ $company->phone }}</div>
                @endif

                @if ($company->email)
                    <div class="detail">✉ {{ $company->email }}</div>
                @endif

                <div class="actions">
                    @if ($company->phone)
                        <a class="button" href="tel:{{ $company->phone }}">დარეკვა</a>
                    @endif
                    <a
    class="button secondary"
    href="{{ route('transport.companies.show', $company->slug) }}"
>
    კომპანიის პროფილი
</a>

                    <a class="button secondary" href="{{ route('transport.create') }}">
                        მოთხოვნის გაგზავნა
                    </a>
                </div>
            </article>
        @empty
            <div class="empty">
                <h3>კომპანიები ჯერ არ მოიძებნა</h3>
                <p>
                    ამჟამად შესაბამისი აქტიური გადამზიდავი კომპანია არ არის
                    გამოქვეყნებული. სცადე სხვა საძიებო სიტყვა ან მოგვიანებით დაბრუნდი.
                </p>
                @if (request()->filled('q'))
                    <a class="button" href="{{ route('transport.companies') }}">ყველა კომპანიის ნახვა</a>
                @endif
            </div>
        @endforelse
    </section>

    <div class="pagination">
        {{ $companies->links() }}
    </div>
</main>

<footer>
    <div class="container">© {{ date('Y') }} MotoHub — მოტოციკლის ტრანსპორტირების სერვისი</div>
</footer>
</body>
</html>