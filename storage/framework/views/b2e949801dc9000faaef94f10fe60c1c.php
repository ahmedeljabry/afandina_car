<?php
    $footerLogo = $footerLogo ?? asset('admin/dist/logo/website_logos/logo_light.svg');
    $footerDescription = $footerDescription ?? null;
    $footerCompanyName = $footerCompanyName ?? config('app.name', 'Afandina Car Rental');
    $footerHomeTranslation = $footerHomeTranslation ?? null;
    $quickLinks = $quickLinks ?? collect();
    $supportItems = $supportItems ?? collect();
    $socialLinks = $socialLinks ?? collect();
    $footerBrands = $footerBrands ?? collect();
    $footerCategories = $footerCategories ?? collect();
    $footerLocations = $footerLocations ?? collect();
    $paymentMethods = $paymentMethods ?? collect();
?>

<footer class="footer footer-four">
    <div class="footer-top aos" data-aos="fade-up">
        <div class="container">
            <div class="row row-gap-4">
                <div class="col-lg-4">
                    <div class="footer-contact footer-widget">
                        <div class="footer-logo">
                            <img src="<?php echo e($footerLogo); ?>" class="img-fluid aos" alt="Logo">
                        </div>

                        <?php if(filled($footerDescription)): ?>
                            <div class="footer-contact-info">
                                <p><?php echo e($footerDescription); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if($socialLinks->isNotEmpty()): ?>
                            <ul class="social-icon">
                                <?php $__currentLoopData = $socialLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $socialLink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <a href="<?php echo e($socialLink['url']); ?>" target="_blank" rel="noopener noreferrer">
                                            <i class="<?php echo e($socialLink['icon']); ?>"></i>
                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget footer-menu">
                        <h5 class="footer-title">
                            <i class="bx bx-link-alt me-1"></i><?php echo e(__('website.footer.quick_links')); ?>

                        </h5>
                        <ul>
                            <?php $__currentLoopData = $quickLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a href="<?php echo e($link['url']); ?>">
                                        <i class="<?php echo e($link['icon']); ?> me-1"></i><?php echo e($link['label']); ?>

                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget footer-menu">
                        <h5 class="footer-title">
                            <i class="bx bx-headphone me-1"></i><?php echo e(__('website.footer.support')); ?>

                        </h5>
                        <ul>
                            <?php $__empty_1 = true; $__currentLoopData = $supportItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supportItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <li>
                                    <a href="<?php echo e($supportItem['url'] ?: 'javascript:void(0);'); ?>">
                                        <i class="<?php echo e($supportItem['icon']); ?> me-1"></i><?php echo e($supportItem['label']); ?>

                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li><?php echo e(__('website.footer.no_support_details')); ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row row-gap-4 mt-3">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget">
                        <h5 class="footer-title">
                            <i class="bx bxs-car me-1"></i>
                            <?php echo e($footerHomeTranslation?->brand_section_title ?? __('website.footer.brands_section')); ?>

                        </h5>

                        <div class="d-flex flex-wrap gap-2">
                            <?php $__empty_1 = true; $__currentLoopData = $footerBrands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a href="<?php echo e($brand['url']); ?>" class="btn btn-outline-light btn-sm rounded-pill">
                                    <?php echo e($brand['name']); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span class="text-muted"><?php echo e(__('website.footer.empty_brands')); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget">
                        <h5 class="footer-title">
                            <i class="bx bxs-category me-1"></i>
                            <?php echo e($footerHomeTranslation?->category_section_title ?? __('website.footer.categories_section')); ?>

                        </h5>

                        <div class="d-flex flex-wrap gap-2">
                            <?php $__empty_1 = true; $__currentLoopData = $footerCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a href="<?php echo e($category['url']); ?>" class="btn btn-outline-light btn-sm rounded-pill">
                                    <?php echo e($category['name']); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span class="text-muted"><?php echo e(__('website.footer.empty_categories')); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="footer-widget">
                        <h5 class="footer-title">
                            <i class="bx bxs-map me-1"></i>
                            <?php echo e($footerHomeTranslation?->where_find_us_section_title ?? __('website.footer.locations')); ?>

                        </h5>

                        <div class="d-flex flex-wrap gap-2">
                            <?php $__empty_1 = true; $__currentLoopData = $footerLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a href="<?php echo e($location['url']); ?>" class="btn btn-outline-light btn-sm rounded-pill">
                                    <?php echo e($location['name']); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span class="text-muted"><?php echo e(__('website.footer.empty_locations')); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="copyright">
                <div class="row align-items-center row-gap-3">
                    <div class="col-lg-4">
                        <div class="copyright-text">
                            <p>&copy; <?php echo e(now()->year); ?> <?php echo e($footerCompanyName); ?>. <?php echo e(__('website.footer.rights_reserved')); ?></p>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="text-center mb-2 text-white-50"><?php echo e(__('website.footer.available_payment_methods')); ?></div>
                        <div class="payment-list">
                            <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentMethod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="javascript:void(0);">
                                    <img src="<?php echo e($paymentMethod); ?>" alt="payment">
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <ul class="privacy-link">
                            <li><a href="<?php echo e(route('home')); ?>"><?php echo e(__('website.nav.home')); ?></a></li>
                            <li><a href="<?php echo e(route('website.cars.index')); ?>"><?php echo e(__('website.nav.all_cars')); ?></a></li>
                            <li><a href="<?php echo e(route('website.blogs.index')); ?>"><?php echo e(__('website.nav.blogs')); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH D:\afandina\resources\views\includes\website\footer.blade.php ENDPATH**/ ?>