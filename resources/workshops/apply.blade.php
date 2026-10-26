<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>სახელოსნოს რეგისტრაცია | MotoHub</title>
    <style>
        :root {
            --bg: #080b12;
            --panel: #111725;
            --line: #273246;
            --text: #f3f6fc;
            --muted: #94a3b8;
            --blue: #3478f6;
            --cyan: #48d9ff;
            --danger: #ff7777;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            background:
                radial-gradient(ellipse at 85% 5%, #142b51 0, transparent 35%),
                var(--bg);
            font-family: Arial, sans-serif;
        }

        a { color: inherit; text-decoration: none; }

        header {
            border-bottom: 1px solid var(--line);
            background: rgba(8, 11, 18, .85);
        }

        .nav {
            max-width: 1120px;
            margin: auto;
            padding: 22px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .brand {
            font-size: 21px;
            font-weight: 900;
            letter-spacing: 1px;
        }

        .brand span { color: var(--cyan); }

        .back {
            color: var(--muted);
            font-size: 14px;
        }

        main {
            max-width: 1120px;
            margin: 50px auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: .85fr 1.15fr;
            gap: 45px;
            align-items: start;
        }

        .eyebrow {
            color: var(--cyan);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        h1 {
            font-size: clamp(34px, 5vw, 54px);
            line-height: 1.08;
            letter-spacing: -1.5px;
            margin: 18px 0;
        }

        .intro {
            color: var(--muted);
            font-size: 16px;
            line-height: 1.8;
        }

        .features {
            margin-top: 34px;
            display: grid;
            gap: 14px;
        }

        .feature {
            border: 1px solid var(--line);
            background: rgba(17, 23, 37, .65);
            padding: 17px;
            border-radius: 12px;
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .feature-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: #172e53;
            color: var(--cyan);
            font-size: 20px;
        }

        .feature strong { display: block; margin-bottom: 5px; }
        .feature small { color: var(--muted); line-height: 1.5; }

        .form-card {
            border: 1px solid var(--line);
            background: rgba(17, 23, 37, .94);
            padding: clamp(22px, 4vw, 36px);
            border-radius: 18px;
            box-shadow: 0 24px 80px #0004;
        }

        h2 { margin: 0 0 9px; font-size: 25px; }
        .subtitle { color: var(--muted); margin: 0 0 25px; line-height: 1.6; }

        .notice {
            padding: 14px 16px;
            margin-bottom: 20px;
            border-radius: 10px;
            line-height: 1.6;
            font-size: 14px;
        }

        .success {
            background: #103b2a;
            border: 1px solid #1d7950;
            color: #a8f0ca;
        }

        .error {
            background: #401b24;
            border: 1px solid #843445;
            color: #ffc1c9;
        }

        .status {
            color: var(--cyan);
            font-size: 13px;
            margin-bottom: 22px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .field { margin-bottom: 18px; }
        .full { grid-column: 1 / -1; }

        label {
            display: block;
            margin-bottom: 9px;
            font-size: 13px;
            font-weight: 700;
        }

        label span { color: var(--muted); font-weight: 400; }

        input, textarea {
            width: 100%;
            padding: 14px;
            border: 1px solid #303d53;
            border-radius: 9px;
            color: var(--text);
            background: #0b101b;
            font: inherit;
            outline: none;
            transition: border .2s;
        }

        input:focus, textarea:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px #3478f622;
        }

        textarea { min-height: 120px; resize: vertical; }

        .field-error {
            color: var(--danger);
            font-size: 12px;
            margin-top: 7px;
        }

        button {
            width: 100%;
            padding: 16px;
            border: 0;
            border-radius: 9px;
            background: linear-gradient(100deg, var(--blue), #285ce8);
            color: white;
            font-weight: 800;
            font-size: 15px;
            cursor: pointer;
        }

        button:hover { filter: brightness(1.12); }

        .fine-print {
            margin: 15px 0 0;
            color: var(--muted);
            text-align: center;
            font-size: 12px;
            line-height: 1.7;
        }

        footer {
            text-align: center;
            color: #64748b;
            padding: 25px;
            font-size: 12px;
        }

        @media (max-width: 800px) {
            main { grid-template-columns: 1fr; margin-top: 30px; gap: 28px; }
            .features { grid-template-columns: 1fr; }
        }

        @media (max-width: 480px) {
            .nav { padding: 18px; }
            main { padding: 0 16px; }
            .grid { grid-template-columns: 1fr; gap: 0; }
            .full { grid-column: auto; }
        }
    </style>
</head>
<body>
<header>
    <nav class="nav">
        <a class="brand" href="{{ route('home') }}">MOTO<span>HUB</span></a>
        <a class="back" href="{{ route('home') }}">← მთავარ გვერდზე</a>
    </nav>
</header>

<main>
    <section>
        <div class="eyebrow">MotoHub Workshop Network</div>
        <h1>შენი სახელოსნო.<br><span style="color:#48d9ff">ახალი შესაძლებლობები.</span></h1>
        <p class="intro">
            შემოუერთდი MotoHub-ის სახელოსნოების ქსელს. მართე შენი
            სერვისი ერთ სივრცეში და მოამზადე საფუძველი კლიენტების,
            მოტოციკლებისა და სამუშაო შეკვეთების სამართავად.
        </p>

        <div class="features">
            <div class="feature">
                <div class="feature-icon">01</div>
                <div>
                    <strong>განაცხადის განხილვა</strong>
                    <small>ადმინისტრატორი შეამოწმებს შენს ინფორმაციას.</small>
                </div>
            </div>
            <div class="feature">
                <div class="feature-icon">02</div>
                <div>
                    <strong>პირადი სამუშაო სივრცე</strong>
                    <small>დამტკიცების შემდეგ მიიღებ შენს სახელოსნოს პანელს.</small>
                </div>
            </div>
            <div class="feature">
                <div class="feature-icon">03</div>
                <div>
                    <strong>ყველაფერი ერთ ადგილას</strong>
                    <small>შემდეგ ეტაპზე დაემატება შეკვეთები, ნაწილები და ფინანსები.</small>
                </div>
            </div>
        </div>
    </section>

    <section class="form-card">
        <h2>სახელოსნოს განაცხადი</h2>
        <p class="subtitle">შეავსე ინფორმაცია ზუსტად. ყველა სავალდებულო ველი მონიშნულია.</p>

        @if (session('success'))
            <div class="notice success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="notice error">
                გთხოვ, შეასწორო შემდეგი შეცდომები:
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($partner)
            <div class="status">
                განაცხადის სტატუსი:
                @switch($partner->application_status)
                    @case('pending')
                        განხილვის მოლოდინში
                        @break
                    @case('rejected')
                        უარყოფილია — შეგიძლია ინფორმაცია განაახლო და ხელახლა გაგზავნო.
                        @break
                    @case('approved')
                        დამტკიცებულია
                        @break
                    @default
                        {{ $partner->application_status }}
                @endswitch
            </div>
        @endif

        <form method="POST" action="{{ route('workshop.apply.store') }}">
            @csrf

            <div class="grid">
                <div class="field full">
                    <label for="name">სახელოსნოს სახელი *</label>
                    <input id="name" name="name" required maxlength="150"
                           value="{{ old('name', $partner?->name) }}"
                           placeholder="მაგ. Moto Garage Tbilisi">
                    @error('name') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="phone">ტელეფონი *</label>
                    <input id="phone" name="phone" type="tel" required maxlength="30"
                           value="{{ old('phone', $partner?->phone) }}"
                           placeholder="+995 5XX XX XX XX">
                    @error('phone') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="email">ელფოსტა <span>(არასავალდებულო)</span></label>
                    <input id="email" name="email" type="email" maxlength="255"
                           value="{{ old('email', $partner?->email) }}"
                           placeholder="garage@example.com">
                    @error('email') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="field full">
                    <label for="address">მისამართი *</label>
                    <input id="address" name="address" required maxlength="255"
                           value="{{ old('address', $partner?->address) }}"
                           placeholder="ქალაქი, ქუჩა, ნომერი">
                    @error('address') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="field full">
                    <label for="description">აღწერა <span>(არასავალდებულო)</span></label>
                    <textarea id="description" name="description" maxlength="3000"
                              placeholder="რა მომსახურებას სთავაზობ მომხმარებლებს?">{{ old('description', $partner?->description) }}</textarea>
                    @error('description') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="submit">
                {{ $partner && $partner->application_status === 'pending'
                    ? 'განაცხადის განახლება'
                    : 'განაცხადის გაგზავნა →' }}
            </button>

            <p class="fine-print">
                განაცხადის გაგზავნა არ ნიშნავს ავტომატურ დამტკიცებას.
                სახელოსნო გააქტიურდება ადმინისტრატორის შემოწმების შემდეგ.
            </p>
        </form>
    </section>
</main>

<footer>© {{ date('Y') }} MotoHub · Workshop Network</footer>
</body>
</html>
