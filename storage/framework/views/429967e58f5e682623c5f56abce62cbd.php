<?php $__env->startSection('title', __('website.nav.all_cars')); ?>

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

        $selectedBrandIds = collect($filters['selected_brand_ids'] ?? [])->map(fn ($id) => (int) $id)->all();
        $selectedCategoryIds = collect($filters['selected_category_ids'] ?? [])->map(fn ($id) => (int) $id)->all();
        $selectedYearIds = collect($filters['selected_year_ids'] ?? [])->map(fn ($id) => (int) $id)->all();

        $search = $filters['search'] ?? '';
        $availableOnly = (bool) ($filters['available_only'] ?? false);
        $sort = $filters['sort'] ?? 'newest';
        $perPage = (int) ($filters['per_page'] ?? 9);

        $perPageOptions = [6, 9, 12, 18, 24];
        $sortOptions = [
            'newest' => __('website.cars.sort.newest'),
            'price_low' => __('website.cars.sort.price_low'),
            'price_high' => __('website.cars.sort.price_high'),
            'year_new' => __('website.cars.sort.year_new'),
            'year_old' => __('website.cars.sort.year_old'),
        ];
    ?>

    <div class="breadcrumb-bar">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title"><?php echo e(__('website.cars.page_title')); ?></h2>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('website.nav.home')); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('website.nav.all_cars')); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="sort-section">
        <div class="container">
            <div class="sortby-sec">
                <div class="sorting-div">
                    <div class="row d-flex align-items-center">
                        <div class="col-xl-4 col-lg-3 col-sm-12 col-12">
                            <div class="count-search">
                                <p>
                                    <?php echo e(__('website.cars.showing_results', [
                                            'from' => $cars->firstItem() ?? 0,
                                            'to' => $cars->lastItem() ?? 0,
                                            'total' => $cars->total(),
                                        ])); ?>

                                </p>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-9 col-sm-12 col-12">
                            <div class="product-filter-group">
                                <div class="sortbyset">
                                    <ul>
                                        <li>
                                            <span class="sortbytitle"><?php echo e(__('website.cars.sort.show')); ?> : </span>
                                            <div class="sorting-select select-one">
                                                <select class="form-control select" name="per_page" form="cars-filter-form" onchange="document.getElementById('cars-filter-form').submit();">
                                                    <?php $__currentLoopData = $perPageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($option); ?>" <?php echo e($perPage === $option ? 'selected' : ''); ?>><?php echo e($option); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </li>
                                        <li>
                                            <span class="sortbytitle"><?php echo e(__('website.cars.sort.sort_by')); ?> </span>
                                            <div class="sorting-select select-two">
                                                <select class="form-control select" name="sort" form="cars-filter-form" onchange="document.getElementById('cars-filter-form').submit();">
                                                    <?php $__currentLoopData = $sortOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sortKey => $sortLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($sortKey); ?>" <?php echo e($sort === $sortKey ? 'selected' : ''); ?>><?php echo e($sortLabel); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="grid-listview">
                                    <ul>
                                        <li>
                                            <a href="javascript:void(0);" class="active">
                                                <i class="feather-grid"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section car-listing pt-0">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-sm-12 col-12 theiaStickySidebar">
                    <form id="cars-filter-form" action="<?php echo e(route('website.cars.index')); ?>" method="GET" autocomplete="off" class="sidebar-form">
                        <div class="sidebar-heading">
                            <h3><?php echo e(__('website.cars.filters.title')); ?></h3>
                        </div>

                        <div class="product-search">
                            <div class="form-custom">
                                <input type="text" class="form-control" name="search" value="<?php echo e($search); ?>" placeholder="<?php echo e(__('website.cars.filters.search_placeholder')); ?>">
                                <span><img src="<?php echo e($assetUrl('img/icons/search.svg')); ?>" alt="search"></span>
                            </div>
                        </div>

                        <div class="product-availability">
                            <h6><?php echo e(__('website.cars.filters.availability')); ?></h6>
                            <div class="status-toggle">
                                <input id="available_only" class="check" type="checkbox" name="available_only" value="1" <?php echo e($availableOnly ? 'checked' : ''); ?>>
                                <label for="available_only" class="checktoggle">checkbox</label>
                            </div>
                        </div>

                        <div class="accord-list">
                            <?php if(($filters['brands'] ?? collect())->isNotEmpty()): ?>
                                <div class="accordion" id="accordionBrand">
                                    <div class="card-header-new" id="headingBrand">
                                        <h6 class="filter-title">
                                            <a href="javascript:void(0);" class="w-100" data-bs-toggle="collapse" data-bs-target="#collapseBrand" aria-expanded="true" aria-controls="collapseBrand">
                                                <?php echo e(__('website.cars.filters.brand')); ?>

                                                <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapseBrand" class="collapse show" aria-labelledby="headingBrand">
                                        <div class="card-body-chat">
                                            <div class="selectBox-cont">
                                                <?php $__currentLoopData = $filters['brands']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <label class="custom_check w-100">
                                                        <input type="checkbox" name="brand[]" value="<?php echo e($brand['id']); ?>" <?php echo e(in_array((int) $brand['id'], $selectedBrandIds, true) ? 'checked' : ''); ?>>
                                                        <span class="checkmark"></span>
                                                        <?php echo e($brand['name']); ?> (<?php echo e($brand['cars_count']); ?>)
                                                    </label>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(($filters['categories'] ?? collect())->isNotEmpty()): ?>
                                <div class="accordion" id="accordionCategory">
                                    <div class="card-header-new" id="headingCategory">
                                        <h6 class="filter-title">
                                            <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse" data-bs-target="#collapseCategory" aria-expanded="false" aria-controls="collapseCategory">
                                                <?php echo e(__('website.cars.filters.category')); ?>

                                                <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapseCategory" class="collapse" aria-labelledby="headingCategory">
                                        <div class="card-body-chat">
                                            <div class="selectBox-cont">
                                                <?php $__currentLoopData = $filters['categories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <label class="custom_check w-100">
                                                        <input type="checkbox" name="category[]" value="<?php echo e($category['id']); ?>" <?php echo e(in_array((int) $category['id'], $selectedCategoryIds, true) ? 'checked' : ''); ?>>
                                                        <span class="checkmark"></span>
                                                        <?php echo e($category['name']); ?> (<?php echo e($category['cars_count']); ?>)
                                                    </label>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(($filters['years'] ?? collect())->isNotEmpty()): ?>
                                <div class="accordion" id="accordionYear">
                                    <div class="card-header-new" id="headingYear">
                                        <h6 class="filter-title">
                                            <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse" data-bs-target="#collapseYear" aria-expanded="false" aria-controls="collapseYear">
                                                <?php echo e(__('website.cars.filters.year')); ?>

                                                <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapseYear" class="collapse" aria-labelledby="headingYear">
                                        <div class="card-body-chat">
                                            <div class="selectBox-cont">
                                                <?php $__currentLoopData = $filters['years']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <label class="custom_check w-100">
                                                        <input type="checkbox" name="year[]" value="<?php echo e($year['id']); ?>" <?php echo e(in_array((int) $year['id'], $selectedYearIds, true) ? 'checked' : ''); ?>>
                                                        <span class="checkmark"></span>
                                                        <?php echo e($year['year']); ?> (<?php echo e($year['cars_count']); ?>)
                                                    </label>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="d-inline-flex align-items-center justify-content-center btn w-100 btn-primary filter-btn">
                            <span><i class="feather-filter me-2"></i></span><?php echo e(__('website.cars.filters.apply')); ?>

                        </button>
                        <a href="<?php echo e(route('website.cars.index')); ?>" class="reset-filter"><?php echo e(__('website.cars.filters.reset')); ?></a>
                    </form>
                </div>

                <div class="col-lg-9">
                    <?php if($cars->count() > 0): ?>
                        <div class="row">
                            <?php $__currentLoopData = $cars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $carName = $car['name'] ?? __('website.common.car');
                                    $carUrl = $car['details_url'] ?? 'javascript:void(0);';
                                    $carImage = $storageUrl($car['image_path'] ?? null, $assetUrl('img/cars/car-01.jpg'));
                                    $carBrand = $car['brand_name'] ?? null;
                                    $carCategory = $car['category_name'] ?? null;
                                    $carStatusRaw = (string) ($car['status'] ?? 'available');
                                    $carStatusLabel = __('website.status.' . $carStatusRaw);

                                    if ($carStatusLabel === 'website.status.' . $carStatusRaw) {
                                        $carStatusLabel = ucfirst(str_replace('_', ' ', $carStatusRaw));
                                    }

                                    $carPrice = $car['daily_price'] ?? null;
                                    $carMainPrice = $car['daily_main_price'] ?? null;
                                    $carCurrency = $car['currency_symbol'] ?? '$';
                                    $isFeatured = (bool) ($car['is_featured'] ?? false);

                                    $specItems = collect([
                                        ['icon' => 'img/icons/car-parts-01.svg', 'value' => $car['gear_type_name'] ?? null],
                                        ['icon' => 'img/icons/car-parts-02.svg', 'value' => isset($car['daily_mileage_included']) ? __('website.units.km_value', ['count' => $car['daily_mileage_included']]) : null],
                                        ['icon' => 'img/icons/car-parts-03.svg', 'value' => isset($car['door_count']) ? __('website.units.doors', ['count' => $car['door_count']]) : null],
                                        ['icon' => 'img/icons/car-parts-05.svg', 'value' => $car['year'] ?? null],
                                        ['icon' => 'img/icons/car-parts-06.svg', 'value' => isset($car['passenger_capacity']) ? __('website.units.persons', ['count' => $car['passenger_capacity']]) : null],
                                        ['icon' => 'img/icons/car-parts-04.svg', 'value' => $carCategory],
                                    ])->filter(fn (array $item) => filled($item['value']))->values();

                                    $specChunks = $specItems->chunk(3)->values();
                                    $primarySpecs = $specChunks->get(0, collect());
                                    $secondarySpecs = $specChunks->get(1, collect());
                                ?>

                                <div class="col-xxl-4 col-lg-6 col-md-6 col-12">
                                    <div class="listing-item">
                                        <div class="listing-img">
                                            <a href="<?php echo e($carUrl); ?>">
                                                <img src="<?php echo e($carImage); ?>" class="img-fluid" alt="<?php echo e($carName); ?>">
                                            </a>
                                            <div class="fav-item justify-content-end">
                                                <a href="javascript:void(0)" class="fav-icon">
                                                    <i class="feather-heart"></i>
                                                </a>
                                            </div>
                                            <?php if(filled($carBrand)): ?>
                                                <span class="featured-text"><?php echo e($carBrand); ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="listing-content">
                                            <div class="listing-features d-flex align-items-end justify-content-between">
                                                <div class="list-rating">
                                                    <h3 class="listing-title">
                                                        <a href="<?php echo e($carUrl); ?>"><?php echo e(Str::limit($carName, 34)); ?></a>
                                                    </h3>
                                                    <?php if(filled($carBrand)): ?>
                                                        <span class="d-block text-muted"><?php echo e($carBrand); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="list-km">
                                                    <span class="km-count"><?php echo e($carStatusLabel); ?></span>
                                                </div>
                                            </div>

                                            <?php if($primarySpecs->isNotEmpty() || $secondarySpecs->isNotEmpty()): ?>
                                                <div class="listing-details-group">
                                                    <?php if($primarySpecs->isNotEmpty()): ?>
                                                        <ul>
                                                            <?php $__currentLoopData = $primarySpecs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <li>
                                                                    <span><img src="<?php echo e($assetUrl($spec['icon'])); ?>" alt="spec"></span>
                                                                    <p><?php echo e($spec['value']); ?></p>
                                                                </li>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </ul>
                                                    <?php endif; ?>

                                                    <?php if($secondarySpecs->isNotEmpty()): ?>
                                                        <ul>
                                                            <?php $__currentLoopData = $secondarySpecs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <li>
                                                                    <span><img src="<?php echo e($assetUrl($spec['icon'])); ?>" alt="spec"></span>
                                                                    <p><?php echo e($spec['value']); ?></p>
                                                                </li>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="listing-location-details">
                                                <?php if(filled($carCategory)): ?>
                                                    <div class="listing-price">
                                                        <span><i class="feather-map-pin"></i></span><?php echo e($carCategory); ?>

                                                    </div>
                                                <?php endif; ?>
                                                <div class="listing-price">
                                                    <?php if($carPrice): ?>
                                                        <h6>
                                                            <?php echo e($carCurrency); ?><?php echo e(number_format((int) $carPrice)); ?> <span><?php echo e(__('website.units.per_day')); ?></span>
                                                            <?php if($carMainPrice && $carMainPrice > $carPrice): ?>
                                                                <small class="text-muted text-decoration-line-through d-block">
                                                                    <?php echo e($carCurrency); ?><?php echo e(number_format((int) $carMainPrice)); ?>

                                                                </small>
                                                            <?php endif; ?>
                                                        </h6>
                                                    <?php else: ?>
                                                        <h6><?php echo e(__('website.common.call_for_price')); ?></h6>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="listing-button">
                                                <a href="<?php echo e($carUrl); ?>" class="btn btn-order">
                                                    <span><i class="feather-calendar me-2"></i></span><?php echo e(__('website.common.rent_now')); ?>

                                                </a>
                                            </div>
                                        </div>

                                        <?php if($isFeatured): ?>
                                            <div class="feature-text">
                                                <span class="bg-danger"><?php echo e(__('website.common.featured')); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="blog-pagination">
                            <?php echo e($cars->onEachSide(1)->links('pagination::bootstrap-5')); ?>

                        </div>
                    <?php else: ?>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <h5 class="mb-2"><?php echo e(__('website.cars.empty_title')); ?></h5>
                                <p class="text-muted mb-0"><?php echo e(__('website.cars.empty_subtitle')); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.website', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views/website/cars.blade.php ENDPATH**/ ?>