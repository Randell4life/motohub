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
    <section style="margin-top:16px;padding:22px;border:1px solid #31577b;border-radius:18px;background:radial-gradient(ellipse at 95% 0,#1b568955,transparent 45%),linear-gradient(135deg,#142b43,#091421)">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap"><div><span style="color:#80c5ff;font-size:11px;font-weight:900;letter-spacing:2px">MOTOHUB CHAMPIONS</span><h2 style="margin:7px 0">შენი მოგებული პრიზები</h2><p style="margin:0;color:#9bb2cc">ლიდერბორდის გამარჯვებულებისთვის მინიჭებული ჯილდოები.</p></div><span style="font-size:32px">🏆</span></div>
        @forelse($prizes as $prize)
            <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;padding:13px 0;border-top:1px solid #ffffff18;margin-top:10px;flex-wrap:wrap"><div><strong>🏅 {{ $prize->title }}</strong><small style="display:block;color:#9bb2cc">{{ $prize->period_type === 'week' ? 'კვირის' : 'თვის' }} რეიტინგი · #{{ $prize->rank }} ადგილი · {{ $prize->period_start->format('d.m.Y') }}</small><small style="color:#91a8c2">{{ $prize->description }}</small></div><strong style="color:#80c5ff">{{ $prize->points_awarded > 0 ? '+' . number_format($prize->points_awarded) . ' ქულა' : 'პრიზი მიღებულია' }}</strong></div>
        @empty <p style="margin-top:15px;color:#9bb2cc">ჯერ პრიზი არ მოგიგია — იბრძოლე ლიდერბორდის პირველი ადგილისთვის!</p> @endforelse
    </section>
    <section style="margin-top:16px;padding:22px;border:1px solid #31577b;border-radius:16px;background:linear-gradient(120deg,#132b43,#0a1523)">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap"><div><span style="color:#78bdff;font-size:11px;font-weight:900;letter-spacing:1.5px">MOTOHUB MEMBERSHIP</span><h2 style="margin:7px 0">{{ $membershipTier['name'] }} <span style="color:#8dcaff">/ {{ $membershipTier['name_ka'] }}</span></h2><p style="margin:0;color:#a8bdd4">მთლიანობაში დაგროვებული: {{ number_format($membershipTier['lifetime_earned']) }} ქულა</p></div><strong style="font-size:24px;color:#7dbfff">{{ $membershipTier['progress_percent'] }}%</strong></div>
        <div style="height:9px;margin-top:18px;border-radius:99px;background:#263c52;overflow:hidden"><div style="height:100%;width:{{ $membershipTier['progress_percent'] }}%;background:linear-gradient(90deg,#1677ff,#66c4ff);border-radius:99px"></div></div>
        <p style="margin:10px 0 0;color:#a8bdd4">{{ $membershipTier['next_tier'] ? number_format($membershipTier['points_to_next']) . ' ქულა გაკლია შემდეგ დონემდე: ' . $membershipTier['next_tier']['name'] : 'გილოცავ! უმაღლეს Platinum დონეს მიაღწიე.' }}</p>
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:16px">@foreach($membershipTier['tiers'] as $tier)<span style="padding:7px 11px;border-radius:99px;border:1px solid {{ $tier['key'] === $membershipTier['key'] ? '#58aaff' : '#30465c' }};background:{{ $tier['key'] === $membershipTier['key'] ? '#173c60' : '#0a1725' }};color:{{ $tier['key'] === $membershipTier['key'] ? '#a9d7ff' : '#91a8c2' }};font-size:12px;font-weight:800">{{ $tier['name'] }} · {{ number_format($tier['threshold']) }}</span>@endforeach</div>
    </section>
    <section style="margin-top:16px;padding:20px 22px;border:1px solid #294766;border-radius:16px;background:linear-gradient(120deg,#10243a,#0a1523);display:flex;justify-content:space-between;gap:18px;align-items:center;flex-wrap:wrap">
        <div><span style="color:#78bdff;font-size:11px;font-weight:900;letter-spacing:1.5px">DAILY RIDER STREAK</span><h2 style="margin:7px 0">ყოველდღიური შესვლა</h2><p style="margin:0;color:#9bb2cc">დღეში ერთხელ მიიღე {{ $dailyCheckIn['daily_points'] }} ქულა. ზედიზედ 7 დღე — დამატებითი {{ $dailyCheckIn['weekly_bonus_points'] }} ქულა.</p></div>
        <div style="text-align:right"><strong style="display:block;color:#70baff;font-size:30px">{{ $dailyCheckIn['streak'] }} დღე</strong><small style="color:#9bb2cc">მიმდინარე სერია</small><strong style="display:block;margin-top:5px;color:#70d6aa">{{ $dailyCheckIn['already_checked_in'] ? 'დღევანდელი ბონუსი მიღებულია' : '+' . number_format($dailyCheckIn['points_awarded']) . ' ქულა დაემატა დღეს' }}</strong></div>
    </section>
    <section style="margin-top:16px;padding:22px;border:1px solid #31577b;border-radius:16px;background:linear-gradient(120deg,#132b43,#0a1523)">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap"><div><span style="color:#78bdff;font-size:11px;font-weight:900;letter-spacing:1.5px">RIDER ACHIEVEMENTS</span><h2 style="margin:7px 0">მისიები და მიღწევები</h2><p style="margin:0;color:#a8bdd4">შეასრულე დავალებები, გახსენი ბეჯები და მიიღე დამატებითი ქულები.</p></div><strong style="color:#7dbfff;font-size:22px">{{ $achievements['unlocked_count'] }} / {{ $achievements['total_count'] }}</strong></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:10px;margin-top:16px">
        @foreach($achievements['badges'] as $badge)
            <article style="padding:14px;border:1px solid {{ $badge['unlocked'] ? '#3976a8' : '#29415e' }};border-radius:12px;background:{{ $badge['unlocked'] ? '#102b43' : '#091421' }};opacity:{{ $badge['unlocked'] ? '1' : '.82' }}">
                <div style="font-size:26px">{{ $badge['icon'] }}</div><strong style="display:block;margin:7px 0">{{ $badge['title'] }}</strong><small style="display:block;color:#9bb2cc;min-height:34px">{{ $badge['description'] }}</small>
                <div style="height:6px;margin:11px 0 7px;border-radius:99px;background:#263c52;overflow:hidden"><div style="height:100%;width:{{ $badge['progress_percent'] }}%;background:linear-gradient(90deg,#1677ff,#66c4ff)"></div></div>
                <div style="display:flex;justify-content:space-between;gap:8px;color:#86c5ff;font-size:12px;font-weight:800"><span>{{ $badge['progress'] }} / {{ $badge['target'] }}</span><span>{{ $badge['unlocked'] ? 'მიღწეულია' : '+' . $badge['points'] . ' ქულა' }}</span></div>
            </article>
        @endforeach
        </div>
    </section>
    <section style="margin-top:16px;padding:22px;border:1px solid #31577b;border-radius:16px;background:linear-gradient(120deg,#132b43,#0a1523)">
        <span style="color:#78bdff;font-size:11px;font-weight:900;letter-spacing:1.5px">MOTOHUB LEADERBOARD</span>
        <h2 style="margin:7px 0 16px">მოტო საზოგადოების რეიტინგი</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px">
            @foreach([['label'=>'ამ კვირის ლიდერები','data'=>$weeklyLeaderboard],['label'=>'ამ თვის ლიდერები','data'=>$monthlyLeaderboard]] as $board)
                <div style="padding:16px;border:1px solid #29415e;border-radius:12px;background:#091421">
                    <h3 style="margin:0 0 12px">{{ $board['label'] }}</h3>
                    @forelse($board['data']['entries'] as $entry)
                        <div style="display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid #ffffff12">
                            <strong style="width:28px;color:{{ $entry['rank'] <= 3 ? '#80c5ff' : '#91a8c2' }}">#{{ $entry['rank'] }}</strong>
                            <span style="flex:1">{{ $entry['name'] }}</span>
                            <b style="color:#80c5ff">{{ number_format($entry['points']) }} ქ.</b>
                        </div>
                    @empty
                        <p style="color:#9bb2cc">ამ პერიოდში ქულები ჯერ არ დაგროვებულა.</p>
                    @endforelse
                    @if($board['data']['current_user'])
                        <p style="margin:12px 0 0;color:#9bb2cc">შენი ადგილი: <strong style="color:#80c5ff">#{{ $board['data']['current_user']['rank'] }}</strong> · {{ number_format($board['data']['current_user']['points']) }} ქულა</p>
                    @else
                        <p style="margin:12px 0 0;color:#9bb2cc">შენი რეიტინგი გამოჩნდება პირველი ქულების მიღების შემდეგ.</p>
                    @endif
                </div>
            @endforeach
        </div>
        <small style="display:block;margin-top:12px;color:#91a8c2">რეიტინგი ითვლის მხოლოდ შესაბამის პერიოდში მიღებულ დადებით ქულებს.</small>
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
