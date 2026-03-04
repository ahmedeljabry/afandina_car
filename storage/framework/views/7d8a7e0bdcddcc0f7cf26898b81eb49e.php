<?php $__env->startSection('title', 'Home CMS'); ?>

<?php $__env->startSection('page-title'); ?>
    Home CMS
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $activeLanguageMap = collect($activeLanguages ?? [])->keyBy('code');
        $homeLocales = [
            ['code' => 'en', 'name' => data_get($activeLanguageMap, 'en.name', 'English')],
            ['code' => 'ar', 'name' => data_get($activeLanguageMap, 'ar.name', 'Arabic')],
        ];

        $translationsByLocale = [];
        foreach ($homeLocales as $locale) {
            $translationsByLocale[$locale['code']] = $item->translations->firstWhere('locale', $locale['code']);
        }

        $translationFallbackKeys = [
            'hero_title_prefix' => 'website.home.hero.title_prefix',
            'hero_title_highlight' => 'website.home.hero.title_highlight',
            'hero_title_suffix' => 'website.home.hero.title_suffix',
            'hero_banner_paragraph' => 'website.home.hero.banner_paragraph',
            'hero_customers_label' => 'website.home.hero.customers_label',
            'hero_customers_subtitle' => 'website.home.hero.customers_subtitle',
            'hero_browse_cars_label' => 'website.home.hero.browse_cars',
            'hero_browse_blogs_label' => 'website.home.hero.browse_blogs',
            'hero_starting_from_label' => 'website.home.hero.starting_from',
            'hero_per_day_label' => 'website.home.hero.per_day',
            'hero_available_for_rent_label' => 'website.home.hero.available_for_rent',
            'feature_section_title' => 'website.home.features.section_title',
            'feature_section_paragraph' => 'website.home.features.section_paragraph',
            'feature_item_1_title' => 'website.home.features.best_deal.title',
            'feature_item_1_description' => 'website.home.features.best_deal.description',
            'feature_item_2_title' => 'website.home.features.doorstep_delivery.title',
            'feature_item_2_description' => 'website.home.features.doorstep_delivery.description',
            'feature_item_3_title' => 'website.home.features.low_security_deposit.title',
            'feature_item_3_description' => 'website.home.features.low_security_deposit.description',
            'feature_item_4_title' => 'website.home.features.latest_cars.title',
            'feature_item_4_description' => 'website.home.features.latest_cars.description',
            'feature_item_5_title' => 'website.home.features.customer_support.title',
            'feature_item_5_description' => 'website.home.features.customer_support.description',
            'feature_item_6_title' => 'website.home.features.no_hidden_charges.title',
            'feature_item_6_description' => 'website.home.features.no_hidden_charges.description',
            'rental_section_title' => 'website.home.rental.title',
            'rental_section_paragraph' => 'website.home.rental.paragraph',
            'rental_step_1_title' => 'website.home.rental.step1_title',
            'rental_step_1_description' => 'website.home.rental.step1_description',
            'rental_step_2_title' => 'website.home.rental.step2_title',
            'rental_step_2_description' => 'website.home.rental.step2_description',
            'rental_step_3_title' => 'website.home.rental.step3_title',
            'rental_step_3_description' => 'website.home.rental.step3_description',
            'rental_stat_1_label' => 'website.home.stats.happy_customers',
            'rental_stat_2_label' => 'website.home.stats.count_of_cars',
            'rental_stat_3_label' => 'website.home.stats.locations_to_pickup',
            'rental_stat_4_label' => 'website.home.stats.total_kilometers',
            'support_item_1_text' => 'website.home.support.best_rate',
            'support_item_2_text' => 'website.home.support.free_cancellation',
            'support_item_3_text' => 'website.home.support.best_security',
            'support_item_4_text' => 'website.home.support.latest_update',
            'support_item_5_text' => 'website.home.support.trusted_proof',
        ];

        $prefillValues = [];
        foreach ($translationFallbackKeys as $fieldName => $key) {
            $prefillValues[$fieldName] = [
                'en' => trans($key, [], 'en'),
                'ar' => trans($key, [], 'ar'),
            ];
        }

        $prefillValues['rental_stat_1_value'] = ['en' => '16', 'ar' => '16'];
        $prefillValues['rental_stat_1_suffix'] = ['en' => 'K+', 'ar' => 'K+'];
        $prefillValues['rental_stat_2_value'] = ['en' => '2547', 'ar' => '2547'];
        $prefillValues['rental_stat_2_suffix'] = ['en' => 'K+', 'ar' => 'K+'];
        $prefillValues['rental_stat_3_value'] = ['en' => '625', 'ar' => '625'];
        $prefillValues['rental_stat_3_suffix'] = ['en' => 'K+', 'ar' => 'K+'];
        $prefillValues['rental_stat_4_value'] = ['en' => '15000', 'ar' => '15000'];
        $prefillValues['rental_stat_4_suffix'] = ['en' => 'K+', 'ar' => 'K+'];

        $sectionLinks = [
            ['anchor' => 'home-section-general', 'label' => 'Home Overview'],
            ['anchor' => 'home-section-hero', 'label' => 'Hero Banner'],
            ['anchor' => 'home-section-features', 'label' => 'Features'],
            ['anchor' => 'home-section-rental', 'label' => 'Rental & Stats'],
            ['anchor' => 'home-section-headings', 'label' => 'Section Headings'],
            ['anchor' => 'home-section-testimonials', 'label' => 'Testimonials'],
            ['anchor' => 'home-section-support', 'label' => 'Support Ticker'],
            ['anchor' => 'home-section-shared', 'label' => 'Shared Content'],
            ['anchor' => 'home-section-seo', 'label' => 'SEO'],
        ];
    ?>

    <style>
        .home-sections-nav {
            position: sticky;
            top: 70px;
            z-index: 10;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 0.75rem;
        }

        .home-sections-nav a {
            margin: 0.25rem;
        }

        .home-media-preview {
            width: 100%;
            min-height: 220px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
            background: #f8f9fa;
        }

        .home-media-empty {
            min-height: 220px;
            border: 1px dashed #ced4da;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            background: #f8f9fa;
            text-align: center;
            padding: 1rem;
        }
    </style>

    <?php
        $sections = [
            [
                'anchor' => 'home-section-hero',
                'title' => 'Hero Banner',
                'description' => 'Main banner copy and hero labels shown on the homepage.',
                'fields' => [
                    ['name' => 'hero_title_prefix', 'label' => 'Title Prefix'],
                    ['name' => 'hero_title_highlight', 'label' => 'Title Highlight'],
                    ['name' => 'hero_title_suffix', 'label' => 'Title Suffix'],
                    ['name' => 'hero_banner_paragraph', 'label' => 'Banner Paragraph', 'type' => 'textarea', 'rows' => 4],
                    ['name' => 'hero_customers_label', 'label' => 'Customers Label'],
                    ['name' => 'hero_customers_subtitle', 'label' => 'Customers Subtitle'],
                    ['name' => 'hero_browse_cars_label', 'label' => 'Browse Cars Button'],
                    ['name' => 'hero_browse_blogs_label', 'label' => 'Browse Blogs Button'],
                    ['name' => 'hero_starting_from_label', 'label' => 'Starting From Label'],
                    ['name' => 'hero_per_day_label', 'label' => 'Per Day Label'],
                    ['name' => 'hero_available_for_rent_label', 'label' => 'Available For Rent Label'],
                ],
            ],
            [
                'anchor' => 'home-section-features',
                'title' => 'Features',
                'description' => 'Feature section heading and the six feature cards.',
                'fields' => [
                    ['name' => 'feature_section_title', 'label' => 'Section Title'],
                    ['name' => 'feature_section_paragraph', 'label' => 'Section Paragraph', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'feature_item_1_title', 'label' => 'Feature 1 Title'],
                    ['name' => 'feature_item_1_description', 'label' => 'Feature 1 Description', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'feature_item_2_title', 'label' => 'Feature 2 Title'],
                    ['name' => 'feature_item_2_description', 'label' => 'Feature 2 Description', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'feature_item_3_title', 'label' => 'Feature 3 Title'],
                    ['name' => 'feature_item_3_description', 'label' => 'Feature 3 Description', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'feature_item_4_title', 'label' => 'Feature 4 Title'],
                    ['name' => 'feature_item_4_description', 'label' => 'Feature 4 Description', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'feature_item_5_title', 'label' => 'Feature 5 Title'],
                    ['name' => 'feature_item_5_description', 'label' => 'Feature 5 Description', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'feature_item_6_title', 'label' => 'Feature 6 Title'],
                    ['name' => 'feature_item_6_description', 'label' => 'Feature 6 Description', 'type' => 'textarea', 'rows' => 3],
                ],
            ],
            [
                'anchor' => 'home-section-rental',
                'title' => 'Rental & Stats',
                'description' => 'Rental steps and counter values used in the homepage stats block.',
                'fields' => [
                    ['name' => 'rental_section_title', 'label' => 'Section Title'],
                    ['name' => 'rental_section_paragraph', 'label' => 'Section Paragraph', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'rental_step_1_title', 'label' => 'Step 1 Title'],
                    ['name' => 'rental_step_1_description', 'label' => 'Step 1 Description', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'rental_step_2_title', 'label' => 'Step 2 Title'],
                    ['name' => 'rental_step_2_description', 'label' => 'Step 2 Description', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'rental_step_3_title', 'label' => 'Step 3 Title'],
                    ['name' => 'rental_step_3_description', 'label' => 'Step 3 Description', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'rental_stat_1_value', 'label' => 'Stat 1 Value'],
                    ['name' => 'rental_stat_1_suffix', 'label' => 'Stat 1 Suffix'],
                    ['name' => 'rental_stat_1_label', 'label' => 'Stat 1 Label'],
                    ['name' => 'rental_stat_2_value', 'label' => 'Stat 2 Value'],
                    ['name' => 'rental_stat_2_suffix', 'label' => 'Stat 2 Suffix'],
                    ['name' => 'rental_stat_2_label', 'label' => 'Stat 2 Label'],
                    ['name' => 'rental_stat_3_value', 'label' => 'Stat 3 Value'],
                    ['name' => 'rental_stat_3_suffix', 'label' => 'Stat 3 Suffix'],
                    ['name' => 'rental_stat_3_label', 'label' => 'Stat 3 Label'],
                    ['name' => 'rental_stat_4_value', 'label' => 'Stat 4 Value'],
                    ['name' => 'rental_stat_4_suffix', 'label' => 'Stat 4 Suffix'],
                    ['name' => 'rental_stat_4_label', 'label' => 'Stat 4 Label'],
                ],
            ],
            [
                'anchor' => 'home-section-headings',
                'title' => 'Section Headings',
                'description' => 'Titles and descriptions for the main homepage content blocks.',
                'fields' => [
                    ['name' => 'category_section_title', 'label' => 'Category Section Title'],
                    ['name' => 'category_section_paragraph', 'label' => 'Category Section Paragraph', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'featured_cars_section_title', 'label' => 'Featured Cars Section Title'],
                    ['name' => 'featured_cars_section_paragraph', 'label' => 'Featured Cars Section Paragraph', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'brand_section_title', 'label' => 'Brand Section Title'],
                    ['name' => 'brand_section_paragraph', 'label' => 'Brand Section Paragraph', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'car_only_section_title', 'label' => 'Only On Section Title'],
                    ['name' => 'car_only_section_paragraph', 'label' => 'Only On Section Paragraph', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'blog_section_title', 'label' => 'Blog Section Title'],
                    ['name' => 'blog_section_paragraph', 'label' => 'Blog Section Paragraph', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'faq_section_title', 'label' => 'FAQ Section Title'],
                    ['name' => 'faq_section_paragraph', 'label' => 'FAQ Section Paragraph', 'type' => 'textarea', 'rows' => 3],
                ],
            ],
            [
                'anchor' => 'home-section-testimonials',
                'title' => 'Testimonials',
                'description' => 'Three testimonial cards and the CTA button.',
                'fields' => [
                    ['name' => 'testimonial_section_title', 'label' => 'Section Title'],
                    ['name' => 'testimonial_section_paragraph', 'label' => 'Section Paragraph', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'testimonial_review_1', 'label' => 'Review 1', 'type' => 'textarea', 'rows' => 4],
                    ['name' => 'testimonial_client_1_name', 'label' => 'Client 1 Name'],
                    ['name' => 'testimonial_client_1_location', 'label' => 'Client 1 Location'],
                    ['name' => 'testimonial_review_2', 'label' => 'Review 2', 'type' => 'textarea', 'rows' => 4],
                    ['name' => 'testimonial_client_2_name', 'label' => 'Client 2 Name'],
                    ['name' => 'testimonial_client_2_location', 'label' => 'Client 2 Location'],
                    ['name' => 'testimonial_review_3', 'label' => 'Review 3', 'type' => 'textarea', 'rows' => 4],
                    ['name' => 'testimonial_client_3_name', 'label' => 'Client 3 Name'],
                    ['name' => 'testimonial_client_3_location', 'label' => 'Client 3 Location'],
                    ['name' => 'testimonial_cta_label', 'label' => 'CTA Button Label'],
                ],
            ],
            [
                'anchor' => 'home-section-support',
                'title' => 'Support Ticker',
                'description' => 'Scrolling support labels shown in the support marquee.',
                'fields' => [
                    ['name' => 'support_item_1_text', 'label' => 'Support Item 1'],
                    ['name' => 'support_item_2_text', 'label' => 'Support Item 2'],
                    ['name' => 'support_item_3_text', 'label' => 'Support Item 3'],
                    ['name' => 'support_item_4_text', 'label' => 'Support Item 4'],
                    ['name' => 'support_item_5_text', 'label' => 'Support Item 5'],
                ],
            ],
            [
                'anchor' => 'home-section-shared',
                'title' => 'Shared Content',
                'description' => 'Legacy and shared home fields reused by other frontend sections and APIs.',
                'fields' => [
                    ['name' => 'hero_header_title', 'label' => 'Legacy Hero Header Title'],
                    ['name' => 'contact_us_title', 'label' => 'Contact Us Title'],
                    ['name' => 'contact_us_paragraph', 'label' => 'Contact Us Paragraph', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'contact_us_detail_title', 'label' => 'Contact Detail Title'],
                    ['name' => 'contact_us_detail_paragraph', 'label' => 'Contact Detail Paragraph', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'special_offers_section_title', 'label' => 'Special Offers Section Title'],
                    ['name' => 'special_offers_section_paragraph', 'label' => 'Special Offers Section Paragraph', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'why_choose_us_section_title', 'label' => 'Why Choose Us Section Title'],
                    ['name' => 'why_choose_us_section_paragraph', 'label' => 'Why Choose Us Section Paragraph', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'where_find_us_section_title', 'label' => 'Where Find Us Section Title'],
                    ['name' => 'where_find_us_section_paragraph', 'label' => 'Where Find Us Section Paragraph', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'required_documents_section_title', 'label' => 'Required Documents Section Title'],
                    ['name' => 'required_documents_section_paragraph', 'label' => 'Required Documents Section Paragraph', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'instagram_section_title', 'label' => 'Instagram Section Title'],
                    ['name' => 'instagram_section_paragraph', 'label' => 'Instagram Section Paragraph', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'footer_section_paragraph', 'label' => 'Footer Section Paragraph', 'type' => 'textarea', 'rows' => 4],
                ],
            ],
        ];

        $seoQuestionsByLocale = $item->seoQuestions->groupBy('locale');
    ?>

    <form action="<?php echo e(route('admin.' . $modelName . '.update', $item->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <input type="hidden" name="page_name" value="<?php echo e(old('page_name', $item->page_name)); ?>">

        <div class="card card-primary card-outline shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
                    <div>
                        <h4 class="mb-1">Homepage Content Manager</h4>
                        <p class="text-muted mb-0">Control homepage text, media, and SEO for English and Arabic from one screen.</p>
                    </div>
                    <button type="submit" class="btn btn-success mt-3 mt-lg-0">
                        <i class="fas fa-save"></i> Save Home Page
                    </button>
                </div>
            </div>
        </div>

        <div class="home-sections-nav mb-4">
            <?php $__currentLoopData = $sectionLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sectionLink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="#<?php echo e($sectionLink['anchor']); ?>" class="btn btn-sm btn-outline-primary"><?php echo e($sectionLink['label']); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="card card-primary card-outline shadow-sm mb-4" id="home-section-general">
            <div class="card-header bg-white">
                <h5 class="mb-1">Home Overview</h5>
                <p class="text-muted mb-0">Select the hero media and keep the homepage record active.</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Current Hero Video</label>
                            <?php if($item->hero_header_video_path): ?>
                                <video id="hero_video_preview" class="home-media-preview" controls>
                                    <source src="<?php echo e(asset('storage/' . $item->hero_header_video_path)); ?>">
                                </video>
                            <?php else: ?>
                                <div class="home-media-empty" id="hero_video_empty">No hero video uploaded yet.</div>
                                <video id="hero_video_preview" class="home-media-preview d-none" controls></video>
                            <?php endif; ?>
                            <div class="custom-file mt-3">
                                <input type="file" name="hero_header_video_path" id="hero_header_video_path"
                                    class="custom-file-input <?php $__errorArgs = ['hero_header_video_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    accept="video/*" data-preview-target="hero_video_preview" data-empty-target="hero_video_empty">
                                <label class="custom-file-label" for="hero_header_video_path">Upload Hero Video</label>
                                <?php $__errorArgs = ['hero_header_video_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Current Hero Image</label>
                            <?php if($item->hero_header_image_path): ?>
                                <img id="hero_image_preview" src="<?php echo e(asset('storage/' . $item->hero_header_image_path)); ?>"
                                    alt="Hero image preview" class="home-media-preview">
                            <?php else: ?>
                                <div class="home-media-empty" id="hero_image_empty">No hero image uploaded yet.</div>
                                <img id="hero_image_preview" src="" alt="Hero image preview" class="home-media-preview d-none">
                            <?php endif; ?>
                            <div class="custom-file mt-3">
                                <input type="file" name="hero_header_image_path" id="hero_header_image_path"
                                    class="custom-file-input <?php $__errorArgs = ['hero_header_image_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    accept="image/*" data-preview-target="hero_image_preview" data-empty-target="hero_image_empty">
                                <label class="custom-file-label" for="hero_header_image_path">Upload Hero Image</label>
                                <?php $__errorArgs = ['hero_header_image_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="hero_type" class="font-weight-bold">Hero Media Type</label>
                            <select name="hero_type" id="hero_type" class="form-control <?php $__errorArgs = ['hero_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="video" <?php if(old('hero_type', $item->hero_type) === 'video'): echo 'selected'; endif; ?>>Video</option>
                                <option value="image" <?php if(old('hero_type', $item->hero_type) === 'image'): echo 'selected'; endif; ?>>Image</option>
                            </select>
                            <?php $__errorArgs = ['hero_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="is_active" class="font-weight-bold d-block">Status</label>
                            <div class="custom-control custom-switch mt-2">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    class="custom-control-input" <?php if((bool) old('is_active', $item->is_active)): echo 'checked'; endif; ?>>
                                <label class="custom-control-label" for="is_active">Homepage is active</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('pages.admin.homes.partials.section-panel', [
                'section' => $section,
                'homeLocales' => $homeLocales,
                'translationsByLocale' => $translationsByLocale,
                'prefillValues' => $prefillValues,
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div class="card card-primary card-outline shadow-sm mb-4" id="home-section-seo">
            <div class="card-header bg-white">
                <h5 class="mb-1">SEO</h5>
                <p class="text-muted mb-0">Meta data, robots directives, and structured Q&amp;A for the homepage.</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php $__currentLoopData = $homeLocales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $localeCode = $locale['code'];
                            $translation = $translationsByLocale[$localeCode] ?? null;
                            $metaKeywordsJson = $translation?->meta_keywords ?? '';
                            $metaKeywords = json_decode((string) $metaKeywordsJson, true);
                            $metaKeywordsValue = '';

                            if (is_array($metaKeywords) && !empty($metaKeywords)) {
                                if (isset($metaKeywords[0]) && is_array($metaKeywords[0]) && isset($metaKeywords[0]['value'])) {
                                    $metaKeywordsValue = implode(',', array_column($metaKeywords, 'value'));
                                } else {
                                    $metaKeywordsValue = implode(',', $metaKeywords);
                                }
                            } elseif (is_string($metaKeywordsJson) && $metaKeywordsJson !== '[]') {
                                $metaKeywordsValue = $metaKeywordsJson;
                            }

                            $oldSeoRows = old('seo_questions.' . $localeCode);
                            if (is_array($oldSeoRows)) {
                                $seoRows = array_values($oldSeoRows);
                            } else {
                                $seoRows = $seoQuestionsByLocale->get($localeCode, collect())->map(function ($seoQuestion) {
                                    return [
                                        'question' => $seoQuestion->question_text,
                                        'answer' => $seoQuestion->answer_text,
                                    ];
                                })->values()->all();
                            }

                            if (empty($seoRows)) {
                                $seoRows = [['question' => '', 'answer' => '']];
                            }
                        ?>

                        <div class="col-lg-6 d-flex">
                            <div class="border rounded p-3 mb-3 flex-fill">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="mb-0"><?php echo e($locale['name']); ?></h6>
                                    <span class="badge badge-secondary"><?php echo e(strtoupper($localeCode)); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="meta_title_<?php echo e($localeCode); ?>" class="font-weight-bold">Meta Title</label>
                                    <input type="text" name="meta_title[<?php echo e($localeCode); ?>]" id="meta_title_<?php echo e($localeCode); ?>"
                                        class="form-control <?php $__errorArgs = ['meta_title.' . $localeCode];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('meta_title.' . $localeCode, $translation?->meta_title ?? '')); ?>">
                                    <?php $__errorArgs = ['meta_title.' . $localeCode];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label for="meta_description_<?php echo e($localeCode); ?>" class="font-weight-bold">Meta Description</label>
                                    <textarea name="meta_description[<?php echo e($localeCode); ?>]" id="meta_description_<?php echo e($localeCode); ?>"
                                        class="form-control <?php $__errorArgs = ['meta_description.' . $localeCode];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        rows="3"><?php echo e(old('meta_description.' . $localeCode, $translation?->meta_description ?? '')); ?></textarea>
                                    <?php $__errorArgs = ['meta_description.' . $localeCode];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label for="meta_keywords_<?php echo e($localeCode); ?>" class="font-weight-bold">Meta Keywords</label>
                                    <input type="text" name="meta_keywords[<?php echo e($localeCode); ?>]" id="meta_keywords_<?php echo e($localeCode); ?>"
                                        class="form-control <?php $__errorArgs = ['meta_keywords.' . $localeCode];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('meta_keywords.' . $localeCode, $metaKeywordsValue)); ?>"
                                        placeholder="keyword one, keyword two">
                                    <?php $__errorArgs = ['meta_keywords.' . $localeCode];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group border rounded p-3 h-100">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" name="robots_index[<?php echo e($localeCode); ?>]"
                                                    id="robots_index_<?php echo e($localeCode); ?>" class="custom-control-input"
                                                    value="index"
                                                    <?php if(old('robots_index.' . $localeCode, $translation?->robots_index ?? 'index') === 'index'): echo 'checked'; endif; ?>>
                                                <label class="custom-control-label" for="robots_index_<?php echo e($localeCode); ?>">Allow indexing</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group border rounded p-3 h-100">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" name="robots_follow[<?php echo e($localeCode); ?>]"
                                                    id="robots_follow_<?php echo e($localeCode); ?>" class="custom-control-input"
                                                    value="follow"
                                                    <?php if(old('robots_follow.' . $localeCode, $translation?->robots_follow ?? 'follow') === 'follow'): echo 'checked'; endif; ?>>
                                                <label class="custom-control-label" for="robots_follow_<?php echo e($localeCode); ?>">Allow link follow</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mt-4 mb-3">
                                    <div>
                                        <h6 class="mb-1">SEO Questions</h6>
                                        <p class="text-muted mb-0">Optional structured content for search engines.</p>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary add-seo-question"
                                        data-lang="<?php echo e($localeCode); ?>">Add Question</button>
                                </div>

                                <div class="seo-questions-container" id="seo-questions-<?php echo e($localeCode); ?>">
                                    <?php $__currentLoopData = $seoRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $seoRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="seo-question-group border rounded p-3 mb-3">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Question</label>
                                                <input type="text"
                                                    name="seo_questions[<?php echo e($localeCode); ?>][<?php echo e($index); ?>][question]"
                                                    class="form-control"
                                                    value="<?php echo e(data_get($seoRow, 'question', '')); ?>">
                                            </div>
                                            <div class="form-group mb-2">
                                                <label class="font-weight-bold">Answer</label>
                                                <textarea name="seo_questions[<?php echo e($localeCode); ?>][<?php echo e($index); ?>][answer]"
                                                    class="form-control" rows="3"><?php echo e(data_get($seoRow, 'answer', '')); ?></textarea>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-seo-question">Remove</button>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-4">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save"></i> Save Home Page
            </button>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.querySelectorAll('.custom-file-input').forEach(function (input) {
            input.addEventListener('change', function () {
                var label = this.nextElementSibling;
                var file = this.files[0];

                if (label && file) {
                    label.textContent = file.name;
                }

                var previewTarget = this.getAttribute('data-preview-target');
                var emptyTarget = this.getAttribute('data-empty-target');
                if (!previewTarget || !file) {
                    return;
                }

                var previewElement = document.getElementById(previewTarget);
                var emptyElement = emptyTarget ? document.getElementById(emptyTarget) : null;
                var objectUrl = URL.createObjectURL(file);

                if (previewElement.tagName === 'IMG') {
                    previewElement.src = objectUrl;
                    previewElement.classList.remove('d-none');
                } else if (previewElement.tagName === 'VIDEO') {
                    previewElement.src = objectUrl;
                    previewElement.classList.remove('d-none');
                    previewElement.load();
                }

                if (emptyElement) {
                    emptyElement.classList.add('d-none');
                }
            });
        });

        document.querySelectorAll('.add-seo-question').forEach(function (button) {
            button.addEventListener('click', function () {
                var lang = this.getAttribute('data-lang');
                var container = document.getElementById('seo-questions-' + lang);
                var count = container.querySelectorAll('.seo-question-group').length;
                var wrapper = document.createElement('div');

                wrapper.className = 'seo-question-group border rounded p-3 mb-3';
                wrapper.innerHTML =
                    '<div class="form-group">' +
                        '<label class="font-weight-bold">Question</label>' +
                        '<input type="text" name="seo_questions[' + lang + '][' + count + '][question]" class="form-control">' +
                    '</div>' +
                    '<div class="form-group mb-2">' +
                        '<label class="font-weight-bold">Answer</label>' +
                        '<textarea name="seo_questions[' + lang + '][' + count + '][answer]" class="form-control" rows="3"></textarea>' +
                    '</div>' +
                    '<button type="button" class="btn btn-sm btn-outline-danger remove-seo-question">Remove</button>';

                container.appendChild(wrapper);
            });
        });

        document.addEventListener('click', function (event) {
            if (!event.target.classList.contains('remove-seo-question')) {
                return;
            }

            var group = event.target.closest('.seo-question-group');
            if (!group) {
                return;
            }

            var container = group.parentElement;
            var groups = container.querySelectorAll('.seo-question-group');
            if (groups.length === 1) {
                group.querySelectorAll('input, textarea').forEach(function (field) {
                    field.value = '';
                });
                return;
            }

            group.remove();
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\homes\edit.blade.php ENDPATH**/ ?>