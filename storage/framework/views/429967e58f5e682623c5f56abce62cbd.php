<?php $__env->startSection('title', __('website.cars.page_title')); ?>

<?php $__env->startSection('content'); ?>
    <?php
        use Illuminate\Support\Str;

        $assetUrl = static fn (string $path): string => asset('website/assets/' . ltrim($path, '/'));

        $storageUrl = static function (?string $path, ?string $fallback = null): ?string {
            if (blank($path)) {
                return $fallback;
            }

            if (Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            return asset('storage/' . ltrim($path, '/'));
        };

        $brands = collect($filters['brands'] ?? []);
        $categories = collect($filters['categories'] ?? []);
        $years = collect($filters['years'] ?? []);

        $selectedBrandIds = collect($filters['selected_brand_ids'] ?? [])->map(fn ($id) => (int) $id)->all();
        $selectedCategoryIds = collect($filters['selected_category_ids'] ?? [])->map(fn ($id) => (int) $id)->all();
        $selectedYearIds = collect($filters['selected_year_ids'] ?? [])->map(fn ($id) => (int) $id)->all();

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
    ?>

    <!-- Breadscrumb Section -->
    <div class="breadcrumb-bar">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title"><?php echo e(__('website.cars.page_title')); ?></h2>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('website.nav.home')); ?></a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo e(__('website.nav.all_cars')); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('website.cars.page_title')); ?></li>
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
                                <p><?php echo e(__('website.cars.showing_results', ['from' => $from, 'to' => $to, 'total' => $total])); ?></p>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-9 col-sm-12 col-12">
                            <form action="<?php echo e(route('website.cars.index')); ?>" method="GET" class="product-filter-group">
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

                                <div class="sortbyset">
                                    <ul>
                                        <li>
                                            <span class="sortbytitle"><?php echo e(__('website.cars.sort.show')); ?> : </span>
                                            <div class="sorting-select select-one">
                                                <select class="form-control select" name="per_page" onchange="this.form.submit()">
                                                    <?php $__currentLoopData = [6, 9, 12, 18, 24]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($size); ?>" <?php if($perPage === $size): echo 'selected'; endif; ?>><?php echo e($size); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </li>
                                        <li>
                                            <span class="sortbytitle"><?php echo e(__('website.cars.sort.sort_by')); ?></span>
                                            <div class="sorting-select select-two">
                                                <select class="form-control select" name="sort" onchange="this.form.submit()">
                                                    <option value="newest" <?php if($sort === 'newest'): echo 'selected'; endif; ?>><?php echo e(__('website.cars.sort.newest')); ?></option>
                                                    <option value="price_low" <?php if($sort === 'price_low'): echo 'selected'; endif; ?>><?php echo e(__('website.cars.sort.price_low')); ?></option>
                                                    <option value="price_high" <?php if($sort === 'price_high'): echo 'selected'; endif; ?>><?php echo e(__('website.cars.sort.price_high')); ?></option>
                                                    <option value="year_new" <?php if($sort === 'year_new'): echo 'selected'; endif; ?>><?php echo e(__('website.cars.sort.year_new')); ?></option>
                                                    <option value="year_old" <?php if($sort === 'year_old'): echo 'selected'; endif; ?>><?php echo e(__('website.cars.sort.year_old')); ?></option>
                                                </select>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Sort By -->

    <!-- Car Grid View -->
    <section class="section car-listing pt-0">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-sm-12 col-12 theiaStickySidebar">
                    <form action="<?php echo e(route('website.cars.index')); ?>" method="GET" autocomplete="off" class="sidebar-form">
                        <div class="sidebar-heading">
                            <h3><?php echo e(__('website.cars.filters.title')); ?></h3>
                        </div>

                        <div class="product-search">
                            <div class="form-custom">
                                <input type="text" class="form-control" name="search" value="<?php echo e($search); ?>" placeholder="<?php echo e(__('website.cars.filters.search_placeholder')); ?>">
                                <span><img src="<?php echo e($assetUrl('img/icons/search.svg')); ?>" alt="img"></span>
                            </div>
                        </div>

                        <div class="product-availability">
                            <h6><?php echo e(__('website.cars.filters.availability')); ?></h6>
                            <div class="status-toggle">
                                <input id="mobile_notifications" class="check" type="checkbox" name="available_only" value="1" <?php if($availableOnly): echo 'checked'; endif; ?>>
                                <label for="mobile_notifications" class="checktoggle">checkbox</label>
                            </div>
                        </div>

                        <div class="accord-list">
                            <div class="accordion" id="accordionMain1">
                                <div class="card-header-new" id="headingOne">
                                    <h6 class="filter-title">
                                        <a href="javascript:void(0);" class="w-100" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <?php echo e(__('website.cars.filters.brand')); ?>

                                            <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </h6>
                                </div>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionMain1">
                                    <div class="card-body-chat">
                                        <div class="selectBox-cont">
                                            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="custom_check w-100">
                                                    <input type="checkbox" name="brand[]" value="<?php echo e($brand['id']); ?>" <?php if(in_array((int) $brand['id'], $selectedBrandIds, true)): echo 'checked'; endif; ?>>
                                                    <span class="checkmark"></span><?php echo e($brand['name']); ?> (<?php echo e($brand['cars_count'] ?? 0); ?>)
                                                </label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion" id="accordionMain2">
                                <div class="card-header-new" id="headingTwo">
                                    <h6 class="filter-title">
                                        <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                            <?php echo e(__('website.cars.filters.category')); ?>

                                            <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </h6>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionMain2">
                                    <div class="card-body-chat">
                                        <div class="selectBox-cont">
                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="custom_check w-100">
                                                    <input type="checkbox" name="category[]" value="<?php echo e($category['id']); ?>" <?php if(in_array((int) $category['id'], $selectedCategoryIds, true)): echo 'checked'; endif; ?>>
                                                    <span class="checkmark"></span><?php echo e($category['name']); ?> (<?php echo e($category['cars_count'] ?? 0); ?>)
                                                </label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion" id="accordionMain3">
                                <div class="card-header-new" id="headingYear">
                                    <h6 class="filter-title">
                                        <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse" data-bs-target="#collapseYear" aria-expanded="true" aria-controls="collapseYear">
                                            <?php echo e(__('website.cars.filters.year')); ?>

                                            <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </h6>
                                </div>
                                <div id="collapseYear" class="collapse" aria-labelledby="headingYear" data-bs-parent="#accordionMain3">
                                    <div class="card-body-chat">
                                        <div class="selectBox-cont">
                                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="custom_check w-100">
                                                    <input type="checkbox" name="year[]" value="<?php echo e($year['id']); ?>" <?php if(in_array((int) $year['id'], $selectedYearIds, true)): echo 'checked'; endif; ?>>
                                                    <span class="checkmark"></span><?php echo e($year['year']); ?> (<?php echo e($year['cars_count'] ?? 0); ?>)
                                                </label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="sort" value="<?php echo e($sort); ?>">
                        <input type="hidden" name="per_page" value="<?php echo e($perPage); ?>">

                        <button type="submit" class="d-inline-flex align-items-center justify-content-center btn w-100 btn-primary filter-btn">
                            <span><i class="feather-filter me-2"></i></span><?php echo e(__('website.cars.filters.apply')); ?>

                        </button>
                        <a href="<?php echo e(route('website.cars.index')); ?>" class="reset-filter"><?php echo e(__('website.cars.filters.reset')); ?></a>
                    </form>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        <?php $__empty_1 = true; $__currentLoopData = $cars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $carName = $car['name'] ?? __('website.common.car');
                                $carImage = $storageUrl($car['image_path'] ?? null, $assetUrl('img/cars/car-01.jpg'));
                                $carBrand = $car['brand_name'] ?? __('website.common.brand');
                                $carCategory = $car['category_name'] ?? __('website.common.category');
                                $carStatusRaw = (string) ($car['status'] ?? 'available');
                                $carStatus = __('website.status.' . $carStatusRaw);
                                if ($carStatus === 'website.status.' . $carStatusRaw) {
                                    $carStatus = ucfirst(str_replace('_', ' ', $carStatusRaw));
                                }
                                $carPrice = $car['daily_price'] ?? null;
                                $carMainPrice = $car['daily_main_price'] ?? null;
                                $carCurrency = $car['currency_symbol'] ?? '$';
                                $carUrl = $car['details_url'] ?? 'javascript:void(0);';
                            ?>

                            <div class="col-xxl-4 col-lg-6 col-md-6 col-12">
                                <div class="listing-item">
                                    <div class="listing-img">
                                        <a href="<?php echo e($carUrl); ?>">
                                            <img src="<?php echo e($carImage); ?>" class="img-fluid" alt="<?php echo e($carName); ?>">
                                        </a>
                                        <span class="featured-text"><?php echo e($carBrand); ?></span>
                                    </div>
                                    <div class="listing-content">
                                        <div class="listing-features d-flex align-items-end justify-content-between">
                                            <div class="list-rating">
                                                <h3 class="listing-title">
                                                    <a href="<?php echo e($carUrl); ?>"><?php echo e(Str::limit($carName, 36)); ?></a>
                                                </h3>
                                                <div class="list-rating">
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star"></i>
                                                    <span><?php echo e($carStatus); ?></span>
                                                </div>
                                            </div>
                                            <div class="list-km">
                                                <span class="km-count"><img src="<?php echo e($assetUrl('img/icons/map-pin.svg')); ?>" alt="author"><?php echo e($car['year'] ?? '-'); ?></span>
                                            </div>
                                        </div>
                                        <div class="listing-details-group">
                                            <ul>
                                                <li>
                                                    <span><img src="<?php echo e($assetUrl('img/icons/car-parts-01.svg')); ?>" alt="Auto"></span>
                                                    <p><?php echo e($car['gear_type_name'] ?? '-'); ?></p>
                                                </li>
                                                <li>
                                                    <span><img src="<?php echo e($assetUrl('img/icons/car-parts-02.svg')); ?>" alt="KM"></span>
                                                    <p><?php echo e(isset($car['daily_mileage_included']) ? __('website.units.km_value', ['count' => $car['daily_mileage_included']]) : '-'); ?></p>
                                                </li>
                                                <li>
                                                    <span><img src="<?php echo e($assetUrl('img/icons/car-parts-03.svg')); ?>" alt="Category"></span>
                                                    <p><?php echo e($carCategory); ?></p>
                                                </li>
                                            </ul>
                                            <ul>
                                                <li>
                                                    <span><img src="<?php echo e($assetUrl('img/icons/car-parts-04.svg')); ?>" alt="Doors"></span>
                                                    <p><?php echo e(isset($car['door_count']) ? __('website.units.doors', ['count' => $car['door_count']]) : '-'); ?></p>
                                                </li>
                                                <li>
                                                    <span><img src="<?php echo e($assetUrl('img/icons/car-parts-05.svg')); ?>" alt="Year"></span>
                                                    <p><?php echo e($car['year'] ?? '-'); ?></p>
                                                </li>
                                                <li>
                                                    <span><img src="<?php echo e($assetUrl('img/icons/car-parts-06.svg')); ?>" alt="Persons"></span>
                                                    <p><?php echo e(isset($car['passenger_capacity']) ? __('website.units.persons', ['count' => $car['passenger_capacity']]) : '-'); ?></p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="listing-location-details">
                                            <div class="listing-price">
                                                <span><i class="feather-map-pin"></i></span><?php echo e($carCategory); ?>

                                            </div>
                                            <div class="listing-price">
                                                <?php if($carPrice): ?>
                                                    <h6><?php echo e($formatCurrency($carPrice, $carCurrency)); ?> <span><?php echo e(__('website.units.per_day')); ?></span></h6>
                                                    <?php if($carMainPrice && $carMainPrice > $carPrice): ?>
                                                        <small class="text-muted text-decoration-line-through"><?php echo e($formatCurrency($carMainPrice, $carCurrency)); ?></small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <h6><?php echo e(__('website.common.call_for_price')); ?></h6>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="listing-button">
                                            <a href="<?php echo e($carUrl); ?>" class="btn btn-order"><span><i class="feather-calendar me-2"></i></span><?php echo e(__('website.common.rent_now')); ?></a>
                                        </div>
                                    </div>
                                    <?php if(!empty($car['is_featured'])): ?>
                                        <div class="feature-text">
                                            <span class="bg-danger"><?php echo e(__('website.common.featured')); ?></span>
                                        </div>
                                    <?php endif; ?>
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
                                            <span class="page-link"><i class="fas fa-regular fa-arrow-left me-2"></i> <?php echo e(__('website.blog.navigation.previous')); ?></span>
                                        <?php else: ?>
                                            <a class="page-link" href="<?php echo e($cars->previousPageUrl()); ?>"><i class="fas fa-regular fa-arrow-left me-2"></i> <?php echo e(__('website.blog.navigation.previous')); ?></a>
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
                                                        <a class="<?php echo e($page === $currentPage ? 'active ' : ''); ?>page-link" href="<?php echo e($cars->url($page)); ?>"><?php echo e($page); ?></a>
                                                    </li>
                                                <?php endfor; ?>

                                                <?php if($endPage < $lastPage): ?>
                                                    <li class="page-item"><a class="page-link" href="<?php echo e($cars->url($lastPage)); ?>"><?php echo e($lastPage); ?></a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </li>

                                    <li class="nextlink <?php echo e($cars->hasMorePages() ? '' : 'disabled'); ?>">
                                        <?php if($cars->hasMorePages()): ?>
                                            <a class="page-link" href="<?php echo e($cars->nextPageUrl()); ?>"><?php echo e(__('website.blog.navigation.next')); ?> <i class="fas fa-regular fa-arrow-right ms-2"></i></a>
                                        <?php else: ?>
                                            <span class="page-link"><?php echo e(__('website.blog.navigation.next')); ?> <i class="fas fa-regular fa-arrow-right ms-2"></i></span>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <!-- /Car Grid View -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.website', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views/website/cars.blade.php ENDPATH**/ ?>