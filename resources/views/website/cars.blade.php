@extends('layouts.website')

<?php
    $listingContext = $listingContext ?? [];
    $pageHeading = (string) ($listingContext['page_title'] ?? __('website.cars.page_title'));
    $metaTitle = (string) ($listingContext['meta_title'] ?? $pageHeading);
?>

@section('title', $metaTitle)

@section('content')
    <?php
        use Illuminate\Support\Str;

        $assetUrl = static fn(string $path): string => asset('website/assets/' . ltrim($path, '/'));

        $storageUrl = static function (?string $path, ?string $fallback = null): ?string {
            if (blank($path)) {
                return $fallback;
            }

            if (Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            return asset('storage/' . ltrim($path, '/'));
        };

        $showFilters = (bool) ($listingContext['show_filters'] ?? true);
        $listingActionUrl = (string) ($listingContext['action_url'] ?? route('website.cars.index'));
        $resetUrl = (string) ($listingContext['reset_url'] ?? route('website.cars.index'));
        $breadcrumbParentLabel = (string) ($listingContext['breadcrumb_parent_label'] ?? __('website.nav.all_cars'));
        $breadcrumbParentUrl = (string) ($listingContext['breadcrumb_parent_url'] ?? route('website.cars.index'));
        $breadcrumbCurrentLabel = (string) ($listingContext['breadcrumb_current_label'] ?? $pageHeading);
        $showSeoContentInBreadcrumb = (bool) ($listingContext['seo_content_in_breadcrumb'] ?? false);
        $listingContentTitle = trim((string) ($listingContext['content_title'] ?? ''));
        $listingContentDescription = trim((string) ($listingContext['content_description'] ?? ''));
        $listingContentArticle = trim((string) ($listingContext['content_article'] ?? ''));
        $breadcrumbTitle = $showSeoContentInBreadcrumb && $listingContentTitle !== ''
            ? $listingContentTitle
            : $pageHeading;
        $breadcrumbDescription = $showSeoContentInBreadcrumb && $listingContentDescription !== ''
            ? trim(strip_tags($listingContentDescription))
            : '';
        $showListingContent = $listingContentArticle !== ''
            || (!$showSeoContentInBreadcrumb && ($listingContentTitle !== '' || $listingContentDescription !== ''));

        $brands = collect($filters['brands'] ?? []);
        $categories = collect($filters['categories'] ?? []);
        $years = collect($filters['years'] ?? []);

        $selectedBrandIds = collect($filters['selected_brand_ids'] ?? [])->map(fn($id) => (int) $id)->all();
        $selectedCategoryIds = collect($filters['selected_category_ids'] ?? [])->map(fn($id) => (int) $id)->all();
        $selectedYearIds = collect($filters['selected_year_ids'] ?? [])->map(fn($id) => (int) $id)->all();

        $search = (string) ($filters['search'] ?? '');
        $availableOnly = (bool) ($filters['available_only'] ?? false);
        $sort = (string) ($filters['sort'] ?? 'newest');
        $perPage = (int) ($filters['per_page'] ?? 9);

        $from = $cars->firstItem() ?? 0;
        $to = $cars->lastItem() ?? 0;
        $total = $cars->total();

        $currentPage = $cars->currentPage();
        $lastPage = $cars->lastPage();
        $startPage = max(1, $currentPage - 1);
        $endPage = min($lastPage, $currentPage + 1);

        $formatCurrency = static function ($price, ?string $currencyLabel): string {
            $label = trim((string) $currencyLabel);

            return ($label !== '' ? $label . ' ' : '') . number_format((float) $price);
        };

        $formatCarCardTitle = static function (?string $name): string {
            $fullName = trim((string) $name);
            $words = preg_split('/\s+/', $fullName, -1, PREG_SPLIT_NO_EMPTY) ?: [];

            if (count($words) <= 1) {
                return $fullName;
            }

            return implode(' ', array_slice($words, 1, 4));
        };

        $phoneValue = $contact?->phone ?: $contact?->alternative_phone;
        $phoneHref = filled($phoneValue) ? 'tel:' . preg_replace('/\s+/', '', $phoneValue) : 'javascript:void(0);';
        $whatsAppHref = filled($contact?->whatsapp)
            ? (str_starts_with((string) $contact->whatsapp, 'http://') || str_starts_with((string) $contact->whatsapp, 'https://')
                ? $contact->whatsapp
                : 'https://wa.me/' . preg_replace('/\D+/', '', (string) $contact->whatsapp))
            : 'javascript:void(0);';
    ?>

    <style>
        .car-card-title {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .listing-action-group {
            display: flex;
            gap: 10px;
        }

        .listing-action-group .listing-action-btn {
            flex: 1 1 0;
            width: 0;
            min-width: 0;
            height: 48px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 600;
            padding: 0 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .listing-action-group .listing-action-btn .action-label {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .listing-action-group .listing-action-btn.whatsapp-btn {
            background: #121212;
            border-color: #121212;
            color: #fff;
        }

        .listing-action-group .listing-action-btn.whatsapp-btn:hover,
        .listing-action-group .listing-action-btn.whatsapp-btn:focus {
            background: #252525;
            border-color: #252525;
            color: #fff;
        }

        .listing-action-group .listing-action-btn.call-btn {
            background: #fff;
            border: 1px solid #121212;
            color: #121212;
        }

        .listing-action-group .listing-action-btn.call-btn:hover,
        .listing-action-group .listing-action-btn.call-btn:focus {
            background: #121212;
            color: #fff;
        }

        .listing-action-group .listing-action-btn.disabled {
            opacity: 0.55;
            pointer-events: none;
        }

        .listing-item .listing-details-group ul li {
            min-width: 0;
        }

        .listing-item .listing-details-group ul li p {
            min-width: 0;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .count-search {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .cars-filter-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 0;
            border-bottom: 1px solid #e2e2e2;
            background: transparent;
            color: #4f5761;
            font-size: 24px;
            font-weight: 500;
            line-height: 1;
            padding: 0 4px 10px;
        }

        .cars-filter-button i {
            font-size: 18px;
        }

        .cars-filter-button:focus-visible {
            outline: 2px solid #121212;
            outline-offset: 3px;
        }

        .sorting-div .product-filter-group .sortbyset {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .sorting-div .product-filter-group .sortbyset .cars-sort-filter-btn {
            margin-right: 0;
            margin-left: 10px;
            flex-shrink: 0;
        }

        html[dir="rtl"] .sorting-div .product-filter-group .sortbyset .cars-sort-filter-btn {
            margin-left: 0;
            margin-right: 10px;
        }

        .cars-filter-offcanvas {
            --bs-offcanvas-width: 440px;
        }

        .cars-filter-offcanvas .offcanvas-header {
            border-bottom: 1px solid #ececec;
            padding: 24px 20px;
        }

        .cars-filter-offcanvas .offcanvas-title {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 36px;
            font-weight: 700;
            margin: 0;
            color: #1f1f1f;
        }

        .cars-filter-offcanvas .offcanvas-body {
            padding: 0;
        }

        .cars-filter-offcanvas .cars-filter-form {
            display: flex;
            flex-direction: column;
            min-height: 100%;
            padding: 24px 20px;
        }

        .cars-filter-offcanvas .cars-filter-form .product-search,
        .cars-filter-offcanvas .cars-filter-form .product-availability,
        .cars-filter-offcanvas .cars-filter-form .accord-list {
            margin-bottom: 24px;
        }

        .cars-filter-actions {
            margin-top: auto;
            padding-top: 20px;
            position: sticky;
            bottom: 0;
            background: #fff;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .cars-filter-actions .filter-btn,
        .cars-filter-actions .reset-filter {
            width: 100%;
            height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-weight: 600;
        }

        .cars-filter-actions .filter-btn {
            margin: 0;
        }

        .cars-filter-actions .reset-filter {
            border: 1px solid #d9d9d9;
            color: #121212;
            text-decoration: none;
            background: #fff;
        }

        .cars-filter-actions .reset-filter:hover,
        .cars-filter-actions .reset-filter:focus {
            color: #121212;
            border-color: #121212;
        }

        @media (max-width: 575.98px) {
            .listing-action-group .listing-action-btn {
                height: 46px;
                font-size: 14px;
                padding: 0 10px;
            }

            .cars-filter-button {
                font-size: 18px;
                padding-bottom: 8px;
            }

            .sorting-div .product-filter-group .sortbyset .cars-sort-filter-btn {
                margin-left: auto;
            }

            html[dir="rtl"] .sorting-div .product-filter-group .sortbyset .cars-sort-filter-btn {
                margin-right: auto;
                margin-left: 0;
            }

            .cars-filter-offcanvas {
                --bs-offcanvas-width: 100%;
            }

            .cars-filter-offcanvas .offcanvas-title {
                font-size: 30px;
            }
        }

        .listing-seo-content {
            margin-top: 30px;
        }

        .listing-breadcrumb-description {
            margin: 12px 0 0;
            color: #fff;
            font-size: 17px;
            line-height: 1.65;
            font-weight: 600;
        }

        .listing-seo-content h2 {
            margin: 0 0 16px;
            font-size: 46px;
            line-height: 1.22;
            color: #1f1f1f;
            font-weight: 700;
        }

        .listing-seo-content .listing-seo-description,
        .listing-seo-content .listing-seo-article,
        .listing-seo-content .listing-seo-article p,
        .listing-seo-content .listing-seo-article li {
            color: #4f5761;
            font-size: 16px;
            line-height: 1.75;
        }

        .listing-seo-content .listing-seo-article h1,
        .listing-seo-content .listing-seo-article h2,
        .listing-seo-content .listing-seo-article h3,
        .listing-seo-content .listing-seo-article h4,
        .listing-seo-content .listing-seo-article h5,
        .listing-seo-content .listing-seo-article h6 {
            margin-top: 16px;
            margin-bottom: 10px;
            color: #1f1f1f;
            font-weight: 600;
            line-height: 1.4;
            font-size: 24px;
        }

        .listing-seo-content .listing-seo-article > h1:first-child,
        .listing-seo-content .listing-seo-article > h2:first-child,
        .listing-seo-content .listing-seo-article > h3:first-child,
        .listing-seo-content .listing-seo-article > h4:first-child,
        .listing-seo-content .listing-seo-article > h5:first-child,
        .listing-seo-content .listing-seo-article > h6:first-child {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 14px;
        }

        @media (max-width: 991.98px) {
            .listing-breadcrumb-description {
                font-size: 16px;
            }

            .listing-seo-content h2 {
                font-size: 38px;
            }

            .listing-seo-content .listing-seo-description,
            .listing-seo-content .listing-seo-article,
            .listing-seo-content .listing-seo-article p,
            .listing-seo-content .listing-seo-article li {
                font-size: 15px;
            }

            .listing-seo-content .listing-seo-article h1,
            .listing-seo-content .listing-seo-article h2,
            .listing-seo-content .listing-seo-article h3,
            .listing-seo-content .listing-seo-article h4,
            .listing-seo-content .listing-seo-article h5,
            .listing-seo-content .listing-seo-article h6 {
                font-size: 22px;
            }

            .listing-seo-content .listing-seo-article > h1:first-child,
            .listing-seo-content .listing-seo-article > h2:first-child,
            .listing-seo-content .listing-seo-article > h3:first-child,
            .listing-seo-content .listing-seo-article > h4:first-child,
            .listing-seo-content .listing-seo-article > h5:first-child,
            .listing-seo-content .listing-seo-article > h6:first-child {
                font-size: 26px;
            }
        }

        @media (max-width: 767.98px) {
            .listing-seo-content h2 {
                font-size: 30px;
            }

            .listing-seo-content .listing-seo-description,
            .listing-seo-content .listing-seo-article,
            .listing-seo-content .listing-seo-article p,
            .listing-seo-content .listing-seo-article li {
                font-size: 14px;
            }

            .listing-seo-content .listing-seo-article h1,
            .listing-seo-content .listing-seo-article h2,
            .listing-seo-content .listing-seo-article h3,
            .listing-seo-content .listing-seo-article h4,
            .listing-seo-content .listing-seo-article h5,
            .listing-seo-content .listing-seo-article h6 {
                font-size: 20px;
            }

            .listing-seo-content .listing-seo-article > h1:first-child,
            .listing-seo-content .listing-seo-article > h2:first-child,
            .listing-seo-content .listing-seo-article > h3:first-child,
            .listing-seo-content .listing-seo-article > h4:first-child,
            .listing-seo-content .listing-seo-article > h5:first-child,
            .listing-seo-content .listing-seo-article > h6:first-child {
                font-size: 24px;
            }
        }

        @media (max-width: 575.98px) {
            .listing-breadcrumb-description {
                font-size: 15px;
                line-height: 1.7;
            }

            .listing-seo-content h2 {
                font-size: 24px;
            }

            .listing-seo-content .listing-seo-description,
            .listing-seo-content .listing-seo-article,
            .listing-seo-content .listing-seo-article p,
            .listing-seo-content .listing-seo-article li {
                font-size: 14px;
                line-height: 1.75;
            }

            .listing-seo-content .listing-seo-article h1,
            .listing-seo-content .listing-seo-article h2,
            .listing-seo-content .listing-seo-article h3,
            .listing-seo-content .listing-seo-article h4,
            .listing-seo-content .listing-seo-article h5,
            .listing-seo-content .listing-seo-article h6 {
                font-size: 18px;
            }

            .listing-seo-content .listing-seo-article > h1:first-child,
            .listing-seo-content .listing-seo-article > h2:first-child,
            .listing-seo-content .listing-seo-article > h3:first-child,
            .listing-seo-content .listing-seo-article > h4:first-child,
            .listing-seo-content .listing-seo-article > h5:first-child,
            .listing-seo-content .listing-seo-article > h6:first-child {
                font-size: 21px;
            }
        }
    </style>

    <!-- Breadscrumb Section -->
    <div class="breadcrumb-bar">
        <div class="container">
            <div class="row align-items-center <?php echo e($showSeoContentInBreadcrumb ? 'text-start' : 'text-center'); ?>">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title"><?php echo e($breadcrumbTitle); ?></h2>
                    <?php if($breadcrumbDescription !== ''): ?>
                        <p class="listing-breadcrumb-description"><?php echo e($breadcrumbDescription); ?></p>
                    <?php endif; ?>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('website.nav.home')); ?></a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e($breadcrumbParentUrl); ?>"><?php echo e($breadcrumbParentLabel); ?></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo e($breadcrumbCurrentLabel); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- /Breadscrumb Section -->

    <!-- Sort By -->
    <div class="sort-section">
        <div class="container">
            <div class="sortby-sec">
                <div class="sorting-div">
                    <div class="row d-flex align-items-center">
                        <div class="col-xl-4 col-lg-3 col-sm-12 col-12">
                            <div class="count-search">
                                <p><?php echo e(__('website.cars.showing_results', ['from' => $from, 'to' => $to, 'total' => $total])); ?>

                                </p>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-9 col-sm-12 col-12">
                            <form action="<?php echo e($listingActionUrl); ?>" method="GET" class="product-filter-group">
                                <?php if($showFilters): ?>
                                    <input type="hidden" name="search" value="<?php echo e($search); ?>">
                                    <?php if($availableOnly): ?>
                                        <input type="hidden" name="available_only" value="1">
                                    <?php endif; ?>
                                    <?php $__currentLoopData = $selectedBrandIds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brandId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="hidden" name="brand[]" value="<?php echo e($brandId); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $selectedCategoryIds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="hidden" name="category[]" value="<?php echo e($categoryId); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $selectedYearIds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yearId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="hidden" name="year[]" value="<?php echo e($yearId); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

                                <div class="sortbyset">
                                    <ul>
                                        <li>
                                            <span class="sortbytitle"><?php echo e(__('website.cars.sort.show')); ?> : </span>
                                            <div class="sorting-select select-one">
                                                <select class="form-control select" name="per_page"
                                                    onchange="this.form.submit()">
                                                    <?php $__currentLoopData = [6, 9, 12, 18, 24]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($size); ?>" <?php if($perPage === $size): echo 'selected'; endif; ?>><?php echo e($size); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </li>
                                        <li>
                                            <span class="sortbytitle"><?php echo e(__('website.cars.sort.sort_by')); ?></span>
                                            <div class="sorting-select select-two">
                                                <select class="form-control select" name="sort"
                                                    onchange="this.form.submit()">
                                                    <option value="newest" <?php if($sort === 'newest'): echo 'selected'; endif; ?>>
                                                        <?php echo e(__('website.cars.sort.newest')); ?></option>
                                                    <option value="price_low" <?php if($sort === 'price_low'): echo 'selected'; endif; ?>>
                                                        <?php echo e(__('website.cars.sort.price_low')); ?></option>
                                                    <option value="price_high" <?php if($sort === 'price_high'): echo 'selected'; endif; ?>>
                                                        <?php echo e(__('website.cars.sort.price_high')); ?></option>
                                                    <option value="year_new" <?php if($sort === 'year_new'): echo 'selected'; endif; ?>>
                                                        <?php echo e(__('website.cars.sort.year_new')); ?></option>
                                                    <option value="year_old" <?php if($sort === 'year_old'): echo 'selected'; endif; ?>>
                                                        <?php echo e(__('website.cars.sort.year_old')); ?></option>
                                                </select>
                                            </div>
                                        </li>
                                    </ul>
                                    <?php if($showFilters): ?>
                                        <button type="button" class="filter-btns cars-sort-filter-btn" data-bs-toggle="offcanvas"
                                            data-bs-target="#carsFilterOffcanvas" aria-controls="carsFilterOffcanvas">
                                            <i class="feather-filter"></i>
                                            <span><?php echo e(__('website.cars.filters.button')); ?></span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Sort By -->

    <?php if($showFilters): ?>
        <div class="offcanvas offcanvas-end cars-filter-offcanvas" tabindex="-1" id="carsFilterOffcanvas"
            aria-labelledby="carsFilterOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="carsFilterOffcanvasLabel">
                    <i class="feather-filter"></i><?php echo e(__('website.cars.filters.drawer_title')); ?>

                </h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="<?php echo e(__('website.common.close')); ?>"></button>
            </div>
            <div class="offcanvas-body">
                <form action="<?php echo e($listingActionUrl); ?>" method="GET" autocomplete="off"
                    class="sidebar-form cars-filter-form">
                    <div class="product-search">
                        <div class="form-custom">
                            <input type="text" class="form-control" name="search" value="<?php echo e($search); ?>"
                                placeholder="<?php echo e(__('website.cars.filters.search_placeholder')); ?>">
                            <span><img src="<?php echo e($assetUrl('img/icons/search.svg')); ?>" alt="img"></span>
                        </div>
                    </div>

                    <div class="product-availability">
                        <h6><?php echo e(__('website.cars.filters.availability')); ?></h6>
                        <div class="status-toggle">
                            <input id="mobile_notifications_drawer" class="check" type="checkbox" name="available_only"
                                value="1" <?php if($availableOnly): echo 'checked'; endif; ?>>
                            <label for="mobile_notifications_drawer" class="checktoggle">checkbox</label>
                        </div>
                    </div>

                    <div class="accord-list">
                        <div class="accordion" id="drawerAccordionBrand">
                            <div class="card-header-new" id="drawerHeadingBrand">
                                <h6 class="filter-title">
                                    <a href="javascript:void(0);" class="w-100" data-bs-toggle="collapse"
                                        data-bs-target="#drawerCollapseBrand" aria-expanded="true"
                                        aria-controls="drawerCollapseBrand">
                                        <?php echo e(__('website.cars.filters.brand')); ?>

                                        <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                                    </a>
                                </h6>
                            </div>
                            <div id="drawerCollapseBrand" class="collapse show" aria-labelledby="drawerHeadingBrand"
                                data-bs-parent="#drawerAccordionBrand">
                                <div class="card-body-chat">
                                    <div class="selectBox-cont">
                                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="custom_check w-100">
                                                <input type="checkbox" name="brand[]" value="<?php echo e($brand['id']); ?>"
                                                    <?php if(in_array((int) $brand['id'], $selectedBrandIds, true)): echo 'checked'; endif; ?>>
                                                <span class="checkmark"></span><?php echo e($brand['name']); ?>

                                                (<?php echo e($brand['cars_count'] ?? 0); ?>)
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion" id="drawerAccordionCategory">
                            <div class="card-header-new" id="drawerHeadingCategory">
                                <h6 class="filter-title">
                                    <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#drawerCollapseCategory" aria-expanded="true"
                                        aria-controls="drawerCollapseCategory">
                                        <?php echo e(__('website.cars.filters.category')); ?>

                                        <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                                    </a>
                                </h6>
                            </div>
                            <div id="drawerCollapseCategory" class="collapse" aria-labelledby="drawerHeadingCategory"
                                data-bs-parent="#drawerAccordionCategory">
                                <div class="card-body-chat">
                                    <div class="selectBox-cont">
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="custom_check w-100">
                                                <input type="checkbox" name="category[]" value="<?php echo e($category['id']); ?>"
                                                    <?php if(in_array((int) $category['id'], $selectedCategoryIds, true)): echo 'checked'; endif; ?>>
                                                <span class="checkmark"></span><?php echo e($category['name']); ?>

                                                (<?php echo e($category['cars_count'] ?? 0); ?>)
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion" id="drawerAccordionYear">
                            <div class="card-header-new" id="drawerHeadingYear">
                                <h6 class="filter-title">
                                    <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#drawerCollapseYear" aria-expanded="true"
                                        aria-controls="drawerCollapseYear">
                                        <?php echo e(__('website.cars.filters.year')); ?>

                                        <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                                    </a>
                                </h6>
                            </div>
                            <div id="drawerCollapseYear" class="collapse" aria-labelledby="drawerHeadingYear"
                                data-bs-parent="#drawerAccordionYear">
                                <div class="card-body-chat">
                                    <div class="selectBox-cont">
                                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="custom_check w-100">
                                                <input type="checkbox" name="year[]" value="<?php echo e($year['id']); ?>"
                                                    <?php if(in_array((int) $year['id'], $selectedYearIds, true)): echo 'checked'; endif; ?>>
                                                <span class="checkmark"></span><?php echo e($year['year']); ?>

                                                (<?php echo e($year['cars_count'] ?? 0); ?>)
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="sort" value="<?php echo e($sort); ?>">
                    <input type="hidden" name="per_page" value="<?php echo e($perPage); ?>">

                    <div class="cars-filter-actions">
                        <a href="<?php echo e($resetUrl); ?>" class="reset-filter"><?php echo e(__('website.cars.filters.reset')); ?></a>
                        <button type="submit"
                            class="d-inline-flex align-items-center justify-content-center btn btn-primary filter-btn">
                            <span><i class="feather-filter me-2"></i></span><?php echo e(__('website.cars.filters.apply')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Car Grid View -->
    <section class="section car-listing pt-0">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <?php $__empty_1 = true; $__currentLoopData = $cars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $carName = $car['name'] ?? __('website.common.car');
                                $carImage = $storageUrl($car['image_path'] ?? null, $assetUrl('img/cars/car-01.jpg'));
                                $carBrand = $car['brand_name'] ?? __('website.common.brand');
                                $carCategory = $car['category_name'] ?? __('website.common.category');
                                $carDailyPrice = $car['daily_price'] ?? null;
                                $carMonthlyPrice = $car['monthly_price'] ?? null;
                                $carCurrency = $car['currency_symbol'] ?? '$';
                                $carUrl = $car['details_url'] ?? route('website.cars.index');
                                $carCardTitle = $formatCarCardTitle($carName);
                            ?>

                            <div class="col-xxl-4 col-lg-6 col-md-6 col-12">
                                <div class="listing-item">
                                    <div class="listing-img">
                                        <a href="<?php echo e($carUrl); ?>">
                                            <img src="<?php echo e($carImage); ?>" class="img-fluid" alt="<?php echo e($carName); ?>">
                                        </a>
                                        <span class="featured-text d-inline-block"
                                            style="max-width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <?php echo e($carBrand); ?>

                                        </span>
                                    </div>
                                    <div class="listing-content">
                                        <div class="listing-features d-flex align-items-end justify-content-between">
                                            <div class="list-rating">
                                                <h3 class="listing-title mb-0">
                                                    <a href="<?php echo e($carUrl); ?>" title="<?php echo e($carName); ?>" class="car-card-title"><?php echo e($carCardTitle); ?></a>
                                                </h3>
                                            </div>
                                            <div class="list-km">
                                                <span class="km-count"><img src="<?php echo e($assetUrl('img/icons/map-pin.svg')); ?>"
                                                        alt="author"><?php echo e($car['year'] ?? '-'); ?></span>
                                            </div>
                                        </div>
                                        <div class="listing-details-group">
                                            <ul>
                                                <li>
                                                    <span><img src="<?php echo e($assetUrl('img/icons/car-parts-01.svg')); ?>"
                                                            alt="Auto"></span>
                                                    <p><?php echo e($car['gear_type_name'] ?? '-'); ?></p>
                                                </li>
                                                <li>
                                                    <span><img src="<?php echo e($assetUrl('img/icons/car-parts-02.svg')); ?>"
                                                            alt="KM"></span>
                                                    <p><?php echo e(isset($car['daily_mileage_included']) ? __('website.units.km_value', ['count' => $car['daily_mileage_included']]) : '-'); ?>

                                                    </p>
                                                </li>
                                                <li>
                                                    <span><img src="<?php echo e($assetUrl('img/icons/car-parts-03.svg')); ?>"
                                                            alt="Category"></span>
                                                    <p><?php echo e($carCategory); ?></p>
                                                </li>
                                            </ul>
                                            <ul>
                                                <li>
                                                    <span><img src="<?php echo e($assetUrl('img/icons/car-parts-04.svg')); ?>"
                                                            alt="Doors"></span>
                                                    <p><?php echo e(isset($car['door_count']) ? __('website.units.doors', ['count' => $car['door_count']]) : '-'); ?>

                                                    </p>
                                                </li>
                                                <li>
                                                    <span><img src="<?php echo e($assetUrl('img/icons/car-parts-05.svg')); ?>"
                                                            alt="Year"></span>
                                                    <p><?php echo e($car['year'] ?? '-'); ?></p>
                                                </li>
                                                <li>
                                                    <span><img src="<?php echo e($assetUrl('img/icons/car-parts-06.svg')); ?>"
                                                            alt="Persons"></span>
                                                    <p><?php echo e(isset($car['passenger_capacity']) ? __('website.units.persons', ['count' => $car['passenger_capacity']]) : '-'); ?>

                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="listing-price-section"
                                            style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 8px; padding: 12px; margin-bottom: 15px;">
                                            <div class="d-flex justify-content-between align-items-center gap-2">
                                                <div class="price-box text-center flex-fill"
                                                    style="background: #fff; border-radius: 6px; padding: 10px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                                    <div
                                                        style="font-size: 11px; color: #6c757d; font-weight: 500; text-transform: uppercase; margin-bottom: 4px;">
                                                        <i class="feather-sun" style="font-size: 12px;"></i>
                                                        <?php echo e(__('website.units.per_day')); ?>

                                                    </div>
                                                    <?php if($carDailyPrice): ?>
                                                        <div style="font-size: 16px; font-weight: 700; color: #f66962;">
                                                            <?php echo e($formatCurrency($carDailyPrice, $carCurrency)); ?>

                                                        </div>
                                                    <?php else: ?>
                                                        <div style="font-size: 13px; color: #6c757d;">
                                                            <?php echo e(__('website.common.call_for_price')); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="price-box text-center flex-fill"
                                                    style="background: #fff; border-radius: 6px; padding: 10px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                                    <div
                                                        style="font-size: 11px; color: #6c757d; font-weight: 500; text-transform: uppercase; margin-bottom: 4px;">
                                                        <i class="feather-calendar" style="font-size: 12px;"></i>
                                                        <?php echo e(__('website.units.per_month')); ?>

                                                    </div>
                                                    <?php if($carMonthlyPrice): ?>
                                                        <div style="font-size: 16px; font-weight: 700; color: #127384;">
                                                            <?php echo e($formatCurrency($carMonthlyPrice, $carCurrency)); ?>

                                                        </div>
                                                    <?php else: ?>
                                                        <div style="font-size: 13px; color: #6c757d;">-</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="listing-button listing-action-group">
                                            <a href="<?php echo e($whatsAppHref); ?>"
                                                class="btn listing-action-btn whatsapp-btn <?php if($whatsAppHref === 'javascript:void(0);'): ?> disabled <?php endif; ?>"
                                                <?php if($whatsAppHref !== 'javascript:void(0);'): ?>
                                                    target="_blank" rel="noopener noreferrer"
                                                <?php endif; ?>
                                                aria-disabled="<?php echo e($whatsAppHref === 'javascript:void(0);' ? 'true' : 'false'); ?>">
                                                <i class="fa-brands fa-whatsapp"></i>
                                                <span class="action-label"><?php echo e(__('website.car_details.owner_details.chat_whatsapp')); ?></span>
                                            </a>
                                            <a href="<?php echo e($phoneHref); ?>"
                                                class="btn listing-action-btn call-btn <?php if($phoneHref === 'javascript:void(0);'): ?> disabled <?php endif; ?>"
                                                aria-disabled="<?php echo e($phoneHref === 'javascript:void(0);' ? 'true' : 'false'); ?>">
                                                <i class="fa-solid fa-phone"></i>
                                                <span class="action-label"><?php echo e(__('website.car_details.sidebar.call_us')); ?></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="feature-text">
                                        <span class="bg-danger"><?php echo e(__('website.units.no_deposit')); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <h4><?php echo e(__('website.cars.empty_title')); ?></h4>
                                    <p class="mb-0"><?php echo e(__('website.cars.empty_subtitle')); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if($cars->hasPages()): ?>
                        <div class="blog-pagination">
                            <nav>
                                <ul class="pagination page-item justify-content-center">
                                    <li class="previtem <?php echo e($cars->onFirstPage() ? 'disabled' : ''); ?>">
                                        <?php if($cars->onFirstPage()): ?>
                                            <span class="page-link"><i class="fas fa-regular fa-arrow-left me-2"></i>
                                                <?php echo e(__('website.blog.navigation.previous')); ?></span>
                                        <?php else: ?>
                                            <a class="page-link" href="<?php echo e($cars->previousPageUrl()); ?>"><i
                                                    class="fas fa-regular fa-arrow-left me-2"></i>
                                                <?php echo e(__('website.blog.navigation.previous')); ?></a>
                                        <?php endif; ?>
                                    </li>

                                    <li class="justify-content-center pagination-center">
                                        <div class="page-group">
                                            <ul>
                                                <?php if($startPage > 1): ?>
                                                    <li class="page-item"><a class="page-link" href="<?php echo e($cars->url(1)); ?>">1</a></li>
                                                <?php endif; ?>

                                                <?php for($page = $startPage; $page <= $endPage; $page++): ?>
                                                    <li class="page-item">
                                                        <a class="<?php echo e($page === $currentPage ? 'active ' : ''); ?>page-link"
                                                            href="<?php echo e($cars->url($page)); ?>"><?php echo e($page); ?></a>
                                                    </li>
                                                <?php endfor; ?>

                                                <?php if($endPage < $lastPage): ?>
                                                    <li class="page-item"><a class="page-link"
                                                            href="<?php echo e($cars->url($lastPage)); ?>"><?php echo e($lastPage); ?></a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </li>

                                    <li class="nextlink <?php echo e($cars->hasMorePages() ? '' : 'disabled'); ?>">
                                        <?php if($cars->hasMorePages()): ?>
                                            <a class="page-link"
                                                href="<?php echo e($cars->nextPageUrl()); ?>"><?php echo e(__('website.blog.navigation.next')); ?> <i
                                                    class="fas fa-regular fa-arrow-right ms-2"></i></a>
                                        <?php else: ?>
                                            <span class="page-link"><?php echo e(__('website.blog.navigation.next')); ?> <i
                                                    class="fas fa-regular fa-arrow-right ms-2"></i></span>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>

                    <?php if($showListingContent): ?>
                        <div class="listing-seo-content">
                            <?php if(!$showSeoContentInBreadcrumb && $listingContentTitle !== ''): ?>
                                <h2><?php echo e($listingContentTitle); ?></h2>
                            <?php endif; ?>

                            <?php if(!$showSeoContentInBreadcrumb && $listingContentDescription !== ''): ?>
                                <div class="listing-seo-description"><?php echo $listingContentDescription; ?></div>
                            <?php endif; ?>

                            <?php if($listingContentArticle !== ''): ?>
                                <div class="listing-seo-article"><?php echo $listingContentArticle; ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <!-- /Car Grid View -->
@endsection
