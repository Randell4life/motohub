@extends('my-service-center.layout')

@section('title', 'სამუშაო საათები | MotoHub')
@section('page-title', 'სამუშაო საათები')
@section('page-subtitle', 'სერვისცენტრის ყოველკვირეული სამუშაო განრიგი')

@section('content')
    <style>
        .hr-hero {
            padding: 25px;
            margin-bottom: 22px;
            border: 1px solid #25486d;
            border-radius: 16px;
            background:
                radial-gradient(ellipse at 100% 0%, rgba(8,124,255,.2), transparent 45%),
                linear-gradient(120deg, #111d2c, #0a101a);
        }

        .hr-eyebrow {
            color: #54b2ff;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .hr-hero h2 {
            margin: 10px 0 7px;
            font-size: 25px;
        }

        .hr-hero p {
            margin: 0;
            line-height: 1.7;
        }

        .hr-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 17px;
        }

        .hr-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 11px;
            border: 1px solid #294c73;
            border-radius: 8px;
            background: rgba(8,124,255,.1);
            color: #b9dcff;
            font-size: 12px;
        }

        .hr-section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 17px;
        }

        .hr-section-heading h3 {
            margin: 0;
            font-size: 17px;
        }

        .hr-list {
            display: grid;
            gap: 11px;
        }

        .hr-day {
            display: grid;
            grid-template-columns: minmax(145px, 1fr) minmax(125px, .8fr) minmax(125px, .8fr) auto;
            align-items: center;
            gap: 16px;
            padding: 17px;
            border: 1px solid #263a52;
            border-radius: 12px;
            background: linear-gradient(110deg, #111b29, #0c131e);
            transition: border-color .2s ease;
        }

        .hr-day:hover {
            border-color: #365b83;
        }

        .hr-day.is-closed {
            background: #0c1119;
            border-color: #202b3a;
        }

        .hr-day-name {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .hr-day-icon {
            display: grid;
            place-items: center;
            flex: 0 0 38px;
            height: 38px;
            border: 1px solid #294c73;
            border-radius: 10px;
            background: rgba(8,124,255,.1);
            color: #69b8ff;
            font-size: 17px;
        }

        .hr-day-title {
            display: block;
            color: #f2f7ff;
            font-size: 14px;
            font-weight: 750;
        }

        .hr-day-sub {
            display: block;
            margin-top: 4px;
            color: #8195ad;
            font-size: 11px;
        }

        .hr-field {
            display: grid;
            gap: 7px;
        }

        .hr-field label {
            color: #91a5be;
            font-size: 11px;
        }

        .hr-field input[type="time"] {
            box-sizing: border-box;
            width: 100%;
            min-width: 0;
            padding: 10px;
            border: 1px solid #2b4058;
            border-radius: 8px;
            background: #080e17;
            color: #f3f7ff;
            color-scheme: dark;
        }

        .hr-field input[type="time"]:focus {
            outline: none;
            border-color: #54b2ff;
            box-shadow: 0 0 0 3px rgba(8,124,255,.12);
        }

        .hr-closed-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #b5c5d9;
            font-size: 12px;
            white-space: nowrap;
            cursor: pointer;
        }

        .hr-closed-toggle input {
            width: 16px;
            height: 16px;
            accent-color: #1682ff;
        }

        .hr-closed-label {
            color: #ff9aab;
        }

        .hr-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 22px;
            padding-top: 20px;
            border-top: 1px solid #243449;
        }

        .hr-note {
            max-width: 420px;
            color: #91a5be;
            font-size: 12px;
            line-height: 1.7;
        }

        @media (max-width: 850px) {
            .hr-day {
                grid-template-columns: 1fr 1fr;
            }

            .hr-day-name {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 480px) {
            .hr-day {
                gap: 13px;
                padding: 14px;
            }

            .hr-day-name {
                grid-column: 1 / -1;
            }

            .hr-closed-toggle {
                grid-column: 1 / -1;
            }

            .hr-hero {
                padding: 20px;
            }
        }
    </style>

    @php
        $days = [
            0 => 'კვირა',
            1 => 'ორშაბათი',
            2 => 'სამშაბათი',
            3 => 'ოთხშაბათი',
            4 => 'ხუთშაბათი',
            5 => 'პარასკევი',
            6 => 'შაბათი',
        ];

        $hoursByDay = $hours->keyBy('day_of_week');
        $openDays = $hours->where('is_closed', false)->count();
    @endphp

    <section class="hr-hero">
        <div class="hr-eyebrow">MOTOHUB / OPERATING HOURS</div>
        <h2>სამუშაო განრიგი</h2>
        <p class="mh-muted">
            განსაზღვრე სამუშაო დღეები და საათები, რომ მომხმარებლებმა
            სერვისცენტრის ხელმისაწვდომობა სწორად დაინახონ.
        </p>

        <div class="hr-summary">
            <span class="hr-chip">◷ კვირის განრიგი</span>
            <span class="hr-chip">სამუშაო დღეები: {{ $openDays }} / 7</span>
        </div>
    </section>

    <section class="mh-card" style="padding:22px">
        <div class="hr-section-heading">
            <h3>კვირის განრიგი</h3>
            <span class="mh-muted" style="font-size:12px">
                7 დღის მართვა
            </span>
        </div>

        <form method="POST" action="{{ route('my-service-center.hours.update') }}">
            @csrf
            @method('PUT')

            <div class="hr-list">
                @foreach($days as $day => $dayName)
                    @php
                        $hour = $hoursByDay->get($day);
                        $isClosed = (bool) ($hour?->is_closed ?? false);
                    @endphp

                    <div class="hr-day {{ $isClosed ? 'is-closed' : '' }}">
                        <div class="hr-day-name">
                            <span class="hr-day-icon">◷</span>
                            <div>
                                <span class="hr-day-title">{{ $dayName }}</span>
                                <span class="hr-day-sub">
                                    {{ $isClosed ? 'დასვენების დღე' : 'სამუშაო დღე' }}
                                </span>
                            </div>
                        </div>

                        <div class="hr-field">
                            <label for="opens-{{ $day }}">გახსნის დრო</label>
                            <input id="opens-{{ $day }}"
                                   type="time"
                                   name="hours[{{ $day }}][opens_at]"
                                   value="{{ substr((string) ($hour?->opens_at ?? '09:00'), 0, 5) }}">
                        </div>

                        <div class="hr-field">
                            <label for="closes-{{ $day }}">დახურვის დრო</label>
                            <input id="closes-{{ $day }}"
                                   type="time"
                                   name="hours[{{ $day }}][closes_at]"
                                   value="{{ substr((string) ($hour?->closes_at ?? '18:00'), 0, 5) }}">
                        </div>

                        <label class="hr-closed-toggle {{ $isClosed ? 'hr-closed-label' : '' }}">
                            <input type="checkbox"
                                   name="hours[{{ $day }}][is_closed]"
                                   value="1"
                                   @checked($isClosed)>
                            დაკეტილია
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="hr-footer">
                <p class="hr-note">
                    ცვლილებები ძალაში შევა მხოლოდ შენახვის შემდეგ.
                    შენახვამდე გადაამოწმე თითოეული დღის გახსნისა და დახურვის დრო.
                </p>

                <button type="submit" class="mh-btn">
                    ✓ განრიგის შენახვა
                </button>
            </div>
        </form>
    </section>
@endsection
