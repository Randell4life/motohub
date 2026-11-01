<!DOCTYPE html>
<html lang="ka">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#070a12"><title>@yield('title', 'MotoHub | შენი მოტოსამყარო')</title>
<style>
:root{color-scheme:dark;--bg:#070a12;--panel:#0d1422;--panel2:#121d30;--line:#243653;--blue:#1677ff;--blue2:#42a5ff;--text:#f4f8ff;--muted:#98a8c2;--green:#36d6a0}
*{box-sizing:border-box}body{margin:0;background:radial-gradient(ellipse at 80% -10%,#10264a 0,transparent 42%),var(--bg);color:var(--text);font:14px/1.65 "Segoe UI",Arial,sans-serif}a{color:inherit;text-decoration:none}button,input,select,textarea{font:inherit}.wrap{width:min(1220px,calc(100% - 36px));margin:auto}.top{position:sticky;top:0;z-index:20;background:#070a12ed;backdrop-filter:blur(16px);border-bottom:1px solid var(--line)}.nav{min-height:68px;display:flex;align-items:center;justify-content:space-between;gap:18px;flex-wrap:wrap}.brand{font-size:23px;font-weight:950;letter-spacing:-1px}.brand span{color:var(--blue2)}.links{display:flex;gap:clamp(12px,2vw,24px);flex-wrap:wrap;align-items:center}.links a{font-size:12px;color:#c8d7ed;font-weight:700}.links a:hover{color:var(--blue2)}.btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;border:1px solid var(--blue);border-radius:8px;padding:10px 15px;background:var(--blue);color:white;font-weight:800;cursor:pointer}.btn.ghost{background:#101b2c;border-color:var(--line);color:#dceaff}.btn.small{padding:7px 10px;font-size:12px}.main{padding:34px 0 70px}.eyebrow{color:var(--blue2);font-size:10px;font-weight:900;letter-spacing:2px;text-transform:uppercase}.page-title{font-size:clamp(28px,4vw,44px);line-height:1.13;letter-spacing:-1px;margin:8px 0 10px}.muted{color:var(--muted)}.lead{max-width:760px;color:#b7c6dc}.grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:15px}.grid.two{grid-template-columns:repeat(2,minmax(0,1fr))}.card,.panel{min-width:0;border:1px solid var(--line);border-radius:14px;background:linear-gradient(145deg,var(--panel2),var(--panel));padding:18px;box-shadow:0 12px 35px #0002}.card h2,.card h3,.panel h2{margin:0 0 8px}.card p{margin:6px 0;color:var(--muted)}.stat{font-size:30px;font-weight:950;color:var(--blue2)}.actions{display:flex;gap:9px;flex-wrap:wrap;margin-top:14px}.form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.field{display:grid;gap:6px}.field.full{grid-column:1/-1}.field label{font-size:12px;color:#c6d6ed;font-weight:750}.field input,.field select,.field textarea{width:100%;min-width:0;border:1px solid #2a4162;border-radius:8px;background:#080f1c;color:var(--text);padding:11px 12px}.field textarea{min-height:90px;resize:vertical}.table{width:100%;border-collapse:collapse}.table th,.table td{text-align:left;padding:11px 9px;border-bottom:1px solid #243653;vertical-align:top}.table th{color:var(--muted);font-size:11px}.flash{padding:12px 15px;border:1px solid #246a54;border-radius:9px;background:#0d2a24;color:#9af0cf;margin-bottom:15px}.error{padding:12px 15px;border:1px solid #743b48;border-radius:9px;background:#29131b;color:#ffb8c4;margin-bottom:15px}.section{margin-top:26px}.section-head{display:flex;align-items:end;justify-content:space-between;gap:12px;margin-bottom:12px}.bike{display:flex;gap:14px;align-items:center}.bike img{width:112px;height:82px;object-fit:cover;border-radius:9px;background:#08101d}.tag{display:inline-block;border:1px solid #31527a;border-radius:30px;padding:3px 9px;color:#a9ceff;font-size:10px}.empty{padding:28px;text-align:center;color:var(--muted);border:1px dashed #355071;border-radius:12px}.footer{border-top:1px solid var(--line);padding:22px 0;color:var(--muted);font-size:11px}
@media(max-width:850px){.grid{grid-template-columns:repeat(2,minmax(0,1fr))}.nav{padding:12px 0}.links{order:3;width:100%;padding-bottom:10px}}@media(max-width:580px){.wrap{width:min(100% - 24px,1220px)}.grid,.grid.two,.form-grid{grid-template-columns:1fr}.field.full{grid-column:auto}.bike{align-items:flex-start}.bike img{width:90px;height:72px}.table{font-size:12px}.table th,.table td{padding:8px 5px}}
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
