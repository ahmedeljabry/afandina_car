@php
    use Illuminate\Support\Str;

    $currentLocale = (string) app()->getLocale();
    $normalizedLocale = Str::of($currentLocale)->replace('_', '-')->lower()->value();
    $isRtlLocale = Str::startsWith($normalizedLocale, 'ar');

    $assetLink = static function (string $ltrPath, ?string $rtlPath = null) use ($isRtlLocale): string {
        $preferredPath = $isRtlLocale ? ($rtlPath ?? $ltrPath) : $ltrPath;

        if (file_exists(public_path($preferredPath))) {
            return asset($preferredPath);
        }

        if ($preferredPath !== $ltrPath && file_exists(public_path($ltrPath))) {
            return asset($ltrPath);
        }

        return asset($preferredPath);
    };
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $currentLocale) }}" dir="{{ $isRtlLocale ? 'rtl' : 'ltr' }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title>@yield('title')</title>

	<!-- Favicon -->
	<link rel="shortcut icon" href="{{ asset('website/assets/img/favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ $assetLink('website/assets/css/bootstrap.min.css', 'website/rtl/assets/css/bootstrap.rtl.min.css') }}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ $assetLink('website/assets/plugins/fontawesome/css/fontawesome.min.css', 'website/rtl/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ $assetLink('website/assets/plugins/fontawesome/css/all.min.css', 'website/rtl/assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ $assetLink('website/assets/plugins/select2/css/select2.min.css', 'website/rtl/assets/plugins/select2/css/select2.min.css') }}">

    <!-- Datepicker CSS -->
    <link rel="stylesheet" href="{{ $assetLink('website/assets/css/bootstrap-datetimepicker.min.css', 'website/rtl/assets/css/bootstrap-datetimepicker.min.css') }}">

    <!-- Aos CSS -->
    <link rel="stylesheet" href="{{ $assetLink('website/assets/plugins/aos/aos.css', 'website/rtl/assets/plugins/aos/aos.css') }}">

    <!-- Feather CSS -->
    <link rel="stylesheet" href="{{ $assetLink('website/assets/css/feather.css', 'website/rtl/assets/css/feather.css') }}">

    <!-- Owl carousel CSS -->
    <link rel="stylesheet" href="{{ $assetLink('website/assets/css/owl.carousel.min.css', 'website/rtl/assets/css/owl.carousel.min.css') }}">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="{{ $assetLink('website/assets/plugins/flatpickr/flatpickr.min.css') }}">

    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="{{ $assetLink('website/assets/plugins/fancybox/fancybox.css', 'website/rtl/assets/plugins/fancybox/fancybox.css') }}">

    <!-- Slick CSS -->
    <link rel="stylesheet" href="{{ $assetLink('website/assets/plugins/slick/slick.css', 'website/rtl/assets/plugins/slick/slick.css') }}">

    <!-- Boxicons CSS -->
    <link rel="stylesheet" href="{{ $assetLink('website/assets/plugins/boxicons/css/boxicons.min.css', 'website/rtl/assets/plugins/boxicons/css/boxicons.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ $assetLink('website/assets/css/style.css', 'website/rtl/assets/css/style.css') }}">

    <style>
        .header .main-menu-wrapper .main-nav > li .submenu.header-mega-dropdown > .mega-menu-body {
            list-style: none;
        }

        .header .header-mega-grid {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .header .header-mega-grid-item {
            list-style: none;
            margin: 0;
        }

        .header .header-mega-grid-item > .header-mega-item {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            border: 1px solid #e4e4e4;
            border-radius: 10px;
            padding: 10px 12px;
            background: #fff;
            white-space: normal;
        }

        .header .header-mega-item-media {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #f3f3f3;
            overflow: hidden;
        }

        .header .header-mega-item-media img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .header .header-mega-item-content {
            display: inline-flex;
            flex-direction: column;
            min-width: 0;
        }

        @media (max-width: 1199.96px) {
            .header .header-mega-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 991.96px) {
            .header .main-menu-wrapper .main-nav > li .submenu.header-mega-dropdown > .mega-menu-body {
                padding: 0;
            }

            .header .header-mega-grid {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .header .header-mega-grid-item > .header-mega-item {
                padding: 10px;
            }
        }
    </style>

    @stack('css')

</head>
<body>
    <div class="main-wrapper home-three">
        @include('includes.website.header')
        @yield('content')
        @include('includes.website.footer')
    </div>
    @include('includes.website.scripts')
</body>
</html>
