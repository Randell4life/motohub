<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>შეკვეთების ისტორია | MotoHub</title>
    @include('transport.partner.partials.styles')
</head>
<body>
<div class="wrap">
@include('transport.partner.partials.header')

    <section class="hero">
        <div class="eyebrow">MOTOTRANSPORT / ORDERS</div>
        <h1>შეკვეთების ისტორია</h1>
        <p>{{ $partner->name }} — მომხმარებლის მიერ მიღებული შეთავაზებების შეკვეთები.</p>
    </section>

    @if(session('success'))
        <div class="card success">{{ session('success') }}</div>
    @endif

    <form class="filters" method="GET"
          action="{{ route('transport.partner.orders') }}">
        <select name="status">
            <option value="">ყველა სტატუსი</option>
            <option value="accepted" @selected($status === 'accepted')>
                მიღებული
            </option>
            <option value="in_transit" @selected($status === 'in_transit')>
                გზაშია
            </option>
            <option value="delivered" @selected($status === 'delivered')>
                მიწოდებულია
            </option>
        </select>
        <button class="btn primary" type="submit">გაფილტვრა</button>
        <a class="btn" href="{{ route('transport.partner.orders') }}">გასუფთავება</a>
    </form>

    @forelse($orders as $order)
        <article class="card">
            <div class="top">
                <div>
                    <span class="tag">{{ $order->request_number }}</span>
                    <h2>
                        {{ $order->motorcycle_brand }}
                        {{ $order->motorcycle_model }}
                    </h2>
                    <a class="btn"
   href="{{ route('transport.partner.orders.show', $order) }}">
    შეკვეთის დეტალები →
</a>
                </div>

                <span class="tag
                    @if($order->status === 'delivered') success
                    @elseif($order->status === 'in_transit') warning
                    @else status
                    @endif">
                    @switch($order->status)
                        @case('accepted') მიღებულია @break
                        @case('in_transit') გზაშია @break
                        @case('delivered') მიწოდებულია @break
                    @endswitch
                </span>
            </div>

            <div class="route">
                {{ $order->pickup_city }}
                <span style="color:#42a5ff"> → </span>
                {{ $order->destination_city }}
            </div>

            <div class="details">
                @if($order->motorcycle_year)
                    <span class="tag">წელი: {{ $order->motorcycle_year }}</span>
                @endif

                @if($order->preferred_date)
                    <span class="tag">
                        თარიღი: {{ $order->preferred_date->format('d.m.Y') }}
                    </span>
                @endif

                @if($order->offers->isNotEmpty())
                    <span class="tag">
                        შეთავაზებული ფასი:
                        {{ number_format((float) $order->offers->first()->price, 2) }} ₾
                    </span>
                    <span class="tag">
                        ვადა: {{ $order->offers->first()->estimated_days }} დღე
                    </span>
                @endif
            </div>

            @if($order->status === 'accepted')
                <p>შეკვეთა მიღებულია. შეგიძლია დაიწყო ტრანსპორტირება.</p>

                <form method="POST"
                      action="{{ route('transport.partner.requests.status', $order) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="in_transit">
                    <button class="btn primary" type="submit">
                        ტრანსპორტირების დაწყება
                    </button>
                </form>
            @elseif($order->status === 'in_transit')
                <p>ტრანსპორტირება მიმდინარეობს.</p>

                <form method="POST"
                      action="{{ route('transport.partner.requests.status', $order) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="delivered">
                    <button class="btn primary" type="submit">
                        მიწოდების დადასტურება
                    </button>
                </form>
            @else
                <p class="success">შეკვეთა დასრულებულია.</p>
            @endif
        </article>
    @empty
        <div class="empty">
            <h2>შეკვეთები ჯერ არ არის</h2>
            <p>აქ გამოჩნდება მომხმარებლის მიერ მიღებული შენი შეთავაზებები.</p>
        </div>
    @endforelse

    <div class="pagination">{{ $orders->links() }}</div>
</div>
</body>
</html>
