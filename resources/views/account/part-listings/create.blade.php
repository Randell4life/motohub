<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>განცხადების დამატება | MotoHub</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#070a12;color:#f4f8ff;font-family:Arial,sans-serif}
        a{color:inherit;text-decoration:none}.wrap{max-width:900px;margin:auto;padding:30px 18px}
        .logo{font-size:25px;font-weight:900;display:inline-block;margin-bottom:28px}.logo span{color:#1677ff}
        .panel{background:#0d1422;border:1px solid #243653;border-radius:16px;padding:clamp(20px,4vw,34px)}
        h1{margin:0 0 8px;font-size:32px}.muted{color:#98a8c2;line-height:1.6}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-top:25px}
        .field{display:flex;flex-direction:column;gap:8px}.wide{grid-column:1/-1}
        label{font-size:14px;font-weight:700}input,select,textarea{width:100%;padding:13px;background:#080e19;color:#f4f8ff;border:1px solid #2a3b58;border-radius:8px;font:inherit}
        textarea{min-height:125px;resize:vertical}input:focus,select:focus,textarea:focus{outline:1px solid #1677ff;border-color:#1677ff}
        .btn{display:inline-block;border:0;border-radius:8px;background:#1677ff;color:white;padding:13px 18px;font-weight:700;cursor:pointer}
        .secondary{background:#152239;border:1px solid #2a3b58}.actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:25px}
        .errors{background:#3b1e28;border:1px solid #713348;color:#ffb5c0;padding:14px;border-radius:8px}
        .hint{font-size:12px;color:#98a8c2}
        @media(max-width:620px){.grid{grid-template-columns:1fr}.wide{grid-column:auto}h1{font-size:27px}}
    </style>
</head>
<body>
<div class="wrap">
    <a class="logo" href="{{ url('/') }}">MOTO<span>HUB</span></a>
    <div class="panel">
        <h1>განცხადების დამატება</h1>
        <p class="muted">შეავსე ნაწილის ინფორმაცია. განცხადება გამოქვეყნებამდე შემოწმების პროცესში იქნება.</p>

        @if($errors->any())
            <div class="errors">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('account.part-listings.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid">
                <div class="field wide">
                    <label for="title">ნაწილის დასახელება *</label>
                    <input id="title" name="title" value="{{ old('title') }}" required maxlength="255" placeholder="მაგ. Yamaha R1 წინა ფარი">
                </div>
                <div class="field">
                    <label for="brand">ბრენდი</label>
                    <input id="brand" name="brand" value="{{ old('brand') }}" maxlength="255" placeholder="მაგ. Yamaha">
                </div>
                <div class="field">
                    <label for="category">კატეგორია</label>
                    <input id="category" name="category" value="{{ old('category') }}" maxlength="255" placeholder="მაგ. ძრავი, პლასტმასი">
                </div>
                <div class="field">
                    <label for="condition">მდგომარეობა *</label>
                    <select id="condition" name="condition" required>
                        <option value="new" @selected(old('condition', 'new') === 'new')>ახალი</option>
                        <option value="used" @selected(old('condition') === 'used')>მეორადი</option>
                    </select>
                </div>
                <div class="field">
                    <label for="price">ფასი (₾) *</label>
                    <input id="price" type="number" name="price" value="{{ old('price') }}" min="0" step="0.01" required placeholder="0.00">
                </div>
                <div class="field">
                    <label for="part_number">ნაწილის კოდი</label>
                    <input id="part_number" name="part_number" value="{{ old('part_number') }}" maxlength="255" placeholder="OEM / Part number">
                </div>
                <div class="field">
                    <label for="compatible_motorcycle">რომელ მოტოციკლს ერგება</label>
                    <input id="compatible_motorcycle" name="compatible_motorcycle" value="{{ old('compatible_motorcycle') }}" maxlength="255" placeholder="მაგ. Yamaha R1 2015–2020">
                </div>
                <div class="field">
                    <label for="part_type">ნაწილის ტიპი</label>
                    <select id="part_type" name="part_type">
                        <option value="">არ არის მითითებული</option>
                        <option value="oem" @selected(old('part_type') === 'oem')>ორიგინალი (OEM)</option>
                        <option value="aftermarket" @selected(old('part_type') === 'aftermarket')>Aftermarket</option>
                        <option value="used" @selected(old('part_type') === 'used')>მეორადი / მოხსნილი</option>
                    </select>
                </div>
                <div class="field">
                    <label for="motorcycle_brand">მოტოციკლის ბრენდი</label>
                    <input id="motorcycle_brand" name="motorcycle_brand" value="{{ old('motorcycle_brand') }}" maxlength="100" placeholder="მაგ. Yamaha">
                </div>
                <div class="field">
                    <label for="motorcycle_model">მოტოციკლის მოდელი</label>
                    <input id="motorcycle_model" name="motorcycle_model" value="{{ old('motorcycle_model') }}" maxlength="120" placeholder="მაგ. R1">
                </div>
                <div class="field">
                    <label for="motorcycle_year">გამოშვების წელი</label>
                    <input id="motorcycle_year" type="number" name="motorcycle_year" value="{{ old('motorcycle_year') }}" min="1900" max="2100" placeholder="მაგ. 2016">
                </div>
                <div class="field">
                    <label for="engine_cc">ძრავის მოცულობა (cc)</label>
                    <input id="engine_cc" type="number" name="engine_cc" value="{{ old('engine_cc') }}" min="1" max="10000" placeholder="მაგ. 1000">
                </div>
                <div class="field wide">
                    <label for="description">აღწერა</label>
                    <textarea id="description" name="description" maxlength="10000" placeholder="მიუთითე ნაწილის მდგომარეობა და სხვა მნიშვნელოვანი ინფორმაცია">{{ old('description') }}</textarea>
                </div>
                <div class="field wide">
                    <label for="image">ნაწილის ფოტო</label>
                    <input id="image" type="file" name="image" accept="image/*">
                    <div class="hint">მაქსიმალური ზომა: 5 MB. ატვირთე მკაფიო ფოტო.</div>
                </div>
            </div>
            <div class="actions">
                <button class="btn" type="submit">განცხადების გაგზავნა</button>
                <a class="btn secondary" href="{{ route('account.part-listings.index') }}">გაუქმება</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
