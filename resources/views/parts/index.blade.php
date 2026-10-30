
<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>მოტოციკლის ნაწილების ბაზარი | MotoHub</title>
    <meta name="description" content="მოტოციკლის ნაწილები ერთ სივრცეში — MotoHub-ის მაღაზია და მომხმარებლების განცხადებები.">

    <style>
        :root {
            color-scheme: dark;
            --bg: #070a12;
            --panel: #0d1422;
            --panel2: #121d30;
            --blue: #1677ff;
            --blue2: #42a5ff;
            --text: #f4f8ff;
            --muted: #98a8c2;
            --border: #243653;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: radial-gradient(ellipse at 75% 0%, #12305a55, transparent 38%), var(--bg);
            color: var(--text);
            font-family: Arial, "Noto Sans Georgian", sans-serif;
        }

        a { color: inherit; text-decoration: none; }
        button, input, select { font: inherit; }
        .wrap { width: min(1280px, calc(100% - 36px)); margin: auto; }

        header {
            position: sticky;
            top: 0;
            z-index: 5;
            background: #080d16ed;
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(16px);
        }

        .nav {
            min-height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .logo { font-size: 23px; font-weight: 950; letter-spacing: -1.3px; }
        .logo span { color: var(--blue2); }

        .links {
            display: flex;
            gap: 22px;
            color: var(--muted);
            font-size: 13px;
        }

        .links a:hover, .links a.active { color: white; }

        .hero { padding: 54px 0 30px; }
        .eyebrow {
            color: var(--blue2);
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 3px;
        }

        .hero h1 {
            font-size: clamp(34px, 5vw, 60px);
            line-height: 1.08;
            letter-spacing: -2px;
            margin: 14px 0;
        }

        .hero h1 span { color: var(--blue2); }

        .hero p {
            max-width: 700px;
            color: var(--muted);
            line-height: 1.8;
            font-size: 14px;
        }

        .search {
            display: flex;
            gap: 10px;
            max-width: 850px;
            margin-top: 25px;
            padding: 7px;
            border: 1px solid #304565;
            border-radius: 10px;
            background: var(--panel);
        }

        .search input {
            flex: 1;
            min-width: 0;
            background: transparent;
            border: 0;
            outline: 0;
            color: white;
            padding: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--blue);
            background: var(--blue);
            color: white;
            border-radius: 7px;
            padding: 12px 17px;
            font-weight: 800;
            cursor: pointer;
        }

        .btn:hover { background: #0863dc; }
        .btn.ghost { background: transparent; border-color: var(--border); }

        .layout {
            display: grid;
            grid-template-columns: 270px minmax(0, 1fr);
            gap: 22px;
            padding: 15px 0 70px;
        }

        .filters, .card, .empty {
            background: linear-gradient(145deg, #111b2b, #0b111c);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .filters {
            height: max-content;
            padding: 19px;
            position: sticky;
            top: 90px;
        }

        .filters h2 { font-size: 17px; margin: 0 0 17px; }
        .field { margin: 0 0 14px; }

        .field label {
            display: block;
            font-size: 11px;
            color: #d8e5f8;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .field input, .field select {
            width: 100%;
            padding: 11px;
            background: #080e19;
            border: 1px solid #2a3c59;
            border-radius: 6px;
            color: white;
            min-height: 42px;
        }

        .row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .filters .actions { display: grid; gap: 8px; margin-top: 18px; }

        .result-head {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 16px;
        }

        .result-head h2 { margin: 0; font-size: 22px; }
        .muted { color: var(--muted); font-size: 12px; }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 15px;
        }

        .card {
            overflow: hidden;
            transition: .2s;
        }

        .card:hover { border-color: #397bd1; transform: translateY(-3px); }

        .photo {
            height: 180px;
            background: radial-gradient(ellipse, #1b3150, #0b111b);
            position: relative;
            display: grid;
            place-items: center;
            overflow: hidden;
        }

        .photo img { width: 100%; height: 100%; object-fit: cover; }
        .no-photo { color: #7386a4; font-size: 11px; letter-spacing: 2px; }

        .tag {
            position: absolute;
            left: 10px;
            top: 10px;
            background: #081323ef;
            border: 1px solid #3b5e8b;
            border-radius: 5px;
            padding: 6px 8px;
            font-size: 10px;
            color: #b9d7ff;
        }

        .body { padding: 14px; }
        .category { color: var(--blue2); font-size: 10px; font-weight: 800; }

        .body h3 { font-size: 15px; line-height: 1.5; margin: 8px 0; }
        .spec { min-height: 40px; color: var(--muted); font-size: 11px; line-height: 1.7; }

        .bottom {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 8px;
            border-top: 1px solid #ffffff12;
            margin-top: 12px;
            padding-top: 12px;
        }

        .price { font-size: 21px; font-weight: 900; }
        .seller { color: var(--muted); font-size: 10px; margin-top: 7px; }

        .details {
            display: inline-flex;
            margin-top: 13px;
            padding: 9px 11px;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 11px;
            color: #dceaff;
        }

        .details:hover { border-color: var(--blue); }

        .empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 50px 20px;
            color: var(--muted);
        }

        .pagination {
            display: flex;
            gap: 7px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 25px;
        }

        .pagination a, .pagination span {
            padding: 9px 12px;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 12px;
        }

        .pagination .active { background: var(--blue); border-color: var(--blue); }

        footer {
            border-top: 1px solid var(--border);
            padding: 24px 0;
            color: var(--muted);
            font-size: 11px;
        }

        @media(max-width: 1000px) {
            .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media(max-width: 720px) {
            .nav { padding: 14px 0; align-items: flex-start; }
            .links { gap: 12px; font-size: 11px; flex-wrap: wrap; justify-content: flex-end; }
            .layout { grid-template-columns: 1fr; }
            .filters { position: static; }
            .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .photo { height: 145px; }
            .body { padding: 11px; }
            .hero { padding-top: 35px; }
        }

        @media(max-width: 430px) {
            .grid { grid-template-columns: 1fr; }
            .photo { height: 210px; }
            .search { flex-direction: column; }
            .search .btn { width: 100%; }
            .result-head { align-items: start; flex-direction: column; }
        }
    </style>
</head>

<body>

<header>
    <div class="wrap nav">
        <a href="{{ route('home') }}" class="logo">MOTO<span>HUB</span></a>

        <nav class="links">
            <a href="{{ route('home') }}">მოტოციკლები</a>
            <a href="{{ route('rentals.index') }}">გაქირავება</a>
            <a href="{{ route('parts.index') }}" class="active">ნაწილები</a>
        </nav>
    </div>
</header>

<main class="wrap">

    <section class="hero">
        <div class="eyebrow">MOTOHUB / PARTS DEPARTMENT</div>

        <h1>
            შენი მოტო.<br>
            <span>საჭირო ნაწილები.</span>
        </h1>

        <p>
            იპოვე საჭირო სათადარიგო ნაწილი ერთ სივრცეში.
            შეადარე ფასები, ბრენდები და თავსებადობა MotoHub-ის მაღაზიასა
            და მომხმარებლების განცხადებებში.
        </p>

        <form action="{{ route('parts.index') }}" method="GET" class="search">
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="მაგ. სამუხრუჭე ხუნდები, ფილტრი, Yamaha..."
            >

            @foreach ($filters as $key => $value)
                @if ($key !== 'q' && $value !== '' && $value !== null)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach

            <button class="btn" type="submit">ძიება →</button>
        </form>
    </section>

    <section class="layout">

        <aside class="filters">
            <h2>ფილტრები</h2>

            <form action="{{ route('parts.index') }}" method="GET">

                <div class="field">
                    <label>ნაწილის ბრენდი</label>
                    <select name="brand">
                        <option value="">ყველა ბრენდი</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand }}" @selected(($filters['brand'] ?? '') == $brand)>
                                {{ $brand }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>კატეგორია</label>
                    <select name="category">
                        <option value="">ყველა კატეგორია</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected(($filters['category'] ?? '') == $category)>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>ნაწილის ტიპი</label>
                    <select name="part_type">
                        <option value="">ყველა ტიპი</option>
                        <option value="oem" @selected(($filters['part_type'] ?? '') === 'oem')>OEM / ორიგინალი</option>
                        <option value="aftermarket" @selected(($filters['part_type'] ?? '') === 'aftermarket')>Aftermarket</option>
                        <option value="used" @selected(($filters['part_type'] ?? '') === 'used')>მეორადი</option>
                    </select>
                </div>

                <div class="field">
                    <label>მოტოციკლის ბრენდი</label>
                    <select name="motorcycle_brand">
                        <option value="">ყველა ბრენდი</option>
                        @foreach ($motorcycleBrands as $motorcycleBrand)
                            <option value="{{ $motorcycleBrand }}" @selected(($filters['motorcycle_brand'] ?? '') == $motorcycleBrand)>
                                {{ $motorcycleBrand }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>მოტოციკლის მოდელი</label>
                    <input
                        type="text"
                        name="motorcycle_model"
                        value="{{ $filters['motorcycle_model'] ?? '' }}"
                        placeholder="მაგ. R1, CBR..."
                    >
                </div>

                <div class="row2">
                    <div class="field">
                        <label>წელი</label>
                        <input
                            type="number"
                            name="motorcycle_year"
                            value="{{ $filters['motorcycle_year'] ?? '' }}"
                            placeholder="2016"
                        >
                    </div>

                    <div class="field">
                        <label>ძრავი (cc)</label>
                        <input
                            type="number"
                            name="engine_cc"
                            value="{{ $filters['engine_cc'] ?? '' }}"
                            placeholder="1000"
                        >
                    </div>
                </div>

                <div class="field">
                    <label>მდგომარეობა</label>
                    <select name="condition">
                        <option value="">ყველა</option>
                        <option value="new" @selected(($filters['condition'] ?? '') === 'new')>ახალი</option>
                        <option value="used" @selected(($filters['condition'] ?? '') === 'used')>მეორადი</option>
                    </select>
                </div>

                <div class="field">
                    <label>წყარო</label>
                    <select name="source">
                        <option value="all" @selected(($filters['source'] ?? 'all') === 'all')>ყველა განცხადება</option>
                        <option value="store" @selected(($filters['source'] ?? '') === 'store')>MotoHub მაღაზია</option>
                        <option value="marketplace" @selected(($filters['source'] ?? '') === 'marketplace')>მომხმარებლები</option>
                    </select>
                </div>

                <div class="field">
                    <label>ფასის დიაპაზონი (₾)</label>
                    <div class="row2">
                        <input
                            type="number"
                            name="min_price"
                            min="0"
                            value="{{ $filters['min_price'] ?? '' }}"
                            placeholder="დან"
                        >
                        <input
                            type="number"
                            name="max_price"
                            min="0"
                            value="{{ $filters['max_price'] ?? '' }}"
                            placeholder="მდე"
                        >
                    </div>
                </div>

                @if ($search !== '')
                    <input type="hidden" name="q" value="{{ $search }}">
                @endif

                <div class="actions">
                    <button class="btn" type="submit">ფილტრაცია</button>
                    <a href="{{ route('parts.index') }}" class="btn ghost">გასუფთავება</a>
                </div>

            </form>
        </aside>

        <div class="catalog">

            <div class="result-head">
                <div>
                    <div class="eyebrow">THE PARTS MARKETPLACE</div>
                    <h2>სათადარიგო ნაწილები</h2>
                    <div class="muted">მაღაზიისა და მომხმარებლების განცხადებები</div>
                </div>

                <div class="muted">
                    ნაპოვნია: <strong>{{ $parts->total() }}</strong>
                </div>
            </div>

            <div class="grid">

                @forelse ($parts as $part)

                    <article class="card">

                        <div class="photo">

                            @if (!empty($part['image_url']))
                                <img
                                    src="{{ $part['image_url'] }}"
                                    alt="{{ $part['title'] }}"
                                    loading="lazy"
                                >
                            @else
                                <div class="no-photo">MOTOHUB / PARTS</div>
                            @endif

                            <span class="tag">
                                {{ ($part['source'] ?? '') === 'store' ? 'MOTOHUB STORE' : 'MARKETPLACE' }}
                            </span>

                        </div>

                        <div class="body">

                            <div class="category">
                                {{ $part['category'] ?: ($part['brand'] ?: 'MOTOHUB PARTS') }}
                            </div>

                            <h3>{{ $part['title'] }}</h3>

                            <div class="spec">
                                @if (!empty($part['motorcycle_brand']) || !empty($part['motorcycle_model']))
                                    თავსებადია:
                                    {{ trim(($part['motorcycle_brand'] ?? '') . ' ' . ($part['motorcycle_model'] ?? '')) }}

                                    @if (!empty($part['motorcycle_year']))
                                        · {{ $part['motorcycle_year'] }}
                                    @endif

                                    @if (!empty($part['engine_cc']))
                                        · {{ $part['engine_cc'] }} cc
                                    @endif
                                @else
                                    {{ $part['compatible_motorcycle'] ?: 'თავსებადობა დასაზუსტებელია' }}
                                @endif
                            </div>

                            <div class="bottom">
                                <div>
                                    <div class="price">
                                        ₾{{ number_format((float) $part['price'], 2) }}
                                    </div>

                                    <div class="seller">
                                        გამყიდველი: {{ $part['seller_name'] ?? 'MotoHub' }}
                                    </div>

                                    @if (!empty($part['seller_phone']))
                                        <div class="seller">
                                            ტელ: {{ $part['seller_phone'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <a
                                class="details"
                                href="{{ route('parts.show', [$part['source'], $part['id']]) }}"
                            >
                                დეტალების ნახვა →
                            </a>

                        </div>
                    </article>

                @empty

                    <div class="empty">
                        <h3>ნაწილი ვერ მოიძებნა</h3>
                        <p>სცადე სხვა საძიებო სიტყვა ან გაასუფთავე ფილტრები.</p>
                        <a href="{{ route('parts.index') }}" class="btn">ყველა ნაწილის ნახვა</a>
                    </div>

                @endforelse

            </div>

            @if ($parts->hasPages())
                <div class="pagination">
                    @if ($parts->onFirstPage())
                        <span>← წინა</span>
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
                        <span>შემდეგი →</span>
                    @endif
                </div>
            @endif

        </div>
    </section>
</main>

<footer>
    <div class="wrap">
        © {{ date('Y') }} MotoHub — მოტოციკლების სამყარო.
    </div>
</footer>

</body>
</html>
