<?php $__env->startSection('title', $aboutTranslation?->meta_title ?? $aboutData['page_title'] ?? __('website.about.page_title')); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $assetUrl = static fn (string $path): string => asset('website/assets/' . ltrim($path, '/'));

        $textOr = static function ($value, ?string $fallback = null): ?string {
            $text = trim(strip_tags((string) $value));

            return filled($text) ? $text : $fallback;
        };

        $aboutMainTitle = $textOr($aboutData['main_title'] ?? null, __('website.about.page_title'));
        $aboutAgencyTitle = $textOr($aboutData['agency_title'] ?? null, __('website.about.about_agency_title'));
        $mainParagraph = $textOr($aboutData['main_paragraph'] ?? null, __('website.about.fallback.main_paragraph'));
        $whyChooseTitle = $textOr($aboutData['why_choose_title'] ?? null, __('website.about.cards.why_choose_title'));
        $whyChooseContent = $textOr($aboutData['why_choose_content'] ?? null, __('website.about.cards.why_choose_content'));
        $ourVisionTitle = $textOr($aboutData['our_vision_title'] ?? null, __('website.about.cards.our_vision_title'));
        $ourVisionContent = $textOr($aboutData['our_vision_content'] ?? null, __('website.about.cards.our_vision_content'));
        $ourMissionTitle = $textOr($aboutData['our_mission_title'] ?? null, __('website.about.cards.our_mission_title'));
        $ourMissionContent = $textOr($aboutData['our_mission_content'] ?? null, __('website.about.cards.our_mission_content'));

        $carsCount = (int) ($stats['active_cars'] ?? 0);
        $brandsCount = (int) ($stats['active_brands'] ?? 0);
        $locationsCount = (int) ($stats['active_locations'] ?? 0);
        $categoriesCount = (int) ($stats['active_categories'] ?? 0);
    ?>

    <!-- Breadscrumb Section -->
    <div class="breadcrumb-bar">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title"><?php echo e($aboutMainTitle); ?></h2>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('website.nav.home')); ?></a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo e(__('website.about.breadcrumb.pages')); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo e($aboutMainTitle); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- /Breadscrumb Section -->

    <!-- About -->
    <section class="section about-sec">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-down">
                    <div class="about-img">
                        <div class="about-exp">
                            <span><?php echo e(__('website.about.badge_active_cars', ['count' => number_format($carsCount)])); ?></span>
                        </div>
                        <div class="abt-img">
                            <img src="<?php echo e($aboutData['why_choose_image_url'] ?? $assetUrl('img/about-us.png')); ?>" class="img-fluid" alt="<?php echo e($aboutMainTitle); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-down">
                    <div class="about-content">
                        <h6><?php echo e($aboutAgencyTitle); ?></h6>
                        <h2><?php echo e($aboutMainTitle); ?></h2>
                        <p><?php echo e($mainParagraph); ?></p>
                        <p><?php echo e($ourVisionContent); ?></p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul>
                                    <li><?php echo e($whyChooseTitle); ?></li>
                                    <li><?php echo e($ourVisionTitle); ?></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li><?php echo e($ourMissionTitle); ?></li>
                                    <li><?php echo e(__('website.about.stats_locations', ['count' => number_format($locationsCount)])); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /About -->

    <!-- services -->
    <section class="section services bg-light-primary">
        <div class="service-right">
            <img src="<?php echo e($assetUrl('img/bg/service-right.svg')); ?>" class="img-fluid" alt="services right">
        </div>
        <div class="container">
            <!-- Heading title-->
            <div class="section-heading" data-aos="fade-down">
                <h2><?php echo e(__('website.home.rental.title')); ?></h2>
                <p><?php echo e(__('website.home.rental.paragraph')); ?></p>
            </div>
            <!-- /Heading title -->
            <div class="services-work">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-12" data-aos="fade-down">
                        <div class="services-group">
                            <div class="services-icon border-secondary">
                                <img class="icon-img bg-secondary" src="<?php echo e($assetUrl('img/icons/services-icon-01.svg')); ?>" alt="Choose Locations">
                            </div>
                            <div class="services-content">
                                <h3>1. <?php echo e(__('website.home.rental.step1_title')); ?></h3>
                                <p><?php echo e(__('website.home.rental.step1_description')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-12" data-aos="fade-down">
                        <div class="services-group">
                            <div class="services-icon border-warning">
                                <img class="icon-img bg-warning" src="<?php echo e($assetUrl('img/icons/services-icon-02.svg')); ?>" alt="Pick-Up Locations">
                            </div>
                            <div class="services-content">
                                <h3>2. <?php echo e(__('website.home.rental.step2_title')); ?></h3>
                                <p><?php echo e(__('website.home.rental.step2_description')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-12" data-aos="fade-down">
                        <div class="services-group">
                            <div class="services-icon border-dark">
                                <img class="icon-img bg-dark" src="<?php echo e($assetUrl('img/icons/services-icon-03.svg')); ?>" alt="Book your Car">
                            </div>
                            <div class="services-content">
                                <h3>3. <?php echo e(__('website.home.rental.step3_title')); ?></h3>
                                <p><?php echo e(__('website.home.rental.step3_description')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /services -->

    <!-- Facts By The Numbers -->
    <section class="section facts-number">
        <div class="facts-left">
            <img src="<?php echo e($assetUrl('img/bg/facts-left.png')); ?>" class="img-fluid" alt="facts left">
        </div>
        <div class="facts-right">
            <img src="<?php echo e($assetUrl('img/bg/facts-right.png')); ?>" class="img-fluid" alt="facts right">
        </div>
        <div class="container">
            <!-- Heading title-->
            <div class="section-heading" data-aos="fade-down">
                <h2 class="title text-white"><?php echo e(__('website.about.facts.title')); ?></h2>
                <p class="description text-white"><?php echo e(__('website.about.facts.paragraph')); ?></p>
            </div>
            <!-- /Heading title -->
            <div class="counter-group">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-12 d-flex" data-aos="fade-down">
                        <div class="count-group flex-fill">
                            <div class="customer-count d-flex align-items-center">
                                <div class="count-img">
                                    <img src="<?php echo e($assetUrl('img/icons/bx-heart.svg')); ?>" alt="Icon">
                                </div>
                                <div class="count-content">
                                    <h4><span class="counterUp"><?php echo e($brandsCount); ?></span>+</h4>
                                    <p><?php echo e(__('website.about.facts.active_brands')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 d-flex" data-aos="fade-down">
                        <div class="count-group flex-fill">
                            <div class="customer-count d-flex align-items-center">
                                <div class="count-img">
                                    <img src="<?php echo e($assetUrl('img/icons/bx-car.svg')); ?>" alt="Icon">
                                </div>
                                <div class="count-content">
                                    <h4><span class="counterUp"><?php echo e($carsCount); ?></span>+</h4>
                                    <p><?php echo e(__('website.home.stats.count_of_cars')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 d-flex" data-aos="fade-down">
                        <div class="count-group flex-fill">
                            <div class="customer-count d-flex align-items-center">
                                <div class="count-img">
                                    <img src="<?php echo e($assetUrl('img/icons/bx-headphone.svg')); ?>" alt="Icon">
                                </div>
                                <div class="count-content">
                                    <h4><span class="counterUp"><?php echo e($locationsCount); ?></span>+</h4>
                                    <p><?php echo e(__('website.home.stats.locations_to_pickup')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 d-flex" data-aos="fade-down">
                        <div class="count-group flex-fill">
                            <div class="customer-count d-flex align-items-center">
                                <div class="count-img">
                                    <img src="<?php echo e($assetUrl('img/icons/bx-history.svg')); ?>" alt="Icon">
                                </div>
                                <div class="count-content">
                                    <h4><span class="counterUp"><?php echo e($categoriesCount); ?></span>+</h4>
                                    <p><?php echo e(__('website.about.facts.active_categories')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Facts By The Numbers -->

    <!-- Why Choose Us -->
    <section class="section why-choose">
        <div class="choose-left">
            <img src="<?php echo e($aboutData['our_mission_image_url'] ?? $assetUrl('img/bg/choose-left.png')); ?>" class="img-fluid" alt="Why Choose Us">
        </div>
        <div class="container">
            <!-- Heading title-->
            <div class="section-heading" data-aos="fade-down">
                <h2><?php echo e($whyChooseTitle); ?></h2>
                <p><?php echo e($whyChooseContent); ?></p>
            </div>
            <!-- /Heading title -->
            <div class="why-choose-group">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-12 d-flex" data-aos="fade-down">
                        <div class="card flex-fill">
                            <div class="card-body">
                                <div class="choose-img choose-black">
                                    <img src="<?php echo e($assetUrl('img/icons/bx-selection.svg')); ?>" alt="Icon">
                                </div>
                                <div class="choose-content">
                                    <h4><?php echo e($whyChooseTitle); ?></h4>
                                    <p><?php echo e($whyChooseContent); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12 d-flex" data-aos="fade-down">
                        <div class="card flex-fill">
                            <div class="card-body">
                                <div class="choose-img choose-secondary">
                                    <img src="<?php echo e($assetUrl('img/icons/bx-crown.svg')); ?>" alt="Icon">
                                </div>
                                <div class="choose-content">
                                    <h4><?php echo e($ourVisionTitle); ?></h4>
                                    <p><?php echo e($ourVisionContent); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12 d-flex" data-aos="fade-down">
                        <div class="card flex-fill">
                            <div class="card-body">
                                <div class="choose-img choose-primary">
                                    <img src="<?php echo e($assetUrl('img/icons/bx-user-check.svg')); ?>" alt="Icon">
                                </div>
                                <div class="choose-content">
                                    <h4><?php echo e($ourMissionTitle); ?></h4>
                                    <p><?php echo e($ourMissionContent); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Why Choose Us -->

    <!-- About us Testimonials -->
    <section class="section about-testimonial testimonials-section">
        <div class="container">
            <!-- Heading title-->
            <div class="section-heading" data-aos="fade-down">
                <h2 class="title text-white"><?php echo e(__('website.home.testimonials.title')); ?></h2>
                <p class="description text-white"><?php echo e(__('website.home.testimonials.paragraph')); ?></p>
            </div>
            <!-- /Heading title -->
            <div class="owl-carousel about-testimonials testimonial-group mb-0 owl-theme">

                <!-- Carousel Item -->
                <div class="testimonial-item d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="quotes-head"></div>
                            <div class="review-box">
                                <div class="review-profile">
                                    <div class="review-img">
                                        <img src="<?php echo e($assetUrl('img/profiles/avatar-02.jpg')); ?>" class="img-fluid" alt="img">
                                    </div>
                                </div>
                                <div class="review-details">
                                    <h6><?php echo e(__('website.home.testimonials.client_1_name')); ?></h6>
                                    <div class="list-rating">
                                        <div class="list-rating-star">
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                        </div>
                                        <p><span>(5.0)</span></p>
                                    </div>
                                </div>
                            </div>
                            <p><?php echo e(__('website.home.testimonials.review1')); ?></p>
                        </div>
                    </div>
                </div>
                <!-- /Carousel Item  -->

                <!-- Carousel Item -->
                <div class="testimonial-item d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="quotes-head"></div>
                            <div class="review-box">
                                <div class="review-profile">
                                    <div class="review-img">
                                        <img src="<?php echo e($assetUrl('img/profiles/avatar-03.jpg')); ?>" class="img-fluid" alt="img">
                                    </div>
                                </div>
                                <div class="review-details">
                                    <h6><?php echo e(__('website.home.testimonials.client_2_name')); ?></h6>
                                    <div class="list-rating">
                                        <div class="list-rating-star">
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                        </div>
                                        <p><span>(5.0)</span></p>
                                    </div>
                                </div>
                            </div>
                            <p><?php echo e(__('website.home.testimonials.review2')); ?></p>
                        </div>
                    </div>
                </div>
                <!-- /Carousel Item  -->

                <!-- Carousel Item -->
                <div class="testimonial-item d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="quotes-head"></div>
                            <div class="review-box">
                                <div class="review-profile">
                                    <div class="review-img">
                                        <img src="<?php echo e($assetUrl('img/profiles/avatar-04.jpg')); ?>" class="img-fluid" alt="img">
                                    </div>
                                </div>
                                <div class="review-details">
                                    <h6><?php echo e(__('website.home.testimonials.client_3_name')); ?></h6>
                                    <div class="list-rating">
                                        <div class="list-rating-star">
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                        </div>
                                        <p><span>(5.0)</span></p>
                                    </div>
                                </div>
                            </div>
                            <p><?php echo e(__('website.home.testimonials.review3')); ?></p>
                        </div>
                    </div>
                </div>
                <!-- /Carousel Item  -->
            </div>
        </div>
    </section>
    <!-- About us Testimonials -->

    <!-- FAQ  -->
    <section class="section faq-section bg-light-primary">
        <div class="container">
            <!-- Heading title-->
            <div class="section-heading" data-aos="fade-down">
                <h2><?php echo e(__('website.home.sections.faq_title')); ?></h2>
                <p><?php echo e(__('website.home.sections.faq_paragraph')); ?></p>
            </div>
            <!-- /Heading title -->
            <div class="faq-info">
                <?php $__empty_1 = true; $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faqIndex => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $faqId = 'faq-' . ($faq['id'] ?? $faqIndex);
                    ?>
                    <div class="faq-card bg-white" data-aos="fade-down">
                        <h4 class="faq-title">
                            <a class="<?php echo e($faqIndex > 0 ? 'collapsed' : ''); ?>" data-bs-toggle="collapse" href="#<?php echo e($faqId); ?>" aria-expanded="<?php echo e($faqIndex === 0 ? 'true' : 'false'); ?>">
                                <?php echo e($faq['question']); ?>

                            </a>
                        </h4>
                        <div id="<?php echo e($faqId); ?>" class="card-collapse collapse <?php echo e($faqIndex === 0 ? 'show' : ''); ?>">
                            <p><?php echo e($faq['answer']); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="faq-card bg-white" data-aos="fade-down">
                        <h4 class="faq-title"><?php echo e(__('website.about.no_faqs_title')); ?></h4>
                        <div class="card-collapse collapse show">
                            <p><?php echo e(__('website.about.no_faqs_description')); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <!-- /FAQ -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.website', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\website\about-us.blade.php ENDPATH**/ ?>