<!DOCTYPE html>
<html lang="ka">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>ჩემი შეტყობინებები | MotoHub</title>
<style>
:root{color-scheme:dark;--bg:#070a12;--panel:#0d1422;--panel2:#121d30;--line:#243653;--blue:#1677ff;--bright:#42a5ff;--text:#f4f8ff;--muted:#98a8c2}
*{box-sizing:border-box}body{margin:0;background:radial-gradient(ellipse at 85% 0%,#11264a 0,transparent 36rem),var(--bg);color:var(--text);font:15px/1.6 Inter,system-ui,Arial,sans-serif}a{color:inherit;text-decoration:none}.wrap{max-width:1100px;margin:auto;padding:28px 22px 70px}.top{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:34px}.brand{font-size:25px;font-weight:950;letter-spacing:-1.2px}.brand span{color:var(--bright)}.back{color:var(--muted)}.hero{padding:28px;border:1px solid var(--line);background:linear-gradient(120deg,#10203a,#0d1422 65%);border-radius:12px;margin-bottom:22px}.eyebrow{font-size:11px;letter-spacing:2px;color:var(--bright);font-weight:900;text-transform:uppercase}.hero h1{font-size:clamp(30px,5vw,46px);line-height:1.05;letter-spacing:-1.5px;margin:10px 0}.hero p{color:var(--muted);margin:0}.list{display:grid;gap:10px}.item{display:flex;align-items:center;gap:15px;padding:18px;border:1px solid var(--line);border-radius:10px;background:rgba(13,20,34,.88);transition:.2s}.item:hover{border-color:#397dcc;background:#101d31}.avatar{width:48px;height:48px;flex:none;border-radius:10px;background:#142b4c;border:1px solid #2c568c;display:grid;place-items:center;font-weight:900;color:#b9dcff}.details{min-width:0;flex:1}.title{font-weight:800}.preview{color:var(--muted);font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.meta{font-size:11px;color:#7087a6}.badge{background:var(--blue);border-radius:99px;padding:2px 8px;font-size:11px;font-weight:800}.empty{padding:40px 20px;text-align:center;border:1px dashed #2b4264;border-radius:12px;color:var(--muted)}.empty strong{display:block;color:var(--text);font-size:20px;margin-bottom:5px}.btn{display:inline-flex;margin-top:15px;background:var(--blue);padding:10px 15px;border-radius:6px;font-weight:800}.flash{padding:12px 15px;border:1px solid #1f765d;background:#0d2b25;color:#8cf0cb;border-radius:8px;margin-bottom:16px} @media(max-width:600px){.wrap{padding:18px 14px 45px}.hero{padding:22px}.item{padding:13px;gap:10px}.avatar{width:42px;height:42px}}
</style></head>
<body><main class="wrap">
<header class="top"><a class="brand" href="{{ route('home') }}">MOTO<span>HUB</span></a><a class="back" href="{{ route('account.index') }}">← პირადი კაბინეტი</a></header>
<section class="hero"><div class="eyebrow">Direct messages</div><h1>შეტყობინებები.</h1><p>ყველა საუბარი გამყიდველებთან და MotoHub-ის მომხმარებლებთან ერთ სივრცეში.</p></section>
@if (session('success'))<div class="flash">{{ session('success') }}</div>@endif
@if ($conversations->count())
<div class="list">
@foreach ($conversations as $conversation)
@php $other = $conversation->participants->firstWhere('id', '!=', auth()->id()); $last = $conversation->latestMessage; @endphp
<a class="item" href="{{ route('messages.show', $conversation) }}"><div class="avatar">{{ mb_strtoupper(mb_substr($other?->name ?? 'M', 0, 1)) }}</div><div class="details"><div class="title">{{ $other?->name ?? 'MotoHub მომხმარებელი' }} @if($conversation->motorcycle)<span class="meta"> · {{ $conversation->motorcycle->title }}</span>@endif</div><div class="preview">{{ $last?->body ?? 'საუბარი ჯერ არ დაწყებულა' }}</div><div class="meta">{{ optional($conversation->last_message_at)->format('d.m.Y H:i') ?? $conversation->created_at->format('d.m.Y H:i') }}</div></div>@if($conversation->unread_count)<span class="badge">{{ $conversation->unread_count }}</span>@endif</a>
@endforeach
</div>
<div style="margin-top:22px">{{ $conversations->links() }}</div>
@else
<div class="empty"><strong>ჯერ საუბრები არ გაქვს</strong>როცა გამყიდველს ან სხვა მომხმარებელს მისწერ, საუბარი აქ გამოჩნდება.<br><a class="btn" href="{{ route('motorcycles.index') }}">მოტოციკლების ნახვა →</a></div>
@endif
</main></body></html>
