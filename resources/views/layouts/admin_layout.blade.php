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
	<link rel="stylesheet" href="{{ asset('admin/assets/plugins/tagify/tagify.css') }}">
	<style>
		:root {
			--tags-border-color: #d0d7e2;
			--tags-hover-border-color: #94a3b8;
			--tags-focus-border-color: #4c6ef5;
			--tag-bg: #eef2ff;
			--tag-hover: #dbeafe;
			--tag-text-color: #1e3a8a;
			--tag-remove-btn-color: #475569;
			--tag-pad: 0.3em 0.72em;
			--tag-border-radius: 999px;
		}

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

		.tagify {
			width: 100%;
			min-height: 48px;
			border-radius: 14px;
			padding: 0.35rem 0.45rem;
			border-color: #d0d7e2;
			box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.04);
			transition: border-color 0.2s ease, box-shadow 0.2s ease;
		}

		.tagify:hover {
			border-color: #94a3b8;
		}

		.tagify.tagify--focus {
			border-color: #4c6ef5;
			box-shadow: 0 0 0 0.2rem rgba(76, 110, 245, 0.14);
		}

		.tagify__input {
			margin: 0.15rem 0.35rem;
			padding: 0;
			min-width: 140px;
		}

		.tagify__tag {
			margin: 0.2rem;
		}

		.tagify__tag > div {
			border-radius: 999px;
			box-shadow: none;
		}

		.tagify__tag-text {
			font-weight: 600;
		}

		input.is-invalid + .tagify,
		textarea.is-invalid + .tagify,
		.tagify.is-invalid {
			border-color: #dc3545;
			box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.12);
		}

		.admin-swal-popup {
			border-radius: 22px;
			padding: 1.5rem 1.5rem 1.25rem;
			box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
		}

		.admin-swal-title {
			font-size: 1.25rem;
			font-weight: 700;
			color: #0f172a;
		}

		.admin-swal-content {
			color: #475569;
			font-size: 0.98rem;
		}

		.admin-swal-toast {
			border-radius: 16px !important;
			box-shadow: 0 16px 40px rgba(15, 23, 42, 0.18) !important;
		}

		.admin-swal-confirm,
		.admin-swal-cancel,
		.admin-swal-deny {
			border-radius: 12px !important;
			padding: 0.65rem 1rem !important;
			font-weight: 600 !important;
			box-shadow: none !important;
		}

		.admin-swal-list {
			margin: 0;
			padding-inline-start: 1.1rem;
			text-align: start;
		}

		.admin-swal-list li + li {
			margin-top: 0.35rem;
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
