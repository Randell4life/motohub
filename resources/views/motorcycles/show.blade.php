<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $motorcycle->title }} | MotoHub</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($motorcycle->description ?? $motorcycle->title), 155) }}">
    <meta name="theme-color" content="#030b18">

    <style>
        :root {
            --bg: #020814;
            --surface: #061326;
            --surface-2: #091a31;
            --surface-3: #0c203b;
            --border: #122b49;
            --text: #f4f8ff;
            --muted: #91a8c8;
            --blue: #0866ff;
            --blue-light: #08a9ff;
            --radius: 10px;
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            background:
                radial-gradient(ellipse at 50% 0%, #07162b 0%, transparent 55%),
                var(--bg);
            color: var(--text);
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; text-decoration: none; }
        button, input { font: inherit; }
        button { color: inherit; }
        img { max-width: 100%; }

        .container {
            width: min(1370px, 94%);
            margin-inline: auto;
        }

        /* HEADER */

        .site-header {
            position: relative;
            z-index: 20;
            background: rgba(3, 11, 24, .96);
            border-bottom: 1px solid #11233b;
        }

        .header-inner {
            min-height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
            font-size: 26px;
            font-weight: 900;
            letter-spacing: -1.1px;
        }

        .logo-mark {
            color: var(--blue-light);
            font-size: 31px;
            font-weight: 1000;
            line-height: 1;
            text-shadow: 0 0 22px #0876ff55;
        }

        .logo-text span { color: var(--blue-light); }

        .main-nav {
            display: flex;
            align-items: center;
            gap: clamp(15px, 2.3vw, 34px);
        }

        .main-nav a {
            color: #e0eafa;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            transition: color .2s;
        }

        .main-nav a:hover,
        .header-icon:hover { color: var(--blue-light); }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 27px;
            height: 35px;
            color: #b7c9e5;
            font-size: 21px;
            transition: color .2s;
        }

        .login-link {
            color: #eaf2ff;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .primary-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 40px;
            padding: 10px 16px;
            border: 1px solid var(--blue);
            border-radius: 7px;
            background: var(--blue);
            color: white;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: .2s ease;
        }

        .primary-button:hover {
            border-color: var(--blue-light);
            background: #0755d7;
            box-shadow: 0 0 22px #0866ff35;
        }

        .outline-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 43px;
            padding: 10px 16px;
            border: 1px solid var(--blue);
            border-radius: 7px;
            background: transparent;
            color: #eaf4ff;
            font-weight: 750;
            cursor: pointer;
            transition: .2s ease;
        }

        .outline-button:hover {
            background: #0866ff16;
            border-color: var(--blue-light);
        }

        /* PAGE */

        .breadcrumb {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            padding: 20px 0 17px;
            color: var(--muted);
            font-size: 12px;
        }

        .breadcrumb a:hover { color: var(--blue-light); }
        .breadcrumb-separator { color: #506987; }

        .listing-heading { margin-bottom: 21px; }

        .eyebrow {
            margin-bottom: 6px;
            color: var(--blue-light);
            font-size: 10px;
            font-weight: 850;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            color: #f6f9ff;
            font-size: clamp(25px, 3vw, 35px);
            font-weight: 850;
            line-height: 1.25;
            letter-spacing: -.8px;
            overflow-wrap: anywhere;
        }

        .listing-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 13px;
        }

        .meta-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            border: 1px solid var(--border);
            border-radius: 5px;
            background: #07162a;
            color: #a8bfdf;
            font-size: 11px;
            font-weight: 650;
        }

        /* MAIN COLUMNS */

        .detail-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.36fr) minmax(390px, 1fr);
            align-items: start;
            gap: 21px;
            margin-bottom: 23px;
        }

        .photo-panel,
        .info-panel,
        .description-panel,
        .seller-panel,
        .additional-panel {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: linear-gradient(145deg, #061426, #030d1c);
            box-shadow: 0 10px 35px #00000012;
        }

        /* GALLERY */

        .gallery-stage {
            position: relative;
            overflow: hidden;
            background: #030a14;
            cursor: zoom-in;
        }

        .main-photo {
            display: block;
            width: 100%;
            aspect-ratio: 1.56 / 1;
            max-height: 650px;
            object-fit: contain;
            background: #030a14;
            transition: opacity .2s;
        }

        .photo-badge {
            position: absolute;
            top: 13px;
            left: 13px;
            padding: 6px 11px;
            border: 1px solid #46a5ff55;
            border-radius: 6px;
            background: #0866ff;
            color: white;
            font-size: 10px;
            font-weight: 850;
            letter-spacing: .3px;
        }

        .gallery-arrow {
            position: absolute;
            z-index: 2;
            top: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 41px;
            height: 41px;
            transform: translateY(-50%);
            border: 1px solid #ffffff20;
            border-radius: 8px;
            background: #020814bb;
            color: #fff;
            font-size: 28px;
            line-height: 1;
            cursor: pointer;
            transition: .2s;
        }

        .gallery-arrow:hover {
            border-color: var(--blue);
            background: var(--blue);
        }

        .gallery-prev { left: 13px; }
        .gallery-next { right: 13px; }

        .gallery-counter {
            position: absolute;
            right: 12px;
            bottom: 12px;
            padding: 5px 9px;
            border: 1px solid #ffffff18;
            border-radius: 5px;
            background: #020814d9;
            color: white;
            font-size: 11px;
            font-weight: 750;
        }

        .gallery-thumbnails {
            display: flex;
            gap: 9px;
            padding: 12px;
            overflow-x: auto;
            border-top: 1px solid var(--border);
            background: #040e1d;
            scrollbar-width: thin;
            scrollbar-color: #174477 #061326;
        }

        .gallery-thumb {
            position: relative;
            flex: 0 0 116px;
            height: 77px;
            padding: 0;
            overflow: hidden;
            border: 1px solid #1a3555;
            border-radius: 6px;
            background: #071326;
            cursor: pointer;
            opacity: .7;
            transition: .2s;
        }

        .gallery-thumb:hover { opacity: 1; }

        .gallery-thumb img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery-thumb.is-active {
            border: 2px solid var(--blue);
            opacity: 1;
            box-shadow: 0 0 14px #0866ff2c;
        }

        /* LISTING INFO */

        .info-panel {
            padding: 22px;
        }

        .info-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
        }

        .info-title {
            margin: 0;
            color: #f2f7ff;
            font-size: clamp(23px, 2.2vw, 29px);
            font-weight: 850;
            line-height: 1.25;
            letter-spacing: -.6px;
            overflow-wrap: anywhere;
        }

        .info-meta {
            margin-top: 8px;
            color: #9bb2d1;
            font-size: 12px;
        }

        .info-meta span { padding: 0 6px; color: #4d6a8c; }

        .quick-actions {
            display: flex;
            gap: 12px;
            flex-shrink: 0;
        }

        .icon-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 31px;
            height: 34px;
            padding: 0;
            border: 0;
            background: transparent;
            color: #9dbce4;
            font-size: 24px;
            cursor: pointer;
            transition: color .2s, transform .2s;
        }

        .icon-button:hover {
            color: var(--blue-light);
            transform: translateY(-1px);
        }

        .price {
            margin: 20px 0 18px;
            color: var(--blue-light);
            font-size: clamp(30px, 3vw, 38px);
            font-weight: 900;
            line-height: 1.2;
            letter-spacing: -.8px;
            overflow-wrap: anywhere;
            text-shadow: 0 0 25px #009bff15;
        }

        .specs {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            border-top: 1px solid #122740;
        }

        .spec {
            min-width: 0;
            min-height: 85px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 16px 10px 13px 3px;
            border-bottom: 1px solid #10243c;
        }

        .spec:nth-child(3n + 2),
        .spec:nth-child(3n + 3) {
            padding-left: 12px;
            border-left: 1px solid #10243c;
        }

        .spec-icon {
            flex-shrink: 0;
            color: #85b8ff;
            font-size: 20px;
            line-height: 1.3;
        }

        .spec-label {
            display: block;
            margin-bottom: 4px;
            color: #91a9c9;
            font-size: 10px;
            line-height: 1.5;
        }

        .spec-value {
            display: block;
            color: #f0f5ff;
            font-size: 12px;
            font-weight: 750;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        /* CONTACT */

        .contact-row {
            display: grid;
            grid-template-columns: 1.35fr 1fr;
            gap: 11px;
            margin-top: 20px;
        }

        .call-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-width: 0;
            min-height: 47px;
            padding: 11px;
            border: 1px solid var(--blue);
            border-radius: 7px;
            background: var(--blue);
            color: #fff;
            font-size: 12px;
            font-weight: 850;
            text-align: center;
            transition: .2s;
        }

        .call-button:hover {
            background: #0755d7;
            box-shadow: 0 0 22px #0866ff35;
        }

        .phone-number {
            display: block;
            margin-top: 9px;
            color: #c8dcf6;
            font-size: 13px;
            font-weight: 750;
            text-align: center;
            overflow-wrap: anywhere;
        }

        .phone-number:hover { color: var(--blue-light); }

        .seller-mini {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-top: 20px;
            padding: 14px;
            border: 1px solid #102c4b;
            border-radius: 8px;
            background: linear-gradient(105deg, #091c35, #071326);
        }

        .seller-mini-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 39px;
            height: 39px;
            flex: 0 0 39px;
            border: 1px solid #0866ff55;
            border-radius: 50%;
            background: #0866ff19;
            color: var(--blue-light);
            font-size: 22px;
        }

        .seller-mini strong {
            display: block;
            font-size: 12px;
        }

        .seller-mini small {
            display: block;
            margin-top: 2px;
            color: #91a9c9;
            font-size: 10px;
        }

        /* LOWER CONTENT */

        .lower-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.36fr) minmax(320px, 1fr);
            gap: 21px;
            align-items: stretch;
            margin-bottom: 26px;
        }

        .description-panel { padding: 23px 26px; }

        .section-tabs {
            display: flex;
            gap: 32px;
            margin: -23px -26px 22px;
            padding: 0 26px;
            border-bottom: 1px solid var(--border);
        }

        .section-tabs a {
            position: relative;
            display: inline-flex;
            align-items: center;
            min-height: 57px;
            color: #91a9c9;
            font-size: 12px;
            font-weight: 750;
        }

        .section-tabs a.active,
        .section-tabs a:hover {
            color: var(--blue-light);
        }

        .section-tabs a.active::after {
            position: absolute;
            right: 0;
            bottom: -1px;
            left: 0;
            height: 3px;
            background: var(--blue);
            content: "";
        }

        .section-title {
            margin: 0 0 12px;
            font-size: 18px;
            font-weight: 850;
        }

        .description {
            color: #a6bbd8;
            font-size: 13px;
            line-height: 1.95;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .muted { color: var(--muted); }

        .feature-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px 18px;
            margin-top: 20px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #a9c2e4;
            font-size: 12px;
        }

        .feature-check {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            flex: 0 0 16px;
            border-radius: 50%;
            background: var(--blue);
            color: white;
            font-size: 11px;
            font-weight: 900;
        }

        /* SELLER CARD */

        .seller-panel {
            display: flex;
            flex-direction: column;
            padding: 23px;
        }

        .seller-header {
            display: flex;
            align-items: center;
            gap: 17px;
            margin-bottom: 22px;
        }

        .seller-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 79px;
            height: 79px;
            flex: 0 0 79px;
            border: 1px solid #183b63;
            border-radius: 50%;
            background: radial-gradient(circle, #0b2c50, #061326);
            color: var(--blue-light);
            font-size: 36px;
            font-weight: 900;
        }

        .seller-name {
            margin: 0 0 3px;
            font-size: 17px;
            font-weight: 850;
        }

        .seller-subtitle {
            color: #91a9c9;
            font-size: 11px;
        }

        .seller-rating {
            margin-top: 5px;
            color: var(--blue-light);
            font-size: 13px;
        }

        .seller-rating small {
            color: #9bb2d1;
            font-size: 10px;
        }

        .seller-badge {
            display: inline-block;
            margin-top: 7px;
            padding: 3px 9px;
            border-radius: 20px;
            background: #0866ff18;
            color: #66b9ff;
            font-size: 10px;
        }

        .seller-buttons {
            display: grid;
            gap: 11px;
            margin-top: auto;
        }

        .seller-details {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 12px;
            margin-top: 20px;
            color: #91a9c9;
            font-size: 10px;
        }

        .seller-details span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .seller-details b {
            color: var(--blue-light);
            font-size: 15px;
        }

        /* EXTRA DETAILS */

        .additional-panel {
            margin-bottom: 30px;
            padding: 23px 26px;
        }

        .additional-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .additional-item {
            padding: 13px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: #07162a;
        }

        .additional-label {
            display: block;
            margin-bottom: 4px;
            color: #91a9c9;
            font-size: 11px;
        }

        .additional-value {
            display: block;
            color: #f0f5ff;
            font-size: 13px;
            font-weight: 750;
            overflow-wrap: anywhere;
        }

        footer {
            padding: 23px 0;
            border-top: 1px solid #11233b;
            background: #020712;
            color: #7289a8;
            font-size: 11px;
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .footer-brand { color: #b5c8e3; font-weight: 750; }

        /* FULLSCREEN GALLERY */

        #galleryFullscreen[hidden] { display: none !important; }

        #galleryFullscreen {
            backdrop-filter: blur(10px);
        }

        #galleryFullscreen button {
            border: 1px solid #ffffff25 !important;
            border-radius: 9px !important;
            background: #0b1c32 !important;
            color: #fff !important;
            transition: background .2s;
        }

        #galleryFullscreen button:hover { background: var(--blue) !important; }

        #fullscreenPhoto { border-radius: 6px; }

        /* RESPONSIVE */

        @media (min-width: 1000px) {
            .info-panel { position: sticky; top: 15px; }
        }

        @media (max-width: 1100px) {
            .main-nav { gap: 13px; }
            .main-nav a { font-size: 11px; }
            .header-actions { gap: 9px; }
            .detail-grid,
            .lower-grid {
                grid-template-columns: minmax(0, 1.15fr) minmax(350px, 1fr);
                gap: 15px;
            }
            .info-panel { padding: 17px; }
            .spec { gap: 7px; }
            .spec:nth-child(3n + 2),
            .spec:nth-child(3n + 3) { padding-left: 8px; }
            .spec-icon { font-size: 17px; }
        }

        @media (max-width: 900px) {
            .main-nav { display: none; }

            .detail-grid,
            .lower-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 15px;
            }

            .info-panel { position: static; }
            .main-photo { max-height: 620px; }
            .seller-panel { min-height: 300px; }
        }

        @media (max-width: 600px) {
            .container { width: 94%; }

            .header-inner { min-height: 61px; gap: 10px; }
            .logo { gap: 7px; font-size: 23px; }
            .logo-mark { font-size: 27px; }

            .header-actions { gap: 7px; }
            .header-icon { width: 23px; font-size: 19px; }
            .login-link { display: none; }
            .header-actions .primary-button {
                min-height: 35px;
                padding: 7px 10px;
                font-size: 10px;
            }

            .breadcrumb {
                gap: 7px;
                padding: 15px 0 13px;
                font-size: 10px;
            }

            .listing-heading { margin-bottom: 15px; }
            h1 { font-size: 25px; }
            .listing-meta { gap: 6px; }
            .meta-chip { padding: 4px 7px; font-size: 10px; }

            .detail-grid { gap: 12px; margin-bottom: 14px; }
            .main-photo { aspect-ratio: 4 / 3; max-height: 460px; }

            .photo-badge { top: 9px; left: 9px; }
            .gallery-arrow {
                width: 34px;
                height: 36px;
                font-size: 24px;
            }
            .gallery-prev { left: 8px; }
            .gallery-next { right: 8px; }

            .gallery-thumbnails { gap: 7px; padding: 8px; }
            .gallery-thumb { flex-basis: 78px; height: 57px; }

            .info-panel { padding: 16px 13px; }
            .info-title { font-size: 24px; }
            .price { margin: 16px 0; font-size: 32px; }

            .specs { grid-template-columns: repeat(2, minmax(0, 1fr)); }

            .spec {
                min-height: 77px;
                padding: 12px 5px 10px 2px;
            }

            .spec:nth-child(3n + 2),
            .spec:nth-child(3n + 3) {
                padding-left: 5px;
                border-left: 0;
            }

            .spec:nth-child(even) {
                padding-left: 10px;
                border-left: 1px solid #10243c;
            }

            .spec-icon { font-size: 18px; }
            .spec-label { font-size: 10px; }
            .spec-value { font-size: 12px; }

            .contact-row { grid-template-columns: 1fr; gap: 8px; margin-top: 15px; }
            .call-button, .outline-button { min-height: 44px; }

            .seller-mini { padding: 11px; }
            .lower-grid { gap: 12px; margin-bottom: 15px; }

            .description-panel { padding: 18px 15px; }

            .section-tabs {
                gap: 22px;
                margin: -18px -15px 18px;
                padding: 0 15px;
            }

            .section-tabs a { min-height: 49px; font-size: 11px; }
            .section-title { font-size: 17px; }
            .description { font-size: 12px; line-height: 1.85; }

            .feature-list { gap: 11px 8px; }
            .feature-item { align-items: flex-start; font-size: 11px; }

            .seller-panel { padding: 17px; }
            .seller-avatar { width: 62px; height: 62px; flex-basis: 62px; font-size: 29px; }
            .seller-name { font-size: 15px; }

            .additional-panel { margin-bottom: 18px; padding: 17px 14px; }
            .additional-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
            .additional-item { padding: 10px; }

            .footer-inner { align-items: flex-start; flex-direction: column; gap: 5px; }

            #galleryFullscreen { padding: 55px 42px !important; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }

        /* Messaging modal */
        .message-modal[hidden] { display: none !important; }
        .message-modal { position: fixed; inset: 0; z-index: 1000; display: grid; place-items: center; padding: 18px; background: #020814d9; backdrop-filter: blur(8px); }
        .message-dialog { width: min(520px, 100%); padding: 24px; border: 1px solid #24518a; border-radius: 12px; background: linear-gradient(145deg, #0b1b31, #050d1a); box-shadow: 0 24px 90px #0009; }
        .message-dialog h3 { margin: 0 0 5px; font-size: 22px; }
        .message-dialog p { margin: 0 0 18px; color: var(--muted); font-size: 12px; }
        .message-dialog label { display: block; margin: 12px 0 6px; color: #c7d9f2; font-size: 12px; font-weight: 750; }
        .message-dialog textarea, .message-dialog input { width: 100%; padding: 12px; border: 1px solid #29476b; border-radius: 7px; background: #030b17; color: var(--text); outline: none; font: inherit; }
        .message-dialog textarea { min-height: 130px; resize: vertical; }
        .message-dialog textarea:focus, .message-dialog input:focus { border-color: var(--blue-light); box-shadow: 0 0 0 3px #0866ff22; }
        .message-dialog-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px; }
        .message-cancel { min-height: 40px; padding: 9px 14px; border: 1px solid var(--border); border-radius: 7px; background: transparent; color: var(--muted); cursor: pointer; }
        .message-error { color: #ffb7c0; font-size: 12px; margin-top: 6px; }

    </style>
</head>

<body>
<header class="site-header">
    <div class="container header-inner">
        <a class="logo" href="{{ route('home') }}" aria-label="MotoHub მთავარი">
            <span class="logo-mark">M</span>
            <span class="logo-text">Moto<span>Hub</span></span>
        </a>

        <nav class="main-nav" aria-label="მთავარი მენიუ">
            <a href="{{ route('home') }}">მთავარი</a>
            <a href="{{ route('motorcycles.index') }}">მოტოციკლები</a>
            <a href="{{ route('parts.index') }}">ნაწილები</a>
            <a href="{{ route('rentals.index') }}">გაქირავება</a>
            <a href="{{ route('service-centers.index') }}">სახელოსნოები</a>
        </nav>

        <div class="header-actions">
            <a class="header-icon" href="{{ route('motorcycles.index') }}" aria-label="მოტოციკლების ძიება">⌕</a>
            <a class="header-icon" href="{{ route('messages.index') }}" aria-label="შეტყობინებები">✉</a>

            @auth
                <a class="login-link" href="{{ route('account.index') }}">ჩემი ანგარიში</a>
            @else
                <a class="login-link" href="{{ route('login') }}">შესვლა</a>
            @endauth

            <a class="primary-button" href="{{ route('account.motorcycles.create') }}">განცხადების დამატება</a>
        </div>
    </div>
</header>

<main class="container">
    <nav class="breadcrumb" aria-label="ნავიგაცია">
        <a href="{{ route('home') }}">⌂</a>
        <span class="breadcrumb-separator">›</span>
        <a href="{{ route('home') }}">მთავარი</a>
        <span class="breadcrumb-separator">›</span>
        <a href="{{ route('motorcycles.index') }}">მოტოციკლები</a>
        <span class="breadcrumb-separator">›</span>
        <span>{{ $motorcycle->title }}</span>
    </nav>

    <section class="listing-heading">
        <div class="eyebrow">MotoHub / მოტოციკლის განცხადება</div>
        <h1>{{ $motorcycle->title }}</h1>

        <div class="listing-meta">
            @if ($motorcycle->brand)
                <span class="meta-chip">{{ $motorcycle->brand }}</span>
            @endif

            @if ($motorcycle->year)
                <span class="meta-chip">{{ $motorcycle->year }} წელი</span>
            @endif

            <span class="meta-chip">
                {{ $motorcycle->condition === 'new' ? 'ახალი' : 'მეორადი' }}
            </span>

            @if ($motorcycle->location)
                <span class="meta-chip">⌖ {{ $motorcycle->location }}</span>
            @endif
        </div>
    </section>

    <section style="display:flex;flex-wrap:wrap;gap:10px;margin:0 0 24px">
        <a href="{{ route('motorcycles.passport', $motorcycle->slug) }}" style="display:inline-flex;align-items:center;gap:8px;padding:11px 15px;border:1px solid #243653;border-radius:9px;background:#0d1422;color:#f4f8ff;font-weight:700">📘 ციფრული პასპორტი</a>
        <a href="{{ route('motorcycles.compare') }}" style="display:inline-flex;align-items:center;gap:8px;padding:11px 15px;border:1px solid #243653;border-radius:9px;background:#0d1422;color:#f4f8ff;font-weight:700">⇄ მოტოციკლების შედარება</a>
        <a href="{{ route('moto-assistant.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:11px 15px;border:1px solid #1677ff;border-radius:9px;background:#0b1c36;color:#42a5ff;font-weight:700">✦ მოტო ასისტენტი</a>
    </section>

    @php
        $allPhotos = collect();

        if ($motorcycle->image_path) {
            $allPhotos->push($motorcycle->image_path);
        }

        foreach ($motorcycle->images as $photo) {
            if ($photo->image_path) {
                $allPhotos->push($photo->image_path);
            }
        }

        $allPhotos = $allPhotos->unique()->values();

        $fallbackPhoto = 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1200&q=85';

        $photoUrls = $allPhotos->isNotEmpty()
            ? $allPhotos->map(fn ($photo) => \Illuminate\Support\Facades\Storage::url($photo))->values()
            : collect([$fallbackPhoto]);

        $contactPhone = $motorcycle->user?->phone ?: $motorcycle->partner?->phone;
        $phoneForCall = $contactPhone ? preg_replace('/[^\d+]/', '', $contactPhone) : '';
        $hasPhone = $phoneForCall !== '' && preg_match('/\d/', $phoneForCall);
    @endphp

    <div class="detail-grid">
        <section class="photo-panel" aria-label="მოტოციკლის ფოტოგალერეა">
            <div class="gallery-stage" id="motorcycleGallery">
                <img
                    class="main-photo"
                    id="mainMotorcyclePhoto"
                    src="{{ $photoUrls->first() }}"
                    alt="{{ $motorcycle->title }}"
                    fetchpriority="high"
                    tabindex="0"
                >

                <span class="photo-badge">MotoHub განცხადება</span>

                @if ($photoUrls->count() > 1)
                    <button type="button" class="gallery-arrow gallery-prev" id="galleryPrev" aria-label="წინა ფოტო">‹</button>
                    <button type="button" class="gallery-arrow gallery-next" id="galleryNext" aria-label="შემდეგი ფოტო">›</button>
                @endif

                <span class="gallery-counter" id="galleryCounter">1 / {{ $photoUrls->count() }}</span>
            </div>

            @if ($photoUrls->count() > 1)
                <div class="gallery-thumbnails" id="galleryThumbnails">
                    @foreach ($photoUrls as $index => $photoUrl)
                        <button
                            type="button"
                            class="gallery-thumb {{ $index === 0 ? 'is-active' : '' }}"
                            data-index="{{ $index }}"
                            aria-label="ფოტო {{ $index + 1 }}"
                            aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"
                        >
                            <img
                                src="{{ $photoUrl }}"
                                alt="{{ $motorcycle->title }} — ფოტო {{ $index + 1 }}"
                                loading="lazy"
                            >
                        </button>
                    @endforeach
                </div>
            @endif

            <script type="application/json" id="motorcycleGalleryData">@json($photoUrls)</script>
        </section>

        <aside class="info-panel">
            <div class="info-heading">
                <div>
                    <h2 class="info-title">{{ $motorcycle->title }}</h2>

                    <div class="info-meta">
                        {{ $motorcycle->year ?: '—' }}
                        <span>·</span>
                        {{ $motorcycle->engine_cc ? $motorcycle->engine_cc . ' cc' : 'მოცულობა —' }}
                        <span>·</span>
                        {{ $motorcycle->condition === 'new' ? 'ახალი' : 'მეორადი' }}
                    </div>
                </div>

                <div class="quick-actions">
                    @auth
                    <form method="POST" action="{{ route('favorites.motorcycles.toggle', $motorcycle->id) }}">@csrf
                        <button class="icon-button" type="submit" aria-label="ფავორიტებში დამატება" title="ფავორიტებში დამატება">♡</button>
                    </form>
                    @else
                    <a class="icon-button" href="{{ route('login') }}" aria-label="შესვლა ფავორიტებში დასამატებლად" title="შესვლა ფავორიტებში დასამატებლად">♡</a>
                    @endauth
                    <button class="icon-button" type="button" id="shareButton" aria-label="გაზიარება" title="გაზიარება">♧</button>
                </div>
            </div>

            <div class="price">
                {{ number_format((float) $motorcycle->price, 0, '.', ' ') }} ₾
            </div>

            <div class="specs">
                <div class="spec">
                    <span class="spec-icon">◉</span>
                    <div>
                        <span class="spec-label">გარბენი</span>
                        <span class="spec-value">
                            {{ $motorcycle->mileage !== null
                                ? number_format((float) $motorcycle->mileage, 0, '.', ' ') . ' კმ'
                                : 'მითითებული არ არის' }}
                        </span>
                    </div>
                </div>

                <div class="spec">
                    <span class="spec-icon">▦</span>
                    <div>
                        <span class="spec-label">გამოშვების წელი</span>
                        <span class="spec-value">{{ $motorcycle->year ?: 'მითითებული არ არის' }}</span>
                    </div>
                </div>

                <div class="spec">
                    <span class="spec-icon">⚙</span>
                    <div>
                        <span class="spec-label">ძრავის მოცულობა</span>
                        <span class="spec-value">{{ $motorcycle->engine_cc ? $motorcycle->engine_cc . ' cc' : 'მითითებული არ არის' }}</span>
                    </div>
                </div>

                <div class="spec">
                    <span class="spec-icon">◆</span>
                    <div>
                        <span class="spec-label">მდგომარეობა</span>
                        <span class="spec-value">{{ $motorcycle->condition === 'new' ? 'ახალი' : 'მეორადი' }}</span>
                    </div>
                </div>

                <div class="spec">
                    <span class="spec-icon">⌖</span>
                    <div>
                        <span class="spec-label">მდებარეობა</span>
                        <span class="spec-value">{{ $motorcycle->location ?: 'მითითებული არ არის' }}</span>
                    </div>
                </div>

                <div class="spec">
                    <span class="spec-icon">▦</span>
                    <div>
                        <span class="spec-label">განცხადების თარიღი</span>
                        <span class="spec-value">{{ $motorcycle->published_at ? $motorcycle->published_at->format('d.m.Y') : '—' }}</span>
                    </div>
                </div>
            </div>

            <div class="contact-row">
                @if ($hasPhone)
                    <a class="call-button" href="tel:{{ $phoneForCall }}">
                        <span aria-hidden="true">☎</span>
                        <span>დაგვიკავშირდით</span>
                    </a>
                @else
                    <span class="call-button" style="opacity:.65;cursor:default">
                        <span aria-hidden="true">☎</span>
                        <span>ტელეფონი მითითებული არ არის</span>
                    </span>
                @endif

                <button type="button" class="outline-button" id="messageButton">
                    <span aria-hidden="true">▤</span>
                    <span>შეტყობინება</span>
                </button>
            </div>

            @if ($hasPhone)
                <a class="phone-number" href="tel:{{ $phoneForCall }}">{{ $contactPhone }}</a>
            @endif

            <div class="seller-mini">
@if ($motorcycle->partner?->logo_path)
    <div class="seller-mini-icon" style="overflow:hidden;padding:0;">
        <img src="{{ asset('storage/' . $motorcycle->partner->logo_path) }}"
             alt="{{ $motorcycle->partner->name }}"
             style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
    </div>
@elseif ($motorcycle->user?->avatar_path)
    <div class="seller-mini-icon" style="overflow:hidden;padding:0;">
        <img src="{{ asset('storage/' . $motorcycle->user->avatar_path) }}"
             alt="{{ $motorcycle->user->name }}"
             style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
    </div>
@else
    <div class="seller-mini-icon"></div>
@endif
                <div>
                    <strong>{{ $motorcycle->partner?->name ?: ($motorcycle->user?->name ?: 'MotoHub გამყიდველი') }}</strong>
                    <small>განცხადების ავტორი · მოტოციკლის გამყიდველი</small>
                </div>
            </div>
        </aside>
    </div>

    <div class="lower-grid">
        <section class="description-panel" id="description">
            <nav class="section-tabs" aria-label="განცხადების სექციები">
                <a class="active" href="#description">აღწერა</a>
                <a href="#additional">მახასიათებლები</a>
                <a href="#seller">გამყიდველი</a>
            </nav>

            <h2 class="section-title">აღწერა</h2>

            @if ($motorcycle->description)
                <div class="description">{{ $motorcycle->description }}</div>
            @else
                <p class="muted">განცხადების ავტორს აღწერა არ დაუმატებია.</p>
            @endif

            <div class="feature-list">
                <div class="feature-item"><span class="feature-check">✓</span> მოტოციკლის მონაცემები</div>
                <div class="feature-item"><span class="feature-check">✓</span> განცხადების დეტალები</div>
                <div class="feature-item"><span class="feature-check">✓</span> ფოტო-გალერეა</div>
                <div class="feature-item"><span class="feature-check">✓</span> გამყიდველთან დაკავშირება</div>
            </div>
        </section>

        <section class="seller-panel" id="seller">
            <div class="seller-header">
                @if ($motorcycle->partner?->logo_path)
    <div class="seller-avatar" style="overflow:hidden;padding:0;">
        <img src="{{ asset('storage/' . $motorcycle->partner->logo_path) }}"
             alt="{{ $motorcycle->partner->name }}"
             style="width:100%;height:100%;object-fit:cover;">
    </div>
@elseif ($motorcycle->user?->avatar_path)
    <div class="seller-avatar" style="overflow:hidden;padding:0;">
        <img src="{{ asset('storage/' . $motorcycle->user->avatar_path) }}"
             alt="{{ $motorcycle->user->name }}"
             style="width:100%;height:100%;object-fit:cover;">
    </div>
@else
    <div class="seller-avatar">M</div>
@endif

                <div>
                    <h2 class="seller-name">{{ $motorcycle->partner?->name ?: ($motorcycle->user?->name ?: 'MotoHub გამყიდველი') }}</h2>
                    <div class="seller-subtitle">განცხადების ავტორი</div>
                    <div class="seller-rating">MotoHub <small>· გამყიდველის პროფილი</small></div>
                    <span class="seller-badge">მოტოციკლის განცხადება</span>
                </div>
            </div>

            <div class="seller-buttons">
                @if ($hasPhone)
                    <a class="call-button" href="tel:{{ $phoneForCall }}">☎ &nbsp; დაგვიკავშირდით</a>
                @endif

                @auth
                    @if ((int) auth()->id() !== (int) $motorcycle->user_id)
                        <button type="button" class="outline-button" id="sellerMessageButton">▤ &nbsp; შეტყობინება</button>
                    @endif
                @else
                    <a class="outline-button" href="{{ route('login') }}">▤ &nbsp; შეტყობინება</a>
                @endauth
            </div>

            <div class="seller-details">
                <span><b>✉</b> განცხადების ინფორმაცია</span>
                <span><b>♙</b> გამყიდველი</span>
                <span><b>↗</b> გაზიარება</span>
            </div>
        </section>
    </div>

        @auth
            @if ((int) auth()->id() !== (int) $motorcycle->user_id)
                <div class="message-modal" id="messageModal" hidden>
                    <section class="message-dialog" role="dialog" aria-modal="true" aria-labelledby="messageModalTitle">
                        <h3 id="messageModalTitle">მისწერე გამყიდველს</h3>
                        <p>შეტყობინება გაიგზავნება MotoHub-ის პირად მიმოწერაში.</p>
                        <form method="POST" action="{{ route('messages.start') }}">
                            @csrf
                            <input type="hidden" name="recipient_id" value="{{ $motorcycle->user_id }}">
                            <input type="hidden" name="motorcycle_id" value="{{ $motorcycle->id }}">
                            <input type="hidden" name="subject" value="მოტოციკლი: {{ $motorcycle->title }}">
                            <label for="sellerMessageBody">შეტყობინება</label>
                            <textarea id="sellerMessageBody" name="body" maxlength="5000" required placeholder="გამარჯობა, მაინტერესებს თქვენი მოტოციკლი...">{{ old('body') }}</textarea>
                            @error('body')<div class="message-error">{{ $message }}</div>@enderror
                            <div class="message-dialog-actions">
                                <button type="button" class="message-cancel" id="closeMessageModal">გაუქმება</button>
                                <button type="submit" class="primary-button">გაგზავნა ↗</button>
                            </div>
                        </form>
                    </section>
                </div>
            @endif
        @endauth

    <section class="additional-panel" id="additional">
        <h2 class="section-title">დამატებითი მახასიათებლები</h2>

        <div class="additional-grid">
            <div class="additional-item">
                <span class="additional-label">ბრენდი</span>
                <span class="additional-value">{{ $motorcycle->brand ?: '—' }}</span>
            </div>

            <div class="additional-item">
                <span class="additional-label">მოდელი</span>
                <span class="additional-value">{{ $motorcycle->model ?: '—' }}</span>
            </div>

            <div class="additional-item">
                <span class="additional-label">გამოშვების წელი</span>
                <span class="additional-value">{{ $motorcycle->year ?: '—' }}</span>
            </div>

            <div class="additional-item">
                <span class="additional-label">ძრავის მოცულობა</span>
                <span class="additional-value">{{ $motorcycle->engine_cc ? $motorcycle->engine_cc . ' cc' : '—' }}</span>
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container footer-inner">
        <span class="footer-brand">MotoHub — მოტოციკლების სამყარო</span>
        <span>© {{ date('Y') }} MotoHub. ყველა უფლება დაცულია.</span>
    </div>
</footer>

<div
    id="galleryFullscreen"
    hidden
    role="dialog"
    aria-modal="true"
    aria-label="მოტოციკლის ფოტოგალერეა"
    style="position:fixed;inset:0;z-index:9999;background:rgba(0,5,14,.97);display:flex;align-items:center;justify-content:center;padding:60px 64px;"
>
    <button type="button" id="fullscreenClose" aria-label="დახურვა"
        style="position:absolute;top:18px;right:22px;width:46px;height:46px;font-size:30px;cursor:pointer;">×</button>

    <button type="button" id="fullscreenPrev" aria-label="წინა ფოტო"
        style="position:absolute;left:16px;top:50%;transform:translateY(-50%);width:46px;height:46px;font-size:30px;cursor:pointer;">‹</button>

    <img id="fullscreenPhoto" alt="მოტოციკლის დიდი ფოტო"
        style="display:block;max-width:100%;max-height:85vh;object-fit:contain;">

    <button type="button" id="fullscreenNext" aria-label="შემდეგი ფოტო"
        style="position:absolute;right:16px;top:50%;transform:translateY(-50%);width:46px;height:46px;font-size:30px;cursor:pointer;">›</button>

    <span id="fullscreenCounter"
        style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);padding:7px 14px;border-radius:8px;background:#0b1c32;color:#fff;font-size:14px;">1 / 1</span>
</div>

<script>
(() => {
    const gallery = document.getElementById('motorcycleGallery');
    const mainPhoto = document.getElementById('mainMotorcyclePhoto');
    const dataElement = document.getElementById('motorcycleGalleryData');

    if (!gallery || !mainPhoto || !dataElement) return;

    let photos;

    try {
        photos = JSON.parse(dataElement.textContent || '[]');
    } catch (error) {
        console.error('გალერეის ფოტოების სია ვერ ჩაიტვირთა.', error);
        return;
    }

    if (!photos.length) return;

    const thumbs = [...document.querySelectorAll('.gallery-thumb')];
    const counter = document.getElementById('galleryCounter');
    const overlay = document.getElementById('galleryFullscreen');
    const fullscreenImage = document.getElementById('fullscreenPhoto');
    const fullscreenCounter = document.getElementById('fullscreenCounter');

    let current = 0;
    let fullscreenIndex = 0;
    let touchStartX = 0;

    function showPhoto(index) {
        current = (index + photos.length) % photos.length;
        mainPhoto.src = photos[current];

        if (counter) counter.textContent = `${current + 1} / ${photos.length}`;

        thumbs.forEach((thumb, i) => {
            const active = i === current;
            thumb.classList.toggle('is-active', active);
            thumb.setAttribute('aria-pressed', String(active));
        });
    }

    function showFullscreenPhoto(index) {
        fullscreenIndex = (index + photos.length) % photos.length;
        fullscreenImage.src = photos[fullscreenIndex];

        if (fullscreenCounter) {
            fullscreenCounter.textContent = `${fullscreenIndex + 1} / ${photos.length}`;
        }
    }

    function openFullscreen() {
        if (!overlay) return;
        overlay.hidden = false;
        showFullscreenPhoto(current);
        document.body.style.overflow = 'hidden';
        document.getElementById('fullscreenClose')?.focus();
    }

    function closeFullscreen() {
        if (!overlay) return;
        overlay.hidden = true;
        document.body.style.overflow = '';
        mainPhoto.focus();
    }

    document.getElementById('galleryPrev')?.addEventListener('click', () => showPhoto(current - 1));
    document.getElementById('galleryNext')?.addEventListener('click', () => showPhoto(current + 1));

    thumbs.forEach(thumb => {
        thumb.addEventListener('click', () => showPhoto(Number(thumb.dataset.index)));
    });

    gallery.addEventListener('touchstart', event => {
        touchStartX = event.changedTouches[0].screenX;
    }, { passive: true });

    gallery.addEventListener('touchend', event => {
        const difference = event.changedTouches[0].screenX - touchStartX;

        if (Math.abs(difference) > 45 && photos.length > 1) {
            showPhoto(current + (difference < 0 ? 1 : -1));
        }
    }, { passive: true });

    mainPhoto.addEventListener('click', openFullscreen);

    mainPhoto.addEventListener('keydown', event => {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            openFullscreen();
        }
    });

    document.getElementById('fullscreenPrev')?.addEventListener('click', () => {
        showFullscreenPhoto(fullscreenIndex - 1);
    });

    document.getElementById('fullscreenNext')?.addEventListener('click', () => {
        showFullscreenPhoto(fullscreenIndex + 1);
    });

    document.getElementById('fullscreenClose')?.addEventListener('click', closeFullscreen);

    overlay?.addEventListener('click', event => {
        if (event.target === overlay) closeFullscreen();
    });

    document.addEventListener('keydown', event => {
        if (!overlay || overlay.hidden) return;

        if (event.key === 'Escape') closeFullscreen();
        if (event.key === 'ArrowLeft') showFullscreenPhoto(fullscreenIndex - 1);
        if (event.key === 'ArrowRight') showFullscreenPhoto(fullscreenIndex + 1);
    });

    // Share: use native sharing when available, otherwise copy the page URL.
    async function shareListing() {
        const shareData = {
            title: document.title,
            text: 'ნახე ეს მოტოციკლის განცხადება MotoHub-ზე',
            url: window.location.href
        };

        try {
            if (navigator.share) {
                await navigator.share(shareData);
            } else if (navigator.clipboard) {
                await navigator.clipboard.writeText(shareData.url);
                alert('განცხადების ბმული დაკოპირდა.');
            } else {
                window.prompt('დააკოპირე განცხადების ბმული:', shareData.url);
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('გაზიარება ვერ შესრულდა.', error);
            }
        }
    }

    document.getElementById('shareButton')?.addEventListener('click', shareListing);
    document.querySelectorAll('.seller-details span:last-child').forEach(item => {
        item.style.cursor = 'pointer';
        item.addEventListener('click', shareListing);
    });

    // Favorites are persisted through the authenticated MotoHub endpoint.

    // Open the authenticated private-message composer from the seller panel.
    const messageModal = document.getElementById('messageModal');
    const messageBody = document.getElementById('sellerMessageBody');
    const openMessageModal = () => {
        if (!messageModal) return;
        messageModal.hidden = false;
        document.body.style.overflow = 'hidden';
        window.setTimeout(() => messageBody?.focus(), 50);
    };
    const closeMessageModal = () => {
        if (!messageModal) return;
        messageModal.hidden = true;
        document.body.style.overflow = '';
    };
    document.getElementById('sellerMessageButton')?.addEventListener('click', openMessageModal);
    document.getElementById('closeMessageModal')?.addEventListener('click', closeMessageModal);
    messageModal?.addEventListener('click', event => {
        if (event.target === messageModal) closeMessageModal();
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && messageModal && !messageModal.hidden) closeMessageModal();
    });

    showPhoto(0);
})();
</script>
</body>
</html>
