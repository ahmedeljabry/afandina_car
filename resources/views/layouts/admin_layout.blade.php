<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="description" content="Dreamsrent - Bootstrap Admin Template">
	<meta name="keywords" content="admin, estimates, bootstrap, business, html5, responsive, Projects">
	<meta name="author" content="Dreams technologies - Bootstrap Admin Template">
	<meta name="robots" content="noindex, nofollow">
    <title>Afandina Admin Panel | @yield('title')</title>

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

	<!-- Apple Touch Icon -->
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png">

	<!-- Theme Settings Js -->
	<script src="{{ asset('admin/assets/js/theme-script.js')  }}"></script>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}">

	<!-- Tabler Icon CSS -->
	<link rel="stylesheet" href="{{ asset('admin/assets/plugins/tabler-icons/tabler-icons.min.css') }}">

    <!-- Daterangepikcer CSS -->
	<link rel="stylesheet" href="{{ asset('admin/assets/plugins/daterangepicker/daterangepicker.css') }}">

	<!-- Main CSS -->
	<link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
    @stack('styles')
</head>


<body>
    <!-- Main Wrapper -->
	<div class="main-wrapper">
        
        {{-- Header --}}
        @include('includes.admin.navbar')
        {{-- Sidebar --}}
        @include('includes.admin.sidebar')
        {{-- Page Wrapper --}}
		<div class="page-wrapper">
            <div class="content pb-0">
                <!-- Breadcrumb -->
                <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
                    <div class="my-auto mb-2">
                        <h4 class="mb-1">@yield('page-title', __('Admin Dashboard'))</h4>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                                </li> 
                                @hasSection('breadcrumbs')
                                    @yield('breadcrumbs')
                                @else
                                    <li class="breadcrumb-item active" aria-current="page">
                                        @yield('page-title', __('Admin Dashboard'))
                                    </li>
                                @endif
                            </ol>
                        </nav>
                    </div>
                    @hasSection('page-actions')
                        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap mb-2">
                            @yield('page-actions')
                        </div>
                    @endif
                </div>
                <!-- /Breadcrumb -->

                {{-- Page Content --}}
                @yield('content')
            </div>
        </div>
        {{-- Footer --}}
        @include('includes.admin.footer')
    </div>
    @include('includes.admin.footer_scripts')
</body>

</html>
