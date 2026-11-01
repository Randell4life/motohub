<!DOCTYPE html>
<html lang="ka">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#070a12"><title>@yield('title', 'MotoHub | შენი მოტოსამყარო')</title>
<style>
:root{color-scheme:dark;--bg:#070a12;--panel:#0d1422;--panel2:#121d30;--line:#243653;--blue:#1677ff;--blue2:#42a5ff;--text:#f4f8ff;--muted:#98a8c2;--green:#36d6a0}
*{box-sizing:border-box}body{margin:0;background:radial-gradient(ellipse at 80% -10%,#10264a 0,transparent 42%),var(--bg);color:var(--text);font:14px/1.65 "Segoe UI",Arial,sans-serif}a{color:inherit;text-decoration:none}button,input,select,textarea{font:inherit}.wrap{width:min(1220px,calc(100% - 36px));margin:auto}.top{position:sticky;top:0;z-index:20;background:#070a12ed;backdrop-filter:blur(16px);border-bottom:1px solid var(--line)}.nav{min-height:68px;display:flex;align-items:center;justify-content:space-between;gap:18px;flex-wrap:wrap}.brand{font-size:23px;font-weight:950;letter-spacing:-1px}.brand span{color:var(--blue2)}.links{display:flex;gap:clamp(12px,2vw,24px);flex-wrap:wrap;align-items:center}.links a{font-size:12px;color:#c8d7ed;font-weight:700}.links a:hover{color:var(--blue2)}.btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;border:1px solid var(--blue);border-radius:8px;padding:10px 15px;background:var(--blue);color:white;font-weight:800;cursor:pointer}.btn.ghost{background:#101b2c;border-color:var(--line);color:#dceaff}.btn.small{padding:7px 10px;font-size:12px}.main{padding:34px 0 70px}.eyebrow{color:var(--blue2);font-size:10px;font-weight:900;letter-spacing:2px;text-transform:uppercase}.page-title{font-size:clamp(28px,4vw,44px);line-height:1.13;letter-spacing:-1px;margin:8px 0 10px}.muted{color:var(--muted)}.lead{max-width:760px;color:#b7c6dc}.grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:15px}.grid.two{grid-template-columns:repeat(2,minmax(0,1fr))}.card,.panel{min-width:0;border:1px solid var(--line);border-radius:14px;background:linear-gradient(145deg,var(--panel2),var(--panel));padding:18px;box-shadow:0 12px 35px #0002}.card h2,.card h3,.panel h2{margin:0 0 8px}.card p{margin:6px 0;color:var(--muted)}.stat{font-size:30px;font-weight:950;color:var(--blue2)}.actions{display:flex;gap:9px;flex-wrap:wrap;margin-top:14px}.form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.field{display:grid;gap:6px}.field.full{grid-column:1/-1}.field label{font-size:12px;color:#c6d6ed;font-weight:750}.field input,.field select,.field textarea{width:100%;min-width:0;border:1px solid #2a4162;border-radius:8px;background:#080f1c;color:var(--text);padding:11px 12px}.field textarea{min-height:90px;resize:vertical}.table{width:100%;border-collapse:collapse}.table th,.table td{text-align:left;padding:11px 9px;border-bottom:1px solid #243653;vertical-align:top}.table th{color:var(--muted);font-size:11px}.flash{padding:12px 15px;border:1px solid #246a54;border-radius:9px;background:#0d2a24;color:#9af0cf;margin-bottom:15px}.error{padding:12px 15px;border:1px solid #743b48;border-radius:9px;background:#29131b;color:#ffb8c4;margin-bottom:15px}.section{margin-top:26px}.section-head{display:flex;align-items:end;justify-content:space-between;gap:12px;margin-bottom:12px}.bike{display:flex;gap:14px;align-items:center}.bike img{width:112px;height:82px;object-fit:cover;border-radius:9px;background:#08101d}.tag{display:inline-block;border:1px solid #31527a;border-radius:30px;padding:3px 9px;color:#a9ceff;font-size:10px}.empty{padding:28px;text-align:center;color:var(--muted);border:1px dashed #355071;border-radius:12px}.footer{border-top:1px solid var(--line);padding:22px 0;color:var(--muted);font-size:11px}
@media(max-width:850px){.grid{grid-template-columns:repeat(2,minmax(0,1fr))}.nav{padding:12px 0}.links{order:3;width:100%;padding-bottom:10px}}@media(max-width:580px){.wrap{width:min(100% - 24px,1220px)}.grid,.grid.two,.form-grid{grid-template-columns:1fr}.field.full{grid-column:auto}.bike{align-items:flex-start}.bike img{width:90px;height:72px}.table{font-size:12px}.table th,.table td{padding:8px 5px}}

/* MotoHub Rewards — unified premium visual system */
.mh-rewards{--rw-line:#294563;--rw-panel:#0c1726;--rw-panel2:#112238;--rw-blue:#42a5ff;--rw-muted:#9aadc5;max-width:1180px!important;padding:34px 18px 76px!important;color:#f4f8ff!important}
.mh-rewards>section{position:relative;isolation:isolate;margin:16px 0 0!important;padding:clamp(17px,3vw,26px)!important;border:1px solid var(--rw-line)!important;border-radius:18px!important;background:linear-gradient(145deg,#101f32 0%,#0a1421 72%)!important;box-shadow:0 14px 38px #0003;overflow:hidden}
.mh-rewards>section:first-child{padding:clamp(22px,4vw,34px)!important;border-color:#35638e!important;background:radial-gradient(ellipse at 88% 0,#1c5b91aa 0,transparent 48%),linear-gradient(135deg,#142b45,#091421)!important}
.mh-rewards h1{line-height:1.08;letter-spacing:-1.2px;font-weight:950!important}
.mh-rewards h2{font-size:clamp(18px,2.4vw,23px)!important;font-weight:900!important;letter-spacing:-.35px}
.mh-rewards h3{font-weight:850!important}
.mh-rewards p{line-height:1.65}
.mh-rewards a{transition:color .18s ease,border-color .18s ease,transform .18s ease}
.mh-rewards a:hover{color:#b9ddff!important}
.mh-rewards button{min-height:42px;border-radius:10px!important;font-weight:850!important;transition:transform .18s ease,filter .18s ease,box-shadow .18s ease}
.mh-rewards button:hover:not(:disabled){filter:brightness(1.12);transform:translateY(-1px);box-shadow:0 8px 22px #1677ff30}
.mh-rewards button:disabled{opacity:.42;cursor:not-allowed}
.mh-rewards input,.mh-rewards select,.mh-rewards textarea{min-width:0;max-width:100%;border:1px solid #304b6b!important;border-radius:10px!important;background:#071321!important;color:#f4f8ff!important;padding:11px 12px!important;outline:none;transition:border-color .18s ease,box-shadow .18s ease}
.mh-rewards input:focus,.mh-rewards select:focus,.mh-rewards textarea:focus{border-color:#42a5ff!important;box-shadow:0 0 0 3px #1677ff25}
.mh-rewards article{transition:border-color .18s ease,transform .18s ease}
.mh-rewards article:hover{border-color:#477cac!important}
.mh-rewards table{width:100%;border-collapse:separate;border-spacing:0;overflow:hidden;border:1px solid #263f5c;border-radius:12px}
.mh-rewards th{background:#14263b;color:#9fc7ef;font-size:11px;letter-spacing:.04em;text-transform:uppercase}
.mh-rewards td{background:#0a1625;color:#d9e7f7}
.mh-rewards th,.mh-rewards td{padding:12px 13px!important;border-bottom:1px solid #223851!important;vertical-align:middle}
.mh-rewards tr:last-child td{border-bottom:0!important}
.mh-rewards .mh-rewards-stat{color:#78bdff;font-weight:950}
.mh-rewards-shop>section,.mh-rewards-admin>section{margin-top:15px!important}
.mh-rewards-shop form{gap:9px}
.mh-rewards-shop form button,.mh-rewards-admin form button,.mh-rewards-catalog-admin form button{background:linear-gradient(110deg,#1677ff,#268cff)!important;color:#fff!important;border:1px solid #4a9dff!important}
.mh-rewards-catalog-admin article,.mh-rewards-admin article{background:#091624!important;border-color:#2a4563!important;border-radius:13px!important}
.mh-rewards-catalog-admin article+article,.mh-rewards-admin article+article{margin-top:10px}
.mh-rewards-catalog-admin label,.mh-rewards-admin label{display:grid;gap:6px;color:#bfd0e5;font-size:12px;font-weight:800}
.mh-rewards-catalog-admin form,.mh-rewards-admin form{border:1px solid #294563;border-radius:13px;background:#0a1726;padding:16px!important}
.mh-rewards .empty,.mh-rewards [class*=empty]{color:#9aadc5}
.mh-rewards .reward-status,.mh-rewards .status-pill{display:inline-flex;align-items:center;gap:6px;padding:5px 10px;border:1px solid #31577b;border-radius:99px;background:#10263d;color:#a9d4ff;font-size:11px;font-weight:850}
@media(max-width:700px){.mh-rewards{padding:22px 12px 54px!important}.mh-rewards>section{border-radius:15px!important}.mh-rewards table{display:block;overflow-x:auto;white-space:nowrap}.mh-rewards button{max-width:100%}.mh-rewards-shop form{margin-top:10px}}
@media(prefers-reduced-motion:reduce){.mh-rewards *{transition:none!important}}
</style>
</head>
<body>
<header class="top"><div class="wrap nav">
<a class="brand" href="{{ route('hub.index') }}">MOTO<span>HUB</span></a>
<nav class="links">
<a href="{{ route('home') }}">მთავარი</a>
<a href="{{ route('garage.index') }}">ჩემი ავტოფარეხი</a>
<a href="{{ route('service-centers.index') }}">სერვისი</a>
<a href="{{ route('parts.index') }}">ნაწილები</a>
<a href="{{ route('clubs.index') }}">მოტოკლუბები</a>
<a href="{{ route('community.index') }}">Community</a>
@auth<a href="{{ route('favorites.index') }}">ფავორიტები</a><a href="{{ route('rewards.index') }}">Rewards</a><a href="{{ route('account.index') }}">კაბინეტი</a>@else<a href="{{ route('login') }}">შესვლა</a>@endauth
</nav>
</div></header>
<main class="wrap main">
@if(session('success'))<div class="flash">{{ session('success') }}</div>@endif
@if($errors->any())<div class="error">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
@yield('content')
</main>
<footer class="footer"><div class="wrap">© {{ date('Y') }} MotoHub — მოტოციკლის მთელი ცხოვრება ერთ სივრცეში.</div></footer>
</body></html>
