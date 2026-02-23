<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title>@yield('title')</title>

	<!-- Favicon -->
	<link rel="shortcut icon" href="{{ asset('website/assets/img/favicon.png') }}">

    @if (app()->getLocale() == 'en')
    	<!-- Bootstrap CSS -->
	    <link rel="stylesheet" href="{{ asset('website/assets/css/bootstrap.min.css') }}">
        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="{{ asset('website/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('website/assets/plugins/fontawesome/css/all.min.css') }}">

        <!-- Select2 CSS -->
        <link rel="stylesheet" href="{{ asset('website/assets/plugins/select2/css/select2.min.css') }}">

        <!-- Datepicker CSS -->
        <link rel="stylesheet" href="{{ asset('website/assets/css/bootstrap-datetimepicker.min.css') }}">

        <!-- Aos CSS -->
        <link rel="stylesheet" href="{{ asset('website/assets/plugins/aos/aos.css') }}">

        <!-- Fearther CSS -->
        <link rel="stylesheet" href="{{ asset('website/assets/css/feather.css') }}">

        <!-- Owl carousel CSS -->
        <link rel="stylesheet" href="{{ asset('website/assets/css/owl.carousel.min.css') }}">

        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="{{ asset('website/assets/plugins/flatpickr/flatpickr.min.css') }}">

        <!-- Fancybox CSS -->
        <link rel="stylesheet" href="{{ asset('website/assets/plugins/fancybox/fancybox.css') }}">

        <!-- Slick CSS -->
        <link rel="stylesheet" href="{{ asset('website/assets/plugins/slick/slick.css') }}">

        <!-- Boxicons CSS -->
        <link rel="stylesheet" href="{{ asset('website/assets/plugins/boxicons/css/boxicons.min.css') }}">

        <!-- Main CSS -->
        <link rel="stylesheet" href="{{ asset('website/assets/css/style.css?v=0.4') }}">
    @else
        <link rel="stylesheet" href="{{ asset('website/rtl/assets/css/bootstrap.rtl.min.css') }}">
        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="{{ asset('website/rtl/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('website/rtl/assets/plugins/fontawesome/css/all.min.css') }}">

        <!-- Select2 CSS -->
        <link rel="stylesheet" href="{{ asset('website/rtl/assets/plugins/select2/css/select2.min.css') }}">

        <!-- Datepicker CSS -->
        <link rel="stylesheet" href="{{ asset('website/rtl/assets/css/bootstrap-datetimepicker.min.css') }}">

        <!-- Aos CSS -->
        <link rel="stylesheet" href="{{ asset('website/rtl/assets/plugins/aos/aos.css') }}">

        <!-- Fearther CSS -->
        <link rel="stylesheet" href="{{ asset('website/rtl/assets/css/feather.css') }}">

        <!-- Owl carousel CSS -->
        <link rel="stylesheet" href="{{ asset('website/rtl/assets/css/owl.carousel.min.css') }}">

        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="{{ asset('website/assets/plugins/flatpickr/flatpickr.min.css') }}">

        <!-- Fancybox CSS -->
        <link rel="stylesheet" href="{{ asset('website/rtl/assets/plugins/fancybox/fancybox.css') }}">

        <!-- Slick CSS -->
        <link rel="stylesheet" href="{{ asset('website/rtl/assets/plugins/slick/slick.css') }}">

        <!-- Boxicons CSS -->
        <link rel="stylesheet" href="{{ asset('website/rtl/assets/plugins/boxicons/css/boxicons.min.css') }}">

        <!-- Main CSS -->
        <link rel="stylesheet" href="{{ asset('website/rtl/assets/css/style.css?v=0.4') }}">
    @endif


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
