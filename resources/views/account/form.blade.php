<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $motorcycle->exists ? 'განცხადების რედაქტირება' : 'ახალი განცხადება' }} | MotoHub</title>

    <style>

* {
    box-sizing: border-box;
}

:root {
    color-scheme: dark;
    --bg: #080b12;
    --panel: #101622;
    --panel-soft: #0c111b;
    --border: #222e42;
    --text: #f1f5fc;
    --muted: #8998ae;
    --blue: #2585ff;
    --blue-light: #65b1ff;
}


body {
    margin: 0;
    min-height: 100vh;
    padding: 42px 0 64px;
    position: relative;
    isolation: isolate;
    overflow-x: hidden;

    background:
        radial-gradient(
            ellipse at 12% 8%,
            rgba(37, 133, 255, 0.18) 0%,
            transparent 32%
        ),
        radial-gradient(
            ellipse at 90% 38%,
            rgba(0, 85, 190, 0.13) 0%,
            transparent 35%
        ),
        radial-gradient(
            ellipse at 50% 100%,
            rgba(22, 65, 125, 0.16) 0%,
            transparent 42%
        ),
        linear-gradient(
            135deg,
            #050810 0%,
            #0a1120 48%,
            #070b14 100%
        );

    color: var(--text);
    font-family: Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
}

body::before {
    content: "";
    position: fixed;
    inset: 0;
    z-index: -1;
    pointer-events: none;

    background-image:
        linear-gradient(
            rgba(120, 170, 255, 0.025) 1px,
            transparent 1px
        ),
        linear-gradient(
            90deg,
            rgba(120, 170, 255, 0.025) 1px,
            transparent 1px
        );

    background-size: 42px 42px;

    mask-image: linear-gradient(
        to bottom,
        black,
        transparent 85%
    );
}


.container {
    width: min(920px, 92%);
    margin: auto;
}

.container > p {
    margin: 0 0 22px;
}

a {
    color: var(--blue-light);
    text-decoration: none;
    transition: color .2s ease;
}

a:hover {
    color: #fff;
}

.card {
    padding: clamp(22px, 4vw, 42px);
    background: linear-gradient(145deg, #121a29, #0d121c 70%);
    border: 1px solid var(--border);
    border-radius: 20px;
    
box-shadow:
    0 24px 70px rgba(0, 0, 0, 0.45),
    0 0 35px rgba(37, 133, 255, 0.08),
    0 0 1px rgba(101, 177, 255, 0.35);

}

h1 {
    margin: 0 0 12px;
    font-size: clamp(27px, 4vw, 38px);
    line-height: 1.2;
    letter-spacing: -1px;
}

.muted {
    color: var(--muted);
    font-size: 14px;
    line-height: 1.7;
}

.fields {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 23px 20px;
    margin-top: 30px;
}

.field {
    min-width: 0;
    margin-top: 22px;
}

.fields > .field {
    margin-top: 0;
}

.full {
    grid-column: 1 / -1;
}

label {
    display: block;
    margin-bottom: 9px;
    color: #dce7f7;
    font-size: 13px;
    font-weight: 600;
    line-height: 1.5;
}

input,
select,
textarea {
    display: block;
    width: 100%;
    min-height: 48px;
    padding: 13px 14px;
    background: #090e17;
    color: var(--text);
    border: 1px solid #29364b;
    border-radius: 10px;
    outline: none;
    font: inherit;
    font-size: 14px;
    transition: border-color .2s, box-shadow .2s, background .2s;
}

input:hover,
select:hover,
textarea:hover {
    border-color: #405574;
}

input:focus,
select:focus,
textarea:focus {
    border-color: var(--blue);
    background: #0b1422;
    box-shadow: 0 0 0 3px #2585ff20;
}

input::placeholder,
textarea::placeholder {
    color: #65748a;
}

.form-section {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-top: 30px;
    padding-bottom: 18px;
    border-bottom: 1px solid var(--border);
}

.section-number {
    display: grid;
    place-items: center;
    flex-shrink: 0;
    width: 38px;
    height: 38px;
    border: 1px solid #28558b;
    border-radius: 10px;
    background: #142944;
    color: var(--blue-light);
    font-size: 13px;
    font-weight: 700;
}

.form-section h2 {
    margin: 2px 0 5px;
    font-size: 17px;
}

.form-section p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.6;
}


input[type="file"] {
    display: block;
    width: 100%;
    min-height: 100px;
    padding: 28px 18px;
    border: 1px dashed #347ac2;
    border-radius: 14px;
    background: linear-gradient(135deg, #101f33, #0b1320);
    color: #a9bad0;
    font-size: 13px;
    cursor: pointer;
    transition: border-color .2s, background .2s, box-shadow .2s;
}

input[type="file"]:hover {
    border-color: #65b1ff;
    background: linear-gradient(135deg, #142943, #0d1828);
    box-shadow: 0 0 22px #2585ff12;
}

input[type="file"]:focus-visible {
    outline: 2px solid #2585ff;
    outline-offset: 3px;
}

input[type="file"]::file-selector-button {
    margin-right: 14px;
    padding: 11px 16px;
    border: 1px solid #347ac2;
    border-radius: 9px;
    background: #17375b;
    color: #d8eaff;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: background .2s, border-color .2s;
}

input[type="file"]::file-selector-button:hover {
    border-color: #65b1ff;
    background: #205087;
}


input[type="file"]::file-selector-button {
    margin-right: 12px;
    padding: 9px 12px;
    border: 1px solid #315b8b;
    border-radius: 7px;
    background: #142b47;
    color: #9bcaff;
    font-weight: 600;
    cursor: pointer;
}

textarea {
    min-height: 150px;
    resize: vertical;
    line-height: 1.7;
}

.error {
    margin-top: 8px;
    color: #ff8997;
    font-size: 13px;
    line-height: 1.5;
}

.current-image {
    display: block;
    width: min(100%, 420px);
    max-height: 280px;
    margin: 14px 0;
    object-fit: cover;
    border: 1px solid var(--border);
    border-radius: 12px;
}

.field > div[style] img {
    border: 1px solid var(--border);
    border-radius: 10px !important;
}

.actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 48px;
    padding: 13px 22px;
    border: 1px solid var(--blue);
    border-radius: 10px;
    background: linear-gradient(135deg, #2585ff, #1263d8);
    color: white;
    font-size: 14px;
    font-weight: 700;
    text-align: center;
    cursor: pointer;
    box-shadow: 0 6px 22px #1263d825;
    transition: transform .2s, box-shadow .2s, filter .2s;
}

.btn:hover {
    color: white;
    filter: brightness(1.12);
    transform: translateY(-1px);
    box-shadow: 0 9px 28px #1263d840;
}

.btn-outline {
    border-color: #344157;
    background: transparent;
    color: #c0ccdc;
    box-shadow: none;
}

.btn-outline:hover {
    border-color: #657b99;
    background: #ffffff08;
    color: white;
    box-shadow: none;
}

@media (max-width: 600px) {
    body {
        padding: 22px 0 36px;
    }

    .container {
        width: 94%;
    }

    .card {
        padding: 22px 17px;
        border-radius: 15px;
    }

    .fields {
        grid-template-columns: 1fr;
        gap: 20px;
        margin-top: 24px;
    }

    .full {
        grid-column: auto;
    }

    .actions {
        flex-direction: column;
    }

    .actions .btn {
        width: 100%;
    }
    .fields > .form-section.full {
    grid-column: 1 / -1;
    width: 100%;
    margin-top: 12px;
}

.fields > .form-section.full:not(:first-child) {
    margin-top: 30px;
}

.fields > .form-section.full h2 {
    color: #f1f5fc;
}

.fields > .form-section.full p {
    max-width: 520px;
}

.fields > .form-section.full + .field {
    margin-top: 0;
}

.field input:not([type="file"]),
.field select,
.field textarea {
    background: linear-gradient(145deg, #0c1422, #080d16);
    border: 1px solid #273750;
    border-radius: 12px;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.015);
}

.field input:not([type="file"]):focus,
.field select:focus,
.field textarea:focus {
    border-color: #2585ff;
    box-shadow:
        0 0 0 3px rgba(37, 133, 255, 0.12),
        0 0 18px rgba(37, 133, 255, 0.08);
}

.field input:not([type="file"]):hover,
.field select:hover,
.field textarea:hover {
    border-color: #3b5273;
}


.btn {
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(101, 177, 255, 0.55);
    background: linear-gradient(135deg, #3194ff 0%, #1765d9 55%, #1048a5 100%);
    box-shadow:
        0 8px 24px rgba(22, 101, 217, 0.22),
        inset 0 1px 0 rgba(255, 255, 255, 0.18);
    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease,
        filter 0.25s ease;
}

.btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.1);
    box-shadow:
        0 12px 30px rgba(37, 133, 255, 0.3),
        0 0 18px rgba(37, 133, 255, 0.12);
}

.btn:active {
    transform: translateY(0);
}

.btn-outline {
    border: 1px solid #344762;
    background: linear-gradient(135deg, #141e2e, #0b111c);
    color: #c5d4e8;
    box-shadow: none;
}

.btn-outline:hover {
    border-color: #5286c7;
    background: #14243a;
    color: #ffffff;
    box-shadow: 0 0 18px rgba(37, 133, 255, 0.08);
}


input[type="file"] {
    min-height: 112px;
    padding: 30px 20px;
    border: 1px dashed rgba(74, 155, 255, 0.65);
    border-radius: 15px;
    background:
        radial-gradient(
            ellipse at top left,
            rgba(37, 133, 255, 0.12),
            transparent 70%
        ),
        linear-gradient(135deg, #101c2d, #0a101b);
    color: #b9c9df;
    transition: all 0.25s ease;
}

input[type="file"]:hover {
    border-color: #65b1ff;
    background-color: #12233a;
    box-shadow: 0 0 24px rgba(37, 133, 255, 0.12);
}

input[type="file"]::file-selector-button {
    padding: 12px 17px;
    margin-right: 15px;
    border: 1px solid #347ac2;
    border-radius: 9px;
    background: linear-gradient(135deg, #1d4775, #123054);
    color: #e4f1ff;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
}

input[type="file"]::file-selector-button:hover {
    background: linear-gradient(135deg, #2867a6, #174578);
    border-color: #65b1ff;
}


}

    </style>
</head>
<body>
<div class="container">
    <p><a href="{{ route('account.index') }}">← ჩემს განცხადებებზე დაბრუნება</a></p>

    <div class="card">
        <h1>
            {{ $motorcycle->exists ? 'განცხადების რედაქტირება' : 'მოტოციკლის განცხადების დამატება' }}
        </h1>

        <p class="muted">
            ვარსკვლავით (*) მონიშნული ველები აუცილებელია.
        </p>

        <form
            method="POST"
            enctype="multipart/form-data"
            action="{{ $motorcycle->exists
                ? route('account.motorcycles.update', $motorcycle->id)
                : route('account.motorcycles.store') }}"
        >
            @csrf

            @if ($motorcycle->exists)
                @method('PUT')
            @endif

            <div class="form-section">
    <span class="section-number">01</span>
    <div>
        <h2>ძირითადი ინფორმაცია</h2>
        <p>მიუთითე მოტოციკლის ძირითადი მონაცემები.</p>
    </div>
</div>
        
            <div class="fields">
                <div class="field full">
                    <label for="title">განცხადების სათაური *</label>
                    <input
                        id="title"
                        name="title"
                        value="{{ old('title', $motorcycle->title) }}"
                        maxlength="255"
                        required
                    >
                    @error('title') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="brand">ბრენდი *</label>
                    <input
                        id="brand"
                        name="brand"
                        value="{{ old('brand', $motorcycle->brand) }}"
                        required
                    >
                    @error('brand') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="model">მოდელი *</label>
                    <input
                        id="model"
                        name="model"
                        value="{{ old('model', $motorcycle->model) }}"
                        required
                    >
                    @error('model') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="year">გამოშვების წელი *</label>
                    <input
                        id="year"
                        name="year"
                        type="number"
                        min="1900"
                        max="{{ date('Y') + 1 }}"
                        value="{{ old('year', $motorcycle->year) }}"
                        required
                    >
                    @error('year') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="price">ფასი (₾) *</label>
                    <input
                        id="price"
                        name="price"
                        type="number"
                        min="0"
                        step="0.01"
                        value="{{ old('price', $motorcycle->price) }}"
                        required
                    >
                    @error('price') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-section full">
    <span class="section-number">02</span>
    <div>
        <h2>ტექნიკური მახასიათებლები</h2>
        <p>მიუთითე მოტოციკლის მდგომარეობა და ტექნიკური მონაცემები.</p>
    </div>
</div>

                <div class="field">
                    <label for="mileage">გარბენი (კმ)</label>
                    <input
                        id="mileage"
                        name="mileage"
                        type="number"
                        min="0"
                        value="{{ old('mileage', $motorcycle->mileage) }}"
                    >
                    @error('mileage') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="engine_cc">ძრავის მოცულობა (cc)</label>
                    <input
                        id="engine_cc"
                        name="engine_cc"
                        type="number"
                        min="1"
                        max="65535"
                        value="{{ old('engine_cc', $motorcycle->engine_cc) }}"
                    >
                    @error('engine_cc') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="condition">მდგომარეობა *</label>
                    <select id="condition" name="condition" required>
                        <option
                            value="used"
                            @selected(old('condition', $motorcycle->condition ?: 'used') === 'used')
                        >
                            მეორადი
                        </option>
                        <option
                            value="new"
                            @selected(old('condition', $motorcycle->condition ?: 'used') === 'new')
                        >
                            ახალი
                        </option>
                    </select>
                    @error('condition') <div class="error">{{ $message }}</div> @enderror
                </div>

                
<div class="form-section full">
    <span class="section-number">03</span>
    <div>
        <h2>მდებარეობა და აღწერა</h2>
        <p>დაუმატე ინფორმაცია, რომელიც მყიდველს არჩევაში დაეხმარება.</p>
    </div>
</div>


                <div class="field full">
                    <label for="location">მდებარეობა</label>
                    <input
                        id="location"
                        name="location"
                        value="{{ old('location', $motorcycle->location) }}"
                        placeholder="მაგ. თბილისი"
                    >
                    @error('location') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field full">
                    <label for="description">აღწერა</label>
                    <textarea
                        id="description"
                        name="description"
                        maxlength="10000"
                        placeholder="მიუთითე მოტოციკლის მდგომარეობა და სხვა მნიშვნელოვანი ინფორმაცია."
                    >{{ old('description', $motorcycle->description) }}</textarea>
                    @error('description') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-section full">
    <span class="section-number">04</span>
    <div>
        <h2>ფოტოები</h2>
        <p>ატვირთე მკაფიო ფოტოები, რომ მყიდველმა მოტოციკლი კარგად დაათვალიეროს.</p>
    </div>
</div>

                <div class="field full">
                    <label for="image">ფოტო (JPG, PNG ან WEBP; მაქსიმუმ 5 MB)</label>

                    @if ($motorcycle->exists && $motorcycle->image_path)
                        <img
                            class="current-image"
                            src="{{ \Illuminate\Support\Facades\Storage::url($motorcycle->image_path) }}"
                            alt="მიმდინარე ფოტო"
                        >
                        <p class="muted">ახალი ფოტოს არჩევა არსებულ ფოტოს შეცვლის.</p>
                    @endif

                    <input
                        id="image"
                        name="image"
                        type="file"
                        accept=".jpg,.jpeg,.png,.webp"
                    >
                    @error('image') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="field full">
    <label for="images">დამატებითი ფოტოები (მაქსიმუმ 10 ფოტო, თითოეული 5 MB-მდე)</label>

    @if ($motorcycle->exists && $motorcycle->images->isNotEmpty())
        <div style="display: flex; flex-wrap: wrap; gap: 12px; margin: 12px 0;">
            @foreach ($motorcycle->images as $photo)
                <img
                    src="{{ \Illuminate\Support\Facades\Storage::url($photo->image_path) }}"
                    alt="მოტოციკლის დამატებითი ფოტო"
                    style="width: 110px; height: 85px; object-fit: cover; border-radius: 8px;"
                >
            @endforeach
        </div>
        <p class="muted">არსებული დამატებითი ფოტოები შენარჩუნდება. ახალი ფოტოების არჩევა მათ დაემატება.</p>
    @endif

    <input
        id="images"
        name="images[]"
        type="file"
        accept=".jpg,.jpeg,.png,.webp"
        multiple
    >

    @error('images')
        <div class="error">{{ $message }}</div>
    @enderror

    @error('images.*')
        <div class="error">{{ $message }}</div>
    @enderror
</div>

            <div class="actions">
                <button class="btn" type="submit">
                    {{ $motorcycle->exists ? 'ცვლილებების შენახვა' : 'განცხადების დამატება' }}
                </button>

                <a class="btn btn-outline" href="{{ route('account.index') }}">
                    გაუქმება
                </a>
            </div>
        </form>
    </div>
</div>
</body>
</html>