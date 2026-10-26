@extends('layouts.service-centers')
@section('title', 'MotoHub | სახელოსნოს რეგისტრაცია')
@section('content')
<section class="page-head"><div class="eyebrow">Join MotoHub</div><h1>დაარეგისტრირე შენი <span style="color:var(--blue2)">სახელოსნო</span></h1><p class="muted" style="max-width:720px">შექმენი ბიზნესპროფილი, დაამატე მომსახურებები და მიიღე ონლაინ ჯავშნები. რეგისტრაციის შემდეგ შეძლებ სამუშაო საათებისა და ფასების მართვას.</p></section>
<section class="panel" style="max-width:850px;margin:0 auto 45px">
    <form method="POST" action="{{ route('my-service-center.store') }}" class="form-grid">
        @csrf
        <div class="field"><label for="name">სახელოსნოს სახელი *</label><input id="name" name="name" value="{{ old('name') }}" required maxlength="160" placeholder="მაგ. MotoTech Garage"></div>
        <div class="field"><label for="phone">საკონტაქტო ტელეფონი *</label><input id="phone" name="phone" value="{{ old('phone') }}" required maxlength="40" placeholder="5XX XX XX XX"></div>
        <div class="field"><label for="email">ელფოსტა</label><input id="email" type="email" name="email" value="{{ old('email') }}" maxlength="255" placeholder="garage@example.com"></div>
        <div class="field"><label for="city">ქალაქი *</label><input id="city" name="city" value="{{ old('city') }}" required maxlength="120" placeholder="თბილისი"></div>
        <div class="field span-two"><label for="address">ზუსტი მისამართი *</label><input id="address" name="address" value="{{ old('address') }}" required maxlength="255" placeholder="ქუჩა, ნომერი, რაიონი"></div>
        <div class="field span-two"><label for="description">სახელოსნოს შესახებ</label><textarea id="description" name="description" maxlength="3000" placeholder="მოკლედ აღწერე შენი მომსახურებები და გამოცდილება">{{ old('description') }}</textarea></div>
        <div class="span-two" style="display:flex;gap:12px;align-items:center;justify-content:flex-end;flex-wrap:wrap"><a class="btn btn-outline" href="{{ route('service-centers.index') }}">გაუქმება</a><button class="btn" type="submit">სახელოსნოს შექმნა →</button></div>
    </form>
    <p class="muted small" style="margin:18px 0 0">რეგისტრაციისას სამუშაო საათები დროებით დაყენდება 09:00–18:00, კვირა — დასვენების დღე. მათი შეცვლა მოგვიანებით შეგეძლება.</p>
</section>
@endsection
