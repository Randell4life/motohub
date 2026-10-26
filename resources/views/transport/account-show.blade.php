<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>შეკვეთის დეტალები | MotoHub</title>

    <style>
        :root {
            color-scheme: dark;
            --bg: #070a12;
            --panel: #0d1422;
            --panel2: #121d30;
            --blue: #1677ff;
            --light-blue: #42a5ff;
            --text: #f4f8ff;
            --muted: #98a8c2;
            --border: #243653;
            --green: #70e0a0;
            --yellow: #f5c76b;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Arial, sans-serif;
        }

        a { color: inherit; text-decoration: none; }

        .wrap {
            width: min(900px, 92%);
            margin: 36px auto 60px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .brand { font-size: 25px; font-weight: 900; }
        .brand span { color: var(--light-blue); }

        .panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 24px;
            margin-top: 20px;
        }

        .eyebrow {
            color: var(--light-blue);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        h1 { font-size: clamp(27px, 5vw, 38px); margin: 12px 0; }
        h2 { margin-top: 0; }

        .muted { color: var(--muted); }
        .row { margin: 15px 0; line-height: 1.7; }

        .button {
            display: inline-block;
            padding: 12px 16px;
            border-radius: 8px;
            background: var(--blue);
            color: white;
            border: 1px solid var(--blue);
            font-weight: 700;
        }

        .button-secondary {
            background: var(--panel2);
            border-color: var(--border);
        }

        .price {
            font-size: 30px;
            font-weight: 900;
            color: var(--light-blue);
            margin: 16px 0;
        }

        .status {
            display: inline-block;
            padding: 8px 12px;
            background: var(--panel2);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--light-blue);
            font-weight: 700;
        }

        .progress {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 24px;
        }

        .step {
            position: relative;
            padding: 16px 12px;
            border: 1px solid var(--border);
            background: #0a101c;
            border-radius: 10px;
            opacity: .55;
        }

        .step.active {
            opacity: 1;
            border-color: var(--blue);
            background: #101e34;
        }

        .step.complete {
            opacity: 1;
            border-color: #216943;
            background: #10251e;
        }

        .step-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--panel2);
            border: 1px solid var(--border);
            margin-bottom: 12px;
            font-weight: 800;
        }

        .step.active .step-number {
            background: var(--blue);
            border-color: var(--blue);
        }

        .step.complete .step-number {
            color: var(--green);
            border-color: var(--green);
        }

        .step-title { font-weight: 800; line-height: 1.4; }
        .step-desc { color: var(--muted); font-size: 12px; margin-top: 8px; line-height: 1.5; }

        .note {
            padding: 14px;
            border: 1px solid var(--border);
            background: var(--panel2);
            border-radius: 9px;
            margin-top: 18px;
            line-height: 1.6;
        }

        .success { color: var(--green); }
        .warning { color: var(--yellow); }

        @media (max-width: 600px) {
            .wrap { margin-top: 22px; }
            .panel { padding: 18px; }
            .progress { grid-template-columns: 1fr; }
            .step { display: flex; align-items: flex-start; gap: 12px; }
            .step-number { flex-shrink: 0; margin-bottom: 0; }
        }
    </style>
</head>

<body>
<div class="wrap">

    <header class="topbar">
        <a class="brand" href="{{ route('home') }}">MOTO<span>HUB</span></a>

        <a href="{{ route('account.transport.index') }}"
           class="button button-secondary">
            ← ჩემი შეკვეთები
        </a>
    </header>

    @php
        $statusLabels = [
            'pending' => 'შეთავაზებებს ელოდება',
            'quoted' => 'შეთავაზებები მიღებულია',
            'accepted' => 'გადამზიდავი შერჩეულია',
            'in_transit' => 'ტრანსპორტირების პროცესშია',
            'delivered' => 'მოტოციკლი ჩაბარებულია',
            'cancelled' => 'შეკვეთა გაუქმებულია',
        ];

        $currentStatus = $transportRequest->status;

        $progressStages = ['accepted', 'in_transit', 'delivered'];

        $currentStage = array_search($currentStatus, $progressStages, true);

        $progressIndex = $currentStage === false ? -1 : $currentStage;

        $isCancelled = $currentStatus === 'cancelled';
    @endphp

    <section class="panel">
        <div class="eyebrow">MOTOTRANSPORT / ORDER</div>

        <h1>შეკვეთის დეტალები</h1>

        <p class="muted">
            შეკვეთის ნომერი: {{ $transportRequest->request_number }}
        </p>

        <div class="row">
            <strong>მიმდინარე სტატუსი:</strong><br>
            <span class="status">
                {{ $statusLabels[$currentStatus] ?? $currentStatus }}
            </span>
        </div>

        @if($isCancelled)
            <div class="note warning">
                ეს შეკვეთა გაუქმებულია.
            </div>
        @else
            <div class="progress">
                @php
                    $steps = [
                        [
                            'title' => 'გადამზიდავი შერჩეულია',
                            'desc' => 'შეთავაზება მიღებულია.',
                        ],
                        [
                            'title' => 'ტრანსპორტირება',
                            'desc' => 'მოტოციკლი გზაშია.',
                        ],
                        [
                            'title' => 'ჩაბარება',
                            'desc' => 'მოტოციკლი დანიშნულების ადგილზეა.',
                        ],
                    ];
                @endphp

                @foreach($steps as $index => $step)
                    @php
                        $stepClass = '';

                        if ($progressIndex > $index) {
                            $stepClass = 'complete';
                        } elseif ($progressIndex === $index) {
                            $stepClass = $currentStatus === 'delivered'
                                ? 'complete'
                                : 'active';
                        }
                    @endphp

                    <div class="step {{ $stepClass }}">
                        <div class="step-number">
                            @if($progressIndex > $index || $currentStatus === 'delivered')
                                ✓
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>

                        <div>
                            <div class="step-title">{{ $step['title'] }}</div>
                            <div class="step-desc">{{ $step['desc'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(in_array($currentStatus, ['pending', 'quoted'], true))
                <div class="note">
                    <strong>შემდეგი ნაბიჯი</strong>
                    <p class="muted">
                        გადახედე შემოსულ შეთავაზებებს და აირჩიე შენთვის სასურველი გადამზიდავი.
                    </p>

                    <a href="{{ route('account.transport.index') }}"
                       class="button">
                        შეთავაზებების ნახვა
                    </a>
                </div>
            @elseif($currentStatus === 'accepted')
                <div class="note">
                    <strong>გადამზიდავი შერჩეულია</strong>
                    <p class="muted">
                        ელოდები ტრანსპორტირების დაწყებას. სტატუსი განახლდება გადამზიდავის მიერ.
                    </p>
                </div>
            @elseif($currentStatus === 'in_transit')
                <div class="note">
                    <strong>მოტოციკლი გზაშია</strong>
                    <p class="muted">
                        გადამზიდავი ტრანსპორტირებას ახორციელებს. ჩაბარების შემდეგ სტატუსი განახლდება.
                    </p>
                </div>
            @elseif($currentStatus === 'delivered')
                <div class="note success">
                    <strong>✓ შეკვეთა დასრულებულია</strong>
                    <p>მოტოციკლი ჩაბარებულად არის მონიშნული.</p>
                </div>
            @endif
        @endif

        <div class="row">
            <strong>მოტოციკლი:</strong>
            {{ $transportRequest->motorcycle_brand }}
            {{ $transportRequest->motorcycle_model }}

            @if($transportRequest->motorcycle_year)
                ({{ $transportRequest->motorcycle_year }})
            @endif
        </div>

        <div class="row">
            <strong>აღების ადგილი:</strong>
            {{ $transportRequest->pickup_city }}

            @if($transportRequest->pickup_address)
                — {{ $transportRequest->pickup_address }}
            @endif
        </div>

        <div class="row">
            <strong>დანიშნულების ადგილი:</strong>
            {{ $transportRequest->destination_city }}

            @if($transportRequest->destination_address)
                — {{ $transportRequest->destination_address }}
            @endif
        </div>

        @if($transportRequest->preferred_date)
            <div class="row">
                <strong>სასურველი თარიღი:</strong>
                {{ $transportRequest->preferred_date->format('d.m.Y') }}
            </div>
        @endif

        @if($transportRequest->motorcycle_details)
            <div class="row">
                <strong>მოტოციკლის დეტალები:</strong>
                <p>{{ $transportRequest->motorcycle_details }}</p>
            </div>
        @endif

        @if($transportRequest->customer_notes)
            <div class="row">
                <strong>შენიშვნები:</strong>
                <p>{{ $transportRequest->customer_notes }}</p>
            </div>
        @endif
    </section>

    <section class="panel">
        <h2>შერჩეული გადამზიდავი</h2>

        @if($acceptedOffer)
            <div class="price">
                {{ number_format((float) $acceptedOffer->price, 2) }} ₾
            </div>

            <p class="muted">
                სავარაუდო ვადა: {{ $acceptedOffer->estimated_days }} დღე
            </p>

            <div class="row">
                <strong>კომპანია:</strong>
                {{ $acceptedOffer->partner?->name ?? 'გადამზიდავი' }}
            </div>

            @if($acceptedOffer->message)
                <div class="row">
                    <strong>შეტყობინება:</strong>
                    <p>{{ $acceptedOffer->message }}</p>
                </div>
            @endif

            @if($acceptedOffer->partner?->phone)
                <div class="row">
                    <strong>ტელეფონი:</strong>
                    <a href="tel:{{ $acceptedOffer->partner->phone }}"
                       style="color:#42a5ff">
                        {{ $acceptedOffer->partner->phone }}
                    </a>
                </div>
            @else
                <p class="muted">
                    გადამზიდავს საკონტაქტო ტელეფონი ჯერ არ აქვს მითითებული.
                </p>
            @endif
        @else
            <p class="muted">
                გადამზიდავი ჯერ არ აგირჩევია. შეთავაზებების მიღებისა და შედარების შემდეგ შეძლებ არჩევას.
            </p>

            <a href="{{ route('account.transport.index') }}"
               class="button">
                შეთავაზებების ნახვა
            </a>
        @endif
    </section>

</div>
</body>
</html>
