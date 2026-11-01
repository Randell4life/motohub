@extends('layouts.motohub-features')
@section('content')
<main class="container" style="max-width:1180px;padding:35px 18px 70px;color:#f4f8ff">
 <div style="padding:25px;border:1px solid #294766;border-radius:18px;background:linear-gradient(140deg,#142b45,#091421)">
  <span style="color:#78bdff;font-size:11px;font-weight:900;letter-spacing:2px">MOTOHUB / ADMIN</span><h1 style="margin:8px 0;font-size:34px">Rewards <span style="color:#70baff">Control.</span></h1><p style="color:#9bb2cc">ქულების ეკონომიკა, მოთხოვნები, მაღაზიის შეკვეთები და მომხმარებლების ჯილდოები.</p>
 </div>
 @if(session('success'))<p style="padding:12px;color:#9ce6c9">{{ session('success') }}</p>@endif
 @if($errors->any())<div style="padding:12px;margin:12px 0;border:1px solid #9b4545;border-radius:9px;color:#ffb5b5">{{ $errors->first() }}</div>@endif
 <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin:16px 0">
 @foreach([['გაცემული ქულები',$totals['issued']],['გამოყენებული ქულები',$totals['redeemed']],['მაღაზიაში დახარჯული',$totals['store_used']],['მოლოდინის მოთხოვნები',$totals['pending']]] as [$label,$value])
 <div style="padding:17px;border:1px solid #29415e;border-radius:12px;background:#0d1827"><small style="color:#91a8c2">{{ $label }}</small><div style="font-size:25px;font-weight:900;color:#72baff">{{ number_format($value) }}</div></div>
 @endforeach
 </div>
 <section style="padding:20px;border:1px solid #29415e;border-radius:14px;background:#0d1827;margin:15px 0"><h2>მომხმარებლისთვის ქულების დამატება / ჩამოკლება</h2>
  <p style="color:#91a8c2">მოძებნე მომხმარებელი სახელით, ელფოსტით ან ID-ით. ყველა ცვლილება ჩაიწერება ქულების ისტორიაში.</p>
  <form method="GET" action="{{ route('admin.rewards.index') }}" style="display:flex;gap:9px;flex-wrap:wrap;margin:14px 0">
   <input name="q" value="{{ request('q') }}" placeholder="სახელი, ელფოსტა ან მომხმარებლის ID" style="flex:1;min-width:220px;padding:11px;border:1px solid #294766;border-radius:8px;background:#081421;color:white">
   <button style="padding:11px 18px;border:0;border-radius:8px;background:#1677ff;color:white;font-weight:800">ძიება</button>
   @if(request('q'))<a href="{{ route('admin.rewards.index') }}" style="padding:11px;color:#8fc8ff">გასუფთავება</a>@endif
  </form>
  @forelse($users as $member)
   <article style="padding:15px 0;border-top:1px solid #ffffff17;display:grid;grid-template-columns:minmax(180px,1fr) minmax(280px,1.4fr);gap:14px;align-items:center">
    <div><strong>{{ $member->name }}</strong><small style="display:block;color:#91a8c2">{{ $member->email }} · ID #{{ $member->id }}</small><div style="margin-top:5px;color:#72baff;font-weight:900">{{ number_format((int) ($balances[$member->id] ?? 0)) }} ქულა</div></div>
    <form method="POST" action="{{ route('admin.rewards.adjust', $member) }}" style="display:grid;grid-template-columns:110px 100px minmax(120px,1fr) auto;gap:7px">
     @csrf
     <select name="operation" required style="padding:9px;border:1px solid #294766;border-radius:7px;background:#081421;color:white"><option value="add">დამატება +</option><option value="subtract">ჩამოკლება −</option></select>
     <input type="number" name="points" min="1" max="1000000" required placeholder="ქულა" style="width:100%;padding:9px;border:1px solid #294766;border-radius:7px;background:#081421;color:white">
     <input name="reason" required minlength="3" maxlength="500" placeholder="მიზეზი (სავალდებულო)" style="min-width:0;padding:9px;border:1px solid #294766;border-radius:7px;background:#081421;color:white">
     <button style="padding:9px 12px;border:0;border-radius:7px;background:#1677ff;color:white;font-weight:800">შენახვა</button>
    </form>
   </article>
  @empty
   <p style="color:#91a8c2">მომხმარებლები ვერ მოიძებნა.</p>
  @endforelse
  <div style="margin-top:12px">{{ $users->links() }}</div>
 </section>
 <section style="padding:20px;border:1px solid #29415e;border-radius:14px;background:#0d1827;margin:15px 0"><h2>ქულების გამოყენების მოთხოვნები</h2>
  <p style="color:#91a8c2">აქ ჩანს Rewards გვერდიდან გამოგზავნილი ქულების გამოყენების მოთხოვნები. ჯერ ახალი / მოლოდინის მოთხოვნებია ნაჩვენები.</p>
  <form method="GET" action="{{ route('admin.rewards.index') }}" style="display:flex;gap:8px;align-items:center;margin:12px 0;flex-wrap:wrap">
   @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
   <select name="status" style="padding:9px;border:1px solid #294766;border-radius:8px;background:#081421;color:white"><option value="">ყველა სტატუსი</option><option value="pending" @selected(request('status')==='pending')>მოლოდინში</option><option value="approved" @selected(request('status')==='approved')>დადასტურებული</option><option value="rejected" @selected(request('status')==='rejected')>უარყოფილი</option></select>
   <button style="padding:9px 14px;border:0;border-radius:8px;background:#1677ff;color:white">ფილტრი</button>
  </form>
  @forelse($redemptions as $item)
   <article style="padding:15px 0;border-bottom:1px solid #ffffff17;display:grid;grid-template-columns:minmax(0,1fr) auto;gap:12px">
    <div><strong>{{ $item->user?->name }} · {{ number_format($item->points) }} ქულა</strong><p style="margin:5px 0;color:#91a8c2">{{ $item->user?->email }} · {{ $item->reward_type === 'store' ? 'მაღაზიის ქულების მოთხოვნა' : 'სერვისის ქულების მოთხოვნა' }} · {{ number_format((float) $item->value_gel,2) }} ₾</p><small style="color:#829bb5">{{ $item->notes ?: 'დამატებითი კომენტარი არ არის' }} · {{ $item->created_at->format('d.m.Y H:i') }}</small><p style="color:#9bb4cf">სტატუსი: <strong>{{ ['pending'=>'მოლოდინში','approved'=>'დადასტურებული','rejected'=>'უარყოფილი'][$item->status] ?? $item->status }}</strong> {{ $item->admin_note }}</p></div>
    @if($item->status === 'pending')
     <form method="POST" action="{{ route('admin.rewards.update',$item) }}" style="display:grid;gap:7px;min-width:150px;align-content:start">@csrf @method('PATCH')<input name="admin_note" placeholder="ადმინის შენიშვნა" style="padding:8px;background:#081421;color:white;border:1px solid #294766;border-radius:7px"><button name="status" value="approved" style="padding:9px;background:#14543e;color:#b5f2d8;border:0;border-radius:7px">დადასტურება</button><button name="status" value="rejected" style="padding:9px;background:#49351e;color:#f3d2a2;border:0;border-radius:7px">უარყოფა / დაბრუნება</button></form>
    @endif
   </article>
  @empty <p style="color:#91a8c2">ქულების გამოყენების მოთხოვნები ჯერ არ არის. მაღაზიის შეკვეთები ქვემოთ ცალკე გამოჩნდება.</p> @endforelse
  <div style="margin-top:15px">{{ $redemptions->links() }}</div>
 </section>
 <section style="padding:20px;border:1px solid #29415e;border-radius:14px;background:#0d1827;margin:15px 0"><h2>MotoHub მაღაზიის შეკვეთები ქულებით</h2>
  <p style="color:#91a8c2">ნაწილების ყიდვისას გამოყენებული ქულები ცალკე შეკვეთებში აისახება და არა Rewards-ის მოთხოვნების ცხრილში.</p>
  @forelse($storeOrders as $order)
   <article style="padding:14px 0;border-bottom:1px solid #ffffff17;display:grid;grid-template-columns:1fr auto;gap:12px">
    <div><strong>{{ $order->order_number }} · {{ $order->user?->name }}</strong><small style="display:block;color:#91a8c2">{{ $order->user?->email }} · {{ $order->created_at->format('d.m.Y H:i') }}</small><p style="margin:6px 0;color:#b6c9df">{{ $order->items->pluck('title')->join(', ') }}</p><small style="color:#91a8c2">შეკვეთის სტატუსი: {{ $order->status }} · ჯამი: {{ number_format((float) $order->total,2) }} ₾</small></div>
    <div style="text-align:right"><strong style="color:#72baff;font-size:20px">−{{ number_format($order->points_used) }} ქულა</strong><small style="display:block;color:#9bb2cc">ფასდაკლება: {{ number_format((float) $order->points_discount,2) }} ₾</small></div>
   </article>
  @empty <p style="color:#91a8c2">ქულებით შესრულებული მაღაზიის შეკვეთები ჯერ არ არის.</p> @endforelse
  <div style="margin-top:15px">{{ $storeOrders->links() }}</div>
 </section>
 <section style="padding:20px;border:1px solid #29415e;border-radius:14px;background:#0d1827"><h2>ქულების წესები</h2>
 <form method="POST" action="{{ route('admin.rewards.settings') }}" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px">@csrf
 @foreach(['points_per_gel'=>'ქულა 1 ₾-ზე','minimum_redemption_points'=>'მინ. გამოყენება','signup_points'=>'რეგისტრაცია','first_post_points'=>'პირველი პოსტი','first_listing_points'=>'პირველი მოტოს განცხადება','referral_signup_points'=>'მეგობრის რეგისტრაცია','referral_first_post_points'=>'მეგობრის პირველი პოსტი','daily_checkin_points'=>'ყოველდღიური შესვლა','weekly_streak_bonus_points'=>'7-დღიანი სერიის ბონუსი','silver_tier_points'=>'Silver-ის ზღვარი','gold_tier_points'=>'Gold-ის ზღვარი','platinum_tier_points'=>'Platinum-ის ზღვარი'] as $key=>$label)
 <label style="display:grid;gap:6px;color:#a9bfd6;font-size:13px">{{ $label }}<input type="number" min="0" max="1000000" name="settings[{{ $key }}]" value="{{ $settings[$key] ?? ( ['silver_tier_points' => 1000, 'gold_tier_points' => 5000, 'platinum_tier_points' => 15000][$key] ?? 0 ) }}" style="padding:10px;border:1px solid #294766;border-radius:8px;background:#081421;color:white"></label>
 @endforeach
 <button style="padding:12px;border:0;border-radius:8px;background:#1677ff;color:white;font-weight:800">წესების შენახვა</button></form></section>
</main>
@endsection
