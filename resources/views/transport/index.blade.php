<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#070a12">
    <title>MotoTransport | MotoHub</title>

    <style>
        :root {
            --bg: #070a12;
            --panel: #0d1422;
            --panel2: #121d30;
            --blue: #1677ff;
            --light: #42a5ff;
            --text: #f4f8ff;
            --muted: #98a8c2;
            --border: #243653;
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            background:
                radial-gradient(ellipse at 80% 12%, rgba(22,119,255,.13), transparent 32%),
                var(--bg);
            color: var(--text);
            font-family: Arial, "Noto Sans Georgian", sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; text-decoration: none; }

        button, input { font: inherit; }

        .top-line {
            height: 3px;
            background: linear-gradient(90deg, var(--blue), var(--light) 45%, transparent);
        }

        .nav {
            position: relative;
            z-index: 2;
            min-height: 82px;
            padding: 18px max(5%, calc((100% - 1240px) / 2));
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            background: rgba(7,10,18,.92);
            border-bottom: 1px solid var(--border);
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            font-size: 25px;
            font-weight: 950;
            letter-spacing: -1.5px;
            white-space: nowrap;
        }

        .logo span { color: var(--light); }

        .logo-mark {
            width: 8px;
            height: 30px;
            margin-right: 9px;
            background: var(--blue);
            transform: skew(-12deg);
            box-shadow: 0 0 18px rgba(22,119,255,.45);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
        }

        .nav-links a {
            padding: 11px 13px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
            transition: .2s;
        }

        .nav-links a:hover,
        .nav-links a[aria-current="page"] {
            color: var(--text);
            background: var(--panel2);
        }

        .nav-links a[aria-current="page"] {
            box-shadow: inset 0 -2px var(--light);
        }

        .container {
            width: min(1240px, 100%);
            margin: auto;
            padding: 52px 24px 84px;
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--light);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 2.8px;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: "";
            width: 24px;
            height: 2px;
            background: var(--blue);
        }

        .hero {
            position: relative;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 36px;
            min-height: 330px;
            padding: clamp(28px, 5vw, 62px);
            margin-bottom: 30px;
            overflow: hidden;
            border: 1px solid #263b5c;
            background:
                linear-gradient(110deg, rgba(13,20,34,.98) 0%, rgba(13,20,34,.96) 57%, rgba(12,35,70,.78) 100%);
        }

        .hero::before {
            content: "MH";
            position: absolute;
            right: 12%;
            top: 50%;
            transform: translateY(-50%) skew(-10deg);
            color: rgba(66,165,255,.055);
            font-size: clamp(150px, 24vw, 310px);
            font-weight: 950;
            letter-spacing: -20px;
            pointer-events: none;
        }

        .hero::after {
            content: "";
            position: absolute;
            right: -60px;
            top: -100px;
            width: 230px;
            height: 550px;
            border: 1px solid rgba(66,165,255,.17);
            transform: rotate(27deg);
            pointer-events: none;
        }

        .hero-content,
        .hero-action {
            position: relative;
            z-index: 1;
        }

        h1 {
            max-width: 760px;
            margin: 23px 0 18px;
            font-size: clamp(39px, 6.2vw, 76px);
            font-weight: 950;
            letter-spacing: -2.8px;
            line-height: 1.08;
        }

        h1 span { color: var(--light); }

        .intro {
            max-width: 570px;
            margin: 0;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.9;
        }

        .hero-action {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
        }

        .button {
            min-height: 48px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            padding: 14px 20px;
            border: 1px solid var(--blue);
            background: var(--blue);
            color: white;
            font-size: 13px;
            font-weight: 900;
            cursor: pointer;
            transition: transform .2s, background .2s, box-shadow .2s;
        }

        .button:hover {
            background: var(--light);
            border-color: var(--light);
            color: #06101f;
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(22,119,255,.2);
        }

        .button.secondary {
            background: transparent;
            border-color: var(--border);
        }

        .button.secondary:hover {
            color: white;
            background: var(--panel2);
            border-color: var(--light);
        }

        .hero-note {
            color: var(--muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .section-top {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin: 42px 0 18px;
        }

        .section-title {
            margin: 0;
            font-size: clamp(21px, 3vw, 29px);
            font-weight: 900;
            letter-spacing: -.7px;
        }

        .section-subtitle {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .section-count {
            color: var(--light);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            white-space: nowrap;
        }

        .filters {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto;
            gap: 12px;
            padding: 16px;
            margin-bottom: 28px;
            border: 1px solid var(--border);
            background: linear-gradient(135deg, var(--panel), #0a101c);
        }

        .field {
            position: relative;
            min-width: 0;
        }

        .field label {
            display: block;
            margin: 0 0 8px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 800;
        }

        input {
            width: 100%;
            min-height: 48px;
            padding: 13px 14px;
            border: 1px solid var(--border);
            border-radius: 0;
            outline: none;
            background: #080e19;
            color: var(--text);
            transition: border-color .2s, box-shadow .2s;
        }

        input::placeholder { color: #71819a; }

        input:focus {
            border-color: var(--light);
            box-shadow: 0 0 0 3px rgba(66,165,255,.1);
        }

        .filter-button {
            align-self: end;
            min-width: 132px;
        }

        .notice {
            padding: 15px 18px;
            margin-bottom: 22px;
            border: 1px solid #1c6449;
            background: #0c261d;
            color: #9af0c5;
            font-size: 14px;
            line-height: 1.7;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .request {
            position: relative;
            min-width: 0;
            padding: 23px;
            overflow: hidden;
            border: 1px solid var(--border);
            background: linear-gradient(145deg, #0e1727, #0a101b 85%);
            transition: border-color .22s, transform .22s, box-shadow .22s;
        }

        .request::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 42px;
            height: 3px;
            background: var(--blue);
            transition: width .22s;
        }

        .request:hover {
            border-color: #3465a3;
            transform: translateY(-4px);
            box-shadow: 0 14px 35px rgba(0,0,0,.22);
        }

        .request:hover::before { width: 100%; }

        .request-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 9px;
            border: 1px solid rgba(66,165,255,.18);
            background: rgba(16,36,71,.65);
            color: var(--light);
            font-size: 10px;
            font-weight: 900;
            line-height: 1.5;
        }

        .tag-dot {
            width: 6px;
            height: 6px;
            flex: 0 0 6px;
            background: var(--light);
            border-radius: 50%;
            box-shadow: 0 0 9px rgba(66,165,255,.6);
        }

        .request-id {
            color: #70819b;
            font-size: 10px;
            font-weight: 800;
            overflow-wrap: anywhere;
            text-align: right;
        }

        .route {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 28px minmax(0, 1fr);
            align-items: center;
            gap: 8px;
            margin: 28px 0 24px;
        }

        .city-block { min-width: 0; }

        .label {
            display: block;
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .city {
            font-size: clamp(16px, 1.5vw, 20px);
            font-weight: 900;
            letter-spacing: -.4px;
            overflow-wrap: anywhere;
        }

        .arrow {
            color: var(--light);
            text-align: center;
            font-size: 23px;
        }

        .route-line {
            height: 1px;
            margin-top: 12px;
            background: linear-gradient(90deg, var(--blue), rgba(66,165,255,.12));
        }

        .bike {
            margin-bottom: 15px;
            font-size: 17px;
            font-weight: 900;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .bike-label {
            margin-bottom: 5px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .meta {
            padding-top: 13px;
            border-top: 1px solid rgba(36,54,83,.7);
            color: var(--muted);
            font-size: 12px;
            line-height: 2;
        }

        .meta strong { color: var(--text); }

        .empty {
            grid-column: 1 / -1;
            padding: 54px 24px;
            border: 1px dashed #2a4265;
            background: rgba(13,20,34,.55);
            text-align: center;
        }

        .empty-mark {
            width: 54px;
            height: 54px;
            display: grid;
            place-items: center;
            margin: 0 auto 20px;
            border: 1px solid var(--border);
            background: var(--panel2);
            color: var(--light);
            font-size: 25px;
        }

        .empty h3 {
            margin: 0 0 10px;
            font-size: 22px;
        }

        .empty p {
            max-width: 480px;
            margin: 0 auto 22px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .pagination { margin-top: 30px; }

        .pagination nav {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 5px;
        }

        .pagination nav > div:first-child {
            display: none;
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 39px;
            min-height: 39px;
            padding: 8px 12px;
            margin: 0;
            border: 1px solid var(--border);
            background: var(--panel);
            color: var(--muted);
            font-size: 12px;
        }

        .pagination a:hover {
            border-color: var(--blue);
            color: white;
        }

        .pagination span[aria-current="page"] span {
            border-color: var(--blue);
            background: var(--blue);
            color: white;
        }

        footer {
            padding: 28px 20px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            text-align: center;
            font-size: 12px;
            line-height: 1.8;
        }

        footer span { color: var(--light); font-weight: 800; }

        @media (max-width: 980px) {
            .nav { padding-inline: 24px; }
            .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .hero { grid-template-columns: 1fr; }
            .hero-action { flex-direction: row; align-items: center; flex-wrap: wrap; }
            .hero::before { right: 4%; }
        }

        @media (max-width: 650px) {
            .nav {
                align-items: flex-start;
                flex-direction: column;
                gap: 15px;
                padding: 18px 20px;
            }

            .nav-links { width: 100%; gap: 5px; }

            .nav-links a {
                padding: 9px 10px;
                font-size: 12px;
            }

            .container { padding: 30px 16px 58px; }

            .hero {
                min-height: auto;
                gap: 25px;
                padding: 30px 22px;
                margin-bottom: 25px;
            }

            h1 {
                font-size: clamp(37px, 10vw, 55px);
                letter-spacing: -1.8px;
            }

            .intro { font-size: 13px; }

            .hero-action { align-items: stretch; flex-direction: column; }

            .hero-action .button { width: 100%; }

            .filters { grid-template-columns: 1fr; padding: 13px; }

            .filter-button { width: 100%; }

            .grid { grid-template-columns: 1fr; gap: 13px; }

            .request { padding: 20px; }

            .section-top { align-items: flex-start; flex-direction: column; }

            .route { margin: 23px 0; }

            .pagination nav > div:first-child { display: block; width: 100%; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }

        
/* ===== MotoHub Premium Transport Upgrade ===== */

:root {
    --blue: #1677ff;
    --light: #55b4ff;
    --border: #263b59;
}

body {
    background:
        radial-gradient(ellipse at 92% 8%, rgba(22,119,255,.13), transparent 28%),
        radial-gradient(ellipse at 0% 55%, rgba(22,119,255,.045), transparent 35%),
        #070a12;
}

::selection {
    background: #1677ff;
    color: #fff;
}

.nav {
    backdrop-filter: blur(18px);
    background: rgba(7,10,18,.88);
}

.logo {
    font-size: 27px;
    letter-spacing: -1.8px;
}

.logo-mark {
    width: 5px;
    height: 32px;
    transform: skew(-18deg);
}

.nav-links a {
    position: relative;
    border: 1px solid transparent;
    transition: .22s ease;
}

.nav-links a:hover,
.nav-links a[aria-current="page"] {
    border-color: rgba(66,165,255,.2);
    background: linear-gradient(135deg, rgba(22,119,255,.14), rgba(22,119,255,.025));
}

.container {
    padding-top: 42px;
}

.hero {
    isolation: isolate;
    min-height: 390px;
    border: 1px solid #29466d;
    background:
        linear-gradient(110deg, #0d1422 0%, #0d1422 47%, rgba(10,30,58,.94) 100%);
    box-shadow: 0 25px 75px rgba(0,0,0,.22);
}

.hero::before {
    content: "RIDE / MOVE";
    right: -15px;
    top: auto;
    bottom: -38px;
    transform: skew(-12deg);
    color: rgba(85,180,255,.055);
    font-size: clamp(76px, 12vw, 165px);
    letter-spacing: -8px;
    white-space: nowrap;
}

.hero::after {
    right: 8%;
    top: -150px;
    width: 110px;
    height: 650px;
    border: 1px solid rgba(85,180,255,.15);
    transform: rotate(27deg);
    box-shadow: 35px 0 0 rgba(85,180,255,.025);
}

.hero-content {
    max-width: 760px;
}

.eyebrow {
    font-size: 10px;
    letter-spacing: 3px;
}

h1 {
    line-height: 1.04;
    letter-spacing: -3.2px;
    text-wrap: balance;
}

h1 span {
    text-shadow: 0 0 35px rgba(22,119,255,.16);
}

.intro {
    max-width: 540px;
    font-size: 14px;
}

.button {
    position: relative;
    overflow: hidden;
    min-height: 51px;
    padding-inline: 22px;
    clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 0 100%);
    letter-spacing: .1px;
}

.button:not(.secondary)::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(110deg, transparent 25%, rgba(255,255,255,.16), transparent 65%);
    transform: translateX(-120%);
    transition: transform .5s ease;
}

.button:not(.secondary):hover::before {
    transform: translateX(120%);
}

.section-top {
    margin-top: 48px;
    margin-bottom: 20px;
}

.section-title {
    letter-spacing: -1px;
}

.section-count {
    padding: 9px 11px;
    border-left: 2px solid var(--blue);
    background: rgba(22,119,255,.07);
}

.filters {
    position: relative;
    gap: 14px;
    padding: 20px;
    border-color: #263b59;
    background: linear-gradient(135deg, rgba(16,27,45,.96), rgba(8,14,25,.96));
    box-shadow: 0 12px 35px rgba(0,0,0,.13);
}

.filters::before {
    content: "ROUTE SEARCH";
    position: absolute;
    right: 15px;
    top: -8px;
    padding: 0 8px;
    background: #0d1422;
    color: #718db3;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: 2px;
}

.field label {
    letter-spacing: .4px;
}

input {
    border-color: #2a3d59;
    background: rgba(4,9,17,.8);
}

input:hover {
    border-color: #41618a;
}

.filter-button {
    clip-path: none;
}

.request {
    padding: 24px;
    border-color: #243650;
    background:
        linear-gradient(145deg, rgba(17,29,48,.98), rgba(8,14,24,.98));
}

.request::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    width: 55px;
    height: 55px;
    background: linear-gradient(135deg, transparent 49%, rgba(66,165,255,.09) 50%, rgba(66,165,255,.09) 52%, transparent 53%);
    pointer-events: none;
}

.request:hover {
    border-color: #3978c5;
    box-shadow: 0 18px 40px rgba(0,0,0,.3), 0 0 24px rgba(22,119,255,.055);
}

.tag {
    border-color: rgba(66,165,255,.25);
    background: rgba(22,119,255,.09);
}

.route {
    gap: 10px;
}

.city {
    line-height: 1.3;
    overflow-wrap: anywhere;
}

.route-line {
    height: 2px;
    background: linear-gradient(90deg, #1677ff, rgba(66,165,255,.08));
}

.arrow {
    display: grid;
    place-items: center;
    width: 28px;
    height: 28px;
    border: 1px solid rgba(66,165,255,.22);
    background: rgba(22,119,255,.08);
    font-size: 18px;
}

.bike {
    font-size: 18px;
}

.meta {
    margin-top: 18px;
    padding-top: 15px;
    line-height: 2.1;
}

.meta strong {
    font-weight: 800;
}

.empty {
    border-color: #29466d;
    background:
        radial-gradient(ellipse at center, rgba(22,119,255,.07), transparent 65%),
        rgba(13,20,34,.65);
}

.empty-mark {
    transform: rotate(-5deg);
    border-color: #31517b;
    box-shadow: 0 0 30px rgba(22,119,255,.08);
}

.pagination a,
.pagination span {
    transition: .2s ease;
}

footer {
    background: rgba(7,10,18,.55);
}

footer span {
    letter-spacing: 1.5px;
}

@media (min-width: 1200px) {
    .grid {
        gap: 20px;
    }

    .request {
        padding: 26px;
    }
}

@media (max-width: 650px) {
    .container {
        padding-top: 25px;
    }

    .hero {
        min-height: 0;
        padding: 29px 21px;
    }

    h1 {
        font-size: clamp(36px, 10vw, 52px);
        letter-spacing: -1.8px;
    }

    .hero::before {
        right: -20px;
        bottom: -12px;
        font-size: 65px;
        letter-spacing: -4px;
    }

    .hero::after {
        right: -70px;
    }

    .filters {
        padding: 17px 13px;
    }

    .request {
        padding: 21px;
    }

    .section-count {
        align-self: flex-start;
    }
}


    </style>
</head>
<body>
<div class="top-line"></div>

<header class="nav">
    <a class="logo" href="{{ route('home') }}" aria-label="MotoHub მთავარი">
        <span class="logo-mark"></span>
        MOTO<span>HUB</span>
    </a>

    <nav class="nav-links" aria-label="მთავარი ნავიგაცია">
        <a href="{{ route('motorcycles.index') }}">მოტოციკლები</a>
        <a href="{{ route('rentals.index') }}">გაქირავება</a>
        <a href="{{ route('parts.index') }}">ნაწილები</a>
        <a href="{{ route('transport.index') }}" aria-current="page">ტრანსპორტი</a>
    </nav>
</header>

<main class="container">
    <section class="hero">
        <div class="hero-content">
            <div class="eyebrow">MotoHub / MotoTransport</div>

            <h1>
                შენი მოტო.<br>
                <span>ჩვენი მარშრუტი.</span>
            </h1>

            <p class="intro">
                მიუთითე, საიდან და სად გჭირდება მოტოციკლის გადატანა.
                შექმენი მოთხოვნა და მიიღე გადამზიდავების შეთავაზებები ერთ სივრცეში.
            </p>
        </div>

        <div class="hero-action">
            <a class="button" href="{{ route('transport.create') }}">
                <span aria-hidden="true">+</span>
                მოთხოვნის დამატება
            </a>
            <div class="hero-note">სწრაფი მოთხოვნა · პირდაპირი მარშრუტი</div>
        </div>
    </section>

    @if (session('success'))
        <div class="notice" role="status">{{ session('success') }}</div>
    @endif

    <div class="section-top">
        <div>
            <div class="eyebrow">Transport network</div>
            <h2 class="section-title" style="margin-top:12px">იპოვე შენი მარშრუტი</h2>
            <p class="section-subtitle">
                მოძებნე ხელმისაწვდომი მოთხოვნები ქალაქებისა და მიმართულების მიხედვით.
            </p>
        </div>
        <div class="section-count">MOTOHUB / 01</div>
    </div>

    <form class="filters" method="GET" action="{{ route('transport.index') }}">
        <div class="field">
            <label for="pickup_city">საიდან</label>
            <input
                id="pickup_city"
                type="text"
                name="pickup_city"
                placeholder="გამგზავრების ქალაქი"
                value="{{ request('pickup_city') }}"
                autocomplete="address-level2"
            >
        </div>

        <div class="field">
            <label for="destination_city">სად</label>
            <input
                id="destination_city"
                type="text"
                name="destination_city"
                placeholder="დანიშნულების ქალაქი"
                value="{{ request('destination_city') }}"
                autocomplete="off"
            >
        </div>

        <button class="button filter-button" type="submit">
            მოთხოვნების მოძებნა
        </button>
    </form>

    <div class="section-top" style="margin-top:38px">
        <div>
            <div class="eyebrow">Live requests</div>
            <h2 class="section-title" style="margin-top:12px">ხელმისაწვდომი მოთხოვნები</h2>
            <p class="section-subtitle">
                შეარჩიე შესაბამისი მარშრუტი და გაეცანი მოთხოვნის დეტალებს.
            </p>
        </div>
        <div class="section-count">TRANSPORT / 02</div>
    </div>

    <section class="grid">
        @forelse ($requests as $transportRequest)
            <article class="request">
                <div class="request-head">
                    <span class="tag">
                        <span class="tag-dot"></span>
                        {{ $transportRequest->status === 'quoted' ? 'შეთავაზებები მიღებულია' : 'ახალი მოთხოვნა' }}
                    </span>

                    <span class="request-id">
                        {{ $transportRequest->request_number }}
                    </span>
                </div>

                <div class="route">
                    <div class="city-block">
                        <span class="label">გამგზავრება</span>
                        <div class="city">{{ $transportRequest->pickup_city }}</div>
                        <div class="route-line"></div>
                    </div>

                    <div class="arrow" aria-label="მიმართულებით">→</div>

                    <div class="city-block">
                        <span class="label">დანიშნულება</span>
                        <div class="city">{{ $transportRequest->destination_city }}</div>
                        <div class="route-line"></div>
                    </div>
                </div>

                <div class="bike-label">მოტოციკლი</div>
                <div class="bike">
                    {{ $transportRequest->motorcycle_brand }}
                    {{ $transportRequest->motorcycle_model }}
                </div>

                <div class="meta">
                    @if ($transportRequest->motorcycle_year)
                        <div>წელი: <strong>{{ $transportRequest->motorcycle_year }}</strong></div>
                    @endif

                    @if ($transportRequest->preferred_date)
                        <div>
                            სასურველი თარიღი:
                            <strong>{{ $transportRequest->preferred_date->format('d.m.Y') }}</strong>
                        </div>
                    @endif

                    <div>
                        მოთხოვნის ნომერი:
                        <strong>{{ $transportRequest->request_number }}</strong>
                    </div>
                </div>
            </article>
        @empty
            <div class="empty">
                <div class="empty-mark" aria-hidden="true">↗</div>
                <h3>მოთხოვნები ჯერ არ არის</h3>
                <p>
                    ამჟამად შესაბამისი მოთხოვნები ვერ მოიძებნა.
                    შექმენი ახალი მოთხოვნა და დაიწყე ტრანსპორტირების ორგანიზება.
                </p>
                <a class="button" href="{{ route('transport.create') }}">
                    მოთხოვნის შექმნა
                </a>
            </div>
        @endforelse
    </section>

    <div class="pagination">{{ $requests->links() }}</div>
</main>

<footer>
    <span>MOTOHUB</span> © {{ date('Y') }} — მოტოციკლების სამყარო ერთ სივრცეში.
</footer>
</body>
</html>