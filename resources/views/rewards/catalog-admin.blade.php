@extends('layouts.motohub-features')
@section('content')
<main class="container" style="max-width:1180px;padding:35px 18px 70px;color:#f4f8ff">
 <div style="padding:24px;border:1px solid #294766;border-radius:18px;background:linear-gradient(140deg,#142b45,#091421)"><small style="color:#78bdff;letter-spacing:2px;font-weight:900">MOTOHUB / REWARDS ADMIN</small><h1 style="font-size:32px;margin:8px 0">საჩუქრების <span style="color:#70baff">მართვა.</span></h1><p style="color:#9bb2cc">კატალოგი, მარაგი და მომხმარებლების შეკვეთები.</p><a href="{{ route('admin.rewards.index') }}" style="color:#9dccff">← Rewards Admin</a></div>
 @if(session('success'))<p style="padding:12px;color:#9ce6c9">{{ session('success') }}</p>@endif
 @if($errors->any())<p style="padding:12px;color:#ffb5b5">{{ $errors->first() }}</p>@endif
 <section style="padding:20px;margin-top:16px;border:1px solid #294766;border-radius:15px;background:#0d1827"><h2>ახალი საჩუქრის დამატება</h2>
 <form method="POST" action="{{ route('admin.rewards.catalog.store') }}" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px">@csrf
  <input name="title" required maxlength="160" placeholder="საჩუქრის სახელი" style="padding:11px;background:#081421;color:white;border:1px solid #31577b;border-radius:8px">
  <input name="points_cost" type="number" min="1" required placeholder="ქულის ფასი" style="padding:11px;background:#081421;color:white;border:1px solid #31577b;border-radius:8px">
  <input name="stock" type="number" min="0" required placeholder="მარაგი" style="padding:11px;background:#081421;color:white;border:1px solid #31577b;border-radius:8px">
  <input name="image_url" type="url" placeholder="სურათის URL (არასავალდებულო)" style="padding:11px;background:#081421;color:white;border:1px solid #31577b;border-radius:8px">
  <input name="sort_order" type="number" min="0" value="0" placeholder="რიგითობა" style="padding:11px;background:#081421;color:white;border:1px solid #31577b;border-radius:8px">
  <input name="description" maxlength="3000" placeholder="აღწერა" style="padding:11px;background:#081421;color:white;border:1px solid #31577b;border-radius:8px">
  <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" name="is_active" value="1" checked> აქტიურია</label>
  <button style="padding:12px;border:0;border-radius:8px;background:#1677ff;color:white;font-weight:900">დამატება</button>
 </form></section>
 <section style="padding:20px;margin-top:16px;border:1px solid #294766;border-radius:15px;background:#0d1827"><h2>კატალოგი · {{ $items->count() }} პროდუქტი</h2>
 @forelse($items as $item)
  <article style="padding:14px 0;border-top:1px solid #ffffff17;display:grid;grid-template-columns:minmax(0,1fr) minmax(300px,1.4fr);gap:14px;align-items:center">
   <div><strong>{{ $item->title }}</strong><small style="display:block;color:#91a8c2">{{ number_format($item->points_cost) }} ქულა · მარაგი {{ $item->stock }} · შეკვეთები {{ $item->orders_count }} · {{ $item->is_active?'აქტიური':'დამალული' }}</small></div>
   <form method="POST" action="{{ route('admin.rewards.catalog.update',$item) }}" style="display:grid;grid-template-columns:1fr 90px 80px auto;gap:6px">@csrf @method('PATCH')
    <input name="title" value="{{ $item->title }}" required style="min-width:0;padding:8px;background:#081421;color:white;border:1px solid #31577b;border-radius:7px">
    <input name="points_cost" type="number" min="1" value="{{ $item->points_cost }}" required style="width:100%;padding:8px;background:#081421;color:white;border:1px solid #31577b;border-radius:7px">
    <input name="stock" type="number" min="0" value="{{ $item->stock }}" required style="width:100%;padding:8px;background:#081421;color:white;border:1px solid #31577b;border-radius:7px">
    <input type="hidden" name="description" value="{{ $item->description }}"><input type="hidden" name="image_url" value="{{ $item->image_url }}"><input type="hidden" name="sort_order" value="{{ $item->sort_order }}"><select name="is_active" style="padding:8px;background:#081421;color:white;border:1px solid #31577b;border-radius:7px"><option value="1" @selected($item->is_active)>აქტიური</option><option value="0" @selected(!$item->is_active)>დამალული</option></select>
    <button style="padding:8px 11px;border:0;border-radius:7px;background:#1677ff;color:white">შენახვა</button>
   </form>
   <form method="POST" action="{{ route('admin.rewards.catalog.delete',$item) }}" style="margin-top:7px;text-align:right">@csrf @method('DELETE')<button style="padding:7px 11px;border:1px solid #8a4c4c;border-radius:7px;background:#351d26;color:#ffc4c4">წაშლა</button></form>
  </article>
 @empty <p style="color:#91a8c2">კატალოგი ცარიელია.</p> @endforelse
 </section>
 <section style="padding:20px;margin-top:16px;border:1px solid #294766;border-radius:15px;background:#0d1827"><h2>მომხმარებლების შეკვეთები</h2>
 @forelse($orders as $order)
  <article style="padding:14px 0;border-top:1px solid #ffffff17;display:grid;grid-template-columns:minmax(0,1fr) auto;gap:12px">
   <div><strong>#{{ $order->id }} · {{ $order->item_title }}</strong><small style="display:block;color:#91a8c2">{{ $order->user?->name }} · {{ $order->user?->email }} · {{ number_format($order->points_spent) }} ქულა · {{ $order->created_at->format('d.m.Y H:i') }}</small><small style="display:block;color:#b6c9df">მიმღები: {{ $order->customer_name }} · {{ $order->phone }} · {{ $order->address }}</small>@if($order->admin_note)<small style="color:#91a8c2">{{ $order->admin_note }}</small>@endif</div>
   <form method="POST" action="{{ route('admin.rewards.catalog.orders.update',$order) }}" style="display:grid;gap:6px;min-width:160px">@csrf @method('PATCH')
    @php
        $availableStatuses = match($order->status) {
            'pending' => ['approved'=>'დადასტურება','processing'=>'მზადდება','rejected'=>'უარყოფა / ქულების დაბრუნება','cancelled'=>'გაუქმება / დაბრუნება'],
            'approved' => ['processing'=>'მზადდება','shipped'=>'გამოგზავნილია','fulfilled'=>'გაცემულია','rejected'=>'უარყოფა / ქულების დაბრუნება','cancelled'=>'გაუქმება / დაბრუნება'],
            'processing' => ['shipped'=>'გამოგზავნილია','fulfilled'=>'გაცემულია','rejected'=>'უარყოფა / ქულების დაბრუნება','cancelled'=>'გაუქმება / დაბრუნება'],
            'shipped' => ['fulfilled'=>'გაცემულია'],
            default => [],
        };
    @endphp
    <select name="status" style="padding:8px;background:#081421;color:white;border:1px solid #31577b;border-radius:7px">
        @if(!$availableStatuses)<option value="{{ $order->status }}" selected>დასრულებული / უცვლელი</option>@endif
        @foreach($availableStatuses as $key=>$label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
    </select>
    <input name="admin_note" value="{{ $order->admin_note }}" placeholder="შენიშვნა" style="padding:8px;background:#081421;color:white;border:1px solid #31577b;border-radius:7px">
    <button style="padding:9px;border:0;border-radius:7px;background:#1677ff;color:white">სტატუსის განახლება</button>
   </form>
  </article>
 @empty <p style="color:#91a8c2">შეკვეთები ჯერ არ არის.</p> @endforelse
 <div style="margin-top:14px">{{ $orders->links() }}</div>
 </section>
</main>
@endsection
