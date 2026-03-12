@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Route;
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

    $currentLocale = app()->getLocale();
    $supportedLocales = collect(LaravelLocalization::getSupportedLocales())->only(['ar', 'en']);
    $contactPageUrl = Route::has('website.contact.index') ? route('website.contact.index') : 'javascript:void(0);';
@endphp

<style>
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
            padding: 0 56px;
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
        }

        .header .header-navbar-rht > li {
            padding: 0;
        }

        .header .header-navbar-rht > li:not(.header-lang-switcher) {
            display: none !important;
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

        .header .header-navbar-rht .header-lang-switcher .header-reg span {
            margin-right: 4px;
        }

        html[dir="rtl"] .header .navbar-header #mobile_btn {
            left: auto;
            right: 12px;
        }

        html[dir="rtl"] .header .header-navbar-rht {
            right: auto;
            left: 12px;
        }

        html[dir="rtl"] .header .header-navbar-rht .header-lang-switcher .header-reg span {
            margin-right: 0;
            margin-left: 4px;
        }
    }
</style>

<!-- Header -->
<header class="header">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg header-nav">
            <div class="navbar-header">
                <a id="mobile_btn" href="javascript:void(0);">
                    <span class="bar-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </a>

                <a href="{{ route('home') }}" class="navbar-brand logo">
                    <img src="{{ $headerLogo ?? asset('website/assets/img/logo.svg') }}" alt="{{ $headerSiteName ?? config('app.name', 'Afandina Car Rental') }}" class="img-fluid">
                </a>

                <a href="{{ route('home') }}" class="navbar-brand logo-small">
                    <img src="{{ $headerLogo ?? asset('website/assets/img/logo.svg') }}" alt="{{ $headerSiteName ?? config('app.name', 'Afandina Car Rental') }}" class="img-fluid">
                </a>
            </div>

            <div class="main-menu-wrapper">
                <div class="menu-header">
                    <a href="{{ route('home') }}" class="menu-logo">
                        <img src="{{ $headerLogo ?? asset('website/assets/img/logo.svg') }}" alt="{{ $headerSiteName ?? config('app.name', 'Afandina Car Rental') }}" class="img-fluid">
                    </a>
                    <a id="menu_close" class="menu-close" href="javascript:void(0);">
                        <i class="fas fa-times"></i>
                    </a>
                </div>

                <ul class="main-nav">
                    <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                        <a href="{{ route('home') }}">{{ __('website.nav.home') }}</a>
                    </li>
                    <li class="{{ request()->routeIs('website.cars.*') ? 'active' : '' }}">
                        <a href="{{ route('website.cars.index') }}">{{ __('website.nav.all_cars') }}</a>
                    </li>

                    <li class="has-submenu has-mega-dropdown {{ request()->routeIs('website.cars.brand') ? 'active' : '' }}">
                        <a href="javascript:void(0);">
                            {{ __('website.nav.brands') }}
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="submenu mega-submenu header-mega-dropdown">
                            <li class="mega-menu-title d-lg-none">{{ __('website.nav.brands') }}</li>
                            <li class="mega-menu-body">
                                <ul class="header-mega-grid" role="list">
                                    @forelse ($headerBrands ?? [] as $brandItem)
                                        <li class="header-mega-grid-item">
                                            <a href="{{ $brandItem['url'] }}" class="header-mega-item">
                                                <span class="header-mega-item-media">
                                                    @if (filled($brandItem['logo_path'] ?? null))
                                                        <img src="{{ $brandItem['logo_path'] }}" alt="{{ $brandItem['name'] }}">
                                                    @else
                                                        <span class="header-mega-item-fallback">{{ Str::substr($brandItem['name'], 0, 1) }}</span>
                                                    @endif
                                                </span>
                                                <span class="header-mega-item-content">
                                                    <strong>{{ $brandItem['name'] }}</strong>
                                                    <small>{{ __('website.nav.cars_count', ['count' => $brandItem['cars_count']]) }}</small>
                                                </span>
                                                <span class="header-mega-item-cta">
                                                    {{ __('website.nav.browse_cars', ['name' => $brandItem['name']]) }}
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
                        <a href="javascript:void(0);">
                            {{ __('website.nav.categories') }}
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="submenu mega-submenu header-mega-dropdown">
                            <li class="mega-menu-title d-lg-none">{{ __('website.nav.categories') }}</li>
                            <li class="mega-menu-body">
                                <ul class="header-mega-grid" role="list">
                                    @forelse ($headerCategories ?? [] as $categoryItem)
                                        <li class="header-mega-grid-item">
                                            <a href="{{ $categoryItem['url'] }}" class="header-mega-item">
                                                <span class="header-mega-item-media">
                                                    @if (filled($categoryItem['image_path'] ?? null))
                                                        <img src="{{ $categoryItem['image_path'] }}" alt="{{ $categoryItem['name'] }}">
                                                    @else
                                                        <span class="header-mega-item-fallback">{{ Str::substr($categoryItem['name'], 0, 1) }}</span>
                                                    @endif
                                                </span>
                                                <span class="header-mega-item-content">
                                                    <strong>{{ $categoryItem['name'] }}</strong>
                                                    <small>{{ __('website.nav.cars_count', ['count' => $categoryItem['cars_count']]) }}</small>
                                                </span>
                                                <span class="header-mega-item-cta">
                                                    {{ __('website.nav.browse_cars', ['name' => $categoryItem['name']]) }}
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
