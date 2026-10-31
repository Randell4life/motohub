
<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>პირადი კაბინეტი | MotoHub</title>

    <style>
        :root {
            --bg: #080d18;
            --sidebar: #0c1423;
            --panel: #111c2e;
            --panel-hover: #16243a;
            --border: #22334c;
            --text: #f1f5fc;
            --muted: #8fa1bb;
            --blue: #2785ff;
            --blue-light: #53a0ff;
            --green: #37d6a0;
            --yellow: #ffca68;
            --red: #ff6474;
            --radius: 17px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 90px;
        }

        body {
            margin: 0;
            background:
                radial-gradient(ellipse at 80% 0%, rgba(39,133,255,.10), transparent 36%),
                var(--bg);
            color: var(--text);
            font-family: Inter, -apple-system, BlinkMacSystemFont,
                "Segoe UI", Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button, input {
            font: inherit;
        }

        button, a {
            -webkit-tap-highlight-color: transparent;
        }

        .app {
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 250px;
            padding: 28px 18px;
            background: rgba(12,20,35,.96);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 20;
            overflow-y: auto;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 10px 32px;
            font-size: 23px;
            font-weight: 900;
            letter-spacing: -1px;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: linear-gradient(135deg, #2785ff, #0754cb);
            box-shadow: 0 7px 24px rgba(39,133,255,.25);
            font-size: 19px;
        }

        .brand span:last-child span {
            color: var(--blue-light);
        }

        .menu-label {
            padding: 8px 12px;
            color: #687d99;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .nav {
            display: grid;
            gap: 6px;
        }

        .nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 46px;
            padding: 11px 13px;
            border: 1px solid transparent;
            border-radius: 11px;
            color: var(--muted);
            font-weight: 600;
            transition: .2s ease;
        }

        .nav a:hover,
        .nav a.active {
            background: rgba(39,133,255,.12);
            border-color: rgba(39,133,255,.17);
            color: #fff;
        }

        .nav a.active {
            box-shadow: inset 3px 0 var(--blue);
        }

        .nav-icon {
            width: 22px;
            text-align: center;
            font-size: 17px;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 26px;
        }

        .sidebar-card {
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: linear-gradient(145deg, #13233b, #0d1728);
        }

        .sidebar-card strong {
            display: block;
            margin-bottom: 5px;
        }

        .sidebar-card p {
            margin: 0 0 13px;
            color: var(--muted);
            font-size: 12px;
        }

        .sidebar-card a {
            color: var(--blue-light);
            font-weight: 700;
        }

        .main {
            margin-left: 250px;
            min-width: 0;
        }

        .topbar {
            height: 76px;
            position: sticky;
            top: 0;
            z-index: 15;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 0 38px;
            background: rgba(8,13,24,.88);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(34,51,76,.8);
        }

        .breadcrumb {
            color: var(--muted);
            font-size: 13px;
        }

        .breadcrumb strong {
            color: var(--text);
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .site-link {
            color: var(--muted);
            font-weight: 600;
        }

        .site-link:hover {
            color: white;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 39px;
            height: 39px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            border: 1px solid rgba(83,160,255,.35);
            border-radius: 12px;
            background: linear-gradient(145deg, #214f88, #142b4c);
            color: white;
            font-weight: 800;
            font-size: 17px;
        }

        .user-name {
            font-weight: 700;
        }

        .user-caption {
            color: var(--muted);
            font-size: 11px;
        }

        .content {
            max-width: 1500px;
            margin: 0 auto;
            padding: 34px 38px 55px;
        }

        .hero {
            position: relative;
            overflow: hidden;
            padding: 32px;
            margin-bottom: 26px;
            border: 1px solid #24436a;
            border-radius: 21px;
            background:
                radial-gradient(ellipse at 90% 10%, rgba(39,133,255,.24), transparent 45%),
                linear-gradient(120deg, #111d30, #101a2a 75%);
        }

        .hero::after {
            content: "MH";
            position: absolute;
            right: 30px;
            top: -36px;
            color: rgba(83,160,255,.055);
            font-size: 180px;
            line-height: 1;
            font-weight: 900;
            letter-spacing: -15px;
            pointer-events: none;
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 9px;
            color: var(--blue-light);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--blue);
            box-shadow: 0 0 12px var(--blue);
        }

        h1 {
            margin: 12px 0 8px;
            font-size: clamp(26px, 3vw, 37px);
            line-height: 1.25;
            letter-spacing: -1.1px;
        }

        .hero p {
            max-width: 520px;
            margin: 0;
            color: var(--muted);
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 11px;
            margin-top: 23px;
        }

        .btn {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #17243a;
            color: white;
            cursor: pointer;
            font-weight: 750;
            transition: .2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            background: #20324c;
        }

        .btn-primary {
            background: var(--blue);
            border-color: var(--blue);
            color: white;
            box-shadow: 0 5px 20px rgba(39,133,255,.16);
        }

        .btn-primary:hover {
            background: #4095ff;
        }

        .btn-small {
            min-height: 34px;
            padding: 6px 10px;
            font-size: 12px;
        }

        .btn-danger {
            color: #ff8995;
            background: rgba(255,100,116,.07);
            border-color: rgba(255,100,116,.22);
        }

        .btn-danger:hover {
            background: rgba(255,100,116,.15);
        }

        .notice {
            padding: 13px 16px;
            margin-bottom: 22px;
            border: 1px solid rgba(55,214,160,.25);
            border-radius: 12px;
            background: rgba(55,214,160,.08);
            color: var(--green);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 35px;
        }

        .stat {
            min-width: 0;
            padding: 21px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: linear-gradient(145deg, #121e31, #0f1929);
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 650;
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(39,133,255,.2);
            border-radius: 11px;
            background: rgba(39,133,255,.10);
            font-size: 17px;
        }

        .stat-number {
            display: block;
            margin-top: 12px;
            font-size: 30px;
            line-height: 1.2;
            font-weight: 850;
            letter-spacing: -.8px;
        }

        .stat-note {
            display: block;
            margin-top: 5px;
            color: #7085a1;
            font-size: 11px;
        }

        .section {
            margin-top: 38px;
            scroll-margin-top: 95px;
        }

        .section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .section-heading h2 {
            margin: 0;
            font-size: 21px;
            letter-spacing: -.5px;
        }

        .section-heading p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 12px;
        }

        .section-count {
            padding: 5px 10px;
            border: 1px solid var(--border);
            border-radius: 9px;
            color: var(--blue-light);
            background: rgba(39,133,255,.07);
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.2fr);
            gap: 17px;
        }

        .panel {
            min-width: 0;
            padding: 23px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--panel);
        }

        .panel h3 {
            margin: 0 0 6px;
            font-size: 16px;
        }

        .panel-description {
            margin: 0 0 20px;
            color: var(--muted);
            font-size: 12px;
        }

        .profile-person {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 23px;
        }

        .profile-person .avatar {
            width: 57px;
            height: 57px;
            border-radius: 16px;
            font-size: 24px;
        }

        .profile-person h3 {
            margin: 0;
            font-size: 17px;
            overflow-wrap: anywhere;
        }

        .profile-person p {
            margin: 3px 0 0;
            color: var(--muted);
            overflow-wrap: anywhere;
        }

        .info-line {
            padding: 13px 0;
            border-top: 1px solid var(--border);
        }

        .info-label {
            display: block;
            color: var(--muted);
            font-size: 11px;
        }

        .info-value {
            display: block;
            margin-top: 3px;
            font-weight: 650;
            overflow-wrap: anywhere;
        }

        .form-group {
            margin-top: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            color: #c8d5e8;
            font-size: 12px;
            font-weight: 700;
        }

        .form-group input {
            width: 100%;
            min-height: 44px;
            padding: 11px 13px;
            border: 1px solid var(--border);
            border-radius: 10px;
            outline: none;
            background: #0b1423;
            color: white;
        }

        .form-group input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(39,133,255,.12);
        }

        .form-help {
            display: block;
            margin-top: 7px;
            color: var(--muted);
            font-size: 11px;
        }

        .motorcycle-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .motorcycle-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--panel);
            transition: transform .2s ease, border-color .2s ease;
        }

        .motorcycle-card:hover {
            transform: translateY(-3px);
            border-color: #365985;
        }

        .motorcycle-image {
            position: relative;
            height: 210px;
            overflow: hidden;
            background: #0c1525;
        }

        .motorcycle-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .4s ease;
        }

        .motorcycle-card:hover .motorcycle-image img {
            transform: scale(1.035);
        }

        .image-placeholder {
            height: 100%;
            display: grid;
            place-items: center;
            background: radial-gradient(circle, #1a3354, #0b1423 72%);
            color: #668bb9;
            font-size: 43px;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 9px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: #18243a;
            color: #bdcbe0;
            font-size: 10px;
            font-weight: 800;
        }

        .status::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .status-published,
        .status-confirmed,
        .status-completed {
            color: var(--green);
            background: rgba(55,214,160,.07);
            border-color: rgba(55,214,160,.18);
        }

        .status-draft,
        .status-pending {
            color: var(--yellow);
            background: rgba(255,202,104,.07);
            border-color: rgba(255,202,104,.18);
        }

        .status-cancelled {
            color: var(--red);
            background: rgba(255,100,116,.07);
            border-color: rgba(255,100,116,.18);
        }

        .motorcycle-status {
            position: absolute;
            top: 12px;
            left: 12px;
        }

        .motorcycle-body {
            padding: 19px;
        }

        .motorcycle-title {
            margin: 0 0 5px;
            font-size: 17px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .motorcycle-subtitle {
            color: var(--muted);
            font-size: 12px;
        }

        .motorcycle-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-top: 15px;
        }

        .meta-chip {
            padding: 5px 8px;
            border: 1px solid var(--border);
            border-radius: 7px;
            color: #aabbd2;
            background: #0c1626;
            font-size: 11px;
        }

        .motorcycle-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 19px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .price {
            color: #fff;
            font-size: 21px;
            font-weight: 850;
            letter-spacing: -.5px;
            overflow-wrap: anywhere;
        }

        .price small {
            color: var(--muted);
            font-size: 11px;
            font-weight: 500;
        }

        .card-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
        }

        .inline-form {
            display: inline;
            margin: 0;
        }

        .booking-list {
            display: grid;
            gap: 13px;
        }

        .booking-card {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 20px;
            padding: 21px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--panel);
        }

        .booking-top {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-bottom: 7px;
        }

        .booking-title {
            margin: 0;
            font-size: 16px;
            overflow-wrap: anywhere;
        }

        .booking-dates {
            display: flex;
            flex-wrap: wrap;
            gap: 9px 20px;
            margin-top: 15px;
            color: var(--muted);
            font-size: 12px;
        }

        .booking-dates strong {
            display: block;
            margin-top: 3px;
            color: #e5edf9;
            font-size: 13px;
        }

        .booking-total {
            min-width: 155px;
            align-self: center;
            padding-left: 22px;
            border-left: 1px solid var(--border);
            text-align: right;
        }

        .booking-total .price {
            margin-top: 5px;
            color: var(--blue-light);
        }

        .muted {
            color: var(--muted);
            font-size: 11px;
        }

        .empty {
            padding: 36px 22px;
            border: 1px dashed #2c4260;
            border-radius: var(--radius);
            background: rgba(17,28,46,.55);
            text-align: center;
        }

        .empty-icon {
            width: 55px;
            height: 55px;
            display: grid;
            place-items: center;
            margin: 0 auto 14px;
            border: 1px solid #2b4566;
            border-radius: 16px;
            background: #14233a;
            font-size: 23px;
        }

        .empty h3 {
            margin: 0 0 7px;
            font-size: 17px;
        }

        .empty p {
            max-width: 400px;
            margin: 0 auto 18px;
            color: var(--muted);
        }

        .pagination {
            margin-top: 22px;
            overflow-x: auto;
        }

        .pagination nav > div:first-child {
            color: var(--muted);
            font-size: 12px;
        }

        .pagination nav > div:last-child {
            margin-top: 12px;
        }

        .pagination a,
        .pagination span[aria-current],
        .pagination span[aria-disabled] {
            display: inline-block;
            padding: 7px 12px;
            margin: 0 3px 3px 0;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--panel);
            color: var(--muted);
        }

        .pagination span[aria-current] {
            background: var(--blue);
            border-color: var(--blue);
            color: white;
        }

        .footer {
            padding: 22px 38px;
            border-top: 1px solid var(--border);
            color: #71839d;
            font-size: 11px;
        }

        .footer-inner {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
            max-width: 1500px;
            margin: 0 auto;
        }

        @media (min-width: 1450px) {
            .motorcycle-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 1100px) {
            .sidebar {
                width: 215px;
            }

            .main {
                margin-left: 215px;
            }

            .content {
                padding: 27px 24px 42px;
            }

            .topbar {
                padding: 0 24px;
            }

            .profile-grid {
                grid-template-columns: 1fr;
            }

            .motorcycle-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 760px) {
            .sidebar {
                position: static;
                width: auto;
                padding: 12px 15px;
                border-right: 0;
                border-bottom: 1px solid var(--border);
            }

            .brand {
                padding: 0 3px 13px;
                font-size: 21px;
            }

            .brand-mark {
                width: 34px;
                height: 34px;
            }

            .menu-label,
            .sidebar-bottom {
                display: none;
            }

            .nav {
                display: flex;
                gap: 7px;
                padding-bottom: 3px;
                overflow-x: auto;
                scrollbar-width: thin;
            }

            .nav a {
                flex-shrink: 0;
                min-height: 38px;
                padding: 8px 11px;
                font-size: 12px;
            }

            .nav-icon {
                width: auto;
                font-size: 14px;
            }

            .nav a.active {
                box-shadow: inset 0 -2px var(--blue);
            }

            .main {
                margin-left: 0;
            }

            .topbar {
                height: 60px;
                padding: 0 17px;
            }

            .top-actions {
                gap: 10px;
            }

            .site-link {
                font-size: 11px;
            }

            .user-chip .user-caption {
                display: none;
            }

            .user-chip .avatar {
                width: 34px;
                height: 34px;
            }

            .content {
                padding: 20px 15px 35px;
            }

            .hero {
                padding: 23px 20px;
                border-radius: 16px;
            }

            .hero::after {
                right: 8px;
                top: -5px;
                font-size: 100px;
            }

            h1 {
                font-size: 27px;
            }

            .stats {
                gap: 9px;
            }

            .stat {
                padding: 13px 11px;
                border-radius: 13px;
            }

            .stat-label {
                font-size: 10px;
            }

            .stat-icon {
                display: none;
            }

            .stat-number {
                font-size: 24px;
            }

            .stat-note {
                font-size: 10px;
            }

            .section {
                margin-top: 30px;
            }

            .section-heading h2 {
                font-size: 19px;
            }

            .panel {
                padding: 18px;
            }

            .motorcycle-image {
                height: 205px;
            }

            .booking-card {
                grid-template-columns: 1fr;
                gap: 16px;
                padding: 17px;
            }

            .booking-total {
                min-width: 0;
                padding: 13px 0 0;
                border-left: 0;
                border-top: 1px solid var(--border);
                text-align: left;
            }

            .footer {
                padding: 20px 15px;
            }
        }

        @media (max-width: 390px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .stat {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .stat-number {
                margin-top: 0;
            }

            .motorcycle-footer {
                align-items: flex-start;
                flex-direction: column;
            }

            .hero-actions .btn {
                width: 100%;
            }

            .breadcrumb {
                font-size: 11px;
            }
        }
    
/* Premium personal profile */
.profile-section{padding-top:12px}
.profile-hero{position:relative;display:flex;align-items:center;justify-content:space-between;gap:20px;overflow:hidden;margin-bottom:18px;padding:25px 28px;border:1px solid #2b4c72;border-radius:18px;background:radial-gradient(ellipse at 85% 0%,#1677ff3a,transparent 48%),linear-gradient(115deg,#13243a,#0a1422 76%)}
.profile-hero-copy{position:relative;z-index:1}
.profile-hero h2{margin:8px 0 5px;font-size:clamp(25px,3vw,34px);font-weight:950;letter-spacing:-1px}
.profile-hero h2 span{color:#58aaff}
.profile-hero p{max-width:510px;margin:0;color:#9aacc5;font-size:12px;line-height:1.7}
.profile-hero-mark{position:absolute;right:24px;top:50%;transform:translateY(-50%);color:#ffffff08;font-size:112px;font-weight:1000;letter-spacing:-10px;pointer-events:none}
.profile-grid{grid-template-columns:minmax(0,.92fr) minmax(0,1.08fr);gap:18px}
.profile-identity-panel,.profile-edit-panel{padding:24px;border-color:#2a4565;background:linear-gradient(145deg,#121f31,#0a1421);box-shadow:0 16px 40px #0003}
.profile-person{gap:16px;margin-bottom:18px}
.profile-person .profile-large-avatar{width:76px;height:76px;flex:0 0 76px;border:2px solid #438bd0;border-radius:19px;background:linear-gradient(145deg,#1c4778,#10243f);box-shadow:0 0 25px #1677ff22;font-size:29px}
.profile-identity-copy{min-width:0}
.profile-kicker{display:block;margin-bottom:5px;color:#66b3ff;font-size:9px;font-weight:900;letter-spacing:1.5px}
.profile-identity-copy h3{font-size:20px!important;font-weight:900;letter-spacing:-.5px}
.profile-identity-copy p{font-size:11px;overflow-wrap:anywhere}
.profile-status-line{display:flex;align-items:center;gap:8px;margin:0 0 10px;padding:9px 11px;border:1px solid #235a4a;border-radius:8px;background:#103126;color:#8de5bd;font-size:10px;font-weight:800}
.profile-status-dot{width:7px;height:7px;border-radius:50%;background:#37d6a0;box-shadow:0 0 10px #37d6a0}
.profile-identity-panel .info-line{padding:12px 2px;border-color:#263c57}
.profile-identity-panel .info-value{font-size:13px}
.profile-edit-heading{display:flex;justify-content:space-between;gap:12px;align-items:flex-start;margin-bottom:13px}
.profile-edit-heading h3{margin:0 0 5px;font-size:18px;font-weight:900}
.profile-edit-heading .panel-description{margin:0;font-size:11px}
.profile-edit-icon{display:grid;place-items:center;width:37px;height:37px;border:1px solid #315982;border-radius:10px;background:#142e4b;color:#80c1ff;font-size:18px}
.profile-upload-row{display:flex;align-items:center;gap:15px;padding:14px;border:1px dashed #365c84;border-radius:12px;background:#0b1726}
.profile-upload-preview{display:grid;place-items:center;width:66px;height:66px;flex:0 0 66px;overflow:hidden;border:1px solid #3a7db7;border-radius:15px;background:#16365a;color:#a8d5ff;font-size:22px;font-weight:900}
.profile-upload-control{min-width:0;flex:1}
.profile-upload-control input[type=file]{width:100%;max-width:100%;color:#aebed3;font-size:11px}
.profile-upload-control small{display:block;margin-top:6px;color:#8198b5;font-size:10px}
.profile-error{color:#ff9aa7!important}
.profile-edit-panel .form-group{margin-top:18px}
.profile-edit-panel .form-group input:not([type=file]){min-height:48px;padding:12px 14px;border:1px solid #2b425f;border-radius:10px;background:#091522;color:#f4f8ff}
.profile-edit-panel .form-group input:focus{border-color:#2785ff;box-shadow:0 0 0 3px #2785ff20}
.profile-save-btn{width:100%;min-height:48px;margin-top:20px}
.profile-save-btn span{font-size:15px}
@media(max-width:900px){.profile-grid{grid-template-columns:1fr}}
@media(max-width:600px){.profile-hero{padding:20px 18px;border-radius:14px}.profile-hero-mark{right:8px;font-size:76px}.profile-grid{gap:12px}.profile-identity-panel,.profile-edit-panel{padding:17px;border-radius:13px}.profile-person .profile-large-avatar{width:64px;height:64px;flex-basis:64px}.profile-identity-copy h3{font-size:17px!important}.profile-upload-row{align-items:flex-start;flex-direction:column}.profile-upload-control{width:100%}}

</style>
</head>

<body>
@php
    $user = auth()->user();

    $motorcycleCount = $motorcycles->total();
    $bookingCount = $rentalBookings->total();

    $publishedCount = $motorcycles->getCollection()
        ->filter(fn ($motorcycle) => $motorcycle->status === 'published')
        ->count();

    $firstName = trim((string) $user->name);
    $initial = mb_substr($firstName, 0, 1, 'UTF-8');

    $statusLabels = [
        'published' => 'გამოქვეყნებული',
        'draft' => 'მონახაზი',
        'pending' => 'მოლოდინში',
        'confirmed' => 'დადასტურებული',
        'cancelled' => 'გაუქმებული',
        'completed' => 'დასრულებული',
    ];
@endphp

<div class="app">
    <aside class="sidebar">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-mark">M</span>
            <span>Moto<span>Hub</span></span>
        </a>

        <div class="menu-label">პირადი სივრცე</div>

        <nav class="nav" aria-label="პირადი კაბინეტის მენიუ">
            <a href="#overview" class="active">
                <span class="nav-icon">⌂</span>
                მთავარი
            </a>

            <a href="#motorcycles">
                <span class="nav-icon">🏍</span>
                ჩემი მოტოციკლები
            </a>

            <a href="#bookings">
                <span class="nav-icon">▦</span>
                გაქირავების ჯავშნები
            </a>

                <a href="{{ route('account.transport.index') }}" class="account-link">
                    <span aria-hidden="true">🏍️</span>
                    <span>ჩემი ტრანსპორტირება</span>
                </a>

            <a href="{{ route('account.service-bookings.index') }}">
    <span class="nav-icon">🛠</span>
    ჩემი სერვისის ჯავშნები
</a>

            <a href="#profile">
                <span class="nav-icon">◎</span>
                ჩემი პროფილი
            </a>

            <a href="{{ route('part-requests.create') }}">
                <span class="nav-icon">⚙</span>
                ნაწილების მოთხოვნა
            </a>
        </nav>

        <div class="sidebar-bottom">
            <div class="sidebar-card">
                <strong>გჭირდება დახმარება?</strong>
                <p>იპოვე საჭირო ნაწილები შენი მოტოციკლისთვის.</p>
                <a href="{{ route('parts.index') }}">ნაწილების ნახვა →</a>
            </div>
        </div>
    </aside>

    <main class="main">
        <header class="topbar">
            <div class="breadcrumb">
                Garage 33 <span> / </span> <strong>პირადი კაბინეტი</strong>
            </div>

            <div class="top-actions">
                <a class="site-link" href="{{ route('home') }}">
                    ← საიტის ნახვა
                </a>

                <div class="user-chip">
                    
                    @if ($user->avatar_path)
    <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="პროფილის ფოტო" class="avatar" style="object-fit: cover;">
@else
    <div class="avatar">{{ $initial }}</div>
@endif
                    <div>
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-caption">პირადი კაბინეტი</div>
                    </div>
                </div>
            </div>
        </header>

        <div class="content">
            <section class="hero" id="overview">
                <div class="eyebrow">Garage 33 / Dashboard</div>

                <h1>გამარჯობა, {{ $user->name }} 👋</h1>

                <p>
                    მართე შენი განცხადებები, აკონტროლე ჯავშნები
                    და განაახლე პირადი ინფორმაცია ერთ სივრცეში.
                </p>

                <div class="hero-actions">
                    <a href="{{ route('account.motorcycles.create') }}"
                       class="btn btn-primary">
                        + განცხადების დამატება
                    </a>

                    <a href="#motorcycles" class="btn">
                        ჩემი განცხადებები →
                    </a>
                </div>
            </section>

            @if (session('success'))
                <div class="notice" role="status">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <section class="stats" aria-label="სტატისტიკა">
                <div class="stat">
                    <div>
                        <div class="stat-label">ჩემი განცხადებები</div>
                        <span class="stat-number">{{ $motorcycleCount }}</span>
                        <span class="stat-note">ყველა განცხადება</span>
                    </div>
                    <div class="stat-icon">🏍</div>
                </div>

                <div class="stat">
                    <div>
                        <div class="stat-label">გამოქვეყნებული</div>
                        <span class="stat-number">{{ $publishedCount }}</span>
                        <span class="stat-note">მიმდინარე გვერდზე</span>
                    </div>
                    <div class="stat-icon">↗</div>
                </div>

                <div class="stat">
                    <div>
                        <div class="stat-label">გაქირავების ჯავშნები</div>
                        <span class="stat-number">{{ $bookingCount }}</span>
                        <span class="stat-note">ყველა ჯავშანი</span>
                    </div>
                    <div class="stat-icon">▦</div>
                </div>
            </section>

            <section class="section profile-section" id="profile">
                <div class="profile-hero">
                    <div class="profile-hero-copy">
                        <span class="eyebrow">MOTOHUB / PERSONAL SPACE</span>
                        <h2>შენი პროფილი<span>.</span></h2>
                        <p>მართე შენი პირადი ინფორმაცია და საკონტაქტო მონაცემები ერთი სივრციდან.</p>
                    </div>
                    <div class="profile-hero-mark">MH</div>
                </div>
                <div class="profile-grid">
                    <div class="panel profile-identity-panel">
                        <div class="profile-person">
                            @if ($user->avatar_path)
                                <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="პროფილის ფოტო" class="avatar profile-large-avatar" style="object-fit:cover;">
                            @else
                                <div class="avatar profile-large-avatar">{{ $initial }}</div>
                            @endif
                            <div class="profile-identity-copy">
                                <span class="profile-kicker">MOTOHUB MEMBER</span>
                                <h3>{{ $user->name }}</h3>
                                <p>{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="profile-status-line"><span class="profile-status-dot"></span> ანგარიში აქტიურია</div>
                        <div class="info-line"><span class="info-label">სახელი და გვარი</span><span class="info-value">{{ $user->name }}</span></div>
                        <div class="info-line"><span class="info-label">ელფოსტა</span><span class="info-value">{{ $user->email }}</span></div>
                        <div class="info-line"><span class="info-label">ტელეფონი</span><span class="info-value">{{ $user->phone ?: 'ტელეფონი ჯერ არ არის მითითებული' }}</span></div>
                        <div class="info-line"><span class="info-label">MotoHub წევრი</span><span class="info-value">{{ $user->created_at?->format('d.m.Y') ?? '—' }}</span></div>
                    </div>
                    <div class="panel profile-edit-panel">
                        <div class="profile-edit-heading"><div><span class="profile-kicker">ACCOUNT SETTINGS</span><h3>პირადი მონაცემების მართვა</h3><p class="panel-description">განაახლე პროფილის ფოტო და ტელეფონის ნომერი.</p></div><span class="profile-edit-icon">✎</span></div>
                        <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group profile-photo-field">
                                <label for="avatar">პროფილის ფოტო</label>
                                <div class="profile-upload-row">
                                    <div class="profile-upload-preview">
                                        @if ($user->avatar_path)
                                            <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="პროფილის ფოტო" style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                            <span>{{ $initial }}</span>
                                        @endif
                                    </div>
                                    <div class="profile-upload-control">
                                        <input type="file" id="avatar" name="avatar" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                                        <small>JPG, PNG ან WEBP · მაქსიმუმ 5 მბ</small>
                                        @error('avatar')<span class="form-help profile-error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group"><label for="phone">ტელეფონის ნომერი</label><input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+995 5XX XX XX XX" autocomplete="tel">@error('phone')<span class="form-help profile-error">{{ $message }}</span>@enderror<span class="form-help">ნომერი გამოიყენება შენთან დასაკავშირებლად.</span></div>
                            <button type="submit" class="btn btn-primary profile-save-btn"><span>✓</span> ცვლილებების შენახვა</button>
                        </form>
                    </div>
                </div>
            </section>
            <section class="section" id="motorcycles">
                <div class="section-heading">
                    <div>
                        <h2>ჩემი მოტოციკლები</h2>
                        <p>შენი განცხადებების მართვა</p>
                    </div>

                    <span class="section-count">
                        სულ {{ $motorcycleCount }}
                    </span>
                </div>

                @if ($motorcycles->count())
                    <div class="motorcycle-grid">
                        @foreach ($motorcycles as $motorcycle)
                            <article class="motorcycle-card">
                                <div class="motorcycle-image">
                                    @if ($motorcycle->image_path)
                                        <img
                                            src="{{ \Illuminate\Support\Facades\Storage::url($motorcycle->image_path) }}"
                                            alt="{{ $motorcycle->title }}"
                                            loading="lazy"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';"
                                        >
                                        <div class="image-placeholder"
                                             style="display:none">🏍</div>
                                    @else
                                        <div class="image-placeholder">🏍</div>
                                    @endif

                                    <span class="status motorcycle-status status-{{ in_array($motorcycle->status, ['published', 'draft'], true) ? $motorcycle->status : 'draft' }}">
                                        {{ $statusLabels[$motorcycle->status] ?? 'უცნობი სტატუსი' }}
                                    </span>
                                </div>

                                <div class="motorcycle-body">
                                    <h3 class="motorcycle-title">
                                        {{ $motorcycle->title }}
                                    </h3>

                                    <div class="motorcycle-subtitle">
                                        {{ $motorcycle->brand }}
                                        {{ $motorcycle->model }}
                                    </div>

                                    <div class="motorcycle-meta">
                                        @if ($motorcycle->year)
                                            <span class="meta-chip">
                                                {{ $motorcycle->year }} წელი
                                            </span>
                                        @endif

                                        <span class="meta-chip">
                                            Garage 33 განცხადება
                                        </span>
                                    </div>

                                    <div class="motorcycle-footer">
                                        <div>
                                            <div class="muted">ფასი</div>
                                            <div class="price">
                                                @if (is_numeric($motorcycle->price))
                                                    {{ number_format((float) $motorcycle->price, 0, '.', ' ') }}
                                                    <small>₾</small>
                                                @else
                                                    <small>ფასი შეთანხმებით</small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="card-actions">
                                            <a
                                                href="{{ route('account.motorcycles.edit', $motorcycle) }}"
                                                class="btn btn-small"
                                            >
                                                რედაქტირება
                                            </a>
                                            <a
                                                href="{{ route('account.motorcycles.service.index', $motorcycle->id) }}"
                                                class="btn btn-small"
                                            >
                                                🔧 სერვისის ისტორია
                                            </a>
                                            <form
                                                action="{{ route('account.motorcycles.destroy', $motorcycle) }}"
                                                method="POST"
                                                class="inline-form"
                                                onsubmit="return confirm('ნამდვილად გსურს ამ განცხადების წაშლა?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-small btn-danger">
                                                    წაშლა
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="pagination">
                        {{ $motorcycles->links() }}
                    </div>
                @else
                    <div class="empty">
                        <div class="empty-icon">🏍</div>
                        <h3>ჯერ განცხადებები არ გაქვს</h3>
                        <p>
                            დაამატე პირველი მოტოციკლი და მართე შენი
                            განცხადებები პირადი კაბინეტიდან.
                        </p>

                        <a href="{{ route('account.motorcycles.create') }}"
                           class="btn btn-primary">
                            + პირველი განცხადების დამატება
                        </a>
                    </div>
                @endif
            </section>

            <section class="section" id="bookings">
                <div class="section-heading">
                    <div>
                        <h2>გაქირავების ჯავშნები</h2>
                        <p>შენი გაქირავების შეკვეთების ინფორმაცია</p>
                    </div>

                    <span class="section-count">
                        სულ {{ $bookingCount }}
                    </span>
                </div>

                @if ($rentalBookings->count())
                    <div class="booking-list">
                        @foreach ($rentalBookings as $booking)
                            @php
                                $bookingStatus = $booking->status;
                                $bookingStatusClass = in_array(
                                    $bookingStatus,
                                    ['pending', 'confirmed', 'cancelled', 'completed'],
                                    true
                                ) ? $bookingStatus : 'pending';
                            @endphp

                            <article class="booking-card">
                                <div>
                                    <div class="booking-top">
                                        <h3 class="booking-title">
                                            {{ $booking->rentalScooter?->title ?? 'მოპედის გაქირავება' }}
                                        </h3>

                                        <span class="status status-{{ $bookingStatusClass }}">
                                            {{ $statusLabels[$bookingStatus] ?? 'უცნობი სტატუსი' }}
                                        </span>
                                    </div>

                                    <div class="muted">
                                        ჯავშნის დეტალები
                                    </div>

                                    <div class="booking-dates">
                                        <div>
                                            დაწყების თარიღი
                                            <strong>
                                                {{ $booking->start_date?->format('d.m.Y') ?? '—' }}
                                            </strong>
                                        </div>

                                        <div>
                                            დასრულების თარიღი
                                            <strong>
                                                {{ $booking->end_date?->format('d.m.Y') ?? '—' }}
                                            </strong>
                                        </div>

                                        <div>
                                            ხანგრძლივობა
                                            <strong>
                                                {{ $booking->days }} დღე
                                            </strong>
                                        </div>

                                        <div>
                                            დღიური ფასი
                                            <strong>
                                                {{ number_format((float) $booking->daily_rate, 2, '.', ' ') }} ₾
                                            </strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="booking-total">
                                    <div class="muted">ჯამური ღირებულება</div>
                                    <div class="price">
                                        {{ number_format((float) $booking->total_price, 2, '.', ' ') }}
                                        ₾
                                    </div>
                                    <div class="muted">
                                        სრული პერიოდისთვის
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="pagination">
                        {{ $rentalBookings->links() }}
                    </div>
                @else
                    <div class="empty">
                        <div class="empty-icon">▦</div>
                        <h3>ჯავშნები ჯერ არ გაქვს</h3>
                        <p>
                            დაათვალიერე ხელმისაწვდომი გასაქირავებელი
                            მოპედები და შეარჩიე სასურველი ვარიანტი.
                        </p>

                        <a href="{{ route('rentals.index') }}"
                           class="btn btn-primary">
                            გაქირავების ნახვა →
                        </a>
                    </div>
                @endif
            </section>
        </div>

        <footer class="footer">
            <div class="footer-inner">
                <span>© {{ date('Y') }} Garage 33</span>
                <span>შენი მოტო-სივრცე</span>
            </div>
        </footer>
    </main>
</div>
</body>
</html>
