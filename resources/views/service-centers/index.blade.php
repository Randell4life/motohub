@extends('layouts.service-centers')

@section('title', 'მოტოსერვის ცენტრები | MotoHub')

@push('head')
<style>
    :root {
        --sc-bg: #090b0f;
        --sc-surface: #11151b;
        --sc-surface-hover: #171d26;
        --sc-border: rgba(255,255,255,.09);
        --sc-text: #f5f7fa;
        --sc-muted: #9ba5b3;
        --sc-accent: #ff812f;
        --sc-accent-soft: #ffb17b;
    }

    .sc-page,
    .sc-page * {
        box-sizing: border-box;
    }

    .sc-page {
        color: var(--sc-text);
        padding: 12px 0 70px;
    }

    .sc-page a {
        text-decoration: none;
    }

    .sc-container {
        width: 100%;
        max-width: 1440px;
        margin-inline: auto;
    }

    /* HERO */

    .sc-hero {
        position: relative;
        isolation: isolate;
        display: flex;
        min-height: 490px;
        flex-direction: column;
        justify-content: space-between;
        padding: clamp(25px, 4.5vw, 58px);
        overflow: hidden;
        border: 1px solid var(--sc-border);
        border-radius: 18px;
        background:
            linear-gradient(90deg,
                rgba(7,9,13,.98) 0%,
                rgba(7,9,13,.89) 42%,
                rgba(7,9,13,.40) 100%),
            linear-gradient(0deg, rgba(7,9,13,.88), transparent 55%),
            url('https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1900&q=85')
            center 55% / cover no-repeat,
            #11151b;
    }

    .sc-hero::after {
        content: "";
        position: absolute;
        z-index: -1;
        right: -110px;
        bottom: -220px;
        width: 430px;
        height: 430px;
        border: 1px solid rgba(255,129,47,.18);
        border-radius: 50%;
        box-shadow:
            0 0 0 45px rgba(255,129,47,.025),
            0 0 0 90px rgba(255,129,47,.018);
        pointer-events: none;
    }

    .sc-hero-content {
        max-width: 710px;
    }

    .sc-eyebrow {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--sc-accent-soft);
        font-size: 10px;
        font-weight: 850;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .sc-eyebrow::before {
        content: "";
        width: 24px;
        height: 3px;
        border-radius: 3px;
        background: var(--sc-accent);
    }

    .sc-hero h1 {
        margin: 25px 0 17px;
        color: #fff;
        font-size: clamp(38px, 5.3vw, 70px);
        line-height: 1.08;
        font-weight: 950;
        letter-spacing: -2px;
    }

    .sc-hero h1 span {
        display: block;
        color: var(--sc-accent);
    }

    .sc-hero-description {
        max-width: 490px;
        margin: 0 0 28px;
        color: #c0c7d1;
        font-size: 13px;
        line-height: 1.95;
    }

    /* SEARCH */

    .sc-search {
        display: grid;
        grid-template-columns: minmax(0,.8fr) minmax(0,1.2fr) auto;
        align-items: stretch;
        gap: 5px;
        max-width: 850px;
        padding: 7px;
        border: 1px solid rgba(255,255,255,.14);
        border-radius: 11px;
        background: rgba(12,15,20,.94);
        box-shadow: 0 15px 45px rgba(0,0,0,.25);
        backdrop-filter: blur(14px);
    }

    .sc-search-field {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
        padding: 9px 12px;
        color: var(--sc-accent);
    }

    .sc-search-field + .sc-search-field {
        border-left: 1px solid var(--sc-border);
    }

    .sc-field-icon {
        flex-shrink: 0;
        font-size: 19px;
    }

    .sc-field-content {
        display: block;
        flex: 1;
        min-width: 0;
    }

    .sc-field-content small {
        display: block;
        margin-bottom: 5px;
        color: #aab3bf;
        font-size: 10px;
        font-weight: 800;
    }

    .sc-field-content input {
        display: block;
        width: 100%;
        min-width: 0;
        padding: 0;
        border: 0;
        outline: 0;
        background: transparent;
        color: #fff;
        font: inherit;
        font-size: 12px;
        box-shadow: none;
    }

    .sc-field-content input:focus {
        border: 0;
        outline: none;
        box-shadow: none;
    }

    .sc-field-content input::placeholder {
        color: #788392;
    }

    .sc-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 45px;
        padding: 12px 18px;
        border: 1px solid transparent;
        border-radius: 7px;
        background: var(--sc-accent);
        color: #19110c !important;
        font-size: 12px;
        font-weight: 900;
        line-height: 1.5;
        text-align: center;
        cursor: pointer;
        transition: background .2s, transform .2s, box-shadow .2s;
    }

    .sc-button:hover {
        transform: translateY(-2px);
        background: #ff9a5a;
        box-shadow: 0 8px 25px rgba(255,129,47,.15);
    }

    .sc-hero-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-top: 28px;
        padding-top: 17px;
        border-top: 1px solid rgba(255,255,255,.13);
    }

    .sc-result-note {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #c5ccd6;
        font-size: 11px;
    }

    .sc-result-note strong {
        color: #fff;
    }

    .sc-live-dot {
        width: 7px;
        height: 7px;
        flex-shrink: 0;
        border-radius: 50%;
        background: var(--sc-accent);
        box-shadow: 0 0 12px rgba(255,129,47,.45);
    }

    .sc-hero-signature {
        color: #8f99a7;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    /* BENEFITS */

    .sc-benefits {
        display: grid;
        grid-template-columns: repeat(4, minmax(0,1fr));
        gap: 12px;
        margin: 18px 0 66px;
    }

    .sc-benefit {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        min-width: 0;
        padding: 19px 15px;
        border: 1px solid var(--sc-border);
        border-radius: 10px;
        background: linear-gradient(145deg,#151a22,#101319);
        transition: border-color .2s, transform .2s;
    }

    .sc-benefit:hover {
        transform: translateY(-3px);
        border-color: rgba(255,129,47,.3);
    }

    .sc-benefit-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 36px;
        width: 36px;
        height: 36px;
        border: 1px solid rgba(255,129,47,.23);
        border-radius: 8px;
        background: rgba(255,129,47,.07);
        color: var(--sc-accent);
        font-size: 17px;
    }

    .sc-benefit h3 {
        margin: 0 0 5px;
        color: #f3f5f8;
        font-size: 11px;
        line-height: 1.6;
        font-weight: 850;
    }

    .sc-benefit p {
        margin: 0;
        color: var(--sc-muted);
        font-size: 10px;
        line-height: 1.8;
    }

    /* SECTION HEADER */

    .sc-section-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 23px;
    }

    .sc-section-title {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 11px;
    }

    .sc-section-mark {
        width: 4px;
        height: 29px;
        border-radius: 3px;
        background: var(--sc-accent);
    }

    .sc-section-title h2 {
        margin: 0;
        color: #f5f7fa;
        font-size: clamp(23px, 3vw, 33px);
        line-height: 1.3;
        font-weight: 900;
        letter-spacing: -.8px;
    }

    .sc-count {
        display: inline-flex;
        align-items: center;
        min-height: 25px;
        padding: 4px 9px;
        border: 1px solid rgba(255,129,47,.23);
        border-radius: 5px;
        background: rgba(255,129,47,.06);
        color: var(--sc-accent-soft);
        font-size: 10px;
        font-weight: 850;
    }

    .sc-section-caption {
        color: #8f99a7;
        font-size: 10px;
    }

    /* CENTER CARDS */

    .sc-card-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0,1fr));
        align-items: stretch;
        gap: 18px;
    }

    .sc-center-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        overflow: hidden;
        border: 1px solid var(--sc-border);
        border-radius: 12px;
        background: linear-gradient(155deg,#151a22,#0e1117);
        transition: transform .22s, border-color .22s, box-shadow .22s;
    }

    .sc-center-card:hover {
        transform: translateY(-4px);
        border-color: rgba(255,129,47,.35);
        box-shadow: 0 18px 40px rgba(0,0,0,.22);
    }

    .sc-center-photo {
        position: relative;
        height: 205px;
        overflow: hidden;
        background-color: #1b222c;
        background-image:
            linear-gradient(0deg,rgba(7,9,13,.72),transparent 65%),
            url('https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=900&q=80');
        background-position: center;
        background-size: cover;
        transition: background-position .3s;
    }

    .sc-center-card:nth-child(3n + 2) .sc-center-photo {
        background-image:
            linear-gradient(0deg,rgba(7,9,13,.72),transparent 65%),
            url('https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=900&q=80');
    }

    .sc-center-card:nth-child(3n) .sc-center-photo {
        background-image:
            linear-gradient(0deg,rgba(7,9,13,.72),transparent 65%),
            url('https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=900&q=80');
    }

    .sc-center-card:hover .sc-center-photo {
        background-position: center 46%;
    }

    .sc-verified {
        position: absolute;
        top: 12px;
        left: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 9px;
        border: 1px solid rgba(255,129,47,.32);
        border-radius: 5px;
        background: rgba(12,15,20,.93);
        color: var(--sc-accent-soft);
        font-size: 9px;
        font-weight: 850;
        backdrop-filter: blur(8px);
    }

    .sc-photo-label {
        position: absolute;
        right: 12px;
        bottom: 12px;
        color: rgba(255,255,255,.85);
        font-size: 9px;
        font-weight: 850;
        letter-spacing: 1.5px;
    }

    .sc-center-body {
        display: flex;
        flex: 1;
        flex-direction: column;
        padding: 19px;
    }

    .sc-center-title {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .sc-center-title h3 {
        margin: 0;
        color: #f5f7fa;
        font-size: 16px;
        line-height: 1.55;
        font-weight: 850;
        overflow-wrap: anywhere;
    }

    .sc-card-arrow {
        flex: 0 0 auto;
        color: var(--sc-accent);
        font-size: 23px;
        line-height: 1.1;
        transition: transform .2s;
    }

    .sc-center-card:hover .sc-card-arrow {
        transform: translateX(3px);
    }

    .sc-center-subtitle {
        margin-top: 7px;
        color: #8994a2;
        font-size: 10px;
    }

    .sc-location {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin: 17px 0;
        color: #c4cbd5;
        font-size: 11px;
        line-height: 1.8;
        overflow-wrap: anywhere;
    }

    .sc-location-icon {
        flex: 0 0 auto;
        color: var(--sc-accent);
        font-size: 15px;
    }

    .sc-card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 14px;
        padding: 12px 0;
        border-top: 1px solid var(--sc-border);
        border-bottom: 1px solid var(--sc-border);
        color: #aeb7c3;
        font-size: 10px;
        line-height: 1.7;
    }

    .sc-services {
        margin: 7px 0 17px;
    }

    .sc-service-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 11px 0;
        border-bottom: 1px solid rgba(255,255,255,.055);
        color: #d9dee6;
        font-size: 11px;
        line-height: 1.7;
    }

    .sc-service-row:last-child {
        border-bottom: 0;
    }

    .sc-service-name {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .sc-service-row strong {
        flex: 0 0 auto;
        color: var(--sc-accent-soft);
        font-size: 10px;
        text-align: right;
        white-space: nowrap;
    }

    .sc-no-services {
        padding: 12px 0;
        color: #8f99a7;
        font-size: 11px;
        line-height: 1.8;
    }

    .sc-card-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: auto;
        padding: 12px 13px;
        border: 1px solid rgba(255,129,47,.27);
        border-radius: 7px;
        background: rgba(255,129,47,.045);
        color: var(--sc-accent-soft) !important;
        font-size: 11px;
        font-weight: 850;
        transition: background .2s, border-color .2s;
    }

    .sc-card-link:hover {
        border-color: rgba(255,129,47,.55);
        background: rgba(255,129,47,.11);
    }

    .sc-card-link-arrow {
        font-size: 17px;
    }

    /* EMPTY STATE */

    .sc-empty {
        grid-column: 1 / -1;
        padding: 48px 22px;
        border: 1px dashed #343c48;
        border-radius: 12px;
        background:
            radial-gradient(ellipse at 50% 0%,rgba(255,129,47,.07),transparent 60%),
            #10141b;
        text-align: center;
    }

    .sc-empty-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        margin-bottom: 15px;
        border: 1px solid rgba(255,129,47,.25);
        border-radius: 13px;
        background: rgba(255,129,47,.07);
        color: var(--sc-accent);
        font-size: 23px;
    }

    .sc-empty h3 {
        margin: 0 0 9px;
        color: #fff;
        font-size: 20px;
    }

    .sc-empty p {
        max-width: 430px;
        margin: 0 auto 19px;
        color: var(--sc-muted);
        font-size: 12px;
        line-height: 1.9;
    }

    .sc-empty-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .sc-button-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 11px 17px;
        border: 1px solid var(--sc-border);
        border-radius: 7px;
        color: #e0e5ec !important;
        font-size: 11px;
        font-weight: 800;
        transition: border-color .2s, background .2s;
    }

    .sc-button-secondary:hover {
        border-color: rgba(255,255,255,.25);
        background: rgba(255,255,255,.04);
    }

    /* PAGINATION */

    .sc-pagination {
        margin-top: 27px;
        overflow-x: auto;
    }

    .sc-pagination nav {
        display: flex;
        justify-content: center;
    }

    .sc-pagination svg {
        width: 17px;
        height: 17px;
    }

    /* PARTNER CTA */

    .sc-business-banner {
        position: relative;
        isolation: isolate;
        display: grid;
        grid-template-columns: minmax(0,1.15fr) minmax(250px,.85fr);
        align-items: center;
        gap: 35px;
        margin-top: 67px;
        padding: clamp(24px,4vw,42px);
        overflow: hidden;
        border: 1px solid rgba(255,129,47,.2);
        border-radius: 14px;
        background:
            radial-gradient(ellipse at 100% 0%,rgba(255,129,47,.11),transparent 45%),
            linear-gradient(125deg,#191d24,#101319 75%);
    }

    .sc-business-banner::after {
        content: "M";
        position: absolute;
        z-index: -1;
        top: -50px;
        right: 18%;
        color: rgba(255,255,255,.025);
        font-size: 240px;
        line-height: 1;
        font-weight: 950;
        pointer-events: none;
    }

    .sc-business-copy {
        max-width: 590px;
    }

    .sc-business-copy h2 {
        margin: 17px 0 13px;
        color: #f5f7fa;
        font-size: clamp(28px,3.3vw,41px);
        line-height: 1.2;
        font-weight: 950;
        letter-spacing: -1px;
    }

    .sc-business-copy h2 span {
        color: var(--sc-accent);
    }

    .sc-business-copy p {
        max-width: 480px;
        margin: 0 0 23px;
        color: #adb6c2;
        font-size: 12px;
        line-height: 1.95;
    }

    .sc-business-points {
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
        padding-left: 27px;
        border-left: 1px solid var(--sc-border);
    }

    .sc-business-point {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 0;
        border-bottom: 1px solid rgba(255,255,255,.07);
        color: #e1e5ec;
        font-size: 11px;
        font-weight: 750;
        line-height: 1.7;
    }

    .sc-business-point:last-child {
        border-bottom: 0;
    }

    .sc-point-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 35px;
        width: 35px;
        height: 35px;
        border: 1px solid rgba(255,129,47,.2);
        border-radius: 8px;
        background: rgba(255,129,47,.065);
        color: var(--sc-accent);
        font-size: 16px;
    }

    /* RESPONSIVE */

    @media (max-width: 1050px) {
        .sc-benefits {
            grid-template-columns: repeat(2,minmax(0,1fr));
        }

        .sc-card-grid {
            grid-template-columns: repeat(2,minmax(0,1fr));
        }

        .sc-business-banner {
            grid-template-columns: minmax(0,1fr);
            gap: 22px;
        }

        .sc-business-points {
            display: grid;
            grid-template-columns: repeat(3,minmax(0,1fr));
            gap: 10px;
            padding: 17px 0 0;
            border-top: 1px solid var(--sc-border);
            border-left: 0;
        }

        .sc-business-point {
            align-items: flex-start;
            border-bottom: 0;
        }
    }

    @media (max-width: 680px) {
        .sc-page {
            padding: 3px 0 42px;
        }

        .sc-hero {
            min-height: auto;
            padding: 28px 16px 16px;
            border-radius: 11px;
            background-position: center,center,60% center;
        }

        .sc-hero h1 {
            margin: 20px 0 14px;
            font-size: clamp(34px,9vw,46px);
            letter-spacing: -1.4px;
        }

        .sc-hero-description {
            font-size: 12px;
        }

        .sc-search {
            grid-template-columns: minmax(0,1fr);
            gap: 0;
            padding: 7px;
        }

        .sc-search-field {
            padding: 12px 8px;
        }

        .sc-search-field + .sc-search-field {
            border-top: 1px solid var(--sc-border);
            border-left: 0;
        }

        .sc-search .sc-button {
            width: 100%;
            margin-top: 7px;
        }

        .sc-hero-footer {
            align-items: flex-start;
            flex-direction: column;
            gap: 9px;
            margin-top: 21px;
        }

        .sc-benefits {
            gap: 9px;
            margin: 14px 0 43px;
        }

        .sc-benefit {
            flex-direction: column;
            gap: 10px;
            padding: 13px 11px;
        }

        .sc-benefit-icon {
            flex-basis: 33px;
            width: 33px;
            height: 33px;
        }

        .sc-section-heading {
            align-items: flex-start;
            flex-direction: column;
            gap: 9px;
            margin-bottom: 17px;
        }

        .sc-section-title {
            gap: 8px;
        }

        .sc-section-title h2 {
            font-size: 23px;
        }

        .sc-card-grid {
            grid-template-columns: minmax(0,1fr);
            gap: 14px;
        }

        .sc-center-photo {
            height: 215px;
        }

        .sc-center-body {
            padding: 16px;
        }

        .sc-business-banner {
            gap: 17px;
            margin-top: 43px;
            padding: 24px 17px;
            border-radius: 11px;
        }

        .sc-business-copy h2 {
            font-size: 29px;
        }

        .sc-business-points {
            grid-template-columns: minmax(0,1fr);
            gap: 0;
        }

        .sc-business-point {
            align-items: center;
            padding: 10px 0;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .sc-page *,
        .sc-page *::before,
        .sc-page *::after {
            transition-duration: .01ms !important;
        }
    }
</style>
@endpush

@section('content')
<div class="sc-page">
    <div class="sc-container">

        {{-- HERO --}}
        <section class="sc-hero">
            <div class="sc-hero-content">
                <div class="sc-eyebrow">
                    MOTOHUB / SERVICE NETWORK
                </div>

                <h1>
                    მოტოსერვისი
                    <span>ახალ დონეზე.</span>
                </h1>

                <p class="sc-hero-description">
                    იპოვე მოტოციკლის მოვლისა და შეკეთების
                    სერვისები ერთ სივრცეში. გაეცანი სახელოსნოებს,
                    შეადარე მომსახურებები და დაგეგმე ვიზიტი.
                </p>

                <form
                    class="sc-search"
                    method="GET"
                    action="{{ route('service-centers.index') }}"
                >
                    <label class="sc-search-field">
                        <span class="sc-field-icon" aria-hidden="true">⌖</span>
                        <span class="sc-field-content">
                            <small>ქალაქი</small>
                            <input
                                type="text"
                                name="city"
                                value="{{ request('city') }}"
                                placeholder="მაგ. თბილისი"
                                aria-label="ქალაქი"
                            >
                        </span>
                    </label>

                    <label class="sc-search-field">
                        <span class="sc-field-icon" aria-hidden="true">⚒</span>
                        <span class="sc-field-content">
                            <small>რა გჭირდება?</small>
                            <input
                                type="text"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="სახელოსნო ან მომსახურება"
                                aria-label="სახელოსნო ან მომსახურება"
                            >
                        </span>
                    </label>

                    <button class="sc-button" type="submit">
                        <span aria-hidden="true">⌕</span>
                        მოძებნა
                    </button>
                </form>
            </div>

            <div class="sc-hero-footer">
                <div class="sc-result-note">
                    <span class="sc-live-dot"></span>
                    <span>
                        ნაპოვნია
                        <strong>{{ $centers->total() }}</strong>
                        სერვისცენტრი
                    </span>
                </div>

                <span class="sc-hero-signature">
                    FIND YOUR SERVICE
                </span>
            </div>
        </section>

        {{-- BENEFITS --}}
        <section class="sc-benefits" aria-label="MotoHub-ის შესაძლებლობები">
            <article class="sc-benefit">
                <span class="sc-benefit-icon" aria-hidden="true">✓</span>
                <div>
                    <h3>გადამოწმებული ცენტრები</h3>
                    <p>გამოარჩიე ოფიციალურად გადამოწმებული პროფილები.</p>
                </div>
            </article>

            <article class="sc-benefit">
                <span class="sc-benefit-icon" aria-hidden="true">⌕</span>
                <div>
                    <h3>მარტივი ძებნა</h3>
                    <p>მოძებნე ქალაქისა და მომსახურების მიხედვით.</p>
                </div>
            </article>

            <article class="sc-benefit">
                <span class="sc-benefit-icon" aria-hidden="true">⚙</span>
                <div>
                    <h3>მომსახურებები</h3>
                    <p>გაეცანი ცენტრის მომსახურებებსა და ფასებს.</p>
                </div>
            </article>

            <article class="sc-benefit">
                <span class="sc-benefit-icon" aria-hidden="true">→</span>
                <div>
                    <h3>დეტალები და ჯავშნები</h3>
                    <p>გადადით ცენტრის გვერდზე დამატებითი ინფორმაციისთვის.</p>
                </div>
            </article>
        </section>

        {{-- LISTING --}}
        <section class="sc-centers">
            <div class="sc-section-heading">
                <div class="sc-section-title">
                    <span class="sc-section-mark"></span>
                    <h2>სერვისცენტრები</h2>
                    <span class="sc-count">{{ $centers->total() }} ცენტრი</span>
                </div>

                <span class="sc-section-caption">
                    MotoHub-ის სერვისების ქსელი
                </span>
            </div>

            <div class="sc-card-grid">
                @forelse($centers as $center)
                    <article class="sc-center-card">
                        <div
                            class="sc-center-photo"
                            role="img"
                            aria-label="{{ $center->name }}"
                        >
                            @if($center->is_verified)
                                <span class="sc-verified">
                                    ✓ გადამოწმებული
                                </span>
                            @endif

                            <span class="sc-photo-label">
                                MOTOHUB SERVICE
                            </span>
                        </div>

                        <div class="sc-center-body">
                            <div class="sc-center-title">
                                <h3>{{ $center->name }}</h3>
                                <span class="sc-card-arrow" aria-hidden="true">↗</span>
                            </div>

                            <div class="sc-center-subtitle">
                                მოტოციკლის მომსახურება
                            </div>

                            <div class="sc-location">
                                <span class="sc-location-icon" aria-hidden="true">⌖</span>
                                <span>
                                    {{ $center->city ?: 'ქალაქი მითითებული არ არის' }}
                                    @if($center->address)
                                        , {{ $center->address }}
                                    @endif
                                </span>
                            </div>

                            <div class="sc-card-meta">
                                <span>
                                    ⚙ {{ $center->services_count }} მომსახურება
                                </span>

                                @if($center->phone)
                                    <span>☎ {{ $center->phone }}</span>
                                @endif
                            </div>

                            <div class="sc-services">
                                @forelse($center->services->take(3) as $service)
                                    <div class="sc-service-row">
                                        <span class="sc-service-name">
                                            {{ $service->name }}
                                        </span>
                                        <strong>{{ $service->price_label }}</strong>
                                    </div>
                                @empty
                                    <div class="sc-no-services">
                                        მომსახურებების სია ჯერ არ არის დამატებული.
                                    </div>
                                @endforelse
                            </div>

                            <a
                                class="sc-card-link"
                                href="{{ route('service-centers.show', $center->slug) }}"
                            >
                                <span>ნახე დეტალები და დაჯავშნა</span>
                                <span class="sc-card-link-arrow" aria-hidden="true">→</span>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="sc-empty">
                        <div class="sc-empty-icon" aria-hidden="true">⌕</div>

                        <h3>შედეგები ვერ მოიძებნა</h3>

                        <p>
                            სცადე სხვა ქალაქი ან საძიებო სიტყვა.
                            შეგიძლია გაასუფთავო ძებნა ან
                            დაარეგისტრირო შენი სახელოსნო MotoHub-ზე.
                        </p>

                        <div class="sc-empty-actions">
                            <a
                                class="sc-button-secondary"
                                href="{{ route('service-centers.index') }}"
                            >
                                ფილტრების გასუფთავება
                            </a>

                            <a
                                class="sc-button"
                                href="{{ route('my-service-center.register') }}"
                            >
                                დაამატე სახელოსნო →
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($centers->hasPages())
                <div class="sc-pagination">
                    {{ $centers->withQueryString()->links() }}
                </div>
            @endif
        </section>

        {{-- PARTNER CTA --}}
        <section class="sc-business-banner">
            <div class="sc-business-copy">
                <div class="sc-eyebrow">MOTOHUB FOR BUSINESS</div>

                <h2>
                    შენი სახელოსნო.
                    <span>მეტი შესაძლებლობა.</span>
                </h2>

                <p>
                    გააცანი შენი მომსახურება მოტომოყვარულებს,
                    მართე სერვისების სია და სამუშაო საათები
                    და მიიღე ჯავშნები MotoHub-ის პლატფორმიდან.
                </p>

                <a
                    class="sc-button"
                    href="{{ route('my-service-center.register') }}"
                >
                    გახდი პარტნიორი
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="sc-business-points">
                <div class="sc-business-point">
                    <span class="sc-point-icon" aria-hidden="true">↗</span>
                    <span>შენი სახელოსნოს წარმოჩენა</span>
                </div>

                <div class="sc-business-point">
                    <span class="sc-point-icon" aria-hidden="true">⚙</span>
                    <span>მომსახურებებისა და ფასების მართვა</span>
                </div>

                <div class="sc-business-point">
                    <span class="sc-point-icon" aria-hidden="true">◷</span>
                    <span>სამუშაო საათები და ონლაინ ჯავშნები</span>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection
