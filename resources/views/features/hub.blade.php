@extends('layouts.motohub-features')
@section('title','MotoHub | ერთიანი მოტო პლატფორმა')
@section('content')
<div class="eyebrow">THE MOTORCYCLE ECOSYSTEM</div><h1 class="page-title">შენი მოტოსამყარო.<br><span style="color:var(--blue2)">ერთ სივრცეში.</span></h1>
<p class="lead">მართე შენი მოტოციკლი, დაგეგმე სერვისი, იპოვე ნაწილები და შეუერთდი მოტო საზოგადოებას.</p>
<div class="grid section">
<a class="card" href="{{ route('garage.index') }}"><div class="eyebrow">01 / MY GARAGE</div><h2>ჩემი ავტოფარეხი</h2><p>მოტოციკლების ისტორია, მომსახურების შეხსენებები და ხარჯების კონტროლი.</p><div class="stat">{{ $stats['motorcycles'] }}</div><span class="muted">შენი მოტოციკლები / კატალოგში</span></a>
<a class="card" href="{{ route('service-centers.index') }}"><div class="eyebrow">02 / SMART SERVICE</div><h2>სერვის ცენტრები</h2><p>იპოვე სახელოსნო, ნახე მომსახურებები და დაჯავშნე თავისუფალი დრო.</p><div class="stat">{{ $stats['service_centers'] }}</div><span class="muted">აქტიური სერვისცენტრი</span></a>
<a class="card" href="{{ route('parts.index') }}"><div class="eyebrow">03 / MARKETPLACE 2.0</div><h2>ნაწილების ბაზარი</h2><p>შეარჩიე ნაწილი მოტოციკლის მარკის, მოდელის, წლისა და მოცულობის მიხედვით.</p><span class="btn small">კატალოგის ნახვა →</span></a>
<a class="card" href="{{ route('favorites.index') }}"><div class="eyebrow">04 / SAVED ITEMS</div><h2>ფავორიტები და შედარება</h2><p>შეინახე სასურველი მოტოციკლები და ნაწილები, შეადარე მოდელები.</p><span class="btn small">ფავორიტების ნახვა →</span></a>
<a class="card" href="{{ route('clubs.index') }}"><div class="eyebrow">05 / MOTO CLUBS</div><h2>მოტოკლუბები და ღონისძიებები</h2><p>შექმენი კლუბი, შეუერთდი გასვლებს და დაგეგმე ღონისძიებები.</p><div class="stat">{{ $stats['clubs'] }}</div><span class="muted">ღია კლუბი · {{ $stats['events'] }} მომავალი ღონისძიება</span></a>
<a class="card" href="{{ route('community.index') }}"><div class="eyebrow">COMMUNITY</div><h2>მოტო საზოგადოება</h2><p>გაუზიარე გამოცდილება სხვა მოტომოყვარულებს.</p><span class="btn small">Community →</span></a>
</div>
<div class="section"><div class="section-head"><div><div class="eyebrow">UPCOMING</div><h2>მომავალი ღონისძიებები</h2></div><a class="btn ghost small" href="{{ route('clubs.index') }}">ყველა კლუბი</a></div>
@if($events->isEmpty())<div class="empty">ღონისძიებები ჯერ არ არის დაგეგმილი. შექმენი კლუბი და მოაწყე პირველი გასვლა.</div>@else<div class="grid two">@foreach($events as $event)<article class="card"><div class="eyebrow">{{ \Illuminate\Support\Carbon::parse($event->starts_at)->format('d.m.Y · H:i') }}</div><h3>{{ $event->title }}</h3><p>{{ $event->club_name }} · {{ $event->location }}</p><a class="btn ghost small" href="{{ route('clubs.show',$event->club_slug) }}">დეტალები →</a></article>@endforeach</div>@endif</div>
<div class="section"><div class="section-head"><h2>მოტოკლუბები</h2><a class="btn ghost small" href="{{ route('clubs.index') }}">ყველა კლუბი →</a></div>
@if($clubs->isEmpty())<div class="empty">ჯერ კლუბები არ არის. შექმენი პირველი MotoHub კლუბი.</div>@else<div class="grid">@foreach($clubs as $club)<a class="card" href="{{ route('clubs.show',$club->slug) }}"><h3>{{ $club->name }}</h3><p>{{ $club->city ?: 'საქართველო' }} · {{ $club->members_count }} წევრი</p><span class="muted">{{ \Illuminate\Support\Str::limit($club->description,100) }}</span></a>@endforeach</div>@endif</div>
@endsection