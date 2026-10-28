@extends('my-service-center.layout')

@section('title', 'შეკვეთის რედაქტირება')
@section('page-title', 'შეკვეთის რედაქტირება')
@section('page-subtitle', 'სამუშაო შეკვეთის მონაცემების განახლება')

@section('content')

@php
    $statusLabels = [
        'received' => 'მიღებულია',
        'diagnostics' => 'დიაგნოსტიკა',
        'waiting_for_parts' => 'ნაწილებს ელოდება',
        'in_repair' => 'შეკეთების პროცესშია',
        'completed' => 'შეკეთებულია',
        'handed_over' => 'კლიენტს გადაეცა',
        'cancelled' => 'გაუქმებულია',
    ];

    $currentStatus = $workOrder->status;
@endphp

<div class="edit-order-page">

    <div class="edit-order-hero">
        <div class="edit-order-hero-content">
            <div class="edit-order-eyebrow">
                <span class="edit-order-live-dot"></span>
                WORK ORDER / EDIT
            </div>

            <h2>შეკვეთის <span>განახლება</span></h2>

            <p>
                მართე მოტოციკლის მიღების ინფორმაცია, დიაგნოსტიკა და სამუშაოს ღირებულება.
            </p>

            <div class="edit-order-meta">
                <div class="edit-order-meta-item">
                    <span>შეკვეთის ნომერი</span>
                    <strong>{{ $workOrder->order_number }}</strong>
                </div>

                <div class="edit-order-meta-item">
                    <span>მიმდინარე სტატუსი</span>
                    <strong>
                        {{ $statusLabels[$currentStatus] ?? $currentStatus }}
                    </strong>
                </div>
            </div>
        </div>

        <div class="edit-order-hero-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M14.7 6.3a5 5 0 0 0-6.6 6.6L3 18l3 3 5.1-5.1a5 5 0 0 0 6.6-6.6L14 13l-3-3 3.7-3.7Z"
                    stroke="currentColor" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>

    @if (session('success'))
        <div class="edit-order-alert edit-order-alert-success">
            <span class="edit-order-alert-icon">✓</span>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="edit-order-alert edit-order-alert-error">
            <span class="edit-order-alert-icon">!</span>
            <div>
                <strong>გთხოვ, შეასწორე შემდეგი შეცდომები:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form
        action="{{ route('my-service-center.work-orders.update', $workOrder) }}"
        method="POST"
        enctype="multipart/form-data"
        class="edit-order-form"
    >
        @csrf
        @method('PUT')

        {{-- მიღების მონაცემები --}}
        <section class="edit-order-card">
            <div class="edit-order-card-heading">
                <div class="edit-order-section-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.6"
                              stroke-linecap="round"/>
                    </svg>
                </div>

                <div>
                    <h3>მიღების მონაცემები</h3>
                    <p>მოტოციკლის მდგომარეობა შემოსვლის მომენტში</p>
                </div>

                <span class="edit-order-step">01</span>
            </div>

            <div class="edit-order-fields">
                <div class="edit-order-field">
                    <label for="intake_odometer">გარბენი <span>(კმ)</span></label>

                    <input
                        type="number"
                        id="intake_odometer"
                        name="intake_odometer"
                        min="0"
                        value="{{ old('intake_odometer', $workOrder->intake_odometer) }}"
                        placeholder="მაგ. 24500"
                    >

                    @error('intake_odometer')
                        <small class="edit-order-field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="edit-order-field edit-order-field-wide">
                    <label for="intake_condition">მიღებისას არსებული მდგომარეობა</label>

                    <textarea
                        id="intake_condition"
                        name="intake_condition"
                        rows="4"
                        placeholder="აღწერე მოტოციკლის ვიზუალური მდგომარეობა, დაზიანებები და სხვა დეტალები..."
                    >{{ old('intake_condition', $workOrder->intake_condition) }}</textarea>

                    @error('intake_condition')
                        <small class="edit-order-field-error">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </section>

        {{-- ფოტოები --}}
        <section class="edit-order-card">
            <div class="edit-order-card-heading">
                <div class="edit-order-section-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <rect x="3" y="4" width="18" height="16" rx="3"
                              stroke="currentColor" stroke-width="1.6"/>
                        <circle cx="8.5" cy="9" r="1.5"
                                stroke="currentColor" stroke-width="1.5"/>
                        <path d="m4 17 5-5 4 4 3-3 4 4"
                              stroke="currentColor" stroke-width="1.6"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <div>
                    <h3>მოტოციკლის ფოტოები</h3>
                    <p>შეინახე მიღების მდგომარეობის ვიზუალური ისტორია</p>
                </div>

                <span class="edit-order-step">02</span>
            </div>

            @if (!empty($workOrder->intake_photos))
                <div class="edit-order-existing-photos">
                    <div class="edit-order-subheading">
                        <span>ატვირთული ფოტოები</span>
                        <span class="edit-order-photo-count">
                            {{ count($workOrder->intake_photos) }} ფოტო
                        </span>
                    </div>

                    <div class="edit-order-photo-grid">
                        @foreach ($workOrder->intake_photos as $photo)
                            <a
                                href="{{ asset('storage/' . $photo) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="edit-order-photo"
                                aria-label="ფოტოს სრულად ნახვა"
                            >
                                <img
                                    src="{{ asset('storage/' . $photo) }}"
                                    alt="მოტოციკლის ფოტო"
                                    loading="lazy"
                                >
                                <span class="edit-order-photo-overlay">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <circle cx="10.5" cy="10.5" r="6.5"
                                                stroke="currentColor" stroke-width="1.8"/>
                                        <path d="m16 16 4 4m-9.5-9.5h0"
                                              stroke="currentColor" stroke-width="1.8"
                                              stroke-linecap="round"/>
                                    </svg>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="edit-order-upload">
                <div class="edit-order-upload-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M12 16V4m0 0L7 9m5-5 5 5"
                              stroke="currentColor" stroke-width="1.7"
                              stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"
                              stroke="currentColor" stroke-width="1.7"
                              stroke-linecap="round"/>
                    </svg>
                </div>

                <div class="edit-order-upload-content">
                    <label for="intake_photos">ახალი ფოტოების დამატება</label>
                    <p>აირჩიე ერთი ან რამდენიმე JPG, PNG ან WEBP ფოტო.</p>
                    <input
                        type="file"
                        id="intake_photos"
                        name="intake_photos[]"
                        accept="image/jpeg,image/png,image/webp"
                        multiple
                    >
                    <div id="edit-order-selected-files" class="edit-order-selected-files">
                        ახალი ფოტოები არჩეული არ არის
                    </div>
                </div>
            </div>

            <div class="edit-order-info">
                <span>i</span>
                ახალი ფოტოები არსებულებს დაემატება და მათ არ ჩაანაცვლებს.
            </div>

            @error('intake_photos')
                <small class="edit-order-field-error">{{ $message }}</small>
            @enderror

            @error('intake_photos.*')
                <small class="edit-order-field-error">{{ $message }}</small>
            @enderror
        </section>

        {{-- დიაგნოსტიკა --}}
        <section class="edit-order-card">
            <div class="edit-order-card-heading">
                <div class="edit-order-section-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M4 19.5V4.5A1.5 1.5 0 0 1 5.5 3H20v17H5.5A1.5 1.5 0 0 0 4 21.5"
                              stroke="currentColor" stroke-width="1.6"
                              stroke-linejoin="round"/>
                        <path d="M8 7h8M8 11h8M8 15h5"
                              stroke="currentColor" stroke-width="1.6"
                              stroke-linecap="round"/>
                    </svg>
                </div>

                <div>
                    <h3>დიაგნოსტიკა და სამუშაო</h3>
                    <p>ტექნიკური შეფასება და შესრულებული სამუშაოების აღწერა</p>
                </div>

                <span class="edit-order-step">03</span>
            </div>

            <div class="edit-order-fields">
                <div class="edit-order-field">
                    <label for="diagnosis">დიაგნოსტიკის შედეგი</label>

                    <textarea
                        id="diagnosis"
                        name="diagnosis"
                        rows="5"
                        placeholder="რა პრობლემა გამოვლინდა? რა საჭიროებს შეკეთებას?"
                    >{{ old('diagnosis', $workOrder->diagnosis) }}</textarea>

                    @error('diagnosis')
                        <small class="edit-order-field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="edit-order-field">
                    <label for="work_description">შესრულებული სამუშაო</label>

                    <textarea
                        id="work_description"
                        name="work_description"
                        rows="5"
                        placeholder="აღწერე შესრულებული სამუშაოები და შეცვლილი ნაწილები..."
                    >{{ old('work_description', $workOrder->work_description) }}</textarea>

                    @error('work_description')
                        <small class="edit-order-field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="edit-order-field edit-order-field-wide">
                    <label for="notes">დამატებითი შენიშვნები</label>

                    <textarea
                        id="notes"
                        name="notes"
                        rows="3"
                        placeholder="დამატებითი ინფორმაცია კლიენტისთვის ან სერვისის გუნდისთვის..."
                    >{{ old('notes', $workOrder->notes) }}</textarea>

                    @error('notes')
                        <small class="edit-order-field-error">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </section>

        {{-- ღირებულება --}}
        <section class="edit-order-card">
            <div class="edit-order-card-heading">
                <div class="edit-order-section-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <rect x="3" y="5" width="18" height="14" rx="2.5"
                              stroke="currentColor" stroke-width="1.6"/>
                        <path d="M3 10h18M7 15h3"
                              stroke="currentColor" stroke-width="1.6"
                              stroke-linecap="round"/>
                    </svg>
                </div>

                <div>
                    <h3>სამუშაოს ღირებულება</h3>
                    <p>ხელობისა და სათადარიგო ნაწილების ხარჯები</p>
                </div>

                <span class="edit-order-step">04</span>
            </div>

            <div class="edit-order-fields edit-order-cost-fields">
                <div class="edit-order-field">
                    <label for="labor_cost">ხელობის ღირებულება <span>(₾)</span></label>

                    <div class="edit-order-input-with-icon">
                        <span>₾</span>
                        <input
                            type="number"
                            id="labor_cost"
                            name="labor_cost"
                            min="0"
                            max="99999999"
                            step="0.01"
                            required
                            value="{{ old('labor_cost', $workOrder->labor_cost) }}"
                            placeholder="0.00"
                        >
                    </div>

                    @error('labor_cost')
                        <small class="edit-order-field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="edit-order-field">
                    <label for="parts_cost">ნაწილების ღირებულება <span>(₾)</span></label>

                    <div class="edit-order-input-with-icon">
                        <span>₾</span>
                        <input
                            type="number"
                            id="parts_cost"
                            name="parts_cost"
                            min="0"
                            max="99999999"
                            step="0.01"
                            required
                            value="{{ old('parts_cost', $workOrder->parts_cost) }}"
                            placeholder="0.00"
                        >
                    </div>

                    @error('parts_cost')
                        <small class="edit-order-field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="edit-order-total">
                    <div>
                        <span class="edit-order-total-label">სრული ღირებულება</span>
                        <span class="edit-order-total-caption">ხელობა + ნაწილები</span>
                    </div>

                    <div id="total-cost" class="edit-order-total-value">
                        0.00 <span>₾</span>
                    </div>
                </div>
            </div>

            <div class="edit-order-actions">
                <button type="submit" class="edit-order-save">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 3h12l4 4v14H3V3h2Z"
                              stroke="currentColor" stroke-width="1.7"
                              stroke-linejoin="round"/>
                        <path d="M7 3v6h10V3M7 21v-8h10v8"
                              stroke="currentColor" stroke-width="1.7"
                              stroke-linejoin="round"/>
                    </svg>
                    ცვლილებების შენახვა
                </button>

                <a
                    href="{{ route('my-service-center.work-orders.show', $workOrder) }}"
                    class="edit-order-cancel"
                >
                    გაუქმება
                </a>
            </div>
        </section>
    </form>

    <div class="edit-order-footer">
        <span class="edit-order-footer-dot"></span>
        MotoHub Service Management
        <span class="edit-order-footer-divider">/</span>
        WORK ORDER {{ $workOrder->order_number }}
    </div>

</div>

@endsection

@push('styles')
<style>
.edit-order-page {
    --eo-bg: #0a101b;
    --eo-panel: #101a29;
    --eo-panel-light: #142136;
    --eo-border: #253750;
    --eo-blue: #2684ff;
    --eo-blue-light: #65b3ff;
    --eo-text: #f3f7ff;
    --eo-muted: #91a3bc;
    color: var(--eo-text);
    max-width: 1240px;
    margin: 0 auto;
    padding-bottom: 28px;
}

.edit-order-page * {
    box-sizing: border-box;
}

.edit-order-hero {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    overflow: hidden;
    padding: 30px;
    margin-bottom: 22px;
    border: 1px solid #27436a;
    border-radius: 18px;
    background:
        radial-gradient(ellipse at 85% 10%, rgba(38,132,255,.20), transparent 42%),
        linear-gradient(125deg, #101c2e 0%, #0c1421 65%, #101c2d 100%);
}

.edit-order-hero::after {
    content: "";
    position: absolute;
    width: 230px;
    height: 230px;
    right: 70px;
    top: -160px;
    border: 1px solid rgba(101,179,255,.2);
    border-radius: 50%;
    box-shadow: 0 0 0 28px rgba(101,179,255,.025),
                0 0 0 56px rgba(101,179,255,.025);
    pointer-events: none;
}

.edit-order-hero-content {
    position: relative;
    z-index: 1;
}

.edit-order-eyebrow {
    display: flex;
    align-items: center;
    gap: 9px;
    color: var(--eo-blue-light);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2px;
    margin-bottom: 14px;
}

.edit-order-live-dot,
.edit-order-footer-dot {
    width: 7px;
    height: 7px;
    display: inline-block;
    flex-shrink: 0;
    border-radius: 50%;
    background: #42dda5;
    box-shadow: 0 0 12px rgba(66,221,165,.55);
}

.edit-order-hero h2 {
    margin: 0;
    color: var(--eo-text);
    font-size: clamp(24px, 3vw, 34px);
    font-weight: 850;
    line-height: 1.25;
    letter-spacing: -.7px;
}

.edit-order-hero h2 span {
    color: var(--eo-blue-light);
}

.edit-order-hero-content > p {
    margin: 12px 0 0;
    max-width: 600px;
    color: var(--eo-muted);
    font-size: 13px;
    line-height: 1.8;
}

.edit-order-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 28px;
    margin-top: 24px;
}

.edit-order-meta-item {
    display: flex;
    flex-direction: column;
    gap: 7px;
}

.edit-order-meta-item > span {
    color: var(--eo-muted);
    font-size: 11px;
}

.edit-order-meta-item > strong {
    color: #eaf3ff;
    font-size: 13px;
    font-weight: 750;
    overflow-wrap: anywhere;
}

.edit-order-hero-icon {
    position: relative;
    z-index: 1;
    display: grid;
    place-items: center;
    width: 92px;
    height: 92px;
    min-width: 92px;
    border: 1px solid rgba(101,179,255,.3);
    border-radius: 23px;
    color: var(--eo-blue-light);
    background: rgba(38,132,255,.09);
    box-shadow: inset 0 0 30px rgba(38,132,255,.06);
}

.edit-order-hero-icon svg {
    width: 44px;
    height: 44px;
}

.edit-order-alert {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    padding: 17px 20px;
    margin-bottom: 20px;
    border: 1px solid var(--eo-border);
    border-radius: 12px;
    font-size: 13px;
    line-height: 1.7;
}

.edit-order-alert-success {
    border-color: rgba(67,217,165,.35);
    background: rgba(67,217,165,.07);
    color: #a4f4d7;
}

.edit-order-alert-error {
    border-color: rgba(255,113,135,.35);
    background: rgba(255,113,135,.06);
    color: #ffb4c0;
}

.edit-order-alert-icon {
    display: grid;
    place-items: center;
    width: 24px;
    height: 24px;
    min-width: 24px;
    border-radius: 7px;
    background: rgba(255,255,255,.06);
    font-weight: 900;
}

.edit-order-alert ul {
    margin: 8px 0 0;
    padding-left: 20px;
}

.edit-order-form {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.edit-order-card {
    min-width: 0;
    padding: 25px;
    border: 1px solid var(--eo-border);
    border-radius: 16px;
    background: linear-gradient(145deg, #111b2a, #0d1522);
    box-shadow: 0 12px 35px rgba(0,0,0,.12);
}

.edit-order-card-heading {
    display: flex;
    align-items: center;
    gap: 14px;
    padding-bottom: 21px;
    margin-bottom: 23px;
    border-bottom: 1px solid rgba(145,163,188,.13);
}

.edit-order-section-icon {
    display: grid;
    place-items: center;
    width: 45px;
    height: 45px;
    min-width: 45px;
    border: 1px solid rgba(38,132,255,.28);
    border-radius: 12px;
    color: var(--eo-blue-light);
    background: rgba(38,132,255,.10);
}

.edit-order-section-icon svg {
    width: 23px;
    height: 23px;
}

.edit-order-card-heading h3 {
    margin: 0;
    color: var(--eo-text);
    font-size: 16px;
    font-weight: 800;
}

.edit-order-card-heading p {
    margin: 6px 0 0;
    color: var(--eo-muted);
    font-size: 12px;
    line-height: 1.6;
}

.edit-order-step {
    margin-left: auto;
    align-self: flex-start;
    color: #49627f;
    font-size: 12px;
    font-weight: 850;
    letter-spacing: 1px;
}

.edit-order-fields {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 22px;
}

.edit-order-field {
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 9px;
}

.edit-order-field-wide {
    grid-column: 1 / -1;
}

.edit-order-field label,
.edit-order-upload-content label {
    color: #dce8f9;
    font-size: 12px;
    font-weight: 750;
}

.edit-order-field label span {
    color: var(--eo-muted);
    font-weight: 500;
}

.edit-order-field input,
.edit-order-field textarea,
.edit-order-input-with-icon input {
    display: block;
    width: 100%;
    min-width: 0;
    padding: 13px 14px;
    border: 1px solid #2a3b53;
    border-radius: 10px;
    outline: none;
    background: #0a1320;
    color: var(--eo-text);
    font-family: inherit;
    font-size: 13px;
    line-height: 1.65;
    transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
}

.edit-order-field input::placeholder,
.edit-order-field textarea::placeholder {
    color: #5f718a;
}

.edit-order-field input:focus,
.edit-order-field textarea:focus,
.edit-order-input-with-icon input:focus {
    border-color: var(--eo-blue);
    background: #0c1727;
    box-shadow: 0 0 0 3px rgba(38,132,255,.12);
}

.edit-order-field textarea {
    resize: vertical;
    min-height: 100px;
}

.edit-order-field-error {
    color: #ff8c9e;
    font-size: 11px;
    line-height: 1.6;
}

.edit-order-subheading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 13px;
    color: #dce8f9;
    font-size: 12px;
    font-weight: 750;
}

.edit-order-photo-count {
    color: var(--eo-blue-light);
    font-size: 11px;
    font-weight: 650;
}

.edit-order-photo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 12px;
    margin-bottom: 22px;
}

.edit-order-photo {
    position: relative;
    display: block;
    aspect-ratio: 4 / 3;
    overflow: hidden;
    border: 1px solid #2b405b;
    border-radius: 11px;
    background: #09111c;
}

.edit-order-photo img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .25s ease;
}

.edit-order-photo:hover img {
    transform: scale(1.06);
}

.edit-order-photo-overlay {
    position: absolute;
    inset: 0;
    display: grid;
    place-items: center;
    color: white;
    background: rgba(3,9,18,.42);
    opacity: 0;
    transition: opacity .2s ease;
}

.edit-order-photo:hover .edit-order-photo-overlay,
.edit-order-photo:focus-visible .edit-order-photo-overlay {
    opacity: 1;
}

.edit-order-photo-overlay svg {
    width: 26px;
    height: 26px;
}

.edit-order-upload {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px;
    border: 1px dashed #365476;
    border-radius: 12px;
    background: rgba(38,132,255,.035);
}

.edit-order-upload-icon {
    display: grid;
    place-items: center;
    width: 42px;
    height: 42px;
    min-width: 42px;
    border-radius: 11px;
    color: var(--eo-blue-light);
    background: rgba(38,132,255,.12);
}

.edit-order-upload-icon svg {
    width: 23px;
    height: 23px;
}

.edit-order-upload-content {
    min-width: 0;
    flex: 1;
}

.edit-order-upload-content > p {
    margin: 6px 0 13px;
    color: var(--eo-muted);
    font-size: 11px;
    line-height: 1.7;
}

.edit-order-upload-content input[type="file"] {
    display: block;
    max-width: 100%;
    color: var(--eo-muted);
    font-family: inherit;
    font-size: 12px;
}

.edit-order-upload-content input[type="file"]::file-selector-button {
    margin-right: 12px;
    padding: 9px 12px;
    border: 1px solid #365476;
    border-radius: 7px;
    background: #15253b;
    color: #dceaff;
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
}

.edit-order-selected-files {
    margin-top: 10px;
    color: var(--eo-muted);
    font-size: 11px;
    overflow-wrap: anywhere;
}

.edit-order-info {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin-top: 15px;
    color: var(--eo-muted);
    font-size: 11px;
    line-height: 1.7;
}

.edit-order-info > span {
    display: grid;
    place-items: center;
    width: 17px;
    height: 17px;
    min-width: 17px;
    border: 1px solid #46658b;
    border-radius: 50%;
    color: var(--eo-blue-light);
    font-size: 10px;
    font-weight: 800;
}

.edit-order-cost-fields {
    align-items: start;
}

.edit-order-input-with-icon {
    position: relative;
}

.edit-order-input-with-icon > span {
    position: absolute;
    z-index: 1;
    top: 50%;
    left: 14px;
    color: var(--eo-blue-light);
    font-size: 13px;
    font-weight: 800;
    transform: translateY(-50%);
    pointer-events: none;
}

.edit-order-input-with-icon input {
    padding-left: 34px;
}

.edit-order-total {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    padding: 22px;
    border: 1px solid rgba(38,132,255,.3);
    border-radius: 13px;
    background:
        radial-gradient(ellipse at 100% 0%, rgba(38,132,255,.12), transparent 55%),
        #0b1626;
}

.edit-order-total-label,
.edit-order-total-caption {
    display: block;
}

.edit-order-total-label {
    color: #eaf3ff;
    font-size: 13px;
    font-weight: 800;
}

.edit-order-total-caption {
    margin-top: 6px;
    color: var(--eo-muted);
    font-size: 11px;
}

.edit-order-total-value {
    color: var(--eo-blue-light);
    font-size: clamp(24px, 3vw, 32px);
    font-weight: 900;
    letter-spacing: -.7px;
    white-space: nowrap;
}

.edit-order-total-value span {
    font-size: 18px;
    font-weight: 750;
}

.edit-order-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
    padding-top: 22px;
    margin-top: 23px;
    border-top: 1px solid rgba(145,163,188,.13);
}

.edit-order-save,
.edit-order-cancel {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    min-height: 45px;
    padding: 12px 19px;
    border-radius: 9px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
    cursor: pointer;
    transition: transform .18s ease, background .18s ease, border-color .18s ease;
}

.edit-order-save {
    border: 1px solid #388cff;
    background: linear-gradient(135deg, #1677ff, #0861d9);
    color: white;
    box-shadow: 0 5px 18px rgba(22,119,255,.2);
}

.edit-order-save:hover {
    background: linear-gradient(135deg, #318aff, #1677ff);
    transform: translateY(-1px);
}

.edit-order-save svg {
    width: 17px;
    height: 17px;
}

.edit-order-cancel {
    border: 1px solid #30415a;
    background: #131e2d;
    color: #c5d2e4;
}

.edit-order-cancel:hover {
    border-color: #5a7394;
    background: #19283b;
}

.edit-order-footer {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 9px;
    padding: 20px 4px 0;
    color: #60738e;
    font-size: 9px;
    font-weight: 750;
    letter-spacing: 1.2px;
}

.edit-order-footer-dot {
    width: 6px;
    height: 6px;
}

.edit-order-footer-divider {
    color: #34465e;
}

@media (max-width: 700px) {
    .edit-order-hero {
        padding: 22px 18px;
        border-radius: 14px;
    }

    .edit-order-hero-icon {
        width: 56px;
        height: 56px;
        min-width: 56px;
        border-radius: 15px;
    }

    .edit-order-hero-icon svg {
        width: 29px;
        height: 29px;
    }

    .edit-order-meta {
        gap: 18px;
    }

    .edit-order-card {
        padding: 19px 15px;
        border-radius: 13px;
    }

    .edit-order-fields {
        grid-template-columns: minmax(0, 1fr);
        gap: 18px;
    }

    .edit-order-field-wide,
    .edit-order-total {
        grid-column: auto;
    }

    .edit-order-card-heading {
        gap: 11px;
    }

    .edit-order-section-icon {
        width: 39px;
        height: 39px;
        min-width: 39px;
    }

    .edit-order-card-heading h3 {
        font-size: 14px;
    }

    .edit-order-card-heading p {
        font-size: 11px;
    }

    .edit-order-step {
        font-size: 10px;
    }

    .edit-order-photo-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .edit-order-upload {
        padding: 15px;
        gap: 11px;
    }

    .edit-order-total {
        padding: 17px 14px;
    }

    .edit-order-save,
    .edit-order-cancel {
        flex: 1 1 auto;
    }
}

@media (max-width: 420px) {
    .edit-order-hero-icon {
        display: none;
    }

    .edit-order-hero h2 {
        font-size: 25px;
    }

    .edit-order-meta {
        flex-direction: column;
        gap: 13px;
    }

    .edit-order-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .edit-order-save,
    .edit-order-cancel {
        width: 100%;
    }

    .edit-order-total-value {
        font-size: 23px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const labor = document.getElementById('labor_cost');
    const parts = document.getElementById('parts_cost');
    const total = document.getElementById('total-cost');
    const photoInput = document.getElementById('intake_photos');
    const selectedFiles = document.getElementById('edit-order-selected-files');

    function calculateTotal() {
        const laborCost = Math.max(0, parseFloat(labor.value) || 0);
        const partsCost = Math.max(0, parseFloat(parts.value) || 0);

        total.innerHTML = (laborCost + partsCost).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' <span>₾</span>';
    }

    labor.addEventListener('input', calculateTotal);
    parts.addEventListener('input', calculateTotal);

    if (photoInput && selectedFiles) {
        photoInput.addEventListener('change', function () {
            const files = Array.from(photoInput.files || []);

            if (!files.length) {
                selectedFiles.textContent = 'ახალი ფოტოები არჩეული არ არის';
                return;
            }

            selectedFiles.textContent = 'არჩეულია ' + files.length +
                (files.length === 1 ? ' ფოტო: ' : ' ფოტო: ') +
                files.map(function (file) {
                    return file.name;
                }).join(', ');
        });
    }

    calculateTotal();
});
</script>
@endpush
