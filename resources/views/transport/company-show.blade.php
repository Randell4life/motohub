<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company->name }} | MotoHub</title>
    <meta name="description" content="{{ $company->description ?: $company->name . ' — მოტოციკლების ტრანსპორტირება' }}">
    <style>
        :root {
            --bg: #070a12;
            --panel: #0d1422;
            --panel2: #121d30;
            --blue: #1677ff;
            --light-blue: #42a5ff;
            --text: #f4f8ff;
            --muted: #98a8c2;
            --border: #243653;
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
            width: min(1050px, 92%);
            margin: auto;
        }

        header {
            border-bottom: 1px solid var(--border);
            background: #090e19;
        }

        .nav {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -1px;
        }

        .logo span { color: var(--light-blue); }

        .back {
            color: var(--muted);
            font-size: 14px;
        }

        main { padding: 54px 0 80px; }

        .eyebrow {
            color: var(--light-blue);
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .profile {
            margin-top: 18px;
            padding: 34px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: linear-gradient(135deg, var(--panel2), var(--panel));
        }

        .company-heading {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .avatar {
            width: 100px;
            height: 100px;
            flex: 0 0 100px;
            display: grid;
            place-items: center;
            overflow: hidden;
            border-radius: 18px;
            border: 1px solid var(--border);
            background: #080d17;
            color: var(--light-blue);
            font-size: 36px;
            font-weight: 900;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        h1 {
            margin: 5px 0;
            font-size: clamp(27px, 5vw, 42px);
            line-height: 1.2;
            overflow-wrap: anywhere;
        }

        .verified {
            display: inline-block;
            margin-top: 8px;
            color: #83c4ff;
            font-size: 13px;
        }

        .description {
            margin-top: 28px;
            color: #c3cee0;
            white-space: pre-line;
        }

        .details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 28px;
        }

        .detail {
            padding: 18px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: rgba(7, 10, 18, .55);
            overflow-wrap: anywhere;
        }

        .detail small {
            display: block;
            margin-bottom: 5px;
            color: var(--muted);
        }

        .detail strong { font-size: 15px; }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 28px;
        }

        .button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            min-height: 48px;
            padding: 12px 20px;
            border: 1px solid var(--blue);
            border-radius: 9px;
            background: var(--blue);
            color: white;
            font-weight: 800;
            transition: .2s;
        }

        .button:hover {
            background: var(--light-blue);
            border-color: var(--light-blue);
            color: #07101e;
        }

        .button.secondary {
            background: transparent;
            border-color: var(--border);
        }

        .button.secondary:hover {
            border-color: var(--light-blue);
            color: var(--light-blue);
            background: transparent;
        }

        footer {
            padding: 25px 0;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 13px;
        }

        @media (max-width: 600px) {
            main { padding-top: 30px; }
            .profile { padding: 22px; }
            .company-heading { align-items: flex-start; gap: 15px; }
            .avatar { width: 70px; height: 70px; flex-basis: 70px; font-size: 27px; }
            .details { grid-template-columns: 1fr; }
            .actions .button { width: 100%; }
        }
    </style>
</head>
<body>
<header>
    <div class="container nav">
        <a class="logo" href="{{ route('transport.index') }}">Moto<span>Hub</span></a>
        <a class="back" href="{{ route('transport.companies') }}">← კომპანიების კატალოგი</a>
    </div>
</header>

<main class="container">
    <div class="eyebrow">MotoHub / Transport Partners</div>

    <section class="profile">
        <div class="company-heading">
            <div class="avatar">
                @if ($company->logo_path)
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::url($company->logo_path) }}"
                        alt="{{ $company->name }}"
                    >
                @else
                    {{ mb_strtoupper(mb_substr($company->name, 0, 1)) }}
                @endif
            </div>

            <div>
                <h1>{{ $company->name }}</h1>
                <div class="verified">✓ დამტკიცებული სატრანსპორტო კომპანია</div>
            </div>
        </div>

        @if ($company->description)
            <div class="description">{{ $company->description }}</div>
        @else
            <div class="description">
                დაუკავშირდი კომპანიას მოტოციკლის ტრანსპორტირების პირობებისა და ფასის გასარკვევად.
            </div>
        @endif

        <div class="details">
            @if ($company->address)
                <div class="detail">
                    <small>მისამართი</small>
                    <strong>{{ $company->address }}</strong>
                </div>
            @endif

            @if ($company->phone)
                <div class="detail">
                    <small>ტელეფონი</small>
                    <strong>{{ $company->phone }}</strong>
                </div>
            @endif

            @if ($company->email)
                <div class="detail">
                    <small>ელფოსტა</small>
                    <strong>{{ $company->email }}</strong>
                </div>
            @endif
        </div>

        <div class="actions">
            @if ($company->phone)
                <a class="button" href="tel:{{ $company->phone }}">დარეკვა</a>
            @endif

            @if ($company->email)
                <a class="button secondary" href="mailto:{{ $company->email }}">
                    ელფოსტის გაგზავნა
                </a>
            @endif

<a class="button secondary"
   href="{{ route('transport.create', ['partner' => $company->id]) }}">
    ამ კომპანიისგან ფასის მიღება →
</a>
        </div>
    </section>
</main>

<footer>
    <div class="container">© {{ date('Y') }} MotoHub — მოტოციკლების ტრანსპორტირება</div>
</footer>
</body>
</html>
