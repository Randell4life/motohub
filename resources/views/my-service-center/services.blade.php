@extends('my-service-center.layout')

@section('title', 'მომსახურებები | MotoHub')
@section('page-title', 'მომსახურებების მართვა')
@section('page-subtitle', 'მომსახურებების, ფასებისა და ხანგრძლივობის მართვა')

@section('content')
    <style>
        .sv-hero {
            padding: 25px;
            margin-bottom: 22px;
            border: 1px solid #25486d;
            border-radius: 16px;
            background:
                radial-gradient(ellipse at 100% 0%, rgba(8,124,255,.2), transparent 45%),
                linear-gradient(120deg, #111d2c, #0a101a);
        }

        .sv-eyebrow {
            color: #54b2ff;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .sv-hero h2 {
            margin: 10px 0 7px;
            font-size: 25px;
        }

        .sv-hero p {
            margin: 0;
            line-height: 1.7;
        }

        .sv-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 17px;
        }

        .sv-heading h3 {
            margin: 0;
            font-size: 17px;
        }

        .sv-count {
            padding: 7px 10px;
            border: 1px solid #294c73;
            border-radius: 8px;
            background: rgba(8,124,255,.1);
            color: #9bcfff;
            font-size: 12px;
            white-space: nowrap;
        }

        .sv-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 17px;
            margin-bottom: 24px;
        }

        .sv-card {
            overflow: hidden;
            border: 1px solid #263a52;
            border-radius: 14px;
            background: linear-gradient(145deg, #111c2a, #0b111a);
        }

        .sv-card-top {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            padding: 19px;
            border-bottom: 1px solid #223247;
        }

        .sv-icon {
            display: grid;
            place-items: center;
            flex: 0 0 43px;
            height: 43px;
            border: 1px solid #294c73;
            border-radius: 11px;
            background: rgba(8,124,255,.12);
            color: #69b8ff;
            font-size: 21px;
        }

        .sv-name {
            margin: 0 0 7px;
            font-size: 16px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .sv-state {
            display: inline-flex;
            padding: 5px 8px;
            border: 1px solid #235b49;
            border-radius: 6px;
            background: #0c201b;
            color: #58e0ae;
            font-size: 10px;
            font-weight: 800;
        }

        .sv-state.off {
            border-color: #4b4650;
            background: #1b1920;
            color: #aeb7c6;
        }

        .sv-price {
            margin-left: auto;
            text-align: right;
            white-space: nowrap;
        }

        .sv-price strong {
            display: block;
            color: #f3f8ff;
            font-size: 20px;
        }

        .sv-price small {
            color: #8fa3bb;
            font-size: 11px;
        }

        .sv-card-body {
            padding: 19px;
        }

        .sv-description {
            min-height: 38px;
            margin: 0 0 16px;
            color: #91a5be;
            font-size: 13px;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .sv-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 18px;
        }

        .sv-meta span {
            padding: 7px 9px;
            border: 1px solid #293c53;
            border-radius: 7px;
            background: #0a121d;
            color: #b7c9df;
            font-size: 11px;
        }

        .sv-edit {
            padding-top: 17px;
            border-top: 1px solid #243449;
        }

        .sv-edit summary {
            color: #69b8ff;
            font-size: 13px;
            font-weight: 750;
            cursor: pointer;
        }

        .sv-form {
            display: grid;
            gap: 14px;
            margin-top: 18px;
        }

        .sv-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px;
        }

        .sv-check {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #c8d7e9;
            font-size: 13px;
        }

        .sv-check input {
            accent-color: #1682ff;
            width: 16px;
            height: 16px;
        }

        .sv-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 4px;
        }

        .sv-delete {
            padding: 10px 13px;
            border: 1px solid #643543;
            border-radius: 9px;
            background: #28141b;
            color: #ff91a2;
            font-size: 12px;
            font-weight: 750;
            cursor: pointer;
        }

        .sv-add {
            margin-top: 24px;
        }

        .sv-empty {
            padding: 35px 20px;
            border: 1px dashed #30445c;
            border-radius: 12px;
            color: #91a5be;
            text-align: center;
        }

        @media (max-width: 850px) {
            .sv-list {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .sv-form-grid {
                grid-template-columns: 1fr;
            }

            .sv-card-top {
                flex-wrap: wrap;
            }

            .sv-price {
                margin-left: 56px;
                text-align: left;
            }
        }
    </style>

    <section class="sv-hero">
        <div class="sv-eyebrow">MOTOHUB / SERVICE CATALOG</div>
        <h2>მომსახურებების კატალოგი</h2>
        <p class="mh-muted">
            მართე სერვისები, ფასები და თითოეული სამუშაოს სავარაუდო ხანგრძლივობა.
        </p>
    </section>

    <div class="sv-heading">
        <h3>შენი მომსახურებები</h3>
        <span class="sv-count">სულ: {{ $services->count() }}</span>
    </div>

    @if($services->count())
        <div class="sv-list">
            @foreach($services as $service)
                <article class="sv-card">
                    <div class="sv-card-top">
                        <div class="sv-icon">🔧</div>

                        <div style="flex:1;min-width:0">
                            <h3 class="sv-name">{{ $service->name }}</h3>
                            <span class="sv-state {{ $service->is_active ? '' : 'off' }}">
                                {{ $service->is_active ? '● აქტიური' : '○ გამორთული' }}
                            </span>
                        </div>

                        <div class="sv-price">
                            <strong>
                                @if($service->price_type === 'quote')
                                    შეთანხმებით
                                @else
                                    {{ number_format((float) $service->price, 2) }} ₾
                                @endif
                            </strong>
                            <small>
                                @if($service->price_type === 'from')
                                    საწყისი ფასი
                                @elseif($service->price_type === 'quote')
                                    ინდივიდუალური ფასი
                                @else
                                    ფიქსირებული ფასი
                                @endif
                            </small>
                        </div>
                    </div>

                    <div class="sv-card-body">
                        <p class="sv-description">
                            {{ $service->description ?: 'მომსახურების აღწერა დამატებული არ არის.' }}
                        </p>

                        <div class="sv-meta">
                            <span>◷ {{ $service->duration_minutes }} წუთი</span>
                            <span>
                                {{ $service->price_type === 'quote'
                                    ? 'ფასი შეთანხმებით'
                                    : ($service->price_type === 'from' ? 'ფასი იწყება' : 'ფიქსირებული ფასი') }}
                            </span>
                        </div>

                        <details class="sv-edit">
                            <summary>✎ მომსახურების რედაქტირება</summary>

                            <form class="sv-form"
                                  method="POST"
                                  action="{{ route('my-service-center.services.update', $service) }}">
                                @csrf
                                @method('PUT')

                                <div class="mh-field">
                                    <label>მომსახურების სახელი</label>
                                    <input type="text" name="name"
                                           value="{{ $service->name }}" required>
                                </div>

                                <div class="sv-form-grid">
                                    <div class="mh-field">
                                        <label>ფასის ტიპი</label>
                                        <select name="price_type" required>
                                            <option value="fixed" @selected($service->price_type === 'fixed')>
                                                ფიქსირებული
                                            </option>
                                            <option value="from" @selected($service->price_type === 'from')>
                                                საწყისი ფასი
                                            </option>
                                            <option value="quote" @selected($service->price_type === 'quote')>
                                                შეთანხმებით
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mh-field">
                                        <label>ფასი (₾)</label>
                                        <input type="number" name="price"
                                               min="0" step="0.01"
                                               value="{{ $service->price }}">
                                    </div>

                                    <div class="mh-field">
                                        <label>ხანგრძლივობა (წუთი)</label>
                                        <input type="number" name="duration_minutes"
                                               min="1" value="{{ $service->duration_minutes }}" required>
                                    </div>
                                </div>

                                <div class="mh-field">
                                    <label>აღწერა</label>
                                    <textarea name="description" rows="3">{{ $service->description }}</textarea>
                                </div>

                                <label class="sv-check">
                                    <input type="checkbox" name="is_active" value="1"
                                           @checked($service->is_active)>
                                    მომსახურება აქტიურია
                                </label>

                                <button class="mh-btn" type="submit">
                                    ცვლილებების შენახვა
                                </button>
                            </form>

                            <form method="POST"
                                  action="{{ route('my-service-center.services.delete', $service) }}"
                                  style="margin-top:12px"
                                  onsubmit="return confirm('ნამდვილად გსურს ამ მომსახურების წაშლა?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="sv-delete">
                                    მომსახურების წაშლა
                                </button>
                            </form>
                        </details>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="sv-empty">
            <div style="font-size:28px;margin-bottom:10px">🔧</div>
            <strong style="display:block;color:#eaf3ff;margin-bottom:7px">
                მომსახურებები ჯერ არ დაგიმატებია
            </strong>
            დაამატე პირველი მომსახურება ქვემოთ მოცემული ფორმით.
        </div>
    @endif

    <section class="mh-card sv-add" style="padding:23px">
        <div class="sv-heading">
            <h3>+ ახალი მომსახურების დამატება</h3>
        </div>

        <p class="mh-muted" style="margin-top:0;margin-bottom:22px">
            შეავსე მომსახურების ინფორმაცია და დაამატე კატალოგში.
        </p>

        <form method="POST" action="{{ route('my-service-center.services.store') }}"
              style="display:grid;gap:17px">
            @csrf

            <div class="sv-form-grid">
                <div class="mh-field">
                    <label>მომსახურების სახელი</label>
                    <input type="text" name="name"
                           value="{{ old('name') }}"
                           placeholder="მაგ. ზეთის შეცვლა" required>
                </div>

                <div class="mh-field">
                    <label>ფასის ტიპი</label>
                    <select name="price_type" required>
                        <option value="fixed">ფიქსირებული ფასი</option>
                        <option value="from">ფასი იწყება</option>
                        <option value="quote">შეთანხმებით</option>
                    </select>
                </div>

                <div class="mh-field">
                    <label>ფასი (₾)</label>
                    <input type="number" name="price"
                           min="0" step="0.01"
                           value="{{ old('price', 0) }}">
                </div>

                <div class="mh-field">
                    <label>ხანგრძლივობა (წუთი)</label>
                    <input type="number" name="duration_minutes"
                           min="1" value="{{ old('duration_minutes', 30) }}" required>
                </div>
            </div>

            <div class="mh-field">
                <label>აღწერა</label>
                <textarea name="description" rows="3"
                          placeholder="მოკლედ აღწერე მომსახურება...">{{ old('description') }}</textarea>
            </div>

            <label class="sv-check">
                <input type="checkbox" name="is_active" value="1" checked>
                მომსახურება აქტიურია
            </label>

            <div>
                <button class="mh-btn" type="submit">
                    + მომსახურების დამატება
                </button>
            </div>
        </form>
    </section>
@endsection
