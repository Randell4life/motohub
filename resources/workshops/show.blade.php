<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $workshop->name }} | MotoHub</title>
    <meta name="description" content="{{ $workshop->description ?? $workshop->name }}">
    <style>
        :root {
            --bg: #080d18;
            --panel: #101a2c;
            --line: #24334b;
            --text: #f3f6fc;
            --muted: #9ba9bf;
            --blue: #3285ff;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        a { color: inherit; text-decoration: none; }

        .container {
            width: min(1100px, 92%);
            margin: auto;
        }

        header {
            border-bottom: 1px solid var(--line);
            background: #0b1220;
        }

        nav {
            min-height: 76px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .logo {
            font-size: 25px;
            font-weight: 900;
            letter-spacing: -1px;
        }

        .logo span { color: var(--blue); }

        .back {
            color: #b9c7dc;
            font-size: 14px;
        }

        .hero { padding: 48px 0 30px; }

        .eyebrow {
            color: #70aaff;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        h1 {
            font-size: clamp(30px, 5vw, 48px);
            line-height: 1.15;
            margin: 12px 0;
            overflow-wrap: anywhere;
        }

        .muted { color: var(--muted); }

        .info {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 25px;
        }

        .info-card, .service {
            border: 1px solid var(--line);
            background: var(--panel);
            border-radius: 14px;
            padding: 20px;
        }

        .label {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 5px;
        }

        .section-title {
            margin: 30px 0 16px;
            font-size: 25px;
        }

        .services {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            padding-bottom: 60px;
        }

        .category {
            color: #75adff;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .service h3 { margin: 8px 0; font-size: 19px; }

        .price {
            color: #78adff;
            font-size: 25px;
            font-weight: 800;
            margin: 16px 0;
        }

        .button {
            display: inline-block;
            padding: 11px 16px;
            border-radius: 9px;
            background: var(--blue);
            color: white;
            font-weight: bold;
        }

        .empty {
            grid-column: 1 / -1;
            border: 1px dashed #35445d;
            border-radius: 14px;
            padding: 30px;
            color: var(--muted);
        }

        footer {
            border-top: 1px solid var(--line);
            padding: 24px 0;
            color: var(--muted);
            font-size: 13px;
        }

        @media (max-width: 650px) {
            .info, .services { grid-template-columns: 1fr; }
            .hero { padding-top: 32px; }
            nav { min-height: 65px; }
        }
    </style>
</head>
<body>
<header>
    <nav class="container">
        <a class="logo" href="{{ route('home') }}">Moto<span>Hub</span></a>
        <a class="back" href="{{ route('workshops.index') }}">← ყველა სახელოსნო</a>
    </nav>
</header>

<main class="container">
    <section class="hero">
        <div class="eyebrow">MotoHub / სახელოსნო</div>
        <h1>{{ $workshop->name }}</h1>

        @if($workshop->description)
            <p class="muted">{{ $workshop->description }}</p>
        @endif

        <div class="info">
            @if($workshop->address)
                <div class="info-card">
                    <div class="label">მისამართი</div>
                    <div>{{ $workshop->address }}</div>
                </div>
            @endif

            @if($workshop->phone)
                <div class="info-card">
                    <div class="label">ტელეფონი</div>
                    <div>
                        <a href="tel:{{ $workshop->phone }}">{{ $workshop->phone }}</a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section>
        <h2 class="section-title">მომსახურებები და ფასები</h2>

        <div class="services">
            @forelse($workshop->services as $service)
                <article class="service">
                    <div class="category">{{ $service->category }}</div>
                    <h3>{{ $service->title }}</h3>

                    @if($service->description)
                        <p class="muted">{{ $service->description }}</p>
                    @endif

                    <div class="price">
                        @if($service->price !== null)
                            {{ number_format((float) $service->price, 2) }} ₾
                        @else
                            ფასი შეთანხმებით
                        @endif
                    </div>

                    <a class="button"
                       href="{{ route('workshops.booking.create', ['slug' => $workshop->slug, 'service' => $service->id]) }}">
                        დაჯავშნის მოთხოვნა
                    </a>
                </article>
            @empty
                <div class="empty">
                    ამ სახელოსნოს აქტიური მომსახურებები ჯერ არ აქვს დამატებული.
                </div>
            @endforelse
        </div>
    </section>
</main>

<footer>
    <div class="container">© {{ date('Y') }} MotoHub — მოტოციკლისტების პლატფორმა</div>
</footer>
</body>
</html>
