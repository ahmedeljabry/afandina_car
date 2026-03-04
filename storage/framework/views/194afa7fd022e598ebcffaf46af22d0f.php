<?php $__env->startSection('title', $homeTranslation?->meta_title ?? __('website.nav.home')); ?>

<?php $__env->startPush('css'); ?>
    <style>
        .home-category-section {
            position: relative;
        }

        .home-category-section .home-category-grid {
            row-gap: 1rem;
        }

        .home-category-section .home-category-col {
            width: 100%;
        }

        @media (min-width: 1200px) {
            .home-category-section .home-category-col {
                flex: 0 0 20%;
                max-width: 20%;
            }
        }

        @media (min-width: 992px) and (max-width: 1199.98px) {
            .home-category-section .home-category-col {
                flex: 0 0 25%;
                max-width: 25%;
            }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            .home-category-section .home-category-col {
                flex: 0 0 33.3333%;
                max-width: 33.3333%;
            }
        }

        @media (max-width: 767.98px) {
            .home-category-section .home-category-col {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        .home-category-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(17, 17, 17, 0.1);
            border-radius: 14px;
            background: linear-gradient(180deg, #ffffff 0%, #fbfcfd 100%);
            box-shadow: 0 8px 18px rgba(17, 17, 17, 0.08);
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .home-category-card::before {
            content: "";
            position: absolute;
            inset-inline-end: -36px;
            inset-block-start: -36px;
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 166, 51, 0.2) 0%, rgba(255, 166, 51, 0) 70%);
            pointer-events: none;
        }

        .home-category-card:hover {
            transform: translateY(-4px);
            border-color: #ffa633;
            box-shadow: 0 12px 24px rgba(18, 115, 132, 0.16);
            background: linear-gradient(180deg, #ffffff 0%, #f4fbfc 100%);
        }

        .home-category-card .category-info {
            position: relative;
            z-index: 1;
            padding: 0;
        }

        .home-category-card .title {
            font-size: 15px;
            line-height: 1.35;
            margin-bottom: 8px;
        }

        .home-category-card .title a {
            color: #111111;
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .home-category-card .home-category-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .home-category-card .home-category-count {
            margin: 0;
            font-size: 11px;
            font-weight: 600;
            line-height: 1;
            color: #111111;
            background: rgba(255, 166, 51, 0.2);
            border: 1px solid rgba(255, 166, 51, 0.3);
            border-radius: 999px;
            padding: 6px 10px;
        }

        .home-category-card .link-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #127384;
            color: #ffffff;
            font-size: 17px;
            flex-shrink: 0;
        }

        .home-category-card:hover .title a {
            color: #111111;
        }

        .home-category-card:hover .link-icon {
            background: #ffa633;
            color: #ffffff;
        }

        .home-category-card .category-img {
            margin-top: auto;
            min-height: 82px;
            text-align: center;
            position: relative;
            z-index: 1;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(18, 115, 132, 0.08) 0%, rgba(255, 166, 51, 0.12) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }

        .home-category-card .category-img img {
            width: auto;
            max-width: 100%;
            max-height: 66px;
            object-fit: contain;
            transition: transform 0.25s ease;
        }

        .home-category-card:hover .category-img img {
            transform: translateY(-3px) scale(1.02);
        }

        .home-category-card .home-category-fallback {
            width: 100%;
            min-height: 66px;
            border-radius: 10px;
            background: linear-gradient(135deg, #127384 0%, #111111 100%);
            color: #ffffff;
            font-size: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .car-section .listing-item.listing-item-two .listing-content .listing-features {
            gap: 10px;
        }

        .car-section .listing-item.listing-item-two .listing-content .listing-features .list-rating {
            min-width: 0;
            flex: 1 1 auto;
        }

        .home-car-card-title {
            margin-bottom: 0;
            min-width: 0;
            display: block;
            width: 100%;
        }

        .car-section .listing-item.listing-item-two .listing-content .listing-features .listing-title.home-car-card-title a {
            display: block !important;
            width: 100%;
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .home-card-badges {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .home-no-deposit-label {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
            color: #dc2626;
            white-space: nowrap;
            animation: home-no-deposit-blink 2s ease-in-out infinite;
        }

        @keyframes home-no-deposit-blink {
            0%,
            15% {
                opacity: 0;
            }

            45%,
            65% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .home-no-deposit-label {
                animation: none;
                opacity: 1;
            }
        }

        @media (max-width: 991.98px) {
            .home-category-card {
                padding: 12px;
            }
        }

        @media (max-width: 575.98px) {
            .home-category-section .home-category-col {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    use Illuminate\Support\Str;

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

    $noDepositLabel = Str::title(str_replace('_', ' ', __('no_deposit')));

    $testimonialItems = [
        [
            'image' => asset('website/assets/img/profiles/avatar-02.jpg'),
            'review' => $homeTranslation?->testimonial_review_1 ?: __('website.home.testimonials.review1'),
            'name' => $homeTranslation?->testimonial_client_1_name ?: __('website.home.testimonials.client_1_name'),
            'location' => $homeTranslation?->testimonial_client_1_location ?: __('website.home.testimonials.client_1_location'),
        ],
        [
            'image' => asset('website/assets/img/profiles/avatar-18.jpg'),
            'review' => $homeTranslation?->testimonial_review_2 ?: __('website.home.testimonials.review2'),
            'name' => $homeTranslation?->testimonial_client_2_name ?: __('website.home.testimonials.client_2_name'),
            'location' => $homeTranslation?->testimonial_client_2_location ?: __('website.home.testimonials.client_2_location'),
        ],
        [
            'image' => asset('website/assets/img/profiles/avatar-15.jpg'),
            'review' => $homeTranslation?->testimonial_review_3 ?: __('website.home.testimonials.review3'),
            'name' => $homeTranslation?->testimonial_client_3_name ?: __('website.home.testimonials.client_3_name'),
            'location' => $homeTranslation?->testimonial_client_3_location ?: __('website.home.testimonials.client_3_location'),
        ],
    ];

    $heroCopy = [
        'title_prefix' => $homeTranslation?->hero_title_prefix ?: __('website.home.hero.title_prefix'),
        'title_highlight' => $homeTranslation?->hero_title_highlight ?: __('website.home.hero.title_highlight'),
        'title_suffix' => $homeTranslation?->hero_title_suffix ?: __('website.home.hero.title_suffix'),
        'banner_paragraph' => $homeTranslation?->hero_banner_paragraph ?: __('website.home.hero.banner_paragraph'),
        'customers_label' => $homeTranslation?->hero_customers_label ?: __('website.home.hero.customers_label'),
        'customers_subtitle' => $homeTranslation?->hero_customers_subtitle ?: __('website.home.hero.customers_subtitle'),
        'browse_cars_label' => $homeTranslation?->hero_browse_cars_label ?: __('website.home.hero.browse_cars'),
        'browse_blogs_label' => $homeTranslation?->hero_browse_blogs_label ?: __('website.home.hero.browse_blogs'),
        'starting_from_label' => $homeTranslation?->hero_starting_from_label ?: __('website.home.hero.starting_from'),
        'per_day_label' => $homeTranslation?->hero_per_day_label ?: __('website.home.hero.per_day'),
        'available_for_rent_label' => $homeTranslation?->hero_available_for_rent_label ?: __('website.home.hero.available_for_rent'),
    ];

    $featureItems = [
        [
            'icon' => 'bx bxs-info-circle',
            'title' => $homeTranslation?->feature_item_1_title ?: __('website.home.features.best_deal.title'),
            'description' => $homeTranslation?->feature_item_1_description ?: __('website.home.features.best_deal.description'),
        ],
        [
            'icon' => 'bx bx-exclude',
            'title' => $homeTranslation?->feature_item_2_title ?: __('website.home.features.doorstep_delivery.title'),
            'description' => $homeTranslation?->feature_item_2_description ?: __('website.home.features.doorstep_delivery.description'),
        ],
        [
            'icon' => 'bx bx-money',
            'title' => $homeTranslation?->feature_item_3_title ?: __('website.home.features.low_security_deposit.title'),
            'description' => $homeTranslation?->feature_item_3_description ?: __('website.home.features.low_security_deposit.description'),
        ],
        [
            'icon' => 'bx bxs-car-mechanic',
            'title' => $homeTranslation?->feature_item_4_title ?: __('website.home.features.latest_cars.title'),
            'description' => $homeTranslation?->feature_item_4_description ?: __('website.home.features.latest_cars.description'),
        ],
        [
            'icon' => 'bx bx-support',
            'title' => $homeTranslation?->feature_item_5_title ?: __('website.home.features.customer_support.title'),
            'description' => $homeTranslation?->feature_item_5_description ?: __('website.home.features.customer_support.description'),
        ],
        [
            'icon' => 'bx bxs-coin',
            'title' => $homeTranslation?->feature_item_6_title ?: __('website.home.features.no_hidden_charges.title'),
            'description' => $homeTranslation?->feature_item_6_description ?: __('website.home.features.no_hidden_charges.description'),
        ],
    ];

    $rentalSteps = [
        [
            'icon_class' => 'bx bx-calendar-heart',
            'icon_bg_class' => 'bg-primary',
            'title' => $homeTranslation?->rental_step_1_title ?: __('website.home.rental.step1_title'),
            'description' => $homeTranslation?->rental_step_1_description ?: __('website.home.rental.step1_description'),
        ],
        [
            'icon_class' => 'bx bxs-edit-location',
            'icon_bg_class' => 'bg-secondary-100',
            'title' => $homeTranslation?->rental_step_2_title ?: __('website.home.rental.step2_title'),
            'description' => $homeTranslation?->rental_step_2_description ?: __('website.home.rental.step2_description'),
        ],
        [
            'icon_class' => 'bx bx-coffee-togo',
            'icon_bg_class' => 'bg-dark',
            'title' => $homeTranslation?->rental_step_3_title ?: __('website.home.rental.step3_title'),
            'description' => $homeTranslation?->rental_step_3_description ?: __('website.home.rental.step3_description'),
        ],
    ];

    $rentalStats = [
        [
            'value' => $homeTranslation?->rental_stat_1_value ?: '16',
            'suffix' => $homeTranslation?->rental_stat_1_suffix ?: 'K+',
            'label' => $homeTranslation?->rental_stat_1_label ?: __('website.home.stats.happy_customers'),
        ],
        [
            'value' => $homeTranslation?->rental_stat_2_value ?: '2547',
            'suffix' => $homeTranslation?->rental_stat_2_suffix ?: 'K+',
            'label' => $homeTranslation?->rental_stat_2_label ?: __('website.home.stats.count_of_cars'),
        ],
        [
            'value' => $homeTranslation?->rental_stat_3_value ?: '625',
            'suffix' => $homeTranslation?->rental_stat_3_suffix ?: 'K+',
            'label' => $homeTranslation?->rental_stat_3_label ?: __('website.home.stats.locations_to_pickup'),
        ],
        [
            'value' => $homeTranslation?->rental_stat_4_value ?: '15000',
            'suffix' => $homeTranslation?->rental_stat_4_suffix ?: 'K+',
            'label' => $homeTranslation?->rental_stat_4_label ?: __('website.home.stats.total_kilometers'),
        ],
    ];

    $supportItems = [
        $homeTranslation?->support_item_1_text ?: __('website.home.support.best_rate'),
        $homeTranslation?->support_item_2_text ?: __('website.home.support.free_cancellation'),
        $homeTranslation?->support_item_3_text ?: __('website.home.support.best_security'),
        $homeTranslation?->support_item_4_text ?: __('website.home.support.latest_update'),
        $homeTranslation?->support_item_5_text ?: __('website.home.support.trusted_proof'),
    ];

    $heroMediaType = $home?->hero_type === 'video' ? 'video' : 'image';
    $heroVideoPath = $home?->hero_header_video_path ? asset('storage/' . $home->hero_header_video_path) : null;
    $heroImagePath = $home?->hero_header_image_path ? asset('storage/' . $home->hero_header_image_path) : asset('website/assets/img/banner/banner.png');
?>

    
    <section class="banner-section-four">
        <div class="container">
            <div class="home-banner">
                <div class="row align-items-center">
                    <div class="col-lg-5" data-aos="fade-down">
                        <div class="banner-content">
                            <h1><?php echo e($heroCopy['title_prefix']); ?> <span><?php echo e($heroCopy['title_highlight']); ?></span> <?php echo e($heroCopy['title_suffix']); ?></h1>
                            <p><?php echo e($heroCopy['banner_paragraph']); ?>

                            </p>
                            <div class="customer-list">
                                <div class="users-wrap">
                                    <ul class="users-list">
                                        <li>
                                            <img src="<?php echo e(asset('admin/dist/img/user1-128x128.jpg')); ?>" class="img-fluid aos" alt="customer image">
                                        </li>
                                        <li>
                                            <img src="<?php echo e(asset('admin/dist/img/user2-160x160.jpg')); ?>" class="img-fluid aos" alt="customer image">
                                        </li>
                                        <li>
                                            <img src="<?php echo e(asset('admin/dist/img/user8-128x128.jpg')); ?>" class="img-fluid aos" alt="customer image">
                                        </li>
                                    </ul>
                                    <div class="customer-info">
                                        <h4><?php echo e($heroCopy['customers_label']); ?></h4>
                                        <p><?php echo e($heroCopy['customers_subtitle']); ?></p>
                                    </div>
                                </div>
                                <div class="view-all d-flex align-items-center gap-3">
                                    <a href="<?php echo e(route('website.cars.index')); ?>" class="btn btn-primary d-inline-flex align-items-center"><?php echo e($heroCopy['browse_cars_label']); ?><i class="bx bx-right-arrow-alt ms-1"></i></a>
                                    <a href="<?php echo e(route('website.blogs.index')); ?>" class="btn btn-secondary d-inline-flex align-items-center"><i class="bx bxs-plus-circle me-1"></i><?php echo e($heroCopy['browse_blogs_label']); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="banner-image">
                            <div class="banner-img" data-aos="fade-down">
                                <div class="amount-icon">
                                    <span class="day-amt">
                                        <p><?php echo e($heroCopy['starting_from_label']); ?></p>
                                        <h6><?php echo e($formatCurrency($minPrice ?? 650, $currencySymbol)); ?> <span><?php echo e($heroCopy['per_day_label']); ?></span></h6>
                                    </span>
                                </div>
                                <span class="rent-tag"><i class="bx bxs-circle"></i> <?php echo e($heroCopy['available_for_rent_label']); ?></span>
                                <?php if($heroMediaType === 'video' && $heroVideoPath): ?>
                                    <video class="img-fluid" autoplay muted loop playsinline>
                                        <source src="<?php echo e($heroVideoPath); ?>">
                                    </video>
                                <?php else: ?>
                                    <img src="<?php echo e($heroImagePath); ?>" class="img-fluid" alt="img">
                                <?php endif; ?>
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
    

    
    <section class="category-section-four home-category-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading heading-four" data-aos="fade-down">
                        <h2><?php echo e($homeTranslation?->category_section_title ?? __('website.home.sections.categories_title')); ?></h2>
                        <p><?php echo e($homeTranslation?->category_section_paragraph ?? __('website.home.sections.categories_paragraph')); ?></p>
                    </div>

                    <div class="row home-category-grid">
                        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="home-category-col d-flex">
                                <div class="category-item home-category-card flex-fill">
                                    <div class="category-info">
                                        <h6 class="title">
                                            <a href="<?php echo e($category['url']); ?>" title="<?php echo e($category['name']); ?>"><?php echo e($category['name']); ?></a>
                                        </h6>
                                        <div class="home-category-meta">
                                            <p class="home-category-count"><?php echo e(__('website.home.labels.cars_count', ['count' => $category['cars_count']])); ?></p>
                                            <a href="<?php echo e($category['url']); ?>" class="link-icon" aria-label="<?php echo e(__('website.home.actions.view_all_cars')); ?>">
                                                <i class="bx bx-right-arrow-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="category-img">
                                        <?php if($category['image_path']): ?>
                                            <img src="<?php echo e(asset('storage/' . $category['image_path'])); ?>"
                                                alt="<?php echo e($category['name']); ?>" class="img-fluid">
                                        <?php else: ?>
                                            <span class="home-category-fallback" aria-hidden="true">
                                                <i class="bx bxs-car"></i>
                                            </span>
                                        <?php endif; ?>
                                    </div>
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
                            <h2><?php echo e($homeTranslation?->feature_section_title ?: __('website.home.features.section_title')); ?></h2>
                            <p><?php echo e($homeTranslation?->feature_section_paragraph ?: __('website.home.features.section_paragraph')); ?></p>
                        </div>
                        <img src="<?php echo e(asset('website/assets/img/cars/car.png')); ?>" alt="img" class="img-fluid">
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="row row-gap-4">
                        <?php $__currentLoopData = $featureItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $featureItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 d-flex">
                                <div class="feature-item flex-fill">
                                    <span class="feature-icon">
                                        <i class="<?php echo e($featureItem['icon']); ?>"></i>
                                    </span>
                                    <div>
                                        <h6 class="mb-1"><?php echo e($featureItem['title']); ?></h6>
                                        <p><?php echo e($featureItem['description']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                    <div class="img-slider home-car-img-slider owl-carousel">
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
                                    <div class="home-card-badges">
                                        <?php if($car['brand_name']): ?>
                                            <span class="featured-text"><?php echo e($car['brand_name']); ?></span>
                                        <?php endif; ?>
                                        <span class="home-no-deposit-label" aria-label="<?php echo e($noDepositLabel); ?>"><?php echo e($noDepositLabel); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="listing-content">
                                <div class="listing-features d-flex align-items-center justify-content-between">
                                    <div class="list-rating">
                                        <?php $featuredCardTitle = $formatCarCardTitle($car['name']); ?>
                                        <h3 class="listing-title home-car-card-title">
                                            <a href="<?php echo e($car['details_url']); ?>" title="<?php echo e($car['name']); ?>"><?php echo e($featuredCardTitle); ?></a>
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
                            <h2><?php echo e($homeTranslation?->rental_section_title ?: __('website.home.rental.title')); ?></h2>
                            <p><?php echo e($homeTranslation?->rental_section_paragraph ?: __('website.home.rental.paragraph')); ?></p>
                        </div>
                        <?php $__currentLoopData = $rentalSteps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rentalStep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="step-item d-flex align-items-center">
                                <span class="step-icon <?php echo e($rentalStep['icon_bg_class']); ?> me-3">
                                    <i class="<?php echo e($rentalStep['icon_class']); ?>"></i>
                                </span>
                                <div>
                                    <h5><?php echo e($rentalStep['title']); ?></h5>
                                    <p><?php echo e($rentalStep['description']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <div class="count-sec">
                <div class="row row-gap-4" >
                    <?php $__currentLoopData = $rentalStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rentalStat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-3 col-md-6 d-flex">
                            <div class="count-item flex-fill">
                                <h3><span class="counterUp"><?php echo e($rentalStat['value']); ?></span><?php echo e($rentalStat['suffix']); ?></h3>
                                <p><?php echo e($rentalStat['label']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                        <p><?php echo e($heroCopy['starting_from_label']); ?></p>
                                        <h6>
                                            <?php echo e($formatCurrency($car['daily_price'], $car['currency_symbol'] ?? null)); ?>

                                            <span><?php echo e($heroCopy['per_day_label']); ?></span>
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
                <h2><?php echo e($homeTranslation?->testimonial_section_title ?: __('website.home.testimonials.title')); ?></h2>
                <p><?php echo e($homeTranslation?->testimonial_section_paragraph ?: __('website.home.testimonials.paragraph')); ?></p>
            </div>

            <div class="row row-gap-4 justify-content-center">
                <?php $__currentLoopData = $testimonialItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6 d-flex">
                        <div class="testimonial-item testimonial-item-two flex-fill">
                            <div class="user-img">
                                <img src="<?php echo e($testimonial['image']); ?>" class="img-fluid" alt="<?php echo e($testimonial['name']); ?>">
                            </div>
                            <p><?php echo e($testimonial['review']); ?></p>
                            <div class="rating">
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                            </div>
                            <div class="user-info">
                                <h6><?php echo e($testimonial['name']); ?></h6>
                                <p><?php echo e($testimonial['location']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="view-all-btn text-center aos" data-aos="fade-down">
                <a href="<?php echo e(route('website.cars.index')); ?>" class="btn btn-secondary"><?php echo e($homeTranslation?->testimonial_cta_label ?: __('website.home.actions.view_all')); ?><i class="bx bx-right-arrow-alt ms-1"></i></a>
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
                <?php $__currentLoopData = $supportItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supportItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="support-item">
                        <h2><?php echo e($supportItem); ?></h2>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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