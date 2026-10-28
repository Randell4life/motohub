<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>განცხადების რედაქტირება | MotoHub</title>
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
        .hint{font-size:12px;color:#98a8c2}.preview{max-width:220px;max-height:180px;object-fit:contain;border-radius:10px;border:1px solid #243653}
        @media(max-width:620px){.grid{grid-template-columns:1fr}.wide{grid-column:auto}h1{font-size:27px}}
    </style>
</head>
<body>
<div class="wrap">
    <a class="logo" href="{{ url('/') }}">MOTO<span>HUB</span></a>
    <div class="panel">
        <h1>განცხადების რედაქტირება</h1>
        <p class="muted">ცვლილებების შენახვის შემდეგ განცხადება ხელახლა გაიგზავნება შემოწმებაზე.</p>

        @if($errors->any())
            <div class="errors">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('account.part-listings.update', $listing) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid">
                <div class="field wide">
                    <label for="title">ნაწილის დასახელება *</label>
                    <input id="title" name="title" value="{{ old('title', $listing->title) }}" required maxlength="255">
                </div>
                <div class="field">
                    <label for="brand">ბრენდი</label>
                    <input id="brand" name="brand" value="{{ old('brand', $listing->brand) }}" maxlength="255">
                </div>
                <div class="field">
                    <label for="category">კატეგორია</label>
                    <input id="category" name="category" value="{{ old('category', $listing->category) }}" maxlength="255">
                </div>
                <div class="field">
                    <label for="condition">მდგომარეობა *</label>
                    <select id="condition" name="condition" required>
                        <option value="new" @selected(old('condition', $listing->condition) === 'new')>ახალი</option>
                        <option value="used" @selected(old('condition', $listing->condition) === 'used')>მეორადი</option>
                    </select>
                </div>
                <div class="field">
                    <label for="price">ფასი (₾) *</label>
                    <input id="price" type="number" name="price" value="{{ old('price', $listing->price) }}" min="0" step="0.01" required>
                </div>
                <div class="field">
                    <label for="part_number">ნაწილის კოდი</label>
                    <input id="part_number" name="part_number" value="{{ old('part_number', $listing->part_number) }}" maxlength="255">
                </div>
                <div class="field">
                    <label for="compatible_motorcycle">რომელ მოტოციკლს ერგება</label>
                    <input id="compatible_motorcycle" name="compatible_motorcycle" value="{{ old('compatible_motorcycle', $listing->compatible_motorcycle) }}" maxlength="255">
                </div>
                <div class="field wide">
                    <label for="description">აღწერა</label>
                    <textarea id="description" name="description" maxlength="10000">{{ old('description', $listing->description) }}</textarea>
                </div>
                @if($listing->image_path)
                    <div class="field wide">
                        <label>მიმდინარე ფოტო</label>
                        <img class="preview" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($listing->image_path) }}" alt="ნაწილის ფოტო">
                    </div>
                @endif
                <div class="field wide">
                    <label for="image">ახალი ფოტო (არასავალდებულო)</label>
                    <input id="image" type="file" name="image" accept="image/*">
                    <div class="hint">თუ ფოტოს არ აირჩევ, მიმდინარე ფოტო შენარჩუნდება. მაქსიმუმ 5 MB.</div>
                </div>
            </div>
            <div class="actions">
                <button class="btn" type="submit">ცვლილებების შენახვა</button>
                <a class="btn secondary" href="{{ route('account.part-listings.index') }}">უკან დაბრუნება</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
