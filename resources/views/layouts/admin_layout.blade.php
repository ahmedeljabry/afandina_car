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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/vendors.css') }}">
    <!-- END VENDOR CSS-->
    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/app.css') }}">
    <!-- END ROBUST CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/dist/css/custom.css') }}">
    <!-- END Page Level CSS-->
    <link rel="stylesheet" href="{{asset('admin/plugins/fontawesome-free/css/all.min.css')}}">
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
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm mt-3 p-4 rounded-lg" role="alert">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle mr-2" style="font-size: 24px;"></i>
                            <div class="flex-grow-1">
                                <h5 class="alert-heading mb-2">{{ __('Please correct the following errors:') }}</h5>
                                <ul class="mb-0 pl-3">
                                    @foreach($errors->getBag('default')->toArray() as $field => $errorMessages)
                                        @foreach($errorMessages as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif
                @if (session('success'))
                    <noscript>
                        <div class="alert alert-success alert-dismissible fade show shadow-sm p-2 px-3 rounded">
                            <strong>{{ __('Success') }}:</strong> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </noscript>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    @include('includes.admin.footer')
    @include('includes.admin.footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach (['success' => 'success', 'error' => 'error', 'warning' => 'warning', 'info' => 'info'] as $type => $icon)
                @if (session($type))
                    Swal.fire({
                        icon: '{{ $icon }}',
                        title: "{{ ucfirst($icon) }}",
                        text: @json(session($type)),
                        timer: 3500,
                        showConfirmButton: false
                    });
                @endif
            @endforeach
        });
    </script>
    @stack('scripts')
</body>

</html>
