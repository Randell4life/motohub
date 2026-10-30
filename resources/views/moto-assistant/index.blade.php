<!DOCTYPE html>
<html lang="ka">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Moto ასისტენტი | MotoHub</title>
<style>
:root{color-scheme:dark;--bg:#070a12;--panel:#0d1422;--line:#243653;--blue:#1677ff;--bright:#42a5ff;--text:#f4f8ff;--muted:#98a8c2}*{box-sizing:border-box}body{margin:0;background:radial-gradient(ellipse at top,#102343,var(--bg) 55%);color:var(--text);font:15px/1.6 Segoe UI,Arial,sans-serif}.wrap{max-width:980px;margin:auto;padding:24px}.nav{display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid var(--line);padding:8px 0 20px}.logo{font-size:25px;font-weight:900}.logo span{color:var(--bright)}a{color:var(--bright);text-decoration:none}.hero{padding:48px 0 24px}.eyebrow{color:var(--bright);font-weight:800;letter-spacing:2px;font-size:12px}.hero h1{font-size:clamp(34px,6vw,58px);line-height:1.05;letter-spacing:-1.5px;margin:12px 0}.hero p,.muted{color:var(--muted)}.panel{background:linear-gradient(145deg,#101d31,#0b1220);border:1px solid var(--line);border-radius:18px;padding:clamp(18px,4vw,32px);box-shadow:0 20px 80px #0005}label{display:block;font-weight:700;margin:18px 0 7px}input,textarea,select{width:100%;background:#070d18;border:1px solid #304563;border-radius:10px;color:var(--text);padding:13px;font:inherit}textarea{min-height:140px;resize:vertical}.checks{display:flex;flex-wrap:wrap;gap:10px}.check{display:flex;align-items:center;gap:8px;border:1px solid var(--line);border-radius:10px;padding:10px;font-size:13px}.check input{width:auto}.btn{display:inline-flex;justify-content:center;background:var(--blue);color:white;border:0;border-radius:10px;padding:14px 20px;font-weight:800;cursor:pointer;margin-top:22px}.error{color:#ff9c9c}.note{font-size:13px;color:var(--muted);margin-top:20px}@media(max-width:600px){.wrap{padding:16px}.hero{padding-top:32px}}
</style></head>
<body><div class="wrap"><header class="nav"><a class="logo" href="{{ route('home') }}">Moto<span>Hub</span></a><nav style="display:flex;gap:14px;flex-wrap:wrap;align-items:center"><a href="{{ route('motorcycles.index') }}">მოტოციკლები</a><a href="{{ route('rentals.index') }}">გაქირავება</a><a href="{{ route('parts.index') }}">ნაწილები</a><a href="{{ route('service-centers.index') }}">სახელოსნოები</a></nav></header>
<section class="hero"><div class="eyebrow">MOTOHUB INTELLIGENCE</div><h1>მოტო ასისტენტი<span style="color:var(--bright)">.</span></h1><p>აღწერე პრობლემა და მიიღე შესაძლო მიზეზები, უსაფრთხოების რჩევები და შემდეგი ნაბიჯები.</p></section>
<form class="panel" method="POST" action="{{ route('moto-assistant.diagnose') }}">@csrf
@if($errors->any())<div class="error">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
<label for="motorcycle">მოტოციკლის მარკა და მოდელი *</label><input id="motorcycle" name="motorcycle" value="{{ old('motorcycle') }}" placeholder="მაგ. Yamaha R1 2016" required maxlength="160">
<label for="symptom">რა პრობლემა აქვს? *</label><textarea id="symptom" name="symptom" required maxlength="3000" placeholder="მაგ. ცხელ ძრავაზე რთულად იქოქება და ბენზინის სუნი აქვს...">{{ old('symptom') }}</textarea>
<label for="when">როდის ჩნდება პრობლემა?</label><select id="when" name="when"><option value="">აირჩიე</option><option value="cold">ცივ ძრავაზე</option><option value="warm">ცხელ ძრავაზე</option><option value="always">ყოველთვის</option><option value="under_load">აჩქარებისას / დატვირთვაზე</option></select>
<label>არის რომელიმე საგანგაშო ნიშანი?</label><div class="checks">
@foreach(['oil_pressure'=>'ზეთის წნევის ნათურა','overheating'=>'გადახურება','brake_issue'=>'მუხრუჭის პრობლემა','fuel_leak'=>'საწვავის გაჟონვა','smoke'=>'კვამლი','none'=>'არცერთი'] as $value=>$label)<label class="check"><input type="checkbox" name="warning_signs[]" value="{{ $value }}"> {{ $label }}</label>@endforeach
</div>
<button class="btn" type="submit">შესაძლო მიზეზების ნახვა ↗</button>
<p class="note">ასისტენტი იძლევა ზოგად ინფორმაციას და არა საბოლოო დიაგნოზს. უსაფრთხოების კრიტიკული პრობლემისას არ მართო მოტოციკლი — მიმართე კვალიფიციურ მექანიკოსს.</p>
</form></div></body></html>
