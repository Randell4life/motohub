<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#080d16">
    <title>@yield('title', 'MotoHub | სახელოსნოები')</title>
    <style>
        :root{--bg:#070b12;--panel:#0e1623;--panel2:#121e2e;--line:#24354a;--blue:#1677ff;--blue2:#55a7ff;--text:#f4f7fc;--muted:#a2b0c3;--green:#35d6a0;--red:#ff6e82;--radius:16px}
        *{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:radial-gradient(ellipse at 30% -15%,rgba(22,119,255,.12),transparent 40%),var(--bg);color:var(--text);font:15px/1.6 "Segoe UI",Arial,sans-serif}a{color:inherit;text-decoration:none}button,input,select,textarea{font:inherit}button{cursor:pointer}img{max-width:100%;display:block}.wrap{width:min(1200px,calc(100% - 36px));margin:auto}.site-header{position:sticky;top:0;z-index:20;background:rgba(7,11,18,.9);backdrop-filter:blur(18px);border-bottom:1px solid rgba(132,170,220,.12)}.nav{min-height:72px;display:flex;align-items:center;gap:26px}.brand{font-size:26px;font-weight:900;letter-spacing:-1px;white-space:nowrap}.brand span{color:var(--blue2)}.navlinks{display:flex;gap:19px;align-items:center;flex:1;color:#c3cedd;font-size:13px}.navlinks a:hover,.navlinks a.active{color:var(--blue2)}.nav-actions{display:flex;gap:9px;align-items:center}.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border:1px solid transparent;border-radius:10px;padding:11px 16px;background:var(--blue);color:white;font-weight:750;font-size:13px;transition:.18s}.btn:hover{background:#348aff;transform:translateY(-1px)}.btn-outline{background:#101a29;border-color:#314762;color:#e7effa}.btn-outline:hover{background:#18263a}.btn-danger{background:#281a27;border-color:#5c2c3e;color:#ffc1cb}.btn-small{padding:7px 10px;font-size:12px}.menu-button{display:none;background:#101a29;color:white;border:1px solid var(--line);border-radius:9px;padding:8px 11px}.page-head{padding:38px 0 22px}.eyebrow{font-size:11px;font-weight:850;letter-spacing:2px;text-transform:uppercase;color:var(--blue2)}h1,h2,h3,p{margin-top:0}h1{font-size:clamp(30px,4vw,46px);line-height:1.13;letter-spacing:-1.2px;margin:8px 0 12px}h2{font-size:24px;letter-spacing:-.5px;margin-bottom:8px}h3{font-size:17px;margin-bottom:8px}.muted{color:var(--muted)}.small{font-size:12px}.grid{display:grid;gap:18px}.filters{display:grid;grid-template-columns:1fr 1fr auto;gap:12px;margin:18px 0 24px;padding:15px;border:1px solid var(--line);border-radius:var(--radius);background:rgba(14,22,35,.9)}.field label{display:block;margin-bottom:6px;color:#cad5e4;font-size:12px;font-weight:700}.field input,.field select,.field textarea{width:100%;min-width:0;color:white;background:#080f1a;border:1px solid #2a3d54;border-radius:9px;padding:11px 12px;outline:none}.field textarea{min-height:100px;resize:vertical}.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--blue2);box-shadow:0 0 0 3px rgba(22,119,255,.14)}.cards{grid-template-columns:repeat(3,minmax(0,1fr));padding-bottom:42px}.card,.panel{border:1px solid var(--line);border-radius:var(--radius);background:linear-gradient(145deg,rgba(17,29,45,.98),rgba(9,15,24,.98));overflow:hidden}.card-body{padding:18px}.card-top{display:flex;justify-content:space-between;gap:12px;align-items:flex-start}.pill{display:inline-flex;align-items:center;gap:6px;font-size:11px;border:1px solid #28486d;border-radius:30px;padding:4px 9px;color:#a9d4ff;background:rgba(22,119,255,.1);white-space:nowrap}.pill-green{color:#86f0c9;border-color:#246b58;background:rgba(53,214,160,.08)}.details{display:flex;gap:12px;flex-wrap:wrap;color:var(--muted);font-size:12px;margin:10px 0}.service-list{border-top:1px solid rgba(132,170,220,.13);margin-top:16px;padding-top:8px}.service-row{display:flex;gap:14px;justify-content:space-between;align-items:flex-start;padding:8px 0;border-bottom:1px solid rgba(132,170,220,.08);font-size:12px}.service-row:last-child{border-bottom:0}.price{font-size:14px;font-weight:850;white-space:nowrap;color:#d9eaff}.card-footer{display:flex;gap:10px;margin-top:16px}.card-footer .btn{flex:1}.layout-two{display:grid;grid-template-columns:minmax(0,1.25fr) minmax(320px,.75fr);gap:20px;align-items:start;padding-bottom:48px}.panel{padding:22px;margin-bottom:18px}.panel-head{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:16px}.notice{padding:12px 14px;border-radius:10px;background:rgba(53,214,160,.09);border:1px solid rgba(53,214,160,.3);color:#a7f1d6;margin:12px 0 18px}.errors{padding:12px 16px;border-radius:10px;background:rgba(255,110,130,.08);border:1px solid rgba(255,110,130,.3);color:#ffc4ce;margin:12px 0 18px}.errors ul{margin:6px 0;padding-left:20px}.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}.span-two{grid-column:1/-1}.table-wrap{overflow-x:auto}table{width:100%;border-collapse:collapse;font-size:12px}th,td{text-align:left;padding:11px 10px;border-bottom:1px solid rgba(132,170,220,.12);vertical-align:top}th{color:#a7b8cc;font-weight:750;background:rgba(22,119,255,.06)}td{color:#e3eaf5}.table-actions{display:flex;gap:7px;flex-wrap:wrap}.hours-grid{display:grid;gap:9px}.hour-row{display:grid;grid-template-columns:110px 1fr 1fr 85px;gap:9px;align-items:center;padding:9px 0;border-bottom:1px solid rgba(132,170,220,.1)}.hour-row input[type=time]{padding:8px}.closed-label{display:flex;gap:5px;align-items:center;font-size:12px;color:var(--muted)}.booking-form{display:grid;gap:13px}.empty{padding:25px;text-align:center;border:1px dashed #344962;border-radius:12px;color:var(--muted)}.pagination{padding:20px 0}.footer{border-top:1px solid rgba(132,170,220,.12);padding:24px 0;color:#8294aa;font-size:12px}.status{display:inline-block;padding:4px 8px;border-radius:6px;font-size:11px;background:#17283d;color:#bfdaff}.status-confirmed,.status-completed{background:#103228;color:#83e6c0}.status-declined,.status-cancelled{background:#321e2a;color:#ffb6c3}.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}.stat{padding:16px;border:1px solid var(--line);border-radius:13px;background:#0b1421}.stat strong{display:block;font-size:25px;color:#e5f2ff}.stat span{font-size:11px;color:var(--muted)}
        @media(max-width:900px){.nav{gap:14px}.navlinks{gap:11px;font-size:12px}.cards{grid-template-columns:repeat(2,minmax(0,1fr))}.layout-two{grid-template-columns:1fr}.stats{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:680px){.wrap{width:calc(100% - 26px)}.nav{min-height:64px;flex-wrap:wrap;padding:10px 0}.brand{font-size:23px}.menu-button{display:inline-flex;margin-left:auto}.navlinks{display:none;flex:0 0 100%;order:3;align-items:stretch;flex-direction:column;gap:0;padding:8px 0;border-top:1px solid var(--line)}.navlinks.is-open{display:flex}.navlinks a{padding:10px 8px}.nav-actions{width:100%;display:none;order:4}.nav-actions.is-open{display:flex}.filters{grid-template-columns:1fr}.cards{grid-template-columns:1fr}.page-head{padding-top:28px}.form-grid{grid-template-columns:1fr}.span-two{grid-column:auto}.panel{padding:16px}.hour-row{grid-template-columns:1fr 1fr}.hour-row strong{grid-column:1/-1}.stats{grid-template-columns:1fr 1fr}}
    </style>
    @stack('head')
</head>
<body>
<header class="site-header">
    <div class="wrap nav">
        <a href="{{ route('home') }}" class="brand">Moto<span>Hub</span></a>
        <button class="menu-button" id="scMenuButton" type="button" aria-expanded="false">☰ მენიუ</button>
        <nav class="navlinks" id="scNavLinks">
            <a href="{{ route('home') }}">მთავარი</a>
            <a href="{{ route('service-centers.index') }}" class="active">სახელოსნოები</a>
            <a href="{{ route('motorcycles.index') }}">მოტოციკლები</a>
            <a href="{{ route('rentals.index') }}">გაქირავება</a>
        </nav>
        <div class="nav-actions" id="scNavActions">
            @auth
                <a class="btn btn-outline" href="{{ route('my-service-center.dashboard') }}">სახელოსნოს კაბინეტი</a>
                <a class="btn" href="{{ route('account.index') }}">ჩემი კაბინეტი</a>
            @else
                <a class="btn btn-outline" href="{{ route('login') }}">შესვლა</a>
            @endauth
        </div>
    </div>
</header>
<main class="wrap">
    @if (session('success')) <div class="notice">{{ session('success') }}</div> @endif
    @if ($errors->any())
        <div class="errors"><strong>გთხოვ, შეამოწმე შეყვანილი ინფორმაცია:</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    @yield('content')
</main>
<footer class="footer"><div class="wrap" style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap"><span>© {{ date('Y') }} MotoHub · სახელოსნოები</span><span>მოტო მომსახურება — ერთ სივრცეში</span></div></footer>
<script>
(() => {
    const button = document.getElementById('scMenuButton');
    const links = document.getElementById('scNavLinks');
    const actions = document.getElementById('scNavActions');
    if (!button || !links || !actions) return;
    button.addEventListener('click', () => {
        const open = button.getAttribute('aria-expanded') !== 'true';
        button.setAttribute('aria-expanded', String(open));
        links.classList.toggle('is-open', open);
        actions.classList.toggle('is-open', open);
    });
})();
</script>
@stack('scripts')
</body>
</html>
