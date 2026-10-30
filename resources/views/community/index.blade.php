<!doctype html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#070a12">
    <title>MotoHub Community — მოტო საზოგადოება</title>
    <style>
        :root{color-scheme:dark;--bg:#070a12;--panel:#0d1422;--panel2:#121d30;--line:#243653;--blue:#1677ff;--bright:#42a5ff;--text:#f4f8ff;--muted:#98a8c2}
        *{box-sizing:border-box}body{margin:0;background:radial-gradient(ellipse at 70% -12%,#12305a66,transparent 38rem),var(--bg);color:var(--text);font:14px/1.6 Inter,"Segoe UI",Arial,sans-serif}
        a{color:inherit;text-decoration:none}button,input,textarea,select{font:inherit}.wrap{width:min(1160px,calc(100% - 32px));margin:auto}
        .top{position:sticky;top:0;z-index:5;background:#070c16ed;border-bottom:1px solid var(--line);backdrop-filter:blur(18px)}
        .nav{min-height:70px;display:flex;align-items:center;gap:22px}.logo{font-size:24px;font-weight:950;letter-spacing:-1px}.logo span{color:var(--bright)}
        .links{display:flex;gap:20px;color:#a8bad5;font-size:13px}.links a:hover,.links a.active{color:white}.profile{margin-left:auto;color:#b8d5ff}
        .hero{padding:45px 0 25px}.eyebrow{font-size:10px;font-weight:900;letter-spacing:2.5px;color:var(--bright)}h1{font-size:clamp(34px,5vw,55px);line-height:1.05;letter-spacing:-1.8px;margin:10px 0}.hero p{max-width:620px;color:var(--muted);margin:0}
        .layout{display:grid;grid-template-columns:minmax(0,1fr) 290px;gap:22px;align-items:start;padding-bottom:60px}
        .feed{min-width:0}.card{background:linear-gradient(145deg,#101a2a,#0b1220);border:1px solid var(--line);border-radius:14px;margin-bottom:16px;overflow:hidden;box-shadow:0 14px 35px #0002}
        .composer{padding:20px}.composer h2,.side h3{margin:0 0 12px;font-size:16px}.composer textarea{width:100%;min-height:110px;resize:vertical;border:1px solid #29466b;border-radius:10px;background:#081326;color:var(--text);padding:13px;outline:none}.composer textarea:focus,.composer select:focus{border-color:var(--bright);box-shadow:0 0 0 3px #1677ff20}
        .compose-row{display:flex;gap:10px;align-items:center;margin-top:12px}.composer select{flex:1;min-width:0;border:1px solid #29466b;border-radius:8px;background:#081326;color:var(--text);padding:11px}
        .btn{border:1px solid #3187f5;border-radius:8px;background:linear-gradient(120deg,#1677ff,#0754ca);color:white;font-weight:800;padding:11px 16px;cursor:pointer}.btn:hover{filter:brightness(1.12)}
        .flash{padding:12px 15px;margin-bottom:14px;border:1px solid #1f765d;background:#0d2b25;color:#8cf0cb;border-radius:9px}.errors{padding:10px 14px;margin:12px 0;border:1px solid #85434b;background:#35151c;color:#ffb9c0;border-radius:8px}
        .post{padding:19px 20px}.post-head{display:flex;align-items:center;gap:11px}.avatar{width:43px;height:43px;display:grid;place-items:center;border:1px solid #367ac0;border-radius:50%;background:linear-gradient(145deg,#164879,#111b2d);font-weight:900;color:#e7f3ff;font-size:17px}.author{font-weight:850}.date{font-size:11px;color:#8098ba}.delete{margin-left:auto;background:transparent;border:0;color:#8fa6c6;cursor:pointer}.delete:hover{color:#ff9ca8}
        .post-body{white-space:pre-wrap;overflow-wrap:anywhere;font-size:14px;line-height:1.8;margin:16px 0;color:#e5edfa}.bike-ref{display:flex;align-items:center;gap:9px;padding:10px 12px;margin:12px 0;border:1px solid #25466d;border-radius:9px;background:#0a1b32;color:#b8d7ff;font-size:12px}
        .post-stats{display:flex;align-items:center;gap:15px;border-top:1px solid #20324b;border-bottom:1px solid #20324b;padding:10px 0;color:#8fa6c6;font-size:12px}
        .actions{display:flex;gap:9px;padding:10px 0 2px}.action{display:inline-flex;align-items:center;gap:7px;border:1px solid transparent;border-radius:7px;padding:7px 11px;background:transparent;color:#a8bbd6;cursor:pointer}.action:hover{background:#12243e;border-color:#284a73;color:white}.action.liked{color:#69b1ff;background:#10294c;border-color:#24558d}
        .comments{border-top:1px solid #20324b;margin-top:12px;padding-top:12px}.comment{display:flex;gap:9px;margin:11px 0}.comment .avatar{width:31px;height:31px;font-size:12px;flex:none}.comment-content{min-width:0;flex:1;padding:8px 11px;border-radius:9px;background:#101d30}.comment-content strong{font-size:12px}.comment-content p{margin:3px 0 0;color:#d2deef;white-space:pre-wrap;overflow-wrap:anywhere;font-size:12px}
        .comment-form{display:flex;gap:8px;margin-top:12px}.comment-form input{flex:1;min-width:0;border:1px solid #29466b;border-radius:8px;background:#081326;color:white;padding:10px 12px}.comment-form button{padding:9px 12px}
        .side{position:sticky;top:88px}.side .card{padding:18px}.side p{color:var(--muted);font-size:12px;margin:0 0 13px}.side-link{display:flex;justify-content:space-between;padding:10px 0;border-top:1px solid #20324b;color:#c8dcf7;font-size:12px}.side-link span{color:var(--bright)}
        .empty{text-align:center;padding:45px 20px;color:var(--muted)}.empty strong{display:block;color:white;font-size:18px;margin-bottom:5px}.pagination{display:flex;justify-content:center;gap:8px;margin:20px 0}.pagination a,.pagination span{padding:8px 12px;border:1px solid var(--line);border-radius:7px;background:var(--panel)}
        footer{border-top:1px solid var(--line);padding:22px 0;color:#7289aa;font-size:11px}
        @media(max-width:850px){.layout{grid-template-columns:1fr}.side{position:static;display:grid;grid-template-columns:1fr 1fr;gap:12px}.side .card{margin:0}}
        @media(max-width:560px){.nav{flex-wrap:wrap;padding:12px 0;gap:12px}.links{order:3;width:100%;overflow:auto;gap:17px}.profile{margin-left:auto}.hero{padding-top:32px}.layout{gap:12px}.composer,.post{padding:15px}.compose-row{align-items:stretch;flex-direction:column}.compose-row .btn{width:100%}.side{grid-template-columns:1fr}.comment-form{align-items:stretch}.comment-form button{padding-inline:9px}}
    </style>
</head>
<body>
<header class="top">
    <div class="wrap nav">
        <a class="logo" href="{{ route('home') }}">MOTO<span>HUB</span></a>
        <nav class="links" aria-label="ნავიგაცია">
            <a href="{{ route('home') }}">მთავარი</a>
            <a href="{{ route('motorcycles.index') }}">მოტოციკლები</a>
            <a href="{{ route('parts.index') }}">ნაწილები</a>
            <a href="{{ route('service-centers.index') }}">სერვისცენტრები</a>
            <a class="active" href="{{ route('community.index') }}">Community</a>
        </nav>
        <a class="profile" href="{{ auth()->check() ? route('account.index') : route('login') }}">{{ auth()->check() ? auth()->user()->name : 'შესვლა' }} ↗</a>
    </div>
</header>

<main class="wrap">
    <section class="hero">
        <div class="eyebrow">MOTOHUB / RIDER NETWORK</div>
        <h1>მოტო ხალხი.<br><span style="color:var(--bright)">ერთი საზოგადოება.</span></h1>
        <p>გააზიარე შენი გამოცდილება, იკითხე რჩევა, აჩვენე შენი მოტოციკლი და გაეცანი სხვა მრბოლელებსა და მოტო ენთუზიასტებს.</p>
    </section>

    <section class="layout">
        <div class="feed">
            @if(session('success'))<div class="flash">{{ session('success') }}</div>@endif
            @if($errors->any())
                <div class="errors">
                    @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
            @endif

            @auth
                <form class="card composer" method="POST" action="{{ route('community.posts.store') }}">
                    @csrf
                    <h2>რა ხდება შენს მოტო სამყაროში?</h2>
                    <textarea name="body" maxlength="3000" required placeholder="გააზიარე გამოცდილება, შეკითხვა ან შენი ბოლო გასეირნება...">{{ old('body') }}</textarea>
                    @if($myMotorcycles->isNotEmpty())
                        <div class="compose-row">
                            <select name="motorcycle_id">
                                <option value="">მოტოციკლის მიბმა (არასავალდებულო)</option>
                                @foreach($myMotorcycles as $motorcycle)
                                    <option value="{{ $motorcycle->id }}" @selected(old('motorcycle_id') == $motorcycle->id)>{{ $motorcycle->title }}</option>
                                @endforeach
                            </select>
                            <button class="btn" type="submit">პოსტის გამოქვეყნება ↗</button>
                        </div>
                    @else
                        <div class="compose-row"><button class="btn" type="submit">პოსტის გამოქვეყნება ↗</button></div>
                    @endif
                </form>
            @else
                <div class="card composer">
                    <h2>შეუერთდი MotoHub Community-ს</h2>
                    <p style="color:var(--muted);margin:0 0 13px">შედით ან დარეგისტრირდი, რომ გამოაქვეყნო პოსტი, დაალაიქო და კომენტარი დატოვო.</p>
                    <a class="btn" href="{{ route('login') }}">შესვლა / რეგისტრაცია</a>
                </div>
            @endauth

            @forelse($posts as $post)
                <article class="card post" id="post-{{ $post->id }}">
                    <header class="post-head">
                        <div class="avatar">{{ mb_strtoupper(mb_substr($post->user->name, 0, 1)) }}</div>
                        <div><div class="author">{{ $post->user->name }}</div><div class="date">{{ $post->created_at->diffForHumans() }}</div></div>
                        @if(auth()->check() && auth()->id() === $post->user_id)
                            <form method="POST" action="{{ route('community.posts.destroy', $post) }}" onsubmit="return confirm('ნამდვილად გსურს პოსტის წაშლა?')">
                                @csrf @method('DELETE')
                                <button class="delete" type="submit" aria-label="პოსტის წაშლა">წაშლა ×</button>
                            </form>
                        @endif
                    </header>

                    <div class="post-body">{!! nl2br(e($post->body)) !!}</div>

                    @if($post->motorcycle)
                        <a class="bike-ref" href="{{ route('motorcycles.show', $post->motorcycle) }}">🏍 დაკავშირებული მოტოციკლი: <strong>{{ $post->motorcycle->title }}</strong> ↗</a>
                    @endif

                    <div class="post-stats"><span>♥ {{ $post->likes_count }} მოწონება</span><span>▤ {{ $post->comments_count }} კომენტარი</span></div>
                    <div class="actions">
                        @auth
                            <form method="POST" action="{{ route('community.posts.like', $post) }}">@csrf
                                <button class="action {{ $post->liked_by_me ? 'liked' : '' }}" type="submit">{{ $post->liked_by_me ? '♥ მოწონებულია' : '♡ მოწონება' }}</button>
                            </form>
                        @else
                            <a class="action" href="{{ route('login') }}">♡ მოწონება</a>
                        @endauth
                        <a class="action" href="#comment-{{ $post->id }}">კომენტარი</a>
                    </div>

                    <div class="comments">
                        @foreach($post->comments as $comment)
                            <div class="comment">
                                <div class="avatar">{{ mb_strtoupper(mb_substr($comment->user->name, 0, 1)) }}</div>
                                <div class="comment-content"><strong>{{ $comment->user->name }}</strong><p>{{ $comment->body }}</p></div>
                            </div>
                        @endforeach
                        @auth
                            <form class="comment-form" id="comment-{{ $post->id }}" method="POST" action="{{ route('community.posts.comments.store', $post) }}">
                                @csrf
                                <input type="text" name="body" maxlength="1000" required placeholder="დაწერე კომენტარი...">
                                <button class="btn" type="submit">გაგზავნა</button>
                            </form>
                        @else
                            <div style="font-size:12px;color:var(--muted);margin-top:10px"><a href="{{ route('login') }}" style="color:var(--bright)">შედი</a>, რომ კომენტარი დატოვო.</div>
                        @endauth
                    </div>
                </article>
            @empty
                <div class="card empty"><strong>საზოგადოება ჯერ შენს პირველ პოსტს ელოდება.</strong>გახდი პირველი, ვინც MotoHub Community-ში გამოცდილებას გააზიარებს.</div>
            @endforelse

            @if($posts->hasPages())
                <div class="pagination">{{ $posts->links() }}</div>
            @endif
        </div>

        <aside class="side">
            <div class="card">
                <h3>Community-ის წესები</h3>
                <p>შევინარჩუნოთ მეგობრული და უსაფრთხო მოტო სივრცე.</p>
                <div class="side-link">პატივისცემა ყველას მიმართ <span>01</span></div>
                <div class="side-link">არ გააზიარო ცრუ ინფორმაცია <span>02</span></div>
                <div class="side-link">უსაფრთხოება პირველ ადგილზე <span>03</span></div>
            </div>
            <div class="card">
                <h3>რა შეგიძლია აქ?</h3>
                <p>დასვი ტექნიკური კითხვა, გააზიარე მოგზაურობის ფოტო-ისტორია ტექსტით, იპოვე თანამოაზრეები და განიხილე მოტო თემები.</p>
                <a class="side-link" href="{{ route('moto-assistant.index') }}">Moto Assistant <span>↗</span></a>
                <a class="side-link" href="{{ route('service-centers.index') }}">იპოვე სერვისი <span>↗</span></a>
            </div>
        </aside>
    </section>
</main>
<footer><div class="wrap">© {{ date('Y') }} MotoHub Community — შენი მოტო საზოგადოება.</div></footer>
</body>
</html>
