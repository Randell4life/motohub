<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#070a12">
    <title>@yield('title', 'MotoHub — სერვისცენტრი')</title>
    <style>
        :root {
            --bg:#070a12; --panel:#0d1422; --panel2:#121d30; --line:#243653;
            --blue:#1677ff; --blue2:#42a5ff; --text:#f4f8ff; --muted:#98a8c2;
            --green:#43d9a5; --red:#ff7187; --amber:#ffc66d;
        }
        *{box-sizing:border-box}
        html{min-height:100%;scrollbar-gutter:stable}
        body{margin:0;min-height:100vh;background:radial-gradient(ellipse at 70% -15%,rgba(22,119,255,.11),transparent 38%),var(--bg);color:var(--text);font:14px/1.6 "Segoe UI",Arial,sans-serif}
        a{color:inherit;text-decoration:none}
        button,input,select,textarea{font:inherit}
        button{cursor:pointer}
        .mh-app{min-height:100vh;display:flex}
        .mh-sidebar{width:258px;flex:0 0 258px;height:100vh;position:sticky;top:0;padding:25px 15px 16px;display:flex;flex-direction:column;overflow-y:auto;background:rgba(9,15,26,.97);border-right:1px solid var(--line)}
        .mh-logo{display:flex;align-items:center;gap:12px;padding:0 9px 28px;font-size:20px;font-weight:900;letter-spacing:-.8px}
        .mh-logo-icon{display:grid;place-items:center;width:42px;height:42px;flex-shrink:0;border:1px solid #3989ff;border-radius:12px;background:linear-gradient(145deg,#268aff,#0647a9);box-shadow:0 8px 28px rgba(22,119,255,.24);font-size:21px;font-style:italic}
        .mh-logo-blue{color:var(--blue2)}
        .mh-logo small{display:block;color:var(--muted);font-size:9px;letter-spacing:2px;font-weight:800}
        .mh-menu-label{padding:13px 11px 9px;color:#71839e;font-size:10px;font-weight:850;letter-spacing:1.7px;text-transform:uppercase}
        .mh-menu{display:grid;gap:5px}
        .mh-menu-link{display:flex;align-items:center;gap:11px;min-height:44px;padding:10px 12px;color:#aab9cf;border:1px solid transparent;border-radius:10px;transition:.18s ease}
        .mh-menu-link>span{display:grid;place-items:center;width:23px;flex-shrink:0;color:var(--blue2);font-size:17px}
        .mh-menu-link:hover,.mh-menu-link.active{color:#fff;background:linear-gradient(100deg,rgba(22,119,255,.17),rgba(22,119,255,.045));border-color:rgba(66,165,255,.23)}
        .mh-menu-link.active{box-shadow:inset 3px 0 var(--blue)}
        .mh-sidebar-bottom{display:flex;align-items:center;gap:11px;margin-top:auto;padding:16px 10px 4px;border-top:1px solid var(--line)}
        .mh-status-dot{width:9px;height:9px;flex-shrink:0;border-radius:50%;background:var(--green);box-shadow:0 0 12px rgba(67,217,165,.55)}
        .mh-sidebar-bottom strong,.mh-sidebar-bottom small{display:block}
        .mh-sidebar-bottom small{color:var(--muted);font-size:11px}
        .mh-main{flex:1;min-width:0}
        .mh-topbar{min-height:78px;padding:14px 30px;display:flex;align-items:center;justify-content:space-between;gap:16px;border-bottom:1px solid rgba(139,169,211,.13);background:rgba(8,13,22,.88);backdrop-filter:blur(14px)}
        .mh-topbar h1{margin:0;font-size:21px;line-height:1.3;font-weight:850;letter-spacing:-.5px}
        .mh-topbar p{margin:4px 0 0;color:var(--muted);font-size:12px}
        .mh-topbar-actions{display:flex;align-items:center;justify-content:flex-end;flex-wrap:wrap;gap:9px}
        .mh-public-link,.mh-logout{display:inline-flex;align-items:center;justify-content:center;min-height:38px;padding:8px 13px;border:1px solid var(--line);border-radius:9px;background:var(--panel);color:#dce7f7;font-size:12px;font-weight:750;transition:.18s ease}
        .mh-public-link:hover,.mh-logout:hover{border-color:#3a82d8;background:var(--panel2)}
        .mh-logout{background:transparent}
        .mh-page{width:100%;max-width:1450px;margin:0 auto;padding:28px 30px 44px}
        .mh-alert,.mh-errors{margin-bottom:18px;padding:13px 16px;border:1px solid rgba(67,217,165,.3);border-radius:10px;background:rgba(67,217,165,.07);color:#8af0ca}
        .mh-alert.error,.mh-errors{border-color:rgba(255,113,135,.3);background:rgba(255,113,135,.07);color:#ff9aaa}
        .mh-errors ul{margin-bottom:0;padding-left:20px}
        .mh-card{min-width:0;padding:21px;border:1px solid var(--line);border-radius:14px;background:linear-gradient(145deg,rgba(16,27,44,.98),rgba(11,18,30,.98));box-shadow:0 12px 35px rgba(0,0,0,.12)}
        .mh-card h2,.mh-card h3{margin-top:0}
        .mh-muted{color:var(--muted)}
        .mh-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
        .mh-stat{min-width:0;padding:18px;border:1px solid var(--line);border-radius:12px;background:var(--panel)}
        .mh-stat strong{display:block;margin-top:5px;font-size:29px;line-height:1.3}
        .mh-btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;min-height:39px;padding:8px 14px;border:1px solid var(--blue);border-radius:9px;background:var(--blue);color:#fff;font-weight:750;transition:.18s ease}
        .mh-btn:hover{background:#0868e8;transform:translateY(-1px)}
        .mh-btn.secondary{background:var(--panel2);border-color:var(--line)}
        .mh-btn.secondary:hover{border-color:#3a82d8}
        .mh-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
        .mh-field{display:grid;gap:6px;min-width:0}
        .mh-field.full{grid-column:1/-1}
        .mh-field label{color:#c6d3e4;font-size:12px;font-weight:700}
        .mh-field input,.mh-field select,.mh-field textarea{width:100%;min-width:0;padding:11px 12px;color:var(--text);background:#080e18;border:1px solid var(--line);border-radius:9px;outline:none}
        .mh-field input:focus,.mh-field select:focus,.mh-field textarea:focus{border-color:var(--blue2);box-shadow:0 0 0 3px rgba(66,165,255,.1)}
        .mh-table-wrap{overflow-x:auto;border:1px solid var(--line);border-radius:10px}
        .mh-table{width:100%;border-collapse:collapse;min-width:620px}
        .mh-table th,.mh-table td{padding:12px;text-align:left;border-bottom:1px solid var(--line);vertical-align:top}
        .mh-table th{background:rgba(18,29,48,.75);color:var(--muted);font-size:10px;text-transform:uppercase;letter-spacing:.7px}
        .mh-table td{color:#e4ebf6}
        .mh-table tr:last-child td{border-bottom:0}
        @media(max-width:1050px){.mh-sidebar{width:225px;flex-basis:225px}.mh-topbar{padding-inline:20px}.mh-page{padding:22px 20px 36px}}
        @media(max-width:700px){
            .mh-app{display:block}
            .mh-sidebar{position:relative;width:100%;height:auto;min-height:0;padding:13px 12px 10px;border-right:0;border-bottom:1px solid var(--line)}
            .mh-logo{padding:0 4px 12px;font-size:18px}
            .mh-logo-icon{width:37px;height:37px}
            .mh-menu-label{padding:5px 4px 8px}
            .mh-menu{display:flex;gap:6px;overflow-x:auto;padding:0 0 7px;scrollbar-width:thin}
            .mh-menu-link{flex:0 0 auto;min-height:37px;padding:7px 10px;font-size:12px;white-space:nowrap}
            .mh-menu-link.active{box-shadow:inset 0 -2px var(--blue)}
            .mh-sidebar-bottom{display:none}
            .mh-topbar{min-height:64px;padding:11px 14px;align-items:center}
            .mh-topbar h1{font-size:17px}
            .mh-topbar p{font-size:11px}
            .mh-topbar-actions{gap:5px}
            .mh-public-link{display:none}
            .mh-logout{min-height:34px;padding:6px 9px}
            .mh-page{padding:16px 12px 28px}
            .mh-grid,.mh-form-grid{grid-template-columns:1fr;gap:10px}
            .mh-field.full{grid-column:auto}
            .mh-card{padding:15px}
            .mh-stat{padding:14px}
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="mh-app">
    @include('my-service-center.partials.sidebar')
    <main class="mh-main">
        <header class="mh-topbar">
            <div>
                <h1>@yield('page-title', 'მთავარი პანელი')</h1>
                <p>@yield('page-subtitle', 'MotoHub Service Center')</p>
            </div>
            <div class="mh-topbar-actions">
                @hasSection('public-link')
                    @yield('public-link')
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="mh-logout" type="submit">გასვლა ↗</button>
                </form>
            </div>
        </header>
        <div class="mh-page">
            @if(session('success'))<div class="mh-alert">{{ session('success') }}</div>@endif
            @if($errors->any())
                <div class="mh-errors">
                    <strong>გთხოვ, შეამოწმე შეყვანილი მონაცემები:</strong>
                    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </div>
    </main>
</div>
@stack('scripts')
</body>
</html>
