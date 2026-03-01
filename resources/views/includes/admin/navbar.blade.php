@php
    $user = auth()->user();
    $navbarConfig = is_array($adminNavbar ?? null) ? $adminNavbar : [];

    $routeUrl = static function (?string $routeName, mixed $parameters = []) {
        if (blank($routeName) || !\Illuminate\Support\Facades\Route::has($routeName)) {
            return 'javascript:void(0);';
        }

        return route($routeName, $parameters);
    };

    $logoUrl = $routeUrl('admin.dashboard');
    $logo = $navbarConfig['logo'] ?? asset('admin/assets/img/logo.svg');
    $darkLogo = $navbarConfig['dark_logo'] ?? asset('admin/assets/img/logo-white.svg');
    $defaultAvatar = asset('admin/assets/img/profiles/avatar-05.jpg');
    $avatar = $navbarConfig['avatar'] ?? $defaultAvatar;

    $primaryActionRoute = $navbarConfig['primary_action_route'] ?? 'admin.cars.create';
    $primaryActionLabel = $navbarConfig['primary_action_label'] ?? __('New Reservation');
    $primaryActionUrl = $routeUrl($primaryActionRoute);

    $reportRoute = $navbarConfig['report_route'] ?? 'admin.dashboard';
    $quickActions = collect($navbarConfig['quick_actions'] ?? [
        ['label' => __('Car'), 'route' => 'admin.cars.create', 'icon' => 'ti ti-car'],
        ['label' => __('Brand'), 'route' => 'admin.brands.create', 'icon' => 'ti ti-badge'],
        ['label' => __('Category'), 'route' => 'admin.categories.create', 'icon' => 'ti ti-layout-grid'],
        ['label' => __('Blog'), 'route' => 'admin.blogs.create', 'icon' => 'ti ti-notebook'],
        ['label' => __('Feature'), 'route' => 'admin.features.create', 'icon' => 'ti ti-star'],
        ['label' => __('Language'), 'route' => 'admin.languages.create', 'icon' => 'ti ti-language'],
    ])->map(function (array $item) use ($routeUrl): array {
        return [
            'label' => $item['label'] ?? __('Item'),
            'icon' => $item['icon'] ?? 'ti ti-circle-dot',
            'url' => isset($item['url']) ? (string) $item['url'] : $routeUrl($item['route'] ?? null, $item['parameters'] ?? []),
        ];
    })->filter(fn (array $item): bool => filled($item['url']) && $item['url'] !== 'javascript:void(0);')
        ->take(6)
        ->values();

    $supportedLocales = config('laravellocalization.supportedLocales', []);
    $flagMap = ['en' => 'gb.svg', 'ar' => 'sa.svg'];
    $languages = collect($supportedLocales)->map(function (array $item, string $code) use ($flagMap): array {
        $flag = $flagMap[$code] ?? strtolower($code) . '.png';
        $flagPath = public_path('admin/assets/img/flags/' . $flag);

        if (!file_exists($flagPath)) {
            $flag = 'gb.svg';
        }

        return [
            'code' => $code,
            'name' => $item['name'] ?? strtoupper($code),
            'flag' => asset('admin/assets/img/flags/' . $flag),
            'active' => app()->getLocale() === $code,
        ];
    })->values();

    $currentLanguage = $languages->firstWhere('active', true) ?? $languages->first() ?? [
        'name' => __('English'),
        'flag' => asset('admin/assets/img/flags/gb.svg'),
    ];

    $notifications = collect($navbarConfig['notifications'] ?? [])->map(function (array $item) use ($defaultAvatar): array {
        return [
            'title' => $item['title'] ?? __('Notification'),
            'message' => $item['message'] ?? null,
            'time' => $item['time'] ?? null,
            'url' => $item['url'] ?? 'javascript:void(0);',
            'avatar' => $item['avatar'] ?? $defaultAvatar,
            'read' => (bool) ($item['read'] ?? false),
            'archived' => (bool) ($item['archived'] ?? false),
        ];
    })->values();

    $activeNotifications = $notifications->where('archived', false)->values();
    $unreadNotifications = $activeNotifications->where('read', false)->values();
    $archivedNotifications = $notifications->where('archived', true)->values();

    $profileUrl = $routeUrl($navbarConfig['profile_route'] ?? 'admin.dashboard');
    $settingsUrl = $routeUrl($navbarConfig['settings_route'] ?? 'admin.languages.index');
    $paymentsUrl = $routeUrl($navbarConfig['payments_route'] ?? 'admin.currencies.index');
    $logoutUrl = $routeUrl('admin.logout');
@endphp

<!-- Header -->
<div class="header">
    <div class="main-header">
        <div class="header-left">
            <a href="{{ $logoUrl }}" class="logo">
                <img src="{{ $logo }}" alt="{{ config('app.name', 'Admin') }}">
            </a>
            <a href="{{ $logoUrl }}" class="dark-logo">
                <img src="{{ $darkLogo }}" alt="{{ config('app.name', 'Admin') }}">
            </a>
        </div>

        <a id="mobile_btn" class="mobile_btn nav-menu-main menu-toggle" href="javascript:void(0);">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>

        <div class="header-user">
            <div class="nav user-menu nav-list">
                <div class="me-auto d-flex align-items-center" id="header-search">
                    <a id="toggle_btn" class="menu-toggle" href="javascript:void(0);">
                        <i class="ti ti-menu-deep"></i>
                    </a>
                    <div class="add-dropdown">
                        <a href="{{ $primaryActionUrl }}" class="btn btn-dark d-inline-flex align-items-center">
                            <i class="ti ti-plus me-1"></i>{{ $primaryActionLabel }}
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-center header-icons">
                    <div class="nav-item dropdown has-arrow flag-nav nav-item-box">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-toggle="dropdown" href="javascript:void(0);" role="button">
                            <img src="{{ $currentLanguage['flag'] }}" alt="{{ $currentLanguage['name'] }}" class="img-fluid">
                        </a>
                        <ul class="dropdown-menu p-2">
                            @foreach($languages as $language)
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item {{ $language['active'] ? 'active' : '' }}" data-locale="{{ $language['code'] }}">
                                        <img src="{{ $language['flag'] }}" alt="{{ $language['name'] }}" height="16">{{ $language['name'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="theme-item">
                        <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle btn btn-menubar">
                            <i class="ti ti-moon"></i>
                        </a>
                        <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle btn btn-menubar">
                            <i class="ti ti-sun-high"></i>
                        </a>
                    </div>

                    <div class="notification_item">
                        <a href="javascript:void(0);" class="btn btn-menubar position-relative" id="notification_popup" data-bs-toggle="dropdown" data-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="ti ti-bell"></i>
                            <span class="badge bg-violet rounded-pill"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                            <div class="topnav-dropdown-header pb-0">
                                <h5 class="notification-title">{{ __('Notifications') }}</h5>
                                <ul class="nav nav-tabs nav-tabs-bottom">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#active-notification" data-bs-toggle="tab" data-toggle="tab">
                                            {{ __('Active') }}
                                            <span class="badge badge-xs rounded-pill bg-danger ms-2">{{ $activeNotifications->count() }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#unread-notification" data-bs-toggle="tab" data-toggle="tab">{{ __('Unread') }}</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#archieve-notification" data-bs-toggle="tab" data-toggle="tab">{{ __('Archive') }}</a></li>
                                </ul>
                            </div>
                            <div class="noti-content">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="active-notification">
                                        @forelse($activeNotifications as $notification)
                                            <div class="notification-list">
                                                <a href="{{ $notification['url'] }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="avatar avatar-lg {{ $notification['read'] ? '' : 'offline' }} me-2 flex-shrink-0">
                                                            <img src="{{ $notification['avatar'] }}" alt="{{ __('Profile') }}" class="rounded-circle">
                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <p class="mb-1">
                                                                <span class="text-gray-9">{{ $notification['title'] }}</span>
                                                                @if(filled($notification['message']))
                                                                    {{ ' ' . $notification['message'] }}
                                                                @endif
                                                            </p>
                                                            @if(filled($notification['time']))
                                                                <span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i>{{ $notification['time'] }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @empty
                                            <div class="d-flex justify-content-center align-items-center p-3">
                                                <div class="text-center">
                                                    <img src="{{ asset('admin/assets/img/icons/nodata.svg') }}" class="mb-2" alt="{{ __('No data') }}">
                                                    <p class="text-gray-5 mb-0">{{ __('No notifications available') }}</p>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="tab-pane fade" id="unread-notification">
                                        @forelse($unreadNotifications as $notification)
                                            <div class="notification-list">
                                                <a href="{{ $notification['url'] }}">
                                                    <div class="d-flex align-items-center">
                                                        <span class="avatar avatar-lg offline me-2 flex-shrink-0">
                                                            <img src="{{ $notification['avatar'] }}" alt="{{ __('Profile') }}" class="rounded-circle">
                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <p class="mb-1"><span class="text-gray-9">{{ $notification['title'] }}</span></p>
                                                            @if(filled($notification['time']))
                                                                <span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i>{{ $notification['time'] }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @empty
                                            <div class="d-flex justify-content-center align-items-center p-3">
                                                <div class="text-center">
                                                    <img src="{{ asset('admin/assets/img/icons/nodata.svg') }}" class="mb-2" alt="{{ __('No data') }}">
                                                    <p class="text-gray-5 mb-0">{{ __('No unread notifications') }}</p>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="tab-pane fade" id="archieve-notification">
                                        @if($archivedNotifications->isEmpty())
                                            <div class="d-flex justify-content-center align-items-center p-3">
                                                <div class="text-center">
                                                    <img src="{{ asset('admin/assets/img/icons/nodata.svg') }}" class="mb-2" alt="{{ __('No data') }}">
                                                    <p class="text-gray-5 mb-0">{{ __('No archived notifications') }}</p>
                                                </div>
                                            </div>
                                        @else
                                            @foreach($archivedNotifications as $notification)
                                                <div class="notification-list">
                                                    <a href="{{ $notification['url'] }}">
                                                        <div class="d-flex align-items-center">
                                                            <span class="avatar avatar-lg me-2 flex-shrink-0">
                                                                <img src="{{ $notification['avatar'] }}" alt="{{ __('Profile') }}" class="rounded-circle">
                                                            </span>
                                                            <div class="flex-grow-1">
                                                                <p class="mb-1"><span class="text-gray-9">{{ $notification['title'] }}</span></p>
                                                                @if(filled($notification['time']))
                                                                    <span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i>{{ $notification['time'] }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between topnav-dropdown-footer">
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="link-primary text-decoration-underline me-3">{{ __('Mark all as read') }}</a>
                                    <a href="javascript:void(0);" class="link-danger text-decoration-underline">{{ __('Clear all') }}</a>
                                </div>
                                <a href="javascript:void(0);" class="btn btn-primary btn-sm d-inline-flex align-items-center">
                                    {{ __('View all notifications') }}<i class="ti ti-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <a href="javascript:void(0);" class="btn btn-menubar" data-bs-toggle="dropdown" data-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="ti ti-grid-dots"></i>
                        </a>
                        <div class="dropdown-menu p-3">
                            <ul>
                                @forelse($quickActions as $action)
                                    <li>
                                        <a href="{{ $action['url'] }}" class="dropdown-item d-inline-flex align-items-center">
                                            <i class="{{ $action['icon'] }} me-2"></i>{{ $action['label'] }}
                                        </a>
                                    </li>
                                @empty
                                    <li>
                                        <span class="dropdown-item text-muted">{{ __('No quick actions available') }}</span>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <div class="dropdown profile-dropdown">
                        <a href="javascript:void(0);" class="d-flex align-items-center" data-bs-toggle="dropdown" data-toggle="dropdown" data-bs-auto-close="outside">
                            <span class="avatar avatar-sm">
                                <img src="{{ $avatar }}" alt="{{ $user?->name ?? __('User') }}" class="img-fluid rounded-circle">
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="profileset d-flex align-items-center">
                                <span class="user-img me-2">
                                    <img src="{{ $avatar }}" alt="{{ $user?->name ?? __('User') }}">
                                </span>
                                <div>
                                    <h6 class="fw-semibold mb-1">{{ $user?->name ?? __('Admin User') }}</h6>
                                    <p class="fs-13">{{ $user?->email ?? __('admin@example.com') }}</p>
                                </div>
                            </div>
                            <a class="dropdown-item d-flex align-items-center" href="{{ $profileUrl }}">
                                <i class="ti ti-user-edit me-2"></i>{{ __('Profile') }}
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="{{ $paymentsUrl }}">
                                <i class="ti ti-credit-card me-2"></i>{{ __('Payments') }}
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="{{ $settingsUrl }}">
                                <i class="ti ti-settings me-2"></i>{{ __('Settings') }}
                            </a>
                            <div class="dropdown-divider my-2"></div>
                            <a class="dropdown-item logout d-flex align-items-center justify-content-between" href="{{ $logoutUrl }}" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                                <span><i class="ti ti-logout me-2"></i>{{ __('Logout Account') }}</span>
                                <i class="ti ti-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dropdown mobile-user-menu">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="{{ $profileUrl }}">{{ __('My Profile') }}</a>
                <a class="dropdown-item" href="{{ $settingsUrl }}">{{ __('Settings') }}</a>
                <a class="dropdown-item" href="{{ $logoutUrl }}" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                    {{ __('Logout') }}
                </a>
            </div>
        </div>
    </div>
</div>
<!-- /Header -->

<form id="admin-logout-form" action="{{ $logoutUrl }}" method="POST" class="d-none">
    @csrf
</form>
