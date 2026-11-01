
<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>მოტოციკლების კატალოგი | MotoHub</title>
    <meta name="description" content="შეარჩიე მოტოციკლი MotoHub-ზე. გაფილტრე განცხადებები მარკის, ფასის, წლისა და მდებარეობის მიხედვით.">
    <meta name="theme-color" content="#080d18">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+Georgian:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #080d18;
            --surface: #101827;
            --surface-light: #151f30;
            --border: rgba(153, 180, 220, .14);
            --text: #f4f7ff;
            --muted: #91a1ba;
            --blue: #1677ff;
            --blue-light: #80b8ff;
            --green: #52d6a2;
            --radius: 16px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            color: var(--text);
            background:
                radial-gradient(ellipse at 50% 0%, rgba(22,119,255,.13), transparent 38%),
                var(--bg);
            font-family: 'Inter', 'Noto Sans Georgian', sans-serif;
            line-height: 1.65;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button, input, select {
            font: inherit;
        }

        button, a, select {
            -webkit-tap-highlight-color: transparent;
        }

        .container {
            width: min(1280px, calc(100% - 48px));
            margin: 0 auto;
        }

        /* Navigation */

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            border-bottom: 1px solid var(--border);
            background: rgba(8,13,24,.88);
            backdrop-filter: blur(18px);
        }

        .nav {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .logo {
            flex-shrink: 0;
            font-size: 27px;
            font-weight: 900;
            letter-spacing: -1.5px;
        }

        .logo span,
        .footer-brand span {
            color: var(--blue);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 27px;
            color: #b6c4d9;
            font-size: 13px;
            font-weight: 600;
        }

        .nav-links a {
            transition: color .2s, background .2s;
        }

        .nav-links a:hover {
            color: var(--blue-light);
        }

        .nav-links .nav-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 43px;
            padding: 0 17px;
            border: 1px solid rgba(22,119,255,.45);
            border-radius: 9px;
            background: rgba(22,119,255,.12);
            color: #e0edff;
        }

        .nav-links .nav-button:hover {
            background: var(--blue);
            color: white;
        }

        /* Hero */

        .hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            padding: 76px 0 66px;
            border-bottom: 1px solid var(--border);
            background:
                radial-gradient(ellipse at 82% 30%, rgba(22,119,255,.24), transparent 37%),
                linear-gradient(115deg, #111d31, #0b1321 75%);
        }

        .hero::after {
            content: "MOTOHUB";
            position: absolute;
            z-index: -1;
            right: -10px;
            bottom: -37px;
            color: rgba(150,190,255,.045);
            font-size: clamp(80px, 14vw, 175px);
            font-weight: 900;
            letter-spacing: -9px;
            line-height: 1;
            pointer-events: none;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--blue-light);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: "";
            width: 26px;
            height: 2px;
            background: var(--blue);
        }

        .hero h1 {
            max-width: 850px;
            margin: 21px 0 17px;
            font-size: clamp(38px, 5.4vw, 68px);
            line-height: 1.17;
            letter-spacing: -2.5px;
            font-weight: 900;
        }

        .hero h1 span {
            color: var(--blue-light);
        }

        .hero-description {
            max-width: 610px;
            margin: 0;
            color: #a6b5cc;
            font-size: 14px;
            line-height: 2;
        }

        .hero-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 18px;
            margin-top: 27px;
        }

        .hero-note {
            color: var(--muted);
            font-size: 12px;
        }

        .hero-note strong {
            margin-right: 7px;
            color: white;
            font-size: 25px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 10px 14px;
            border: 1px solid rgba(128,184,255,.2);
            border-radius: 9px;
            background: rgba(8,13,24,.35);
            color: #c9ddff;
            font-size: 11px;
            font-weight: 700;
        }

        .hero-badge::before {
            content: "";
            width: 7px;
            height: 7px;
            flex-shrink: 0;
            border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 12px rgba(82,214,162,.4);
        }

        /* Main layout */

        .main-content {
            padding-top: 36px;
        }

        .layout {
            display: grid;
            grid-template-columns: 265px minmax(0, 1fr);
            align-items: start;
            gap: 26px;
            padding-bottom: 75px;
        }

        /* Filters */

        .filters {
            position: sticky;
            top: 96px;
            padding: 23px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: linear-gradient(145deg, #121d2d, #0d1522);
            box-shadow: 0 18px 45px rgba(0,0,0,.13);
        }

        .filters-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 22px;
            padding-bottom: 17px;
            border-bottom: 1px solid var(--border);
        }

        .filters-heading h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
        }

        .filter-symbol {
            display: grid;
            place-items: center;
            width: 36px;
            height: 36px;
            border: 1px solid rgba(22,119,255,.25);
            border-radius: 10px;
            background: rgba(22,119,255,.12);
            color: var(--blue-light);
            font-size: 20px;
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            color: #c7d3e5;
            font-size: 12px;
            font-weight: 600;
        }

        .field input,
        .field select {
            width: 100%;
            min-height: 45px;
            padding: 10px 12px;
            border: 1px solid #29374c;
            border-radius: 9px;
            outline: none;
            background: #0a1220;
            color: var(--text);
            font-size: 12px;
            transition: border-color .2s, box-shadow .2s;
        }

        .field input::placeholder {
            color: #72829a;
        }

        .field input:focus,
        .field select:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(22,119,255,.13);
        }

        .range {
            display: grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap: 8px;
        }

        .primary-button,
        .reset-button {
            display: flex;
            width: 100%;
            min-height: 45px;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 800;
            transition: .2s;
        }

        .primary-button {
            border: 1px solid var(--blue);
            background: linear-gradient(135deg, #398cff, #1263df);
            color: white;
            box-shadow: 0 8px 22px rgba(22,119,255,.18);
        }

        .primary-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(22,119,255,.25);
        }

        .reset-button {
            margin-top: 9px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,.025);
            color: #b8c6da;
        }

        .reset-button:hover {
            border-color: #526682;
            background: rgba(255,255,255,.05);
        }

        /* Results */

        .results-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 18px;
            margin-bottom: 21px;
        }

        .results-kicker {
            margin-bottom: 5px;
            color: var(--blue-light);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .results-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -.7px;
        }

        .results-header p {
            margin: 5px 0 0;
            color: var(--muted);
            font-size: 12px;
        }

        .results-header p strong {
            color: #e0eaff;
        }

        .sort select {
            max-width: 210px;
            min-height: 43px;
            padding: 9px 30px 9px 12px;
            border: 1px solid var(--border);
            border-radius: 9px;
            outline: none;
            background: #111a29;
            color: #dce7f8;
            font-size: 11px;
        }

        .sort select:focus {
            border-color: var(--blue);
        }

        /* Motorcycle cards */

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0,1fr));
            gap: 17px;
        }

        .card {
            display: block;
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: linear-gradient(155deg, #131e2e, #0d1521);
            box-shadow: 0 8px 26px rgba(0,0,0,.09);
            transition: transform .25s, border-color .25s, box-shadow .25s;
        }

        .card:hover {
            transform: translateY(-5px);
            border-color: rgba(22,119,255,.7);
            box-shadow: 0 20px 40px rgba(0,0,0,.23);
        }

        .image-wrap {
            position: relative;
            height: 205px;
            overflow: hidden;
            background: radial-gradient(ellipse, #263951, #101827);
        }

        .image-wrap img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .45s;
        }

        .card:hover .image-wrap img {
            transform: scale(1.06);
        }

        .image-shade {
            position: absolute;
            inset: 45% 0 0;
            background: linear-gradient(transparent, rgba(5,10,18,.48));
            pointer-events: none;
        }

        .no-image {
            display: flex;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 8px;
            color: #a2b2c9;
            font-size: 12px;
        }

        .no-image-icon {
            font-size: 32px;
            opacity: .75;
        }

        .featured {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 6px 9px;
            border: 1px solid rgba(128,184,255,.35);
            border-radius: 6px;
            background: rgba(7,17,33,.88);
            color: #c0ddff;
            font-size: 10px;
            font-weight: 800;
            backdrop-filter: blur(8px);
        }

        .condition {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--blue-light);
            font-size: 10px;
            font-weight: 800;
        }

        .condition::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--blue);
        }

        .card-body {
            padding: 17px;
        }

        .card h3 {
            margin: 8px 0 13px;
            overflow-wrap: anywhere;
            font-size: 16px;
            line-height: 1.5;
            font-weight: 800;
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            color: #a0aec2;
            font-size: 10px;
        }

        .meta span {
            padding: 4px 7px;
            border: 1px solid rgba(166,190,225,.09);
            border-radius: 5px;
            background: rgba(255,255,255,.025);
        }

        .card-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 17px;
            padding-top: 14px;
            border-top: 1px solid var(--border);
        }

        .price-label {
            display: block;
            margin-bottom: 3px;
            color: #8292a9;
            font-size: 10px;
        }

        .price {
            color: #f5f8ff;
            font-size: 20px;
            font-weight: 900;
            letter-spacing: -.5px;
        }

        .price-currency {
            color: var(--blue-light);
            font-size: 13px;
        }

        .details {
            display: inline-flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-height: 37px;
            padding: 0 11px;
            border: 1px solid rgba(22,119,255,.3);
            border-radius: 8px;
            background: rgba(22,119,255,.1);
            color: #b6d5ff;
            font-size: 10px;
            font-weight: 800;
            transition: .2s;
        }

        .card:hover .details {
            background: var(--blue);
            color: white;
        }

        /* Empty state */

        .empty {
            grid-column: 1 / -1;
            padding: 55px 20px;
            border: 1px dashed #35455e;
            border-radius: 14px;
            background: rgba(16,24,39,.65);
            color: var(--muted);
            text-align: center;
        }

        .empty-icon {
            margin-bottom: 12px;
            color: var(--blue-light);
            font-size: 34px;
        }

        .empty h3 {
            margin: 0 0 8px;
            color: var(--text);
            font-size: 19px;
        }

        .empty p {
            margin: 0 0 18px;
            font-size: 12px;
        }

        .empty .details {
            display: inline-flex;
        }

        /* Pagination */

        .pagination {
            margin-top: 28px;
            overflow-x: auto;
        }

        .pagination nav {
            display: flex;
            justify-content: center;
            min-width: max-content;
        }

        .pagination nav > div:first-child {
            display: none;
        }

        .pagination nav > div:last-child {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
            gap: 6px;
        }

        .pagination nav > div:last-child > span,
        .pagination nav > div:last-child > a {
            display: inline-flex;
            min-width: 37px;
            min-height: 37px;
            align-items: center;
            justify-content: center;
            padding: 7px 11px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: var(--surface);
            color: #c6d2e5;
            font-size: 12px;
        }

        .pagination nav > div:last-child > a:hover {
            border-color: var(--blue);
            color: white;
        }

        /* Footer */

        footer {
            padding: 25px 0;
            border-top: 1px solid var(--border);
            background: #070c14;
            color: #8999b1;
            font-size: 11px;
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .footer-brand {
            font-size: 17px;
            font-weight: 900;
            letter-spacing: -.7px;
        }

        /* Responsive */


        /* MotoHub catalogue refresh */
        .filters{border-color:#345071;box-shadow:0 22px 55px #0004}
        .filters-heading{margin-bottom:18px}
        .filters-heading h2{font-size:19px;letter-spacing:-.4px}
        .field{margin-bottom:17px}
        .field label{font-size:11px;letter-spacing:.15px}
        .field input,.field select{min-height:47px;border-color:#2d4260;background:linear-gradient(145deg,#0a1422,#0b111d);font-size:12px}
        .field input:focus,.field select:focus{border-color:#4b9cff;box-shadow:0 0 0 3px #1677ff20,0 0 22px #1677ff12}
        .featured-filter{display:flex;align-items:center;gap:11px;margin:4px 0 20px;padding:13px;border:1px solid #31527a;border-radius:11px;background:linear-gradient(110deg,#132b49,#101c2d);cursor:pointer}
        .featured-filter input{position:absolute;opacity:0;pointer-events:none}
        .featured-check{display:grid;place-items:center;width:19px;height:19px;flex:0 0 19px;border:1px solid #57779e;border-radius:5px;background:#091321}
        .featured-filter input:checked+.featured-check{border-color:#4da0ff;background:#1677ff}
        .featured-filter input:checked+.featured-check:after{content:'✓';font-size:12px;font-weight:900;color:white}
        .featured-filter strong,.featured-filter small{display:block}
        .featured-filter strong{font-size:11px;color:#e9f2ff}
        .featured-filter small{margin-top:2px;color:#8ea4c2;font-size:9px}
        .catalog-toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:-3px 0 16px}
        .quick-filters{display:flex;flex-wrap:wrap;gap:7px}
        .quick-chip{display:inline-flex;align-items:center;justify-content:center;min-height:34px;padding:0 12px;border:1px solid #2b405b;border-radius:99px;background:#101a29;color:#aebed4;font-size:10px;font-weight:800;transition:.2s}
        .quick-chip:hover,.quick-chip.active{border-color:#398eff;background:#1677ff20;color:#dcecff;box-shadow:0 0 16px #1677ff12}
        .result-count{white-space:nowrap;color:#8fa4c0;font-size:10px;font-weight:700}
        .active-filters{display:flex;align-items:center;flex-wrap:wrap;gap:7px;margin:-3px 0 17px;color:#8ea1ba;font-size:10px}
        .active-filter{padding:5px 8px;border:1px solid #31527a;border-radius:6px;background:#12233a;color:#c6dcfa}
        .active-filters>a{color:#72b5ff;font-weight:800}
        .results-header{padding:17px 18px;border:1px solid #263d5c;border-radius:14px;background:linear-gradient(120deg,#111f33,#0c1523);margin-bottom:17px}
        .results-header h2{font-size:clamp(21px,3vw,27px)}
        .sort select{border-color:#314968;background:#0b1524;min-height:45px}
        .grid{gap:19px}
        .card{border-color:#263d5a;border-radius:16px;background:linear-gradient(155deg,#142238,#0b1421);box-shadow:0 12px 32px #0003}
        .card:hover{border-color:#4b9cff;box-shadow:0 22px 45px #0005,0 0 0 1px #1677ff20}
        .image-wrap{height:220px;background:radial-gradient(ellipse,#294563,#101827)}
        .card-body{padding:18px}
        .card h3{font-size:16px;min-height:48px}
        .price{color:#eaf3ff;text-shadow:0 0 18px #1677ff20}
        .details{border-color:#398eff66;background:#1677ff18}
        .pagination{padding:14px;border:1px solid #263d5a;border-radius:13px;background:#0d1725}
        .empty{padding:65px 20px;border-color:#3b5271;background:linear-gradient(145deg,#111f32,#0b1421)}
        @media(max-width:760px){
            .filters{position:relative;padding:17px;border-radius:15px}
            .filters-heading{margin-bottom:14px}
            .catalog-toolbar{align-items:flex-start;flex-direction:column}
            .quick-filters{width:100%}
            .quick-chip{flex:1;padding:0 9px}
            .results-header{padding:15px}
            .grid{gap:12px}
            .image-wrap{height:170px}
        }
        @media(max-width:420px){.image-wrap{height:205px}.card h3{min-height:0}}

        @media (max-width: 1120px) {
            .layout {
                grid-template-columns: 245px minmax(0,1fr);
                gap: 20px;
            }

            .grid {
                grid-template-columns: repeat(2,minmax(0,1fr));
            }

            .image-wrap {
                height: 195px;
            }
        }

        @media (max-width: 760px) {
            .container {
                width: calc(100% - 30px);
            }

            .nav {
                min-height: 66px;
                gap: 12px;
            }

            .logo {
                font-size: 23px;
            }

            .nav-links {
                gap: 12px;
                font-size: 11px;
            }

            .nav-links .optional {
                display: none;
            }

            .nav-links .nav-button {
                min-height: 38px;
                padding: 0 10px;
            }

            .hero {
                padding: 47px 0 42px;
            }

            .hero h1 {
                font-size: clamp(35px,8vw,49px);
                letter-spacing: -1.5px;
            }

            .hero-description {
                font-size: 12px;
            }

            .hero-bottom {
                align-items: flex-start;
                flex-direction: column;
            }

            .hero::after {
                right: -25px;
                bottom: -10px;
                font-size: 70px;
                letter-spacing: -4px;
            }

            .main-content {
                padding-top: 24px;
            }

            .layout {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .filters {
                position: static;
                padding: 18px;
            }

            .grid {
                grid-template-columns: repeat(2,minmax(0,1fr));
                gap: 12px;
            }

            .image-wrap {
                height: 150px;
            }

            .card-body {
                padding: 12px;
            }

            .card h3 {
                font-size: 13px;
            }

            .meta {
                gap: 5px;
            }

            .meta span {
                padding: 3px 5px;
                font-size: 9px;
            }

            .price {
                font-size: 17px;
            }

            .details {
                min-height: 32px;
                padding: 0 7px;
                font-size: 9px;
            }

            .results-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .sort,
            .sort select {
                width: 100%;
                max-width: none;
            }

            .footer-inner {
                align-items: flex-start;
                flex-direction: column;
                gap: 7px;
            }
        }

        @media (max-width: 420px) {
            .container {
                width: calc(100% - 24px);
            }

            .nav-links {
                gap: 8px;
                font-size: 10px;
            }

            .nav-links .nav-button {
                padding: 0 8px;
                font-size: 10px;
            }

            .grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .image-wrap {
                height: 220px;
            }

            .card-body {
                padding: 15px;
            }

            .card h3 {
                font-size: 16px;
            }

            .price {
                font-size: 20px;
            }

            .details {
                min-height: 37px;
                padding: 0 11px;
                font-size: 10px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }
    

/* MotoHub catalogue — responsive refinement */
@media (max-width: 760px) {
    .filters { border-radius: 14px; box-shadow: 0 12px 30px rgba(0,0,0,.2); }
    .filters form { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 0 12px; }
    .filters form .field:first-child,
    .filters form .featured-filter,
    .filters form > button,
    .filters form > .reset-button { grid-column: 1 / -1; }
    .filters form .field { min-width: 0; }
    .filters form .range { grid-template-columns: repeat(2,minmax(0,1fr)); }
    .results-header { gap: 12px; }
    .results-header h2 { font-size: 20px; }
    .sort { margin-top: 4px; }
    .active-filters { line-height: 1.7; }
}
@media (max-width: 420px) {
    .filters form { grid-template-columns: 1fr; }
    .filters form .field:first-child,
    .filters form .featured-filter,
    .filters form > button,
    .filters form > .reset-button { grid-column: auto; }
}

    </style>
</head>

<body>

<header class="topbar">
    <div class="container nav">
        <a class="logo" href="{{ route('home') }}">Moto<span>Hub</span></a>

        <nav class="nav-links" aria-label="მთავარი ნავიგაცია">
            <a href="{{ route('home') }}">მთავარი</a>
            <a href="{{ route('motorcycles.index') }}">მოტოციკლები</a>
            <a class="optional" href="{{ route('parts.index') }}">ნაწილები</a><a class="optional" href="{{ route('rentals.index') }}">გაქირავება</a><a class="optional" href="{{ route('service-centers.index') }}">სახელოსნოები</a><a class="optional" href="{{ route('moto-assistant.index') }}">მოტო ასისტენტი</a>

            @auth
                <a class="nav-button" href="{{ route('account.index') }}">ჩემი ანგარიში</a>
            @else
                <a class="nav-button" href="{{ route('login') }}">შესვლა</a>
            @endauth
        </nav>
    </div>
</header>

<section class="hero">
    <div class="container">
        <span class="eyebrow">MotoHub Marketplace</span>

        <h1>
            იპოვე შენი შემდეგი<br>
            <span>მოტოციკლი.</span>
        </h1>

        <p class="hero-description">
            დაათვალიერე მოტოციკლების განცხადებები, გამოიყენე ფილტრები
            და შეარჩიე მოდელი, რომელიც შენს სტილსა და ბიუჯეტს შეესაბამება.
        </p>

        <div class="hero-bottom">
            <div class="hero-note">
                <strong>{{ $motorcycles->total() }}</strong>
                განცხადება კატალოგში
            </div>

            <div class="hero-badge">შეარჩიე შენი შემდეგი თავგადასავალი</div>
        </div>
    </div>
</section>

<main class="container main-content">
    <div class="layout">

        <aside class="filters">
            <div class="filters-heading">
                <h2>ფილტრები</h2>
                <span class="filter-symbol" aria-hidden="true">⌕</span>
            </div>

            <form method="GET" action="{{ route('motorcycles.index') }}">
                <div class="field">
                    <label for="q">მოძებნე მოტოციკლი</label>
                    <input
                        id="q"
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="მარკა ან მოდელი"
                    >
                </div>

                <div class="field">
                    <label for="brand">მარკა</label>
                    <select id="brand" name="brand">
                        <option value="">ყველა მარკა</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand }}" @selected(request('brand') === $brand)>
                                {{ $brand }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>ფასი (₾)</label>
                    <div class="range">
                        <input
                            type="number"
                            name="min_price"
                            min="0"
                            value="{{ request('min_price') }}"
                            placeholder="დან"
                            aria-label="მინიმალური ფასი"
                        >
                        <input
                            type="number"
                            name="max_price"
                            min="0"
                            value="{{ request('max_price') }}"
                            placeholder="მდე"
                            aria-label="მაქსიმალური ფასი"
                        >
                    </div>
                </div>

                <div class="field">
                    <label>გამოშვების წელი</label>
                    <div class="range">
                        <input
                            type="number"
                            name="min_year"
                            min="1900"
                            max="2100"
                            value="{{ request('min_year') }}"
                            placeholder="დან"
                            aria-label="მინიმალური წელი"
                        >
                        <input
                            type="number"
                            name="max_year"
                            min="1900"
                            max="2100"
                            value="{{ request('max_year') }}"
                            placeholder="მდე"
                            aria-label="მაქსიმალური წელი"
                        >
                    </div>
                </div>

                <div class="field">
                    <label>ძრავის მოცულობა (cc)</label>
                    <div class="range">
                        <input type="number" name="min_cc" min="0" value="{{ request('min_cc') }}" placeholder="დან" aria-label="მინიმალური ძრავის მოცულობა">
                        <input type="number" name="max_cc" min="0" value="{{ request('max_cc') }}" placeholder="მდე" aria-label="მაქსიმალური ძრავის მოცულობა">
                    </div>
                </div>

                <div class="field">
                    <label>გარბენი (კმ)</label>
                    <div class="range">
                        <input type="number" name="min_mileage" min="0" value="{{ request('min_mileage') }}" placeholder="დან" aria-label="მინიმალური გარბენი">
                        <input type="number" name="max_mileage" min="0" value="{{ request('max_mileage') }}" placeholder="მდე" aria-label="მაქსიმალური გარბენი">
                    </div>
                </div>

                <label class="featured-filter">
                    <input type="checkbox" name="featured" value="1" @checked(request()->boolean('featured'))>
                    <span class="featured-check"></span>
                    <span><strong>მხოლოდ გამორჩეული</strong><small>პრემიუმ განცხადებები</small></span>
                </label>

                <div class="field">
                    <label for="type">მდგომარეობა</label>
                    <select id="type" name="type">
                        <option value="">ყველა მდგომარეობა</option>
                        <option value="new" @selected(request('type') === 'new')>ახალი</option>
                        <option value="used" @selected(request('type') === 'used')>მეორადი</option>
                    </select>
                </div>

                <div class="field">
                    <label for="location">მდებარეობა</label>
                    <input
                        id="location"
                        type="text"
                        name="location"
                        value="{{ request('location') }}"
                        placeholder="მაგ. თბილისი"
                    >
                </div>

                <button class="primary-button" type="submit">
                    მოტოციკლის ძიება ↗
                </button>

                <a class="reset-button" href="{{ route('motorcycles.index') }}">
                    ფილტრების გასუფთავება
                </a>
            </form>
        </aside>

        <section class="results">
            <div class="results-header">
                <div>
                    <div class="results-kicker">The Collection</div>
                    <h2>მოტოციკლების კატალოგი</h2>
                    <p>
                        ნაპოვნია
                        <strong>{{ $motorcycles->total() }}</strong>
                        განცხადება
                    </p>
                </div>

                <form class="sort" method="GET" action="{{ route('motorcycles.index') }}">
                    @foreach (request()->except(['sort', 'page']) as $key => $value)
                        @if (is_scalar($value) && $value !== '')
                            <input
                                type="hidden"
                                name="{{ $key }}"
                                value="{{ $value }}"
                            >
                        @endif
                    @endforeach

                    <select
                        name="sort"
                        aria-label="განცხადებების დალაგება"
                        onchange="this.form.submit()"
                    >
                        <option value="">რეკომენდებული</option>
                        <option value="price_asc" @selected(request('sort') === 'price_asc')>
                            ფასი: ზრდადობით
                        </option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>
                            ფასი: კლებადობით
                        </option>
                        <option value="year_desc" @selected(request('sort') === 'year_desc')>
                            უახლესი წელი
                        </option>
                        <option value="mileage_asc" @selected(request('sort') === 'mileage_asc')>გარბენი: ნაკლებიდან</option>
                        <option value="mileage_desc" @selected(request('sort') === 'mileage_desc')>გარბენი: მეტიდან</option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>უძველესი განცხადებები</option>
                    </select>
                </form>
            </div>

            <div class="catalog-toolbar">
                <div class="quick-filters">
                    <a class="quick-chip {{ !request('type') ? 'active' : '' }}" href="{{ route('motorcycles.index', request()->except(['type', 'page'])) }}">ყველა</a>
                    <a class="quick-chip {{ request('type') === 'new' ? 'active' : '' }}" href="{{ route('motorcycles.index', array_merge(request()->except('page'), ['type' => 'new'])) }}">ახალი</a>
                    <a class="quick-chip {{ request('type') === 'used' ? 'active' : '' }}" href="{{ route('motorcycles.index', array_merge(request()->except('page'), ['type' => 'used'])) }}">მეორადი</a>
                    <a class="quick-chip {{ request()->boolean('featured') ? 'active' : '' }}" href="{{ route('motorcycles.index', array_merge(request()->except('page'), ['featured' => request()->boolean('featured') ? null : 1])) }}">✦ რჩეული</a>
                </div>
                <span class="result-count">{{ $motorcycles->total() }} შედეგი</span>
            </div>

            @if (request()->hasAny(['q','brand','min_price','max_price','min_year','max_year','min_cc','max_cc','min_mileage','max_mileage','type','location','featured']))
                <div class="active-filters">
                    <span>აქტიური ფილტრები</span>
                    @foreach (request()->only(['q','brand','min_price','max_price','min_year','max_year','min_cc','max_cc','min_mileage','max_mileage','type','location']) as $filterValue)
                        @if (is_scalar($filterValue) && $filterValue !== '')
                            <span class="active-filter">{{ $filterValue }}</span>
                        @endif
                    @endforeach
                    @if (request()->boolean('featured')) <span class="active-filter">რჩეული</span> @endif
                    <a href="{{ route('motorcycles.index') }}">ყველას გასუფთავება ×</a>
                </div>
            @endif

            <div class="grid">
                @forelse ($motorcycles as $motorcycle)
                    <a
                        class="card"
                        href="{{ route('motorcycles.show', $motorcycle->slug) }}"
                    >
                        <div class="image-wrap">
                            @php
                                $photo = $motorcycle->images->first();

                                $photoPath = $photo?->image_path
                                    ?? $photo?->path
                                    ?? $motorcycle->image_path;
                            @endphp

                            @if ($photoPath)
                                <img
                                    src="{{ \Illuminate\Support\Str::startsWith($photoPath, ['http://', 'https://']) ? $photoPath : asset('storage/' . ltrim($photoPath, '/')) }}"
                                    alt="{{ $motorcycle->title }}"
                                    loading="lazy"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >

                                <div class="no-image" style="display:none;">
                                    <span class="no-image-icon">🏍</span>
                                    ფოტო ვერ ჩაიტვირთა
                                </div>
                            @else
                                <div class="no-image">
                                    <span class="no-image-icon">🏍</span>
                                    ფოტო არ არის
                                </div>
                            @endif

                            <div class="image-shade"></div>

                            @if ($motorcycle->is_featured)
                                <span class="featured">✦ გამორჩეული</span>
                            @endif
                        </div>

                        <div class="card-body">
                            <span class="condition">
                                {{ $motorcycle->condition === 'new' ? 'ახალი' : 'მეორადი' }}
                            </span>

                            <h3>{{ $motorcycle->title }}</h3>

                            <div class="meta">
                                @if ($motorcycle->year)
                                    <span>{{ $motorcycle->year }} წ.</span>
                                @endif

                                @if ($motorcycle->engine_cc)
                                    <span>{{ number_format($motorcycle->engine_cc) }} cc</span>
                                @endif

                                @if ($motorcycle->mileage !== null)
                                    <span>{{ number_format($motorcycle->mileage) }} კმ</span>
                                @endif

                                @if ($motorcycle->location)
                                    <span>⌖ {{ $motorcycle->location }}</span>
                                @endif
                            </div>

                            <div class="card-bottom">
                                <div class="price-wrap">
                                    <span class="price-label">ფასი</span>
                                    <div class="price">
                                        {{ number_format((float) $motorcycle->price, 0, '.', ' ') }}
                                        <span class="price-currency">₾</span>
                                    </div>
                                </div>

                                <span class="details">დეტალები ↗</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="empty">
                        <div class="empty-icon">⌕</div>
                        <h3>განცხადებები ვერ მოიძებნა</h3>
                        <p>სცადე სხვა საძიებო სიტყვა ან გაასუფთავე ფილტრები.</p>

                        <a class="details" href="{{ route('motorcycles.index') }}">
                            ყველა განცხადების ნახვა →
                        </a>
                    </div>
                @endforelse
            </div>

            <div class="pagination">
                {{ $motorcycles->links() }}
            </div>
        </section>
    </div>
</main>

<footer>
    <div class="container footer-inner">
        <a class="footer-brand" href="{{ route('home') }}">Moto<span>Hub</span></a>
        <span>© {{ date('Y') }} MotoHub — ყველა უფლება დაცულია.</span>
    </div>
</footer>

</body>
</html>
