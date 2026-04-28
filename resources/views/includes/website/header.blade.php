@php
    use Illuminate\Support\Str;
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

    $currentLocale = app()->getLocale();
    $supportedLocales = collect(LaravelLocalization::getSupportedLocales());
    $contactPageUrl = route('website.contact.index');
    $brandsSectionUrl = route('home') . '#home-brands';
    $categoriesSectionUrl = route('home') . '#home-categories';
    $headerSearchTerm = (string) request('search', '');
    $headerBrands = collect($headerBrands ?? []);
    $headerCategories = collect($headerCategories ?? []);
    $deferredMediaPlaceholder = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
    $normalizeHeaderCategoryImage = static function (?string $path): ?string {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');
        if (Str::startsWith($normalizedPath, ['storage/', 'admin/', 'website/'])) {
            return asset($normalizedPath);
        }

        return asset('storage/' . $normalizedPath);
    };
@endphp

<style>
    .header .navbar-header #mobile_btn,
    .header .main-menu-wrapper .menu-header .menu-close {
        background: transparent;
        border: 0;
        padding: 0;
        cursor: pointer;
    }

    .header .navbar-header #mobile_btn:focus-visible,
    .header .main-menu-wrapper .menu-header .menu-close:focus-visible {
        outline: 2px solid #121212;
        outline-offset: 3px;
    }

    .header .header-navbar-rht .header-lang-switcher .header-reg {
        color: #111111;
        border-color: rgba(17, 17, 17, 0.18);
    }

    .header .header-navbar-rht {
        align-items: center;
        gap: 10px;
    }

    .header .header-navbar-rht > li {
        padding: 0;
    }

    .header-search-toggle {
        width: 44px;
        height: 44px;
        border: 1px solid rgba(17, 17, 17, 0.16);
        border-radius: 12px;
        background: #fff;
        color: #111;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: border-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
    }

    .header-search-toggle:hover,
    .header-search-toggle:focus {
        border-color: #127384;
        color: #127384;
        box-shadow: 0 10px 24px rgba(18, 115, 132, 0.14);
    }

    .header-search-toggle:focus-visible {
        outline: 2px solid #121212;
        outline-offset: 3px;
    }

    .header-search-modal .modal-content {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 30px 80px rgba(15, 23, 42, 0.28);
    }

    .header-search-modal .modal-header {
        padding: 24px 28px 12px;
        border-bottom: 0;
    }

    .header-search-modal .modal-title {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        font-size: 28px;
        line-height: 1.2;
        font-weight: 700;
        color: #111;
    }

    .header-search-modal .modal-title i {
        color: #127384;
    }

    .header-search-modal .modal-body {
        padding: 10px 28px 28px;
    }

    .header-search-form {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 12px;
        align-items: center;
    }

    .header-search-form .form-control {
        height: 58px;
        border-radius: 14px;
        border: 1px solid #d7dee8;
        padding-inline: 18px;
        font-size: 16px;
    }

    .header-search-form .btn {
        height: 58px;
        min-width: 140px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-weight: 700;
        background: #127384;
        border-color: #127384;
    }

    .header-search-form .btn:hover,
    .header-search-form .btn:focus {
        background: #0f5f6d;
        border-color: #0f5f6d;
    }

    @media (max-width: 991.98px) {
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1045;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(14px);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
        }

        .header .header-nav {
            min-height: 65px;
        }

        .header .main-menu-wrapper {
            width: 86vw;
            width: min(86vw, 360px);
            max-width: 100vw;
            height: 100vh;
            height: 100dvh;
            max-height: 100vh;
            max-height: 100dvh;
            top: 0;
            bottom: auto;
            left: 0;
            right: auto;
            z-index: 1060;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transform: translateX(-100%);
            background: #201f1d;
        }

        html[dir="rtl"] .header .main-menu-wrapper {
            left: auto;
            right: 0;
            transform: translateX(100%);
        }

        html.menu-opened,
        html.menu-opened body {
            overflow: hidden;
        }

        html.menu-opened .header .main-menu-wrapper {
            transform: translateX(0);
        }

        html.menu-opened .global-mobile-search {
            display: none;
        }

        .header .main-menu-wrapper .menu-header {
            flex: 0 0 65px;
            min-height: 65px;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .header .main-menu-wrapper .menu-header .menu-logo {
            min-width: 0;
        }

        .header .main-menu-wrapper .menu-header .menu-logo img {
            width: auto;
            max-width: 210px;
            max-height: 42px;
            object-fit: contain;
        }

        .header .main-menu-wrapper .main-nav {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            padding-bottom: calc(28px + env(safe-area-inset-bottom));
        }

        .sidebar-overlay.opened {
            left: 0;
        }
    }

    @media (max-width: 575.96px) {
        .header .header-nav {
            padding: 0 12px;
            min-height: 65px;
            justify-content: center;
            position: relative;
        }

        .header .navbar-header {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 0 112px 0 56px;
        }

        .header .navbar-header #mobile_btn {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            margin: 0;
            padding: 0;
        }

        .header .navbar-header .logo-small {
            display: flex;
            align-items: center;
            justify-content: center;
            width: auto;
            max-width: 100%;
            margin: 0 auto;
            text-align: center;
        }

        .header .navbar-header .logo-small img {
            width: auto;
            max-width: 180px;
            max-height: 34px;
            object-fit: contain;
        }

        .header .header-navbar-rht {
            display: flex !important;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            margin: 0;
            z-index: 9;
            gap: 6px;
        }

        .header .header-navbar-rht > li {
            padding: 0;
        }

        .header .header-navbar-rht > li:not(.header-lang-switcher):not(.header-search-entry) {
            display: none !important;
        }

        .header-search-toggle {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            font-size: 13px;
        }

        .header .header-navbar-rht .header-lang-switcher .header-reg {
            padding: 6px 10px;
            font-size: 11px;
            line-height: 1;
            min-width: 52px;
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .header .main-menu-wrapper {
            width: 100vw;
        }

        .header .main-menu-wrapper .menu-header {
            padding: 0 16px;
        }

        .header .main-menu-wrapper .menu-header .menu-logo img {
            max-width: min(220px, 70vw);
        }

        .header .header-navbar-rht .header-lang-switcher .header-reg span {
            margin-right: 4px;
        }

        html[dir="rtl"] .header .navbar-header #mobile_btn {
            left: auto;
            right: 12px;
        }

        html[dir="rtl"] .header .navbar-header {
            padding: 0 56px 0 112px;
        }

        html[dir="rtl"] .header .header-navbar-rht {
            right: auto;
            left: 12px;
        }

        html[dir="rtl"] .header .header-navbar-rht .header-lang-switcher .header-reg span {
            margin-right: 0;
            margin-left: 4px;
        }

        .header-search-modal .modal-title {
            font-size: 23px;
        }

        .header-search-form {
            grid-template-columns: 1fr;
        }

        .header-search-form .btn {
            width: 100%;
        }
    }
</style>

<!-- Header -->
<header class="header">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg header-nav">
            <div class="navbar-header">
                <button
                    id="mobile_btn"
                    type="button"
                    aria-controls="primary-navigation"
                    aria-expanded="false"
                    aria-label="{{ __('Open menu') }}">
                    <span class="bar-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <a href="{{ route('home') }}" class="navbar-brand logo">
                    <img src="{{ $headerLogo ?? asset('website/assets/img/logo.svg') }}" alt="{{ $headerSiteName ?? config('app.name', 'Afandina Car Rental') }}" class="img-fluid">
                </a>

                <a href="{{ route('home') }}" class="navbar-brand logo-small">
                    <img src="{{ $headerLogo ?? asset('website/assets/img/logo.svg') }}" alt="{{ $headerSiteName ?? config('app.name', 'Afandina Car Rental') }}" class="img-fluid">
                </a>
            </div>

            <div id="primary-navigation" class="main-menu-wrapper">
                <div class="menu-header">
                    <a href="{{ route('home') }}" class="menu-logo">
                        <img src="{{ $headerLogo ?? asset('website/assets/img/logo.svg') }}" alt="{{ $headerSiteName ?? config('app.name', 'Afandina Car Rental') }}" class="img-fluid">
                    </a>
                    <button
                        id="menu_close"
                        class="menu-close"
                        type="button"
                        aria-controls="primary-navigation"
                        aria-label="{{ __('Close menu') }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <ul class="main-nav">
                    <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                        <a href="{{ route('home') }}">{{ __('website.nav.home') }}</a>
                    </li>
                    <li class="{{ request()->routeIs('website.cars.*') ? 'active' : '' }}">
                        <a href="{{ route('website.cars.index') }}">{{ __('website.nav.all_cars') }}</a>
                    </li>

                    <li class="has-submenu has-mega-dropdown {{ request()->routeIs('website.cars.brand') ? 'active' : '' }}">
                        <a href="{{ $brandsSectionUrl }}">
                            {{ __('website.nav.brands') }}
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="submenu mega-submenu header-mega-dropdown">
                            <li class="mega-menu-title d-lg-none">{{ __('website.nav.brands') }}</li>
                            <li class="mega-menu-body">
                                <ul class="header-mega-grid" role="list">
                                    @forelse ($headerBrands as $brandItem)
                                        @php
                                            $brandName = (string) data_get($brandItem, 'name', __('website.common.brand'));
                                            $brandLogoPath = data_get($brandItem, 'logo_path');
                                            $brandCarsCount = (int) data_get($brandItem, 'cars_count', 0);
                                            $brandHref = data_get($brandItem, 'url', route('website.cars.index'));
                                        @endphp
                                        <li class="header-mega-grid-item">
                                            <a href="{{ $brandHref }}" class="header-mega-item">
                                                <span class="header-mega-item-media">
                                                    @if (filled($brandLogoPath))
                                                        <img
                                                            src="{{ $deferredMediaPlaceholder }}"
                                                            data-defer-src="{{ $brandLogoPath }}"
                                                            alt="{{ $brandName }}"
                                                            loading="lazy"
                                                            fetchpriority="low"
                                                            decoding="async">
                                                    @else
                                                        <span class="header-mega-item-fallback">{{ Str::substr($brandName, 0, 1) }}</span>
                                                    @endif
                                                </span>
                                                <span class="header-mega-item-content">
                                                    <strong>{{ $brandName }}</strong>
                                                    <small>{{ __('website.nav.cars_count', ['count' => $brandCarsCount]) }}</small>
                                                </span>
                                                <span class="header-mega-item-cta">
                                                    {{ __('website.nav.browse_cars', ['name' => $brandName]) }}
                                                </span>
                                            </a>
                                        </li>
                                    @empty
                                        <li class="header-mega-grid-item header-mega-grid-empty">
                                            <span class="header-mega-empty">{{ __('website.home.empty_brands') }}</span>
                                        </li>
                                    @endforelse
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="has-submenu has-mega-dropdown {{ request()->routeIs('website.cars.category') ? 'active' : '' }}">
                        <a href="{{ $categoriesSectionUrl }}">
                            {{ __('website.nav.categories') }}
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="submenu mega-submenu header-mega-dropdown">
                            <li class="mega-menu-title d-lg-none">{{ __('website.nav.categories') }}</li>
                            <li class="mega-menu-body">
                                <ul class="header-mega-grid" role="list">
                                    @forelse ($headerCategories as $categoryItem)
                                        @php
                                            $categoryName = (string) data_get($categoryItem, 'name', __('website.common.category'));
                                            $categoryImagePath = $normalizeHeaderCategoryImage(data_get($categoryItem, 'image_path'));
                                            $categoryCarsCount = (int) data_get($categoryItem, 'cars_count', 0);
                                            $categoryHref = route('website.cars.category', ['category' => data_get($categoryItem, 'slug')]);
                                        @endphp
                                        <li class="header-mega-grid-item">
                                            <a href="{{ $categoryHref }}" class="header-mega-item">
                                                <span class="header-mega-item-media">
                                                    @if (filled($categoryImagePath))
                                                        <img
                                                            src="{{ $deferredMediaPlaceholder }}"
                                                            data-defer-src="{{ $categoryImagePath }}"
                                                            alt="{{ $categoryName }}"
                                                            loading="lazy"
                                                            fetchpriority="low"
                                                            decoding="async">
                                                    @else
                                                        <span class="header-mega-item-fallback">{{ Str::substr($categoryName, 0, 1) }}</span>
                                                    @endif
                                                </span>
                                                <span class="header-mega-item-content">
                                                    <strong>{{ $categoryName }}</strong>
                                                    <small>{{ __('website.nav.cars_count', ['count' => $categoryCarsCount]) }}</small>
                                                </span>
                                                <span class="header-mega-item-cta">
                                                    {{ __('website.nav.browse_cars', ['name' => $categoryName]) }}
                                                </span>
                                            </a>
                                        </li>
                                    @empty
                                        <li class="header-mega-grid-item header-mega-grid-empty">
                                            <span class="header-mega-empty">{{ __('website.home.empty_categories') }}</span>
                                        </li>
                                    @endforelse
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="{{ request()->routeIs('website.about.*') ? 'active' : '' }}">
                        <a href="{{ route('website.about.index') }}">{{ __('website.nav.about_us') }}</a>
                    </li>
                    <li class="{{ request()->routeIs('website.blogs.*') ? 'active' : '' }}">
                        <a href="{{ route('website.blogs.index') }}">{{ __('website.nav.blogs') }}</a>
                    </li>
                    <li class="{{ request()->routeIs('website.contact.*') ? 'active' : '' }}">
                        <a href="{{ $contactPageUrl }}">{{ __('website.nav.contact_us') }}</a>
                    </li>

                </ul>
            </div>

            <ul class="nav header-navbar-rht">
                <li class="nav-item header-search-entry">
                    <button
                        class="header-search-toggle"
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#headerSearchModal"
                        aria-label="{{ __('website.search.open') }}">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </li>
                <li class="nav-item dropdown header-lang-switcher">
                    <a class="nav-link header-reg dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span><i class="fa-solid fa-language"></i></span>{{ strtoupper($currentLocale) }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @foreach($supportedLocales as $localeCode => $properties)
                            <li>
                                <a class="dropdown-item {{ $currentLocale === $localeCode ? 'active' : '' }}"
                                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                    {{ strtoupper($localeCode) }} - {{ data_get($properties, 'native', data_get($properties, 'name', strtoupper($localeCode))) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</header>
<!-- /Header -->

<div class="modal fade header-search-modal" id="headerSearchModal" tabindex="-1" aria-labelledby="headerSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="headerSearchModalLabel">
                    <i class="fa-solid fa-magnifying-glass"></i>{{ __('website.search.title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('website.common.close') }}"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('website.cars.search') }}" method="GET" class="header-search-form">
                    <input
                        type="search"
                        class="form-control"
                        name="search"
                        value="{{ $headerSearchTerm }}"
                        placeholder="{{ __('website.search.placeholder') }}"
                        aria-label="{{ __('website.search.input_label') }}"
                        autocomplete="off">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-arrow-right"></i>
                        <span>{{ __('website.search.submit') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
