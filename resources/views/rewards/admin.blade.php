@extends('layouts.motohub-features')
@section('content')
<main class="container" style="max-width:1180px;padding:35px 18px 70px;color:#f4f8ff">
 <div style="padding:25px;border:1px solid #294766;border-radius:18px;background:linear-gradient(140deg,#142b45,#091421)">
  <span style="color:#78bdff;font-size:11px;font-weight:900;letter-spacing:2px">MOTOHUB / ADMIN</span><h1 style="margin:8px 0;font-size:34px">Rewards <span style="color:#70baff">Control.</span></h1><p style="color:#9bb2cc">ქულების ეკონომიკა, მოთხოვნები და მომხმარებლების ჯილდოები.</p>
 </div>
 @if(session('success'))<p style="padding:12px;color:#9ce6c9">{{ session('success') }}</p>@endif
 <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin:16px 0">
 @foreach([['გაცემული ქულები',$totals['issued']],['გამოყენებული ქულები',$totals['redeemed']],['მოლოდინის მოთხოვნები',$totals['pending']]] as [$label,$value])
 <div style="padding:17px;border:1px solid #29415e;border-radius:12px;background:#0d1827"><small style="color:#91a8c2">{{ $label }}</small><div style="font-size:25px;font-weight:900;color:#72baff">{{ number_format($value) }}</div></div>
 @endforeach
 </div>
 <section style="padding:20px;border:1px solid #29415e;border-radius:14px;background:#0d1827;margin:15px 0"><h2>ქულების წესები</h2>
 <form method="POST" action="{{ route('admin.rewards.settings') }}" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px">@csrf
 @foreach(['points_per_gel'=>'ქულა 1 ₾-ზე','minimum_redemption_points'=>'მინ. გამოყენება','signup_points'=>'რეგისტრაცია','first_post_points'=>'პირველი პოსტი','first_listing_points'=>'პირველი მოტოს განცხადება','referral_signup_points'=>'მეგობრის რეგისტრაცია','referral_first_post_points'=>'მეგობრის პირველი პოსტი'] as $key=>$label)
 <label style="display:grid;gap:6px;color:#a9bfd6;font-size:13px">{{ $label }}<input type="number" min="0" max="1000000" name="settings[{{ $key }}]" value="{{ $settings[$key] ?? 0 }}" style="padding:10px;border:1px solid #294766;border-radius:8px;background:#081421;color:white"></label>
 @endforeach
 <button style="padding:12px;border:0;border-radius:8px;background:#1677ff;color:white;font-weight:800">წესების შენახვა</button></form></section>
 <section style="padding:20px;border:1px solid #29415e;border-radius:14px;background:#0d1827"><h2>ქულების გამოყენების მოთხოვნები</h2>
 @forelse($redemptions as $item)
 <article style="padding:15px 0;border-bottom:1px solid #ffffff17;display:grid;grid-template-columns:1fr auto;gap:12px"><div><strong>{{ $item->user?->name }} · {{ $item->points }} ქულა</strong><p style="margin:5px 0;color:#91a8c2">{{ $item->user?->email }} · {{ $item->reward_type === 'store' ? 'მაღაზია' : 'სერვისი' }} · {{ number_format($item->value_gel,2) }} ₾</p><small style="color:#829bb5">{{ $item->notes }} · {{ $item->created_at->format('d.m.Y H:i') }}</small><p style="color:#9bb4cf">სტატუსი: {{ $item->status }} {{ $item->admin_note }}</p></div>
 @if($item->status === 'pending')<form method="POST" action="{{ route('admin.rewards.update',$item) }}" style="display:grid;gap:7px;min-width:130px">@csrf @method('PATCH')<input name="admin_note" placeholder="ადმინის შენიშვნა" style="padding:8px;background:#081421;color:white;border:1px solid #294766;border-radius:7px"><button name="status" value="approved" style="padding:8px;background:#14543e;color:#b5f2d8;border:0;border-radius:7px">დადასტურება</button><button name="status" value="rejected" style="padding:8px;background:#49351e;color:#f3d2a2;border:0;border-radius:7px">უარყოფა / დაბრუნება</button></form>@endif</article>
 @empty <p style="color:#91a8c2">მოთხოვნები ჯერ არ არის.</p> @endforelse
 <div style="margin-top:15px">{{ $redemptions->links() }}</div></section>
</main>
@endsection
