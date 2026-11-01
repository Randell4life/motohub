@extends('layouts.motohub-features')
@section('content')
<main class="container" style="max-width:1120px;padding:38px 18px 70px;color:#f4f8ff">
 <section style="padding:25px;border:1px solid #31577b;border-radius:18px;background:radial-gradient(ellipse at 90% 0,#1b568955,transparent 45%),linear-gradient(135deg,#142b43,#091421)">
  <small style="color:#80c5ff;font-weight:900;letter-spacing:2px">MOTOHUB / REWARDS SHOP</small><h1 style="font-size:clamp(30px,5vw,42px);margin:9px 0">ქულების <span style="color:#72baff">საჩუქრების მაღაზია.</span></h1>
  <p style="color:#a8bdd4">გაცვალე დაგროვებული ქულები სპეციალურ საჩუქრებსა და პარტნიორულ შეთავაზებებში.</p>
  <strong style="font-size:26px;color:#78bdff">{{ number_format($balance) }} ქულა</strong>
  <a href="{{ route('rewards.index') }}" style="display:inline-block;margin-left:14px;color:#9dccff">← Rewards-ზე დაბრუნება</a>
 </section>
 @if(session('success'))<p style="padding:12px;color:#9ce6c9">{{ session('success') }}</p>@endif
 @if($errors->any())<p style="padding:12px;color:#ffb5b5">{{ $errors->first() }}</p>@endif
 <section style="display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:14px;margin-top:20px">
 @forelse($items as $item)
  <article style="overflow:hidden;border:1px solid #294766;border-radius:16px;background:#0d1827">
   @if($item->image_url)<img src="{{ $item->image_url }}" alt="{{ $item->title }}" style="width:100%;height:175px;object-fit:cover">@else<div style="height:125px;display:grid;place-items:center;background:linear-gradient(135deg,#142b43,#081421);font-size:42px">🎁</div>@endif
   <div style="padding:16px"><h2 style="font-size:19px;margin:0 0 8px">{{ $item->title }}</h2><p style="min-height:42px;color:#9bb2cc;font-size:13px">{{ $item->description }}</p>
    <div style="display:flex;justify-content:space-between;align-items:center;margin:14px 0"><strong style="color:#7dc1ff;font-size:21px">{{ number_format($item->points_cost) }} ქ.</strong><small style="color:#91a8c2">მარაგი: {{ $item->stock }}</small></div>
    <form method="POST" action="{{ route('rewards.catalog.redeem',$item) }}" style="display:grid;gap:7px">@csrf
     <input name="customer_name" required maxlength="160" value="{{ auth()->user()->name }}" placeholder="მიმღების სახელი" style="width:100%;box-sizing:border-box;padding:9px;background:#081421;color:white;border:1px solid #31577b;border-radius:7px">
     <input name="phone" required maxlength="40" value="{{ auth()->user()->phone }}" placeholder="ტელეფონი" style="width:100%;box-sizing:border-box;padding:9px;background:#081421;color:white;border:1px solid #31577b;border-radius:7px">
     <input name="address" required maxlength="1000" placeholder="მიწოდების მისამართი" style="width:100%;box-sizing:border-box;padding:9px;background:#081421;color:white;border:1px solid #31577b;border-radius:7px">
     <button {{ $balance < $item->points_cost ? 'disabled' : '' }} style="width:100%;padding:12px;border:0;border-radius:9px;background:{{ $balance < $item->points_cost ? '#33465a' : '#1677ff' }};color:white;font-weight:900;cursor:pointer">ქულებით მიღება</button></form>
   </div>
  </article>
 @empty <p style="color:#9bb2cc">საჩუქრები ჯერ არ დამატებულა. მალე დაემატება ახალი შეთავაზებები.</p> @endforelse
 </section>
 <section style="margin-top:25px;padding:20px;border:1px solid #294766;border-radius:15px;background:#0d1827"><h2>ჩემი საჩუქრების შეკვეთები</h2>
 @forelse($orders as $order)
  <div style="display:flex;justify-content:space-between;gap:12px;padding:12px 0;border-top:1px solid #ffffff17;flex-wrap:wrap"><span><strong>{{ $order->item_title }}</strong><small style="display:block;color:#91a8c2">#{{ $order->id }} · {{ $order->created_at->format('d.m.Y H:i') }} · {{ number_format($order->points_spent) }} ქულა</small>@if($order->admin_note)<small style="color:#91a8c2">{{ $order->admin_note }}</small>@endif</span><b style="color:#80c5ff">{{ ['pending'=>'მოლოდინში','approved'=>'დადასტურებული','processing'=>'მზადდება','shipped'=>'გამოგზავნილია','fulfilled'=>'გაცემულია','rejected'=>'უარყოფილი','cancelled'=>'გაუქმებული'][$order->status] ?? $order->status }}</b></div>
 @empty <p style="color:#91a8c2">შეკვეთები ჯერ არ გაქვს.</p> @endforelse
 </section>
 <p style="margin-top:14px;color:#829bb5;font-size:12px">საჩუქრის მოთხოვნისას ქულები რეზერვდება. უარყოფის ან გაუქმების შემთხვევაში ქულები ბალანსზე დაგიბრუნდება.</p>
</main>
@endsection
