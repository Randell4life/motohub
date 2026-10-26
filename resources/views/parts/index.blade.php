
<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>სათადარიგო ნაწილები | MotoHub</title>
    <meta name="description" content="იპოვე მოტოციკლის სათადარიგო ნაწილები MotoHub-ის კატალოგში.">

    <style>
        :root {
            color-scheme: dark;
            --bg: #07090e;
            --surface: #0e121b;
            --surface-2: #131a27;
            --blue: #3478ff;
            --blue-light: #82acff;
            --text: #f4f7ff;
            --muted: #929bad;
            --border: #252d3b;
            --green: #65dba0;
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            background:
                radial-gradient(ellipse at 50% 0%, rgba(38, 86, 170, .12), transparent 38%),
                var(--bg);
            color: var(--text);
            font-family: Arial, "Noto Sans Georgian", sans-serif;
        }

        a { color: inherit; text-decoration: none; }
        button, input { font: inherit; }

        .container {
            width: min(1200px, calc(100% - 40px));
            margin-inline: auto;
        }

        /* HEADER */

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            background: rgba(7, 9, 14, .88);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(18px);
        }

        .nav {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -1.5px;
            white-space: nowrap;
        }

        .logo-mark {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(145deg, #4385ff, #1945a6);
            color: white;
            font-size: 18px;
            box-shadow: 0 5px 24px rgba(52, 120, 255, .2);
        }

        .logo span { color: var(--blue-light); }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
            color: #b5bece;
            font-size: 13px;
        }

        .nav-links a {
            transition: color .2s;
        }

        .nav-links a:hover,
        .nav-links a.current {
            color: white;
        }

        .nav-links a.current {
            position: relative;
        }

        .nav-links a.current::after {
            content: "";
            position: absolute;
            bottom: -29px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--blue);
        }

        /* HERO */

        .hero {
            position: relative;
            overflow: hidden;
            padding: 65px 0 45px;
        }

        .hero::after {
            content: "PARTS";
            position: absolute;
            top: 32px;
            right: -12px;
            z-index: -1;
            color: rgba(130, 172, 255, .035);
            font-size: clamp(90px, 18vw, 220px);
            font-weight: 900;
            letter-spacing: -10px;
            pointer-events: none;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--blue-light);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: "";
            width: 25px;
            height: 2px;
            background: var(--blue);
        }

        h1 {
            margin: 20px 0 16px;
            font-size: clamp(37px, 6vw, 67px);
            line-height: 1.12;
            letter-spacing: -2.8px;
        }

        h1 span {
            color: var(--blue-light);
        }

        .hero-description {
            max-width: 570px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.9;
        }

        /* SEARCH */

        .search-form {
            display: flex;
            gap: 10px;
            max-width: 740px;
            margin-top: 30px;
            padding: 7px;
            border: 1px solid #29364b;
            border-radius: 12px;
            background: rgba(14, 18, 27, .94);
            box-shadow: 0 15px 45px rgba(0, 0, 0, .18);
        }

        .search-input-wrap {
            display: flex;
            flex: 1;
            align-items: center;
            gap: 12px;
            min-width: 0;
            padding-left: 13px;
        }

        .search-icon {
            color: var(--blue-light);
            font-size: 20px;
        }

        .search-form input {
            width: 100%;
            min-width: 0;
            padding: 13px 0;
            border: 0;
            outline: 0;
            background: transparent;
            color: white;
            font-size: 13px;
        }

        .search-form input::placeholder {
            color: #747f93;
        }

        .search-form button {
            padding: 13px 22px;
            border: 0;
            border-radius: 8px;
            background: var(--blue);
            color: white;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: background .2s, transform .2s;
        }

        .search-form button:hover {
            background: #2364e9;
            transform: translateY(-1px);
        }

        .hero-note {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
            color: #778297;
            font-size: 11px;
        }

        .hero-note span {
            color: var(--green);
        }

        /* CATALOG */

        .catalog {
            padding: 26px 0 80px;
        }

        .section-line {
            height: 1px;
            margin-bottom: 32px;
            background: linear-gradient(90deg, var(--blue), var(--border) 35%, transparent);
            opacity: .7;
        }

        .catalog-heading {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .section-kicker {
            margin-bottom: 9px;
            color: var(--blue-light);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .catalog-heading h2 {
            margin: 0;
            font-size: 25px;
            letter-spacing: -.8px;
        }

        .catalog-heading p {
            margin: 10px 0 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .result-count {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: var(--surface);
            color: var(--muted);
            font-size: 11px;
            white-space: nowrap;
        }

        .result-count strong {
            color: var(--blue-light);
            font-size: 17px;
        }

        /* PRODUCT CARDS */

        .parts-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .part-card {
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: linear-gradient(155deg, #111722, #0c1018 75%);
            transition: transform .25s, border-color .25s, box-shadow .25s;
        }

        .part-card:hover {
            transform: translateY(-5px);
            border-color: #365d9b;
            box-shadow: 0 18px 45px rgba(0, 0, 0, .25);
        }

        .part-image {
            position: relative;
            display: grid;
            place-items: center;
            height: 245px;
            overflow: hidden;
            border-bottom: 1px solid rgba(255, 255, 255, .035);
            background:
                radial-gradient(ellipse at 50% 55%, rgba(43, 91, 170, .18), transparent 68%),
                linear-gradient(145deg, #151d2b, #0d1119);
        }

        .part-image::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 65%, rgba(7, 9, 14, .28));
            pointer-events: none;
        }

        .part-image img {
            width: 100%;
            height: 100%;
            padding: 18px;
            object-fit: contain;
            transition: transform .35s;
        }

        .part-card:hover .part-image img {
            transform: scale(1.055);
        }

        .part-badge {
            position: absolute;
            z-index: 2;
            top: 13px;
            left: 13px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 10px;
            border: 1px solid rgba(101, 219, 160, .22);
            border-radius: 6px;
            background: rgba(7, 18, 17, .9);
            color: var(--green);
            font-size: 10px;
            font-weight: 800;
        }

        .part-badge::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            box-shadow: 0 0 10px currentColor;
        }

        .part-badge.order {
            border-color: rgba(130, 172, 255, .25);
            background: rgba(10, 20, 40, .92);
            color: var(--blue-light);
        }

        .image-label {
            position: absolute;
            right: 13px;
            bottom: 12px;
            z-index: 2;
            color: rgba(210, 222, 244, .35);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .part-content {
            display: flex;
            flex: 1;
            flex-direction: column;
            padding: 20px;
        }

        .part-brand {
            margin-bottom: 9px;
            color: var(--blue-light);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            overflow-wrap: anywhere;
        }

        .part-title {
            margin: 0 0 11px;
            font-size: 17px;
            line-height: 1.55;
            overflow-wrap: anywhere;
        }

        .compatible {
            min-height: 38px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.8;
        }

        .part-bottom {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 12px;
            margin-top: auto;
            padding-top: 18px;
            border-top: 1px solid var(--border);
        }

        .price-label {
            margin-bottom: 5px;
            color: #7d899d;
            font-size: 10px;
        }

        .price {
            font-size: 23px;
            font-weight: 900;
            letter-spacing: -.6px;
        }

        .stock {
            margin-top: 8px;
            color: var(--green);
            font-size: 10px;
        }

        .stock.unavailable {
            color: #a7b6d1;
        }

        .part-code {
            margin-top: 13px;
            color: #657086;
            font-size: 9px;
            letter-spacing: 1px;
        }

        /* EMPTY STATE */

        .empty {
            grid-column: 1 / -1;
            padding: 65px 20px;
            border: 1px dashed #30394a;
            border-radius: 14px;
            background: rgba(14, 18, 27, .6);
            text-align: center;
        }

        .empty-icon {
            display: grid;
            place-items: center;
            width: 58px;
            height: 58px;
            margin: 0 auto 20px;
            border: 1px solid #2c4268;
            border-radius: 16px;
            background: #111c30;
            color: var(--blue-light);
            font-size: 25px;
        }

        .empty h3 {
            margin: 0 0 12px;
            font-size: 20px;
        }

        .empty p {
            margin: 0 auto 20px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .empty a {
            display: inline-block;
            padding: 12px 17px;
            border-radius: 8px;
            background: var(--blue);
            color: white;
            font-size: 12px;
            font-weight: 700;
        }

        /* PAGINATION */

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 7px;
            margin-top: 38px;
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            min-width: 40px;
            min-height: 40px;
            padding: 9px 13px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            color: #bac4d6;
            font-size: 12px;
            transition: border-color .2s, background .2s;
        }

        .pagination a:hover {
            border-color: var(--blue);
            color: white;
        }

        .pagination .active {
            border-color: var(--blue);
            background: var(--blue);
            color: white;
        }

        .pagination .disabled {
            opacity: .4;
        }

        /* FOOTER */

        .footer {
            border-top: 1px solid var(--border);
            background: #080a10;
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding-top: 26px;
            padding-bottom: 26px;
        }

        .footer .logo {
            font-size: 20px;
        }

        .footer .logo-mark {
            width: 32px;
            height: 32px;
            font-size: 15px;
        }

        .footer-copy {
            color: #778297;
            font-size: 11px;
            line-height: 1.8;
        }

        /* RESPONSIVE */

        @media (max-width: 900px) {
            .parts-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .nav-links { gap: 17px; }
            .part-image { height: 225px; }
        }

        @media (max-width: 600px) {
            .container {
                width: calc(100% - 28px);
            }

            .nav {
                min-height: 66px;
                gap: 12px;
            }

            .logo {
                gap: 7px;
                font-size: 20px;
            }

            .logo-mark {
                width: 32px;
                height: 32px;
                border-radius: 8px;
            }

            .nav-links {
                gap: 12px;
                font-size: 11px;
            }

            .nav-links a.current::after { bottom: -25px; }

            .hero { padding: 43px 0 32px; }

            h1 {
                font-size: clamp(35px, 9vw, 48px);
                letter-spacing: -1.8px;
            }

            .hero-description {
                font-size: 12px;
            }

            .search-form {
                flex-direction: column;
                gap: 5px;
            }

            .search-input-wrap {
                min-height: 44px;
            }

            .search-form button { min-height: 45px; }

            .catalog { padding-top: 22px; }

            .catalog-heading {
                align-items: start;
                flex-direction: column;
                gap: 16px;
            }

            .catalog-heading h2 { font-size: 22px; }

            .parts-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .part-image { height: 250px; }

            .part-content { padding: 18px; }

            .footer-inner {
                align-items: start;
                flex-direction: column;
            }
        }

        @media (max-width: 360px) {
            .nav { flex-wrap: wrap; padding: 12px 0; }
            .nav-links { width: 100%; justify-content: space-between; }
            .nav-links a.current::after { bottom: -7px; }
        }
    </style>
</head>

<body>
    <header class="topbar">
        <div class="container nav">
            <a href="{{ route('home') }}" class="logo" aria-label="MotoHub მთავარი გვერდი">
                <span class="logo-mark">M</span>
                <span style="color: var(--text)">MOTO<span>HUB</span></span>
            </a>

            <nav class="nav-links" aria-label="მთავარი ნავიგაცია">
                <a href="{{ route('home') }}">მოტოციკლები</a>
                <a href="{{ route('rentals.index') }}">გაქირავება</a>
                <a href="{{ route('parts.index') }}" class="current">ნაწილები</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="container hero">
            <div class="eyebrow">MotoHub / Parts Department</div>

            <h1>
                შენი მოტო.<br>
                <span>სწორი ნაწილები.</span>
            </h1>

            <p class="hero-description">
                იპოვე საჭირო სათადარიგო ნაწილი ერთ სივრცეში.
                შეამოწმე ფასი, ბრენდი და თავსებადი მოდელი —
                მოამზადე შენი მოტოციკლი შემდეგი გზისთვის.
            </p>

            <form action="{{ route('parts.index') }}" method="GET" class="search-form">
                <div class="search-input-wrap">
                    <span class="search-icon" aria-hidden="true">⌕</span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $search }}"
                        placeholder="მაგ. სამუხრუჭე ხუნდები, ფილტრი, Yamaha..."
                        aria-label="ნაწილის ძებნა"
                    >
                </div>
                <button type="submit">ნაწილის ძებნა ↗</button>
            </form>

            <div class="hero-note">
                <span>●</span>
                მოძებნე კატალოგში და შეამოწმე ნაწილის ხელმისაწვდომობა
            </div>
        </section>

        <section class="container catalog">
            <div class="section-line"></div>

            <div class="catalog-heading">
                <div>
                    <div class="section-kicker">THE PARTS CATALOG</div>
                    <h2>სათადარიგო ნაწილები</h2>
                    <p>ყველაფერი, რაც შენს მოტოციკლს სჭირდება.</p>
                </div>

                <div class="result-count">
                    შედეგები
                    <strong>{{ $parts->total() }}</strong>
                </div>
            </div>

            <div class="parts-grid">
                @forelse ($parts as $part)
                    <article class="part-card">
                        <div class="part-image">
                            @if ($part->image_path)
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($part->image_path) }}"
                                    alt="{{ $part->title }}"
                                    loading="lazy"
                                >
                            @else
                                <img
                                    src="https://placehold.co/600x400/101722/82acff?text=MotoHub+Parts"
                                    alt="MotoHub სათადარიგო ნაწილი"
                                    loading="lazy"
                                >
                            @endif

                            <span class="part-badge {{ $part->stock > 0 ? '' : 'order' }}">
                                {{ $part->stock > 0 ? 'მარაგშია' : 'შეკვეთით' }}
                            </span>

                            <span class="image-label">MOTOHUB / PARTS</span>
                        </div>

                        <div class="part-content">
                            <div class="part-brand">
                                {{ $part->brand ?: 'MotoHub Parts' }}
                            </div>

                            <h3 class="part-title">{{ $part->title }}</h3>

                            <div class="compatible">
                                @if ($part->compatible_motorcycle)
                                    თავსებადი მოდელი: {{ $part->compatible_motorcycle }}
                                @else
                                    თავსებადობის დასაზუსტებლად დაგვიკავშირდი.
                                @endif
                            </div>

                            <div class="part-bottom">
                                <div>
                                    <div class="price-label">ღირებულება</div>

                                    <div class="price">
                                        ₾{{ number_format((float) $part->price, 2) }}
                                    </div>

                                    <div class="stock {{ $part->stock > 0 ? '' : 'unavailable' }}">
                                        @if ($part->stock > 0)
                                            მარაგში: {{ $part->stock }} ცალი
                                        @else
                                            ხელმისაწვდომობა დასაზუსტებელია
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="part-code">MOTOHUB · ORIGINAL CATALOG</div>
                        </div>
                    </article>
                @empty
                    <div class="empty">
                        <div class="empty-icon">⌕</div>

                        <h3>
                            {{ $search !== '' ? 'შესაბამისი ნაწილი ვერ მოიძებნა' : 'კატალოგი ჯერ ცარიელია' }}
                        </h3>

                        <p>
                            {{ $search !== '' ? 'სცადე სხვა საძიებო სიტყვა ან ნახე კატალოგის ყველა ნაწილი.' : 'ახალი ნაწილები კატალოგში დამატების შემდეგ გამოჩნდება.' }}
                        </p>

                        @if ($search !== '')
                            <a href="{{ route('parts.index') }}">ყველა ნაწილის ნახვა ↗</a>
                        @endif
                    </div>
                @endforelse
            </div>

            @if ($parts->hasPages())
                <div class="pagination">
                    @if ($parts->onFirstPage())
                        <span class="disabled">← წინა</span>
                    @else
                        <a href="{{ $parts->previousPageUrl() }}">← წინა</a>
                    @endif

                    @foreach ($parts->getUrlRange(1, $parts->lastPage()) as $page => $url)
                        @if ($page === $parts->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($parts->hasMorePages())
                        <a href="{{ $parts->nextPageUrl() }}">შემდეგი →</a>
                    @else
                        <span class="disabled">შემდეგი →</span>
                    @endif
                </div>
            @endif
        </section>
    </main>

    <footer class="footer">
        <div class="container footer-inner">
            <a href="{{ route('home') }}" class="logo">
                <span class="logo-mark">M</span>
                <span style="color: var(--text)">MOTO<span>HUB</span></span>
            </a>

            <div class="footer-copy">
                © {{ date('Y') }} MotoHub — მოტოციკლების სამყარო.<br>
                შექმნილია მოტო ენთუზიასტებისთვის.
            </div>
        </div>
    </footer>
</body>
</html>
