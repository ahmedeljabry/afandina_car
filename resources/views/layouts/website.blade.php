<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @php
        use App\Support\HomePageSchema;
        use App\Support\ListingPageSchema;

        $isHomePage = request()->routeIs('home');
        $isCarWebsiteRoute = request()->routeIs('website.cars.*');
        $isListingPage = request()->routeIs('website.cars.index')
            || request()->routeIs('website.cars.search')
            || request()->routeIs('website.cars.brand')
            || request()->routeIs('website.cars.category');
        $styleVersion = '0.6';
        $currentLocale = app()->getLocale();
        $isRtlLocale = $currentLocale === 'ar';
        $mobileSearchValue = $isCarWebsiteRoute ? trim((string) request('search', '')) : '';
        $schemaLanguage = match ($currentLocale) {
            'ar' => 'ar-AE',
            'ru' => 'ru-RU',
            default => 'en-AE',
        };
        $deferredHomeIconStyles = $isRtlLocale
            ? [
                asset('website/rtl/assets/plugins/fontawesome/css/all.min.css'),
                asset('website/rtl/assets/plugins/boxicons/css/boxicons.min.css'),
            ]
            : [
                asset('website/assets/plugins/fontawesome/css/all.min.css'),
                asset('website/assets/plugins/boxicons/css/boxicons.min.css'),
            ];
        $defaultMetaDescription = match (true) {
            request()->routeIs('website.cars.*') => __('website.seo.cars_description'),
            request()->routeIs('website.blogs.index') => __('website.seo.blog_description'),
            request()->routeIs('website.contact.*') => __('website.contact.description'),
            default => __('website.seo.default_description'),
        };
        $rawMetaDescription = trim((string) $__env->yieldContent('meta_description'));

        if ($rawMetaDescription === '') {
            $rawMetaDescription = (string) (
                data_get($listingContext ?? [], 'meta_description')
                ?: data_get($listingContext ?? [], 'content_description')
                ?: data_get($carDetails ?? [], 'meta_description')
                ?: data_get($carDetails ?? [], 'description')
                ?: data_get($carDetails ?? [], 'long_description')
                ?: data_get($blogDetails ?? [], 'meta_description')
                ?: data_get($blogDetails ?? [], 'description')
                ?: ($homeTranslation?->meta_description ?? null)
                ?: ($aboutTranslation?->meta_description ?? null)
                ?: data_get($aboutData ?? [], 'main_paragraph')
                ?: ($allCarsPageTranslation?->description ?? null)
                ?: ($homeTranslation?->contact_us_detail_paragraph ?? null)
                ?: $defaultMetaDescription
            );
        }

        $metaDescription = trim((string) preg_replace('/\s+/u', ' ', strip_tags($rawMetaDescription)));
        $metaDescription = $metaDescription !== ''
            ? \Illuminate\Support\Str::limit($metaDescription, 160, '')
            : $defaultMetaDescription;

        $normalizeAssetUrl = static function (?string $path): ?string {
            $value = trim((string) $path);

            if ($value === '') {
                return null;
            }

            if (\Illuminate\Support\Str::startsWith($value, ['http://', 'https://'])) {
                return $value;
            }

            $normalizedPath = ltrim($value, '/');

            if (\Illuminate\Support\Str::startsWith($normalizedPath, ['storage/', 'website/', 'admin/'])) {
                return asset($normalizedPath);
            }

            return asset('storage/' . $normalizedPath);
        };

        $homeSchema = null;
        $listingSchemas = null;

        if ($isHomePage) {
            $heroImageUrl = $normalizeAssetUrl($home?->hero_header_image_path);
            $featuredCarImageUrl = collect($featuredCars ?? [])
                ->map(fn ($car) => $normalizeAssetUrl(data_get($car, 'image_path')))
                ->filter()
                ->first();
            $popularCarImageUrl = collect($popularCars ?? [])
                ->map(fn ($car) => $normalizeAssetUrl(data_get($car, 'image_path')))
                ->filter()
                ->first();
            $primaryImageUrl = $heroImageUrl ?: $featuredCarImageUrl ?: $popularCarImageUrl ?: ($websiteLogo ?? $websiteFavicon ?? null);
            $schemaSiteName = trim((string) ($websiteSiteName ?? $contact?->name ?? config('app.name', 'Afandina Car Rental')));
            $schemaPageUrl = route('home');
            $schemaPriceRange = filled($minPrice ?? null) && filled($maxPrice ?? null)
                ? trim((string) ($currencySymbol ?? 'AED')) . ' ' . $minPrice . ' - ' . trim((string) ($currencySymbol ?? 'AED')) . ' ' . $maxPrice
                : null;
            $schemaHeadline = trim((string) ($homeTranslation?->meta_title ?: $schemaSiteName));
            $schemaImageCaption = $schemaHeadline !== '' ? $schemaHeadline : $schemaSiteName;

            $homeSchema = HomePageSchema::build([
                'page_url' => $schemaPageUrl,
                'site_name' => $schemaSiteName,
                'site_description' => $metaDescription,
                'logo_url' => $websiteLogo ?? $websiteFavicon ?? null,
                'primary_image_url' => $primaryImageUrl,
                'primary_image_caption' => $schemaImageCaption,
                'email' => $contact?->email,
                'telephone' => $contact?->phone ?: $contact?->alternative_phone,
                'has_map' => $contact?->google_map_url,
                'same_as' => array_values(array_filter([
                    $contact?->facebook,
                    $contact?->instagram,
                    $contact?->twitter,
                    $contact?->linkedin,
                    $contact?->youtube,
                    $contact?->tiktok,
                    $contact?->snapchat,
                    $contact?->google_map_url,
                ])),
                'address' => [
                    'address_line1' => $contact?->address_line1,
                    'address_line2' => $contact?->address_line2,
                    'city' => $contact?->city,
                    'state' => $contact?->state,
                    'postal_code' => $contact?->postal_code,
                    'country' => $contact?->country,
                ],
                'in_language' => $schemaLanguage,
                'headline' => $schemaHeadline,
                'date_published' => $home?->created_at,
                'date_modified' => $home?->updated_at,
                'price_range' => $schemaPriceRange,
                'faq_items' => collect($faqs ?? [])->map(fn ($faq) => [
                    'question' => data_get($faq, 'question'),
                    'answer' => data_get($faq, 'answer'),
                ])->all(),
            ]);
        }

        if ($isListingPage) {
            $listingBaseUrl = (string) (data_get($listingContext ?? [], 'action_url') ?: url()->current());
            $listingPageUrl = request()->fullUrl();
            $listingCars = collect($cars?->items() ?? []);
            $listingPageName = trim((string) (
                data_get($listingContext ?? [], 'content_title')
                ?: data_get($listingContext ?? [], 'meta_title')
                ?: data_get($listingContext ?? [], 'page_title')
                ?: __('website.cars.page_title')
            ));
            $listingPrimaryImage = $normalizeAssetUrl(data_get($listingContext ?? [], 'schema_image_path'))
                ?: $listingCars->map(fn ($car) => $normalizeAssetUrl(data_get($car, 'image_path')))->filter()->first()
                ?: ($websiteLogo ?? $websiteFavicon ?? null);
            $listingDailyPrices = $listingCars
                ->map(fn ($car) => data_get($car, 'daily_price'))
                ->filter(fn ($price) => $price !== null && $price !== '');
            $listingPriceRange = $listingDailyPrices->isNotEmpty()
                ? trim((string) ($currencySymbol ?? 'AED')) . ' ' . $listingDailyPrices->min() . ' - ' . trim((string) ($currencySymbol ?? 'AED')) . ' ' . $listingDailyPrices->max()
                : '$$$';

            $listingSchemas = ListingPageSchema::build([
                'site_url' => route('home'),
                'page_url' => $listingPageUrl,
                'site_name' => trim((string) ($websiteSiteName ?? config('app.name', 'Afandina Car Rental'))),
                'page_name' => $listingPageName,
                'description' => $metaDescription,
                'logo_url' => $websiteLogo ?? $websiteFavicon ?? null,
                'primary_image_url' => $listingPrimaryImage,
                'telephone' => $contact?->phone ?: $contact?->alternative_phone,
                'has_map' => $contact?->google_map_url,
                'same_as' => array_values(array_filter([
                    $contact?->facebook,
                    $contact?->twitter,
                    $contact?->instagram,
                    $contact?->linkedin,
                    $contact?->youtube,
                    $contact?->tiktok,
                    $contact?->snapchat,
                ])),
                'address' => [
                    'address_line1' => $contact?->address_line1,
                    'address_line2' => $contact?->address_line2,
                    'city' => $contact?->city,
                    'state' => $contact?->state,
                    'country' => $contact?->country,
                    'postal_code' => $contact?->postal_code,
                ],
                'in_language' => $schemaLanguage,
                'date_published' => data_get($listingContext ?? [], 'schema_date_published'),
                'date_modified' => data_get($listingContext ?? [], 'schema_date_modified'),
                'price_range' => $listingPriceRange,
                'search_url_template' => $listingBaseUrl . (str_contains($listingBaseUrl, '?') ? '&' : '?') . 'search={search_term_string}',
                'breadcrumb_items' => array_values(array_filter([
                    ['url' => route('home'), 'name' => __('website.nav.home')],
                    !request()->routeIs('website.cars.index') ? [
                        'url' => data_get($listingContext ?? [], 'breadcrumb_parent_url'),
                        'name' => data_get($listingContext ?? [], 'breadcrumb_parent_label'),
                    ] : null,
                    [
                        'url' => $listingPageUrl,
                        'name' => data_get($listingContext ?? [], 'breadcrumb_current_label')
                            ?: data_get($listingContext ?? [], 'page_title')
                            ?: $listingPageName,
                    ],
                ], fn ($item) => filled(data_get($item, 'url')) && filled(data_get($item, 'name')))),
                'faq_items' => data_get($listingContext ?? [], 'schema_faq_items', []),
                'products' => $listingCars->map(function ($car) use ($normalizeAssetUrl) {
                    return [
                        'id' => data_get($car, 'id'),
                        'name' => data_get($car, 'name'),
                        'image' => $normalizeAssetUrl(data_get($car, 'image_path')),
                        'description' => data_get($car, 'description'),
                        'brand_name' => data_get($car, 'brand_name'),
                        'category_name' => data_get($car, 'category_name'),
                        'passenger_capacity' => data_get($car, 'passenger_capacity'),
                        'year' => data_get($car, 'year'),
                        'daily_price' => data_get($car, 'daily_price'),
                        'weekly_price' => data_get($car, 'weekly_price'),
                        'monthly_price' => data_get($car, 'monthly_price'),
                        'currency_code' => data_get($car, 'currency_code', 'AED'),
                        'status' => data_get($car, 'status'),
                        'url' => data_get($car, 'details_url'),
                    ];
                })->all(),
            ]);
        }
    @endphp
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@hasSection('title')@yield('title') | @endif{{ $websiteSiteName ?? config('app.name', 'Afandina Car Rental') }}</title>
    <meta name="description" content="{{ $metaDescription }}">

	<!-- Favicon -->
	<link rel="shortcut icon" href="{{ $websiteFavicon ?? asset('website/assets/img/favicon.png') }}">
    @stack('head')
    @if ($homeSchema)
        <script type="application/ld+json">{!! json_encode($homeSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
    @endif
    @if ($listingSchemas)
        <script type="application/ld+json">{!! json_encode($listingSchemas['local_business'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
        <script type="application/ld+json">{!! json_encode($listingSchemas['page_graph'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
        @if (!empty($listingSchemas['breadcrumb']))
            <script type="application/ld+json">{!! json_encode($listingSchemas['breadcrumb'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
        @endif
        @if (!empty($listingSchemas['faq']))
            <script type="application/ld+json">{!! json_encode($listingSchemas['faq'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
        @endif
        @foreach (($listingSchemas['products'] ?? []) as $productSchema)
            <script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
        @endforeach
    @endif

    @if (!$isRtlLocale)
    	<!-- Bootstrap CSS -->
	    <link rel="stylesheet" href="{{ asset('website/assets/css/bootstrap.min.css') }}">
        @unless ($isHomePage)
            <!-- Fontawesome CSS -->
            <link rel="stylesheet" href="{{ asset('website/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
            <link rel="stylesheet" href="{{ asset('website/assets/plugins/fontawesome/css/all.min.css') }}">

            <!-- Fearther CSS -->
            <link rel="stylesheet" href="{{ asset('website/assets/css/feather.css') }}">
            <!-- Boxicons CSS -->
            <link rel="stylesheet" href="{{ asset('website/assets/plugins/boxicons/css/boxicons.min.css') }}">
        @endunless

        @unless ($isHomePage)
            <!-- Aos CSS -->
            <link rel="stylesheet" href="{{ asset('website/assets/plugins/aos/aos.css') }}">
        @endunless

        <!-- Owl carousel CSS -->
        <link rel="stylesheet" href="{{ asset('website/assets/css/owl.carousel.min.css') }}">

        @unless ($isHomePage)
            <!-- Select2 CSS -->
            <link rel="stylesheet" href="{{ asset('website/assets/plugins/select2/css/select2.min.css') }}">

            <!-- Datepicker CSS -->
            <link rel="stylesheet" href="{{ asset('website/assets/css/bootstrap-datetimepicker.min.css') }}">

            <!-- Flatpickr CSS -->
            <link rel="stylesheet" href="{{ asset('website/assets/plugins/flatpickr/flatpickr.min.css') }}">

            <!-- Fancybox CSS -->
            <link rel="stylesheet" href="{{ asset('website/assets/plugins/fancybox/fancybox.css') }}">

            <!-- Slick CSS -->
            <link rel="stylesheet" href="{{ asset('website/assets/plugins/slick/slick.css') }}">
        @endunless

        <!-- Main CSS -->
        <link rel="stylesheet" href="{{ asset('website/assets/css/style.css?v=' . $styleVersion) }}">
    @else
        <link rel="stylesheet" href="{{ asset('website/rtl/assets/css/bootstrap.rtl.min.css') }}">
        @unless ($isHomePage)
            <!-- Fontawesome CSS -->
            <link rel="stylesheet" href="{{ asset('website/rtl/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
            <link rel="stylesheet" href="{{ asset('website/rtl/assets/plugins/fontawesome/css/all.min.css') }}">

            <!-- Fearther CSS -->
            <link rel="stylesheet" href="{{ asset('website/rtl/assets/css/feather.css') }}">
            <!-- Boxicons CSS -->
            <link rel="stylesheet" href="{{ asset('website/rtl/assets/plugins/boxicons/css/boxicons.min.css') }}">
        @endunless

        @unless ($isHomePage)
            <!-- Aos CSS -->
            <link rel="stylesheet" href="{{ asset('website/rtl/assets/plugins/aos/aos.css') }}">
        @endunless

        <!-- Owl carousel CSS -->
        <link rel="stylesheet" href="{{ asset('website/rtl/assets/css/owl.carousel.min.css') }}">

        @unless ($isHomePage)
            <!-- Select2 CSS -->
            <link rel="stylesheet" href="{{ asset('website/rtl/assets/plugins/select2/css/select2.min.css') }}">

            <!-- Datepicker CSS -->
            <link rel="stylesheet" href="{{ asset('website/rtl/assets/css/bootstrap-datetimepicker.min.css') }}">

            <!-- Flatpickr CSS -->
            <link rel="stylesheet" href="{{ asset('website/assets/plugins/flatpickr/flatpickr.min.css') }}">

            <!-- Fancybox CSS -->
            <link rel="stylesheet" href="{{ asset('website/rtl/assets/plugins/fancybox/fancybox.css') }}">

            <!-- Slick CSS -->
            <link rel="stylesheet" href="{{ asset('website/rtl/assets/plugins/slick/slick.css') }}">
        @endunless

        <!-- Main CSS -->
        <link rel="stylesheet" href="{{ asset('website/rtl/assets/css/style.css?v=' . $styleVersion) }}">
    @endif


    @stack('css')
    <style>
        @media (max-width: 991.98px) {
            body {
                padding-top: calc(65px + env(safe-area-inset-top));
                padding-bottom: 110px;
            }

            .global-mobile-search {
                position: fixed;
                left: 12px;
                right: 12px;
                bottom: calc(12px + env(safe-area-inset-bottom));
                z-index: 1030;
                display: grid;
                grid-template-columns: auto minmax(0, 1fr) auto;
                align-items: center;
                gap: 12px;
                padding: 10px 10px 10px 16px;
                border: 1px solid rgba(18, 115, 132, 0.14);
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.98);
                box-shadow: 0 18px 38px rgba(15, 23, 42, 0.18);
                backdrop-filter: blur(14px);
            }

            .main-wrapper {
                min-height: 100vh;
            }

            .global-mobile-search__icon {
                width: 20px;
                height: 20px;
                color: #8a94a6;
                flex-shrink: 0;
            }

            .global-mobile-search__input {
                width: 100%;
                min-width: 0;
                border: 0;
                background: transparent;
                color: #1f2937;
                font-size: 17px;
                font-weight: 500;
                padding: 0;
                box-shadow: none;
            }

            .global-mobile-search__input::placeholder {
                color: #6b7280;
                opacity: 1;
            }

            .global-mobile-search__input:focus {
                outline: none;
                box-shadow: none;
            }

            .global-mobile-search__submit {
                width: 46px;
                height: 46px;
                border: 0;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: #127384;
                color: #fff;
                box-shadow: 0 12px 24px rgba(18, 115, 132, 0.24);
                transition: background-color 0.2s ease, transform 0.2s ease;
            }

            .global-mobile-search__submit:hover,
            .global-mobile-search__submit:focus {
                background: #0f5f6d;
                color: #fff;
            }

            .global-mobile-search__submit:focus-visible,
            .global-mobile-search:focus-within {
                outline: 3px solid rgba(18, 115, 132, 0.18);
                outline-offset: 2px;
            }

            .global-mobile-search__submit svg {
                width: 18px;
                height: 18px;
            }

            html[dir="rtl"] .global-mobile-search__submit svg {
                transform: rotate(180deg);
            }
        }

        @media (min-width: 992px) {
            .global-mobile-search {
                display: none;
            }
        }
    </style>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    (function() {
        var analyticsLoaded = false;
        var isHomePage = {{ $isHomePage ? 'true' : 'false' }};
        var homeDeferredStyles = {!! json_encode($deferredHomeIconStyles, JSON_UNESCAPED_SLASHES) !!};

        function loadAnalytics() {
            if (analyticsLoaded) {
                return;
            }

            analyticsLoaded = true;

            var script = document.createElement('script');
            script.async = true;
            script.src = 'https://www.googletagmanager.com/gtag/js?id=G-B175L5LXNC';
            document.head.appendChild(script);

            gtag('js', new Date());
            gtag('config', 'G-B175L5LXNC');
        }

        function loadDeferredStyles() {
            if (!isHomePage || !homeDeferredStyles.length) {
                return;
            }

            homeDeferredStyles.forEach(function(href) {
                if (document.querySelector('link[data-home-style=\"' + href + '\"]')) {
                    return;
                }

                var stylesheet = document.createElement('link');
                stylesheet.rel = 'stylesheet';
                stylesheet.href = href;
                stylesheet.setAttribute('data-home-style', href);
                document.head.appendChild(stylesheet);
            });
        }

        window.addEventListener('load', function() {
            if (isHomePage) {
                var interactionEvents = ['pointerdown', 'keydown', 'touchstart', 'scroll'];
                var triggerDeferredLoad = function() {
                    loadDeferredStyles();
                    loadAnalytics();

                    interactionEvents.forEach(function(eventName) {
                        window.removeEventListener(eventName, triggerDeferredLoad, listenerOptions);
                    });
                };
                var listenerOptions = { once: true, passive: true };

                interactionEvents.forEach(function(eventName) {
                    window.addEventListener(eventName, triggerDeferredLoad, listenerOptions);
                });

                window.setTimeout(loadDeferredStyles, 2500);
                window.setTimeout(loadAnalytics, 10000);
                return;
            }

            if ('requestIdleCallback' in window) {
                window.requestIdleCallback(loadAnalytics, { timeout: 4000 });
                return;
            }

            window.setTimeout(loadAnalytics, 1500);
        }, { once: true });
    })();
    </script>
</head>
<body>
    <div class="main-wrapper home-three">
        @include('includes.website.header')
        @yield('content')
        <form action="{{ route('website.cars.search') }}" method="GET" class="global-mobile-search" role="search" aria-label="{{ __('website.search.open') }}">
            <label class="visually-hidden" for="global-mobile-search-input">{{ __('website.search.input_label') }}</label>
            <span class="global-mobile-search__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input
                id="global-mobile-search-input"
                class="global-mobile-search__input"
                type="search"
                name="search"
                value="{{ $mobileSearchValue }}"
                placeholder="{{ __('website.cars.filters.search_placeholder') }}"
                autocomplete="off">
            <button type="submit" class="global-mobile-search__submit" aria-label="{{ __('website.search.submit') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        </form>
        @include('includes.website.footer')
    </div>
    @include('includes.website.scripts')
</body>
</html>
