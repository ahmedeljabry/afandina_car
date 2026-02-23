<?php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Route;
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

    $currentLocale = app()->getLocale();
    $supportedLocales = collect(LaravelLocalization::getSupportedLocales())->only(['ar', 'en']);
    $contactPageUrl = Route::has('website.contact.index') ? route('website.contact.index') : 'javascript:void(0);';
?>

<!-- Header -->
<header class="header">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg header-nav">
            <div class="navbar-header">
                <a id="mobile_btn" href="javascript:void(0);">
                    <span class="bar-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </a>

                <a href="<?php echo e(route('home')); ?>" class="navbar-brand logo">
                </a>

                <a href="<?php echo e(route('home')); ?>" class="navbar-brand logo-small">
                </a>
            </div>

            <div class="main-menu-wrapper">
                <div class="menu-header">
                    <a href="<?php echo e(route('home')); ?>" class="menu-logo">
                    </a>
                    <a id="menu_close" class="menu-close" href="javascript:void(0);">
                        <i class="fas fa-times"></i>
                    </a>
                </div>

                <ul class="main-nav">
                    <li class="<?php echo e(request()->routeIs('home') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('home')); ?>"><?php echo e(__('website.nav.home')); ?></a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('website.cars.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('website.cars.index')); ?>"><?php echo e(__('website.nav.all_cars')); ?></a>
                    </li>

                    <li class="has-submenu has-mega-dropdown <?php echo e(request()->routeIs('website.cars.index') && request()->filled('brand') ? 'active' : ''); ?>">
                        <a href="javascript:void(0);">
                            <?php echo e(__('website.nav.brands')); ?>

                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="submenu mega-submenu header-mega-dropdown">
                            <li class="mega-menu-title d-lg-none"><?php echo e(__('website.nav.brands')); ?></li>
                            <li class="mega-menu-body">
                                <ul class="header-mega-grid" role="list">
                                    <?php $__empty_1 = true; $__currentLoopData = $headerBrands ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brandItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <li class="header-mega-grid-item">
                                            <a href="<?php echo e($brandItem['url']); ?>" class="header-mega-item">
                                                <span class="header-mega-item-media">
                                                    <?php if(filled($brandItem['logo_path'] ?? null)): ?>
                                                        <img src="<?php echo e($brandItem['logo_path']); ?>" alt="<?php echo e($brandItem['name']); ?>">
                                                    <?php else: ?>
                                                        <span class="header-mega-item-fallback"><?php echo e(Str::substr($brandItem['name'], 0, 1)); ?></span>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="header-mega-item-content">
                                                    <strong><?php echo e($brandItem['name']); ?></strong>
                                                    <small><?php echo e(__('website.nav.cars_count', ['count' => $brandItem['cars_count']])); ?></small>
                                                </span>
                                                <span class="header-mega-item-cta">
                                                    <?php echo e(__('website.nav.browse_cars', ['name' => $brandItem['name']])); ?>

                                                </span>
                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <li class="header-mega-grid-item header-mega-grid-empty">
                                            <span class="header-mega-empty"><?php echo e(__('website.home.empty_brands')); ?></span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="has-submenu has-mega-dropdown <?php echo e(request()->routeIs('website.cars.index') && request()->filled('category') ? 'active' : ''); ?>">
                        <a href="javascript:void(0);">
                            <?php echo e(__('website.nav.categories')); ?>

                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="submenu mega-submenu header-mega-dropdown">
                            <li class="mega-menu-title d-lg-none"><?php echo e(__('website.nav.categories')); ?></li>
                            <li class="mega-menu-body">
                                <ul class="header-mega-grid" role="list">
                                    <?php $__empty_1 = true; $__currentLoopData = $headerCategories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <li class="header-mega-grid-item">
                                            <a href="<?php echo e($categoryItem['url']); ?>" class="header-mega-item">
                                                <span class="header-mega-item-media">
                                                    <?php if(filled($categoryItem['image_path'] ?? null)): ?>
                                                        <img src="<?php echo e($categoryItem['image_path']); ?>" alt="<?php echo e($categoryItem['name']); ?>">
                                                    <?php else: ?>
                                                        <span class="header-mega-item-fallback"><?php echo e(Str::substr($categoryItem['name'], 0, 1)); ?></span>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="header-mega-item-content">
                                                    <strong><?php echo e($categoryItem['name']); ?></strong>
                                                    <small><?php echo e(__('website.nav.cars_count', ['count' => $categoryItem['cars_count']])); ?></small>
                                                </span>
                                                <span class="header-mega-item-cta">
                                                    <?php echo e(__('website.nav.browse_cars', ['name' => $categoryItem['name']])); ?>

                                                </span>
                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <li class="header-mega-grid-item header-mega-grid-empty">
                                            <span class="header-mega-empty"><?php echo e(__('website.home.empty_categories')); ?></span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="<?php echo e(request()->routeIs('website.about.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('website.about.index')); ?>"><?php echo e(__('website.nav.about_us')); ?></a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('website.blogs.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('website.blogs.index')); ?>"><?php echo e(__('website.nav.blogs')); ?></a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('website.contact.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e($contactPageUrl); ?>"><?php echo e(__('website.nav.contact_us')); ?></a>
                    </li>

                    <li class="has-submenu d-lg-none">
                        <a href="javascript:void(0);">
                            <?php echo e(__('website.nav.language')); ?>

                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="submenu">
                            <?php $__currentLoopData = $supportedLocales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $localeCode => $properties): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a href="<?php echo e(LaravelLocalization::getLocalizedURL($localeCode, null, [], true)); ?>"
                                        class="<?php echo e($currentLocale === $localeCode ? 'active' : ''); ?>">
                                        <?php echo e(strtoupper($localeCode)); ?> - <?php echo e(data_get($properties, 'native', data_get($properties, 'name', strtoupper($localeCode)))); ?>

                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </li>
                </ul>
            </div>

            <ul class="nav header-navbar-rht">
                <li class="nav-item dropdown">
                    <a class="nav-link header-reg dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span><i class="fa-solid fa-language"></i></span><?php echo e(strtoupper($currentLocale)); ?>

                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php $__currentLoopData = $supportedLocales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $localeCode => $properties): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a class="dropdown-item <?php echo e($currentLocale === $localeCode ? 'active' : ''); ?>"
                                    href="<?php echo e(LaravelLocalization::getLocalizedURL($localeCode, null, [], true)); ?>">
                                    <?php echo e(strtoupper($localeCode)); ?> - <?php echo e(data_get($properties, 'native', data_get($properties, 'name', strtoupper($localeCode)))); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</header>
<!-- /Header -->
<?php /**PATH D:\afandina\resources\views/includes/website/header.blade.php ENDPATH**/ ?>