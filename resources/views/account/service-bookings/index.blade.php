<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ჩემი ჯავშნები | MotoHub</title>
    <style>
        :root{--bg:#070d18;--sidebar:#0b1423;--panel:#0d192b;--panel2:#101f34;--border:#1e3552;--text:#f2f7ff;--muted:#8ea4c0;--blue:#087cff;--blue2:#39a1ff;--green:#35d5a0;--yellow:#ffc866;--red:#ff6878;--purple:#b6a0ff;--radius:17px}
        *{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:radial-gradient(ellipse at 80% 0,rgba(8,124,255,.13),transparent 38%),var(--bg);color:var(--text);font-family:Inter,"Segoe UI",Arial,sans-serif;font-size:14px;line-height:1.55}a{color:inherit;text-decoration:none}button{font:inherit} .app{min-height:100vh}.sidebar{position:fixed;inset:0 auto 0 0;width:245px;background:rgba(9,18,32,.97);border-right:1px solid var(--border);padding:27px 17px;display:flex;flex-direction:column;z-index:5;overflow-y:auto}.brand{display:flex;align-items:center;gap:10px;padding:0 10px 31px;font-size:23px;font-weight:900;letter-spacing:-.8px}.brand-mark{width:38px;height:38px;border-radius:12px;display:grid;place-items:center;background:linear-gradient(140deg,#1287ff,#0747bd);box-shadow:0 8px 24px #087cff35;font-size:18px}.brand-blue{color:var(--blue2)}.menu-label{padding:6px 12px 12px;color:#607895;font-size:10px;font-weight:800;letter-spacing:1.6px}.nav{display:grid;gap:6px}.nav a{display:flex;align-items:center;gap:11px;padding:12px;border:1px solid transparent;border-radius:11px;color:var(--muted);font-weight:650;transition:.18s}.nav a:hover,.nav a.active{color:#fff;background:#087cff1c;border-color:#087cff35}.nav a.active{box-shadow:inset 3px 0 var(--blue)}.nav-icon{width:22px;text-align:center;font-size:17px}.sidebar-bottom{margin-top:auto;padding-top:26px}.side-promo{padding:17px;border:1px solid var(--border);border-radius:14px;background:linear-gradient(145deg,#112641,#0b1729)}.side-promo b{display:block;margin-bottom:5px}.side-promo p{margin:0 0 12px;color:var(--muted);font-size:12px}.side-promo a{color:var(--blue2);font-weight:750}.main{min-width:0;margin-left:245px}.topbar{height:72px;position:sticky;top:0;z-index:4;display:flex;align-items:center;justify-content:space-between;gap:15px;padding:0 35px;background:#070d18e8;backdrop-filter:blur(16px);border-bottom:1px solid var(--border)}.crumb{color:var(--muted);font-size:12px}.crumb strong{color:var(--text)}.top-actions{display:flex;align-items:center;gap:15px}.site-link{color:var(--muted);font-weight:650}.site-link:hover{color:#fff}.user-chip{display:flex;align-items:center;gap:9px}.avatar{width:37px;height:37px;border-radius:12px;display:grid;place-items:center;background:#163a65;border:1px solid #318dff66;color:#fff;font-weight:850}.user-name{font-weight:750}.user-caption{color:var(--muted);font-size:11px}.content{max-width:1380px;margin:0 auto;padding:31px 35px 52px}.hero{position:relative;overflow:hidden;padding:30px;border:1px solid #1e4776;border-radius:21px;background:radial-gradient(ellipse at 92% 8%,#087cff2e,transparent 42%),linear-gradient(120deg,#102039,#0b1729 74%);margin-bottom:23px}.hero:after{content:"MH";position:absolute;right:24px;top:-34px;font-size:164px;line-height:1;font-weight:950;letter-spacing:-14px;color:#4e9dff0b;pointer-events:none}.eyebrow{color:var(--blue2);font-size:10px;font-weight:850;letter-spacing:1.8px;text-transform:uppercase}.eyebrow:before{content:"";display:inline-block;width:6px;height:6px;border-radius:100%;background:var(--blue);box-shadow:0 0 13px var(--blue);margin:0 9px 1px 0}h1{position:relative;z-index:1;margin:11px 0 7px;font-size:clamp(27px,3vw,37px);letter-spacing:-1px;line-height:1.2}.hero p{position:relative;z-index:1;max-width:620px;margin:0;color:var(--muted)}.hero-meta{display:flex;flex-wrap:wrap;gap:9px;margin-top:21px}.meta-pill{padding:7px 11px;border:1px solid #2b4b73;border-radius:9px;color:#c5d9f3;background:#0a1729aa;font-size:12px}.meta-pill strong{color:white}.flash{padding:13px 16px;margin:0 0 17px;border-radius:12px;background:#35d5a012;border:1px solid #35d5a04d;color:var(--green)}.flash.error{background:#ff687812;border-color:#ff68784d;color:#ff9aa5}.summary{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-bottom:26px}.summary-card{padding:17px 19px;border:1px solid var(--border);border-radius:15px;background:linear-gradient(145deg,#101f34,#0a1525)}.summary-label{font-size:12px;color:var(--muted)}.summary-number{font-size:27px;font-weight:900;margin-top:4px;letter-spacing:-.6px}.summary-note{font-size:11px;color:#6f88a7}.filters{display:flex;gap:8px;overflow-x:auto;padding:2px 1px 13px;margin-bottom:9px;scrollbar-width:thin}.filter{flex-shrink:0;display:inline-flex;align-items:center;gap:8px;padding:9px 13px;border:1px solid var(--border);border-radius:999px;background:#0b1728;color:#9db0ca;font-size:12px;font-weight:750}.filter.active{background:var(--blue);border-color:var(--blue);color:white;box-shadow:0 5px 20px #087cff28}.filter-count{font-size:10px;opacity:.8}.section-heading{display:flex;align-items:end;justify-content:space-between;gap:12px;margin:5px 0 15px}.section-heading h2{margin:0;font-size:20px;letter-spacing:-.4px}.section-heading p{margin:4px 0 0;color:var(--muted);font-size:12px}.booking-list{display:grid;gap:12px}.booking-card{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:17px;padding:19px;border:1px solid var(--border);border-radius:16px;background:linear-gradient(115deg,#0d192b,#0a1525);transition:border-color .2s,transform .2s}.booking-card:hover{border-color:#315783;transform:translateY(-1px)}.booking-main{min-width:0}.booking-top{display:flex;align-items:center;gap:9px;flex-wrap:wrap;margin-bottom:6px}.workshop-name{font-size:16px;font-weight:850;overflow-wrap:anywhere}.verified{display:inline-grid;place-items:center;width:17px;height:17px;background:var(--blue);color:white;border-radius:50%;font-size:10px}.service-name{color:#bbcee6;font-weight:650}.booking-sub{margin-top:5px;color:var(--muted);font-size:12px}.booking-meta{display:flex;flex-wrap:wrap;gap:8px 17px;margin-top:15px;color:var(--muted);font-size:12px}.booking-meta span{display:inline-flex;align-items:center;gap:7px}.booking-meta b{color:#e4edf9;font-weight:700}.status{display:inline-flex;align-items:center;gap:6px;padding:5px 9px;border:1px solid var(--border);border-radius:8px;background:#14233a;color:#bdcce1;font-size:10px;font-weight:850;white-space:nowrap}.status:before{content:"";width:6px;height:6px;border-radius:50%;background:currentColor}.status-pending{color:var(--yellow);background:#ffc86612;border-color:#ffc8663a}.status-confirmed{color:var(--green);background:#35d5a012;border-color:#35d5a03c}.status-completed{color:var(--blue2);background:#1684ff12;border-color:#1684ff45}.status-cancelled,.status-declined{color:var(--red);background:#ff687812;border-color:#ff68783c}.booking-side{min-width:150px;display:flex;flex-direction:column;align-items:flex-end;justify-content:center;gap:8px;padding-left:18px;border-left:1px solid var(--border)}.price-label{color:var(--muted);font-size:11px}.price{font-size:24px;font-weight:900;letter-spacing:-.7px;white-space:nowrap}.price small{font-size:12px;color:var(--muted);font-weight:600}.actions{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:7px;margin-top:3px}.btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;min-height:35px;padding:7px 11px;border:1px solid var(--border);border-radius:9px;background:#122239;color:#e4efff;cursor:pointer;font-size:11px;font-weight:800;transition:.18s}.btn:hover{border-color:#3c6c9f;background:#19304e}.btn-primary{border-color:var(--blue);background:var(--blue);color:#fff}.btn-primary:hover{background:#2690ff}.btn-danger{border-color:#ff68784d;background:#ff687810;color:#ff9aa5}.btn-danger:hover{background:#ff687822}.details{margin-top:13px;border-top:1px solid var(--border);padding-top:11px}.details summary{cursor:pointer;color:var(--blue2);font-weight:750;font-size:12px;list-style:none}.details summary::-webkit-details-marker{display:none}.details summary:after{content:"＋";float:right}.details[open] summary:after{content:"−"}.detail-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;margin-top:12px}.detail-item{padding:10px 12px;border:1px solid #1d304a;border-radius:10px;background:#091424}.detail-label{display:block;color:var(--muted);font-size:10px}.detail-value{display:block;margin-top:3px;font-size:12px;font-weight:700;overflow-wrap:anywhere}.empty{padding:43px 22px;border:1px dashed #294666;border-radius:17px;background:#0c1727a8;text-align:center}.empty-icon{width:55px;height:55px;margin:0 auto 13px;display:grid;place-items:center;border:1px solid #2a4d76;border-radius:16px;background:#122743;font-size:24px}.empty h3{margin:0 0 7px;font-size:17px}.empty p{max-width:410px;margin:0 auto 18px;color:var(--muted)}.pagination{overflow-x:auto;margin-top:20px}.pagination nav>div:first-child{color:var(--muted);font-size:12px}.pagination nav>div:last-child{margin-top:12px}.pagination a,.pagination span[aria-current],.pagination span[aria-disabled]{display:inline-block;margin:0 3px 3px 0;padding:7px 11px;border:1px solid var(--border);border-radius:8px;background:var(--panel);color:var(--muted)}.pagination span[aria-current]{background:var(--blue);border-color:var(--blue);color:#fff}.footer{padding:20px 35px;border-top:1px solid var(--border);color:#6f829d;font-size:11px}.footer-inner{display:flex;justify-content:space-between;gap:10px;max-width:1380px;margin:auto}@media(max-width:1100px){.sidebar{width:215px}.main{margin-left:215px}.content{padding:25px 23px 40px}.topbar{padding:0 23px}.booking-card{grid-template-columns:1fr}.booking-side{min-width:0;align-items:flex-start;padding:13px 0 0;border-left:0;border-top:1px solid var(--border)}.actions{justify-content:flex-start}}@media(max-width:760px){.sidebar{position:static;width:auto;padding:11px 14px;border-right:0;border-bottom:1px solid var(--border)}.brand{padding:0 2px 12px;font-size:21px}.brand-mark{width:34px;height:34px}.menu-label,.sidebar-bottom{display:none}.nav{display:flex;gap:6px;overflow-x:auto;padding-bottom:3px;scrollbar-width:thin}.nav a{flex-shrink:0;padding:8px 10px;font-size:11px}.nav a.active{box-shadow:inset 0 -2px var(--blue)}.main{margin-left:0}.topbar{height:58px;padding:0 15px}.top-actions{gap:10px}.site-link{font-size:11px}.user-caption{display:none}.avatar{width:33px;height:33px}.content{padding:18px 13px 32px}.hero{padding:22px 18px;border-radius:16px}.hero:after{right:7px;top:0;font-size:90px}.summary{gap:8px}.summary-card{padding:12px 10px}.summary-label{font-size:10px}.summary-number{font-size:22px}.summary-note{font-size:9px}.section-heading h2{font-size:18px}.booking-card{padding:15px;gap:12px}.booking-side{flex-direction:row;align-items:center;justify-content:space-between;flex-wrap:wrap}.price{font-size:21px}.actions{width:100%}.btn{flex:0 0 auto}.detail-grid{grid-template-columns:1fr 1fr}.footer{padding:18px 13px}}@media(max-width:400px){.summary{grid-template-columns:1fr}.summary-card{display:flex;align-items:center;justify-content:space-between}.summary-number{margin:0}.topbar .crumb{font-size:10px}.booking-top{gap:7px}.detail-grid{grid-template-columns:1fr}.booking-meta{gap:8px 12px}}
    </style>
</head>
<body>
@php
    $user = auth()->user();
    $filters = [
        'all' => 'ყველა',
        'pending' => 'მოლოდინში',
        'confirmed' => 'დადასტურებული',
        'completed' => 'შესრულებული',
        'cancelled' => 'გაუქმებული',
        'declined' => 'უარყოფილი',
    ];
    $statusClasses = [
        'pending' => 'pending',
        'confirmed' => 'confirmed',
        'completed' => 'completed',
        'cancelled' => 'cancelled',
        'declined' => 'declined',
    ];
    $statusLabels = [
        'pending' => 'მოლოდინში',
        'confirmed' => 'დადასტურებული',
        'completed' => 'შესრულებული',
        'cancelled' => 'გაუქმებული',
        'declined' => 'უარყოფილი',
    ];
    $initial = mb_substr(trim((string) $user->name), 0, 1, 'UTF-8');
    $countFor = fn ($key) => $key === 'all' ? $totalCount : (int) ($counts[$key] ?? 0);
@endphp
<div class="app">
    <aside class="sidebar">
        <a href="{{ route('home') }}" class="brand"><span class="brand-mark">🏍</span><span>Moto<span class="brand-blue">Hub</span></span></a>
        <div class="menu-label">პირადი სივრცე</div>
        <nav class="nav" aria-label="პირადი კაბინეტის მენიუ">
            <a href="{{ route('account.index') }}"><span class="nav-icon">⌂</span> მთავარი</a>
            <a href="{{ route('account.index') }}#motorcycles"><span class="nav-icon">🏍</span> ჩემი მოტოციკლები</a>
            <a href="{{ route('account.index') }}#bookings"><span class="nav-icon">▦</span> გაქირავების ჯავშნები</a>
            <a class="active" href="{{ route('account.service-bookings.index') }}"><span class="nav-icon">🛠</span> ჩემი სერვისის ჯავშნები</a>
            <a href="{{ route('account.index') }}#profile"><span class="nav-icon">◎</span> ჩემი პროფილი</a>
            <a href="{{ route('part-requests.create') }}"><span class="nav-icon">⚙</span> ნაწილების მოთხოვნა</a>
        </nav>
        <div class="sidebar-bottom"><div class="side-promo"><b>MotoHub Service</b><p>მართე შენი მოტოსერვისის ვიზიტები ერთ სივრცეში.</p><a href="{{ route('service-centers.index') }}">სახელოსნოების ნახვა →</a></div></div>
    </aside>
    <main class="main">
        <header class="topbar">
            <div class="crumb">MotoHub <span> / </span> <strong>ჩემი ჯავშნები</strong></div>
            <div class="top-actions"><a class="site-link" href="{{ route('service-centers.index') }}">+ ახალი ჯავშანი</a><div class="user-chip"><div class="avatar">{{ $initial ?: 'M' }}</div><div><div class="user-name">{{ $user->name }}</div><div class="user-caption">პირადი კაბინეტი</div></div></div></div>
        </header>
        <div class="content">
            <section class="hero">
                <div class="eyebrow">MotoHub / Service bookings</div>
                <h1>ჩემი ჯავშნები</h1>
                <p>ნახე სახელოსნოს ვიზიტები, შეამოწმე სტატუსები და მართე მომავალი მომსახურებები ერთ სივრცეში.</p>
                <div class="hero-meta"><span class="meta-pill">ჯამში: <strong>{{ $totalCount }}</strong> ჯავშანი</span><span class="meta-pill">მომავალი ვიზიტები: <strong>{{ $upcomingCount }}</strong></span></div>
            </section>

            @if (session('success'))<div class="flash" role="status">✓ {{ session('success') }}</div>@endif
            @if ($errors->has('booking'))<div class="flash error" role="alert">{{ $errors->first('booking') }}</div>@endif

            <section class="summary" aria-label="ჯავშნების შეჯამება">
                <div class="summary-card"><div><div class="summary-label">მოლოდინში</div><div class="summary-note">სახელოსნოს პასუხი</div></div><div class="summary-number">{{ (int) ($counts['pending'] ?? 0) }}</div></div>
                <div class="summary-card"><div><div class="summary-label">დადასტურებული</div><div class="summary-note">დაგეგმილი ვიზიტები</div></div><div class="summary-number">{{ (int) ($counts['confirmed'] ?? 0) }}</div></div>
                <div class="summary-card"><div><div class="summary-label">შესრულებული</div><div class="summary-note">დასრულებული მომსახურება</div></div><div class="summary-number">{{ (int) ($counts['completed'] ?? 0) }}</div></div>
            </section>

            <div class="filters" aria-label="ჯავშნების ფილტრი">
                @foreach ($filters as $key => $label)
                    <a class="filter {{ $status === $key ? 'active' : '' }}" href="{{ route('account.service-bookings.index', $key === 'all' ? [] : ['status' => $key]) }}">
                        {{ $label }} <span class="filter-count">{{ $countFor($key) }}</span>
                    </a>
                @endforeach
            </div>

            <section>
                <div class="section-heading"><div><h2>{{ $status === 'all' ? 'ყველა ჯავშანი' : ($filters[$status] ?? 'ჯავშნები') }}</h2><p>სახელოსნო, მომსახურება, თარიღი და ჯავშნის სტატუსი.</p></div></div>
                @if ($bookings->count())
                    <div class="booking-list">
                        @foreach ($bookings as $booking)
                            @php
                                $bookingStatus = $booking->status;
                                $statusClass = $statusClasses[$bookingStatus] ?? 'pending';
                                $statusLabel = $statusLabels[$bookingStatus] ?? $booking->status_label;
                                $center = $booking->serviceCenter;
                            @endphp
                            <article class="booking-card">
                                <div class="booking-main">
                                    <div class="booking-top"><h3 class="workshop-name">{{ $center?->name ?? 'სახელოსნო' }}</h3>@if ($center?->is_verified)<span class="verified" title="გადამოწმებული სახელოსნო">✓</span>@endif<span class="status status-{{ $statusClass }}">{{ $statusLabel }}</span></div>
                                    <div class="service-name">{{ $booking->service_name }}</div>
                                    <div class="booking-sub">{{ $center?->city }}@if ($center?->address) · {{ $center->address }}@endif</div>
                                    <div class="booking-meta">
                                        <span>▦ <b>{{ $booking->scheduled_at?->format('d.m.Y') ?? '—' }}</b></span>
                                        <span>◷ <b>{{ $booking->scheduled_at?->format('H:i') ?? '—' }}</b></span>
                                        <span>⏱ <b>{{ $booking->duration_minutes }} წუთი</b></span>
                                    </div>
                                    <details class="details">
                                        <summary>ჯავშნის დეტალები</summary>
                                        <div class="detail-grid">
                                            <div class="detail-item"><span class="detail-label">მომსახურება</span><span class="detail-value">{{ $booking->service_name }}</span></div>
                                            <div class="detail-item"><span class="detail-label">ფასი დაჯავშნისას</span><span class="detail-value">{{ $booking->price_at_booking !== null ? number_format((float) $booking->price_at_booking, 2, '.', ' ') . ' ₾' : 'ფასი დასაზუსტებელია' }}</span></div>
                                            <div class="detail-item"><span class="detail-label">მოტოციკლი</span><span class="detail-value">{{ $booking->motorcycle ?: 'არ არის მითითებული' }}</span></div>
                                            <div class="detail-item"><span class="detail-label">სახელოსნოს ტელეფონი</span><span class="detail-value">{{ $center?->phone ?: 'არ არის მითითებული' }}</span></div>
                                            <div class="detail-item"><span class="detail-label">შენი საკონტაქტო ნომერი</span><span class="detail-value">{{ $booking->customer_phone }}</span></div>
                                            <div class="detail-item"><span class="detail-label">დამატებითი ინფორმაცია</span><span class="detail-value">{{ $booking->notes ?: 'არ არის მითითებული' }}</span></div>
                                        </div>
                                    </details>
                                </div>
                                <div class="booking-side">
                                    <div><div class="price-label">მომსახურების ფასი</div><div class="price">{{ $booking->price_at_booking !== null ? number_format((float) $booking->price_at_booking, 2, '.', ' ') : '—' }} <small>₾</small></div></div>
                                    <div class="actions">
                                        @if ($center)<a class="btn" href="{{ route('service-centers.show', $center->slug) }}">სახელოსნო ↗</a>@endif
                                        @if (in_array($bookingStatus, ['pending', 'confirmed'], true))
                                            <form method="POST" action="{{ route('account.service-bookings.cancel', $booking) }}" onsubmit="return confirm('ნამდვილად გსურს ამ ჯავშნის გაუქმება?');">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-danger" type="submit">ჯავშნის გაუქმება</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="pagination">{{ $bookings->links() }}</div>
                @else
                    <div class="empty"><div class="empty-icon">▦</div><h3>ჯავშნები ვერ მოიძებნა</h3><p>{{ $status === 'all' ? 'ჯერ სახელოსნოში მომსახურება არ დაგიჯავშნავს.' : 'ამ სტატუსით ჯავშანი არ გაქვს.' }} მოძებნე სახელოსნო და დაგეგმე ვიზიტი.</p><a class="btn btn-primary" href="{{ route('service-centers.index') }}">სახელოსნოების ნახვა →</a></div>
                @endif
            </section>
        </div>
        <footer class="footer"><div class="footer-inner"><span>© {{ date('Y') }} MotoHub</span><span>შენი მოტოსამყარო</span></div></footer>
    </main>
</div>
</body>
</html>
