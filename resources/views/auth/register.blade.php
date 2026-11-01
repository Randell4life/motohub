<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>რეგისტრაცია | Garage 33</title>
    <meta name="theme-color" content="#0a0b0f">

    <style>
        :root {
            --bg: #090a0e;
            --panel: #111319;
            --field: #0b0d12;
            --border: #272b35;
            --text: #f5f6fa;
            --muted: #9298a6;
            --red: #ed2939;
            --red-hover: #ff3d4d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 32px 18px;
            display: grid;
            place-items: center;
            position: relative;
            overflow-x: hidden;
            color: var(--text);
            background:
                radial-gradient(ellipse at 15% 15%, #ed293916, transparent 35%),
                radial-gradient(ellipse at 90% 85%, #233b6b20, transparent 35%),
                var(--bg);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: .14;
            background-image:
                linear-gradient(#ffffff08 1px, transparent 1px),
                linear-gradient(90deg, #ffffff08 1px, transparent 1px);
            background-size: 42px 42px;
            mask-image: linear-gradient(to bottom, black, transparent 85%);
        }

        a {
            color: var(--red);
            text-decoration: none;
            transition: color .2s ease;
        }

        a:hover {
            color: var(--red-hover);
        }

        .layout {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 1;
        }

        .topline {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--red);
            box-shadow: 0 0 12px #ed293980;
        }

        .card {
            padding: 38px;
            background: linear-gradient(145deg, #151820, #0f1117 75%);
            border: 1px solid #ffffff12;
            border-radius: 18px;
            box-shadow: 0 28px 90px #0008;
        }

        .brand-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding-bottom: 26px;
            border-bottom: 1px solid #ffffff10;
        }

        .logo {
            color: #fff;
            font-size: 25px;
            font-weight: 950;
            letter-spacing: -1.5px;
        }

        .logo span {
            color: var(--red);
        }

        .brand-caption {
            color: #737987;
            font-size: 10px;
            letter-spacing: 2px;
            text-align: right;
        }

        .eyebrow {
            margin: 28px 0 10px;
            color: var(--red);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            font-size: clamp(27px, 6vw, 34px);
            font-weight: 850;
            letter-spacing: -1.2px;
            line-height: 1.2;
        }

        .subtitle {
            margin: 12px 0 28px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .form-grid {
            display: grid;
            gap: 18px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            color: #dfe1e8;
            font-size: 13px;
            font-weight: 650;
        }

        .optional {
            color: #777e8d;
            font-size: 11px;
            font-weight: 400;
        }

        .input-wrap {
            position: relative;
        }

        input {
            width: 100%;
            min-height: 49px;
            padding: 13px 14px;
            outline: none;
            border: 1px solid var(--border);
            border-radius: 9px;
            color: var(--text);
            background: var(--field);
            font: inherit;
            font-size: 14px;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        input::placeholder {
            color: #555b68;
        }

        input:hover {
            border-color: #414754;
        }

        input:focus {
            border-color: var(--red);
            background: #101117;
            box-shadow: 0 0 0 3px #ed29391c;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: var(--text);
            box-shadow: 0 0 0 1000px var(--field) inset;
            transition: background-color 9999s ease-in-out 0s;
        }

        .password-input {
            padding-right: 75px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            padding: 6px 0 6px 8px;
            border: 0;
            color: #9ba1ae;
            background: transparent;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
        }

        .toggle-password:hover {
            color: white;
        }

        .error {
            margin-top: 7px;
            color: #ff8d98;
            font-size: 12px;
            line-height: 1.5;
        }

        .error-summary {
            margin-bottom: 20px;
            padding: 13px 15px;
            border: 1px solid #ed293955;
            border-radius: 9px;
            color: #ffadb4;
            background: #ed293911;
            font-size: 13px;
            line-height: 1.7;
        }

        .btn {
            width: 100%;
            min-height: 51px;
            margin-top: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: 1px solid var(--red);
            border-radius: 9px;
            color: white;
            background: var(--red);
            font: inherit;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: transform .2s, background .2s, box-shadow .2s;
        }

        .btn:hover {
            transform: translateY(-2px);
            background: var(--red-hover);
            box-shadow: 0 10px 28px #ed293930;
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-arrow {
            font-size: 18px;
            transition: transform .2s;
        }

        .btn:hover .btn-arrow {
            transform: translateX(4px);
        }

        .bottom {
            margin-top: 24px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
            line-height: 2;
        }

        .bottom strong {
            color: #f0f1f5;
            font-weight: 650;
        }

        .home-link {
            display: inline-block;
            margin-top: 9px;
            color: #a2a7b3;
            font-size: 12px;
        }

        .home-link:hover {
            color: white;
        }

        .footer {
            margin-top: 22px;
            color: #5f6470;
            text-align: center;
            font-size: 10px;
            letter-spacing: 1.5px;
        }

        @media (max-width: 520px) {
            body {
                padding: 20px 12px;
            }

            .card {
                padding: 26px 20px;
                border-radius: 14px;
            }

            .brand-caption {
                font-size: 9px;
                letter-spacing: 1px;
            }

            .logo {
                font-size: 23px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                transition: none !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body>
    <main class="layout">
        <div class="topline">
            <span class="status">
                <span class="status-dot"></span>
                MOTO COMMUNITY
            </span>
            <span>EST. 33</span>
        </div>

        <section class="card">
            <div class="brand-row">
                <a class="logo" href="{{ route('home') }}">
                    MOTO<span>HUB</span>
                </a>
                <div class="brand-caption">
                    RIDE YOUR WAY<br>
                    GARAGE 33
                </div>
            </div>

            <p class="eyebrow">JOIN THE COMMUNITY</p>
            <h1>შექმენი ანგარიში<span style="color: var(--red)">.</span></h1>
            <p class="subtitle">
                შემოუერთდი Garage 33-ის მოტოსამყაროს და მართე შენი მოტო-პროფილი ერთ სივრცეში.
            </p>

            @if ($errors->any())
                <div class="error-summary" role="alert">
                    გთხოვ, გადაამოწმე ფორმაში მონიშნული ველები.
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <div class="form-grid">
                    <div class="field">
                        <label for="referral_code">რეფერალური კოდი <span class="optional">(არასავალდებულო)</span></label>
                        <input id="referral_code" name="referral_code" type="text" value="{{ old('referral_code', $referralCode ?? session('referral_code')) }}" maxlength="20" minlength="4" autocomplete="off" placeholder="შეიყვანე მეგობრის მოწვევის კოდი" style="text-transform:uppercase" aria-describedby="referral-code-help referral-code-error">
                        <small id="referral-code-help" style="display:block;margin-top:7px;color:#9298a6;font-size:11px">თუ მეგობარმა მოგიწვია, ჩაწერე მისი კოდი — ორივე მიიღებთ ქულებს.</small>
                        @error('referral_code')
                            <div class="error" id="referral-code-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label for="name">სახელი და გვარი</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            autocomplete="name"
                            placeholder="მაგ. გიორგი გიორგაძე"
                            required
                            autofocus
                            aria-describedby="name-error"
                        >
                        @error('name')
                            <div class="error" id="name-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="phone">
                            ტელეფონის ნომერი
                            <span class="optional">(არასავალდებულო)</span>
                        </label>
                        <input
                            id="phone"
                            name="phone"
                            type="tel"
                            value="{{ old('phone') }}"
                            autocomplete="tel"
                            placeholder="+995 5XX XX XX XX"
                            aria-describedby="phone-error"
                        >
                        @error('phone')
                            <div class="error" id="phone-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="email">ელფოსტის მისამართი</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            placeholder="შენი ელფოსტა"
                            required
                            aria-describedby="email-error"
                        >
                        @error('email')
                            <div class="error" id="email-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">პაროლი</label>
                        <div class="input-wrap">
                            <input
                                class="password-input"
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="new-password"
                                placeholder="მინიმუმ 8 სიმბოლო"
                                minlength="8"
                                required
                                aria-describedby="password-error"
                            >
                            <button
                                class="toggle-password"
                                type="button"
                                data-target="password"
                                aria-label="პაროლის ჩვენება"
                                aria-pressed="false"
                            >ჩვენება</button>
                        </div>
                        @error('password')
                            <div class="error" id="password-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">გაიმეორე პაროლი</label>
                        <div class="input-wrap">
                            <input
                                class="password-input"
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                autocomplete="new-password"
                                placeholder="შეიყვანე პაროლი ხელახლა"
                                minlength="8"
                                required
                            >
                            <button
                                class="toggle-password"
                                type="button"
                                data-target="password_confirmation"
                                aria-label="პაროლის ჩვენება"
                                aria-pressed="false"
                            >ჩვენება</button>
                        </div>
                    </div>
                </div>

                <button class="btn" type="submit">
                    ანგარიშის შექმნა
                    <span class="btn-arrow" aria-hidden="true">→</span>
                </button>
            </form>

            <div class="bottom">
                <div>უკვე გაქვს ანგარიში? <strong><a href="{{ route('login') }}">შესვლა</a></strong></div>
                <a class="home-link" href="{{ route('home') }}">← მთავარ გვერდზე დაბრუნება</a>
            </div>
        </section>

        <div class="footer">
            GARAGE 33 &nbsp;·&nbsp; BUILT FOR RIDERS
        </div>
    </main>

    <script>
        document.querySelectorAll('.toggle-password').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(button.dataset.target);
                const showing = input.type === 'password';

                input.type = showing ? 'text' : 'password';
                button.textContent = showing ? 'დამალვა' : 'ჩვენება';
                button.setAttribute('aria-pressed', String(showing));
                button.setAttribute(
                    'aria-label',
                    showing ? 'პაროლის დამალვა' : 'პაროლის ჩვენება'
                );
            });
        });
    </script>
</body>
</html>
