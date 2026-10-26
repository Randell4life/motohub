<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>სერვისის ისტორია | Garage 33</title>
    <style>
        :root {
            --bg: #080d18;
            --panel: #101a2b;
            --panel-light: #16233a;
            --text: #edf4ff;
            --muted: #91a2bc;
            --blue: #168cff;
            --border: #253651;
            --green: #4ade80;
            --red: #f87171;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        a { color: inherit; text-decoration: none; }

        .container {
            width: min(1100px, 92%);
            margin: 0 auto;
        }

        header {
            border-bottom: 1px solid var(--border);
            background: #0b1220;
        }

        .nav {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .logo {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -1px;
        }

        .logo span { color: var(--blue); }

        .back {
            color: #b8c8df;
            font-size: 14px;
        }

        .back:hover { color: white; }

        main { padding: 42px 0 70px; }

        .eyebrow {
            color: var(--blue);
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        h1 {
            margin: 8px 0;
            font-size: clamp(28px, 5vw, 42px);
            line-height: 1.2;
        }

        .subtitle {
            margin: 0;
            color: var(--muted);
        }

        .bike-info {
            margin: 28px 0;
            padding: 20px 22px;
            background: linear-gradient(120deg, #122441, #101a2b);
            border: 1px solid #24456e;
            border-radius: 16px;
        }

        .bike-info strong {
            display: block;
            font-size: 20px;
        }

        .bike-info span {
            color: #9bb1ce;
            font-size: 14px;
        }

        .section-title {
            margin: 34px 0 16px;
            font-size: 22px;
        }

        .panel {
            padding: 24px;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .field { min-width: 0; }
        .full { grid-column: 1 / -1; }

        label {
            display: block;
            margin-bottom: 7px;
            color: #cbd8eb;
            font-size: 13px;
            font-weight: 700;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 13px;
            border: 1px solid #30415d;
            border-radius: 9px;
            outline: none;
            background: #0b1322;
            color: white;
            font: inherit;
            font-size: 14px;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px #168cff20;
        }

        textarea {
            min-height: 95px;
            resize: vertical;
        }

        .hint {
            display: block;
            margin-top: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 12px 19px;
            border: 0;
            border-radius: 9px;
            background: var(--blue);
            color: white;
            font-weight: 800;
            cursor: pointer;
        }

        .btn:hover { background: #0878e5; }

        .success {
            margin-bottom: 20px;
            padding: 13px 16px;
            border: 1px solid #236b46;
            border-radius: 9px;
            background: #10291e;
            color: var(--green);
        }

        .errors {
            margin-bottom: 20px;
            padding: 13px 16px;
            border: 1px solid #7f3030;
            border-radius: 9px;
            background: #301717;
            color: #fecaca;
        }

        .records {
            display: grid;
            gap: 14px;
        }

        .record {
            padding: 20px;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        .record-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
        }

        .record h3 {
            margin: 8px 0 4px;
            font-size: 18px;
        }

        .category {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 6px;
            background: #142e4d;
            color: #77bcff;
            font-size: 12px;
            font-weight: bold;
        }

        .date {
            color: var(--muted);
            font-size: 13px;
            white-space: nowrap;
        }

        .record p {
            margin: 10px 0;
            color: #b9c7db;
            white-space: pre-line;
        }

        .record-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 22px;
            margin-top: 14px;
            padding-top: 13px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 13px;
        }

        .cost {
            color: var(--green);
            font-weight: bold;
        }

        .empty {
            padding: 32px 20px;
            border: 1px dashed #344763;
            border-radius: 14px;
            color: var(--muted);
            text-align: center;
        }

        .pagination {
            margin-top: 22px;
        }

        .pagination nav > div {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 7px;
            background: var(--panel-light);
            color: var(--text);
        }

        @media (max-width: 650px) {
            main { padding-top: 28px; }
            .form-grid { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
            .panel { padding: 17px; }
            .record-top { flex-direction: column; gap: 6px; }
            .nav { min-height: 66px; }
        }
    </style>
</head>
<body>
<header>
    <div class="container nav">
        <a class="logo" href="{{ route('account.index') }}">Moto<span>Hub</span></a>
        <a class="back" href="{{ route('account.index') }}">← ჩემს ანგარიშზე დაბრუნება</a>
    </div>
</header>

<main class="container">
    <div class="eyebrow">პირადი მოტოციკლის პროფილი</div>
    <h1>სერვისის ისტორია</h1>
    <p class="subtitle">შეინახე ყველა სერვისი, შეკეთება და ხარჯი ერთ ადგილას.</p>

    <div class="bike-info">
        <strong>
            {{ $motorcycle->brand ?? '' }}
            {{ $motorcycle->model ?? 'ჩემი მოტოციკლი' }}
        </strong>
        <span>
            @if($motorcycle->year)
                გამოშვების წელი: {{ $motorcycle->year }}
            @endif
            @if($motorcycle->registration_number)
                · ნომერი: {{ $motorcycle->registration_number }}
            @endif
        </span>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="errors">
            <strong>შეამოწმე შეყვანილი მონაცემები:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2 class="section-title">＋ ახალი სერვისის დამატება</h2>

    <div class="panel">
        <form method="POST"
              action="{{ route('account.motorcycles.service.store', $motorcycle->id) }}">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="service_date">სერვისის თარიღი *</label>
                    <input id="service_date" type="date" name="service_date"
                           value="{{ old('service_date', now()->format('Y-m-d')) }}"
                           max="{{ now()->format('Y-m-d') }}" required>
                </div>

                <div class="field">
                    <label for="mileage">გარბენი (კმ)</label>
                    <input id="mileage" type="number" name="mileage"
                           min="0" step="1" value="{{ old('mileage') }}"
                           placeholder="მაგ. 12500">
                </div>

                <div class="field">
                    <label for="category">სერვისის კატეგორია *</label>
                    <select id="category" name="category" required>
                        <option value="">აირჩიე კატეგორია</option>
                        @foreach([
                            'ზეთის შეცვლა',
                            'ფილტრების შეცვლა',
                            'მუხრუჭები',
                            'საბურავები',
                            'ჯაჭვი და ტრანსმისია',
                            'ელექტროობა',
                            'ძრავი',
                            'რეგულარული შემოწმება',
                            'შეკეთება',
                            'სხვა'
                        ] as $category)
                            <option value="{{ $category }}"
                                @selected(old('category', 'სხვა') === $category)>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="title">შესრულებული სამუშაო *</label>
                    <input id="title" type="text" name="title"
                           value="{{ old('title') }}"
                           maxlength="255" required
                           placeholder="მაგ. ძრავის ზეთის შეცვლა">
                </div>

                <div class="field">
                    <label for="cost">ღირებულება (₾) *</label>
                    <input id="cost" type="number" name="cost"
                           min="0" max="99999999.99" step="0.01"
                           value="{{ old('cost', '0') }}" required>
                </div>

                <div class="field full">
                    <label for="description">სამუშაოს აღწერა</label>
                    <textarea id="description" name="description"
                              maxlength="5000"
                              placeholder="რა გაკეთდა მოტოციკლზე?">{{ old('description') }}</textarea>
                </div>

                <div class="field full">
                    <label for="notes">დამატებითი შენიშვნები</label>
                    <textarea id="notes" name="notes" maxlength="5000"
                              placeholder="მაგ. შემდეგი ზეთის შეცვლა 3000 კმ-ის შემდეგ">{{ old('notes') }}</textarea>
                </div>

                <div class="field full">
                    <button class="btn" type="submit">ჩანაწერის შენახვა →</button>
                </div>
            </div>
        </form>
    </div>

    <h2 class="section-title">ჩანაწერების ისტორია</h2>

    <div class="records">
        @forelse($serviceRecords as $record)
            <article class="record">
                <div class="record-top">
                    <div>
                        <span class="category">{{ $record->category }}</span>
                        <h3>{{ $record->title }}</h3>
                    </div>
                    <span class="date">
                        {{ $record->service_date->format('d.m.Y') }}
                    </span>
                </div>

                @if($record->description)
                    <p>{{ $record->description }}</p>
                @endif

                @if($record->notes)
                    <p><strong>შენიშვნა:</strong> {{ $record->notes }}</p>
                @endif

                <div class="record-meta">
                    @if($record->mileage !== null)
                        <span>გარბენი: {{ number_format($record->mileage) }} კმ</span>
                    @endif
                    <span class="cost">
                        ღირებულება: {{ number_format((float) $record->cost, 2) }} ₾
                    </span>
                </div>
            </article>
        @empty
            <div class="empty">
                <div style="font-size: 32px; margin-bottom: 8px;">🔧</div>
                <strong>სერვისის ჩანაწერები ჯერ არ არის</strong>
                <div>დაამატე პირველი ჩანაწერი ზემოთ მოცემული ფორმით.</div>
            </div>
        @endforelse
    </div>

    @if($serviceRecords->hasPages())
        <div class="pagination">
            {{ $serviceRecords->links() }}
        </div>
    @endif
</main>
</body>
</html>