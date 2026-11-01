@extends('layouts.motohub-features')

@section('content')
<main class="container" style="max-width:1100px;padding:42px 18px 70px;color:#f4f8ff">
    <section style="padding:30px;border:1px solid #294766;border-radius:22px;background:radial-gradient(ellipse at 90% 0,#1b5689 0,transparent 45%),linear-gradient(145deg,#14263d,#091421)">
        <span style="color:#82c4ff;font-size:11px;font-weight:900;letter-spacing:2px">MOTOHUB / REWARDS</span>
        <h1 style="font-size:clamp(30px,5vw,48px);font-weight:950;margin:12px 0">შენი აქტიურობა <span style="color:#62b4ff">ფასდება.</span></h1>
        <p style="color:#a8bdd4">დააგროვე ქულები, მოიწვიე მეგობრები და გამოიყენე ისინი MotoHub-ის მაღაზიასა და სერვისებში.</p>
        <div style="margin-top:22px;padding:20px;border:1px solid #ffffff22;border-radius:16px;background:#061321a8;max-width:360px">
            <small style="color:#9bb3ce">ხელმისაწვდომი ბალანსი</small>
            <div style="font-size:42px;font-weight:950;color:#6bb8ff">{{ number_format($balance) }} <small style="font-size:14px">ქულა</small></div>
            <small style="color:#9bb3ce">100 ქულა = 1 ₾ · კურსი შეიძლება შეიცვალოს</small>
        </div>
    </section>
    @if(session('success'))<p style="padding:12px;margin:16px 0;border:1px solid #327b64;border-radius:10px;color:#9ce6c9">{{ session('success') }}</p>@endif
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;margin-top:22px">
        <section style="padding:22px;border:1px solid #29415e;border-radius:16px;background:#0d1827">
            <span style="color:#75baff;font-size:11px;font-weight:900">INVITE & EARN</span><h2 style="margin:8px 0">მოიწვიე მეგობარი</h2>
            <p style="color:#9aafc7;font-size:14px">მეგობარი დარეგისტრირდება შენი კოდით — ორივე მიიღებთ ბონუსს.</p>
            <div style="padding:12px;border-radius:9px;background:#07111e;color:#7fc0ff;font-weight:900;word-break:break-all">{{ $code }}</div>
            <button type="button" onclick="navigator.clipboard.writeText('{{ url('/invite/'.$code) }}')" style="margin-top:12px;padding:11px 15px;border:0;border-radius:9px;background:#1677ff;color:white;font-weight:800">მოწვევის ბმულის კოპირება</button>
            <p style="color:#9aafc7;font-size:12px;margin-top:10px">მოწვეული მეგობრები: {{ $referrals->count() }}</p>
        </section>
        <section style="padding:22px;border:1px solid #29415e;border-radius:16px;background:#0d1827">
            <span style="color:#75baff;font-size:11px;font-weight:900">REDEEM POINTS</span><h2 style="margin:8px 0">გამოიყენე ქულები</h2>
            <form method="POST" action="{{ route('rewards.redeem') }}" style="display:grid;gap:10px">@csrf
                <label>სად გსურს გამოყენება<select name="reward_type" required style="padding:11px;border-radius:8px;background:#071321;color:white;border:1px solid #294766"><option value="store">MotoHub ოფიციალური მაღაზია</option><option value="service">სერვისის მომსახურება</option></select></label>
                <label>ქულების რაოდენობა<input type="number" name="points" min="500" max="{{ $balance }}" required style="padding:11px;border-radius:8px;background:#071321;color:white;border:1px solid #294766"></label>
                <label>დამატებითი ინფორმაცია<textarea name="notes" rows="2" maxlength="1000" style="padding:11px;border-radius:8px;background:#071321;color:white;border:1px solid #294766"></textarea></label>
                <button style="padding:12px;border:0;border-radius:9px;background:#1677ff;color:white;font-weight:900">ქულების გამოყენების მოთხოვნა</button>
            </form>
            <small style="display:block;margin-top:9px;color:#91a8c2">მოთხოვნას MotoHub-ის გუნდი დაადასტურებს. ქულები მოთხოვნის გაგზავნისას რეზერვდება.</small>
        </section>
    </div>
    <section style="margin-top:26px;padding:22px;border:1px solid #29415e;border-radius:16px;background:#0d1827">
        <h2>ჩემი ქულების გამოყენების მოთხოვნები</h2>
        @forelse($redemptions as $redemption)
            <div style="display:flex;justify-content:space-between;gap:14px;padding:13px 0;border-bottom:1px solid #ffffff12;flex-wrap:wrap">
                <div><strong>{{ number_format($redemption->points) }} ქულა · {{ $redemption->reward_type === 'store' ? 'მაღაზია' : 'სერვისი' }}</strong><small style="display:block;color:#91a8c2">{{ $redemption->created_at->format('d.m.Y H:i') }} · {{ $redemption->notes ?: 'დამატებითი ინფორმაცია არ არის' }}</small></div>
                <strong style="color:{{ $redemption->status === 'pending' ? '#78bdff' : ($redemption->status === 'approved' ? '#70d6aa' : '#ffbd82') }}">{{ ['pending'=>'მოლოდინში','approved'=>'დადასტურებული','rejected'=>'უარყოფილი'][$redemption->status] ?? $redemption->status }}</strong>
            </div>
        @empty
            <p style="color:#91a8c2">მოთხოვნა ჯერ არ გაგიგზავნია.</p>
        @endforelse
    </section>
    <section style="margin-top:26px;padding:22px;border:1px solid #29415e;border-radius:16px;background:#0d1827">
        <h2>როგორ დააგროვო ქულები</h2><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:12px;margin-top:14px">
            @foreach([['რეგისტრაცია','100'],['პირველი პოსტი','150'],['პირველი მოტოციკლის განცხადება','150'],['მეგობრის მოწვევა','100'],['მოწვეული მეგობრის პირველი პოსტი','150']] as [$label,$points])
            <div style="padding:14px;border:1px solid #29415e;border-radius:11px;background:#091421"><strong>{{ $label }}</strong><div style="margin-top:8px;color:#69b7ff;font-size:22px;font-weight:900">+{{ $points }} ქულა</div></div>
            @endforeach
        </div>
    </section>
    <section style="margin-top:26px;padding:22px;border:1px solid #29415e;border-radius:16px;background:#0d1827">
        <h2>ქულების ისტორია</h2>
        @forelse($transactions as $transaction)
            <div style="display:flex;justify-content:space-between;gap:12px;padding:12px 0;border-bottom:1px solid #ffffff12"><div><strong>{{ $transaction->description }}</strong><small style="display:block;color:#8fa7c1">{{ $transaction->created_at->format('d.m.Y H:i') }}</small></div><strong style="color:{{ $transaction->points >= 0 ? '#70d6aa' : '#ffbd82' }}">{{ $transaction->points > 0 ? '+' : '' }}{{ $transaction->points }}</strong></div>
        @empty <p style="color:#91a8c2">ქულების ოპერაციები ჯერ არ გაქვს.</p> @endforelse
    </section>
</main>
@endsection
