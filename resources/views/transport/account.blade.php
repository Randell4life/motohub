<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ჩემი ტრანსპორტირება | MotoHub</title>

    <style>

:root {
    --bg: #070a12;
    --panel: #0d1422;
    --panel2: #121d30;
    --blue: #1677ff;
    --light: #55b4ff;
    --text: #f4f8ff;
    --muted: #98a8c2;
    --border: #243653;
    --green: #65e6aa;
}

* {
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    margin: 0;
    min-height: 100vh;
    color: var(--text);
    background:
        radial-gradient(ellipse at 90% 0%, rgba(22,119,255,.13), transparent 30%),
        radial-gradient(ellipse at 0% 55%, rgba(22,119,255,.05), transparent 32%),
        var(--bg);
    font-family: Arial, "Noto Sans Georgian", sans-serif;
    -webkit-font-smoothing: antialiased;
}

a {
    color: var(--light);
    text-decoration: none;
}

button,
input {
    font: inherit;
}

.wrap {
    width: min(1120px, 100%);
    margin: 0 auto;
    padding: 40px 24px 80px;
}

/* Header */

.top {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 25px;
    padding: clamp(26px, 5vw, 48px);
    overflow: hidden;
    border: 1px solid #29466d;
    background:
        linear-gradient(115deg, rgba(13,20,34,.99) 0%, rgba(13,20,34,.97) 58%, rgba(12,35,70,.88) 100%);
    box-shadow: 0 24px 70px rgba(0,0,0,.2);
}

.top::before {
    content: "MOTO / TRANSPORT";
    position: absolute;
    right: -10px;
    bottom: -18px;
    color: rgba(85,180,255,.055);
    font-size: clamp(42px, 8vw, 100px);
    font-weight: 950;
    letter-spacing: -4px;
    white-space: nowrap;
    transform: skew(-10deg);
    pointer-events: none;
}

.top > * {
    position: relative;
    z-index: 1;
}

.top > div {
    min-width: 0;
}

.top > div > a:first-child {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--muted);
    font-size: 12px;
    font-weight: 800;
    transition: color .2s ease;
}

.top > div > a:first-child:hover {
    color: var(--light);
}

h1 {
    margin: 23px 0 10px;
    font-size: clamp(32px, 5vw, 53px);
    font-weight: 950;
    line-height: 1.12;
    letter-spacing: -2px;
}

h2 {
    margin: 0 0 12px;
    font-size: 21px;
    line-height: 1.5;
    letter-spacing: -.5px;
}

h3 {
    margin: 27px 0 14px;
    font-size: 15px;
    font-weight: 900;
}

p {
    line-height: 1.8;
}

.muted {
    color: var(--muted);
    font-size: 13px;
    line-height: 1.8;
}

/* Buttons */

.button {
    position: relative;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    min-height: 47px;
    padding: 13px 19px;
    border: 1px solid var(--blue);
    border-radius: 0;
    background: var(--blue);
    color: #fff;
    font-size: 13px;
    font-weight: 900;
    cursor: pointer;
    text-align: center;
    transition:
        transform .2s ease,
        background .2s ease,
        border-color .2s ease,
        box-shadow .2s ease;
}

.button:hover {
    transform: translateY(-2px);
    border-color: var(--light);
    background: var(--light);
    color: #06101f;
    box-shadow: 0 9px 28px rgba(22,119,255,.2);
}

.button:focus-visible,
a:focus-visible {
    outline: 2px solid var(--light);
    outline-offset: 4px;
}

.button.secondary {
    border-color: var(--border);
    background: rgba(18,29,48,.75);
    color: var(--text);
}

.button.secondary:hover {
    border-color: var(--light);
    background: rgba(22,119,255,.12);
    color: var(--light);
    box-shadow: none;
}

/* Feedback messages */

.success {
    margin: 20px 0;
    padding: 15px 18px;
    border: 1px solid #286747;
    border-radius: 0;
    background: rgba(16,41,31,.85);
    color: var(--green);
    font-size: 13px;
    line-height: 1.8;
    overflow-wrap: anywhere;
}

/* Request card */

.request {
    position: relative;
    margin-top: 22px;
    padding: clamp(20px, 3vw, 30px);
    overflow: hidden;
    border: 1px solid var(--border);
    border-radius: 0;
    background:
        linear-gradient(145deg, rgba(17,29,48,.98), rgba(8,14,24,.98));
    transition: border-color .22s ease, box-shadow .22s ease;
}

.request::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 58px;
    height: 3px;
    background: var(--blue);
    transition: width .25s ease;
}

.request::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    width: 56px;
    height: 56px;
    background: linear-gradient(
        135deg,
        transparent 48%,
        rgba(66,165,255,.1) 49%,
        rgba(66,165,255,.1) 51%,
        transparent 52%
    );
    pointer-events: none;
}

.request:hover {
    border-color: #355a89;
    box-shadow: 0 18px 45px rgba(0,0,0,.18);
}

.request:hover::before {
    width: 100%;
}

.request-head,
.offer-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 18px;
    flex-wrap: wrap;
}

.request-head > div,
.offer-head > div {
    min-width: 0;
}

.request-head h2 {
    margin-bottom: 7px;
    overflow-wrap: anywhere;
}

.request-head .muted {
    font-size: 11px;
    letter-spacing: .2px;
    overflow-wrap: anywhere;
}

/* Status labels */

.status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    max-width: 100%;
    padding: 8px 11px;
    border: 1px solid rgba(66,165,255,.25);
    border-radius: 0;
    background: rgba(22,119,255,.09);
    color: var(--light);
    font-size: 11px;
    font-weight: 800;
    line-height: 1.6;
    overflow-wrap: anywhere;
}

/* Route panel */

.request > p:first-of-type {
    margin-top: 25px;
    padding: 17px;
    border-left: 2px solid var(--blue);
    background: rgba(4,10,19,.55);
    font-size: 16px;
    font-weight: 800;
    overflow-wrap: anywhere;
}

.request > p:first-of-type strong {
    display: block;
    margin-bottom: 7px;
    color: var(--muted);
    font-size: 10px;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.request > p:nth-of-type(2) {
    color: var(--muted);
    font-size: 13px;
}

.request > p:nth-of-type(2) strong {
    color: var(--text);
}

/* Offers */

.offer {
    position: relative;
    margin-top: 13px;
    padding: 21px;
    border: 1px solid #2a3e5b;
    border-radius: 0;
    background: linear-gradient(135deg, rgba(18,29,48,.95), rgba(9,16,28,.96));
    transition: border-color .2s ease, background .2s ease;
}

.offer:hover {
    border-color: #3b6394;
    background: linear-gradient(135deg, rgba(20,35,57,.98), rgba(9,16,28,.98));
}

.price {
    margin-bottom: 8px;
    color: var(--light);
    font-size: clamp(26px, 4vw, 34px);
    font-weight: 950;
    letter-spacing: -1px;
    line-height: 1.2;
}

.offer p {
    margin: 7px 0;
    color: var(--muted);
    font-size: 13px;
}

.offer p strong {
    color: var(--text);
}

.offer-head {
    margin-bottom: 15px;
}

.offer form {
    margin-top: 18px;
}

.offer form .button {
    width: 100%;
    max-width: 250px;
}

.offer > p {
    padding-top: 12px;
    border-top: 1px solid rgba(36,54,83,.7);
    overflow-wrap: anywhere;
}

/* Request actions */

.request-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
}

/* Empty state */

.empty {
    position: relative;
    margin-top: 24px;
    padding: clamp(32px, 6vw, 60px) 22px;
    overflow: hidden;
    border: 1px dashed #31517b;
    border-radius: 0;
    background:
        radial-gradient(ellipse at 50% 0%, rgba(22,119,255,.1), transparent 65%),
        var(--panel);
    text-align: center;
}

.empty h2 {
    margin-bottom: 10px;
    font-size: clamp(20px, 4vw, 27px);
}

.empty p {
    max-width: 480px;
    margin: 0 auto 23px;
}

/* Pagination */

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 30px;
    overflow-x: auto;
}

.pagination nav {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 5px;
}

.pagination a,
.pagination span {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    min-width: 38px;
    min-height: 38px;
    padding: 8px 11px;
    border: 1px solid var(--border);
    border-radius: 0;
    background: var(--panel);
    color: var(--muted);
    font-size: 12px;
}

.pagination a:hover {
    border-color: var(--blue);
    color: var(--light);
}

.pagination span[aria-current="page"] span {
    border-color: var(--blue);
    background: var(--blue);
    color: #fff;
}

/* Responsive */

@media (max-width: 700px) {
    .wrap {
        padding: 20px 15px 48px;
    }

    .top {
        align-items: stretch;
        flex-direction: column;
        padding: 26px 21px;
    }

    .top > .button {
        width: 100%;
    }

    .top::before {
        right: -15px;
        bottom: 48px;
        font-size: 43px;
        letter-spacing: -2px;
    }

    h1 {
        font-size: clamp(31px, 8vw, 43px);
        letter-spacing: -1.5px;
    }

    .request {
        padding: 20px 16px;
    }

    .request-head,
    .offer-head {
        align-items: flex-start;
        flex-direction: column;
        gap: 12px;
    }

    .request > p:first-of-type {
        padding: 14px;
        font-size: 14px;
        line-height: 1.9;
    }

    .offer {
        padding: 17px 14px;
    }

    .request-actions {
        flex-direction: column;
    }

    .request-actions .button {
        width: 100%;
    }

    .offer form .button {
        max-width: none;
    }
}

@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
    }
}


        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Arial, sans-serif;
        }

        a {
            color: #42a5ff;
            text-decoration: none;
        }

        .wrap {
            width: min(1100px, 92%);
            margin: 0 auto;
            padding: 36px 0 60px;
        }

        .top, .request-head, .offer-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        h1 {
            font-size: clamp(28px, 5vw, 42px);
            margin-bottom: 8px;
        }

        h2 { font-size: 21px; }

        .muted { color: var(--muted); }

        .button {
            display: inline-block;
            border: 0;
            border-radius: 8px;
            padding: 12px 16px;
            background: var(--blue);
            color: white;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }

        .button:hover {
            background: #3288ff;
        }

        .button.secondary {
            background: var(--panel2);
            border: 1px solid var(--border);
        }

        .button.secondary:hover {
            border-color: var(--blue);
        }

        .request {
            margin-top: 24px;
            padding: 24px;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        .offer {
            margin-top: 14px;
            padding: 18px;
            background: var(--panel2);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .price {
            font-size: 25px;
            font-weight: bold;
        }

        .status {
            display: inline-block;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 6px 10px;
            font-size: 13px;
        }

        .success {
            background: #10291f;
            border: 1px solid #286747;
            padding: 14px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .empty {
            text-align: center;
            padding: 36px 18px;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 14px;
            margin-top: 24px;
        }

        .request-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid var(--border);
        }

        .pagination { margin-top: 24px; }

        @media (max-width: 600px) {
            .request { padding: 16px; }
            .offer { padding: 14px; }
            .request-actions .button { text-align: center; }
        }
    </style>
</head>
<body>
<div class="wrap">

    <div class="top">
        <div>
            <a href="{{ route('home') }}">← MotoHub</a>
            <h1>ჩემი ტრანსპორტირება</h1>
            <p class="muted">მოთხოვნები და გადამზიდავების შეთავაზებები</p>
        </div>

        <a class="button" href="{{ route('transport.create') }}">
            + ახალი მოთხოვნა
        </a>
    </div>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="success">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @forelse ($requests as $transportRequest)
        <section class="request">

            <div class="request-head">
                <div>
                    <h2>
                        {{ $transportRequest->motorcycle_brand }}
                        {{ $transportRequest->motorcycle_model }}

                        @if ($transportRequest->motorcycle_year)
                            · {{ $transportRequest->motorcycle_year }}
                        @endif
                    </h2>

                    <div class="muted">
                        მოთხოვნა: {{ $transportRequest->request_number }}
                    </div>
                </div>

                <span class="status">
                    სტატუსი: {{ $transportRequest->status }}
                </span>
            </div>

            <p>
                <strong>მარშრუტი:</strong>
                {{ $transportRequest->pickup_city }}
                →
                {{ $transportRequest->destination_city }}
            </p>

            @if ($transportRequest->preferred_date)
                <p>
                    <strong>სასურველი თარიღი:</strong>
                    {{ $transportRequest->preferred_date->format('d.m.Y') }}
                </p>
            @endif

            <h3>მიღებული შეთავაზებები</h3>

            @forelse ($transportRequest->offers as $offer)
                <div class="offer">

                    <div class="offer-head">
                        <div>
                            <div class="price">
                                {{ number_format((float) $offer->price, 2) }} ₾
                            </div>

                            <p>
                                მიწოდების ვადა:
                                {{ $offer->estimated_days }} დღე
                            </p>

                            <p>
                                <strong>გადამზიდავი:</strong>
                                {{ $offer->partner?->name ?? $offer->user?->name ?? 'გადამზიდავი' }}
                            </p>
                        </div>

                        <span class="status">
                            @switch($offer->status)
                                @case('pending')
                                    მოლოდინში
                                    @break

                                @case('accepted')
                                    მიღებულია
                                    @break

                                @case('rejected')
                                    უარყოფილია
                                    @break

                                @default
                                    {{ $offer->status }}
                            @endswitch
                        </span>
                    </div>

                    @if ($offer->message)
                        <p>{{ $offer->message }}</p>
                    @endif

                    @if (
                        in_array($transportRequest->status, ['pending', 'quoted'], true)
                        && $offer->status === 'pending'
                    )
                        <form
                            method="POST"
                            action="{{ route('account.transport.offers.accept', $offer) }}"
                            onsubmit="return confirm('ნამდვილად გსურს ამ შეთავაზების მიღება?');"
                        >
                            @csrf

                            <button class="button" type="submit">
                                შეთავაზების მიღება
                            </button>
                        </form>
                    @endif

                </div>
            @empty
                <p class="muted">
                    ამ მოთხოვნაზე შეთავაზებები ჯერ არ მიგიღია.
                </p>
            @endforelse

            {{-- შეკვეთის დეტალების ღილაკი --}}
            <div class="request-actions">
                <a
                    href="{{ route('account.transport.show', $transportRequest) }}"
                    class="button secondary"
                >
                    შეკვეთის დეტალები →
                </a>
            </div>

        </section>
    @empty
        <div class="empty">
            <h2>ტრანსპორტირების მოთხოვნები ჯერ არ გაქვს</h2>

            <p class="muted">
                შექმენი მოთხოვნა და მიიღე შეთავაზებები გადამზიდავებისგან.
            </p>

            <a class="button" href="{{ route('transport.create') }}">
                მოთხოვნის შექმნა
            </a>
        </div>
    @endforelse

    <div class="pagination">
        {{ $requests->links() }}
    </div>

</div>
</body>
</html>