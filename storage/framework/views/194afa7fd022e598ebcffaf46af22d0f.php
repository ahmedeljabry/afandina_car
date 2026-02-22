<?php $__env->startSection('title', $homeTranslation?->meta_title ?? __('website.nav.home')); ?>

<?php $__env->startSection('content'); ?>
<?php
    use Illuminate\Support\Str;

    $formatCurrency = static function ($price, ?string $currencyLabel): string {
        $label = trim((string) $currencyLabel);

        return ($label !== '' ? $label . ' ' : '') . number_format((float) $price);
    };
?>

    
    <section class="banner-section-four">
        <div class="container">
            <div class="home-banner">
                <div class="row align-items-center">
                    <div class="col-lg-5" data-aos="fade-down">
                        <div class="banner-content">
                            <h1><?php echo e(__('website.home.hero.title_prefix')); ?> <span><?php echo e(__('website.home.hero.title_highlight')); ?></span> <?php echo e(__('website.home.hero.title_suffix')); ?></h1>
                            <p><?php echo e(__('website.home.hero.banner_paragraph')); ?>

                            </p>
                            <div class="customer-list">
                                <div class="users-wrap">
                                    <ul class="users-list">
                                        <li>
                                            <img src="<?php echo e(asset('website/assets/img/profiles/avatar-11.jpg')); ?>" class="img-fluid aos" alt="bannerimage">
                                        </li>
                                        <li>
                                            <img src="<?php echo e(asset('website/assets/img/profiles/avatar-15.jpg')); ?>" class="img-fluid aos" alt="bannerimage">
                                        </li>
                                        <li>
                                            <img src="<?php echo e(asset('website/assets/img/profiles/avatar-03.jpg')); ?>" class="img-fluid aos" alt="bannerimage">
                                        </li>
                                    </ul>
                                    <div class="customer-info">
                                        <h4><?php echo e(__('website.home.hero.customers_label')); ?></h4>
                                        <p><?php echo e(__('website.home.hero.customers_subtitle')); ?></p>
                                    </div>
                                </div>
                                <div class="view-all d-flex align-items-center gap-3">
                                    <a href="<?php echo e(route('website.cars.index')); ?>" class="btn btn-primary d-inline-flex align-items-center"><?php echo e(__('website.home.hero.browse_cars')); ?><i class="bx bx-right-arrow-alt ms-1"></i></a>
                                    <a href="<?php echo e(route('website.blogs.index')); ?>" class="btn btn-secondary d-inline-flex align-items-center"><i class="bx bxs-plus-circle me-1"></i><?php echo e(__('website.home.hero.browse_blogs')); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="banner-image">
                            <div class="banner-img" data-aos="fade-down">
                                <div class="amount-icon">
                                    <span class="day-amt">
                                        <p><?php echo e(__('website.home.hero.starting_from')); ?></p>
                                        <h6><?php echo e($formatCurrency($minPrice ?? 650, $currencySymbol)); ?> <span><?php echo e(__('website.home.hero.per_day')); ?></span></h6>
                                    </span>
                                </div>
                                <span class="rent-tag"><i class="bx bxs-circle"></i> <?php echo e(__('website.home.hero.available_for_rent')); ?></span>
                                <img src="<?php echo e(asset('website/assets/img/banner/banner.png')); ?>" class="img-fluid" alt="img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="banner-bgs">
            <img src="<?php echo e(asset('website/assets/img/bg/banner-bg-01.png')); ?>" class="bg-01 img-fluid" alt="img">
        </div>
    </section>
    

    
    <section class="category-section-four">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading heading-four" data-aos="fade-down">
                        <h2><?php echo e($homeTranslation?->category_section_title ?? __('website.home.sections.categories_title')); ?></h2>
                        <p><?php echo e($homeTranslation?->category_section_paragraph ?? __('website.home.sections.categories_paragraph')); ?></p>
                    </div>

                    <div class="row row-gap-4">
                        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col-xl-2 col-md-4 col-sm-6 d-flex">
                                <div class="category-item flex-fill">
                                    <div class="category-info d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="title">
                                                <a href="<?php echo e($category['url']); ?>"><?php echo e($category['name']); ?></a>
                                            </h6>
                                            <p><?php echo e(__('website.home.labels.cars_count', ['count' => $category['cars_count']])); ?></p>
                                        </div>
                                        <a href="<?php echo e($category['url']); ?>" class="link-icon">
                                            <i class="bx bx-right-arrow-alt"></i>
                                        </a>
                                    </div>
                                    <?php if($category['image_path']): ?>
                                        <div class="category-img">
                                            <img src="<?php echo e(asset('storage/' . $category['image_path'])); ?>"
                                                alt="<?php echo e($category['name']); ?>" class="img-fluid">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-12 text-center text-muted py-4">
                                <?php echo e(__('website.home.empty.categories')); ?>

                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="view-all-btn text-center aos" data-aos="fade-down">
                        <a href="<?php echo e(route('website.cars.index')); ?>" class="btn btn-secondary">
                            <?php echo e(__('website.home.actions.view_all_cars')); ?>

                            <i class="bx bx-right-arrow-alt ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    

    
    <section class="feature-section pt-0">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">

                    <div class="feature-img">
                        <div class="section-heading heading-four text-start" data-aos="fade-down">
                            <h2><?php echo e(__('website.home.features.section_title')); ?></h2>
                            <p><?php echo e(__('website.home.features.section_paragraph')); ?></p>
                        </div>
                        <img src="<?php echo e(asset('website/assets/img/cars/car.png')); ?>" alt="img" class="img-fluid">
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="row row-gap-4">

                        <!-- Feature Item -->
                        <div class="col-md-6 d-flex">
                            <div class="feature-item flex-fill">
                                <span class="feature-icon">
                                    <i class="bx bxs-info-circle"></i>
                                </span>
                                <div>
                                    <h6 class="mb-1"><?php echo e(__('website.home.features.best_deal.title')); ?></h6>
                                    <p><?php echo e(__('website.home.features.best_deal.description')); ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /Feature Item -->

                        <!-- Feature Item -->
                        <div class="col-md-6 d-flex">
                            <div class="feature-item flex-fill">
                                <span class="feature-icon">
                                    <i class="bx bx-exclude"></i>
                                </span>
                                <div>
                                    <h6 class="mb-1"><?php echo e(__('website.home.features.doorstep_delivery.title')); ?></h6>
                                    <p><?php echo e(__('website.home.features.doorstep_delivery.description')); ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /Feature Item -->

                        <!-- Feature Item -->
                        <div class="col-md-6 d-flex">
                            <div class="feature-item flex-fill">
                                <span class="feature-icon">
                                    <i class="bx bx-money"></i>
                                </span>
                                <div>
                                    <h6 class="mb-1"><?php echo e(__('website.home.features.low_security_deposit.title')); ?></h6>
                                    <p><?php echo e(__('website.home.features.low_security_deposit.description')); ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /Feature Item -->

                        <!-- Feature Item -->
                        <div class="col-md-6 d-flex">
                            <div class="feature-item flex-fill">

                                <span class="feature-icon">
                                    <i class="bx bxs-car-mechanic"></i>
                                </span>
                                <div>
                                    <h6 class="mb-1"><?php echo e(__('website.home.features.latest_cars.title')); ?></h6>
                                    <p><?php echo e(__('website.home.features.latest_cars.description')); ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /Feature Item -->

                        <!-- Feature Item -->
                        <div class="col-md-6 d-flex">
                            <div class="feature-item flex-fill">
                                <span class="feature-icon">
                                    <i class="bx bx-support"></i>
                                </span>
                                <div>
                                    <h6 class="mb-1"><?php echo e(__('website.home.features.customer_support.title')); ?></h6>
                                    <p><?php echo e(__('website.home.features.customer_support.description')); ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /Feature Item -->

                        <!-- Feature Item -->
                        <div class="col-md-6 d-flex">
                            <div class="feature-item flex-fill">
                                <span class="feature-icon">
                                    <i class="bx bxs-coin"></i>
                                </span>
                                <div>
                                    <h6 class="mb-1"><?php echo e(__('website.home.features.no_hidden_charges.title')); ?></h6>
                                    <p><?php echo e(__('website.home.features.no_hidden_charges.description')); ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /Feature Item -->

                    </div>
                </div>
            </div>
        </div>
    </section>
	

    
    <?php if($featuredCars->isNotEmpty()): ?>
    <section class="car-section">
        <div class="container">
            <div class="section-heading heading-four" data-aos="fade-down">
                <h2><?php echo e($homeTranslation?->featured_cars_section_title ?? __('website.home.sections.featured_cars_title')); ?></h2>
                <p><?php echo e($homeTranslation?->featured_cars_section_paragraph ?? __('website.home.sections.featured_cars_paragraph')); ?></p>
            </div>

            <div class="row">
                <?php $__currentLoopData = $featuredCars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="listing-item listing-item-two">
                            <div class="listing-img">
                                <?php
                                    $allImages = array_values(array_filter(array_merge(
                                        $car['image_path'] ? [$car['image_path']] : [],
                                        $car['images'] ?? []
                                    )));
                                ?>

                                <?php if(count($allImages) > 1): ?>
                                    <div class="img-slider owl-carousel">
                                        <?php $__currentLoopData = array_slice($allImages, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="slide-images">
                                                <a href="<?php echo e($car['details_url']); ?>">
                                                    <img src="<?php echo e(asset('storage/' . $img)); ?>"
                                                        class="img-fluid" alt="<?php echo e($car['name']); ?>">
                                                </a>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <a href="<?php echo e($car['details_url']); ?>">
                                        <img src="<?php echo e($car['image_path'] ? asset('storage/' . $car['image_path']) : asset('website/assets/img/cars/car-11.jpg')); ?>"
                                            class="img-fluid" alt="<?php echo e($car['name']); ?>">
                                    </a>
                                <?php endif; ?>

                                <div class="fav-item">
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if($car['brand_name']): ?>
                                            <span class="featured-text"><?php echo e($car['brand_name']); ?></span>
                                        <?php endif; ?>
                                        <?php if($car['status'] === 'available'): ?>
                                            <span class="availability"><?php echo e(__('website.status.available')); ?></span>
                                        <?php else: ?>
                                            <span class="availability bg-secondary"><?php echo e(__('website.status.not_available')); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="listing-content">
                                <div class="listing-features d-flex align-items-center justify-content-between">
                                    <div class="list-rating">
                                        <h3 class="listing-title">
                                            <a href="<?php echo e($car['details_url']); ?>"><?php echo e($car['name']); ?></a>
                                        </h3>
                                    </div>
                                    <?php if($car['daily_price']): ?>
                                        <div>
                                            <h4 class="price">
                                                <?php echo e($formatCurrency($car['daily_price'], $car['currency_symbol'] ?? null)); ?>

                                                <span><?php echo e(__('website.units.per_day')); ?></span>
                                            </h4>
                                        </div>
                                    <?php else: ?>
                                        <div>
                                            <h4 class="price text-muted"><?php echo e(__('website.common.call_for_price')); ?></h4>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="listing-details-group">
                                    <ul>
                                        <?php if($car['gear_type']): ?>
                                            <li>
                                                <img src="<?php echo e(asset('website/assets/img/icons/car-parts-01.svg')); ?>" alt="gear">
                                                <p><?php echo e($car['gear_type']); ?></p>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($car['passenger_capacity']): ?>
                                            <li>
                                                <img src="<?php echo e(asset('website/assets/img/icons/car-parts-05.svg')); ?>" alt="passengers">
                                                <p><?php echo e(__('website.units.persons', ['count' => $car['passenger_capacity']])); ?></p>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($car['year']): ?>
                                            <li>
                                                <img src="<?php echo e(asset('website/assets/img/icons/car-parts-05.svg')); ?>" alt="year">
                                                <p><?php echo e($car['year']); ?></p>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="view-all-btn text-center aos" data-aos="fade-down">
                <a href="<?php echo e(route('website.cars.index')); ?>"
                    class="btn btn-secondary d-inline-flex align-items-center">
                    <?php echo e(__('website.home.actions.view_all_cars')); ?>

                    <i class="bx bx-right-arrow-alt ms-1"></i>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>
    

    
    <?php if($brands->isNotEmpty()): ?>
    <section class="brand-section">
        <div class="container">
            <div class="section-heading heading-four" data-aos="fade-down">
                <h2 class="text-white"><?php echo e($homeTranslation?->brand_section_title ?? __('website.home.sections.brands_title')); ?></h2>
                <p><?php echo e($homeTranslation?->brand_section_paragraph ?? __('website.home.sections.brands_paragraph')); ?></p>
            </div>
            <div class="brands-slider owl-carousel">
                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="brand-wrap">
                        <?php if($brand['logo_path']): ?>
                            <img src="<?php echo e(asset('storage/' . $brand['logo_path'])); ?>" alt="<?php echo e($brand['name']); ?>">
                        <?php endif; ?>
                        <p><?php echo e($brand['name']); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="brand-img text-center">
                <img src="<?php echo e(asset('website/assets/img/bg/brand.png')); ?>" alt="img" class="img-fluid">
            </div>
        </div>
    </section>
    <?php endif; ?>
    

    
    <section class="rental-section-four">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="rental-img">
                        <img src="<?php echo e(asset('website/assets/img/about/rent-car.png')); ?>" alt="img" class="img-fluid">
                        <div class="grid-img">
                            <img src="<?php echo e(asset('website/assets/img/about/car-grid.png')); ?>" alt="img" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="rental-content">
                        <div class="section-heading heading-four text-start" data-aos="fade-down">
                            <h2><?php echo e(__('website.home.rental.title')); ?></h2>
                            <p><?php echo e(__('website.home.rental.paragraph')); ?></p>
                        </div>
                        <div class="step-item d-flex align-items-center">
                            <span class="step-icon bg-primary me-3">
                                <i class="bx bx-calendar-heart"></i>
                            </span>
                            <div>
                                <h5><?php echo e(__('website.home.rental.step1_title')); ?></h5>
                                <p><?php echo e(__('website.home.rental.step1_description')); ?></p>
                            </div>
                        </div>
                        <div class="step-item d-flex align-items-center">
                            <span class="step-icon bg-secondary-100 me-3">
                                <i class="bx bxs-edit-location"></i>
                            </span>
                            <div>
                                <h5><?php echo e(__('website.home.rental.step2_title')); ?></h5>
                                <p><?php echo e(__('website.home.rental.step2_description')); ?></p>
                            </div>
                        </div>
                        <div class="step-item d-flex align-items-center">
                            <span class="step-icon bg-dark me-3">
                                <i class="bx bx-coffee-togo"></i>
                            </span>
                            <div>
                                <h5><?php echo e(__('website.home.rental.step3_title')); ?></h5>
                                <p><?php echo e(__('website.home.rental.step3_description')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="count-sec">
                <div class="row row-gap-4" >
                    <div class="col-lg-3 col-md-6 d-flex">
                        <div class="count-item flex-fill">
                            <h3><span class="counterUp">16</span>K+</h3>
                            <p><?php echo e(__('website.home.stats.happy_customers')); ?></p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 d-flex">
                        <div class="count-item flex-fill">
                            <h3><span class="counterUp">2547</span>K+</h3>
                            <p><?php echo e(__('website.home.stats.count_of_cars')); ?></p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 d-flex">
                        <div class="count-item flex-fill">
                            <h3><span class="counterUp">625</span>K+</h3>
                            <p><?php echo e(__('website.home.stats.locations_to_pickup')); ?></p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 d-flex">
                        <div class="count-item flex-fill">
                            <h3><span class="counterUp">15000</span>K+</h3>
                            <p><?php echo e(__('website.home.stats.total_kilometers')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    

    
    <?php if($popularCars->isNotEmpty()): ?>
    <section class="popular-section-four">
        <div class="container">
            <div class="section-heading heading-four" data-aos="fade-down">
                <h2><?php echo e($homeTranslation?->car_only_section_title ?? __('website.home.sections.only_on_title')); ?></h2>
                <p><?php echo e($homeTranslation?->car_only_section_paragraph ?? __('website.home.sections.only_on_paragraph')); ?></p>
            </div>
            <div class="car-slider owl-carousel">
                <?php $__currentLoopData = $popularCars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="car-item">
                        <?php if($car['brand_name']): ?>
                            <h6><?php echo e(Str::upper($car['brand_name'])); ?></h6>
                        <?php endif; ?>
                        <h2 class="display-1"><?php echo e(Str::upper($car['name'])); ?></h2>
                        <div class="car-img">
                            <img src="<?php echo e($car['image_path'] ? asset('storage/' . $car['image_path']) : asset('website/assets/img/cars/car-15.png')); ?>"
                                alt="<?php echo e($car['name']); ?>" class="img-fluid">
                            <?php if($car['daily_price']): ?>
                                <div class="amount-icon">
                                    <span class="day-amt">
                                        <p><?php echo e(__('website.home.hero.starting_from')); ?></p>
                                        <h6>
                                            <?php echo e($formatCurrency($car['daily_price'], $car['currency_symbol'] ?? null)); ?>

                                            <span><?php echo e(__('website.home.hero.per_day')); ?></span>
                                        </h6>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="spec-list">
                            <?php if($car['gear_type']): ?>
                                <span>
                                    <img src="<?php echo e(asset('website/assets/img/icons/spec-01.svg')); ?>" alt="gear">
                                    <?php echo e($car['gear_type']); ?>

                                </span>
                            <?php endif; ?>
                            <?php if($car['passenger_capacity']): ?>
                                <span>
                                    <img src="<?php echo e(asset('website/assets/img/icons/spec-05.svg')); ?>" alt="persons">
                                    <?php echo e(__('website.units.persons', ['count' => $car['passenger_capacity']])); ?>

                                </span>
                            <?php endif; ?>
                            <?php if($car['year']): ?>
                                <span>
                                    <img src="<?php echo e(asset('website/assets/img/icons/spec-03.svg')); ?>" alt="year">
                                    <?php echo e($car['year']); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo e($car['details_url']); ?>" class="btn btn-primary">
                            <?php echo e(__('website.common.rent_now')); ?>

                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    

    <!-- Testimonial Section -->
    <section class="testimonial-section">
        <div class="container">
            <div class="section-heading heading-four" data-aos="fade-down">
                <h2><?php echo e(__('website.home.testimonials.title')); ?></h2>
                <p><?php echo e(__('website.home.testimonials.paragraph')); ?></p>
            </div>

            <div class="row row-gap-4 justify-content-center">

                <!-- Testimonial Item -->
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="testimonial-item testimonial-item-two flex-fill">
                        <div class="user-img">
                            <img src="<?php echo e(asset('website/assets/img/profiles/avatar-02.jpg')); ?>" class="img-fluid" alt="img">
                        </div>
                        <p><?php echo e(__('website.home.testimonials.review1')); ?></p>
                        <div class="rating">
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                        </div>
                        <div class="user-info">
                            <h6><?php echo e(__('website.home.testimonials.client_1_name')); ?></h6>
                            <p><?php echo e(__('website.home.testimonials.client_1_location')); ?></p>
                        </div>
                    </div>
                </div>
                <!-- /Testimonial Item -->

                <!-- Testimonial Item -->
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="testimonial-item testimonial-item-two flex-fill">
                        <div class="user-img">
                            <img src="<?php echo e(asset('website/assets/img/profiles/avatar-18.jpg')); ?>" class="img-fluid" alt="img">
                        </div>
                        <p><?php echo e(__('website.home.testimonials.review2')); ?></p>
                        <div class="rating">
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                        </div>
                        <div class="user-info">
                            <h6><?php echo e(__('website.home.testimonials.client_2_name')); ?></h6>
                            <p><?php echo e(__('website.home.testimonials.client_2_location')); ?></p>
                        </div>
                    </div>
                </div>
                <!-- /Testimonial Item -->

                <!-- Testimonial Item -->
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="testimonial-item testimonial-item-two flex-fill">
                        <div class="user-img">
                            <img src="<?php echo e(asset('website/assets/img/profiles/avatar-15.jpg')); ?>" class="img-fluid" alt="img">
                        </div>
                        <p><?php echo e(__('website.home.testimonials.review3')); ?></p>
                        <div class="rating">
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                        </div>
                        <div class="user-info">
                            <h6><?php echo e(__('website.home.testimonials.client_3_name')); ?></h6>
                            <p><?php echo e(__('website.home.testimonials.client_3_location')); ?></p>
                        </div>
                    </div>
                </div>
                <!-- /Testimonial Item -->

            </div>

            <div class="view-all-btn text-center aos" data-aos="fade-down">
                <a href="<?php echo e(route('website.cars.index')); ?>" class="btn btn-secondary"><?php echo e(__('website.home.actions.view_all')); ?><i class="bx bx-right-arrow-alt ms-1"></i></a>
            </div>

            <div class="client-slider owl-carousel">
                <div>
                    <img src="<?php echo e(asset('website/assets/img/clients/client-01.svg')); ?>" alt="img">
                </div>
                <div>
                    <img src="<?php echo e(asset('website/assets/img/clients/client-02.svg')); ?>" alt="img">
                </div>
                <div>
                    <img src="<?php echo e(asset('website/assets/img/clients/client-03.svg')); ?>" alt="img">
                </div>
                <div>
                    <img src="<?php echo e(asset('website/assets/img/clients/client-04.svg')); ?>" alt="img">
                </div>
                <div>
                    <img src="<?php echo e(asset('website/assets/img/clients/client-05.svg')); ?>" alt="img">
                </div>
                <div>
                    <img src="<?php echo e(asset('website/assets/img/clients/client-06.svg')); ?>" alt="img">
                </div>
            </div>
        </div>
    </section>
	<!-- /Testimonial Section -->

    <!-- Support Section -->
    <section class="support-section">
        <div class="horizontal-slide d-flex" data-direction="left" data-speed="slow">
            <div class="slide-list d-flex">
                <div class="support-item">
                    <h2><?php echo e(__('website.home.support.best_rate')); ?></h2>
                </div>
                <div class="support-item">
                    <h2><?php echo e(__('website.home.support.free_cancellation')); ?></h2>
                </div>
                <div class="support-item">
                    <h2><?php echo e(__('website.home.support.best_security')); ?></h2>
                </div>
                <div class="support-item">
                    <h2><?php echo e(__('website.home.support.latest_update')); ?></h2>
                </div>
                <div class="support-item">
                    <h2><?php echo e(__('website.home.support.trusted_proof')); ?></h2>
                </div>
            </div>
        </div>
    </section>
    <!-- /Support Section -->

    
    <section class="blog-section-four">
        <div class="container">
            <?php if($blogs->isNotEmpty()): ?>
                <div class="section-heading heading-four" data-aos="fade-down">
                    <h2><?php echo e($homeTranslation?->blog_section_title ?? __('website.home.sections.blogs_title')); ?></h2>
                    <p><?php echo e($homeTranslation?->blog_section_paragraph ?? __('website.home.sections.blogs_paragraph')); ?></p>
                </div>

                <div class="row row-gap-3 justify-content-center">
                    <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <!-- Blog Item -->
                        <div class="col-lg-4 col-md-6 d-flex">
                            <div class="blog-item flex-fill">
                                <?php if($blog['image_path']): ?>
                                    <div class="blog-img">
                                        <a href="<?php echo e($blog['url']); ?>">
                                            <img src="<?php echo e(asset('storage/' . $blog['image_path'])); ?>"
                                                class="img-fluid" alt="<?php echo e($blog['title']); ?>">
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <div class="blog-content">
                                    <div class="d-flex align-center justify-content-between blog-category">
                                        <?php if($blog['category_name'] ?? null): ?>
                                            <a href="javascript:void(0);" class="category"><?php echo e($blog['category_name']); ?></a>
                                        <?php endif; ?>
                                        <?php if($blog['published_on']): ?>
                                            <p class="date d-inline-flex align-center">
                                                <i class="bx bx-calendar me-1"></i><?php echo e($blog['published_on']); ?>

                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <h5 class="title">
                                        <a href="<?php echo e($blog['url']); ?>"><?php echo e($blog['title']); ?></a>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <!-- /Blog Item -->
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="view-all-btn text-center aos" data-aos="fade-down">
                    <a href="<?php echo e(route('website.blogs.index')); ?>"
                        class="btn btn-secondary d-inline-flex align-center">
                        <?php echo e(__('website.home.actions.view_all_blogs')); ?>

                        <i class="bx bx-right-arrow-alt ms-1"></i>
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </section>
    

    
    <?php if($faqs->isNotEmpty()): ?>
    <section class="faq-section-four pt-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="section-heading heading-four" data-aos="fade-down">
                        <h2><?php echo e($homeTranslation?->faq_section_title ?? __('website.home.sections.faq_title')); ?></h2>
                        <p><?php echo e($homeTranslation?->faq_section_paragraph ?? __('website.home.sections.faq_paragraph')); ?></p>
                    </div>
                    <div class="accordion faq-accordion" id="faqAccordion">
                        <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $faqId = 'faqItem' . $faq['id']; ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button <?php echo e($index > 0 ? 'collapsed' : ''); ?>"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#<?php echo e($faqId); ?>"
                                        aria-expanded="<?php echo e($index === 0 ? 'true' : 'false'); ?>"
                                        aria-controls="<?php echo e($faqId); ?>">
                                        <?php echo e($faq['question']); ?>

                                    </button>
                                </h2>
                                <div id="<?php echo e($faqId); ?>"
                                    class="accordion-collapse collapse <?php echo e($index === 0 ? 'show' : ''); ?>"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p><?php echo e($faq['answer']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    

    
    <?php if($allCategories->isNotEmpty() || $allBrands->isNotEmpty()): ?>
    <section class="categories-section">
        <div class="container">
            <div class="accordion custom-accordion" id="catalogAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button"
                            data-bs-toggle="collapse" data-bs-target="#catalogPanel"
                            aria-expanded="true" aria-controls="catalogPanel">
                            <?php echo e(__('website.home.catalog.title')); ?>

                        </button>
                    </h2>
                    <div id="catalogPanel" class="accordion-collapse collapse show"
                        data-bs-parent="#catalogAccordion">
                        <div class="accordion-body">
                            <div class="row row-gap-3">
                                <?php
                                    $catalogItems = $allCategories->merge($allBrands);
                                    $chunks = $catalogItems->chunk(6);
                                ?>
                                <?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <ul class="category-list">
                                            <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li>
                                                    <a href="<?php echo e($item['url']); ?>"><?php echo e($item['name']); ?></a>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.website', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\website\home.blade.php ENDPATH**/ ?>