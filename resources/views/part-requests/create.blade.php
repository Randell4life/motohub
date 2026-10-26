
<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ნაწილის მოთხოვნა | MotoHub</title>
    <meta name="description" content="ვერ იპოვე სასურველი მოტოციკლის ნაწილი? გამოგვიგზავნე მოთხოვნა MotoHub-ს.">

    <style>
        :root {
            color-scheme: dark;
            --bg: #07090e;
            --panel: #0e131d;
            --panel-light: #131b29;
            --blue: #3478ff;
            --blue-light: #82acff;
            --text: #f4f7ff;
            --muted: #929db0;
            --border: #252e3e;
            --green: #65dba0;
            --danger: #ff8181;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background:
                radial-gradient(ellipse at 85% 0%, rgba(52, 120, 255, .13), transparent 34%),
                radial-gradient(ellipse at 0% 60%, rgba(35, 74, 142, .07), transparent 32%),
                var(--bg);
            color: var(--text);
            font-family: Arial, "Noto Sans Georgian", sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button, input, textarea {
            font: inherit;
        }

        .container {
            width: min(1120px, calc(100% - 40px));
            margin: 0 auto;
        }

        /* NAVIGATION */

        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 1px solid rgba(255, 255, 255, .07);
            background: rgba(7, 9, 14, .88);
            backdrop-filter: blur(18px);
        }

        .nav {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 23px;
            font-weight: 900;
            letter-spacing: -1.3px;
            white-space: nowrap;
        }

        .logo-mark {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border: 1px solid rgba(255, 255, 255, .16);
            border-radius: 10px;
            background: linear-gradient(145deg, #4387ff, #1a48ad);
            box-shadow: 0 5px 25px rgba(52, 120, 255, .2);
            color: white;
            font-size: 17px;
        }

        .logo-name span {
            color: var(--blue-light);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 10px 13px;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: #b5bfd0;
            font-size: 12px;
            transition: .2s;
        }

        .back-link:hover {
            border-color: #3d65a5;
            background: #111a29;
            color: white;
        }

        /* INTRO */

        .hero {
            position: relative;
            padding: 65px 0 36px;
        }

        .hero::after {
            content: "REQUEST";
            position: absolute;
            top: 33px;
            right: -8px;
            z-index: -1;
            color: rgba(130, 172, 255, .035);
            font-size: clamp(55px, 12vw, 145px);
            font-weight: 900;
            letter-spacing: -7px;
            pointer-events: none;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--blue-light);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2.5px;
        }

        .eyebrow::before {
            content: "";
            width: 25px;
            height: 2px;
            background: var(--blue);
        }

        h1 {
            margin: 19px 0 17px;
            font-size: clamp(37px, 6vw, 65px);
            line-height: 1.12;
            letter-spacing: -2.5px;
            font-weight: 900;
        }

        h1 span {
            color: var(--blue-light);
        }

        .hero-description {
            max-width: 610px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.95;
        }

        .hero-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 23px;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: rgba(14, 19, 29, .7);
            color: #aebbd0;
            font-size: 10px;
        }

        .hero-tag span {
            color: var(--blue-light);
        }

        /* MAIN LAYOUT */

        .layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: 22px;
            align-items: start;
            padding-bottom: 70px;
        }

        .form-card,
        .side-card {
            border: 1px solid var(--border);
            border-radius: 15px;
            background: linear-gradient(145deg, rgba(17, 24, 37, .97), rgba(10, 14, 22, .98));
            box-shadow: 0 18px 55px rgba(0, 0, 0, .15);
        }

        .form-card {
            padding: 30px;
        }

        .card-heading {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 27px;
        }

        .heading-icon {
            display: grid;
            flex: 0 0 43px;
            place-items: center;
            width: 43px;
            height: 43px;
            border: 1px solid rgba(52, 120, 255, .25);
            border-radius: 11px;
            background: rgba(52, 120, 255, .12);
            color: var(--blue-light);
            font-size: 21px;
        }

        .section-heading {
            margin: 1px 0 7px;
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -.4px;
        }

        .section-description {
            color: var(--muted);
            font-size: 11px;
            line-height: 1.8;
        }

        .required-note {
            margin-left: auto;
            padding-top: 5px;
            color: #8390a5;
            font-size: 10px;
            white-space: nowrap;
        }

        .required-note span {
            color: var(--blue-light);
        }

        /* ALERTS */

        .notice,
        .validation-summary {
            margin-bottom: 24px;
            padding: 15px 17px;
            border-radius: 10px;
            font-size: 12px;
            line-height: 1.8;
        }

        .notice {
            border: 1px solid rgba(101, 219, 160, .25);
            background: rgba(101, 219, 160, .07);
            color: #91edba;
        }

        .validation-summary {
            border: 1px solid rgba(255, 129, 129, .25);
            background: rgba(255, 129, 129, .07);
            color: #ffb0b0;
        }

        .validation-summary ul {
            padding-left: 18px;
            margin-top: 8px;
        }

        /* FORM FIELDS */

        .fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 21px 16px;
        }

        .field {
            min-width: 0;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 9px;
            color: #dce5f4;
            font-size: 11px;
            font-weight: 700;
        }

        .required {
            color: var(--blue-light);
        }

        .optional {
            color: #7c899e;
            font-weight: 400;
        }

        .control {
            position: relative;
        }

        input,
        textarea {
            display: block;
            width: 100%;
            border: 1px solid #29354a;
            border-radius: 9px;
            outline: none;
            background: #090e17;
            color: white;
            font-size: 12px;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        input {
            min-height: 48px;
            padding: 13px 14px;
        }

        textarea {
            min-height: 120px;
            padding: 14px;
            resize: vertical;
            line-height: 1.8;
        }

        input::placeholder,
        textarea::placeholder {
            color: #68768b;
        }

        input:hover,
        textarea:hover {
            border-color: #3b4c67;
        }

        input:focus,
        textarea:focus {
            border-color: var(--blue);
            background: #0b1220;
            box-shadow: 0 0 0 3px rgba(52, 120, 255, .12);
        }

        .error {
            margin-top: 7px;
            color: var(--danger);
            font-size: 11px;
            line-height: 1.6;
        }

        .field-hint {
            margin-top: 7px;
            color: #738198;
            font-size: 10px;
            line-height: 1.6;
        }

        /* SUBMIT */

        .submit-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            min-height: 53px;
            margin-top: 25px;
            border: 1px solid rgba(130, 172, 255, .25);
            border-radius: 9px;
            background: linear-gradient(100deg, #3478ff, #285ee5);
            box-shadow: 0 8px 25px rgba(52, 120, 255, .15);
            color: white;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s, filter .2s;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(52, 120, 255, .24);
            filter: brightness(1.08);
        }

        .submit-button:focus-visible {
            outline: 2px solid var(--blue-light);
            outline-offset: 3px;
        }

        .submit-arrow {
            font-size: 18px;
            transition: transform .2s;
        }

        .submit-button:hover .submit-arrow {
            transform: translate(3px, -2px);
        }

        .privacy {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            gap: 8px;
            margin-top: 14px;
            color: #7b879b;
            font-size: 10px;
            line-height: 1.8;
            text-align: center;
        }

        .privacy span {
            color: var(--green);
        }

        /* SIDEBAR */

        .side-card {
            position: sticky;
            top: 98px;
            padding: 24px;
        }

        .side-icon {
            display: grid;
            place-items: center;
            width: 47px;
            height: 47px;
            margin-bottom: 19px;
            border: 1px solid rgba(52, 120, 255, .27);
            border-radius: 12px;
            background: linear-gradient(145deg, rgba(52, 120, 255, .2), rgba(52, 120, 255, .05));
            color: var(--blue-light);
            font-size: 23px;
        }

        .side-card h2 {
            margin-bottom: 11px;
            font-size: 18px;
            line-height: 1.5;
        }

        .side-card > p {
            color: var(--muted);
            font-size: 11px;
            line-height: 1.9;
        }

        .side-divider {
            height: 1px;
            margin: 24px 0;
            background: var(--border);
        }

        .steps-title {
            margin-bottom: 19px;
            color: #c5d0e2;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 20px;
        }

        .step:last-child {
            margin-bottom: 0;
        }

        .step-number {
            display: grid;
            flex: 0 0 30px;
            place-items: center;
            width: 30px;
            height: 30px;
            border: 1px solid #293e60;
            border-radius: 8px;
            background: #121e31;
            color: var(--blue-light);
            font-size: 10px;
            font-weight: 800;
        }

        .step strong {
            display: block;
            margin: 2px 0 6px;
            font-size: 11px;
        }

        .step p {
            color: var(--muted);
            font-size: 10px;
            line-height: 1.8;
        }

        .side-footer {
            margin-top: 23px;
            padding: 13px;
            border: 1px solid rgba(52, 120, 255, .15);
            border-radius: 9px;
            background: rgba(52, 120, 255, .055);
            color: #a7badb;
            font-size: 10px;
            line-height: 1.8;
        }

        /* FOOTER */

        footer {
            padding: 22px 0;
            border-top: 1px solid var(--border);
            color: #6e7b91;
            font-size: 10px;
            text-align: center;
        }

        /* RESPONSIVE */

        @media (max-width: 850px) {
            .layout {
                grid-template-columns: minmax(0, 1fr) 250px;
                gap: 15px;
            }

            .form-card { padding: 23px 20px; }
            .side-card { padding: 20px; }
            .required-note { display: none; }
        }

        @media (max-width: 700px) {
            .container {
                width: calc(100% - 28px);
            }

            .nav { min-height: 68px; }
            .hero { padding-top: 45px; }

            .layout {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .side-card {
                position: static;
            }

            .side-icon { margin-bottom: 14px; }
        }

        @media (max-width: 460px) {
            .logo { font-size: 20px; }
            .logo-mark { width: 33px; height: 33px; }
            .back-link { padding: 9px; font-size: 10px; }

            h1 {
                font-size: clamp(34px, 9vw, 45px);
                letter-spacing: -1.7px;
            }

            .hero-description { font-size: 12px; }

            .form-card {
                padding: 21px 15px;
                border-radius: 12px;
            }

            .fields {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .field.full { grid-column: auto; }

            .section-heading { font-size: 17px; }

            .hero-tags { gap: 7px; }
            .hero-tag { font-size: 9px; }
        }
    </style>
</head>

<body>
    <header class="topbar">
        <div class="container nav">
            <a href="{{ route('home') }}" class="logo" aria-label="MotoHub მთავარი გვერდი">
                <span class="logo-mark">M</span>
                <span class="logo-name">MOTO<span>HUB</span></span>
            </a>

            <a href="{{ route('parts.index') }}" class="back-link">
                <span aria-hidden="true">←</span>
                ნაწილების კატალოგი
            </a>
        </div>
    </header>

    <div class="container">
        <section class="hero">
            <div class="eyebrow">MOTOHUB / PART REQUEST</div>

            <h1>
                ვერ იპოვე საჭირო<br>
                <span>ნაწილი?</span>
            </h1>

            <p class="hero-description">
                მოგვწერე, რა ნაწილი გჭირდება და რომელ მოტოციკლზე.
                მოგვაწოდე დეტალები, რათა შევძლოთ სასურველი ნაწილის
                მოძიება და ხელმისაწვდომობის გადამოწმება.
            </p>

            <div class="hero-tags">
                <div class="hero-tag"><span>↗</span> მარტივი მოთხოვნა</div>
                <div class="hero-tag"><span>✓</span> დეტალების დაზუსტება</div>
                <div class="hero-tag"><span>◎</span> პირდაპირი კონტაქტი</div>
            </div>
        </section>

        <main class="layout">
            <section class="form-card">
                @if (session('success'))
                    <div class="notice" role="status">
                        ✓ {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="validation-summary" role="alert">
                        <strong>ფორმაში აღმოჩნდა შეცდომები.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card-heading">
                    <div class="heading-icon" aria-hidden="true">↗</div>
                    <div>
                        <h2 class="section-heading">მოთხოვნის დეტალები</h2>
                        <p class="section-description">
                            შეავსე ქვემოთ მოცემული ველები.
                            ვარსკვლავით (*) მონიშნული ველები სავალდებულოა.
                        </p>
                    </div>
                </div>

                <form action="{{ route('part-requests.store') }}" method="POST">
                    @csrf

                    <div class="fields">
                        <div class="field full">
                            <label for="part_name">
                                რა ნაწილი გჭირდება? <span class="required">*</span>
                            </label>
                            <input
                                id="part_name"
                                name="part_name"
                                type="text"
                                value="{{ old('part_name') }}"
                                placeholder="მაგ. წინა ფარი, სამუხრუჭე ხუნდები"
                                maxlength="255"
                                autocomplete="off"
                                required
                            >
                            @error('part_name')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="motorcycle_brand">მოტოციკლის ბრენდი</label>
                            <input
                                id="motorcycle_brand"
                                name="motorcycle_brand"
                                type="text"
                                value="{{ old('motorcycle_brand') }}"
                                placeholder="მაგ. Yamaha"
                                maxlength="255"
                            >
                            @error('motorcycle_brand')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="motorcycle_model">მოტოციკლის მოდელი</label>
                            <input
                                id="motorcycle_model"
                                name="motorcycle_model"
                                type="text"
                                value="{{ old('motorcycle_model') }}"
                                placeholder="მაგ. YZF-R1"
                                maxlength="255"
                            >
                            @error('motorcycle_model')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="motorcycle_year">გამოშვების წელი</label>
                            <input
                                id="motorcycle_year"
                                name="motorcycle_year"
                                type="number"
                                value="{{ old('motorcycle_year') }}"
                                placeholder="მაგ. 2020"
                                min="1900"
                                max="2100"
                            >
                            @error('motorcycle_year')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="customer_name">
                                შენი სახელი <span class="required">*</span>
                            </label>
                            <input
                                id="customer_name"
                                name="customer_name"
                                type="text"
                                value="{{ old('customer_name') }}"
                                placeholder="სახელი და გვარი"
                                maxlength="255"
                                autocomplete="name"
                                required
                            >
                            @error('customer_name')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="phone">
                                ტელეფონის ნომერი <span class="required">*</span>
                            </label>
                            <input
                                id="phone"
                                name="phone"
                                type="tel"
                                value="{{ old('phone') }}"
                                placeholder="მაგ. 599 12 34 56"
                                maxlength="50"
                                autocomplete="tel"
                                required
                            >
                            @error('phone')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field full">
                            <label for="email">
                                ელფოსტა <span class="optional">(არასავალდებულო)</span>
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                placeholder="name@example.com"
                                maxlength="255"
                                autocomplete="email"
                            >
                            @error('email')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field full">
                            <label for="details">
                                დამატებითი ინფორმაცია
                                <span class="optional">(არასავალდებულო)</span>
                            </label>
                            <textarea
                                id="details"
                                name="details"
                                maxlength="5000"
                                placeholder="ნაწილის კოდი, ფერი, რაოდენობა ან სხვა მნიშვნელოვანი დეტალი..."
                            >{{ old('details') }}</textarea>
                            @error('details')
                                <div class="error">{{ $message }}</div>
                            @enderror
                            <div class="field-hint">
                                რაც უფრო მეტ დეტალს მიუთითებ, მით უფრო მარტივი იქნება საჭირო ნაწილის იდენტიფიცირება.
                            </div>
                        </div>
                    </div>

                    <button class="submit-button" type="submit">
                        მოთხოვნის გაგზავნა
                        <span class="submit-arrow" aria-hidden="true">↗</span>
                    </button>

                    <p class="privacy">
                        <span aria-hidden="true">✓</span>
                        შენი მონაცემები გამოიყენება ამ მოთხოვნასთან დაკავშირებული კომუნიკაციისთვის.
                    </p>
                </form>
            </section>

            <aside class="side-card">
                <div class="side-icon" aria-hidden="true">✳</div>

                <h2>შენი მოტოსთვის საჭირო ნაწილი.</h2>

                <p>
                    კატალოგში ვერ იპოვე სასურველი დეტალი?
                    გამოგვიგზავნე მოთხოვნა და მიუთითე მოტოციკლის მონაცემები.
                </p>

                <div class="side-divider"></div>

                <div class="steps-title">როგორ მუშაობს</div>

                <div class="step">
                    <div class="step-number">01</div>
                    <div>
                        <strong>შეავსე ფორმა</strong>
                        <p>მიუთითე ნაწილის სახელი და მოტოციკლის მოდელი.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">02</div>
                    <div>
                        <strong>მოთხოვნის შემოწმება</strong>
                        <p>მოთხოვნის მონაცემების მიხედვით შესაძლებელი იქნება ნაწილის მოძიება.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">03</div>
                    <div>
                        <strong>დაგიკავშირდებით</strong>
                        <p>საკონტაქტო მონაცემები დაგვეხმარება პასუხის მოწოდებაში.</p>
                    </div>
                </div>

                <div class="side-footer">
                    <strong>რჩევა:</strong> თუ იცი ნაწილის ზუსტი კოდი, მიუთითე დამატებითი ინფორმაციის ველში.
                </div>
            </aside>
        </main>
    </div>

    <footer>
        <div class="container">
            © {{ date('Y') }} MotoHub — შექმნილია მოტო ენთუზიასტებისთვის.
        </div>
    </footer>
</body>
</html>
