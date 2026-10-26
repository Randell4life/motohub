<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#080b12">
    <title>@yield('title', 'MotoHub — მართვის პანელი')</title>

    <style>

:root {
    --bg: #080b12;
    --panel: #0d1420;
    --panel2: #121d2c;
    --line: #243249;
    --blue: #1677ff;
    --blue2: #42a5ff;
    --text: #f3f6fc;
    --muted: #95a4ba;
    --green: #43d9a5;
    --red: #ff7187;
}

* {
    box-sizing: border-box;
}

html {
    min-height: 100%;
    scrollbar-gutter: stable;
}

body {
    margin: 0;
    min-height: 100vh;
    background: var(--bg);
    color: var(--text);
    font: 14px/1.55 "Segoe UI", Arial, sans-serif;
}

a {
    color: inherit;
    text-decoration: none;
}

button, input, select, textarea {
    font: inherit;
}

button {
    cursor: pointer;
}

.mh-app {
    min-height: 100vh;
    display: flex;
}

/* მარცხენა მენიუ */
.mh-sidebar {
    width: 232px;
    flex: 0 0 232px;
    height: 100vh;
    min-height: 520px;
    position: sticky;
    top: 0;
    padding: 22px 13px 14px;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    background: #0b111c;
    border-right: 1px solid var(--line);
}

.mh-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 8px 22px;
    font-size: 19px;
    font-weight: 850;
    letter-spacing: -.6px;
}

.mh-logo-icon {
    display: grid;
    place-items: center;
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    border-radius: 10px;
    color: white;
    background: linear-gradient(135deg, #328bff, #0752c5);
    box-shadow: 0 4px 18px rgba(22,119,255,.18);
}

.mh-logo-blue {
    color: var(--blue2);
}

.mh-logo small {
    display: block;
    color: var(--muted);
    font-size: 9px;
    letter-spacing: 1.6px;
    font-weight: 700;
}

.mh-menu-label {
    padding: 10px 10px 8px;
    color: #71829a;
    font-size: 10px;
    font-weight: 750;
    letter-spacing: 1.4px;
    text-transform: uppercase;
}

.mh-menu {
    display: grid;
    gap: 4px;
}

.mh-menu-link {
    display: flex;
    align-items: center;
    gap: 10px;
    min-height: 40px;
    padding: 9px 10px;
    color: #aab7c9;
    border: 1px solid transparent;
    border-radius: 8px;
    transition: background .18s ease, color .18s ease;
}

.mh-menu-link > span {
    width: 21px;
    flex-shrink: 0;
    text-align: center;
    color: var(--blue2);
    font-size: 16px;
}

.mh-menu-link:hover,
.mh-menu-link.active {
    color: #fff;
    background: rgba(22,119,255,.10);
    border-color: rgba(67,145,255,.20);
}

.mh-menu-link.active {
    box-shadow: inset 2px 0 var(--blue);
}

.mh-sidebar-bottom {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: auto;
    padding: 15px 8px 3px;
    border-top: 1px solid var(--line);
}

.mh-status-dot {
    width: 8px;
    height: 8px;
    flex-shrink: 0;
    border-radius: 50%;
    background: var(--green);
}

.mh-sidebar-bottom strong,
.mh-sidebar-bottom small {
    display: block;
}

.mh-sidebar-bottom small {
    color: var(--muted);
    font-size: 11px;
}

/* მარჯვენა სამუშაო სივრცე */
.mh-main {
    flex: 1;
    min-width: 0;
}

.mh-topbar {
    min-height: 68px;
    padding: 13px 26px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    border-bottom: 1px solid rgba(139,169,211,.12);
    background: rgba(10,15,24,.94);
}

.mh-topbar h1 {
    margin: 0;
    font-size: 20px;
    line-height: 1.35;
    font-weight: 750;
    letter-spacing: -.45px;
}

.mh-topbar p {
    margin: 3px 0 0;
    color: var(--muted);
    font-size: 12px;
}

.mh-topbar-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 8px;
}

.mh-public-link,
.mh-logout {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 35px;
    padding: 7px 11px;
    border: 1px solid var(--line);
    border-radius: 8px;
    background: var(--panel);
    color: #dce7f7;
    font-size: 12px;
    transition: border-color .18s ease, background .18s ease;
}

.mh-public-link:hover,
.mh-logout:hover {
    border-color: #3a6cae;
    background: var(--panel2);
}

.mh-page {
    width: 100%;
    max-width: 1260px;
    margin: 0 auto;
    padding: 24px 26px 38px;
}

/* შეტყობინებები */
.mh-alert,
.mh-errors {
    margin-bottom: 16px;
    padding: 12px 15px;
    border: 1px solid rgba(67,217,165,.28);
    border-radius: 9px;
    background: rgba(67,217,165,.06);
    color: #8af0ca;
}

.mh-alert.error,
.mh-errors {
    border-color: rgba(255,113,135,.3);
    background: rgba(255,113,135,.06);
    color: #ff9aaa;
}

.mh-errors ul {
    margin-bottom: 0;
    padding-left: 20px;
}

/* კომპაქტური ბარათები */
.mh-card {
    min-width: 0;
    padding: 19px;
    border: 1px solid var(--line);
    border-radius: 11px;
    background: var(--panel);
}

.mh-card h2,
.mh-card h3 {
    margin-top: 0;
}

.mh-muted {
    color: var(--muted);
}

.mh-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
}

.mh-stat {
    min-width: 0;
    padding: 16px;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--panel);
}

.mh-stat strong {
    display: block;
    margin-top: 5px;
    font-size: 27px;
    line-height: 1.3;
}

/* ღილაკები */
.mh-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 37px;
    padding: 8px 13px;
    border: 1px solid var(--blue);
    border-radius: 8px;
    background: var(--blue);
    color: #fff;
    font-weight: 700;
    transition: background .18s ease;
}

.mh-btn:hover {
    background: #0868e8;
}

.mh-btn.secondary {
    background: var(--panel2);
    border-color: var(--line);
}

.mh-btn.secondary:hover {
    border-color: #3a6cae;
}

/* ფორმები */
.mh-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 13px;
}

.mh-field {
    display: grid;
    gap: 5px;
    min-width: 0;
}

.mh-field.full {
    grid-column: 1 / -1;
}

.mh-field label {
    color: #c6d3e4;
    font-size: 12px;
    font-weight: 650;
}

.mh-field input,
.mh-field select,
.mh-field textarea {
    width: 100%;
    min-width: 0;
    padding: 10px 11px;
    color: var(--text);
    background: #090f18;
    border: 1px solid var(--line);
    border-radius: 8px;
    outline: none;
}

.mh-field input:focus,
.mh-field select:focus,
.mh-field textarea:focus {
    border-color: var(--blue2);
    box-shadow: 0 0 0 2px rgba(66,165,255,.08);
}

/* ცხრილები */
.mh-table-wrap {
    overflow-x: auto;
}

.mh-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 620px;
}

.mh-table th,
.mh-table td {
    padding: 11px 10px;
    text-align: left;
    border-bottom: 1px solid var(--line);
    vertical-align: top;
}

.mh-table th {
    color: var(--muted);
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .55px;
}

.mh-table td {
    color: #e4ebf6;
}

/* საშუალო ეკრანები */
@media (max-width: 1050px) {
    .mh-sidebar {
        width: 210px;
        flex-basis: 210px;
    }

    .mh-topbar {
        padding-right: 18px;
        padding-left: 18px;
    }

    .mh-page {
        padding: 20px 18px 32px;
    }

    .mh-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* მობილური */
@media (max-width: 680px) {
    .mh-app {
        display: block;
    }

    .mh-sidebar {
        position: static;
        width: 100%;
        height: auto;
        min-height: 0;
        padding: 12px;
        border-right: 0;
        border-bottom: 1px solid var(--line);
    }

    .mh-logo {
        padding: 0 4px 12px;
    }

    .mh-menu-label {
        padding: 7px 4px;
    }

    .mh-menu {
        display: flex;
        gap: 5px;
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .mh-menu-link {
        flex-shrink: 0;
        min-height: 36px;
        padding: 7px 9px;
        font-size: 12px;
    }

    .mh-menu-link.active {
        box-shadow: inset 0 -2px var(--blue);
    }

    .mh-sidebar-bottom {
        display: none;
    }

    .mh-topbar {
        min-height: 60px;
        padding: 12px 14px;
        align-items: flex-start;
    }

    .mh-topbar h1 {
        font-size: 17px;
    }

    .mh-topbar p {
        font-size: 11px;
    }

    .mh-topbar-actions {
        gap: 5px;
    }

    .mh-public-link {
        display: none;
    }

    .mh-page {
        padding: 16px 12px 28px;
    }

    .mh-grid,
    .mh-form-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .mh-field.full {
        grid-column: auto;
    }

    .mh-card {
        padding: 14px;
    }

    .mh-stat {
        padding: 14px;
    }
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
                    <button class="mh-logout" type="submit">გასვლა</button>
                </form>
            </div>
        </header>

        <div class="mh-page">
            @if(session('success'))
                <div class="mh-alert">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="mh-errors">
                    <strong>გთხოვ, შეამოწმე შეყვანილი მონაცემები:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

@stack('scripts')
</body>
</html>
