<!DOCTYPE html>
<html lang="ka">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#050b16">
<title>MotoHub — შენი მოტოსამყარო</title>
<meta name="description" content="მოტოციკლები, ნაწილები, სერვისები და მოტო საზოგადოება — ყველაფერი ერთ სივრცეში.">
<link rel="preconnect" href="https://images.unsplash.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fira+GO:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{color-scheme:dark;--bg:#050a13;--panel:#08172b;--line:rgba(91,151,224,.24);--blue:#0878ff;--cyan:#25baff;--text:#f4f8ff;--muted:#9aabc2}
*{box-sizing:border-box}html{scroll-behavior:smooth;scroll-padding-top:80px}body{margin:0;background:var(--bg);color:var(--text);font-family:"Fira GO","Segoe UI",Arial,sans-serif;line-height:1.55}a{color:inherit;text-decoration:none}button,input,select{font:inherit}.wrap{width:min(1480px,calc(100% - 64px));margin:auto}
.ico{width:19px;height:19px;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;flex:none}
header{position:sticky;top:0;z-index:50;background:rgba(3,9,19,.95);border-bottom:1px solid var(--line);backdrop-filter:blur(18px)}
nav{min-height:66px;display:flex;align-items:center;justify-content:space-between;gap:18px}
.logo{display:flex;align-items:center;gap:8px;font-size:28px;font-weight:900;letter-spacing:-1.5px;white-space:nowrap}.logo b{color:#159cff}
.mark{width:34px;height:27px;display:inline-block;background:linear-gradient(135deg,#38c7ff,#0878ff);clip-path:polygon(0 100%,38% 0,62% 0,48% 42%,100% 0,100% 28%,58% 100%,34% 100%,49% 53%,18% 100%)}
.links{display:flex;align-items:center;gap:clamp(10px,1.4vw,22px);font-size:12px;font-weight:700;color:#d6e2f4}.links a:hover{color:var(--cyan)}
.links .drop{position:relative}.drop button{border:0;background:none;color:inherit;font-weight:inherit;cursor:pointer;padding:8px 0}.drop-menu{display:none;position:absolute;top:100%;left:-12px;min-width:210px;padding:8px;border:1px solid var(--line);border-radius:12px;background:#071326;box-shadow:0 20px 45px #0008}.drop.open .drop-menu{display:grid}.drop-menu a{padding:10px;border-radius:7px}.drop-menu a:hover{background:#0878ff22}
.actions{display:flex;align-items:center;gap:8px}.icon-btn,.menu-toggle{width:40px;height:40px;display:grid;place-items:center;border:1px solid var(--line);border-radius:10px;background:#0a1930;color:white}.account-link,.logout-btn{min-height:40px;display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:0 13px;border:1px solid var(--line);border-radius:9px;background:#0a1930;color:#eaf4ff;font-size:11px;font-weight:800;white-space:nowrap}.logout-btn{border-color:#31577e;background:#101d30;cursor:pointer}.logout-btn:hover{border-color:#168bff;background:#102b4c}.logout-form{margin:0}.logout-icon{width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}.add-btn,.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:42px;padding:10px 16px;border:1px solid #248fff;border-radius:9px;background:linear-gradient(135deg,#0865e8,#159cff);color:white;font-size:12px;font-weight:800;cursor:pointer}.btn.ghost{background:#071326;border-color:#4c83bd}.menu-toggle{display:none}
.hero{position:relative;min-height:400px;overflow:hidden;background:#061020}.hero:before{content:"";position:absolute;inset:0;background:url("https://hqgwubzpqihzqdlabmok.supabase.co/storage/v1/object/public/seo-images/montana-motorcycle-safety-foundation/featured-35956d49-816f-4aa6-b00b-cb78408528f6.webp") center 48%/cover}.hero:after{content:"";position:absolute;inset:0;background:linear-gradient(90deg,#030912 0%,rgba(3,10,21,.88) 28%,rgba(3,10,21,.42) 55%,transparent 85%),linear-gradient(0deg,#050a13,transparent 45%)}
.hero-inner{position:relative;z-index:1;min-height:400px;display:flex;align-items:center}.hero-copy{max-width:590px;padding:48px 0 64px}.eyebrow{display:flex;align-items:center;gap:13px;color:var(--cyan);font-size:11px;font-weight:800;letter-spacing:3px;text-transform:uppercase}.eyebrow:after{content:"";width:46px;height:2px;background:linear-gradient(90deg,#0878ff,#25baff)}
h1{margin:17px 0 13px;font-size:clamp(48px,6vw,78px);font-weight:900;letter-spacing:-2.5px;line-height:1.03}h1 span{display:block;color:#087bff}.hero p{max-width:510px;color:#d4e0f1;font-size:14px;line-height:1.9}.hero-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:22px}.hero-side{position:absolute;right:5%;top:35%;font-size:10px;line-height:2.1;letter-spacing:4px;text-align:center}
.search-wrap{position:relative;z-index:3;margin-top:-24px}.search{display:grid;grid-template-columns:1.4fr 1fr 1fr 1fr auto;gap:11px;align-items:end;padding:16px;border:1px solid #0878ff99;border-radius:14px;background:rgba(4,15,31,.97);box-shadow:0 22px 55px #0008}.field label{display:block;margin:0 0 6px 2px;color:#9eb6d5;font-size:10px;font-weight:700}.control{height:48px;display:flex;align-items:center;gap:9px;padding:0 12px;border:1px solid var(--line);border-radius:9px;background:#061225}.control input,.control select{width:100%;min-width:0;height:100%;border:0;outline:0;background:transparent;color:white;font-size:11px}.control select option{background:#071326}.search .btn{height:48px;min-width:110px}
.categories{display:grid;grid-template-columns:repeat(7,minmax(0,1fr));gap:11px;padding:22px 0}.category{position:relative;min-height:112px;display:flex;align-items:flex-end;justify-content:center;overflow:hidden;padding:12px 7px;border:1px solid var(--line);border-radius:11px;background:#09172a;font-size:11px;font-weight:800;text-align:center}.category:after{content:"";position:absolute;inset:0;background:linear-gradient(0deg,#020916 0%,rgba(2,9,22,.2) 100%)}.category img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.65;transition:.3s}.category span{position:relative;z-index:1;display:flex;align-items:center;gap:6px}.category:hover{border-color:#168bff;transform:translateY(-3px)}.category:hover img{opacity:.9;transform:scale(1.06)}
.home-grid{display:grid;grid-template-columns:1.08fr .74fr 1.5fr;gap:15px;padding-bottom:45px}.club{position:relative;min-height:235px;display:flex;align-items:flex-end;overflow:hidden;padding:22px;border:1px solid #168bff66;border-radius:13px;background:url("https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1400&q=90") center/cover}.club:before{content:"";position:absolute;inset:0;background:linear-gradient(90deg,#030a15f2,rgba(3,10,21,.58),rgba(3,10,21,.15))}.club-content{position:relative;max-width:320px}.club small{color:var(--cyan);font-weight:800}.club h2{font-size:29px;line-height:1.15;margin:9px 0}.club h2 span{color:#168aff}.club p{font-size:11px;color:#c5d5e9}
.benefits,.featured{border:1px solid var(--line);border-radius:13px;background:linear-gradient(145deg,#09172a,#06101f);padding:13px}.benefit{display:flex;align-items:center;gap:12px;padding:13px 0}.benefit+.benefit{border-top:1px solid #7899bd22}.benefit-icon{width:42px;height:42px;display:grid;place-items:center;border:1px solid #0878ff88;border-radius:50%;color:#25baff}.benefit strong{font-size:11px}.benefit small{display:block;color:var(--muted);font-size:9px;margin-top:4px}.featured-head{display:flex;justify-content:space-between;gap:10px;margin-bottom:10px;font-size:12px;font-weight:800}.featured-head a,.text-link{color:#25baff;font-size:10px;font-weight:800}.featured-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(145px,1fr));gap:10px}.mini{overflow:hidden;border:1px solid var(--line);border-radius:9px;background:#071326}.mini img{width:100%;height:126px;object-fit:cover;display:block}.mini-info{padding:8px}.mini-info strong{display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:9px}.mini-meta{display:flex;gap:7px;color:var(--muted);font-size:8px;margin-top:5px}.mini-price{margin-top:7px;color:#19aaff;font-size:13px;font-weight:900}
.section{padding:42px 0}.section-head{display:flex;align-items:flex-end;justify-content:space-between;gap:18px;margin-bottom:18px}.section-head h2{font-size:clamp(23px,3vw,32px);margin:8px 0 4px}.section-head p{font-size:12px;color:var(--muted);margin:0}.listings{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,265px),1fr));gap:16px;align-items:stretch}.listing{overflow:hidden;border:1px solid var(--line);border-radius:12px;background:linear-gradient(145deg,#0d1b2e,#071222);transition:.22s}.listing:hover{transform:translateY(-4px);border-color:#168bff}.listing-image{position:relative;height:220px;overflow:hidden;background:#0b1728}.listing-image img{width:100%;height:100%;object-fit:cover;display:block;transition:.4s}.listing:hover img{transform:scale(1.05)}.listing-badge{position:absolute;top:10px;left:10px;padding:5px 8px;border-radius:6px;background:#030b16d9;font-size:9px;font-weight:800}.listing-body{padding:15px}.listing-body h3{font-size:15px}.meta{font-size:11px}.listing-bottom{margin-top:15px}.listing-body h3{font-size:14px;margin:0 0 5px}.meta{display:flex;flex-wrap:wrap;gap:6px 10px;color:var(--muted);font-size:10px}.listing-bottom{display:flex;align-items:center;justify-content:space-between;margin-top:13px;padding-top:11px;border-top:1px solid #fff1}.price{color:#19aaff;font-size:19px;font-weight:900}.price small{color:#d5e4f7;font-size:11px}.empty{grid-column:1/-1;padding:30px;border:1px dashed var(--line);border-radius:12px;color:var(--muted);text-align:center}
.callout{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:25px 28px;border:1px solid #168bff66;border-radius:14px;background:linear-gradient(105deg,#071a31f5,#071120e8),url("https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1200&q=80") center/cover}.callout h2{margin:7px 0;font-size:24px}.callout p{margin:0;color:var(--muted);font-size:12px}
footer{margin-top:35px;padding:25px 0;border-top:1px solid var(--line);background:#040914;color:var(--muted);font-size:11px}.footer-inner{display:flex;align-items:center;justify-content:space-between;gap:16px}.footer-logo{font-size:21px;font-weight:900;color:white}.footer-logo span{color:#159cff}.footer-links{display:flex;flex-wrap:wrap;gap:15px}
@media(max-width:1280px){nav{gap:10px}.logo{font-size:24px}.links{gap:10px;font-size:11px}.actions{gap:6px}.account-link,.logout-btn{padding:0 9px;font-size:10px}.home-grid{grid-template-columns:1fr 1fr}.featured{grid-column:1/-1}}
@media(max-width:1020px){.menu-toggle{display:grid;place-items:center}.links,.actions{display:none;width:100%;flex:1 0 100%}.links.open{display:flex;order:3;flex-direction:column;align-items:stretch;padding:10px;border:1px solid var(--line);border-radius:12px;background:#061326}.actions.open{display:flex;order:4;flex-wrap:wrap}.drop-menu{position:static;box-shadow:none}.search{grid-template-columns:repeat(2,minmax(0,1fr))}.search .field:first-child{grid-column:1/-1}.search .btn{width:100%}.categories{grid-template-columns:repeat(4,minmax(0,1fr))}.home-grid{grid-template-columns:1fr 1fr}.featured{grid-column:1/-1}}
@media(max-width:650px){.wrap{width:calc(100% - 28px)}.actions.open{flex-direction:column;align-items:stretch}.actions.open>*{width:100%;justify-content:center}.logout-form{width:100%}.logout-form button{width:100%}.hero,.hero-inner{min-height:420px}.hero:after{background:linear-gradient(90deg,#030912f5,rgba(3,10,21,.72)),linear-gradient(0deg,#050a13,transparent 45%)}.hero-copy{padding:42px 0 55px}h1{font-size:clamp(42px,11vw,60px);letter-spacing:-1.6px}.hero p{font-size:12px}.hero-side{display:none}.search-wrap{margin-top:14px}.search{grid-template-columns:1fr;padding:12px}.search .field:first-child{grid-column:auto}.categories{grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}.category{min-height:100px}.home-grid{grid-template-columns:1fr;gap:10px}.club{min-height:210px}.featured{grid-column:auto}.featured-grid{grid-template-columns:repeat(3,minmax(0,1fr))}.mini img{height:82px}.section{padding:30px 0}.section-head{align-items:flex-start;flex-direction:column;gap:8px}.listings{grid-template-columns:1fr}.listing-image{height:220px}.callout{align-items:flex-start;flex-direction:column;padding:21px}.callout h2{font-size:21px}.footer-inner{align-items:flex-start;flex-direction:column}}
@media(prefers-reduced-motion:reduce){*{scroll-behavior:auto!important;transition:none!important}}
</style>
</head>
<body>
<header><nav class="wrap">
<a class="logo" href="{{ route('home') }}"><span class="mark"></span><span>Moto<b>Hub</b></span></a>
<button class="menu-toggle" id="menuToggle" aria-label="მენიუ" aria-expanded="false"><svg class="ico" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
<div class="links" id="mainLinks">
<a href="{{ route('home') }}">მთავარი</a>
<div class="drop"><button type="button">მოტოციკლები⌄</button><div class="drop-menu"><a href="{{ route('motorcycles.index') }}">მოტოციკლების კატალოგი</a><a href="{{ route('account.motorcycles.create') }}">განცხადების დამატება</a></div></div>
<div class="drop"><button type="button">ნაწილები⌄</button><div class="drop-menu"><a href="{{ route('parts.index') }}">ნაწილების კატალოგი</a><a href="{{ route('part-requests.create') }}">ნაწილის მოთხოვნა</a></div></div>
<div class="drop"><button type="button">სერვისები⌄</button><div class="drop-menu"><a href="{{ route('service-centers.index') }}">სერვისცენტრები</a><a href="{{ route('workshops.index') }}">სახელოსნოები</a><a href="{{ route('transport.companies') }}">ევაკუატორი</a></div></div>
<a href="{{ route('rentals.index') }}">გაქირავება</a><a href="{{ route('hub.index') }}">Hub</a><a href="{{ route('clubs.index') }}">კლუბები</a><a href="{{ route('community.index') }}">Community</a>
</div>
<div class="actions" id="navActions">
<a class="icon-btn" href="{{ route('motorcycles.index') }}" aria-label="ძებნა"><svg class="ico" viewBox="0 0 24 24"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/></svg></a>
<a class="add-btn" href="{{ route('account.motorcycles.create') }}">＋ განცხადების დამატება</a>
@guest<a class="account-link" href="{{ route('login') }}">შესვლა</a><a class="account-link" href="{{ route('register') }}">რეგისტრაცია</a>@else
<a class="account-link" href="{{ route('account.index') }}"><span>♙</span> ჩემი კაბინეტი</a>
<form class="logout-form" method="POST" action="{{ route('logout') }}">@csrf<button class="logout-btn" type="submit"><svg class="logout-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3"/><path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/></svg> ანგარიშიდან გასვლა</button></form>
@endguest
</div>
</nav></header>
<main>
<section class="hero"><div class="wrap hero-inner"><div class="hero-copy">
<div class="eyebrow">MotoHub — შენი მოტოსამყარო</div>
<h1>იპოვე შენი <span>თავისუფლება.</span></h1>
<p>მოტოციკლების განცხადებები, ნაწილები, სერვისები, გაქირავება, კლუბები და ყველაფერი ერთ პლატფორმაზე.</p>
<div class="hero-actions"><a class="btn" href="#motorcycles">⌕ მოტოციკლების ძებნა</a><a class="btn ghost" href="{{ route('hub.index') }}">▷ როგორ მუშაობს?</a></div>
</div><div class="hero-side">MORE<br>THAN<br>JUST<br>BIKES</div></div></section>
<div class="wrap search-wrap"><form class="search" action="{{ route('home') }}#motorcycles" method="GET">
<div class="field"><label for="q">მოძებნე ბრენდი ან მოდელი</label><div class="control"><svg class="ico" viewBox="0 0 24 24"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/></svg><input id="q" name="q" value="{{ request('q') }}" placeholder="მაგ: Yamaha, BMW, Honda"></div></div>
<div class="field"><label for="type">ტიპი / მდგომარეობა</label><div class="control">🏍 <select id="type" name="type"><option value="">ყველა კატეგორია</option><option value="used" @selected(request('type') === 'used')>მეორადი</option><option value="new" @selected(request('type') === 'new')>ახალი</option></select></div></div>
<div class="field"><label for="budget">მაქსიმალური ბიუჯეტი</label><div class="control">⌖ <select id="budget" name="budget"><option value="">ნებისმიერი ფასი</option><option value="5000" @selected(request('budget') == '5000')>5,000 ₾-მდე</option><option value="10000" @selected(request('budget') == '10000')>10,000 ₾-მდე</option><option value="20000" @selected(request('budget') == '20000')>20,000 ₾-მდე</option><option value="50000" @selected(request('budget') == '50000')>50,000 ₾-მდე</option></select></div></div>
<div class="field"><label>მდებარეობა</label><div class="control">⌖ <select disabled><option>ნებისმიერი რეგიონი</option></select></div></div>
<button class="btn" type="submit">⌕ ძებნა</button>
</form></div>
<div class="wrap">
<div class="categories">
<a class="category" href="{{ route('motorcycles.index') }}"><img src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=500&q=80" alt="" loading="lazy"><span>🏍 მოტოციკლები</span></a>
<a class="category" href="{{ route('parts.index') }}"><img src="https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=500&q=80" alt="" loading="lazy"><span>⚙ ნაწილები</span></a>
<a class="category" href="{{ route('service-centers.index') }}"><img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=500&q=80" alt="" loading="lazy"><span>🔧 სერვისები</span></a>
<a class="category" href="{{ route('rentals.index') }}"><img src="https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=500&q=80" alt="" loading="lazy"><span>🛵 გაქირავება</span></a>
<a class="category" href="{{ route('workshops.index') }}"><img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=500&q=80" alt="" loading="lazy"><span>🏁 სახელოსნოები</span></a>
<a class="category" href="{{ route('clubs.index') }}"><img src="https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=500&q=80" alt="" loading="lazy"><span>♧ კლუბები</span></a>
<a class="category" href="{{ route('clubs.index') }}"><img src="https://images.unsplash.com/photo-1519608487953-e999c86e7455?auto=format&fit=crop&w=500&q=80" alt="" loading="lazy"><span>▦ ღონისძიებები</span></a>
</div>
<div class="home-grid">
<a class="club" href="{{ route('clubs.index') }}"><div class="club-content"><small>✦ MotoHub CLUB</small><h2>შეუერთდი <span>კლუბებს</span></h2><p>გაიცანი თანამოაზრეები და აღმოაჩინე ახალი მარშრუტები.</p><span class="btn ghost">გაიგე მეტი →</span></div></a>
<div class="benefits">
<div class="benefit"><span class="benefit-icon">♢</span><div><strong>უსაფრთხო გარემო</strong><small>მომხმარებლები და სანდო განცხადებები</small></div></div>
<div class="benefit"><span class="benefit-icon">♙</span><div><strong>რეალური მოტომოყვარულები</strong><small>ერთ სივრცეში შეკრებილი საზოგადოება</small></div></div>
<div class="benefit"><span class="benefit-icon">☏</span><div><strong>მხარდაჭერა</strong><small>დახმარება პლატფორმის გამოყენებისას</small></div></div>
</div>
<div class="featured"><div class="featured-head"><span>პოპულარული განცხადებები</span><a href="{{ route('motorcycles.index') }}">ყველას ნახვა →</a></div><div class="featured-grid">
@forelse($motorcycles->take(3) as $motorcycle)
<a class="mini" href="{{ route('motorcycles.show',$motorcycle->slug) }}">
@php $miniPath=$motorcycle->image_path ?: optional($motorcycle->images->first())->image_path; $miniUrl=$miniPath ? (\Illuminate\Support\Str::startsWith($miniPath,['http://','https://']) ? $miniPath : \Illuminate\Support\Facades\Storage::url($miniPath)) : 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=600&q=80'; @endphp
<img src="{{ $miniUrl }}" alt="{{ $motorcycle->title }}" loading="lazy"><div class="mini-info"><strong>{{ $motorcycle->title }}</strong><div class="mini-meta"><span>{{ $motorcycle->engine_cc ? $motorcycle->engine_cc.' cc' : $motorcycle->brand }}</span><span>{{ $motorcycle->year }}</span></div><div class="mini-price">{{ number_format((float)$motorcycle->price,0) }} ₾</div></div></a>
@empty<div class="empty">პოპულარული განცხადებები მალე გამოჩნდება.</div>@endforelse
</div></div>
</div>
</div>
<section class="section" id="motorcycles"><div class="wrap">
<div class="section-head"><div><div class="eyebrow">MOTO MARKETPLACE</div><h2>მოტოციკლების განცხადებები</h2><p>შეარჩიე სასურველი მოდელი და იპოვე შენი შემდეგი თავგადასავალი.</p></div><a class="text-link" href="{{ route('motorcycles.index') }}">ყველა განცხადება →</a></div>
<div class="listings">
@forelse($motorcycles as $motorcycle)
<a href="{{ route('motorcycles.show',$motorcycle->slug) }}"><article class="listing"><div class="listing-image">
@php $photo=$motorcycle->image_path ?: optional($motorcycle->images->first())->image_path; $photoUrl=$photo ? (\Illuminate\Support\Str::startsWith($photo,['http://','https://']) ? $photo : \Illuminate\Support\Facades\Storage::url($photo)) : 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=900&q=85'; @endphp
<img src="{{ $photoUrl }}" alt="{{ $motorcycle->title }}" loading="lazy"><span class="listing-badge">{{ $motorcycle->condition==='new' ? 'ახალი' : 'მეორადი' }}</span></div><div class="listing-body"><h3>{{ $motorcycle->title }}</h3><div class="meta"><span>{{ $motorcycle->brand }} {{ $motorcycle->model }}</span>@if($motorcycle->year)<span>{{ $motorcycle->year }}</span>@endif</div><div class="meta" style="margin-top:7px">@if($motorcycle->engine_cc)<span>{{ $motorcycle->engine_cc }} cc</span>@endif @if($motorcycle->location)<span>{{ $motorcycle->location }}</span>@endif</div><div class="listing-bottom"><span class="price">{{ number_format((float)$motorcycle->price,0,'.',' ') }} <small>₾</small></span><span class="text-link">დეტალები →</span></div></div></article></a>
@empty<div class="empty"><strong>მოტოციკლები ჯერ არ მოიძებნა</strong><p>სცადე სხვა საძიებო სიტყვა ან გაასუფთავე ფილტრები.</p><a class="btn" href="{{ route('motorcycles.index') }}">ყველა განცხადება</a></div>@endforelse
</div>
</div></section>
<section class="section" id="parts"><div class="wrap">
<div class="section-head"><div><div class="eyebrow">MOTOHUB PARTS</div><h2>მოტოციკლის ნაწილები</h2><p>ახალი და მეორადი ნაწილები — შენი მოტოციკლისთვის.</p></div><a class="text-link" href="{{ route('parts.index') }}">ნაწილების კატალოგი →</a></div>
<div class="listings">
@forelse($partListings as $partListing)
<a href="{{ route('parts.show',[$partListing->source ?? 'marketplace',$partListing->id]) }}"><article class="listing"><div class="listing-image">
@if($partListing->image_path)<img src="{{ \Illuminate\Support\Facades\Storage::url($partListing->image_path) }}" alt="{{ $partListing->title }}" loading="lazy">@else<img src="https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=900&q=85" alt="{{ $partListing->title }}" loading="lazy">@endif
<span class="listing-badge">{{ $partListing->condition==='new' ? 'ახალი ნაწილი' : 'მეორადი ნაწილი' }}</span></div><div class="listing-body"><h3>{{ $partListing->title }}</h3><div class="meta">@if($partListing->brand)<span>{{ $partListing->brand }}</span>@endif @if($partListing->category)<span>{{ $partListing->category }}</span>@endif</div><div class="listing-bottom"><span class="price">{{ number_format((float)$partListing->price,0,'.',' ') }} <small>₾</small></span><span class="text-link">ნახვა →</span></div></div></article></a>
@empty<div class="empty">ნაწილების განცხადებები მალე გამოჩნდება. <a class="text-link" href="{{ route('parts.index') }}">კატალოგის ნახვა →</a></div>@endforelse
</div></div></section>
<section class="section"><div class="wrap"><div class="callout"><div><div class="eyebrow">MOTOHUB PARTS</div><h2>ეძებ კონკრეტულ ნაწილს?</h2><p>ვერ იპოვე საჭირო დეტალი? გამოგვიგზავნე მოთხოვნა და დაგეხმარებით მოძებნაში.</p></div><a class="btn" href="{{ route('part-requests.create') }}">ნაწილის მოთხოვნა →</a></div></div></section>
</main>
<footer><div class="wrap footer-inner"><a class="footer-logo" href="{{ route('home') }}">Moto<span>Hub</span></a><span>© {{ date('Y') }} MotoHub. ყველა უფლება დაცულია.</span><div class="footer-links"><a href="{{ route('motorcycles.index') }}">მოტოციკლები</a><a href="{{ route('parts.index') }}">ნაწილები</a><a href="{{ route('rentals.index') }}">გაქირავება</a><a href="{{ route('community.index') }}">Community</a></div></div></footer>
<script>
(() => {
 const toggle=document.getElementById('menuToggle'),links=document.getElementById('mainLinks'),actions=document.getElementById('navActions');
 if(toggle&&links&&actions){toggle.addEventListener('click',()=>{const open=toggle.getAttribute('aria-expanded')!=='true';toggle.setAttribute('aria-expanded',String(open));links.classList.toggle('open',open);actions.classList.toggle('open',open);});}
 document.querySelectorAll('.drop>button').forEach(button=>button.addEventListener('click',()=>{const parent=button.parentElement;document.querySelectorAll('.drop').forEach(item=>{if(item!==parent)item.classList.remove('open');});parent.classList.toggle('open');}));
 document.addEventListener('click',event=>{if(!event.target.closest('nav'))document.querySelectorAll('.drop').forEach(item=>item.classList.remove('open'));});
 document.addEventListener('keydown',event=>{if(event.key==='Escape')document.querySelectorAll('.drop').forEach(item=>item.classList.remove('open'));});
})();
</script>
</body>
</html>