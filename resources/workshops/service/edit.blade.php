<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ჩანაწერის რედაქტირება | MotoHub</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 24px;
            font-family: Arial, sans-serif;
            background: #0b1120;
            color: #eef3ff;
        }
        .container { max-width: 850px; margin: auto; }
        .panel {
            margin-top: 24px;
            padding: 24px;
            background: #111b2e;
            border: 1px solid #24334d;
            border-radius: 16px;
        }
        .form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }
        label { display: block; }
        input, select, textarea {
            width: 100%;
            margin-top: 7px;
            padding: 12px;
            border: 1px solid #34445f;
            border-radius: 9px;
            background: #0b1120;
            color: white;
            font: inherit;
        }
        textarea { min-height: 100px; resize: vertical; }
        .full { grid-column: 1 / -1; }
        .btn {
            display: inline-block;
            padding: 11px 16px;
            border: 0;
            border-radius: 9px;
            background: #1687ff;
            color: white;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }
        .muted { color: #94a3b8; }
        .error { color: #fca5a5; }
        @media (max-width: 600px) {
            .form { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
        }
    </style>
</head>
<body>
<div class="container">
    <a class="btn" href="{{ route('workshop.customers.motorcycles.service.index', ['customer' => $customerId, 'motorcycle' => $motorcycle->id]) }}">
        ← უკან დაბრუნება
    </a>

    <h1>მომსახურების რედაქტირება</h1>
    <p class="muted">
        {{ $motorcycle->title ?: trim(($motorcycle->brand ?? '') . ' ' . ($motorcycle->model ?? '')) }}
        — {{ $motorcycle->year ?? '—' }}
    </p>

    @if ($errors->any())
        <div class="panel error">
            <strong>შეამოწმე შეყვანილი მონაცემები:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="panel">
        <form method="POST" class="form"
              action="{{ route('workshop.customers.motorcycles.service.update', ['customer' => $customerId, 'motorcycle' => $motorcycle->id, 'record' => $serviceRecord->id]) }}">
            @csrf
            @method('PUT')

            <div>
                <label for="service_date">მომსახურების თარიღი *</label>
                <input id="service_date" type="date" name="service_date"
                       value="{{ old('service_date', \Illuminate\Support\Carbon::parse($serviceRecord->service_date)->format('Y-m-d')) }}"
                       max="{{ date('Y-m-d') }}" required>
            </div>

            <div>
                <label for="mileage">გარბენი (კმ)</label>
                <input id="mileage" type="number" name="mileage" min="0"
                       value="{{ old('mileage', $serviceRecord->mileage) }}">
            </div>

            <div>
                <label for="category">კატეგორია *</label>
                <select id="category" name="category" required>
                    <option value="">აირჩიე კატეგორია</option>
                    @foreach (['ზეთის შეცვლა', 'ძრავი', 'მუხრუჭები', 'საბურავები', 'ელექტროობა', 'გადაცემათა სისტემა', 'სხვა'] as $category)
                        <option value="{{ $category }}" @selected(old('category', $serviceRecord->category) === $category)>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="title">შესრულებული სამუშაო *</label>
                <input id="title" name="title" maxlength="255"
                       value="{{ old('title', $serviceRecord->title) }}" required>
            </div>

            <div>
                <label for="cost">ღირებულება (₾) *</label>
                <input id="cost" type="number" name="cost" min="0"
                       max="99999999.99" step="0.01"
                       value="{{ old('cost', $serviceRecord->cost) }}" required>
            </div>

            <div class="full">
                <label for="description">სამუშაოს აღწერა</label>
                <textarea id="description" name="description">{{ old('description', $serviceRecord->description) }}</textarea>
            </div>

            <div class="full">
                <label for="notes">შენიშვნები</label>
                <textarea id="notes" name="notes">{{ old('notes', $serviceRecord->notes) }}</textarea>
            </div>

            <div class="full">
                <button class="btn" type="submit">ცვლილებების შენახვა</button>
            </div>
        </form>
    </section>
</div>
</body>
</html>
