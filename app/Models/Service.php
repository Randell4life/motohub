<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $motorcycle->title }} | Garage 33</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($motorcycle->description ?? $motorcycle->title), 155) }}">

    <style>

:root {
    --bg: #0b0b0c;
    --panel: #141416;
    --panel-light: #1d1d20;
    --text: #f5f3ef;
    --muted: #96949a;
    --red: #f04444;
    --border: rgba(255,255,255,.12);
    --display: "Arial Narrow", "Segoe UI", Arial, sans-serif;
}

* {
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    margin: 0;
    color: var(--text);
    background:
        linear-gradient(115deg, transparent 0 72%, rgba(240,68,68,.035) 72% 72.2%, transparent 72.2%),
        var(--bg);
    font-family: "Segoe UI", Arial, sans-serif;
    line-height: 1.65;
    -webkit-font-smoothing: antialiased;
}

a {
    color: inherit;
    text-decoration: none;
}

.container {
    width: min(1320px, 92%);
    margin: 0 auto;
}

header {
    background: rgba(11,11,12,.94);
    border-bottom: 1px solid var(--border);
}

.header-inner {
    min-height: 78px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.logo {
    font-size: 27px;
    font-weight: 950;
    letter-spacing: -1.8px;
    text-transform: uppercase;
}

.logo span {
    color: var(--red);
}

.btn {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    padding: 11px 17px;
    border: 1px solid var(--border);
    background: transparent;
    color: var(--text);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .7px;
    text-transform: uppercase;
    transition: .2s ease;
}

.btn:hover {
    border-color: var(--red);
    background: var(--red);
}

.back-link {
    display: inline-flex;
    align-items: center;
    margin: 25px 0 22px;
    color: var(--muted);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    transition: color .2s;
}

.back-link:hover {
    color: var(--red);
}

.detail-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.45fr) minmax(310px, .72fr);
    gap: 34px;
    align-items: start;
    padding-bottom: 38px;
}

.photo-panel,
.info-panel,
.description-panel {
    min-width: 0;
    background: var(--panel);
    border: 1px solid var(--border);
}

.photo-panel {
    border-radius: 2px;
    overflow: hidden;
}

.gallery-stage {
    position: relative;
    overflow: hidden;
    cursor: zoom-in;
    background:
        radial-gradient(ellipse at 50% 50%, #252529 0%, #111113 54%, #080809 100%);
}

.main-photo {
    display: block;
    width: 100%;
    aspect-ratio: 5 / 4;
    max-height: 740px;
    object-fit: contain;
    background: transparent;
    transition: opacity .25s;
}

.gallery-arrow {
    position: absolute;
    z-index: 2;
    top: 50%;
    transform: translateY(-50%);
    width: 46px;
    height: 46px;
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 0;
    background: rgba(0,0,0,.72);
    color: #fff;
    font-size: 29px;
    cursor: pointer;
    opacity: .8;
    transition: .2s;
}

.gallery-stage:hover .gallery-arrow {
    opacity: 1;
}

.gallery-arrow:hover {
    background: var(--red);
    border-color: var(--red);
}

.gallery-prev {
    left: 16px;
}

.gallery-next {
    right: 16px;
}

.gallery-counter {
    position: absolute;
    right: 15px;
    bottom: 15px;
    padding: 6px 10px;
    background: var(--red);
    color: white;
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 1px;
}

.gallery-thumbnails {
    display: flex;
    gap: 9px;
    padding: 13px;
    border-top: 1px solid var(--border);
    overflow-x: auto;
    scrollbar-width: thin;
}

.gallery-thumb {
    flex: 0 0 90px;
    height: 67px;
    padding: 0;
    border: 1px solid #39393d;
    border-radius: 0;
    overflow: hidden;
    background: #202023;
    cursor: pointer;
    opacity: .55;
    transition: .2s;
}

.gallery-thumb:hover {
    opacity: 1;
}

.gallery-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.gallery-thumb.is-active {
    border: 2px solid var(--red);
    opacity: 1;
}

.info-panel {
    padding: 27px 25px;
    border-radius: 2px;
    position: relative;
}

.eyebrow {
    display: block;
    color: var(--red);
    font-size: 10px;
    font-weight: 900;
    letter-spacing: 2px;
    line-height: 1.7;
    text-transform: uppercase;
}

h1 {
    margin: 18px 0 24px;
    font-family: var(--display);
    font-size: clamp(34px, 4.3vw, 55px);
    font-weight: 950;
    line-height: .99;
    letter-spacing: -2.3px;
    text-transform: uppercase;
    overflow-wrap: anywhere;
}

.price {
    position: relative;
    margin: 0 0 27px;
    padding: 20px 17px 19px;
    border-left: 4px solid var(--red);
    background: #1b1b1e;
    color: var(--text);
    font-size: clamp(29px, 3.2vw, 38px);
    font-weight: 950;
    line-height: 1.1;
    letter-spacing: -1.4px;
    overflow-wrap: anywhere;
}

.price::before {
    content: "ფასი";
    display: block;
    margin-bottom: 10px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2px;
    line-height: 1.4;
}

.specs {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0 14px;
}

.spec {
    min-width: 0;
    padding: 15px 0 13px;
    border-top: 1px solid var(--border);
    background: transparent;
}

.spec-label {
    display: block;
    margin-bottom: 5px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .9px;
    text-transform: uppercase;
}

.spec-value {
    display: block;
    color: var(--text);
    font-size: 14px;
    font-weight: 750;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

.spec:hover .spec-value {
    color: #ff7777;
}

.description-panel {
    position: relative;
    margin: 0 0 55px;
    padding: 30px 32px;
    border-radius: 2px;
    background:
        linear-gradient(100deg, rgba(240,68,68,.055), transparent 40%),
        var(--panel);
}

.description-panel h2 {
    display: flex;
    align-items: center;
    gap: 13px;
    margin: 0 0 22px;
    font-size: clamp(24px, 3vw, 34px);
    font-weight: 950;
    letter-spacing: -1px;
    line-height: 1.1;
    text-transform: uppercase;
}

.description-panel h2::before {
    content: "";
    display: block;
    width: 5px;
    height: 30px;
    flex: 0 0 5px;
    background: var(--red);
}

.description {
    color: #cfccd0;
    font-size: 15px;
    line-height: 1.95;
    white-space: pre-line;
    overflow-wrap: anywhere;
}

.muted {
    color: var(--muted);
}

.contact-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 17px;
    margin-top: 28px;
    padding-top: 23px;
    border-top: 1px solid var(--border);
}

.call-button {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    min-height: 49px;
    padding: 13px 23px;
    border: 1px solid var(--red);
    border-radius: 0;
    background: var(--red);
    color: #fff;
    font-size: 13px;
    font-weight: 900;
    letter-spacing: 1px;
    text-transform: uppercase;
    box-shadow: 5px 5px 0 rgba(240,68,68,.15);
    transition: .2s;
}

.call-button:hover {
    transform: translate(-2px, -2px);
    box-shadow: 7px 7px 0 rgba(240,68,68,.25);
    background: #d83232;
}

.phone-number {
    color: #f3f0ec;
    font-size: 16px;
    font-weight: 800;
    transition: color .2s;
}

.phone-number:hover {
    color: var(--red);
}

footer {
    border-top: 1px solid var(--border);
    padding: 26px 0;
    color: var(--muted);
    font-size: 12px;
    letter-spacing: .3px;
}

#galleryFullscreen[hidden] {
    display: none !important;
}

#galleryFullscreen {
    backdrop-filter: blur(8px);
}

#galleryFullscreen button {
    border: 1px solid rgba(255,255,255,.2) !important;
    border-radius: 0 !important;
    transition: background .2s;
}

#galleryFullscreen button:hover {
    background: var(--red) !important;
}

#fullscreenPhoto {
    border-radius: 0;
}

@media (min-width: 1050px) {
    .info-panel {
        position: sticky;
        top: 22px;
    }
}

@media (max-width: 900px) {
    .detail-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 20px;
    }

    .info-panel {
        position: static;
    }

    .main-photo {
        max-height: 600px;
    }
}

@media (max-width: 600px) {
    .container {
        width: 92%;
    }

    .header-inner {
        min-height: 68px;
        gap: 10px;
    }

    .logo {
        font-size: 23px;
    }

    .btn {
        padding: 9px 10px;
        font-size: 10px;
        letter-spacing: .3px;
    }

    .back-link {
        margin: 19px 0;
        font-size: 10px;
    }

    .detail-grid {
        gap: 15px;
        padding-bottom: 24px;
    }

    .info-panel {
        padding: 23px 19px;
    }

    h1 {
        margin: 15px 0 21px;
        font-size: clamp(33px, 9vw, 45px);
        letter-spacing: -1.7px;
    }

    .price {
        padding: 17px 14px;
        font-size: 30px;
    }

    .specs {
        gap: 0 12px;
    }

    .spec {
        padding: 13px 0 11px;
    }

    .spec-value {
        font-size: 13px;
    }

    .main-photo {
        aspect-ratio: 4 / 3;
        max-height: 460px;
    }

    .gallery-arrow {
        width: 37px;
        height: 37px;
        font-size: 24px;
    }

    .gallery-prev {
        left: 9px;
    }

    .gallery-next {
        right: 9px;
    }

    .gallery-thumbnails {
        padding: 9px;
        gap: 7px;
    }

    .gallery-thumb {
        flex-basis: 72px;
        height: 56px;
    }

    .description-panel {
        padding: 23px 19px;
        margin-bottom: 33px;
    }

    .description-panel h2 {
        font-size: 25px;
    }

    .description {
        font-size: 14px;
    }

    .contact-row {
        align-items: stretch;
        flex-direction: column;
        gap: 15px;
    }

    .contact-row .call-button {
        width: 100%;
    }

    .phone-number {
        text-align: center;
        font-size: 15px;
    }

    #galleryFullscreen {
        padding: 55px 45px !important;
    }
}

@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
    }
}

    </style>
</head>
<body>
    <header>
        <div class="container header-inner">
            <a class="logo" href="{{ route('home') }}">Moto<span>Hub</span></a>
            <a class="btn" href="{{ route('home') }}">განცხადებების ნახვა</a>
        </div>
    </header>

    <main class="container">
        <a class="back-link" href="{{ route('home') }}">← ყველა განცხადება</a>

        <div class="detail-grid">
            <div class="photo-panel">
                @php
                    $allPhotos = collect();

                    if ($motorcycle->image_path) {
                        $allPhotos->push($motorcycle->image_path);
                    }

                    foreach ($motorcycle->images as $photo) {
                        if ($photo->image_path) {
                            $allPhotos->push($photo->image_path);
                        }
                    }

                    $allPhotos = $allPhotos->unique()->values();
                    $fallbackPhoto = 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1200&q=85';
                @endphp

                <div class="gallery-stage" id="motorcycleGallery">
                    <img
                        class="main-photo"
                        id="mainMotorcyclePhoto"
                        src="{{ $allPhotos->isNotEmpty() ? \Illuminate\Support\Facades\Storage::url($allPhotos->first()) : $fallbackPhoto }}"
                        alt="{{ $motorcycle->title }}"
                        fetchpriority="high"
                    >

                    @if ($allPhotos->count() > 1)
                        <button type="button" class="gallery-arrow gallery-prev" id="galleryPrev" aria-label="წინა ფოტო">‹</button>
                        <button type="button" class="gallery-arrow gallery-next" id="galleryNext" aria-label="შემდეგი ფოტო">›</button>
                        <span class="gallery-counter" id="galleryCounter">1 / {{ $allPhotos->count() }}</span>
                    @endif
                </div>

                @if ($allPhotos->count() > 1)
                    <div class="gallery-thumbnails" id="galleryThumbnails">
                        @foreach ($allPhotos as $index => $photo)
                            <button
                                type="button"
                                class="gallery-thumb {{ $index === 0 ? 'is-active' : '' }}"
                                data-index="{{ $index }}"
                                aria-label="ფოტო {{ $index + 1 }}"
                                aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"
                            >
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::url($photo) }}"
                                    alt="{{ $motorcycle->title }} — ფოტო {{ $index + 1 }}"
                                    loading="lazy"
                                >
                            </button>
                        @endforeach
                    </div>
                @endif

                <script type="application/json" id="motorcycleGalleryData">@json($allPhotos->isNotEmpty() ? $allPhotos->map(fn ($photo) => \Illuminate\Support\Facades\Storage::url($photo))->values() : [$fallbackPhoto])</script>
            </div>

            <section class="info-panel">
                <div class="eyebrow">Garage 33 · მოტოციკლის განცხადება</div>
                <h1>{{ $motorcycle->title }}</h1>

                <div class="price">
                    {{ number_format((float) $motorcycle->price, 0, '.', ' ') }} ₾
                </div>

                <div class="specs">
                    <div class="spec">
                        <span class="spec-label">ბრენდი</span>
                        <span class="spec-value">{{ $motorcycle->brand ?: 'მითითებული არ არის' }}</span>
                    </div>

                    <div class="spec">
                        <span class="spec-label">მოდელი</span>
                        <span class="spec-value">{{ $motorcycle->model ?: 'მითითებული არ არის' }}</span>
                    </div>

                    <div class="spec">
                        <span class="spec-label">გამოშვების წელი</span>
                        <span class="spec-value">{{ $motorcycle->year ?: 'მითითებული არ არის' }}</span>
                    </div>

                    <div class="spec">
                        <span class="spec-label">მდგომარეობა</span>
                        <span class="spec-value">{{ $motorcycle->condition === 'new' ? 'ახალი' : 'მეორადი' }}</span>
                    </div>

                    <div class="spec">
                        <span class="spec-label">გარბენი</span>
                        <span class="spec-value">
                            {{ $motorcycle->mileage !== null
                                ? number_format((float) $motorcycle->mileage, 0, '.', ' ') . ' კმ'
                                : 'მითითებული არ არის' }}
                        </span>
                    </div>

                    <div class="spec">
                        <span class="spec-label">ძრავის მოცულობა</span>
                        <span class="spec-value">{{ $motorcycle->engine_cc ? $motorcycle->engine_cc . ' cc' : 'მითითებული არ არის' }}</span>
                    </div>

                    <div class="spec">
                        <span class="spec-label">მდებარეობა</span>
                        <span class="spec-value">{{ $motorcycle->location ?: 'მითითებული არ არის' }}</span>
                    </div>

                    <div class="spec">
                        <span class="spec-label">განცხადების თარიღი</span>
                        <span class="spec-value">{{ $motorcycle->published_at ? $motorcycle->published_at->format('d.m.Y') : '—' }}</span>
                    </div>
                </div>
            </section>
        </div>

        <section class="description-panel">
            <h2>აღწერა</h2>

            @if ($motorcycle->description)
                <div class="description">{{ $motorcycle->description }}</div>
            @else
                <p class="muted">განცხადების ავტორს აღწერა არ დაუმატებია.</p>
            @endif

            @php
                $contactPhone = $motorcycle->user?->phone ?: $motorcycle->partner?->phone;
                $phoneForCall = $contactPhone ? preg_replace('/[^\d+]/', '', $contactPhone) : '';
            @endphp

            @if ($phoneForCall !== '' && preg_match('/\d/', $phoneForCall))
                <div class="contact-row">
                    <a class="call-button" href="tel:{{ $phoneForCall }}">
                        <span aria-hidden="true">☎</span>
                        <span>დარეკვა</span>
                    </a>
                    <a class="phone-number" href="tel:{{ $phoneForCall }}">{{ $contactPhone }}</a>
                </div>
            @endif
        </section>
    </main>

    <footer>
        <div class="container">
            © {{ date('Y') }} Garage 33 — მოტოციკლების განცხადებები
        </div>
    </footer>

    <div
        id="galleryFullscreen"
        hidden
        role="dialog"
        aria-modal="true"
        aria-label="მოტოციკლის ფოტოგალერეა"
        style="position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.96);display:flex;align-items:center;justify-content:center;padding:60px 64px;"
    >
        <button
            type="button"
            id="fullscreenClose"
            aria-label="დახურვა"
            style="position:absolute;top:18px;right:22px;width:46px;height:46px;border:0;border-radius:50%;background:#25252b;color:#fff;font-size:30px;cursor:pointer;"
        >×</button>

        <button
            type="button"
            id="fullscreenPrev"
            aria-label="წინა ფოტო"
            style="position:absolute;left:16px;top:50%;transform:translateY(-50%);width:46px;height:46px;border:0;border-radius:50%;background:#25252b;color:#fff;font-size:30px;cursor:pointer;"
        >‹</button>

        <img
            id="fullscreenPhoto"
            alt="მოტოციკლის დიდი ფოტო"
            style="display:block;max-width:100%;max-height:85vh;object-fit:contain;"
        >

        <button
            type="button"
            id="fullscreenNext"
            aria-label="შემდეგი ფოტო"
            style="position:absolute;right:16px;top:50%;transform:translateY(-50%);width:46px;height:46px;border:0;border-radius:50%;background:#25252b;color:#fff;font-size:30px;cursor:pointer;"
        >›</button>

        <span
            id="fullscreenCounter"
            style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);padding:7px 14px;border-radius:8px;background:#25252b;color:#fff;font-size:14px;"
        >1 / 1</span>
    </div>

    <script>
    (() => {
        const gallery = document.getElementById('motorcycleGallery');
        const mainPhoto = document.getElementById('mainMotorcyclePhoto');
        const dataElement = document.getElementById('motorcycleGalleryData');

        if (!gallery || !mainPhoto || !dataElement) return;

        let photos;
        try {
            photos = JSON.parse(dataElement.textContent || '[]');
        } catch (error) {
            console.error('გალერეის ფოტოების სია ვერ ჩაიტვირთა.', error);
            return;
        }

        if (!photos.length) return;

        const thumbs = [...document.querySelectorAll('.gallery-thumb')];
        const counter = document.getElementById('galleryCounter');
        const overlay = document.getElementById('galleryFullscreen');
        const fullscreenImage = document.getElementById('fullscreenPhoto');
        const fullscreenCounter = document.getElementById('fullscreenCounter');
        let current = 0;
        let fullscreenIndex = 0;
        let touchStartX = 0;

        function showPhoto(index) {
            current = (index + photos.length) % photos.length;
            mainPhoto.src = photos[current];

            if (counter) counter.textContent = `${current + 1} / ${photos.length}`;

            thumbs.forEach((thumb, i) => {
                const active = i === current;
                thumb.classList.toggle('is-active', active);
                thumb.setAttribute('aria-pressed', String(active));
            });
        }

        function showFullscreenPhoto(index) {
            fullscreenIndex = (index + photos.length) % photos.length;
            if (fullscreenImage) fullscreenImage.src = photos[fullscreenIndex];
            if (fullscreenCounter) {
                fullscreenCounter.textContent = `${fullscreenIndex + 1} / ${photos.length}`;
            }
        }

        function openFullscreen() {
            if (!overlay) return;
            overlay.hidden = false;
            showFullscreenPhoto(current);
            document.body.style.overflow = 'hidden';
            document.getElementById('fullscreenClose')?.focus();
        }

        function closeFullscreen() {
            if (!overlay) return;
            overlay.hidden = true;
            document.body.style.overflow = '';
            mainPhoto.focus?.();
        }

        document.getElementById('galleryPrev')?.addEventListener('click', () => showPhoto(current - 1));
        document.getElementById('galleryNext')?.addEventListener('click', () => showPhoto(current + 1));

        thumbs.forEach((thumb) => {
            thumb.addEventListener('click', () => showPhoto(Number(thumb.dataset.index)));
        });

        gallery.addEventListener('touchstart', (event) => {
            touchStartX = event.changedTouches[0].screenX;
        }, { passive: true });

        gallery.addEventListener('touchend', (event) => {
            const difference = event.changedTouches[0].screenX - touchStartX;
            if (Math.abs(difference) > 45 && photos.length > 1) {
                showPhoto(current + (difference < 0 ? 1 : -1));
            }
        }, { passive: true });

        mainPhoto.addEventListener('click', openFullscreen);
        document.getElementById('fullscreenPrev')?.addEventListener('click', () => showFullscreenPhoto(fullscreenIndex - 1));
        document.getElementById('fullscreenNext')?.addEventListener('click', () => showFullscreenPhoto(fullscreenIndex + 1));
        document.getElementById('fullscreenClose')?.addEventListener('click', closeFullscreen);

        overlay?.addEventListener('click', (event) => {
            if (event.target === overlay) closeFullscreen();
        });

        document.addEventListener('keydown', (event) => {
            if (!overlay || overlay.hidden) return;

            if (event.key === 'Escape') closeFullscreen();
            if (event.key === 'ArrowLeft') showFullscreenPhoto(fullscreenIndex - 1);
            if (event.key === 'ArrowRight') showFullscreenPhoto(fullscreenIndex + 1);
        });

        showPhoto(0);
    })();
    </script>
</body>
</html>
