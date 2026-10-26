<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ჩემი შეთავაზებები | MotoHub</title>

    @include('transport.partner.partials.styles')
</head>

<body>
<div class="wrap">
@include('transport.partner.partials.header')

    <section class="hero">
        <div class="eyebrow">MOTOTRANSPORT / PARTNER</div>
        <h1>ჩემი შეთავაზებები</h1>
        <p>{{ $partner->name }} — შენი გაგზავნილი სატრანსპორტო შეთავაზებები.</p>
    </section>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @php
        $statusLabels = [
            'pending' => 'მომხმარებლის პასუხს ელოდება',
            'accepted' => 'შეთავაზება მიღებულია',
            'in_transit' => 'ტრანსპორტირების პროცესშია',
            'delivered' => 'მოტოციკლი ჩაბარებულია',
            'rejected' => 'შეთავაზება უარყოფილია',
        ];

        $requestStatusLabels = [
            'pending' => 'ელოდება შეთავაზებას',
            'quoted' => 'შეთავაზებები მიღებულია',
            'accepted' => 'შეთავაზება მიღებულია',
            'in_transit' => 'ტრანსპორტირების პროცესშია',
            'delivered' => 'ჩაბარებულია',
            'cancelled' => 'გაუქმებულია',
        ];
    @endphp

    @forelse($offers as $offer)
        @php
            $transportRequest = $offer->transportRequest;
            $requestStatus = $transportRequest->status;
            $offerStatus = $offer->status;

            $isAcceptedOffer = $offerStatus === 'accepted';

            $canStartTransport =
                $isAcceptedOffer && $requestStatus === 'accepted';

            $canMarkDelivered =
                $isAcceptedOffer && $requestStatus === 'in_transit';
        @endphp

        <article class="card">
            <div class="meta">
                <span class="tag">
                    შეკვეთა: {{ $transportRequest->request_number }}
                </span>

                <span class="tag">
                    შეთავაზება:
                    <strong class="status-{{ $offerStatus }}">
                        {{ $statusLabels[$offerStatus] ?? $offerStatus }}
                    </strong>
                </span>

                <span class="tag">
                    შეკვეთის სტატუსი:
                    {{ $requestStatusLabels[$requestStatus] ?? $requestStatus }}
                </span>
            </div>

            <h2>
                {{ $transportRequest->motorcycle_brand }}
                {{ $transportRequest->motorcycle_model }}
                @if($transportRequest->motorcycle_year)
                    ({{ $transportRequest->motorcycle_year }})
                @endif
            </h2>

            <div class="route">
                {{ $transportRequest->pickup_city }}
                <span style="color:var(--light-blue)"> → </span>
                {{ $transportRequest->destination_city }}
            </div>

            <div class="price">
                {{ number_format((float) $offer->price, 2) }} ₾
            </div>

            <div class="meta">
                <span class="tag">
                    ვადა: {{ $offer->estimated_days }} დღე
                </span>

                <span class="tag">
                    გაგზავნილია:
                    {{ $offer->created_at->format('d.m.Y H:i') }}
                </span>

                @if($offer->accepted_at)
                    <span class="tag">
                        მიღებულია:
                        {{ $offer->accepted_at->format('d.m.Y H:i') }}
                    </span>
                @endif
            </div>

            @if($offer->message)
                <p>{{ $offer->message }}</p>
            @endif

            @if($canStartTransport)
                <div class="actions">
                    <form
                        method="POST"
                        action="{{ route('transport.partner.requests.status', $transportRequest) }}"
                        onsubmit="return confirm('დავიწყოთ ამ მოტოციკლის ტრანსპორტირება?');"
                    >
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="status" value="in_transit">

                        <button type="submit" class="btn btn-primary">
                            ტრანსპორტირების დაწყება →
                        </button>
                    </form>
                </div>
            @elseif($canMarkDelivered)
                <div class="actions">
                    <form
                        method="POST"
                        action="{{ route('transport.partner.requests.status', $transportRequest) }}"
                        onsubmit="return confirm('ადასტურებ, რომ მოტოციკლი ჩაბარდა?');"
                    >
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="status" value="delivered">

                        <button type="submit" class="btn btn-primary">
                            მოტოციკლი ჩაბარდა ✓
                        </button>
                    </form>
                </div>
            @elseif($offerStatus === 'pending')
                <p class="muted">
                    სტატუსის მართვა შესაძლებელი გახდება, როცა მომხმარებელი შეთავაზებას მიიღებს.
                </p>
            @elseif($offerStatus === 'rejected')
                <p class="muted">
                    მომხმარებელმა ეს შეთავაზება არ მიიღო.
                </p>
            @elseif($requestStatus === 'delivered')
                <p class="status-delivered">
                    შეკვეთა წარმატებით დასრულებულია.
                </p>
            @endif
        </article>
    @empty
        <div class="empty">
            <h2>შეთავაზებები ჯერ არ გაქვს</h2>
            <p>გადადით შეკვეთების გვერდზე და გაუგზავნე მომხმარებლებს ფასიანი შეთავაზებები.</p>

            <a class="btn" href="{{ route('transport.partner.index') }}">
                შეკვეთების ნახვა
            </a>
        </div>
    @endforelse

    {{ $offers->links() }}
</div>
</body>
</html>