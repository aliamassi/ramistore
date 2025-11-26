{{-- Top Navigation Bar --}}
<nav class="top-navbar">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between w-100">

            {{-- Left: Logo & Brand Name --}}
            <a href="{{ route('menu.index', $name) }}" class="brand-logo">
                <div class="brand-name">
                    <div class="brand-name-main">{{ $restaurant['business_name']->value ?? 'Restaurant' }}</div>
                </div>
                @if(!empty($user->logo))
                    <img src="{{ $user->logo }}" alt="Logo" class="brand-logo-img">
                @else
                    <div class="brand-logo-placeholder">
                        {{ strtoupper(substr($restaurant['business_name']->value ?? 'R', 0, 1)) }}
                    </div>
                @endif
            </a>

            {{-- Center: Navigation Menu (Desktop) --}}
            <nav class="nav-menu">
                <a href="{{ route('menu.index', $name) }}" class="nav-link {{ request()->routeIs('menu.index') ? 'active' : '' }}">
                    {{ __('Home') }}
                </a>
                <a href="{{ route('menu.about', $name) }}" class="nav-link {{ request()->routeIs('menu.about') ? 'active' : '' }}">
                    {{ __('About Us') }}
                </a>
                <a href="{{ route('menu.contact', $name) }}" class="nav-link {{ request()->routeIs('menu.contact') ? 'active' : '' }}">
                    {{ __('Contact Us') }}
                </a>
            </nav>

            {{-- Right: Control Buttons --}}
            <div class="nav-controls">
                <button class="nav-btn" id="menuToggle" title="Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <button class="nav-btn" id="darkModeToggle" title="Dark Mode">
                    <i class="fas fa-moon"></i>
                </button>
                <button class="nav-btn" id="langToggle" title="Language">
                    <i class="fas fa-globe"></i>
                </button>
            </div>

        </div>
    </div>
</nav>

{{-- Mobile Menu Overlay --}}
<div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

{{-- Mobile Menu Sidebar --}}
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-header">
        <div class="brand-name-main">{{ __('Menu') }}</div>
        <button class="mobile-menu-close" id="mobileMenuClose">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="mobile-nav-links">
        <a href="{{ route('menu.index', $name) }}" class="mobile-nav-link {{ request()->routeIs('menu.index') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            {{ __('Home') }}
        </a>
        <a href="{{ route('menu.about', $name) }}" class="mobile-nav-link {{ request()->routeIs('menu.about') ? 'active' : '' }}">
            <i class="fas fa-info-circle"></i>
            {{ __('About Us') }}
        </a>
        <a href="{{ route('menu.contact', $name) }}" class="mobile-nav-link {{ request()->routeIs('menu.contact') ? 'active' : '' }}">
            <i class="fas fa-envelope"></i>
            {{ __('Contact Us') }}
        </a>
    </div>
</div>
