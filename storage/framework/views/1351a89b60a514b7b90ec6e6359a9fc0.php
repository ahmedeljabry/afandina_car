<?php
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
?>

<!-- Header -->
<div class="header">
    <div class="main-header">
        <div class="header-left">
            <a href="<?php echo e($logoUrl); ?>" class="logo">
                <img src="<?php echo e($logo); ?>" alt="<?php echo e(config('app.name', 'Admin')); ?>">
            </a>
            <a href="<?php echo e($logoUrl); ?>" class="dark-logo">
                <img src="<?php echo e($darkLogo); ?>" alt="<?php echo e(config('app.name', 'Admin')); ?>">
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
                        <a href="<?php echo e($primaryActionUrl); ?>" class="btn btn-dark d-inline-flex align-items-center">
                            <i class="ti ti-plus me-1"></i><?php echo e($primaryActionLabel); ?>

                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-center header-icons">
                    <div class="nav-item dropdown has-arrow flag-nav nav-item-box">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-toggle="dropdown" href="javascript:void(0);" role="button">
                            <img src="<?php echo e($currentLanguage['flag']); ?>" alt="<?php echo e($currentLanguage['name']); ?>" class="img-fluid">
                        </a>
                        <ul class="dropdown-menu p-2">
                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item <?php echo e($language['active'] ? 'active' : ''); ?>" data-locale="<?php echo e($language['code']); ?>">
                                        <img src="<?php echo e($language['flag']); ?>" alt="<?php echo e($language['name']); ?>" height="16"><?php echo e($language['name']); ?>

                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                <h5 class="notification-title"><?php echo e(__('Notifications')); ?></h5>
                                <ul class="nav nav-tabs nav-tabs-bottom">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#active-notification" data-bs-toggle="tab" data-toggle="tab">
                                            <?php echo e(__('Active')); ?>

                                            <span class="badge badge-xs rounded-pill bg-danger ms-2"><?php echo e($activeNotifications->count()); ?></span>
                                        </a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#unread-notification" data-bs-toggle="tab" data-toggle="tab"><?php echo e(__('Unread')); ?></a></li>
                                    <li class="nav-item"><a class="nav-link" href="#archieve-notification" data-bs-toggle="tab" data-toggle="tab"><?php echo e(__('Archive')); ?></a></li>
                                </ul>
                            </div>
                            <div class="noti-content">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="active-notification">
                                        <?php $__empty_1 = true; $__currentLoopData = $activeNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <div class="notification-list">
                                                <a href="<?php echo e($notification['url']); ?>">
                                                    <div class="d-flex align-items-center">
                                                        <span class="avatar avatar-lg <?php echo e($notification['read'] ? '' : 'offline'); ?> me-2 flex-shrink-0">
                                                            <img src="<?php echo e($notification['avatar']); ?>" alt="<?php echo e(__('Profile')); ?>" class="rounded-circle">
                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <p class="mb-1">
                                                                <span class="text-gray-9"><?php echo e($notification['title']); ?></span>
                                                                <?php if(filled($notification['message'])): ?>
                                                                    <?php echo e(' ' . $notification['message']); ?>

                                                                <?php endif; ?>
                                                            </p>
                                                            <?php if(filled($notification['time'])): ?>
                                                                <span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i><?php echo e($notification['time']); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="d-flex justify-content-center align-items-center p-3">
                                                <div class="text-center">
                                                    <img src="<?php echo e(asset('admin/assets/img/icons/nodata.svg')); ?>" class="mb-2" alt="<?php echo e(__('No data')); ?>">
                                                    <p class="text-gray-5 mb-0"><?php echo e(__('No notifications available')); ?></p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="tab-pane fade" id="unread-notification">
                                        <?php $__empty_1 = true; $__currentLoopData = $unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <div class="notification-list">
                                                <a href="<?php echo e($notification['url']); ?>">
                                                    <div class="d-flex align-items-center">
                                                        <span class="avatar avatar-lg offline me-2 flex-shrink-0">
                                                            <img src="<?php echo e($notification['avatar']); ?>" alt="<?php echo e(__('Profile')); ?>" class="rounded-circle">
                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <p class="mb-1"><span class="text-gray-9"><?php echo e($notification['title']); ?></span></p>
                                                            <?php if(filled($notification['time'])): ?>
                                                                <span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i><?php echo e($notification['time']); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="d-flex justify-content-center align-items-center p-3">
                                                <div class="text-center">
                                                    <img src="<?php echo e(asset('admin/assets/img/icons/nodata.svg')); ?>" class="mb-2" alt="<?php echo e(__('No data')); ?>">
                                                    <p class="text-gray-5 mb-0"><?php echo e(__('No unread notifications')); ?></p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="tab-pane fade" id="archieve-notification">
                                        <?php if($archivedNotifications->isEmpty()): ?>
                                            <div class="d-flex justify-content-center align-items-center p-3">
                                                <div class="text-center">
                                                    <img src="<?php echo e(asset('admin/assets/img/icons/nodata.svg')); ?>" class="mb-2" alt="<?php echo e(__('No data')); ?>">
                                                    <p class="text-gray-5 mb-0"><?php echo e(__('No archived notifications')); ?></p>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <?php $__currentLoopData = $archivedNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="notification-list">
                                                    <a href="<?php echo e($notification['url']); ?>">
                                                        <div class="d-flex align-items-center">
                                                            <span class="avatar avatar-lg me-2 flex-shrink-0">
                                                                <img src="<?php echo e($notification['avatar']); ?>" alt="<?php echo e(__('Profile')); ?>" class="rounded-circle">
                                                            </span>
                                                            <div class="flex-grow-1">
                                                                <p class="mb-1"><span class="text-gray-9"><?php echo e($notification['title']); ?></span></p>
                                                                <?php if(filled($notification['time'])): ?>
                                                                    <span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i><?php echo e($notification['time']); ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between topnav-dropdown-footer">
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="link-primary text-decoration-underline me-3"><?php echo e(__('Mark all as read')); ?></a>
                                    <a href="javascript:void(0);" class="link-danger text-decoration-underline"><?php echo e(__('Clear all')); ?></a>
                                </div>
                                <a href="javascript:void(0);" class="btn btn-primary btn-sm d-inline-flex align-items-center">
                                    <?php echo e(__('View all notifications')); ?><i class="ti ti-chevron-right ms-1"></i>
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
                                <?php $__empty_1 = true; $__currentLoopData = $quickActions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <li>
                                        <a href="<?php echo e($action['url']); ?>" class="dropdown-item d-inline-flex align-items-center">
                                            <i class="<?php echo e($action['icon']); ?> me-2"></i><?php echo e($action['label']); ?>

                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <li>
                                        <span class="dropdown-item text-muted"><?php echo e(__('No quick actions available')); ?></span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <div class="dropdown profile-dropdown">
                        <a href="javascript:void(0);" class="d-flex align-items-center" data-bs-toggle="dropdown" data-toggle="dropdown" data-bs-auto-close="outside">
                            <span class="avatar avatar-sm">
                                <img src="<?php echo e($avatar); ?>" alt="<?php echo e($user?->name ?? __('User')); ?>" class="img-fluid rounded-circle">
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="profileset d-flex align-items-center">
                                <span class="user-img me-2">
                                    <img src="<?php echo e($avatar); ?>" alt="<?php echo e($user?->name ?? __('User')); ?>">
                                </span>
                                <div>
                                    <h6 class="fw-semibold mb-1"><?php echo e($user?->name ?? __('Admin User')); ?></h6>
                                    <p class="fs-13"><?php echo e($user?->email ?? __('admin@example.com')); ?></p>
                                </div>
                            </div>
                            <a class="dropdown-item d-flex align-items-center" href="<?php echo e($profileUrl); ?>">
                                <i class="ti ti-user-edit me-2"></i><?php echo e(__('Profile')); ?>

                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="<?php echo e($paymentsUrl); ?>">
                                <i class="ti ti-credit-card me-2"></i><?php echo e(__('Payments')); ?>

                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="<?php echo e($settingsUrl); ?>">
                                <i class="ti ti-settings me-2"></i><?php echo e(__('Settings')); ?>

                            </a>
                            <div class="dropdown-divider my-2"></div>
                            <a class="dropdown-item logout d-flex align-items-center justify-content-between" href="<?php echo e($logoutUrl); ?>" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                                <span><i class="ti ti-logout me-2"></i><?php echo e(__('Logout Account')); ?></span>
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
                <a class="dropdown-item" href="<?php echo e($profileUrl); ?>"><?php echo e(__('My Profile')); ?></a>
                <a class="dropdown-item" href="<?php echo e($settingsUrl); ?>"><?php echo e(__('Settings')); ?></a>
                <a class="dropdown-item" href="<?php echo e($logoutUrl); ?>" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                    <?php echo e(__('Logout')); ?>

                </a>
            </div>
        </div>
    </div>
</div>
<!-- /Header -->

<form id="admin-logout-form" action="<?php echo e($logoutUrl); ?>" method="POST" class="d-none">
    <?php echo csrf_field(); ?>
</form>
<?php /**PATH D:\afandina\resources\views\includes\admin\navbar.blade.php ENDPATH**/ ?>