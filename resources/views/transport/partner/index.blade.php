<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>გადამზიდავის კაბინეტი | MotoHub</title>
    @include('transport.partner.partials.styles')
</head>
<body>
<div class="wrap partner-dashboard">
@include('transport.partner.partials.header')

<section class="hero">
    <div class="hero-content">
        <div class="eyebrow">MOTOTRANSPORT / PARTNER</div>
        <h1>ტრანსპორტირების მოთხოვნები</h1>
        <p>{{ $partner->name }} — შეარჩიე შეკვეთა, მიუთითე ფასი და ტრანსპორტირების სავარაუდო ვადა.</p>
    </div>
</section>

    
<div class="stats">
    <article class="stat-card" style="--stat-color:#42a5ff">
        <div class="stat-label">ახალი მოთხოვნები</div>
        <div class="stat-number">{{ $newRequestsCount ?? 0 }}</div>
        <div class="stat-note">შეთავაზებას ელოდება</div>
    </article>

    <article class="stat-card" style="--stat-color:#a78bfa">
        <div class="stat-label">გაგზავნილი შეთავაზებები</div>
        <div class="stat-number">{{ $offersCount ?? 0 }}</div>
        <div class="stat-note">შენი კომპანიის შეთავაზებები</div>
    </article>

    <article class="stat-card" style="--stat-color:#fbbf24">
        <div class="stat-label">გზაშია</div>
        <div class="stat-number">{{ $inTransitCount ?? 0 }}</div>
        <div class="stat-note">მიმდინარე გადაზიდვები</div>
    </article>

    <article class="stat-card" style="--stat-color:#34d399">
        <div class="stat-label">მიწოდებულია</div>
        <div class="stat-number">{{ $deliveredCount ?? 0 }}</div>
        <div class="stat-note">დასრულებული გადაზიდვები</div>
    </article>
</div>


    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert">
            <strong>შეამოწმე შეყვანილი ინფორმაცია:</strong>
            @foreach($errors->all() as $error)
                <div class="error">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form class="filters" method="GET" action="{{ route('transport.partner.index') }}">
        <input name="pickup_city" value="{{ request('pickup_city') }}" placeholder="საიდან — ქალაქი">
        <input name="destination_city" value="{{ request('destination_city') }}" placeholder="სად — ქალაქი">
        <button class="btn primary" type="submit">ძებნა</button>
    </form>

    <div class="grid">
        @forelse($requests as $transportRequest)
            <article class="card">
                <div class="meta">
                    <span class="tag">{{ $transportRequest->request_number }}</span>
                    <span class="tag">მოთხოვნა</span>
                </div>

                <h2>{{ $transportRequest->motorcycle_brand }} {{ $transportRequest->motorcycle_model }}</h2>

                <div class="route">
                    {{ $transportRequest->pickup_city }}
                    <span style="color:#42a5ff"> → </span>
                    {{ $transportRequest->destination_city }}
                </div>

                <div class="meta">
                    @if($transportRequest->motorcycle_year)
                        <span class="tag">წელი: {{ $transportRequest->motorcycle_year }}</span>
                    @endif
                    @if($transportRequest->preferred_date)
                        <span class="tag">თარიღი: {{ $transportRequest->preferred_date->format('d.m.Y') }}</span>
                    @endif
                </div>

                @if($transportRequest->motorcycle_details)
                    <div class="details">
                        <strong>მოტოციკლის დეტალები</strong>
                        <p>{{ $transportRequest->motorcycle_details }}</p>
                    </div>
                @endif

                @if($transportRequest->customer_notes)
                    <div class="details">
                        <strong>დამატებითი ინფორმაცია</strong>
                        <p>{{ $transportRequest->customer_notes }}</p>
                    </div>
                @endif

                <form class="offer-form"
                      method="POST"
                      action="{{ route('transport.partner.offers.store', $transportRequest) }}">
                    @csrf

                    <label>
                        ფასი (₾)
                        <input type="number" name="price" min="0.01" max="99999999"
                               step="0.01" required placeholder="მაგ. 150">
                    </label>

                    <label>
                        სავარაუდო ვადა (დღე)
                        <input type="number" name="estimated_days" min="1" max="365"
                               value="1" required>
                    </label>

                    <label class="full">
                        კომენტარი (არასავალდებულო)
                        <input name="message" maxlength="2000"
                               placeholder="ტრანსპორტირების პირობები...">
                    </label>

                    <button class="btn primary full" type="submit">ფასის შეთავაზება</button>
                </form>
            </article>
        @empty
            <div class="empty">
                <h2>ახალი მოთხოვნები ამჟამად არ არის</h2>
                <p>მოგვიანებით შეამოწმე ან შეცვალე საძიებო ქალაქები.</p>
            </div>
        @endforelse
    </div>

    <div class="pagination">{{ $requests->links() }}</div>
</div>
</body>
</html>
