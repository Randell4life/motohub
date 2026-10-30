@extends('layouts.motohub-features')
@section('title','მოტოკლუბები | MotoHub')
@section('content')
<div class="eyebrow">MOTO COMMUNITY / CLUBS</div><h1 class="page-title">იპოვე შენი მოტო საზოგადოება</h1><p class="lead">შეუერთდი კლუბს, გაიცანი თანამოაზრეები და დაგეგმე საერთო გასვლები.</p>
<div class="actions section"><a class="btn" href="{{ route('clubs.create') }}">+ ახალი კლუბის შექმნა</a></div>
@if($clubs->isEmpty())<div class="empty section">კლუბები ჯერ არ არის. შექმენი პირველი მოტოკლუბი!</div>@else<div class="grid section">@foreach($clubs as $club)<article class="card"><div class="eyebrow">{{ $club->city ?: 'საქართველო' }}</div><h2>{{ $club->name }}</h2><p>{{ \Illuminate\Support\Str::limit($club->description,130) }}</p><div class="muted">ორგანიზატორი: {{ $club->owner_name ?: 'MotoHub' }} · {{ $club->members_count }} წევრი</div><a class="btn small" style="margin-top:12px" href="{{ route('clubs.show',$club->slug) }}">კლუბის ნახვა →</a></article>@endforeach</div><div class="section">{{ $clubs->links() }}</div>@endif
@endsection