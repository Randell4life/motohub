<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#090b0f">

    <title>მოტოციკლების გაქირავება | MotoHub</title>
    <meta name="description" content="შეარჩიე მოტოციკლი და დაგეგმე შენი შემდეგი თავგადასავალი MotoHub-ის გაქირავების სერვისით.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+Georgian:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #090b0f;
            --surface: #11151b;
            --surface-2: #171d25;
            --accent: #ff812f;
            --accent-light: #ffb17b;
            --text: #f5f7fa;
            --muted: #9ba5b3;
            --border: rgba(255,255,255,.09);
            --container: 1240px;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 24px;
        }

        body {
            min-height: 100vh;
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', 'Noto Sans Georgian', sans-serif;
            line-height: 1.65;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        button,
        input {
            font: inherit;
        }

        img {
            display: block;
            max-width: 100%;
        }

        .container {
            width: min(var(--container), calc(100% - 48px));
            margin-inline: auto;
        }

        /* HEADER */

        .header {
            position: relative;
            z-index: 10;
            border-bottom: 1px solid var(--border);
            background: #0a0c10;
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 28px;
            min-height: 78px;
        }

        .logo {
            flex-shrink: 0;
            font-size: 26px;
            font-weight: 900;
            letter-spacing: -1.5px;
        }

        .logo span {
            color: var(--accent);
        }

        .nav-links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 29px;
            color: #a6afbb;
            font-size: 12px;
            font-weight: 600;
        }

        .nav-links a {
            position: relative;
            padding: 8px 0;
            transition: color .2s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--accent-light);
        }

        .nav-links a.active::after {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            height: 2px;
            background: var(--accent);
            content: "";
        }

        .account {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0 16px;
            border: 1px solid var(--border);
            border-radius: 7px;
            color: #e9edf3;
            font-size: 11px;
            font-weight: 800;
            transition: border-color .2s, background .2s;
        }

        .account:hover {
            border-color: rgba(255,129,47,.5);
            background: rgba(255,129,47,.06);
        }

        /* HERO */

        .hero {
            position: relative;
            isolation: isolate;
            display: flex;
            align-items: center;
            min-height: 520px;
            overflow: hidden;
            border-bottom: 1px solid rgba(255,129,47,.19);
            background:
                radial-gradient(ellipse at 80% 35%, rgba(255,129,47,.12), transparent 40%),
                linear-gradient(125deg, #141820, #0b0e13 70%);
        }

        .hero-photo {
            position: absolute;
            z-index: -2;
            inset: 0 0 0 auto;
            width: 78%;
            height: 100%;
            object-fit: cover;
            object-position: center 48%;
            opacity: .8;
        }

        .hero::before {
            position: absolute;
            z-index: -1;
            inset: 0;
            background:
                linear-gradient(90deg,
                    #090b0f 0%,
                    rgba(9,11,15,.96) 27%,
                    rgba(9,11,15,.76) 49%,
                    rgba(9,11,15,.16) 100%),
                linear-gradient(0deg, rgba(9,11,15,.65), transparent 50%);
            content: "";
        }

        .hero::after {
            position: absolute;
            z-index: -1;
            right: -180px;
            bottom: -290px;
            width: 520px;
            height: 520px;
            border: 1px solid rgba(255,129,47,.17);
            border-radius: 50%;
            box-shadow:
                0 0 0 50px rgba(255,129,47,.025),
                0 0 0 100px rgba(255,129,47,.018);
            content: "";
            pointer-events: none;
        }

        .hero-content {
            width: 100%;
            padding-top: 70px;
            padding-bottom: 54px;
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 11px;
            color: var(--accent-light);
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .eyebrow::before {
            width: 24px;
            height: 3px;
            border-radius: 3px;
            background: var(--accent);
            box-shadow: 0 0 15px rgba(255,129,47,.28);
            content: "";
        }

        .hero h1 {
            max-width: 760px;
            margin-top: 24px;
            color: #fff;
            font-size: clamp(40px, 5.5vw, 72px);
            font-weight: 900;
            line-height: 1.16;
            letter-spacing: -2.5px;
        }

        .hero h1 span {
            display: block;
            color: var(--accent);
        }

        .hero-description {
            max-width: 490px;
            margin-top: 20px;
            color: #b7c0cb;
            font-size: 13px;
            line-height: 2;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
            margin-top: 27px;
        }

        .button-primary,
        .button-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 47px;
            padding: 0 19px;
            border: 1px solid transparent;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 900;
            transition: transform .2s, background .2s, border-color .2s;
        }

        .button-primary {
            background: var(--accent);
            color: #18100a;
        }

        .button-primary:hover {
            transform: translateY(-2px);
            background: #ff9958;
        }

        .button-secondary {
            border-color: rgba(255,255,255,.17);
            background: rgba(255,255,255,.035);
            color: #f5f7fa;
        }

        .button-secondary:hover {
            border-color: rgba(255,129,47,.5);
            background: rgba(255,129,47,.06);
        }

        .hero-bottom {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-top: 32px;
            color: #b8c0cb;
            font-size: 11px;
        }

        .hero-bottom strong {
            color: white;
            font-size: 22px;
            font-weight: 900;
        }

        .hero-dot {
            width: 7px;
            height: 7px;
            flex-shrink: 0;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 12px rgba(255,129,47,.4);
        }

        /* BENEFITS */

        .benefits {
            display: grid;
            grid-template-columns: repeat(3, minmax(0,1fr));
            gap: 13px;
            margin-top: 22px;
        }

        .benefit {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            padding: 19px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: linear-gradient(140deg, #151a21, #101318);
            transition: border-color .2s, transform .2s;
        }

        .benefit:hover {
            transform: translateY(-3px);
            border-color: rgba(255,129,47,.3);
        }

        .benefit-icon {
            display: grid;
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            place-items: center;
            border: 1px solid rgba(255,129,47,.22);
            border-radius: 8px;
            background: rgba(255,129,47,.07);
            color: var(--accent);
            font-size: 17px;
        }

        .benefit h3 {
            margin-bottom: 5px;
            color: #f2f4f7;
            font-size: 11px;
            font-weight: 850;
        }

        .benefit p {
            color: var(--muted);
            font-size: 10px;
            line-height: 1.8;
        }

        /* FLEET */

        .fleet {
            padding: 65px 0 82px;
        }

        .section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
            padding-bottom: 22px;
            border-bottom: 1px solid var(--border);
        }

        .section-title-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-mark {
            width: 4px;
            height: 31px;
            flex-shrink: 0;
            border-radius: 3px;
            background: var(--accent);
        }

        .section-heading h2 {
            color: #f5f7fa;
            font-size: clamp(23px, 3vw, 31px);
            font-weight: 900;
            line-height: 1.35;
            letter-spacing: -.8px;
        }

        .section-heading p {
            margin-top: 7px;
            color: var(--muted);
            font-size: 11px;
        }

        .section-label {
            color: #929ba8;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 2px;
            white-space: nowrap;
        }

        /* MOTORCYCLE CARDS */

        .bike-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0,1fr));
            gap: 19px;
        }

        .bike-card {
            display: flex;
            min-width: 0;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 11px;
            background: linear-gradient(145deg, #171c24, #101319 80%);
            transition: transform .23s, border-color .23s, box-shadow .23s;
        }

        .bike-card:hover {
            transform: translateY(-4px);
            border-color: rgba(255,129,47,.36);
            box-shadow: 0 18px 40px rgba(0,0,0,.23);
        }

        .bike-image-link {
            display: block;
        }

        .bike-image {
            position: relative;
            height: 235px;
            overflow: hidden;
            background: #171c24;
        }

        .bike-image::after {
            position: absolute;
            inset: 40% 0 0;
            background: linear-gradient(0deg, rgba(8,10,14,.58), transparent);
            content: "";
            pointer-events: none;
        }

        .bike-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .45s ease;
        }

        .bike-card:hover .bike-image img {
            transform: scale(1.045);
        }

        .image-label {
            position: absolute;
            z-index: 1;
            top: 13px;
            left: 13px;
            padding: 7px 10px;
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 5px;
            background: rgba(10,12,16,.85);
            color: #fff;
            font-size: 9px;
            font-weight: 850;
            backdrop-filter: blur(8px);
        }

        .image-number {
            position: absolute;
            z-index: 1;
            right: 14px;
            bottom: 10px;
            color: rgba(255,255,255,.86);
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 2px;
        }

        .bike-content {
            display: flex;
            flex: 1;
            flex-direction: column;
            padding: 19px;
        }

        .bike-brand {
            margin-bottom: 7px;
            color: var(--accent-light);
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 1.7px;
            text-transform: uppercase;
        }

        .bike-title {
            color: #f5f7fa;
            font-size: 18px;
            font-weight: 850;
            line-height: 1.55;
            overflow-wrap: anywhere;
        }

        .bike-specs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 14px;
            margin-top: 14px;
            color: #a7b0bc;
            font-size: 10px;
        }

        .bike-specs span {
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .spec-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--accent);
        }

        .bike-divider {
            height: 1px;
            margin: 18px 0;
            background: var(--border);
        }

        .bike-bottom {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            margin-top: auto;
        }

        .price-label {
            display: block;
            margin-bottom: 4px;
            color: var(--muted);
            font-size: 10px;
        }

        .price {
            color: #fff;
            font-size: 25px;
            font-weight: 900;
            line-height: 1.35;
            letter-spacing: -.6px;
        }

        .price small {
            color: #a7b0bc;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0;
        }

        .details-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 41px;
            padding: 0 13px;
            flex-shrink: 0;
            border: 1px solid var(--accent);
            border-radius: 6px;
            background: var(--accent);
            color: #1a110b;
            font-size: 10px;
            font-weight: 900;
            white-space: nowrap;
            transition: background .2s, transform .2s;
        }

        .details-link:hover {
            transform: translateY(-1px);
            background: #ff9958;
        }

        /* EMPTY STATE */

        .empty-state {
            padding: 55px 22px;
            border: 1px dashed #343b45;
            border-radius: 11px;
            background:
                radial-gradient(ellipse at 50% 0%, rgba(255,129,47,.07), transparent 65%),
                var(--surface);
            text-align: center;
        }

        .empty-icon {
            display: grid;
            width: 54px;
            height: 54px;
            margin: 0 auto 16px;
            place-items: center;
            border: 1px solid rgba(255,129,47,.22);
            border-radius: 12px;
            background: rgba(255,129,47,.07);
            color: var(--accent);
            font-size: 23px;
        }

        .empty-state h3 {
            margin-bottom: 9px;
            color: #fff;
            font-size: 20px;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.9;
        }

        /* PAGINATION */

        .pagination {
            margin-top: 30px;
            overflow-x: auto;
        }

        .pagination nav {
            display: flex;
            justify-content: center;
        }

        .pagination svg {
            width: 17px;
            height: 17px;
        }

        .pagination nav > div {
            max-width: 100%;
        }

        .pagination nav > div:first-child:not(:last-child) {
            margin-bottom: 10px;
        }

        .pagination a,
        .pagination span[aria-current],
        .pagination nav > div > span {
            border-radius: 6px;
        }

        /* FOOTER */

        .footer {
            border-top: 1px solid var(--border);
            background: #07090c;
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding-top: 25px;
            padding-bottom: 25px;
        }

        .footer .logo {
            font-size: 21px;
        }

        .footer p {
            color: var(--muted);
            font-size: 10px;
        }

        /* RESPONSIVE */

        @media (max-width: 1000px) {
            .bike-grid {
                grid-template-columns: repeat(2, minmax(0,1fr));
            }

            .nav-links {
                gap: 17px;
            }

            .hero {
                min-height: 480px;
            }

            .hero h1 {
                font-size: clamp(40px, 6vw, 60px);
            }
        }

        @media (max-width: 650px) {
            .container {
                width: calc(100% - 30px);
            }

            .nav {
                min-height: 66px;
                flex-wrap: wrap;
                gap: 10px;
                padding: 11px 0;
            }

            .logo {
                font-size: 23px;
            }

            .account {
                min-height: 37px;
                padding: 0 11px;
                font-size: 10px;
            }

            .nav-links {
                order: 3;
                width: 100%;
                justify-content: flex-start;
                gap: 23px;
                overflow-x: auto;
                font-size: 11px;
            }

            .hero {
                min-height: 505px;
                align-items: flex-end;
            }

            .hero-photo {
                width: 100%;
                height: 100%;
                object-position: 60% center;
                opacity: .68;
            }

            .hero::before {
                background:
                    linear-gradient(0deg,
                        rgba(9,11,15,.99) 0%,
                        rgba(9,11,15,.86) 47%,
                        rgba(9,11,15,.28) 100%);
            }

            .hero-content {
                padding-top: 55px;
                padding-bottom: 31px;
            }

            .hero h1 {
                margin-top: 19px;
                font-size: clamp(35px, 9vw, 47px);
                letter-spacing: -1.5px;
            }

            .hero-description {
                margin-top: 16px;
                font-size: 12px;
                line-height: 1.9;
            }

            .hero-actions {
                gap: 9px;
                margin-top: 22px;
            }

            .button-primary,
            .button-secondary {
                min-height: 43px;
                padding: 0 12px;
                font-size: 10px;
            }

            .hero-bottom {
                margin-top: 22px;
            }

            .benefits {
                grid-template-columns: 1fr;
                gap: 9px;
                margin-top: 13px;
            }

            .benefit {
                align-items: center;
                padding: 14px;
            }

            .fleet {
                padding: 43px 0 53px;
            }

            .section-heading {
                align-items: flex-start;
                gap: 10px;
                margin-bottom: 18px;
                padding-bottom: 17px;
            }

            .section-heading h2 {
                font-size: 22px;
            }

            .section-heading p {
                max-width: 270px;
                font-size: 10px;
            }

            .section-label {
                padding-top: 5px;
                font-size: 8px;
                letter-spacing: 1px;
            }

            .bike-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .bike-image {
                height: 240px;
            }

            .bike-content {
                padding: 17px;
            }

            .bike-title {
                font-size: 18px;
            }

            .price {
                font-size: 23px;
            }

            .details-link {
                min-height: 40px;
                padding: 0 11px;
            }

            .footer-inner {
                align-items: flex-start;
                flex-direction: column;
                gap: 8px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

<header class="header">
    <div class="container">
        <nav class="nav">
            <a href="{{ route('home') }}" class="logo" aria-label="MotoHub მთავარი">
                Moto<span>Hub</span>
            </a>

            <div class="nav-links">
                <a href="{{ route('home') }}">მთავარი</a>

                @if(\Illuminate\Support\Facades\Route::has('motorcycles.index'))
                    <a href="{{ route('motorcycles.index') }}">მოტოციკლები</a>
                @endif

                <a href="{{ url('/rentals') }}" class="active">გაქირავება</a>
            </div>

            @auth
                <a href="{{ route('account.index') }}" class="account">
                    ჩემი ანგარიში
                </a>
            @else
                <a href="{{ route('login') }}" class="account">
                    შესვლა
                </a>
            @endauth
        </nav>
    </div>
</header>

<main>
    <section class="hero" id="top">
        <img
            class="hero-photo"
            src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1900&q=85"
            alt=""
            aria-hidden="true"
        >

        <div class="container">
            <div class="hero-content">
                <div class="eyebrow">MotoHub / Rental Collection</div>

                <h1>
                    შენი გზა.
                    <span>შენი წესები.</span>
                </h1>

                <p class="hero-description">
                    აირჩიე მოტოციკლი, დატოვე ყოველდღიური რუტინა
                    უკან და აღმოაჩინე ახალი მარშრუტები.
                    შენი შემდეგი მოტო-თავგადასავალი აქ იწყება.
                </p>

                <div class="hero-actions">
                    <a href="#motorcycles" class="button-primary">
                        შეარჩიე მოტოციკლი
                        <span aria-hidden="true">↗</span>
                    </a>

                    <a href="#motorcycles" class="button-secondary">
                        მოდელების ნახვა
                    </a>
                </div>

                <div class="hero-bottom">
                    <span class="hero-dot"></span>
                    <strong>{{ $scooters->total() }}</strong>
                    <span>გასაქირავებელი მოტოციკლი</span>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <section class="benefits" aria-label="გაქირავების უპირატესობები">
            <article class="benefit">
                <div class="benefit-icon" aria-hidden="true">↗</div>
                <div>
                    <h3>აირჩიე შენი სტილი</h3>
                    <p>დაათვალიერე ხელმისაწვდომი მოდელები ერთ სივრცეში.</p>
                </div>
            </article>

            <article class="benefit">
                <div class="benefit-icon" aria-hidden="true">₾</div>
                <div>
                    <h3>ფასი წინასწარ</h3>
                    <p>შეადარე დღიური ფასები და შეარჩიე შენთვის სასურველი ვარიანტი.</p>
                </div>
            </article>

            <article class="benefit">
                <div class="benefit-icon" aria-hidden="true">→</div>
                <div>
                    <h3>დეტალები ერთ გვერდზე</h3>
                    <p>გაეცანი მოტოციკლს და გადადი გაქირავების გვერდზე.</p>
                </div>
            </article>
        </section>
    </div>

    <section class="fleet" id="motorcycles">
        <div class="container">
            <div class="section-heading">
                <div>
                    <div class="section-title-wrap">
                        <span class="section-mark"></span>
                        <h2>აირჩიე შენი მოტო</h2>
                    </div>
                    <p>იპოვე მოტოციკლი შენი შემდეგი გასეირნებისთვის.</p>
                </div>

                <div class="section-label">THE COLLECTION</div>
            </div>

            @if($scooters->count())
                <div class="bike-grid">
                    @foreach($scooters as $scooter)
                        <article class="bike-card">
                            <a
                                href="{{ route('rentals.show', $scooter->slug) }}"
                                class="bike-image-link"
                                aria-label="{{ $scooter->title }} — დეტალები"
                            >
                                <div class="bike-image">
                                    <img
                                        src="{{ $scooter->image_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($scooter->image_path) : 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=900&q=80' }}"
                                        alt="{{ $scooter->brand }} {{ $scooter->title }}"
                                        loading="lazy"
                                    >

                                    <span class="image-label">ქირაობა</span>
                                    <span class="image-number">MOTOHUB / RENTAL</span>
                                </div>
                            </a>

                            <div class="bike-content">
                                <div class="bike-brand">
                                    {{ $scooter->brand }}
                                </div>

                                <h3 class="bike-title">
                                    {{ $scooter->title }}
                                </h3>

                                <div class="bike-specs">
                                    @if($scooter->year)
                                        <span>
                                            <i class="spec-dot"></i>
                                            {{ $scooter->year }} წელი
                                        </span>
                                    @endif

                                    @if($scooter->engine_cc)
                                        <span>
                                            <i class="spec-dot"></i>
                                            {{ $scooter->engine_cc }} cc
                                        </span>
                                    @endif

                                    @if($scooter->location)
                                        <span>
                                            <i class="spec-dot"></i>
                                            {{ $scooter->location }}
                                        </span>
                                    @endif
                                </div>

                                <div class="bike-divider"></div>

                                <div class="bike-bottom">
                                    <div>
                                        <span class="price-label">დღიური ღირებულება</span>

                                        <div class="price">
                                            {{ number_format((float) $scooter->daily_rate, 0) }} ₾
                                            <small>/ დღე</small>
                                        </div>
                                    </div>

                                    <a
                                        href="{{ route('rentals.show', $scooter->slug) }}"
                                        class="details-link"
                                    >
                                        დეტალები
                                        <span aria-hidden="true">↗</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if($scooters->hasPages())
                    <div class="pagination">
                        {{ $scooters->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-icon" aria-hidden="true">↗</div>

                    <h3>მოტოციკლები მალე დაემატება</h3>

                    <p>
                        ამ ეტაპზე გასაქირავებელი მოტოციკლები არ არის.
                        მოგვიანებით ისევ შემოგვიერთდი.
                    </p>
                </div>
            @endif
        </div>
    </section>
</main>

<footer class="footer">
    <div class="container footer-inner">
        <a href="{{ route('home') }}" class="logo">
            Moto<span>Hub</span>
        </a>

        <p>© {{ date('Y') }} MotoHub. ყველა უფლება დაცულია.</p>
    </div>
</footer>

</body>
</html>
