
<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#09090b">

    <title>შესვლა | Garage 33</title>

    <style>
        :root {
            --bg: #09090b;
            --surface: #121216;
            --surface-light: #19191f;
            --border: #292930;
            --text: #f5f5f7;
            --muted: #9696a2;
            --red: #ed2939;
            --red-hover: #ff3b4b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 18px;
            background:
                radial-gradient(ellipse at 50% 0%, rgba(237, 41, 57, .10), transparent 48%),
                var(--bg);
            color: var(--text);
            font-family: Arial, sans-serif;
        }

        a {
            color: var(--red);
            text-decoration: none;
            transition: color .2s ease;
        }

        a:hover {
            color: var(--red-hover);
        }

        .page {
            width: 100%;
            max-width: 450px;
        }

        .top {
            margin-bottom: 22px;
            text-align: center;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: var(--text);
            font-size: 29px;
            font-weight: 900;
            letter-spacing: -1.5px;
        }

        .logo-mark {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--red);
            color: white;
            font-size: 17px;
            letter-spacing: -1px;
            box-shadow: 0 5px 24px rgba(237, 41, 57, .22);
        }

        .logo span:last-child {
            color: var(--red);
        }

        .top-note {
            margin: 10px 0 0;
            color: var(--muted);
            font-size: 13px;
            letter-spacing: .3px;
        }

        .card {
            padding: 34px;
            background: rgba(18, 18, 22, .96);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 24px 80px rgba(0, 0, 0, .32);
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 13px;
            color: var(--red);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: "";
            width: 22px;
            height: 2px;
            background: var(--red);
        }

        h1 {
            margin: 0;
            font-size: clamp(27px, 5vw, 34px);
            line-height: 1.2;
            letter-spacing: -1px;
        }

        .subtitle {
            margin: 12px 0 27px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            margin-bottom: 9px;
            color: #dedee4;
            font-size: 13px;
            font-weight: 700;
        }

        .input-wrap {
            position: relative;
        }

        .field input[type="email"],
        .field input[type="password"],
        .field input[type="text"] {
            display: block;
            width: 100%;
            min-height: 49px;
            padding: 14px 15px;
            border: 1px solid #303039;
            border-radius: 9px;
            outline: none;
            background: #0c0c0f;
            color: var(--text);
            font: inherit;
            font-size: 14px;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .field input::placeholder {
            color: #60606b;
        }

        .field input:focus {
            border-color: var(--red);
            background: #101014;
            box-shadow: 0 0 0 3px rgba(237, 41, 57, .12);
        }

        .password-input {
            padding-right: 76px !important;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            padding: 6px 4px;
            border: 0;
            background: transparent;
            color: #a6a6b1;
            font-size: 12px;
            cursor: pointer;
        }

        .toggle-password:hover {
            color: white;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 3px 0 24px;
            color: #b8b8c2;
            font-size: 13px;
            cursor: pointer;
        }

        .remember input {
            width: 16px;
            height: 16px;
            margin: 0;
            accent-color: var(--red);
            cursor: pointer;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            min-height: 51px;
            padding: 14px 18px;
            border: 1px solid var(--red);
            border-radius: 9px;
            background: var(--red);
            color: #fff;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            transition: background .2s, transform .2s, box-shadow .2s;
        }

        .btn:hover {
            background: var(--red-hover);
            box-shadow: 0 8px 24px rgba(237, 41, 57, .2);
            transform: translateY(-1px);
        }

        .btn:active {
            transform: translateY(0);
        }

        .error {
            margin-top: 8px;
            color: #ff8994;
            font-size: 12px;
            line-height: 1.6;
        }

        .error-summary {
            margin-bottom: 20px;
            padding: 12px 14px;
            border: 1px solid rgba(237, 41, 57, .35);
            border-radius: 9px;
            background: rgba(237, 41, 57, .08);
            color: #ffadb4;
            font-size: 13px;
            line-height: 1.6;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 13px;
            margin: 25px 0;
            color: #666671;
            font-size: 11px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .register {
            text-align: center;
            color: #b8b8c2;
            font-size: 13px;
            line-height: 1.8;
        }

        .register a {
            font-weight: 800;
        }

        .back {
            display: block;
            margin-top: 24px;
            color: #858590;
            text-align: center;
            font-size: 12px;
        }

        .back:hover {
            color: white;
        }

        .footer {
            margin-top: 24px;
            color: #555560;
            text-align: center;
            font-size: 11px;
            letter-spacing: .5px;
        }

        @media (max-width: 480px) {
            body {
                padding: 22px 14px;
            }

            .card {
                padding: 25px 21px;
                border-radius: 14px;
            }

            .logo {
                font-size: 26px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                transition: none !important;
            }
        }
    </style>
</head>

<body>
    <main class="page">
        <header class="top">
            <a class="logo" href="{{ route('home') }}" aria-label="Garage 33 მთავარი გვერდი">
                <span class="logo-mark">G33</span>
                <span>GARAGE <span>33</span></span>
            </a>
            <p class="top-note">YOUR RIDE. YOUR GARAGE.</p>
        </header>

        <section class="card">
            <div class="eyebrow">WELCOME BACK</div>

            <h1>კეთილი დაბრუნება</h1>
            <p class="subtitle">
                შედი შენს ანგარიშში და გააგრძელე Garage 33-ის გამოყენება.
            </p>

            @if ($errors->any())
                <div class="error-summary" role="alert">
                    მონაცემები ვერ დადასტურდა. გადაამოწმე ელფოსტა და პაროლი.
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="field">
                    <label for="email">ელფოსტის მისამართი</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        placeholder="name@example.com"
                        autocomplete="email"
                        required
                        autofocus
                    >

                    @error('email')
                        <div class="error">{{ $message }}</div>
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
                            placeholder="შეიყვანე პაროლი"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            class="toggle-password"
                            type="button"
                            id="togglePassword"
                            aria-controls="password"
                            aria-pressed="false"
                        >ჩვენება</button>
                    </div>

                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <label class="remember" for="remember">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        value="1"
                        @checked(old('remember'))
                    >
                    <span>დამიმახსოვრე</span>
                </label>

                <button class="btn" type="submit">
                    <span>ანგარიშში შესვლა</span>
                    <span aria-hidden="true">→</span>
                </button>
            </form>

            <div class="divider">ახალი ხარ Garage 33-ში?</div>

            <div class="register">
                ჯერ არ გაქვს ანგარიში?
                <a href="{{ route('register') }}">შექმენი ანგარიში</a>
            </div>

            <a class="back" href="{{ route('home') }}">
                ← მთავარ გვერდზე დაბრუნება
            </a>
        </section>

        <footer class="footer">
            GARAGE 33 &copy; {{ date('Y') }} · BUILT FOR RIDERS
        </footer>
    </main>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';

            passwordInput.type = isHidden ? 'text' : 'password';
            togglePassword.textContent = isHidden ? 'დამალვა' : 'ჩვენება';
            togglePassword.setAttribute('aria-pressed', String(isHidden));
        });
    </script>
</body>
</html>
