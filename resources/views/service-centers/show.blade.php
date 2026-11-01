@extends('layouts.service-centers')
@section('title', 'MotoHub | ' . $serviceCenter->name)
@section('content')
<section class="page-head">
    <a class="muted small" href="{{ route('service-centers.index') }}">← ყველა სახელოსნო</a>
    <div class="eyebrow" style="margin-top:18px">MotoHub service partner</div>
    <div style="display:flex;gap:18px;justify-content:space-between;align-items:flex-start;flex-wrap:wrap"><div><h1>{{ $serviceCenter->name }}</h1><div class="details"><span>⌖ {{ $serviceCenter->city }}, {{ $serviceCenter->address }}</span><span>☎ {{ $serviceCenter->phone }}</span>@if($serviceCenter->email)<span>✉ {{ $serviceCenter->email }}</span>@endif</div></div>@if($serviceCenter->is_verified)<span class="pill pill-green">✓ გადამოწმებული სახელოსნო</span>@endif</div>
    @if($serviceCenter->description)<p class="muted" style="max-width:800px">{{ $serviceCenter->description }}</p>@endif
</section>
<div class="layout-two">
    <section class="panel">
        <div class="panel-head"><div><h2>მომსახურებები და ფასები</h2><div class="muted small">ფასი და ხანგრძლივობა წინასწარ არის მითითებული სახელოსნოს მიერ.</div></div></div>
        @forelse($serviceCenter->services as $service)
            <div class="service-row" style="padding:14px 0"><div><strong>{{ $service->name }}</strong>@if($service->description)<div class="muted small" style="margin-top:4px">{{ $service->description }}</div>@endif<div class="muted small" style="margin-top:4px">◷ {{ $service->duration_minutes }} წუთი</div></div><strong class="price">{{ $service->price_label }}</strong></div>
        @empty
            <div class="empty">ამ სახელოსნოს მომსახურებები ჯერ არ დაუმატებია.</div>
        @endforelse
        <div style="margin-top:24px"><h2>სამუშაო საათები</h2>
            @php($dayNames = [0 => 'კვირა', 1 => 'ორშაბათი', 2 => 'სამშაბათი', 3 => 'ოთხშაბათი', 4 => 'ხუთშაბათი', 5 => 'პარასკევი', 6 => 'შაბათი'])
            @php($hoursByDay = $serviceCenter->hours->keyBy('day_of_week'))
            @foreach($dayNames as $day => $label)
                @php($hour = $hoursByDay->get($day))
                <div class="service-row"><span>{{ $label }}</span><span class="muted">{{ !$hour || $hour->is_closed ? 'დაკეტილია' : substr($hour->opens_at, 0, 5) . ' – ' . substr($hour->closes_at, 0, 5) }}</span></div>
            @endforeach
        </div>
    </section>
    <aside class="panel" id="booking">
        <div class="eyebrow">Online booking</div><h2>დაჯავშნე მომსახურება</h2><p class="muted small">აირჩიე მომსახურება და თარიღი — თავისუფალი და დაკავებული საათები გამოჩნდება ქვემოთ. ჯავშანი საბოლოოდ ხელახლა მოწმდება გაგზავნისას.</p>
        @auth
            @if($serviceCenter->services->isNotEmpty())
                <form class="booking-form" id="serviceBookingForm" method="POST" action="{{ route('service-centers.bookings.store', $serviceCenter->slug) }}">
                    @csrf
                    <div class="field">
                        <label for="service_id">მომსახურება</label>
                        <select id="service_id" name="service_id" required>
                            <option value="">აირჩიე მომსახურება</option>
                            @foreach($serviceCenter->services as $service)
                                <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->name }} — {{ $service->price_label }} ({{ $service->duration_minutes }} წთ)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="booking_date">თარიღი</label>
                        <input id="booking_date" type="date" min="{{ now()->format('Y-m-d') }}" value="{{ old('scheduled_at') ? substr(old('scheduled_at'), 0, 10) : '' }}" required>
                    </div>
                    <div class="field">
                        <label for="booking_slot">თავისუფალი საათი</label>
                        <select id="booking_slot" disabled required>
                            <option value="">ჯერ აირჩიე მომსახურება და თარიღი</option>
                        </select>
                        <small id="slot_message" class="muted" role="status" aria-live="polite">თავისუფალი საათების საჩვენებლად აირჩიე მომსახურება და თარიღი.</small>
                        @error('scheduled_at')<small class="field-error" style="display:block">{{ $message }}</small>@enderror
                        <input type="hidden" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}">
                    </div>
                    <div class="field"><label for="customer_name">სახელი და გვარი</label><input id="customer_name" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required maxlength="160"></div>
                    <div class="field"><label for="customer_phone">ტელეფონი</label><input id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required maxlength="40" placeholder="5XX XX XX XX"></div>
                    <div class="field"><label for="motorcycle">მოტოციკლის მარკა და მოდელი</label><input id="motorcycle" name="motorcycle" value="{{ old('motorcycle') }}" maxlength="180" placeholder="მაგ. Yamaha MT-07"></div>
                    <div class="field"><label for="notes">დამატებითი ინფორმაცია</label><textarea id="notes" name="notes" maxlength="1500" placeholder="აღწერე პრობლემა ან დამატებითი მოთხოვნა">{{ old('notes') }}</textarea></div>
                    <div class="field"><label for="points">Rewards ქულები (მაქსიმუმ 30%)</label><input id="points" name="points" type="number" min="0" max="1000000" value="{{ old('points', 0) }}"><small class="muted">ფასის მაქსიმუმ 30% დაფარვა შეგიძლია ქულებით.</small></div>
                    <button class="btn" type="submit">ჯავშნის მოთხოვნის გაგზავნა →</button>
                </form>
            @else
                <div class="empty">ჯავშნისთვის ჯერ საჭიროა სახელოსნომ მომსახურებები დაამატოს.</div>
            @endif
        @else
            <div class="empty"><p>ჯავშნის გასაგზავნად საჭიროა ავტორიზაცია.</p><a class="btn" href="{{ route('login') }}">შესვლა და დაჯავშნა</a></div>
        @endauth
    </aside>
</div>
@auth
@if($serviceCenter->services->isNotEmpty())
<script>
document.addEventListener('DOMContentLoaded', () => {
    const serviceSelect = document.getElementById('service_id');
    const dateInput = document.getElementById('booking_date');
    const slotSelect = document.getElementById('booking_slot');
    const scheduledInput = document.getElementById('scheduled_at');
    const message = document.getElementById('slot_message');
    const form = document.getElementById('serviceBookingForm');
    const availabilityUrl = @json(route('service-centers.availability', $serviceCenter->slug));
    let initialScheduledAt = @json(old('scheduled_at'));

    let requestController = null;

    function setMessage(text) {
        message.textContent = text || '';
    }

    function resetSlots(placeholder) {
        slotSelect.replaceChildren(new Option(placeholder, ''));
        slotSelect.disabled = true;
        scheduledInput.value = '';
    }

    async function loadSlots() {
        resetSlots('თავისუფალი საათების ჩატვირთვა...');
        const serviceId = serviceSelect.value;
        const date = dateInput.value;

        if (!serviceId || !date) {
            resetSlots('ჯერ აირჩიე მომსახურება და თარიღი');
            setMessage('თავისუფალი საათების საჩვენებლად აირჩიე მომსახურება და თარიღი.');
            return;
        }

        if (requestController) requestController.abort();
        requestController = new AbortController();
        setMessage('ვამოწმებთ თავისუფალ საათებს...');

        const params = new URLSearchParams({ service_id: serviceId, date });
        try {
            const response = await fetch(`${availabilityUrl}?${params.toString()}`, {
                headers: { 'Accept': 'application/json' },
                signal: requestController.signal,
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'თავისუფალი საათების მიღება ვერ მოხერხდა.');

            slotSelect.replaceChildren(new Option('აირჩიე საათი', ''));
            let availableCount = 0;
            const preferredDate = initialScheduledAt ? initialScheduledAt.slice(0, 10) : '';
            const preferredTime = initialScheduledAt && preferredDate === date ? initialScheduledAt.slice(11, 16) : '';

            for (const slot of (data.slots || [])) {
                const option = new Option(
                    slot.available ? slot.label : `${slot.label} — ${slot.reason || 'დაკავებულია'}`,
                    slot.value
                );
                option.disabled = !slot.available;
                if (slot.available) availableCount++;
                slotSelect.add(option);
            }

            slotSelect.disabled = (data.slots || []).length === 0;
            setMessage(data.message || (availableCount > 0
                ? 'დაკავებული საათები მონიშნულია და არჩევა შეუძლებელია.'
                : 'ამ თარიღზე თავისუფალი დრო არ მოიძებნა.'));

            if (preferredTime) {
                const matching = Array.from(slotSelect.options).find(option => option.value === preferredTime && !option.disabled);
                if (matching) {
                    slotSelect.value = preferredTime;
                    scheduledInput.value = `${date}T${preferredTime}`;
                }
            }
        } catch (error) {
            if (error.name === 'AbortError') return;
            resetSlots('საათების ჩატვირთვა ვერ მოხერხდა');
            setMessage(error.message || 'შეცდომა მოხდა. განაახლე გვერდი და სცადე თავიდან.');
        }
    }

    serviceSelect.addEventListener('change', () => {
        initialScheduledAt = null;
        loadSlots();
    });
    dateInput.addEventListener('change', loadSlots);
    slotSelect.addEventListener('change', () => {
        scheduledInput.value = slotSelect.value && dateInput.value
            ? `${dateInput.value}T${slotSelect.value}`
            : '';
    });
    form.addEventListener('submit', (event) => {
        if (!scheduledInput.value) {
            event.preventDefault();
            setMessage('ჯავშნის გასაგზავნად აირჩიე თავისუფალი საათი.');
            slotSelect.focus();
        }
    });

    if (serviceSelect.value && dateInput.value) loadSlots();
});
</script>
@endif
@endauth
@endsection
