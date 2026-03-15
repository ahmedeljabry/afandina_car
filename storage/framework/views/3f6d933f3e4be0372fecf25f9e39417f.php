<?php
    use Illuminate\Support\Str;
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

    $currentLocale = app()->getLocale();
    $supportedLocales = collect(LaravelLocalization::getSupportedLocales())->only(['ar', 'en']);
    $contactPageUrl = route('website.contact.index');
    $brandsSectionUrl = route('home') . '#home-brands';
    $categoriesSectionUrl = route('home') . '#home-categories';
?>

<style>
    @media (max-width: 575.96px) {
        .header .header-nav {
            padding: 0 12px;
            min-height: 65px;
            justify-content: center;
            position: relative;
        }

        .header .navbar-header {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 0 56px;
        }

        .header .navbar-header #mobile_btn {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            margin: 0;
            padding: 0;
        }

        .header .navbar-header .logo-small {
            display: flex;
            align-items: center;
            justify-content: center;
            width: auto;
            max-width: 100%;
            margin: 0 auto;
            text-align: center;
        }

        .header .navbar-header .logo-small img {
            width: auto;
            max-width: 180px;
            max-height: 34px;
            object-fit: contain;
        }

        .header .header-navbar-rht {
            display: flex !important;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            margin: 0;
            z-index: 9;
        }

        .header .header-navbar-rht > li {
            padding: 0;
        }

        .header .header-navbar-rht > li:not(.header-lang-switcher) {
            display: none !important;
        }

        .header .header-navbar-rht .header-lang-switcher .header-reg {
            padding: 6px 10px;
            font-size: 11px;
            line-height: 1;
            min-width: 52px;
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .header .header-navbar-rht .header-lang-switcher .header-reg span {
            margin-right: 4px;
        }

        html[dir="rtl"] .header .navbar-header #mobile_btn {
            left: auto;
            right: 12px;
        }

        html[dir="rtl"] .header .header-navbar-rht {
            right: auto;
            left: 12px;
        }

        html[dir="rtl"] .header .header-navbar-rht .header-lang-switcher .header-reg span {
            margin-right: 0;
            margin-left: 4px;
        }
    }
</style>

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
                    <img src="<?php echo e($headerLogo ?? asset('website/assets/img/logo.svg')); ?>" alt="<?php echo e($headerSiteName ?? config('app.name', 'Afandina Car Rental')); ?>" class="img-fluid">
                </a>

                <a href="<?php echo e(route('home')); ?>" class="navbar-brand logo-small">
                    <img src="<?php echo e($headerLogo ?? asset('website/assets/img/logo.svg')); ?>" alt="<?php echo e($headerSiteName ?? config('app.name', 'Afandina Car Rental')); ?>" class="img-fluid">
                </a>
            </div>

            <div class="main-menu-wrapper">
                <div class="menu-header">
                    <a href="<?php echo e(route('home')); ?>" class="menu-logo">
                        <img src="<?php echo e($headerLogo ?? asset('website/assets/img/logo.svg')); ?>" alt="<?php echo e($headerSiteName ?? config('app.name', 'Afandina Car Rental')); ?>" class="img-fluid">
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

                    <li class="has-submenu has-mega-dropdown <?php echo e(request()->routeIs('website.cars.brand') ? 'active' : ''); ?>">
                        <a href="<?php echo e($brandsSectionUrl); ?>">
                            <?php echo e(__('website.nav.brands')); ?>

                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="submenu mega-submenu header-mega-dropdown">
                            <li class="mega-menu-title d-lg-none"><?php echo e(__('website.nav.brands')); ?></li>
                            <li class="mega-menu-body">
                                <ul class="header-mega-grid" role="list">
                                    <?php $__empty_1 = true; $__currentLoopData = $headerBrands ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brandItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $brandName = (string) data_get($brandItem, 'name', __('website.common.brand'));
                                            $brandLogoPath = data_get($brandItem, 'logo_path');
                                            $brandCarsCount = (int) data_get($brandItem, 'cars_count', 0);
                                            $brandSlug = data_get($brandItem, 'slug');
                                            $brandId = data_get($brandItem, 'id');
                                            $brandHref = route('website.cars.brand', ['brand' => filled($brandSlug) ? $brandSlug : $brandId]);
                                        ?>
                                        <li class="header-mega-grid-item">
                                            <a href="<?php echo e($brandHref); ?>" class="header-mega-item">
                                                <span class="header-mega-item-media">
                                                    <?php if(filled($brandLogoPath)): ?>
                                                        <img src="<?php echo e($brandLogoPath); ?>" alt="<?php echo e($brandName); ?>">
                                                    <?php else: ?>
                                                        <span class="header-mega-item-fallback"><?php echo e(Str::substr($brandName, 0, 1)); ?></span>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="header-mega-item-content">
                                                    <strong><?php echo e($brandName); ?></strong>
                                                    <small><?php echo e(__('website.nav.cars_count', ['count' => $brandCarsCount])); ?></small>
                                                </span>
                                                <span class="header-mega-item-cta">
                                                    <?php echo e(__('website.nav.browse_cars', ['name' => $brandName])); ?>

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

                    <li class="has-submenu has-mega-dropdown <?php echo e(request()->routeIs('website.cars.category') ? 'active' : ''); ?>">
                        <a href="<?php echo e($categoriesSectionUrl); ?>">
                            <?php echo e(__('website.nav.categories')); ?>

                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="submenu mega-submenu header-mega-dropdown">
                            <li class="mega-menu-title d-lg-none"><?php echo e(__('website.nav.categories')); ?></li>
                            <li class="mega-menu-body">
                                <ul class="header-mega-grid" role="list">
                                    <?php $__empty_1 = true; $__currentLoopData = $headerCategories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $categoryName = (string) data_get($categoryItem, 'name', __('website.common.category'));
                                            $categoryImagePath = data_get($categoryItem, 'image_path');
                                            $categoryCarsCount = (int) data_get($categoryItem, 'cars_count', 0);
                                            $categorySlug = data_get($categoryItem, 'slug');
                                            $categoryId = data_get($categoryItem, 'id');
                                            $categoryHref = route('website.cars.category', ['category' => filled($categorySlug) ? $categorySlug : $categoryId]);
                                        ?>
                                        <li class="header-mega-grid-item">
                                            <a href="<?php echo e($categoryHref); ?>" class="header-mega-item">
                                                <span class="header-mega-item-media">
                                                    <?php if(filled($categoryImagePath)): ?>
                                                        <img src="<?php echo e($categoryImagePath); ?>" alt="<?php echo e($categoryName); ?>">
                                                    <?php else: ?>
                                                        <span class="header-mega-item-fallback"><?php echo e(Str::substr($categoryName, 0, 1)); ?></span>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="header-mega-item-content">
                                                    <strong><?php echo e($categoryName); ?></strong>
                                                    <small><?php echo e(__('website.nav.cars_count', ['count' => $categoryCarsCount])); ?></small>
                                                </span>
                                                <span class="header-mega-item-cta">
                                                    <?php echo e(__('website.nav.browse_cars', ['name' => $categoryName])); ?>

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

                </ul>
            </div>

            <ul class="nav header-navbar-rht">
                <li class="nav-item dropdown header-lang-switcher">
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