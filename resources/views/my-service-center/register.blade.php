@extends('layouts.service-centers')
@section('title', 'MotoHub | სერვისცენტრის რეგისტრაცია')
@push('head')
<style>
.sc-register{padding:30px 0 58px}.sc-register-hero{position:relative;isolation:isolate;overflow:hidden;display:grid;grid-template-columns:minmax(0,1fr) 260px;gap:24px;align-items:center;padding:clamp(22px,4vw,42px);border:1px solid #2c4d70;border-radius:22px;background:radial-gradient(ellipse at 85% 0,#1b4d7c 0,transparent 42%),linear-gradient(125deg,#142a43,#091421 76%);box-shadow:0 22px 55px #0005}.sc-register-hero:after{content:"";position:absolute;z-index:-1;right:7%;bottom:-130px;width:270px;height:270px;border:1px solid #83c4ff24;transform:rotate(42deg)}.sc-register-kicker{display:flex;align-items:center;gap:9px;color:#8cc7ff;font-size:10px;font-weight:900;letter-spacing:2px}.sc-register-kicker:before{content:"";width:24px;height:2px;background:#328cff}.sc-register-hero h1{max-width:700px;margin:15px 0 12px;font-size:clamp(34px,5vw,58px);font-weight:950;line-height:1.04;letter-spacing:-1.8px}.sc-register-hero h1 span{color:#65b4ff}.sc-register-hero p{max-width:650px;margin:0;color:#a6bad2;font-size:14px;line-height:1.85}.sc-register-aside{padding:17px;border:1px solid #ffffff20;border-radius:15px;background:#071522a8;backdrop-filter:blur(8px)}.sc-register-aside b{display:block;color:#eff7ff;font-size:13px}.sc-register-aside ul{display:grid;gap:11px;margin:14px 0 0;padding:0;list-style:none}.sc-register-aside li{display:flex;gap:9px;color:#a8bdd4;font-size:11px;line-height:1.5}.sc-register-aside li:before{content:"✓";display:grid;place-items:center;flex:0 0 19px;width:19px;height:19px;border-radius:50%;background:#12385a;color:#80c1ff;font-weight:900}.sc-register-layout{display:grid;grid-template-columns:minmax(0,1fr) 285px;gap:18px;align-items:start;margin-top:18px}.sc-register-form,.sc-register-summary{border:1px solid #263d57;border-radius:17px;background:linear-gradient(150deg,#111e2e,#0a121e);box-shadow:0 14px 35px #0003}.sc-register-form{padding:clamp(16px,3vw,27px)}.sc-register-section{padding:0 0 22px;margin:0 0 21px;border-bottom:1px solid #ffffff14}.sc-register-section:last-of-type{border:0;margin-bottom:0}.sc-register-section-head{display:flex;gap:12px;align-items:flex-start;margin-bottom:16px}.sc-register-number{display:grid;place-items:center;flex:0 0 32px;width:32px;height:32px;border:1px solid #315c87;border-radius:9px;background:#102b47;color:#81c2ff;font-size:11px;font-weight:900}.sc-register-section-head h2{margin:0;color:#f1f6ff;font-size:17px;font-weight:900}.sc-register-section-head p{margin:4px 0 0;color:#839bb6;font-size:11px}.sc-register-fields{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.sc-register-field{display:flex;flex-direction:column;gap:7px;min-width:0}.sc-register-field.full{grid-column:1/-1}.sc-register-field label{color:#c6d5e7;font-size:11px;font-weight:800}.sc-register-field label small{color:#748ba6;font-weight:500}.sc-register-field input,.sc-register-field textarea{width:100%;min-width:0;padding:12px 13px;border:1px solid #2b4562;border-radius:10px;background:#081320;color:#f2f7ff;font-size:13px;outline:none;transition:border-color .18s,box-shadow .18s}.sc-register-field textarea{min-height:108px;resize:vertical}.sc-register-field input:focus,.sc-register-field textarea:focus{border-color:#4b9ef1;box-shadow:0 0 0 3px #1677ff20}.sc-register-field input::placeholder,.sc-register-field textarea::placeholder{color:#60748d}.sc-register-plans{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.sc-register-plan{position:relative;display:flex;flex-direction:column;gap:9px;min-width:0;padding:15px;border:1px solid #2a405b;border-radius:13px;background:#0a1522;cursor:pointer;transition:.18s}.sc-register-plan:hover{border-color:#477db1;transform:translateY(-1px)}.sc-register-plan:has(input:checked){border-color:#398fff;background:linear-gradient(145deg,#123252,#0c1b2b);box-shadow:inset 0 0 0 1px #398fff,0 8px 22px #1677ff18}.sc-register-plan input{position:absolute;top:14px;right:14px;width:16px;height:16px;accent-color:#2688ff}.sc-register-plan-top{display:flex;align-items:center;gap:8px;color:#78baff;font-size:10px;font-weight:950;letter-spacing:1.5px}.sc-register-plan-price{color:#f4f8ff;font-size:23px;font-weight:950;letter-spacing:-.6px}.sc-register-plan-price small{color:#8da4be;font-size:10px;font-weight:600;letter-spacing:0}.sc-register-plan-desc{min-height:35px;color:#a0b4cb;font-size:10px;line-height:1.6}.sc-register-plan ul{display:grid;gap:6px;margin:2px 0 0;padding:10px 0 0;border-top:1px solid #ffffff14;list-style:none}.sc-register-plan li{color:#b8c9dc;font-size:10px;line-height:1.4}.sc-register-plan li:before{content:"✓";margin-right:7px;color:#66b6ff;font-weight:900}.sc-register-note{margin:12px 0 0;padding:11px 12px;border:1px solid #35506d;border-radius:10px;background:#101f30;color:#91a9c3;font-size:10px;line-height:1.7}.sc-register-summary{position:sticky;top:90px;padding:19px}.sc-register-summary .eyebrow{font-size:9px}.sc-register-summary h3{margin:7px 0 14px;color:#f0f6ff;font-size:17px}.sc-register-summary-row{display:flex;justify-content:space-between;gap:8px;padding:10px 0;border-bottom:1px solid #ffffff13;color:#91a8c2;font-size:10px}.sc-register-summary-row strong{color:#eaf4ff;text-align:right}.sc-register-summary-total{margin-top:13px;padding:13px;border:1px solid #2c4e71;border-radius:11px;background:#10243a}.sc-register-summary-total small{display:block;color:#8ca8c6;font-size:9px}.sc-register-summary-total strong{display:block;margin-top:4px;color:#f1f7ff;font-size:25px;font-weight:950}.sc-register-submit{display:flex;flex-direction:column;gap:10px;margin-top:17px}.sc-register-submit .btn{width:100%;min-height:46px;font-size:13px}.sc-register-submit p{margin:0;color:#788fa9;font-size:9px;line-height:1.6;text-align:center}.sc-register-bottom{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:18px;color:#7f96b0;font-size:10px}.sc-register-bottom a{color:#a9cfff;text-decoration:underline;text-underline-offset:3px}
@media(max-width:850px){.sc-register-hero{grid-template-columns:1fr}.sc-register-aside ul{grid-template-columns:repeat(3,minmax(0,1fr))}.sc-register-layout{grid-template-columns:1fr}.sc-register-summary{position:static;order:-1}}
@media(max-width:600px){.sc-register{padding:18px 0 35px}.sc-register-hero{gap:18px;padding:22px 18px;border-radius:17px}.sc-register-hero h1{font-size:36px}.sc-register-hero p{font-size:12px}.sc-register-aside{padding:13px}.sc-register-aside ul{grid-template-columns:1fr 1fr;gap:9px}.sc-register-aside li{font-size:10px}.sc-register-layout{gap:11px;margin-top:11px}.sc-register-form{padding:16px 13px}.sc-register-fields,.sc-register-plans{grid-template-columns:1fr}.sc-register-field.full{grid-column:auto}.sc-register-plan{padding:13px}.sc-register-plan-desc{min-height:0}.sc-register-summary{padding:15px}.sc-register-bottom{align-items:flex-start;flex-direction:column}}
</style>
@endpush
@section('content')
<div class="sc-register">
    <section class="sc-register-hero">
        <div>
            <div class="sc-register-kicker">MOTOHUB / PARTNER NETWORK</div>
            <h1>შენი სერვისი.<br><span>ახალი შესაძლებლობები.</span></h1>
            <p>დაარეგისტრირე სერვისცენტრი MotoHub-ზე, შექმენი პროფესიონალური პროფილი და მართე მომხმარებლები, ჯავშნები და სამუშაო პროცესები ერთი სივრციდან.</p>
        </div>
        <aside class="sc-register-aside">
            <b>რეგისტრაციის შემდეგ მიიღებ</b>
            <ul>
                <li>სერვისცენტრის საჯარო ბიზნესპროფილს დამტკიცების შემდეგ</li>
                <li>მომსახურებებისა და ფასების მართვის შესაძლებლობას</li>
                <li>ონლაინ ჯავშნებსა და კლიენტებთან კომუნიკაციას</li>
            </ul>
        </aside>
    </section>
    <div class="sc-register-layout">
        <form method="POST" action="{{ route('my-service-center.store') }}" class="sc-register-form">
            @csrf
            <section class="sc-register-section">
                <div class="sc-register-section-head"><span class="sc-register-number">01</span><div><h2>სერვისცენტრის ინფორმაცია</h2><p>შეიყვანე მონაცემები, რომ მომხმარებლებმა მარტივად გიპოვონ.</p></div></div>
                <div class="sc-register-fields">
                    <div class="sc-register-field"><label for="name">სერვისცენტრის სახელი *</label><input id="name" name="name" value="{{ old('name') }}" required maxlength="160" placeholder="მაგ. MotoTech Garage"></div>
                    <div class="sc-register-field"><label for="phone">საკონტაქტო ტელეფონი *</label><input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required maxlength="40" placeholder="+995 5XX XX XX XX"></div>
                    <div class="sc-register-field"><label for="email">ელფოსტა <small>(არასავალდებულო)</small></label><input id="email" type="email" name="email" value="{{ old('email') }}" maxlength="255" placeholder="garage@example.com"></div>
                    <div class="sc-register-field"><label for="city">ქალაქი *</label><input id="city" name="city" value="{{ old('city') }}" required maxlength="120" placeholder="თბილისი"></div>
                    <div class="sc-register-field full"><label for="address">ზუსტი მისამართი *</label><input id="address" name="address" value="{{ old('address') }}" required maxlength="255" placeholder="ქუჩა, ნომერი, რაიონი"></div>
                </div>
            </section>
            <section class="sc-register-section">
                <div class="sc-register-section-head"><span class="sc-register-number">02</span><div><h2>აირჩიე პაკეტი</h2><p>დაიწყე უფასოდ და საჭიროებისამებრ გადადი უფრო მაღალ გეგმაზე.</p></div></div>
                <div class="sc-register-plans">
                    @foreach ($plans as $key => $plan)
                        <label class="sc-register-plan">
                            <input type="radio" name="subscription_plan" value="{{ $key }}" @checked(old('subscription_plan', 'start') === $key) required>
                            <span class="sc-register-plan-top">{{ strtoupper($key) }} @if($key === 'pro') · POPULAR @endif</span>
                            <strong class="sc-register-plan-price">{{ $plan['price'] === 0 ? 'უფასო' : $plan['price'] . ' ₾' }}<small>{{ $plan['price'] === 0 ? '' : ' / თვე' }}</small></strong>
                            <span class="sc-register-plan-desc">{{ $plan['description'] }}</span>
                            <ul>@foreach ($plan['features'] as $feature)<li>{{ $feature }}</li>@endforeach</ul>
                        </label>
                    @endforeach
                </div>
                <p class="sc-register-note">ფასიანი პაკეტები ამ ეტაპზე სატესტო რეჟიმშია. ონლაინ გადახდის ჩართვამდე გამოწერის აქტივაციას ადმინისტრატორი დაადასტურებს. START პაკეტი უფასოა.</p>
            </section>
            <section class="sc-register-section">
                <div class="sc-register-section-head"><span class="sc-register-number">03</span><div><h2>მოკლე აღწერა</h2><p>მოუყევი მომხმარებლებს შენს გამოცდილებასა და სერვისებზე.</p></div></div>
                <div class="sc-register-field"><label for="description">სერვისცენტრის შესახებ <small>(არასავალდებულო)</small></label><textarea id="description" name="description" maxlength="3000" placeholder="მოტოციკლების შეკეთება, დიაგნოსტიკა, ტექნიკური მომსახურება...">{{ old('description') }}</textarea></div>
            </section>
            <div class="sc-register-submit"><button class="btn" type="submit">რეგისტრაციის განაცხადის გაგზავნა <span aria-hidden="true">→</span></button><p>გაგზავნით ადასტურებ, რომ მითითებული საკონტაქტო ინფორმაცია სწორია.</p></div>
        </form>
        <aside class="sc-register-summary">
            <span class="eyebrow">MOTOHUB PARTNER</span><h3>შემდეგი ნაბიჯები</h3>
            <div class="sc-register-summary-row"><span>01 · განაცხადი</span><strong>შევსება</strong></div>
            <div class="sc-register-summary-row"><span>02 · შემოწმება</span><strong>ადმინისტრატორი</strong></div>
            <div class="sc-register-summary-row"><span>03 · პროფილი</span><strong>დამტკიცების შემდეგ</strong></div>
            <div class="sc-register-summary-total"><small>START პაკეტით დაწყება</small><strong>0 ₾</strong></div>
            <div class="sc-register-submit"><a class="btn btn-outline" href="{{ route('service-centers.index') }}">სერვისცენტრების ნახვა</a><p>რეგისტრაციის შემდეგ სამუშაო საათები საწყისად დაყენდება 09:00–18:00, კვირა — დასვენების დღე. შეცვლას კაბინეტიდან შეძლებ.</p></div>
        </aside>
    </div>
    <div class="sc-register-bottom"><span>უკვე გაქვს ანგარიში? ჯერ შედი სისტემაში.</span><a href="{{ route('login') }}">ავტორიზაცია</a></div>
</div>
@endsection
