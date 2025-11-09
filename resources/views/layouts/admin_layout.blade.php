<!DOCTYPE html>
<html class="loading" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-textdirection="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="Robust admin is super flexible, powerful, clean &amp; modern responsive admin template.">
    <meta name="keywords"
        content="admin template, robust admin template, dashboard template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Afandina Admin Panel | @yield('title')</title>
    <link rel="apple-touch-icon" href="{{ asset('admin/dist/logo/afandina.svg') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('admin/dist/logo/afandina.svg') }}">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700"
        rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/vendors.css') }}">
    <!-- END VENDOR CSS-->
    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/app.css') }}">
    <!-- END ROBUST CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/colors/palette-gradient.css') }}">
    <!-- END Page Level CSS-->
    @stack('styles')
</head>

<body class="vertical-layout vertical-menu 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="2-columns">
    @include('includes.admin.navbar')
    @include('includes.admin.sidebar')

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-8 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">
                        @yield('page-title', __('Dashboard'))
                    </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                @hasSection('breadcrumbs')
                                    @yield('breadcrumbs')
                                @else
                                    <li class="breadcrumb-item active">
                                        @yield('page-title', __('Dashboard'))
                                    </li>
                                @endif
                            </ol>
                        </div>
                    </div>
                </div>
                @hasSection('page-actions')
                <div class="content-header-right col-md-4 col-12">
                    <div class="btn-group float-md-right">
                        @yield('page-actions')
                    </div>
                </div>
                @endif
            </div>
            <div class="content-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm p-2 px-3 rounded">
                        <strong>{{ __('Success') }}:</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('Close') }}">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    @include('includes.admin.footer')
    @include('includes.admin.footer_scripts')
    @stack('scripts')
</body>

</html>
