<header class="partner-header">
    <a class="brand" href="{{ route('home') }}">
        MOTO<span>HUB</span>
        <small>TRANSPORT PARTNER</small>
    </a>

    <nav class="partner-nav">
        <a class="nav" href="{{ route('transport.index') }}">
    საჯარო მოთხოვნები
</a>
        <a
            class="nav {{ request()->routeIs('transport.partner.index') ? 'active' : '' }}"
            href="{{ route('transport.partner.index') }}"
        >
            ახალი მოთხოვნები
        </a>

        <a
            class="nav {{ request()->routeIs('transport.partner.offers') ? 'active' : '' }}"
            href="{{ route('transport.partner.offers') }}"
        >
            ჩემი შეთავაზებები
        </a>

        <a
            class="nav {{ request()->routeIs('transport.partner.orders') || request()->routeIs('transport.partner.orders.show') ? 'active' : '' }}"
            href="{{ route('transport.partner.orders') }}"
        >
            შეკვეთების ისტორია
        </a>
    </nav>

    <div class="partner-header-label">
        <span class="partner-status-dot"></span>
        პარტნიორის პანელი
    </div>
</header>