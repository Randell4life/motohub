<aside class="mh-sidebar">
    <a href="{{ route('my-service-center.dashboard') }}" class="mh-logo">
        <span class="mh-logo-icon">M</span>
        <span>MOTO<span class="mh-logo-blue">HUB</span><small>SERVICE OPERATIONS</small></span>
    </a>
    <div class="mh-menu-label">სამუშაო სივრცე</div>
    <nav class="mh-menu">
        <a href="{{ route('my-service-center.dashboard') }}" class="mh-menu-link {{ request()->routeIs('my-service-center.dashboard') ? 'active' : '' }}"><span>▦</span> მიმოხილვა</a>
        <a href="{{ route('my-service-center.bookings') }}" class="mh-menu-link {{ request()->routeIs('my-service-center.bookings') ? 'active' : '' }}"><span>▤</span> ჯავშნები</a>
        <a href="{{ route('my-service-center.operations') }}" class="mh-menu-link {{ request()->routeIs('my-service-center.operations') ? 'active' : '' }}"><span>▣</span> მართვის ცენტრი</a>
        <a href="{{ route('my-service-center.work-orders.index') }}" class="mh-menu-link {{ request()->routeIs('my-service-center.work-orders.*') ? 'active' : '' }}"><span>⚙</span> სამუშაო შეკვეთები</a>
        <a href="{{ route('my-service-center.services') }}" class="mh-menu-link {{ request()->routeIs('my-service-center.services') ? 'active' : '' }}"><span>🔧</span> მომსახურებები</a>
        <a href="{{ route('my-service-center.hours') }}" class="mh-menu-link {{ request()->routeIs('my-service-center.hours') ? 'active' : '' }}"><span>◷</span> სამუშაო საათები</a>
        <a href="{{ route('my-service-center.passport-records.create') }}" class="mh-menu-link {{ request()->routeIs('my-service-center.passport-records.*') ? 'active' : '' }}"><span>✓</span> სერვისის პასპორტი</a>
        <a href="{{ route('my-service-center.profile') }}" class="mh-menu-link {{ request()->routeIs('my-service-center.profile') ? 'active' : '' }}"><span>▣</span> პროფილი</a>
    </nav>
    <div class="mh-sidebar-bottom">
        <div class="mh-status-dot"></div>
        <div><strong>სერვისცენტრის პანელი</strong><small>MotoHub • Management</small></div>
    </div>
</aside>
