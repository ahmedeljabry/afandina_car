@php
    use Illuminate\Support\Str;
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

    $currentLocale = app()->getLocale();
    $supportedLocales = collect(LaravelLocalization::getSupportedLocales())->only(['ar', 'en']);
    $contactPageUrl = route('website.contact.index');
    $brandsSectionUrl = route('home') . '#home-brands';
    $categoriesSectionUrl = route('home') . '#home-categories';
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
    $headerCategories = \App\Models\Category::query()
        ->with('translations')
        ->withCount([
            'cars as cars_count' => fn ($carsQuery) => $carsQuery->where('is_active', true),
        ])
        ->where('is_active', true)
        ->whereNotNull('slug')
        ->where('slug', '!=', '')
        ->orderByDesc('cars_count')
        ->latest('id')
        ->take(24)
        ->get()
        ->map(function (\App\Models\Category $category) use ($currentLocale): ?array {
            $translation = $category->translations->firstWhere('locale', $currentLocale)
                ?? $category->translations->firstWhere('locale', 'en')
                ?? $category->translations->first();
            $name = trim((string) ($translation?->name ?? ''));

            if ($name === '') {
                return null;
            }

            return [
                'name' => $name,
                'slug' => $category->slug,
                'image_path' => $category->image_path,
                'cars_count' => (int) ($category->cars_count ?? 0),
            ];
        })
        ->filter()
        ->values();
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
                        <a href="{{ $brandsSectionUrl }}">
                            {{ __('website.nav.brands') }}
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="submenu mega-submenu header-mega-dropdown">
                            <li class="mega-menu-title d-lg-none">{{ __('website.nav.brands') }}</li>
                            <li class="mega-menu-body">
                                <ul class="header-mega-grid" role="list">
                                    @forelse ($headerBrands ?? [] as $brandItem)
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
                                                        <img src="{{ $brandLogoPath }}" alt="{{ $brandName }}">
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
                                                        <img src="{{ $categoryImagePath }}" alt="{{ $categoryName }}">
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
