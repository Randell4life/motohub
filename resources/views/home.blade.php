<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#080b10">
    <title>Garage33 — შენი მოტოსამყარო</title>
    <meta name="description" content="აღმოაჩინე მოტოციკლები, შეარჩიე სასურველი მოდელი და იპოვე მოტოსერვისები Garage33-ზე.">

    <style>
        :root {
            --bg: #080b10;
            --panel: #10151d;
            --panel2: #141b25;
            --red: #1677ff;
            --red2: #38a5ff;
            --text: #f7f8fb;
            --muted: #9ca7b7;
            --border: #27313f;
            --green: #38d69b;
            --radius: 15px;
        }

        * { box-sizing: border-box; }

        .account-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 14px;
    border: 1px solid #263247;
    border-radius: 9px;
    background: #101622;
    color: #dce6f5;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: border-color .2s, background .2s, color .2s;
}


.nav-dropdown {
    position: relative;
    display: flex;
    align-items: center;
}

.nav-dropdown-toggle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 0 12px;
    border: 1px solid transparent;
    border-radius: 9px;
    background: transparent;
    color: #dce7f7;
    font: inherit;
    font-size: 12px;
    font-weight: 720;
    white-space: nowrap;
    cursor: pointer;
    transition: color .2s ease, background .2s ease, border-color .2s ease;
}

.nav-dropdown-toggle:hover,
.nav-dropdown.is-open .nav-dropdown-toggle,
.nav-dropdown-toggle:focus-visible {
    color: #fff;
    border-color: rgba(56, 165, 255, .28);
    background: rgba(22, 119, 255, .12);
}

.dropdown-arrow {
    display: inline-block;
    color: #7eabed;
    font-size: 15px;
    line-height: 1;
    transition: transform .22s ease, color .22s ease;
}

.nav-dropdown.is-open .dropdown-arrow {
    transform: rotate(180deg);
    color: #42a5ff;
}

.nav-dropdown-menu {
    position: absolute;
    top: calc(100% + 10px);
    left: 0;
    z-index: 100;
    display: grid;
    gap: 5px;
    min-width: 235px;
    padding: 9px;
    border: 1px solid rgba(66, 153, 255, .28);
    border-radius: 13px;
    background: linear-gradient(145deg, #111a2a 0%, #090f1b 100%);
    box-shadow: 0 18px 45px rgba(0, 0, 0, .48), 0 0 22px rgba(22, 119, 255, .08);
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transform: translateY(8px);
    transition: opacity .2s ease, transform .2s ease, visibility .2s ease;
}

.nav-dropdown.is-open .nav-dropdown-menu {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    transform: translateY(0);
}

.nav-dropdown-menu a {
    display: block;
    padding: 11px 12px;
    border: 1px solid transparent;
    border-radius: 8px;
    color: #c4d1e5;
    font-size: 13px;
    transition: color .18s ease, background .18s ease, border-color .18s ease, padding-left .18s ease;
}

.nav-dropdown-menu a:hover,
.nav-dropdown-menu a:focus-visible {
    padding-left: 16px;
    border-color: rgba(66, 153, 255, .2);
    background: linear-gradient(90deg, rgba(22, 119, 255, .18), rgba(22, 119, 255, .04));
    color: #fff;
}

@media (max-width: 1100px) {
    .nav-dropdown {
        display: block;
        width: 100%;
    }

    .nav-dropdown-toggle {
        width: 100%;
        justify-content: space-between;
        min-height: 43px;
        padding: 11px 13px;
        text-align: left;
    }

    .nav-dropdown-menu {
        position: static;
        min-width: 0;
        margin: 4px 0 7px;
        padding: 5px 7px 5px 12px;
        border: 0;
        border-left: 2px solid rgba(56, 165, 255, .35);
        border-radius: 0;
        background: transparent;
        box-shadow: none;
        transform: none;
    }

    .nav-dropdown.is-open .nav-dropdown-menu {
        transform: none;
    }

    .nav-dropdown-menu a {
        padding: 10px 12px;
        white-space: normal;
    }
}

.account-link svg {
    width: 17px;
    height: 17px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.6;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.account-link:hover {
    border-color: #2585ff;
    background: #14243a;
    color: #60aaff;
}

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 90px;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: "Segoe UI", Arial, sans-serif;
            line-height: 1.55;
        }

        a { color: inherit; text-decoration: none; }

        button, input, select { font: inherit; }

        button, a { -webkit-tap-highlight-color: transparent; }

        button:focus-visible, a:focus-visible, input:focus-visible, select:focus-visible {
            outline: 2px solid var(--red2);
            outline-offset: 3px;
        }

        .container {
            width: min(1420px, calc(100% - 64px));
            margin-inline: auto;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(8, 11, 16, .92);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .nav {
            min-height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .logo {
            flex-shrink: 0;
            font-size: 28px;
            font-weight: 950;
            letter-spacing: -1.5px;
        }

        .logo span { color: var(--red); }

        .nav-links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(15px, 2.2vw, 32px);
            color: #d1d6e0;
            font-size: 13px;
            font-weight: 650;
        }

        .nav-links a {
            transition: color .2s;
            white-space: nowrap;
        }

        .nav-links a:hover,
        .nav-links a.active { color: var(--red2); }

        .auth-nav {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 8px;
        }

        .auth-nav form { margin: 0; }

        .auth-name {
            color: #d1d6e0;
            font-size: 12px;
        }

        .btn {
            min-height: 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 17px;
            border: 1px solid transparent;
            border-radius: 8px;
            background: var(--red);
            color: white;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: background .2s, border-color .2s, transform .2s;
        }

        .btn:hover {
            background: var(--red2);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: rgba(15,20,29,.8);
            border-color: #3a4554;
        }

        .btn-outline:hover {
            background: #202a37;
            border-color: #637083;
        }

        .hero {
            position: relative;
            display: flex;
            align-items: center;
            min-height: 460px;
            overflow: hidden;
            background:
                linear-gradient(90deg,
                    rgba(7,10,15,.98) 0%,
                    rgba(7,10,15,.88) 34%,
                    rgba(7,10,15,.30) 69%,
                    rgba(7,10,15,.08) 100%),
                linear-gradient(0deg, rgba(8,11,16,.45), transparent 40%),
                url('https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=2200&q=90')
                center 55% / cover no-repeat;
            border-bottom: 1px solid var(--border);
        }

        .hero-content {
            width: 100%;
            padding-top: 56px;
            padding-bottom: 72px;
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--red2);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        .eyebrow::after {
            content: "";
            width: 45px;
            height: 2px;
            background: var(--red);
        }

        h1 {
            max-width: 680px;
            margin: 17px 0;
            font-size: clamp(42px, 6vw, 76px);
            line-height: 1.08;
            letter-spacing: -2.4px;
            font-weight: 950;
        }

        h1 span { color: var(--red); }

        .hero-copy {
            max-width: 500px;
            margin: 0;
            color: #d0d5df;
            font-size: 15px;
            line-height: 1.9;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 25px;
        }

        .hero-actions .btn { min-height: 47px; }

        .search-wrap {
            position: relative;
            z-index: 3;
            margin-top: -30px;
        }

        .search-panel {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr auto;
            gap: 12px;
            align-items: end;
            padding: 18px;
            border: 1px solid #303b4a;
            border-radius: 14px;
            background: rgba(16,21,29,.97);
            box-shadow: 0 20px 55px rgba(0,0,0,.3);
        }

        .field label {
            display: block;
            margin-bottom: 7px;
            color: #b0bac9;
            font-size: 11px;
            font-weight: 750;
        }

        .field input,
        .field select {
            width: 100%;
            min-width: 0;
            height: 45px;
            padding: 0 12px;
            border: 1px solid #303b49;
            border-radius: 8px;
            background: #0a0e14;
            color: white;
            font-size: 13px;
        }

        .field input::placeholder { color: #758093; }

        .search-button {
            min-width: 112px;
            height: 45px;
        }

        .categories {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr)) minmax(190px, 1.5fr);
            align-items: stretch;
            gap: 12px;
            padding-top: 34px;
            padding-bottom: 34px;
            border-bottom: 1px solid var(--border);
        }

        .category {
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
            gap: 9px;
            min-height: 91px;
            padding: 12px 6px;
            border: 1px solid transparent;
            border-radius: 12px;
            color: #b9c2d0;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            transition: .2s;
        }

        .category-icon {
            display: grid;
            place-items: center;
            width: 48px;
            height: 48px;
            border: 1px solid #364151;
            border-radius: 50%;
            background: #111721;
            color: #e5eaf1;
            font-size: 23px;
        }

        .category:hover,
        .category.selected {
            border-color: rgba(22,119,255,.55);
            background: rgba(22,119,255,.055);
            color: white;
        }

        .category:hover .category-icon,
        .category.selected .category-icon {
            border-color: var(--red);
            color: white;
            box-shadow: 0 0 0 4px rgba(22,119,255,.09);
        }

        .trust-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 13px;
            padding: 10px 15px;
            border-left: 1px solid #344050;
        }

        .trust-icon {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 43px;
            height: 43px;
            border: 1px solid rgba(22,119,255,.45);
            border-radius: 12px;
            color: var(--red2);
            font-size: 21px;
        }

        .trust-item strong {
            display: block;
            font-size: 13px;
        }

        .trust-item small {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 11px;
        }

        .section { padding: 58px 0; }

        .section-heading {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }

        .section-heading h2 {
            margin: 8px 0 5px;
            font-size: clamp(24px, 3vw, 33px);
            letter-spacing: -.8px;
        }

        .section-heading p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
        }

        .text-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #dce1ea;
            font-size: 12px;
            font-weight: 750;
            white-space: nowrap;
        }

        .text-link:hover { color: var(--red2); }

        .bikes {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 17px;
        }

        .bike-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 13px;
            background: linear-gradient(155deg, #141b25, #0e131b);
            transition: border-color .25s, transform .25s, box-shadow .25s;
        }

        .bike-card:hover {
            transform: translateY(-4px);
            border-color: #4a5668;
            box-shadow: 0 18px 38px rgba(0,0,0,.23);
        }

        .bike-image {
            position: relative;
            height: 205px;
            overflow: hidden;
            background: #171e28;
        }

        .bike-image img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }

        /* Garage 33 — ანიმაციური ფოტოები */
.bike-image {
    isolation: isolate;
}

.bike-image .bike-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transform: scale(1.035);
    transition: opacity .65s ease, transform 1.1s ease;
}

.bike-image .bike-slide.is-active {
    opacity: 1;
    transform: scale(1);
}

.bike-image .slide-arrow {
    position: absolute;
    z-index: 4;
    top: 50%;
    width: 34px;
    height: 34px;
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 50%;
    background: rgba(8,11,16,.75);
    color: white;
    cursor: pointer;
    opacity: 0;
    transform: translateY(-50%);
    transition: opacity .25s, background .25s;
}

.bike-image:hover .slide-arrow,
.bike-image:focus-within .slide-arrow {
    opacity: 1;
}

.bike-image .slide-arrow:hover {
    background: var(--red);
}

.bike-image .slide-prev { left: 10px; }
.bike-image .slide-next { right: 10px; }

.bike-image .slide-dots {
    position: absolute;
    z-index: 4;
    left: 0;
    right: 0;
    bottom: 12px;
    display: flex;
    justify-content: center;
    gap: 5px;
}

.bike-image .slide-dot {
    width: 6px;
    height: 6px;
    padding: 0;
    border: 0;
    border-radius: 10px;
    background: rgba(255,255,255,.55);
    cursor: pointer;
    transition: width .25s, background .25s;
}

.bike-image .slide-dot.is-active {
    width: 19px;
    background: var(--red2);
}

@media (prefers-reduced-motion: reduce) {
    .bike-image .bike-slide {
        transition: none;
    }
}

        .bike-card:hover .bike-image img { transform: scale(1.055); }

        .image-shade {
            position: absolute;
            inset: 45% 0 0;
            background: linear-gradient(transparent, rgba(5,8,13,.55));
            pointer-events: none;
        }

        .listing-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 7px;
            background: rgba(8,13,18,.83);
            color: #dce5ef;
            font-size: 10px;
            font-weight: 800;
            backdrop-filter: blur(8px);
        }

        .listing-badge::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--green);
        }

        .bike-info { padding: 16px; }

        .bike-info h3 {
            margin: 0 0 5px;
            font-size: 17px;
            line-height: 1.4;
            overflow-wrap: anywhere;
        }

        .bike-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 5px 9px;
            color: var(--muted);
            font-size: 11px;
        }

        .bike-specs {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
            padding-top: 12px;
            border-top: 1px solid rgba(255,255,255,.07);
            color: #c4ccd8;
            font-size: 11px;
        }

        .bike-specs span { white-space: nowrap; }

        .bike-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-top: 16px;
        }

        .price {
            font-size: 22px;
            line-height: 1.2;
            font-weight: 950;
            letter-spacing: -.7px;
            white-space: nowrap;
        }

        .price-currency {
            color: #e9edf4;
            font-size: 15px;
        }

        .details-btn {
            min-height: 37px;
            padding: 9px 12px;
            border-radius: 7px;
            font-size: 11px;
        }

        .empty {
            grid-column: 1 / -1;
            padding: 42px 20px;
            border: 1px dashed #344052;
            border-radius: 14px;
            color: var(--muted);
            text-align: center;
        }

        .empty h3 {
            margin: 0 0 7px;
            color: white;
        }

        .empty p { margin: 0 0 18px; }

        .feature-strip {
            position: relative;
            display: grid;
            grid-template-columns: repeat(3, 1fr) 1.3fr;
            overflow: hidden;
            border: 1px solid #303b49;
            border-radius: 14px;
            background:
                radial-gradient(ellipse at 100% 50%, rgba(22,119,255,.1), transparent 38%),
                linear-gradient(115deg, #121923, #0e131b);
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 22px 20px;
        }

        .feature + .feature { border-left: 1px solid #2b3542; }

        .feature-icon {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--red);
            color: white;
            font-size: 19px;
            font-weight: 900;
        }

        .feature strong {
            display: block;
            font-size: 12px;
        }

        .feature p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 10px;
            line-height: 1.7;
        }

        .partner-banner {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
            overflow: hidden;
            padding: 35px;
            border: 1px solid #303b49;
            border-radius: 15px;
            background:
                linear-gradient(90deg, #141a24 0%, rgba(15,20,29,.96) 65%, rgba(15,20,29,.75) 100%),
                url('https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1200&q=85')
                center 55% / cover no-repeat;
        }

        .partner-banner h2 {
            margin: 10px 0;
            font-size: clamp(23px, 3vw, 32px);
        }

        .partner-banner p {
            max-width: 570px;
            margin: 0;
            color: var(--muted);
            font-size: 13px;
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 38px;
            align-items: center;
        }

        .about-grid h2 {
            margin: 12px 0;
            font-size: clamp(25px, 3vw, 35px);
            line-height: 1.3;
        }

        .about-grid p {
            max-width: 570px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.9;
        }

        .about-points {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .about-point {
            padding: 18px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--panel);
        }

        .about-point strong {
            display: block;
            font-size: 13px;
        }

        .about-point span {
            display: block;
            margin-top: 5px;
            color: var(--muted);
            font-size: 11px;
        }

        footer {
            padding: 25px 0;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 12px;
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .footer-logo {
            font-size: 20px;
            font-weight: 950;
            color: white;
        }

        .footer-logo span { color: var(--red); }

        .footer-links {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
        }

        .footer-links a:hover { color: white; }

        @media (min-width: 1500px) {
            .hero { min-height: 500px; }
            .bike-image { height: 225px; }
        }

        @media (max-width: 1100px) {
            .container { width: calc(100% - 40px); }
            .nav { gap: 15px; }
            .nav-links { gap: 14px; font-size: 12px; }
            .auth-name { display: none; }
            .categories { grid-template-columns: repeat(3, 1fr); }
            .trust-item { border-left: 0; border-top: 1px solid #344050; }
            .bikes { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .feature-strip { grid-template-columns: repeat(2, 1fr); }
            .feature:nth-child(3) { border-left: 0; border-top: 1px solid #2b3542; }
            .feature:nth-child(4) { border-top: 1px solid #2b3542; }
        }

        @media (max-width: 760px) {
            .container { width: calc(100% - 28px); }
            .nav {
                min-height: 65px;
                flex-wrap: wrap;
                gap: 10px;
                padding: 11px 0;
            }
            .logo { font-size: 25px; }
            .nav-links {
                order: 3;
                width: 100%;
                justify-content: flex-start;
                gap: 19px;
                overflow-x: auto;
                padding: 3px 0 5px;
                font-size: 12px;
            }
            .auth-nav { margin-left: auto; }
            .auth-nav .btn { min-height: 37px; padding: 8px 10px; font-size: 11px; }
            .hero { min-height: 420px; background-position: 61% center; }
            .hero-content { padding: 53px 0 64px; }
            h1 { font-size: clamp(39px, 9vw, 58px); letter-spacing: -1.5px; }
            .hero-copy { max-width: 420px; font-size: 14px; }
            .search-wrap { margin-top: 18px; }
            .search-panel { grid-template-columns: 1fr 1fr; padding: 14px; }
            .field:first-child { grid-column: 1 / -1; }
            .search-button { width: 100%; }
            .categories { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 7px; padding: 23px 0; }
            .category { min-height: 83px; font-size: 11px; }
            .category-icon { width: 43px; height: 43px; font-size: 20px; }
            .trust-item { grid-column: 1 / -1; justify-content: flex-start; padding: 16px 8px 5px; }
            .section { padding: 43px 0; }
            .section-heading { align-items: flex-start; }
            .section-heading p { max-width: 450px; line-height: 1.7; }
            .bike-image { height: 210px; }
            .feature-strip { grid-template-columns: 1fr 1fr; }
            .feature { padding: 17px 12px; gap: 9px; }
            .feature-icon { width: 36px; height: 36px; font-size: 15px; }
            .feature strong { font-size: 11px; }
            .feature p { font-size: 10px; }
            .partner-banner { align-items: flex-start; flex-direction: column; padding: 25px 20px; }
            .about-grid { grid-template-columns: 1fr; gap: 20px; }
        }

        @media (max-width: 480px) {
            .auth-nav { gap: 5px; }
            .auth-nav .btn { padding: 8px; }
            .hero { min-height: 390px; background-position: 64% center; }
            .hero-content { padding: 42px 0 48px; }
            .eyebrow { font-size: 10px; letter-spacing: 2px; }
            h1 { font-size: 41px; }
            .hero-copy { font-size: 13px; line-height: 1.8; }
            .hero-actions { flex-direction: column; align-items: stretch; }
            .search-panel { grid-template-columns: 1fr; }
            .field:first-child { grid-column: auto; }
            .categories { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .bikes { grid-template-columns: 1fr; }
            .bike-image { height: 230px; }
            .section-heading { flex-direction: column; gap: 10px; }
            .section-heading h2 { font-size: 26px; }
            .feature-strip { grid-template-columns: 1fr; }
            .feature + .feature,
            .feature:nth-child(3),
            .feature:nth-child(4) {
                border-left: 0;
                border-top: 1px solid #2b3542;
            }
            .about-points { grid-template-columns: 1fr 1fr; gap: 8px; }
            .about-point { padding: 13px 10px; }
            .footer-inner { align-items: flex-start; flex-direction: column; }
            .footer-links { gap: 12px; }
        }

                @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                scroll-behavior: auto !important;
                transition: none !important;
                animation: none !important;
            }
        }

        /* Garage33 — premium navigation */
        .topbar {
            background: rgba(7, 10, 16, .88);
            border-bottom: 1px solid rgba(75, 143, 255, .18);
            box-shadow: 0 8px 28px rgba(0, 0, 0, .18);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .nav {
            position: relative;
            min-height: 78px;
            gap: clamp(10px, 1.1vw, 18px);
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            color: #f6f8fc;
            font-size: 27px;
            font-weight: 950;
            letter-spacing: -1.6px;
            white-space: nowrap;
            text-shadow: 0 0 22px rgba(22, 119, 255, .12);
        }

        .logo span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: 3px;
            padding: 3px 6px 4px;
            border: 1px solid rgba(91, 166, 255, .5);
            border-radius: 6px;
            background: linear-gradient(135deg, #1677ff, #38a5ff);
            color: #fff;
            font-size: .72em;
            line-height: 1;
            letter-spacing: -.3px;
            box-shadow: 0 5px 18px rgba(22, 119, 255, .25);
        }

        .nav-links {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: nowrap;
            gap: 3px;
            padding: 5px;
            border: 1px solid rgba(139, 157, 184, .15);
            border-radius: 13px;
            background: rgba(16, 22, 32, .78);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .025);
        }

        .nav-links a {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 9px 10px;
            border: 1px solid transparent;
            border-radius: 9px;
            color: #b6c1d2;
            font-size: 12px;
            font-weight: 720;
            line-height: 1.25;
            white-space: nowrap;
            transition: color .2s ease, background .2s ease, border-color .2s ease, transform .2s ease;
        }

        .nav-links a:hover {
            border-color: rgba(74, 145, 255, .16);
            background: rgba(22, 119, 255, .09);
            color: #fff;
        }

        .nav-links a.active {
            border-color: rgba(69, 145, 255, .28);
            background: linear-gradient(135deg, rgba(22, 119, 255, .22), rgba(56, 165, 255, .09));
            color: #fff;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .045), 0 4px 16px rgba(22, 119, 255, .08);
        }

        .auth-nav {
            flex-shrink: 0;
            align-items: center;
            gap: 8px;
        }

        .auth-nav .btn {
            min-height: 40px;
            padding: 10px 13px;
            border-radius: 9px;
            white-space: nowrap;
        }

        .auth-nav .btn:not(.btn-outline) {
            border-color: rgba(99, 171, 255, .45);
            background: linear-gradient(135deg, #1267e8, #258bff);
            box-shadow: 0 6px 18px rgba(22, 119, 255, .2);
        }

        .auth-nav .btn:not(.btn-outline):hover {
            background: linear-gradient(135deg, #2380ff, #52b1ff);
            box-shadow: 0 8px 22px rgba(22, 119, 255, .28);
        }

        .auth-nav .btn-outline {
            border-color: rgba(139, 157, 184, .25);
            background: rgba(16, 22, 32, .72);
        }

        .auth-nav .btn-outline:hover {
            border-color: rgba(91, 166, 255, .55);
            background: rgba(22, 119, 255, .1);
        }

        .account-link {
            min-height: 40px;
            padding: 9px 12px;
            border-color: rgba(91, 166, 255, .24);
            border-radius: 9px;
            background: rgba(22, 119, 255, .07);
        }

        .menu-toggle {
            display: none;
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 10px;
            border: 1px solid rgba(139, 157, 184, .22);
            border-radius: 11px;
            background: linear-gradient(145deg, #151e2b, #0e141e);
            box-shadow: 0 5px 17px rgba(0, 0, 0, .2);
            cursor: pointer;
            transition: border-color .2s ease, background .2s ease, box-shadow .2s ease;
        }

        .menu-toggle:hover,
        .menu-toggle[aria-expanded="true"] {
            border-color: rgba(66, 153, 255, .65);
            background: rgba(22, 119, 255, .14);
            box-shadow: 0 0 0 4px rgba(22, 119, 255, .08);
        }

        .menu-toggle span {
            display: block;
            width: 19px;
            height: 2px;
            border-radius: 2px;
            background: #edf5ff;
            transition: transform .22s ease, opacity .18s ease;
        }

        .menu-toggle[aria-expanded="true"] span:first-child {
            transform: translateY(7px) rotate(45deg);
        }

        .menu-toggle[aria-expanded="true"] span:nth-child(2) {
            opacity: 0;
        }

        .menu-toggle[aria-expanded="true"] span:last-child {
            transform: translateY(-7px) rotate(-45deg);
        }

        @media (max-width: 1320px) and (min-width: 1101px) {
            .nav { gap: 9px; }
            .logo { font-size: 24px; }
            .nav-links { gap: 1px; padding: 4px; }
            .nav-links a { padding-inline: 7px; font-size: 11px; }
            .auth-nav { gap: 5px; }
            .auth-nav .btn { padding-inline: 10px; font-size: 12px; }
        }

        @media (max-width: 1100px) {
            .nav {
                min-height: 72px;
                flex-wrap: wrap;
                gap: 10px;
                padding-top: 12px;
                padding-bottom: 12px;
            }

            .logo { font-size: 25px; }

            .menu-toggle {
                display: flex;
                margin-left: auto;
            }

            .nav-links,
            .auth-nav {
                display: none;
                width: 100%;
                flex: 0 0 100%;
            }

            .nav-links.is-open {
                order: 3;
                display: flex;
                flex-direction: column;
                align-items: stretch;
                justify-content: flex-start;
                gap: 4px;
                overflow: visible;
                padding: 9px;
                border: 1px solid rgba(139, 157, 184, .16);
                border-radius: 13px;
                background: rgba(13, 19, 29, .98);
                box-shadow: 0 18px 38px rgba(0, 0, 0, .28);
            }

            .nav-links.is-open a {
                display: flex;
                justify-content: flex-start;
                min-height: 43px;
                padding: 11px 13px;
                border-radius: 8px;
                font-size: 13px;
                white-space: normal;
            }

            .nav-links.is-open a.active {
                box-shadow: inset 3px 0 0 #38a5ff;
            }

            .auth-nav.is-open {
                order: 4;
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
                padding: 10px;
                border: 1px solid rgba(139, 157, 184, .16);
                border-radius: 13px;
                background: rgba(13, 19, 29, .98);
            }

            .auth-nav.is-open .btn,
            .auth-nav.is-open form,
            .auth-nav.is-open form button,
            .auth-nav.is-open .account-link {
                width: 100%;
                min-height: 43px;
                justify-content: center;
                margin: 0;
            }
        }

        @media (max-width: 480px) {
            .nav { gap: 8px; }
            .logo { font-size: 24px; }
            .menu-toggle { width: 42px; height: 42px; flex-basis: 42px; }
            .nav-links.is-open,
            .auth-nav.is-open { padding: 8px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .menu-toggle,
            .menu-toggle span,
            .nav-links a {
                transition: none !important;
            }
        }
</style>
</head>
<body>

<header class="topbar">
    <nav class="container nav" aria-label="მთავარი ნავიგაცია">

        <a href="{{ route('home') }}" class="logo">
            GARAGE<span>33</span>
        </a>

        <button
            class="menu-toggle"
            id="menuToggle"
            type="button"
            aria-label="მენიუს გახსნა"
            aria-expanded="false"
            aria-controls="mainNavLinks authNav"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>


<div class="nav-links" id="mainNavLinks">
    <a class="active" href="{{ route('home') }}">მთავარი</a>

    <div class="nav-dropdown">
        <button class="nav-dropdown-toggle" type="button" aria-expanded="false">
            მოტოციკლები <span class="dropdown-arrow" aria-hidden="true">⌄</span>
        </button>
        <div class="nav-dropdown-menu">
            <a href="{{ route('motorcycles.index') }}">მოტოციკლების კატალოგი</a>
            <a href="{{ route('account.motorcycles.create') }}">განცხადების დამატება</a>
        </div>
    </div>

    <div class="nav-dropdown">
        <button class="nav-dropdown-toggle" type="button" aria-expanded="false">
            ნაწილები <span class="dropdown-arrow" aria-hidden="true">⌄</span>
        </button>
        <div class="nav-dropdown-menu">
            <a href="{{ route('parts.index') }}">ნაწილების კატალოგი</a>
            <a href="{{ route('part-requests.create') }}">ნაწილის მოთხოვნა</a>
        </div>
    </div>

    <div class="nav-dropdown">
        <button class="nav-dropdown-toggle" type="button" aria-expanded="false">
            სერვისი <span class="dropdown-arrow" aria-hidden="true">⌄</span>
        </button>
        <div class="nav-dropdown-menu">
            <a href="{{ route('service-centers.index') }}">სახელოსნოები</a>
            <a href="{{ route('account.service-bookings.index') }}">ჩემი სერვისის ჯავშნები</a>
            <a href="{{ route('transport.companies') }}">ევაკუატორის მომსახურება</a>
        </div>
    </div>
    <a href="{{ route('rentals.index') }}">გაქირავება</a>
    <a href="#about">ჩვენ შესახებ</a>
</div>


        <div class="auth-nav" id="authNav">
 @guest
    <a class="btn btn-outline" href="{{ route('login') }}">
        შესვლა
    </a>

    <a class="btn" href="{{ route('register') }}">
        რეგისტრაცია
    </a>
@else
                <a href="{{ route('account.index') }}" class="account-link">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.5"></circle>
                        <path d="M5 20c0-3.5 2.8-6 7-6s7 2.5 7 6"></path>
                    </svg>

                    <span>ჩემი კაბინეტი</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button class="btn btn-outline" type="submit">
                        გასვლა
                    </button>
                </form>
            @endguest
        </div>

    </nav>
</header>

<main>
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="eyebrow">Garage33 · შენი მოტოსამყარო</div>

                <h1>იპოვე შენი<br><span>თავისუფლება.</span></h1>

                <p class="hero-copy">
                    აღმოაჩინე შენი შემდეგი მოტოციკლი, შეადარე მოდელები
                    და დაიწყე ახალი თავგადასავალი. ყველაფერი, რაც მოტოსამყაროში
                    გჭირდება, ერთ სივრცეში.
                </p>

                <div class="hero-actions">
                    <a class="btn" href="#motorcycles">მოტოციკლების აღმოჩენა →</a>
                    <a class="btn btn-outline" href="{{ route('rentals.index') }}">მოტოს გაქირავება</a>
                </div>
            </div>
        </div>
    </section>

    <div class="container search-wrap">
        <form class="search-panel" action="{{ route('home') }}#motorcycles" method="GET">
            <div class="field">
                <label for="q">მარკა ან მოდელი</label>
                <input
                    id="q"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="მაგ. Yamaha, BMW, Honda"
                >
            </div>

            <div class="field">
                <label for="type">მდგომარეობა</label>
                <select id="type" name="type">
                    <option value="">ყველა მდგომარეობა</option>
                    <option value="used" @selected(request('type') === 'used')>მეორადი</option>
                    <option value="new" @selected(request('type') === 'new')>ახალი</option>
                </select>
            </div>

            <div class="field">
                <label for="budget">მაქსიმალური ბიუჯეტი</label>
                <select id="budget" name="budget">
                    <option value="">ნებისმიერი ფასი</option>
                    <option value="5000" @selected(request('budget') == '5000')>5,000 ₾-მდე</option>
                    <option value="10000" @selected(request('budget') == '10000')>10,000 ₾-მდე</option>
                    <option value="20000" @selected(request('budget') == '20000')>20,000 ₾-მდე</option>
                    <option value="50000" @selected(request('budget') == '50000')>50,000 ₾-მდე</option>
                </select>
            </div>

            <button class="btn search-button" type="submit">⌕ ძიება</button>
        </form>
    </div>

    <div class="container">
        <div class="categories">
            <a class="category selected" href="#motorcycles">
                <span class="category-icon">🏍</span>
                <span>სპორტული</span>
            </a>
            <a class="category" href="#motorcycles">
                <span class="category-icon">🏍</span>
                <span>ტურისტული</span>
            </a>
            <a class="category" href="#motorcycles">
                <span class="category-icon">⚙</span>
                <span>კრუიზერი</span>
            </a>
            <a class="category" href="#motorcycles">
                <span class="category-icon">⛰</span>
                <span>ენდურო</span>
            </a>
            <a class="category" href="#motorcycles">
                <span class="category-icon">🛵</span>
                <span>სკუტერი</span>
            </a>
            <a class="category" href="#motorcycles">
                <span class="category-icon">🏁</span>
                <span>ოფროუდი</span>
            </a>

            <div class="trust-item">
                <div class="trust-icon">✓</div>
                <div>
                    <strong>შენი მოტო იწყება აქ</strong>
                    <small>მოძებნე, შეადარე და შეარჩიე</small>
                </div>
            </div>
        </div>
    </div>

    <section class="section" id="motorcycles">
        <div class="container">
            <div class="section-heading">
                <div>
                    <div class="eyebrow">შეარჩიე შენი სტილი</div>
                    <h2>მოტოციკლების განცხადებები</h2>
                    <p>აღმოაჩინე საინტერესო მოდელები და იპოვე შენთვის სასურველი მოტოციკლი.</p>
                </div>

                <a class="text-link" href="{{ route('motorcycles.index') }}">
    განცხადებების ნახვა <span>→</span>
</a>
            </div>

            <div class="bikes">
                @forelse ($motorcycles as $motorcycle)
                    <a href="{{ route('motorcycles.show', $motorcycle->slug) }}">
                        <article class="bike-card">
<div class="bike-image">
    @php
        $galleryPhotos = collect();

        if ($motorcycle->image_path) {
            $galleryPhotos->push($motorcycle->image_path);
        }

        foreach ($motorcycle->images as $photo) {
            if ($photo->image_path && !$galleryPhotos->contains($photo->image_path)) {
                $galleryPhotos->push($photo->image_path);
            }
        }

        if ($galleryPhotos->isEmpty()) {
            $galleryPhotos->push('https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=900&q=85');
        }
    @endphp

    @foreach ($galleryPhotos as $index => $photoPath)
        <img
            class="bike-slide {{ $index === 0 ? 'is-active' : '' }}"
            src="{{ str_starts_with($photoPath, 'https://') ? $photoPath : \Illuminate\Support\Facades\Storage::url($photoPath) }}"
            alt="{{ $motorcycle->title }} — ფოტო {{ $index + 1 }}"
            loading="{{ $index === 0 ? 'lazy' : 'lazy' }}"
        >
    @endforeach

    @if ($galleryPhotos->count() > 1)
        <button type="button" class="slide-arrow slide-prev" aria-label="წინა ფოტო">‹</button>
        <button type="button" class="slide-arrow slide-next" aria-label="შემდეგი ფოტო">›</button>

        <div class="slide-dots">
            @foreach ($galleryPhotos as $index => $photoPath)
                <button
                    type="button"
                    class="slide-dot {{ $index === 0 ? 'is-active' : '' }}"
                    aria-label="ფოტო {{ $index + 1 }}"
                    data-slide="{{ $index }}"
                ></button>
            @endforeach
        </div>
    @endif

    <div class="image-shade"></div>
    <span class="listing-badge">განცხადება</span>
</div>

                            <div class="bike-info">
                                <h3>{{ $motorcycle->title }}</h3>

                                <div class="bike-meta">
                                    <span>{{ $motorcycle->brand }} {{ $motorcycle->model }}</span>
                                    @if ($motorcycle->year)
                                        <span>{{ $motorcycle->year }}</span>
                                    @endif
                                </div>

                                <div class="bike-specs">
                                    @if ($motorcycle->engine_cc)
                                        <span>⚙ {{ $motorcycle->engine_cc }} cc</span>
                                    @endif
                                    <span>{{ $motorcycle->condition === 'new' ? 'ახალი' : 'მეორადი' }}</span>
                                    @if ($motorcycle->location)
                                        <span>⌖ {{ $motorcycle->location }}</span>
                                    @endif
                                </div>

                                <div class="bike-bottom">
                                    <div class="price">
                                        {{ number_format((float) $motorcycle->price, 0, '.', ' ') }}
                                        <span class="price-currency">₾</span>
                                    </div>
                                    <span class="btn details-btn">დეტალები →</span>
                                </div>
                            </div>
                        </article>
                    </a>
                @empty
                    <div class="empty">
                        <h3>მოტოციკლები ჯერ არ მოიძებნა</h3>
                        <p>სცადე სხვა საძიებო სიტყვა ან გაასუფთავე ფილტრები.</p>
                        <a class="btn" href="{{ route('home') }}#motorcycles">ყველა განცხადება</a>
                    </div>
                @endforelse
            </div>

            @if (method_exists($motorcycles, 'hasPages') && $motorcycles->hasPages())
                <div style="margin-top: 28px;">
                    {{ $motorcycles->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </section>

    <div class="container">
        <div class="feature-strip">
            <div class="feature">
                <div class="feature-icon">⚡</div>
                <div>
                    <strong>სწრაფი ძიება</strong>
                    <p>შეარჩიე მოტო მარტივად</p>
                </div>
            </div>
            <div class="feature">
                <div class="feature-icon">✓</div>
                <div>
                    <strong>მარტივი განცხადებები</strong>
                    <p>განათავსე შენი მოტოციკლი</p>
                </div>
            </div>
            <div class="feature">
                <div class="feature-icon">♧</div>
                <div>
                    <strong>მოტო თავგადასავლები</strong>
                    <p>აღმოაჩინე გაქირავების შეთავაზებები</p>
                </div>
            </div>
            <div class="feature">
                <div>
                    <strong>დროა, შენი მოტო იპოვო!</strong>
                    <p>შემდეგი თავგადასავალი აქ იწყება.</p>
                </div>
            </div>
        </div>
    </div>

    <section class="section" id="parts-request">
    <div class="container">
        <div class="partner-banner parts-request-banner">
            <div>
                <div class="eyebrow">Garage33 PARTS</div>
                <h2>ეძებ კონკრეტულ მოტო-ნაწილს?</h2>
                <p>
                    ვერ იპოვე საჭირო ნაწილი? გამოგვიგზავნე მოთხოვნა
                    და მიუთითე მოტოციკლის მოდელი და საჭირო დეტალი.
                </p>
            </div>

            <a class="btn" href="{{ route('part-requests.create') }}">
                მოვითხოვო ნაწილი →
            </a>
        </div>
    </div>
</section>

    <section class="section" id="services">
        <div class="container">
            <div class="partner-banner">
                <div>
                    <div class="eyebrow">Garage33 პარტნიორები</div>
                    <h2>შენი ბიზნესი მოტოსამყაროში</h2>
                    <p>
                        წარმოაჩინე შენი მოტოსერვისი და გახადე ის უფრო ხელმისაწვდომი
                        მოტოციკლის მფლობელებისთვის.
                    </p>
                </div>
                <a class="btn" href="#about">გაიგე მეტი →</a>
            </div>
        </div>
    </section>

    <section class="section" id="about">
        <div class="container about-grid">
            <div>
                <div class="eyebrow">ჩვენ შესახებ</div>
                <h2>ყველაფერი მოტოციკლების შესახებ — ერთ სივრცეში.</h2>
                <p>
                    Garage33 აერთიანებს მოტოციკლების განცხადებებსა და გაქირავების
                    შესაძლებლობებს ერთ მოსახერხებელ სივრცეში. იპოვე სასურველი
                    მოდელი, გაეცანი ინფორმაციას და დაგეგმე შენი შემდეგი გასეირნება.
                </p>
                <a class="btn" href="#motorcycles">აღმოაჩინე მოტოციკლები →</a>
            </div>

            <div class="about-points">
                <div class="about-point">
                    <strong>🏍 მოტოციკლები</strong>
                    <span>განცხადებები სხვადასხვა მოდელზე</span>
                </div>
                <div class="about-point">
                    <strong>⌕ მარტივი ძიება</strong>
                    <span>მოძებნე მარკითა და ბიუჯეტით</span>
                </div>
                <div class="about-point">
                    <strong>♧ გაქირავება</strong>
                    <span>დაათვალიერე ხელმისაწვდომი მოტოები</span>
                </div>
                <div class="about-point">
                    <strong>◈ შენი კაბინეტი</strong>
                    <span>მართე საკუთარი განცხადებები</span>
                </div>
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container footer-inner">
        <a href="{{ route('home') }}" class="footer-logo">GARAGE<span>33</span></a>
        <div>© {{ date('Y') }} Garage33. ყველა უფლება დაცულია.</div>
        <div class="footer-links">
            <a href="#motorcycles">მოტოციკლები</a>
            <a href="{{ route('rentals.index') }}">გაქირავება</a>
            <a href="#parts-request">ნაწილის მოთხოვნა</a>
            <a href="#about">ჩვენ შესახებ</a>
        </div>
    </div>
</footer>


<script>
document.querySelectorAll('.bike-image').forEach((gallery) => {
    const slides = gallery.querySelectorAll('.bike-slide');
    const dots = gallery.querySelectorAll('.slide-dot');

    if (slides.length < 2) return;

    let current = 0;
    let timer;
    let startX = 0;

    function showSlide(index) {
        current = (index + slides.length) % slides.length;

        slides.forEach((slide, i) => {
            slide.classList.toggle('is-active', i === current);
        });

        dots.forEach((dot, i) => {
            dot.classList.toggle('is-active', i === current);
        });
    }

    function startAuto() {
        clearInterval(timer);
        timer = setInterval(() => showSlide(current + 1), 3500);
    }

    gallery.querySelector('.slide-prev')?.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        showSlide(current - 1);
        startAuto();
    });

    gallery.querySelector('.slide-next')?.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        showSlide(current + 1);
        startAuto();
    });

    dots.forEach((dot) => {
        dot.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            showSlide(Number(dot.dataset.slide));
            startAuto();
        });
    });

    gallery.addEventListener('touchstart', (event) => {
        startX = event.changedTouches[0].screenX;
    }, { passive: true });

    gallery.addEventListener('touchend', (event) => {
        const difference = event.changedTouches[0].screenX - startX;

        if (Math.abs(difference) > 45) {
            showSlide(current + (difference < 0 ? 1 : -1));
            startAuto();
        }
    }, { passive: true });

    gallery.addEventListener('mouseenter', () => clearInterval(timer));
    gallery.addEventListener('mouseleave', startAuto);

    startAuto();
});
</script>

<script>
(() => {
    const toggle = document.getElementById('menuToggle');
    const links = document.getElementById('mainNavLinks');
    const auth = document.getElementById('authNav');

    if (!toggle || !links || !auth) return;

    const dropdowns = links.querySelectorAll('.nav-dropdown');

    const closeDropdowns = (except = null) => {
        dropdowns.forEach((dropdown) => {
            if (dropdown === except) return;

            dropdown.classList.remove('is-open');
            dropdown.querySelector('.nav-dropdown-toggle')
                ?.setAttribute('aria-expanded', 'false');
        });
    };

    dropdowns.forEach((dropdown) => {
        const button = dropdown.querySelector('.nav-dropdown-toggle');
        if (!button) return;

        button.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();

            const willOpen = !dropdown.classList.contains('is-open');
            closeDropdowns();
            dropdown.classList.toggle('is-open', willOpen);
            button.setAttribute('aria-expanded', String(willOpen));
        });
    });

    const setMenu = (open) => {
        toggle.setAttribute('aria-expanded', String(open));
        toggle.setAttribute('aria-label', open ? 'მენიუს დახურვა' : 'მენიუს გახსნა');
        links.classList.toggle('is-open', open);
        auth.classList.toggle('is-open', open);

        if (!open) closeDropdowns();
    };

    toggle.addEventListener('click', () => {
        setMenu(toggle.getAttribute('aria-expanded') !== 'true');
    });

    links.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setMenu(false));
    });

    auth.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setMenu(false));
    });

    auth.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', () => setMenu(false));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') setMenu(false);
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.nav')) {
            closeDropdowns();
            setMenu(false);
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 1100) setMenu(false);
    });
})();
</script>

</body>
</html>
