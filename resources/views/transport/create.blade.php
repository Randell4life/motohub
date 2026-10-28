<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ტრანსპორტირების მოთხოვნა | MotoHub</title>
    <style>
        :root {
            --bg: #070a12;
            --panel: #0d1422;
            --blue: #1677ff;
            --light: #42a5ff;
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
        }

        a { color: inherit; text-decoration: none; }

        header {
            border-bottom: 1px solid var(--border);
            padding: 22px max(5%, calc((100% - 1000px) / 2));
        }

        .logo { font-size: 23px; font-weight: 900; }
        .logo span { color: var(--light); }

        main { max-width: 1000px; margin: auto; padding: 45px 20px 80px; }

        .eyebrow { color: var(--light); font-size: 12px; font-weight: 800; letter-spacing: 3px; }

        h1 { font-size: clamp(34px, 5vw, 52px); margin: 15px 0; letter-spacing: -1px; }
        .intro { color: var(--muted); line-height: 1.8; max-width: 650px; }

        form { background: var(--panel); border: 1px solid var(--border); padding: 28px; margin-top: 30px; }

        h2 { font-size: 17px; margin: 30px 0 18px; }
        h2:first-child { margin-top: 0; }

        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }

        .field { display: flex; flex-direction: column; gap: 8px; }
        .full { grid-column: 1 / -1; }

        label { font-size: 13px; color: #d8e4f8; }

        input, textarea {
            width: 100%;
            background: #080e19;
            border: 1px solid var(--border);
            color: white;
            padding: 14px;
            font: inherit;
            outline: none;
        }

        textarea { min-height: 100px; resize: vertical; }
        input:focus, textarea:focus { border-color: var(--light); }

        .errors {
            padding: 15px;
            background: #35151d;
            border: 1px solid #8b3145;
            color: #ffc5d0;
            margin-bottom: 20px;
        }

        .hint { color: var(--muted); font-size: 12px; line-height: 1.6; }

        .actions { display: flex; align-items: center; gap: 20px; margin-top: 28px; flex-wrap: wrap; }

        button {
            background: var(--blue);
            border: 1px solid var(--blue);
            color: white;
            padding: 15px 22px;
            font-weight: 800;
            cursor: pointer;
        }

        button:hover { background: var(--light); color: #06101f; }
        .back { color: var(--muted); }

        footer { border-top: 1px solid var(--border); padding: 25px; text-align: center; color: var(--muted); font-size: 13px; }

        @media (max-width: 600px) {
            .grid { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
            form { padding: 20px; }
        }
    </style>
</head>
<body>
<header>
    <a class="logo" href="{{ route('home') }}">MOTO<span>HUB</span></a><nav style="display:flex;gap:14px;flex-wrap:wrap"><a href="{{ route('motorcycles.index') }}">მოტოციკლები</a><a href="{{ route('rentals.index') }}">გაქირავება</a><a href="{{ route('parts.index') }}">ნაწილები</a><a href="{{ route('service-centers.index') }}">სახელოსნოები</a><a href="{{ route('transport.index') }}">ტრანსპორტი</a></nav>
</header>

<main>
    <div class="eyebrow">MOTOTRANSPORT / NEW REQUEST</div>
    <h1>მოითხოვე მოტოციკლის გადაზიდვა.</h1>
    <p class="intro">
        შეავსე ინფორმაცია მოტოციკლსა და მარშრუტზე.
        მოთხოვნის გაგზავნის შემდეგ შესაძლებელი იქნება გადამზიდავების შეთავაზებების მიღება.
    </p>

    @if ($errors->any())
        <div class="errors">
            <strong>გთხოვ, შეამოწმე მონიშნული ველები:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<form method="POST" action="{{ route('transport.store') }}">
    @csrf

    @if ($company)
        <input type="hidden" name="partner_id" value="{{ $company->id }}">

        <div style="padding:15px; margin-bottom:20px; background:#121d30; border:1px solid #1677ff; color:#f4f8ff;">
            მოთხოვნა იგზავნება კომპანიასთან:
            <strong>{{ $company->name }}</strong>
        </div>
    @endif

        <h2>01 / მოტოციკლის ინფორმაცია</h2>
        <div class="grid">
            <div class="field">
                <label for="motorcycle_brand">ბრენდი *</label>
                <input id="motorcycle_brand" name="motorcycle_brand" required maxlength="100"
                       value="{{ old('motorcycle_brand') }}" placeholder="მაგ. Yamaha">
            </div>

            <div class="field">
                <label for="motorcycle_model">მოდელი *</label>
                <input id="motorcycle_model" name="motorcycle_model" required maxlength="100"
                       value="{{ old('motorcycle_model') }}" placeholder="მაგ. YZF-R1">
            </div>

            <div class="field">
                <label for="motorcycle_year">გამოშვების წელი</label>
                <input id="motorcycle_year" type="number" name="motorcycle_year"
                       min="1900" max="{{ date('Y') + 1 }}" value="{{ old('motorcycle_year') }}"
                       placeholder="{{ date('Y') }}">
            </div>

            <div class="field">
                <label for="motorcycle_details">დამატებითი ინფორმაცია</label>
                <input id="motorcycle_details" name="motorcycle_details" maxlength="3000"
                       value="{{ old('motorcycle_details') }}" placeholder="წონა, მდგომარეობა და სხვა">
            </div>
        </div>

        <h2>02 / მარშრუტი</h2>
        <div class="grid">
            <div class="field">
                <label for="pickup_city">საწყისი ქალაქი *</label>
                <input id="pickup_city" name="pickup_city" required maxlength="150"
                       value="{{ old('pickup_city') }}" placeholder="მაგ. თბილისი">
            </div>

            <div class="field">
                <label for="destination_city">დანიშნულების ქალაქი *</label>
                <input id="destination_city" name="destination_city" required maxlength="150"
                       value="{{ old('destination_city') }}" placeholder="მაგ. ბათუმი">
            </div>

            <div class="field">
                <label for="pickup_address">მისამართი აღებისას</label>
                <input id="pickup_address" name="pickup_address" maxlength="255"
                       value="{{ old('pickup_address') }}" placeholder="ქუჩა ან ლოკაცია">
            </div>

            <div class="field">
                <label for="destination_address">მისამართი ჩაბარებისას</label>
                <input id="destination_address" name="destination_address" maxlength="255"
                       value="{{ old('destination_address') }}" placeholder="ქუჩა ან ლოკაცია">
            </div>

            <div class="field">
                <label for="preferred_date">სასურველი თარიღი</label>
                <input id="preferred_date" type="date" name="preferred_date"
                       min="{{ date('Y-m-d') }}" value="{{ old('preferred_date') }}">
            </div>
        </div>

        <h2>03 / საკონტაქტო ინფორმაცია</h2>
        <div class="grid">
            <div class="field">
                <label for="customer_name">სახელი და გვარი *</label>
                <input id="customer_name" name="customer_name" required maxlength="150"
                       value="{{ old('customer_name', auth()->user()->name ?? '') }}">
            </div>

            <div class="field">
                <label for="customer_phone">ტელეფონის ნომერი *</label>
                <input id="customer_phone" type="tel" name="customer_phone" required maxlength="40"
                       value="{{ old('customer_phone', auth()->user()->phone ?? '') }}">
            </div>

            <div class="field full">
                <label for="customer_email">ელფოსტა</label>
                <input id="customer_email" type="email" name="customer_email" maxlength="255"
                       value="{{ old('customer_email', auth()->user()->email ?? '') }}">
            </div>

            <div class="field full">
                <label for="customer_notes">დამატებითი მოთხოვნები</label>
                <textarea id="customer_notes" name="customer_notes" maxlength="3000"
                          placeholder="მაგ. მოტოციკლი არ იქოქება ან საჭიროა დახურული ტრანსპორტი">{{ old('customer_notes') }}</textarea>
            </div>
        </div>

        <p class="hint">* ვარსკვლავით მონიშნული ველები სავალდებულოა.</p>

        <div class="actions">
            <button type="submit">მოთხოვნის გაგზავნა →</button>
            <a class="back" href="{{ route('transport.index') }}">უკან დაბრუნება</a>
        </div>
    </form>
</main>

<footer>MotoHub © {{ date('Y') }}</footer>
</body>
</html>
