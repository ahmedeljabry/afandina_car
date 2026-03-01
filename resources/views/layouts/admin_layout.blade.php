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
    <title>{{ $adminSiteName ?? config('app.name', 'Afandina') }} @hasSection('title')| @yield('title')@endif</title>

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="{{ $adminFavicon ?? asset('website/assets/img/favicon.png') }}">

	<!-- Apple Touch Icon -->
	<link rel="apple-touch-icon" sizes="180x180" href="{{ $adminFavicon ?? asset('website/assets/img/favicon.png') }}">

	<!-- Theme Settings Js -->
	<script src="{{ asset('admin/assets/js/theme-script.js')  }}"></script>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}">

	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="{{ asset('admin/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin/assets/plugins/fontawesome/css/all.min.css') }}">

	<!-- Tabler Icon CSS -->
	<link rel="stylesheet" href="{{ asset('admin/assets/plugins/tabler-icons/tabler-icons.min.css') }}">

    <!-- Daterangepikcer CSS -->
	<link rel="stylesheet" href="{{ asset('admin/assets/plugins/daterangepicker/daterangepicker.css') }}">

	<!-- Main CSS -->
	<link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
	<style>
		.page-wrapper .table-responsive {
			border: 1px solid #eef2f7;
			border-radius: 18px;
			background: #fff;
		}

		.page-wrapper .table {
			margin-bottom: 0;
			--bs-table-bg: transparent;
		}

		.page-wrapper .table > :not(caption) > * > * {
			padding: 0.95rem 0.9rem;
			vertical-align: middle;
			border-bottom-color: #eef2f7;
		}

		.page-wrapper .table thead th {
			background: #f8fafc;
			color: #334155;
			font-size: 0.78rem;
			font-weight: 700;
			letter-spacing: 0.06em;
			text-transform: uppercase;
			border-bottom: 1px solid #e2e8f0;
			white-space: nowrap;
		}

		.page-wrapper .table tbody tr:hover {
			background: rgba(59, 130, 246, 0.04);
		}

		.page-wrapper .table tbody td:last-child {
			white-space: nowrap;
		}

		.page-wrapper .table tbody td .btn-group,
		.page-wrapper .table tbody tr td .action-icon {
			display: inline-flex;
			align-items: center;
			gap: 0.45rem;
			flex-wrap: nowrap;
		}

		.page-wrapper .table tbody td .btn-group form,
		.page-wrapper .table tbody td form {
			display: inline-block;
			margin: 0;
		}

		.page-wrapper .table tbody td .btn-group > .btn,
		.page-wrapper .table tbody td a.btn.btn-sm,
		.page-wrapper .table tbody td button.btn.btn-sm,
		.page-wrapper .table tbody td form .btn.btn-sm {
			min-width: 2.45rem;
			min-height: 2.45rem;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 0.35rem;
			padding: 0.45rem 0.7rem;
			border-radius: 12px;
			line-height: 1;
		}

		.page-wrapper .table tbody td .btn i {
			font-size: 0.95rem;
			line-height: 1;
		}

		.page-wrapper .table .badge {
			border-radius: 999px;
		}

		@media (max-width: 767.98px) {
			.page-wrapper .table > :not(caption) > * > * {
				padding: 0.8rem 0.75rem;
			}
		}
	</style>
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
