<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $condition === 'new' ? 'ახალი ნაწილები' : 'მეორადი ნაწილები' }} | MotoHub</title>
    <meta name="description" content="მოტოციკლის ახალი და მეორადი ნაწილების განცხადებები MotoHub-ზე.">
    <style>
        :root {
            --bg: #080c14;
            --panel: #101827;
            --panel-hover: #152238;
            --blue: #1677ff;
            --blue-light: #48a5ff;
            --text: #f4f7ff;
            --muted: #92a1b9;
            --border: #25334b;
            --green: #48d6a0;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
        }

        a { color: inherit; text-decoration: none; }

        button, input, select { font: inherit; }

        .container {
            width: min(1380px, 94%);
            margin: 0 auto;
        }

        .topbar {
            border-bottom: 1px solid var(--border);
            background: #0b111d;
        }

        .nav {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .logo {
            font-size: 27px;
            font-weight: 950;
            letter-spacing: -1.5px;
        }

        .logo span { color: var(--blue-light); }

        .navlinks {
            display: flex;
            align-items: center;
            gap: 24px;
            color: var(--muted);
            font-size: 14px;
        }

        .navlinks a:hover { color: white; }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--blue);
            border-radius: 5px;
            padding: 12px 16px;
            color: white;
            background: var(--blue);
            font-weight: 750;
            cursor: pointer;
            transition: .2s;
        }

        .btn:hover {
            background: #0862df;
            border-color: #0862df;
        }

        .btn-secondary {
            background: transparent;
            border-color: var(--border);
        }

        .btn-secondary:hover {
            background: var(--panel-hover);
            border-color: var(--blue);
        }

        .hero {
            padding: 38px 0 30px;
            border-bottom: 1px solid var(--border);
            background:
                radial-gradient(ellipse at 85% 0%, rgba(22,119,255,.17), transparent 44%),
                linear-gradient(110deg, #080c14, #0b1423);
        }

        .eyebrow {
            color: var(--blue-light);
            font-size: 11px;
            font-weight: 850;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        h1 {
            margin: 12px 0;
            font-size: clamp(32px, 4.5vw, 54px);
            line-height: 1.08;
            letter-spacing: -2px;
        }

        .hero p {
            max-width: 640px;
            color: var(--muted);
            line-height: 1.7;
            font-size: 15px;
        }

        .tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 24px;
        }

        .tab {
            padding: 11px 17px;
            border: 1px solid var(--border);
            border-radius: 4px;
            background: #0e1726;
            color: var(--muted);
            font-size: 14px;
            font-weight: 700;
        }

        .tab.active {
            color: white;
            background: #102c51;
            border-color: var(--blue);
        }

        .searchbar {
            display: flex;
            gap: 10px;
            margin: 24px 0;
        }

        .searchbar input {
            min-width: 0;
            flex: 1;
            padding: 15px 17px;
            color: white;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 5px;
            outline: none;
        }

        input:focus, select:focus {
            border-color: var(--blue) !important;
        }

        .layout {
            display: grid;
            grid-template-columns: 265px minmax(0, 1fr);
            gap: 25px;
            align-items: start;
            padding-bottom: 65px;
        }

        .filters {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 7px;
            overflow: hidden;
        }

        .filter-head {
            padding: 19px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filter-head strong { font-size: 16px; }

        .clear {
            color: var(--blue-light);
            font-size: 12px;
        }

        .filter-body { padding: 18px; }

        .field { margin-bottom: 19px; }

        .field:last-child { margin-bottom: 0; }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 750;
            color: #dce6f8;
        }

        .field input, .field select {
            width: 100%;
            min-height: 43px;
            padding: 10px 11px;
            color: white;
            background: #0a111e;
            border: 1px solid var(--border);
            border-radius: 4px;
            outline: none;
        }

        .field select option { background: #101827; }

        .price-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .filter-actions {
            display: grid;
            gap: 9px;
            margin-top: 22px;
        }

        .results-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin: 0 0 17px;
        }

        .results-head h2 {
            margin: 0;
            font-size: 21px;
        }

        .count {
            color: var(--muted);
            font-size: 13px;
            white-space: nowrap;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .card {
            min-width: 0;
            overflow: hidden;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 7px;
            transition: transform .18s, border-color .18s;
        }

        .card:hover {
            transform: translateY(-3px);
            border-color: var(--blue);
        }

        .photo {
            position: relative;
            height: 190px;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(ellipse at center, #1a2a42, #111a29 65%);
            overflow: hidden;
        }

        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-photo {
            color: #637792;
            font-size: 12px;
            font-weight: 850;
            letter-spacing: 2px;
        }

        .condition {
            position: absolute;
            top: 11px;
            left: 11px;
            padding: 6px 8px;
            background: rgba(8, 12, 20, .91);
            border: 1px solid #3a5475;
            border-radius: 3px;
            color: #b8d9ff;
            font-size: 10px;
            font-weight: 800;
        }

        .card-body { padding: 15px; }

        .category {
            color: var(--blue-light);
            font-size: 11px;
            font-weight: 750;
        }

        .card h3 {
            margin: 9px 0;
            font-size: 15px;
            line-height: 1.45;
            overflow-wrap: anywhere;
        }

        .compatibility {
            min-height: 34px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.55;
            overflow-wrap: anywhere;
        }

        .card-bottom {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 8px;
            padding-top: 15px;
            margin-top: 14px;
            border-top: 1px solid var(--border);
        }

        .price {
            font-size: 21px;
            font-weight: 900;
            letter-spacing: -.5px;
            overflow-wrap: anywhere;
        }

        .currency {
            color: var(--blue-light);
            font-size: 12px;
        }

        .part-number {
            color: var(--muted);
            font-size: 10px;
            text-align: right;
        }

        .empty {
            grid-column: 1 / -1;
            padding: 55px 20px;
            text-align: center;
            background: var(--panel);
            border: 1px dashed var(--border);
            border-radius: 7px;
        }

        .empty h3 { font-size: 21px; }

        .empty p {
            color: var(--muted);
            line-height: 1.7;
            font-size: 14px;
        }

        .pagination { margin-top: 28px; }

        .pagination nav > div:first-child {
            color: var(--muted);
            margin-bottom: 12px;
            font-size: 13px;
        }

        .pagination nav > div:last-child > span,
        .pagination nav > div:last-child > a {
            display: inline-flex;
            gap: 5px;
        }

        .pagination a, .pagination span span {
            display: inline-block;
            padding: 9px 12px;
            margin: 2px;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 13px;
        }

        .notice {
            margin-bottom: 20px;
            padding: 13px 16px;
            background: #102a24;
            border: 1px solid #225342;
            color: #9ef0ca;
            border-radius: 5px;
        }

        footer {
            padding: 25px 0;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 12px;
        }

        @media (max-width: 1050px) {
            .layout { grid-template-columns: 230px minmax(0, 1fr); gap: 17px; }
            .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .photo { height: 200px; }
            .navlinks { gap: 14px; }
        }

        @media (max-width: 700px) {
            .nav { padding: 16px 0; align-items: flex-start; }
            .navlinks a:not(.btn) { display: none; }
            .navlinks { gap: 8px; }
            .navlinks .btn { padding: 10px; font-size: 12px; }
            .hero { padding-top: 30px; }
            .layout { grid-template-columns: 1fr; }
            .filters { order: 1; }
            .results { order: 2; }
            .filter-body { padding: 15px; }
            .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
            .photo { height: 145px; }
            .card-body { padding: 11px; }
            .card h3 { font-size: 13px; }
            .price { font-size: 18px; }
            .searchbar { margin: 18px 0; }
        }

        @media (max-width: 390px) {
            .grid { grid-template-columns: 1fr; }
            .photo { height: 210px; }
        }
    </style>
</head>
<body>

<header class="topbar">
    <div class="container nav">
        <a href="{{ url('/') }}" class="logo">MOTO<span>HUB</span></a>

        <nav class="navlinks">
            <a href="{{ route('home') }}">მთავარი</a>
            <a href="{{ route('parts.index') }}">ნაწილების კატალოგი</a><a href="{{ route('motorcycles.index') }}">მოტოციკლები</a><a href="{{ route('rentals.index') }}">გაქირავება</a><a href="{{ route('service-centers.index') }}">სახელოსნოები</a>
            <a href="{{ route('account.part-listings.index') }}">ჩემი განცხადებები</a>
            <a class="btn" href="{{ route('account.part-listings.create') }}">+ განცხადება</a>
        </nav>
    </div>
</header>

<section class="hero">
    <div class="container">
        <div class="eyebrow">MotoHub / Marketplace</div>

        <h1>
            {{ $condition === 'new' ? 'ახალი ნაწილები.' : 'მეორადი ნაწილები.' }}<br>
            იპოვე შენი დეტალი.
        </h1>

        <p>
            მოტოციკლის ნაწილების განცხადებები ერთ სივრცეში.
            მოძებნე საჭირო დეტალი ბრენდის, მოდელისა და ფასის მიხედვით.
        </p>

        <div class="tabs">
            <a class="tab {{ $condition === 'new' ? 'active' : '' }}"
               href="{{ route('part-listings.new') }}">ახალი ნაწილები</a>

            <a class="tab {{ $condition === 'used' ? 'active' : '' }}"
               href="{{ route('part-listings.used') }}">მეორადი ნაწილები</a>
        </div>
    </div>
</section>

<div class="container">
    <form class="searchbar" method="GET"
          action="{{ $condition === 'new' ? route('part-listings.new') : route('part-listings.used') }}">
        <input type="search" name="q" value="{{ request('q') }}"
               placeholder="მაგ. Yamaha R1, სამუხრუჭე დისკი, აკრაპოვიჩი...">
        <button class="btn" type="submit">ძიება</button>
    </form>

    @if (session('success'))
        <div class="notice">{{ session('success') }}</div>
    @endif

    <div class="layout">
        <aside class="filters">
            <div class="filter-head">
                <strong>ფილტრები</strong>
                <a class="clear"
                   href="{{ $condition === 'new' ? route('part-listings.new') : route('part-listings.used') }}">
                    გასუფთავება
                </a>
            </div>

            <form class="filter-body" method="GET"
                  action="{{ $condition === 'new' ? route('part-listings.new') : route('part-listings.used') }}">

                <div class="field">
                    <label for="brand">ბრენდი</label>
                    <select id="brand" name="brand">
                        <option value="">ყველა ბრენდი</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand }}"
                                @selected(request('brand') === $brand)>
                                {{ $brand }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="category">კატეგორია</label>
                    <select id="category" name="category">
                        <option value="">ყველა კატეგორია</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}"
                                @selected(request('category') === $category)>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="compatible_motorcycle">მოტოციკლის მოდელი</label>
                    <input id="compatible_motorcycle" name="compatible_motorcycle"
                           value="{{ request('compatible_motorcycle') }}"
                           placeholder="მაგ. Yamaha R1">
                </div>

                <div class="field">
                    <label>ფასის დიაპაზონი (₾)</label>
                    <div class="price-row">
                        <input type="number" name="min_price" min="0" step="0.01"
                               value="{{ request('min_price') }}" placeholder="დან">
                        <input type="number" name="max_price" min="0" step="0.01"
                               value="{{ request('max_price') }}" placeholder="მდე">
                    </div>
                </div>

                <input type="hidden" name="q" value="{{ request('q') }}">

                <div class="filter-actions">
                    <button type="submit" class="btn">ფილტრების გამოყენება</button>
                    <a class="btn btn-secondary"
                       href="{{ $condition === 'new' ? route('part-listings.new') : route('part-listings.used') }}">
                        გასუფთავება
                    </a>
                </div>
            </form>
        </aside>

        <section class="results">
            <div class="results-head">
                <h2>ნაწილების განცხადებები</h2>
                <span class="count">{{ $listings->total() }} შედეგი</span>
            </div>

            <div class="grid">
                @forelse ($listings as $listing)
                    <article class="card">
                        <div class="photo">
                            @if ($listing->image_path)
                                <img
                                    src="{{ asset('storage/' . $listing->image_path) }}"
                                    alt="{{ $listing->title }}"
                                    loading="lazy">
                            @else
                                <span class="no-photo">MOTOHUB / PARTS</span>
                            @endif

                            <span class="condition">
                                {{ $condition === 'new' ? 'ახალი' : 'მეორადი' }}
                            </span>
                        </div>

                        <div class="card-body">
                            <div class="category">
                                {{ $listing->category ?: ($listing->brand ?: 'მოტონაწილი') }}
                            </div>

                            <h3>{{ $listing->title }}</h3>

                            <div class="compatibility">
                                @if ($listing->brand)
                                    ბრენდი: {{ $listing->brand }}<br>
                                @endif

                                @if ($listing->compatible_motorcycle)
                                    მოდელი: {{ $listing->compatible_motorcycle }}
                                @else
                                    თავსებადი მოდელი მითითებული არ არის
                                @endif
                            </div>

                            <div class="card-bottom">
                                <div>
                                    <div class="price">
                                        {{ number_format((float) $listing->price, 2, '.', ' ') }}
                                        <span class="currency">₾</span>
                                    </div>
                                </div>

                                @if ($listing->part_number)
                                    <div class="part-number">
                                        კოდი<br>{{ $listing->part_number }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="empty">
                        <h3>განცხადებები ვერ მოიძებნა</h3>
                        <p>
                            სცადე სხვა ფილტრები ან განათავსე პირველი განცხადება
                            ამ კატეგორიაში.
                        </p>
                        <a class="btn" href="{{ route('account.part-listings.create') }}">
                            + განცხადების დამატება
                        </a>
                    </div>
                @endforelse
            </div>

            <div class="pagination">
                {{ $listings->links() }}
            </div>
        </section>
    </div>
</div>

<footer>
    <div class="container">
        © {{ date('Y') }} MotoHub · მოტოციკლებისა და ნაწილების ბაზარი
    </div>
</footer>

</body>
</html>
