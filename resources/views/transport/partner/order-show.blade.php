<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>შეკვეთის დეტალები | MotoHub</title>
    @include('transport.partner.partials.styles')
</head>
<body>
<div class="wrap">
@include('transport.partner.partials.header')

    <section class="hero">
        <div class="eyebrow">MOTOTRANSPORT / ORDER DETAILS</div>
        <h1>შეკვეთა {{ $order->request_number }}</h1>
        <p>{{ $partner->name }} — შეკვეთის დეტალური ინფორმაცია.</p>

        <span class="tag
            @if($order->status === 'delivered') success
            @elseif($order->status === 'in_transit') warning
            @else blue
            @endif">
            @switch($order->status)
                @case('accepted') მიღებულია @break
                @case('in_transit') გზაშია @break
                @case('delivered') მიწოდებულია @break
            @endswitch
        </span>
    </section>

    <section class="panel">
        <h2>მოტოციკლის მონაცემები</h2>
        <div class="grid">
            <div class="field">
                <div class="label">მარკა და მოდელი</div>
                <div class="value">
                    {{ $order->motorcycle_brand }}
                    {{ $order->motorcycle_model }}
                </div>
            </div>
            <div class="field">
                <div class="label">გამოშვების წელი</div>
                <div class="value">{{ $order->motorcycle_year ?: 'მითითებული არ არის' }}</div>
            </div>
        </div>

        @if($order->motorcycle_details)
            <div class="field" style="margin-top:18px">
                <div class="label">მოტოციკლის დამატებითი დეტალები</div>
                <div class="value description">{{ $order->motorcycle_details }}</div>
            </div>
        @endif
    </section>

    <section class="panel">
        <h2>ტრანსპორტირების მარშრუტი</h2>
        <div class="route">
            <div class="place">
                <div class="place-label">აღების ადგილი</div>
                <div class="field">
                    <div class="label">ქალაქი</div>
                    <div class="value">{{ $order->pickup_city }}</div>
                </div>
                <div class="field">
                    <div class="label">მისამართი</div>
                    <div class="value">{{ $order->pickup_address ?: 'მითითებული არ არის' }}</div>
                </div>
            </div>

            <div class="place">
                <div class="place-label">ჩაბარების ადგილი</div>
                <div class="field">
                    <div class="label">ქალაქი</div>
                    <div class="value">{{ $order->destination_city }}</div>
                </div>
                <div class="field">
                    <div class="label">მისამართი</div>
                    <div class="value">{{ $order->destination_address ?: 'მითითებული არ არის' }}</div>
                </div>
            </div>
        </div>

        <div class="grid" style="margin-top:20px">
            <div class="field">
                <div class="label">სასურველი თარიღი</div>
                <div class="value">
                    {{ $order->preferred_date ? $order->preferred_date->format('d.m.Y') : 'მითითებული არ არის' }}
                </div>
            </div>
            <div class="field">
                <div class="label">შეკვეთის ნომერი</div>
                <div class="value">{{ $order->request_number }}</div>
            </div>
        </div>
    </section>

    <section class="panel">
        <h2>მომხმარებლის საკონტაქტო ინფორმაცია</h2>
        <div class="grid">
            <div class="field">
                <div class="label">სახელი</div>
                <div class="value">{{ $order->customer_name }}</div>
            </div>
            
<div class="contact-actions">
    @if($order->customer_phone)
        <a class="btn primary"
           href="tel:{{ $order->customer_phone }}">
            ☎ დარეკვა
        </a>
    @endif

    @if($order->customer_email)
        <a class="btn"
           href="mailto:{{ $order->customer_email }}">
            ✉ ელფოსტის გაგზავნა
        </a>
    @endif
</div>

            <div class="field">
                <div class="label">ტელეფონი</div>
                <div class="value">
                    @if($order->customer_phone)
                        <a class="blue" href="tel:{{ $order->customer_phone }}">
                            {{ $order->customer_phone }}
                        </a>
                    @else
                        მითითებული არ არის
                    @endif
                </div>
            </div>
            <div class="field">
                <div class="label">ელფოსტა</div>
                <div class="value">{{ $order->customer_email ?: 'მითითებული არ არის' }}</div>
            </div>
        </div>

        @if($order->customer_notes)
            <div class="field" style="margin-top:18px">
                <div class="label">მომხმარებლის შენიშვნა</div>
                <div class="value description">{{ $order->customer_notes }}</div>
            </div>
        @endif
    </section>

    <section class="panel">
        <h2>მიღებული შეთავაზება</h2>
        <div class="field">
            <div class="label">შეთანხმებული ფასი</div>
            <div class="price">{{ number_format((float) $offer->price, 2) }} ₾</div>
        
<section class="panel">
    <h2>შეკვეთის სტატუსის მართვა</h2>

    @if($order->status === 'accepted')
        <p class="muted">
            დაადასტურე, როდესაც მოტოციკლის ტრანსპორტირება დაიწყება.
        </p>

        <form method="POST"
              action="{{ route('transport.partner.requests.status', $order) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="in_transit">

            <button class="btn primary" type="submit">
                ტრანსპორტირება დაიწყო
            </button>
        </form>

    @elseif($order->status === 'in_transit')
        <p class="muted">
            მონიშნე შეკვეთა მიწოდებულად მხოლოდ ჩაბარების დასრულების შემდეგ.
        </p>

        <form method="POST"
              action="{{ route('transport.partner.requests.status', $order) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="delivered">

            <button class="btn primary" type="submit">
                მოტოციკლი ჩაბარებულია
            </button>
        </form>

    @elseif($order->status === 'delivered')
        <p class="success">
            ✓ შეკვეთა წარმატებით მიწოდებულია.
        </p>
    @endif

    @if($errors->any())
        <div class="warning" style="margin-top:16px">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
</section>
    
        </div>
        <div class="field">
            <div class="label">სავარაუდო ტრანსპორტირების ვადა</div>
            <div class="value">{{ $offer->estimated_days }} დღე</div>
        </div>

        @if($offer->message)
            <div class="field">
                <div class="label">შეთავაზების კომენტარი</div>
                <div class="value description">{{ $offer->message }}</div>
            </div>
        @endif
    </section>

    <a class="btn primary" href="{{ route('transport.partner.orders') }}">
        ← შეკვეთების ისტორიაში დაბრუნება
    </a>
</div>
</body>
</html>
