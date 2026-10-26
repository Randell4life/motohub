<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>მომსახურების დაჯავშნა | MotoHub</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #080d18;
            color: #f3f6fc;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        a { color: #83b5ff; text-decoration: none; }

        header {
            background: #0b1220;
            border-bottom: 1px solid #24334b;
            padding: 20px 0;
        }

        .container {
            width: min(760px, 92%);
            margin: auto;
        }

        .logo {
            color: white;
            font-size: 24px;
            font-weight: 900;
        }

        .logo span { color: #3285ff; }

        main { padding: 35px 0 60px; }

        .muted { color: #9ba9bf; }

        .panel {
            background: #101a2c;
            border: 1px solid #24334b;
            border-radius: 16px;
            padding: 25px;
            margin-top: 20px;
        }

        h1 { font-size: clamp(27px, 5vw, 38px); line-height: 1.2; }

        .price {
            font-size: 26px;
            font-weight: 800;
            color: #78adff;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .field { margin-bottom: 16px; }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 6px;
        }

        input, textarea {
            width: 100%;
            border: 1px solid #35445d;
            background: #080f1d;
            color: white;
            border-radius: 9px;
            padding: 12px;
            font: inherit;
        }

        input:focus, textarea:focus {
            outline: 2px solid #3285ff;
            border-color: transparent;
        }

        textarea { min-height: 110px; resize: vertical; }

        .button {
            width: 100%;
            border: 0;
            border-radius: 10px;
            padding: 14px;
            background: #3285ff;
            color: white;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
        }

        .errors {
            background: #351b27;
            border: 1px solid #7e3449;
            color: #ffc4d1;
            padding: 14px 18px;
            border-radius: 10px;
        }

        @media (max-width: 560px) {
            .grid { grid-template-columns: 1fr; gap: 0; }
            .panel { padding: 18px; }
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <a class="logo" href="{{ route('home') }}">Moto<span>Hub</span></a>
    </div>
</header>

<main class="container">
    <a href="{{ route('workshops.show', ['slug' => $workshop->slug]) }}">
        ← სახელოსნოს გვერდზე დაბრუნება
    </a>

    <h1>მომსახურების დაჯავშნა</h1>
    <p class="muted">{{ $workshop->name }}</p>

    <section class="panel">
        <div class="muted">არჩეული მომსახურება</div>
        <h2>{{ $service->title }}</h2>

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
    </section>

    @if($errors->any())
        <div class="errors">
            <strong>გთხოვ, გადაამოწმე ფორმა:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="panel"
          method="POST"
          action="{{ route('workshops.booking.store', ['slug' => $workshop->slug]) }}">
        @csrf

        <input type="hidden" name="service_id" value="{{ $service->id }}">

        <h2>საკონტაქტო ინფორმაცია</h2>

        <div class="field">
            <label for="customer_name">სახელი და გვარი *</label>
            <input id="customer_name" name="customer_name"
                   value="{{ old('customer_name') }}" required maxlength="255"
                   autocomplete="name">
        </div>

        <div class="grid">
            <div class="field">
                <label for="phone">ტელეფონი *</label>
                <input id="phone" name="phone" type="tel"
                       value="{{ old('phone') }}" required maxlength="40"
                       autocomplete="tel">
            </div>

            <div class="field">
                <label for="email">ელფოსტა</label>
                <input id="email" name="email" type="email"
                       value="{{ old('email') }}" maxlength="255"
                       autocomplete="email">
            </div>
        </div>

        <h2>მოტოციკლის მონაცემები</h2>

        <div class="grid">
            <div class="field">
                <label for="motorcycle_brand">მარკა</label>
                <input id="motorcycle_brand" name="motorcycle_brand"
                       value="{{ old('motorcycle_brand') }}" maxlength="100"
                       placeholder="მაგ. Yamaha">
            </div>

            <div class="field">
                <label for="motorcycle_model">მოდელი</label>
                <input id="motorcycle_model" name="motorcycle_model"
                       value="{{ old('motorcycle_model') }}" maxlength="100"
                       placeholder="მაგ. MT-07">
            </div>
        </div>

        <div class="grid">
            <div class="field">
                <label for="motorcycle_year">გამოშვების წელი</label>
                <input id="motorcycle_year" name="motorcycle_year"
                       type="number" min="1950" max="2100"
                       value="{{ old('motorcycle_year') }}">
            </div>

            <div class="field">
                <label for="preferred_date">სასურველი თარიღი</label>
                <input id="preferred_date" name="preferred_date"
                       type="date" min="{{ now()->toDateString() }}"
                       value="{{ old('preferred_date') }}">
            </div>
        </div>

        <div class="field">
            <label for="problem_description">პრობლემის აღწერა ან დამატებითი ინფორმაცია *</label>
            <textarea id="problem_description" name="problem_description"
                      required maxlength="3000"
                      placeholder="აღწერე, რა მომსახურება გჭირდება...">{{ old('problem_description') }}</textarea>
        </div>

        <button class="button" type="submit">დაჯავშნის მოთხოვნის გაგზავნა</button>

        <p class="muted">
            ეს არის მოთხოვნა სასურველ თარიღზე და არა დადასტურებული ჯავშანი.
            სახელოსნომ უნდა დაგიკავშირდეს დასადასტურებლად.
        </p>
    </form>
</main>
</body>
</html>
