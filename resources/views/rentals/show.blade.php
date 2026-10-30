
<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#050812">
    <title>{{ $rentalScooter->title }} — გაქირავება | MotoHub</title>

    <style>
        :root {
            --bg: #050812;
            --surface: #0b1220;
            --surface-2: #101b2d;
            --blue: #168cff;
            --blue-light: #55baff;
            --text: #f5f8ff;
            --muted: #94a6c0;
            --border: rgba(85, 170, 255, .20);
            --radius: 18px;
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            color: var(--text);
            font-family: Arial, sans-serif;
            line-height: 1.65;
            background:
                radial-gradient(ellipse at 8% 10%, rgba(0, 105, 255, .15), transparent 35%),
                radial-gradient(ellipse at 95% 50%, rgba(0, 145, 255, .09), transparent 32%),
                var(--bg);
        }

        a { color: inherit; text-decoration: none; }

        button, input, textarea { font: inherit; }

        button, a, input, textarea { -webkit-tap-highlight-color: transparent; }

        .container {
            width: min(1440px, 94%);
            margin-inline: auto;
        }

        header {
            position: sticky;
            top: 0;
            z-index: 20;
            background: rgba(5, 8, 18, .91);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
        }

        nav {
            min-height: 82px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .logo {
            font-size: clamp(25px, 2.5vw, 33px);
            font-weight: 900;
            letter-spacing: -1.5px;
            white-space: nowrap;
        }

        .logo span {
            color: var(--blue-light);
            text-shadow: 0 0 20px rgba(22, 140, 255, .45);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 46px;
            padding: 12px 18px;
            border: 1px solid rgba(85, 186, 255, .35);
            border-radius: 10px;
            background: linear-gradient(115deg, #075ed0, #168cff 65%, #42b4ff);
            color: white;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: .22s ease;
            box-shadow: 0 8px 25px rgba(0, 100, 255, .17);
        }

        .btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.12);
            box-shadow: 0 10px 30px rgba(0, 133, 255, .3);
        }

        .btn-outline {
            background: rgba(12, 24, 42, .75);
            border-color: var(--border);
            box-shadow: none;
        }

        .btn-outline:hover {
            border-color: var(--blue);
            background: rgba(22, 140, 255, .12);
        }

        main { padding: 34px 0 76px; }

        .back {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 25px;
            color: var(--blue-light);
            font-size: 14px;
            font-weight: 800;
            transition: .2s ease;
        }

        .back:hover { transform: translateX(-4px); color: white; }

        .layout {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(340px, .8fr);
            gap: 27px;
            align-items: start;
        }

        .panel {
            min-width: 0;
            position: relative;
            isolation: isolate;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: linear-gradient(145deg, rgba(15, 25, 43, .98), rgba(7, 12, 23, .99));
            box-shadow: 0 24px 65px rgba(0, 0, 0, .3);
            animation: enter .55s ease both;
        }

        .panel::before {
            content: "";
            position: absolute;
            z-index: 5;
            top: 0;
            left: 0;
            width: 110px;
            height: 3px;
            background: linear-gradient(90deg, var(--blue), var(--blue-light), transparent);
            box-shadow: 0 0 18px rgba(22, 140, 255, .5);
            pointer-events: none;
        }

        .booking-panel { animation-delay: .1s; }

        .photo {
            position: relative;
            overflow: hidden;
            aspect-ratio: 16 / 10;
            background:
                radial-gradient(ellipse at center, rgba(22, 133, 255, .20), transparent 65%),
                #080e1a;
        }

        .photo::before {
            content: "";
            position: absolute;
            z-index: 1;
            inset: 0;
            pointer-events: none;
            background:
                linear-gradient(135deg, rgba(0, 119, 255, .13), transparent 45%),
                linear-gradient(0deg, rgba(4, 8, 16, .42), transparent 45%);
        }

        .photo::after {
            content: "";
            position: absolute;
            z-index: 2;
            top: -80px;
            right: 8%;
            width: 100px;
            height: 230px;
            border: 1px solid rgba(72, 180, 255, .4);
            transform: skewX(-28deg);
            pointer-events: none;
        }

        .photo img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity .2s ease, transform .65s ease;
        }

        .photo:hover img { transform: scale(1.025); }

        .photo-count {
            position: absolute;
            z-index: 3;
            right: 16px;
            bottom: 15px;
            padding: 7px 12px;
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 8px;
            background: rgba(5, 8, 18, .72);
            backdrop-filter: blur(10px);
            color: white;
            font-size: 12px;
        }

        .photo-gallery {
            display: flex;
            gap: 11px;
            overflow-x: auto;
            padding: 14px 18px 4px;
            scrollbar-width: thin;
            scrollbar-color: #24496f transparent;
        }

        .gallery-thumb {
            flex: 0 0 100px;
            height: 70px;
            padding: 0;
            overflow: hidden;
            border: 1px solid #27364c;
            border-radius: 9px;
            background: #0c1422;
            cursor: pointer;
            transition: .2s ease;
        }

        .gallery-thumb img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery-thumb.active,
        .gallery-thumb:hover {
            border: 2px solid var(--blue);
            box-shadow: 0 0 15px rgba(22, 140, 255, .22);
        }

        .gallery-thumb:focus-visible,
        .btn:focus-visible {
            outline: 2px solid var(--blue-light);
            outline-offset: 3px;
        }

        .details { padding: 24px 30px 30px; }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: var(--blue-light);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: "";
            width: 23px;
            height: 2px;
            background: var(--blue-light);
            box-shadow: 0 0 12px var(--blue);
        }

        h1 {
            margin: 12px 0 10px;
            font-size: clamp(29px, 3.5vw, 44px);
            line-height: 1.15;
            letter-spacing: -1.2px;
            overflow-wrap: anywhere;
        }

        h2 {
            margin: 0;
            font-size: 21px;
            line-height: 1.35;
            letter-spacing: -.4px;
        }

        .muted { color: var(--muted); }

        .price {
            display: flex;
            align-items: baseline;
            flex-wrap: wrap;
            gap: 7px;
            margin: 21px 0 8px;
            color: var(--blue-light);
            font-size: clamp(28px, 3vw, 37px);
            font-weight: 900;
            letter-spacing: -.7px;
            text-shadow: 0 0 25px rgba(22, 133, 255, .2);
        }

        .price small {
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            text-shadow: none;
        }

        .specs {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 24px;
        }

        .spec {
            min-width: 0;
            padding: 15px 16px;
            border: 1px solid var(--border);
            border-radius: 11px;
            background: linear-gradient(145deg, rgba(25, 43, 68, .6), rgba(9, 17, 31, .8));
            transition: .2s ease;
        }

        .spec:hover {
            transform: translateY(-2px);
            border-color: rgba(72, 180, 255, .55);
        }

        .spec-label {
            display: block;
            margin-bottom: 5px;
            color: var(--muted);
            font-size: 11px;
        }

        .spec-value {
            display: block;
            font-size: 14px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .description-title { margin-top: 28px; }

        .description {
            white-space: pre-line;
            overflow-wrap: anywhere;
            line-height: 1.9;
        }

        .booking-panel { padding: 28px; }

        .booking-intro {
            margin: 10px 0 22px;
            font-size: 14px;
            line-height: 1.8;
        }

        .booking-note {
            margin: 17px 0 0;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: rgba(22, 140, 255, .06);
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        label {
            display: block;
            margin: 17px 0 7px;
            color: #e1edff;
            font-size: 13px;
            font-weight: 700;
        }

        input,
        textarea {
            display: block;
            width: 100%;
            min-width: 0;
            padding: 13px 14px;
            color: var(--text);
            background: rgba(3, 8, 17, .82);
            border: 1px solid #263b58;
            border-radius: 9px;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        input:focus,
        textarea:focus {
            border-color: var(--blue-light);
            background: #091426;
            box-shadow: 0 0 0 3px rgba(22, 133, 255, .13);
        }

        input[type="date"] { color-scheme: dark; }

        textarea { min-height: 96px; resize: vertical; }

        .error {
            margin-top: 6px;
            color: #ff9ca8;
            font-size: 12px;
        }

        .alert {
            margin-bottom: 22px;
            padding: 15px 18px;
            border: 1px solid rgba(60, 210, 140, .35);
            border-radius: 12px;
            background: rgba(18, 100, 60, .16);
            color: #c6f9dd;
        }

        .estimate {
            position: relative;
            margin-top: 23px;
            padding: 20px;
            overflow: hidden;
            border: 1px solid rgba(37, 137, 255, .4);
            border-radius: 12px;
            background:
                radial-gradient(ellipse at top right, rgba(22, 133, 255, .17), transparent 70%),
                linear-gradient(135deg, #0b1930, #08101d);
        }

        .estimate-total {
            position: relative;
            z-index: 1;
            margin: 6px 0;
            color: var(--blue-light);
            font-size: clamp(27px, 3vw, 34px);
            font-weight: 900;
            overflow-wrap: anywhere;
        }

        .estimate::after {
            content: "";
            position: absolute;
            top: -35px;
            right: -55px;
            width: 105px;
            height: 160px;
            border: 1px solid rgba(72, 180, 255, .2);
            transform: skewX(-25deg);
            pointer-events: none;
        }

        .full { width: 100%; margin-top: 20px; }

        .register-link { color: var(--blue-light); font-weight: 800; }

        footer {
            padding: 26px 0;
            border-top: 1px solid var(--border);
            background: rgba(4, 7, 13, .7);
            color: var(--muted);
            font-size: 13px;
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
        }

        @keyframes enter {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 1000px) {
            .layout {
                grid-template-columns: minmax(0, 1fr) minmax(310px, .82fr);
                gap: 18px;
            }

            .booking-panel { padding: 22px; }
            .details { padding: 23px; }
        }

        @media (max-width: 760px) {
            nav {
                padding: 14px 0;
                min-height: auto;
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
            }

            .nav-links { width: 100%; }
            .nav-links .btn { flex: 1; padding-inline: 10px; }

            main { padding-top: 24px; }

            .layout { grid-template-columns: 1fr; }
            .photo { aspect-ratio: 16 / 10; }

            .booking-panel { padding: 24px; }
        }

        @media (max-width: 420px) {
            .container { width: 92%; }
            .logo { font-size: 25px; }
            .nav-links { gap: 7px; }
            .nav-links .btn { font-size: 12px; min-height: 42px; }
            .specs { grid-template-columns: 1fr; }
            .details, .booking-panel { padding: 19px; }
            .gallery-thumb { flex-basis: 82px; height: 62px; }
            h1 { font-size: 29px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>
<body>

<header>
    <nav class="container">
        <a class="logo" href="{{ route('home') }}">MOTO<span>HUB</span></a>

        <div class="nav-links">
            <a class="btn btn-outline" href="{{ route('rentals.index') }}">ყველა მოპედი</a>
            @auth
                @auth<a class="btn btn-outline" href="{{ route('account.index') }}">ჩემი კაბინეტი</a><a class="btn btn-outline" href="{{ route('messages.index') }}">შეტყობინებები</a>@endauth
            @else
                <a class="btn btn-outline" href="{{ route('login') }}">შესვლა</a>
            @endauth
        </div>
    </nav>
</header>

<main class="container">
    <a class="back" href="{{ route('rentals.index') }}">← ყველა გასაქირავებელი მოპედი</a>

    @if (session('success'))
        <div class="alert" role="status">{{ session('success') }}</div>
    @endif

    <div class="layout">
        <section class="panel">
            @php
                $mainImage = $rentalScooter->image_path
                    ? \Illuminate\Support\Facades\Storage::disk('public')->url($rentalScooter->image_path)
                    : 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1400&q=85';

                $galleryImages = [
                    $mainImage,
                    'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=900&q=70&sat=-25',
                ];
            @endphp

            <div class="photo">
                <img
                    id="main-scooter-image"
                    src="{{ $mainImage }}"
                    alt="{{ $rentalScooter->title }}"
                    fetchpriority="high"
                >
                <span class="photo-count" id="photo-count">ფოტო 1 / {{ count($galleryImages) }}</span>
            </div>

            <div class="photo-gallery" aria-label="სკუტერის ფოტოების გალერეა">
                @foreach ($galleryImages as $index => $image)
                    <button
                        type="button"
                        class="gallery-thumb {{ $index === 0 ? 'active' : '' }}"
                        data-image="{{ $image }}"
                        data-index="{{ $index + 1 }}"
                        aria-label="სკუტერის ფოტო {{ $index + 1 }}"
                        aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"
                    >
                        <img src="{{ $image }}" alt="" loading="lazy">
                    </button>
                @endforeach
            </div>

            <div class="details">
                <div class="eyebrow">MotoHub Rental</div>

                <h1>{{ $rentalScooter->title }}</h1>

                <div class="muted">
                    {{ $rentalScooter->brand }} {{ $rentalScooter->model }}
                </div>

                <div class="price">
                    {{ number_format((float) $rentalScooter->daily_rate, 2, '.', ' ') }}
                    ₾ <small>/ დღე</small>
                </div>

                <div class="specs">
                    @if ($rentalScooter->year)
                        <div class="spec">
                            <span class="spec-label">გამოშვების წელი</span>
                            <span class="spec-value">{{ $rentalScooter->year }}</span>
                        </div>
                    @endif

                    @if ($rentalScooter->engine_cc)
                        <div class="spec">
                            <span class="spec-label">ძრავის მოცულობა</span>
                            <span class="spec-value">{{ $rentalScooter->engine_cc }} cc</span>
                        </div>
                    @endif

                    @if ($rentalScooter->location)
                        <div class="spec">
                            <span class="spec-label">მდებარეობა</span>
                            <span class="spec-value">{{ $rentalScooter->location }}</span>
                        </div>
                    @endif
                </div>

                <h2 class="description-title">სკუტერის შესახებ</h2>

                <p class="muted description">{{ $rentalScooter->description ?: 'დამატებითი ინფორმაციის მისაღებად გამოგვიგზავნე ჯავშნის მოთხოვნა.' }}</p>
            </div>
        </section>

        <section class="panel booking-panel" id="booking">
            <h2>მოპედის დაჯავშნა</h2>

            <p class="muted booking-intro">
                აირჩიე აღებისა და დაბრუნების თარიღები და გაიგე გაქირავების სავარაუდო ღირებულება.
                ჯავშანი ძალაში შევა კომპანიის დადასტურების შემდეგ.
            </p>

            @auth
                <form
                    method="POST"
                    action="{{ route('rentals.bookings.store', $rentalScooter->slug) }}"
                    id="booking-form"
                    data-rate="{{ (float) $rentalScooter->daily_rate }}"
                >
                    @csrf

                    <label for="start_date">აღების თარიღი *</label>
                    <input
                        id="start_date"
                        name="start_date"
                        type="date"
                        min="{{ now()->toDateString() }}"
                        value="{{ old('start_date') }}"
                        required
                    >
                    @error('start_date')
                        <div class="error">{{ $message }}</div>
                    @enderror

                    <label for="end_date">დაბრუნების თარიღი *</label>
                    <input
                        id="end_date"
                        name="end_date"
                        type="date"
                        min="{{ now()->addDay()->toDateString() }}"
                        value="{{ old('end_date') }}"
                        required
                    >
                    @error('end_date')
                        <div class="error">{{ $message }}</div>
                    @enderror

                    <label for="customer_name">სახელი და გვარი *</label>
                    <input
                        id="customer_name"
                        name="customer_name"
                        value="{{ old('customer_name', auth()->user()->name) }}"
                        maxlength="255"
                        autocomplete="name"
                        required
                    >
                    @error('customer_name')
                        <div class="error">{{ $message }}</div>
                    @enderror

                    <label for="phone">საკონტაქტო ტელეფონი *</label>
                    <input
                        id="phone"
                        name="phone"
                        type="tel"
                        value="{{ old('phone', auth()->user()->phone) }}"
                        placeholder="+995 5XX XX XX XX"
                        maxlength="30"
                        autocomplete="tel"
                        required
                    >
                    @error('phone')
                        <div class="error">{{ $message }}</div>
                    @enderror

                    <label for="notes">შენიშვნა (არასავალდებულო)</label>
                    <textarea
                        id="notes"
                        name="notes"
                        maxlength="2000"
                        placeholder="დამატებითი ინფორმაცია..."
                    >{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="error">{{ $message }}</div>
                    @enderror

                    <div class="estimate" aria-live="polite">
                        <div class="muted">სავარაუდო ღირებულება</div>
                        <div class="estimate-total" id="estimate-total">—</div>
                        <div class="muted" id="estimate-days">
                            აირჩიე თარიღები თანხის დასათვლელად.
                        </div>
                    </div>

                    <button class="btn full" type="submit">
                        ჯავშნის მოთხოვნის გაგზავნა →
                    </button>

                    <div class="booking-note">
                        თანხა სავარაუდოა. საბოლოო ღირებულებასა და ხელმისაწვდომობას კომპანია დაადასტურებს.
                    </div>
                </form>
            @else
                <div class="estimate">
                    <div class="muted">დღიური ღირებულება</div>
                    <div class="estimate-total">
                        {{ number_format((float) $rentalScooter->daily_rate, 2, '.', ' ') }} ₾
                    </div>
                    <div class="muted">ანგარიშში შესვლის შემდეგ შეძლებ ჯავშნის მოთხოვნის გაგზავნას.</div>
                </div>

                <a class="btn full" href="{{ route('login') }}">შესვლა და დაჯავშნა →</a>

                <p class="muted">
                    ანგარიში არ გაქვს?
                    <a class="register-link" href="{{ route('register') }}">რეგისტრაცია</a>
                </p>
            @endauth
        </section>
    </div>
</main>

<footer>
    <div class="container footer-inner">
        <a class="logo" href="{{ route('home') }}">MOTO<span>HUB</span></a>
        <span>© {{ date('Y') }} MotoHub — მოპედების გაქირავება</span>
    </div>
</footer>

<script>
(() => {
    const mainImage = document.getElementById('main-scooter-image');
    const thumbs = document.querySelectorAll('.gallery-thumb');
    const photoCount = document.getElementById('photo-count');

    thumbs.forEach((thumb) => {
        thumb.addEventListener('click', () => {
            const image = thumb.dataset.image;
            if (!image || !mainImage) return;

            mainImage.style.opacity = '0.35';

            const preload = new Image();
            preload.onload = () => {
                mainImage.src = image;
                mainImage.style.opacity = '1';
            };
            preload.onerror = () => {
                mainImage.style.opacity = '1';
            };
            preload.src = image;

            thumbs.forEach((item) => {
                const active = item === thumb;
                item.classList.toggle('active', active);
                item.setAttribute('aria-pressed', active ? 'true' : 'false');
            });

            if (photoCount) {
                photoCount.textContent =
                    `ფოტო ${thumb.dataset.index} / ${thumbs.length}`;
            }
        });
    });

    const form = document.getElementById('booking-form');
    if (!form) return;

    const start = document.getElementById('start_date');
    const end = document.getElementById('end_date');
    const total = document.getElementById('estimate-total');
    const daysLabel = document.getElementById('estimate-days');
    const rate = Number(form.dataset.rate);

    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    function updateEstimate() {
        if (start.value) {
            const nextDate = new Date(start.value + 'T12:00:00');
            nextDate.setDate(nextDate.getDate() + 1);
            end.min = formatDate(nextDate);

            if (end.value && end.value <= start.value) {
                end.setCustomValidity('დაბრუნების თარიღი აღების თარიღზე გვიან უნდა იყოს.');
            } else {
                end.setCustomValidity('');
            }
        } else {
            end.setCustomValidity('');
        }

        if (!start.value || !end.value) {
            total.textContent = '—';
            daysLabel.textContent = 'აირჩიე ორივე თარიღი თანხის დასათვლელად.';
            return;
        }

        const startDate = new Date(start.value + 'T12:00:00');
        const endDate = new Date(end.value + 'T12:00:00');

        const days = Math.round(
            (endDate.getTime() - startDate.getTime()) / 86400000
        );

        if (!Number.isFinite(days) || days < 1) {
            total.textContent = '—';
            daysLabel.textContent =
                'დაბრუნების თარიღი აღების თარიღზე მინიმუმ ერთი დღით გვიან უნდა იყოს.';
            return;
        }

        const price = days * rate;

        total.textContent = new Intl.NumberFormat('ka-GE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(price) + ' ₾';

        daysLabel.textContent =
            `${days} ${days === 1 ? 'დღე' : 'დღე'} × ` +
            `${new Intl.NumberFormat('ka-GE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(rate)} ₾`;
    }

    start.addEventListener('change', updateEstimate);
    end.addEventListener('change', updateEstimate);

    form.addEventListener('submit', (event) => {
        updateEstimate();

        if (
            !start.value ||
            !end.value ||
            end.value <= start.value
        ) {
            event.preventDefault();
            daysLabel.textContent =
                'გთხოვ, სწორად აირჩიე აღებისა და დაბრუნების თარიღები.';
            end.reportValidity();
        }
    });

    updateEstimate();
})();
</script>

</body>
</html>
