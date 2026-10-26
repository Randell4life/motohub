<style>
:root {
    color-scheme: dark;
    --bg: #070a12;
    --panel: #0d1422;
    --panel2: #121d30;
    --blue: #1677ff;
    --light-blue: #42a5ff;
    --text: #f4f8ff;
    --muted: #98a8c2;
    --border: #243653;
    --green: #34d399;
    --yellow: #fbbf24;
}

* { box-sizing: border-box; }

html {
    min-height: 100%;
    background: var(--bg);
}

body {
    margin: 0;
    color: var(--text);
    background:
        radial-gradient(ellipse at 80% 0%, rgba(22,119,255,.13), transparent 38%),
        linear-gradient(180deg, #070a12, #080e19 60%, #070a12);
    font-family: Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
}

a { color: inherit; text-decoration: none; }

button, input, select, textarea { font: inherit; }

button, .btn, .nav {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    min-height: 42px;
    padding: 11px 15px;
    border: 1px solid var(--border);
    border-radius: 7px;
    background: var(--panel2);
    color: var(--text);
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease,
                transform .2s ease, filter .2s ease;
}

button:hover, .btn:hover, .nav:hover {
    border-color: var(--light-blue);
    filter: brightness(1.12);
    transform: translateY(-1px);
}

.btn.primary, .btn-primary, button.primary {
    color: #fff;
    background: linear-gradient(135deg, var(--blue), #0799ff);
    border-color: var(--blue);
    box-shadow: 0 5px 18px rgba(22,119,255,.18);
}

.wrap {
    width: min(1440px, 100%);
    margin: 0 auto;
    padding: 28px 24px 60px;
}

header:not(.partner-header) {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 32px;
}

.brand {
    color: var(--text);
    font-size: 25px;
    font-weight: 900;
    letter-spacing: -1px;
}

.brand span { color: var(--light-blue); }

nav:not(.partner-nav) {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.partner-header {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 22px;
    flex-wrap: wrap;
    padding: 22px 0;
    margin-bottom: 30px;
    border-bottom: 1px solid var(--border);
}

.partner-header .brand {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 2px;
    font-size: 25px;
    font-style: italic;
    font-weight: 900;
    letter-spacing: -1px;
}

.partner-header .brand span { color: var(--light-blue); }

.partner-header .brand small {
    flex-basis: 100%;
    margin-top: 3px;
    color: var(--muted);
    font-size: 9px;
    font-style: normal;
    letter-spacing: 3px;
}

.partner-nav {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.partner-nav .nav {
    min-height: 42px;
    padding: 11px 14px;
    border: 1px solid transparent;
    border-radius: 7px;
    background: transparent;
    color: var(--muted);
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
}

.partner-nav .nav:hover,
.partner-nav .nav.active {
    color: #fff;
    background: linear-gradient(110deg, rgba(22,119,255,.24), rgba(18,29,48,.9));
    border-color: var(--blue);
}

.partner-nav .nav.active {
    box-shadow: inset 3px 0 var(--light-blue), 0 0 18px rgba(22,119,255,.09);
}

.partner-header-label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--muted);
    font-size: 12px;
    font-weight: 700;
}

.partner-status-dot {
    width: 8px;
    height: 8px;
    flex-shrink: 0;
    border-radius: 50%;
    background: var(--light-blue);
    box-shadow: 0 0 12px var(--blue);
}

.hero {
    position: relative;
    overflow: hidden;
    padding: clamp(24px, 4vw, 42px);
    margin-bottom: 24px;
    border: 1px solid #1b4c85;
    border-radius: 12px;
    background:
        radial-gradient(ellipse at 90% 10%, rgba(22,119,255,.24), transparent 45%),
        linear-gradient(115deg, #111e34, #0d1422 72%);
    box-shadow: inset 0 1px rgba(255,255,255,.025);
}

.hero::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    width: 5px;
    height: 100%;
    background: linear-gradient(var(--light-blue), var(--blue));
    box-shadow: 0 0 24px rgba(22,119,255,.6);
}

.eyebrow {
    color: var(--light-blue);
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 2px;
    text-transform: uppercase;
}

h1 {
    margin: 12px 0;
    font-size: clamp(29px, 5vw, 46px);
    line-height: 1.12;
    letter-spacing: -1px;
    overflow-wrap: anywhere;
}

h2 { font-size: 20px; line-height: 1.35; }

p { color: var(--muted); line-height: 1.7; }

.card, .panel, .empty, .filters {
    min-width: 0;
    padding: 22px;
    margin-bottom: 16px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: linear-gradient(145deg, rgba(17,30,52,.92), var(--panel) 70%);
}

.card, .stat-card {
    transition: border-color .2s ease, transform .2s ease;
}

.card:hover {
    border-color: #345b8e;
    transform: translateY(-2px);
}

input, select, textarea {
    width: 100%;
    min-width: 0;
    padding: 12px 13px;
    border: 1px solid var(--border);
    border-radius: 7px;
    background: #070d18;
    color: var(--text);
    outline: none;
}

input:focus, select:focus, textarea:focus {
    border-color: var(--light-blue);
    box-shadow: 0 0 0 3px rgba(22,119,255,.14);
}

input::placeholder, textarea::placeholder { color: #7184a2; }

.filters {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 24px;
}

.stat-card {
    position: relative;
    min-width: 0;
    overflow: hidden;
    padding: 20px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background:
        radial-gradient(ellipse at 100% 0%, rgba(22,119,255,.11), transparent 55%),
        linear-gradient(145deg, #111e34, var(--panel));
}

.stat-card::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 3px;
    height: 100%;
    background: var(--stat-color, var(--blue));
}

.stat-label, .label {
    color: var(--muted);
    font-size: 13px;
    font-weight: 700;
}

.stat-number {
    margin-top: 12px;
    font-size: clamp(26px, 3vw, 36px);
    font-weight: 900;
    letter-spacing: -1px;
    overflow-wrap: anywhere;
}

.stat-note {
    margin-top: 6px;
    color: var(--muted);
    font-size: 12px;
}

.route {
    margin: 16px 0;
    font-size: 18px;
    font-weight: 900;
    overflow-wrap: anywhere;
}

.tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    max-width: 100%;
    padding: 7px 10px;
    border: 1px solid var(--border);
    border-radius: 5px;
    background: var(--panel2);
    color: #c5d7f3;
    font-size: 12px;
    overflow-wrap: anywhere;
}

.meta, .details, .actions, .contact-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
}

.actions, .contact-actions {
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid var(--border);
}

.price {
    margin: 16px 0;
    color: var(--light-blue);
    font-size: 29px;
    font-weight: 900;
}

.muted, .label { color: var(--muted); }

.blue, .status-accepted, .status-in_transit { color: var(--light-blue); }

.success, .status-delivered { color: var(--green); }

.warning, .status-pending { color: var(--yellow); }

.status-rejected { color: #ff9292; }

.alert, .error {
    padding: 14px 16px;
    margin-bottom: 16px;
    border: 1px solid;
    border-radius: 8px;
}

.alert {
    border-color: #216943;
    background: #102b20;
    color: #a7f3d0;
}

.error {
    border-color: #813547;
    background: #351a22;
    color: #fecdd3;
}

.field { min-width: 0; margin-bottom: 17px; }

.value {
    font-size: 15px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.description { white-space: pre-line; overflow-wrap: anywhere; }

.place {
    min-width: 0;
    padding: 18px;
    border: 1px solid var(--border);
    border-radius: 9px;
    background: #070d18;
}

.place-label {
    margin-bottom: 10px;
    color: var(--light-blue);
    font-size: 12px;
    font-weight: 900;
}

.pagination { margin-top: 22px; overflow-x: auto; }

.pagination nav { justify-content: center; }

@media (max-width: 1000px) {
    .partner-header { align-items: flex-start; }
    .partner-nav { flex: 1 1 100%; }
    .partner-header-label { margin-left: auto; }
    .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

@media (max-width: 600px) {
    .wrap { padding: 20px 14px 40px; }

    header:not(.partner-header) {
        align-items: flex-start;
        margin-bottom: 24px;
    }

    .partner-header {
        gap: 15px;
        padding: 18px 0;
        margin-bottom: 22px;
    }

    .partner-nav {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        width: 100%;
    }

    .partner-nav .nav {
        justify-content: flex-start;
        width: 100%;
        padding: 12px;
    }

    .partner-header-label { margin-left: 0; }

    .hero, .card, .panel, .empty, .filters { padding: 17px; }

    .grid, .stats { grid-template-columns: minmax(0, 1fr); }

    .filters { display: grid; grid-template-columns: minmax(0, 1fr); }

    .filters select, .filters input { width: 100%; }

    .actions .btn, .actions button, .contact-actions .btn {
        flex: 1 1 100%;
        width: 100%;
        text-align: center;
    }

    .route { font-size: 16px; }
}
/* MotoHub premium transport dashboard */

.partner-dashboard .hero {
    min-height: 250px;
    display: flex;
    align-items: center;
    background:
        linear-gradient(90deg, rgba(7,10,18,.98) 0%, rgba(7,10,18,.76) 55%, rgba(7,10,18,.25) 100%),
        radial-gradient(ellipse at 85% 50%, rgba(22,119,255,.35), transparent 55%),
        linear-gradient(135deg, #142a48, #0d1422 65%);
    border-color: #244b7c;
}

.partner-dashboard .hero-content {
    max-width: 760px;
}

.partner-dashboard .hero h1 {
    max-width: 700px;
    font-size: clamp(34px, 5vw, 58px);
    font-weight: 900;
    letter-spacing: -1.8px;
    text-transform: uppercase;
}

.partner-dashboard .hero p {
    max-width: 650px;
}

.partner-dashboard .section-heading {
    display: flex;
    justify-content: space-between;
    align-items: end;
    flex-wrap: wrap;
    gap: 12px;
    margin: 30px 0 18px;
}

.partner-dashboard .section-heading h2 {
    margin: 0;
    font-size: 23px;
    font-weight: 900;
}

.partner-dashboard .section-heading p {
    margin: 5px 0 0;
    font-size: 13px;
}

.partner-dashboard .filters {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto;
    padding: 14px;
    background: #0b1220;
}

.partner-dashboard .filters button {
    min-width: 120px;
}

.partner-dashboard .card {
    position: relative;
    padding: 24px;
    border-radius: 9px;
    background:
        linear-gradient(145deg, rgba(18,34,57,.94), rgba(10,16,28,.98));
}

.partner-dashboard .card h2 {
    margin: 20px 0 10px;
    font-size: 23px;
    font-weight: 900;
}

.partner-dashboard .route {
    padding: 15px;
    border: 1px solid #203a5d;
    border-radius: 7px;
    background: rgba(7,13,24,.75);
    font-size: 17px;
}

.partner-dashboard .offer-form {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
}

.partner-dashboard .offer-form label {
    display: block;
    min-width: 0;
    color: var(--muted);
    font-size: 12px;
    font-weight: 800;
}

.partner-dashboard .offer-form input {
    margin-top: 8px;
}

.partner-dashboard .offer-form .full {
    grid-column: 1 / -1;
}

.partner-dashboard .offer-form button {
    min-height: 48px;
    letter-spacing: .2px;
}

.partner-dashboard .stat-card {
    min-height: 145px;
    border-radius: 9px;
}

.partner-dashboard .tag {
    border-radius: 5px;
}

.partner-dashboard .empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px 22px;
}

@media (max-width: 700px) {
    .partner-dashboard .hero {
        min-height: 220px;
        padding: 22px;
    }

    .partner-dashboard .hero h1 {
        font-size: 34px;
        letter-spacing: -1px;
    }

    .partner-dashboard .filters {
        grid-template-columns: minmax(0, 1fr);
    }

    .partner-dashboard .filters button {
        width: 100%;
    }

    .partner-dashboard .offer-form {
        grid-template-columns: minmax(0, 1fr);
    }

    .partner-dashboard .offer-form .full {
        grid-column: auto;
    }
}
</style>
