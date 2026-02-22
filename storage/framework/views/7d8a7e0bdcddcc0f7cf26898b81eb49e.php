<?php $__env->startSection('title', 'Edit ' . $modelName); ?>

<?php $__env->startSection('page-title'); ?>
    Edit <?php echo e($modelName); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .select2-container .select2-selection--multiple .select2-selection__choice {
            display: flex;
            align-items: center;
        }

        .select2-container .select2-selection--multiple .select2-selection__choice img {
            margin-right: 5px;
        }
    </style>
    <!-- Display errors -->


    <div class="card card-primary card-outline card-tabs shadow-lg">
        <div class="card-header p-0 pt-1 border-bottom-0 bg-light">
            <!-- Tabs Header -->
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active text-dark" id="custom-tabs-general-tab" data-toggle="pill"
                        href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-general" aria-selected="true">
                        <i class="fas fa-info-circle"></i> General Data
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" id="custom-tabs-translated-tab" data-toggle="pill"
                        href="#custom-tabs-translated" role="tab" aria-controls="custom-tabs-translated"
                        aria-selected="false">
                        <i class="fas fa-language"></i> Translated Data
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" id="custom-tabs-seo-tab" data-toggle="pill" href="#custom-tabs-seo"
                        role="tab" aria-controls="custom-tabs-seo" aria-selected="false">
                        <i class="fas fa-search"></i> SEO Data
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <!-- Form -->
            <form action="<?php echo e(route('admin.' . $modelName . '.update', $item->id)); ?>" method="POST"
                enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="tab-content" id="custom-tabs-three-tabContent">
                    <!-- General Data Tab Content -->
                    <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel"
                        aria-labelledby="custom-tabs-general-tab">

                        <input type="hidden" name="page_name" class="form-control shadow-sm" id="page_name"
                            value="<?php echo e(old('page_name', $item->page_name)); ?>">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <!-- Video Preview with Placeholder -->
                                    <div class="mb-3">
                                        <video id="videoPreview_hero_header_video_path" controls
                                            style="height: 300px; width: 100%; object-fit: cover; border: 2px solid #ddd;">
                                            <source
                                                src="<?php echo e($item->hero_header_video_path ? asset('storage/' . $item->hero_header_video_path) : 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'400\' viewBox=\'0 0 400 400\'%3E%3Crect width=\'100%25\' height=\'100%25\' fill=\'%23ddd\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' fill=\'%23555\' font-size=\'20\' text-anchor=\'middle\' dy=\'.3em\'%3E400x300%3C/text%3E%3C/svg%3E'); ?>"
                                                type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>

                                    <!-- File Input for Video Upload -->
                                    <div class="custom-file">
                                        <input type="file" name="hero_header_video_path"
                                            class="custom-file-input video-upload <?php $__errorArgs = ['hero_header_video_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="video_path" data-preview="videoPreview_hero_header_video_path"
                                            accept="video/*">
                                        <label class="custom-file-label" for="hero_header_video_path">Upload Hero Header
                                            Video</label>
                                    </div>

                                    <!-- Error Handling -->
                                    <?php $__errorArgs = ['hero_header_video_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <!-- Full-Screen Image Preview with Placeholder -->
                                    <div class="mb-3 full-screen-image-container">
                                        <img id="imagePreview_hero_header_image_path"
                                            src="<?php echo e($item->hero_header_image_path ? asset('storage/' . $item->hero_header_image_path) : 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'400\' viewBox=\'0 0 400 400\'%3E%3Crect width=\'100%25\' height=\'100%25\' fill=\'%23ddd\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' fill=\'%23555\' font-size=\'20\' text-anchor=\'middle\' dy=\'.3em\'%3E1024x720%3C/text%3E%3C/svg%3E'); ?>"
                                            class="img-fluid full-screen-image" alt="Hero Header Image">
                                    </div>

                                    <!-- File Input for Image Upload -->
                                    <div class="custom-file">
                                        <input type="file" name="hero_header_image_path"
                                            class="custom-file-input image-upload <?php $__errorArgs = ['hero_header_image_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="hero_header_image_path" data-preview="imagePreview_hero_header_image_path"
                                            accept="image/*">
                                        <label class="custom-file-label" for="hero_header_image_path">Upload Hero Header
                                            Image</label>
                                    </div>

                                    <!-- Error Handling -->
                                    <?php $__errorArgs = ['hero_header_image_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                        </div>



                        <div class="form-group">
                            <label for="hero_type">Hero Section Video or Image ?</label>
                            <select name="hero_type" id="car_maker_id"
                                class="form-control <?php $__errorArgs = ['hero_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Select type</option>
                                <option value="video" <?php if($item->hero_type == 'video'): ?> selected <?php endif; ?>>Video</option>
                                <option value="image" <?php if($item->hero_type == 'image'): ?> selected <?php endif; ?>>Image</option>
                            </select>
                            <?php $__errorArgs = ['hero_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="is_active" class="font-weight-bold">Active</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_active" class="custom-control-input" id="is_active"
                                    value="<?php echo e($item->is_active); ?>" <?php echo e($item->is_active ? 'checked' : ''); ?>>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>

                    <!-- Translated Data Tab Content with Sub-tabs for Languages -->
                    <div class="tab-pane fade" id="custom-tabs-translated" role="tabpanel"
                        aria-labelledby="custom-tabs-translated-tab">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php if($loop->first): ?> active <?php endif; ?> bg-light text-dark"
                                        id="pills-<?php echo e($lang->code); ?>-tab" data-toggle="pill" href="#pills-<?php echo e($lang->code); ?>"
                                        role="tab" aria-controls="pills-<?php echo e($lang->code); ?>"
                                        aria-selected="true"><?php echo e($lang->name); ?></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <div class="tab-content shadow-sm p-3 mb-4 bg-white rounded" id="pills-tabContent">
                            <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $translation = $item->translations->where('locale', $lang->code)->first();
                                ?>
                                <div class="tab-pane fade <?php if($loop->first): ?> show active <?php endif; ?>" id="pills-<?php echo e($lang->code); ?>"
                                    role="tabpanel" aria-labelledby="pills-<?php echo e($lang->code); ?>-tab">

                                    <div class="form-group">
                                        <label for="hero_header_title_[<?php echo e($lang->code); ?>]" class="font-weight-bold">Hero Header
                                            Title (<?php echo e($lang->name); ?>)</label>
                                        <input type="text" name="hero_header_title[<?php echo e($lang->code); ?>]"
                                            class="form-control shadow-sm" id="hero_header_title_[<?php echo e($lang->code); ?>]"
                                            value="<?php echo e(old('hero_header_title.' . $lang->code, $translation->hero_header_title ?? '')); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="car_only_section_title_[<?php echo e($lang->code); ?>]" class="font-weight-bold">Car
                                            Only Section Title (<?php echo e($lang->name); ?>)</label>
                                        <input type="text" name="car_only_section_title[<?php echo e($lang->code); ?>]"
                                            class="form-control shadow-sm" id="car_only_section_title_[<?php echo e($lang->code); ?>]"
                                            value="<?php echo e(old('car_only_section_title.' . $lang->code, $translation->car_only_section_title ?? '')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="car_only_section_paragraph_<?php echo e($lang->code); ?>" class="font-weight-bold">Car
                                            Only Section Paragraph (<?php echo e($lang->name); ?>)</label>
                                        <textarea name="car_only_section_paragraph[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm "
                                            id="car_only_section_paragraph_<?php echo e($lang->code); ?>"
                                            rows="4"><?php echo e(old('car_only_section_paragraph.' . $lang->code, $translation->car_only_section_paragraph ?? '')); ?></textarea>
                                    </div>


                                    <div class="form-group">
                                        <label for="special_offers_section_title_[<?php echo e($lang->code); ?>]"
                                            class="font-weight-bold">Special Offers Section Title (<?php echo e($lang->name); ?>)</label>
                                        <input type="text" name="special_offers_section_title[<?php echo e($lang->code); ?>]"
                                            class="form-control shadow-sm" id="special_offers_section_title_[<?php echo e($lang->code); ?>]"
                                            value="<?php echo e(old('special_offers_section_title.' . $lang->code, $translation->special_offers_section_title ?? '')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="special_offers_section_paragraph_<?php echo e($lang->code); ?>"
                                            class="font-weight-bold">Special Offers Section Paragraph
                                            (<?php echo e($lang->name); ?>)</label>
                                        <textarea name="special_offers_section_paragraph[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm"
                                            id="special_offers_section_paragraph_<?php echo e($lang->code); ?>"
                                            rows="4"><?php echo e(old('special_offers_section_paragraph.' . $lang->code, $translation->special_offers_section_paragraph ?? '')); ?></textarea>
                                    </div>


                                    <div class="form-group">
                                        <label for="why_choose_us_section_title_[<?php echo e($lang->code); ?>]"
                                            class="font-weight-bold">Why Choose Us Section Title (<?php echo e($lang->name); ?>)</label>
                                        <input type="text" name="why_choose_us_section_title[<?php echo e($lang->code); ?>]"
                                            class="form-control shadow-sm" id="why_choose_us_section_title_[<?php echo e($lang->code); ?>]"
                                            value="<?php echo e(old('why_choose_us_section_title.' . $lang->code, $translation->why_choose_us_section_title ?? '')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="why_choose_us_section_paragraph_<?php echo e($lang->code); ?>"
                                            class="font-weight-bold">Why Choose Us Section Paragraph(<?php echo e($lang->name); ?>)</label>
                                        <textarea name="why_choose_us_section_paragraph[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm"
                                            id="why_choose_us_section_paragraph_<?php echo e($lang->code); ?>"
                                            rows="4"><?php echo e(old('why_choose_us_section_paragraph.' . $lang->code, $translation->why_choose_us_section_paragraph ?? '')); ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="faq_section_title_[<?php echo e($lang->code); ?>]" class="font-weight-bold">FAQ Section
                                            Title (<?php echo e($lang->name); ?>)</label>
                                        <input type="text" name="faq_section_title[<?php echo e($lang->code); ?>]"
                                            class="form-control shadow-sm" id="faq_section_title_[<?php echo e($lang->code); ?>]"
                                            value="<?php echo e(old('faq_section_title.' . $lang->code, $translation->faq_section_title ?? '')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="faq_section_paragraph_<?php echo e($lang->code); ?>" class="font-weight-bold">FAQ
                                            Section Paragraph(<?php echo e($lang->name); ?>)</label>
                                        <textarea name="faq_section_paragraph[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm"
                                            id="faq_section_paragraph_<?php echo e($lang->code); ?>"
                                            rows="4"><?php echo e(old('faq_section_paragraph.' . $lang->code, $translation->faq_section_paragraph ?? '')); ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="contact_us_title_[<?php echo e($lang->code); ?>]" class="font-weight-bold">Contact Us
                                            Section Title (<?php echo e($lang->name); ?>)</label>
                                        <input type="text" name="contact_us_title[<?php echo e($lang->code); ?>]"
                                            class="form-control shadow-sm" id="contact_us_title_[<?php echo e($lang->code); ?>]"
                                            value="<?php echo e(old('contact_us_title.' . $lang->code, $translation->contact_us_title ?? '')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="contact_us_paragraph_<?php echo e($lang->code); ?>" class="font-weight-bold">Contact Us
                                            Section Paragraph(<?php echo e($lang->name); ?>)</label>
                                        <textarea name="contact_us_paragraph[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm"
                                            id="contact_us_paragraph_<?php echo e($lang->code); ?>"
                                            rows="4"><?php echo e(old('contact_us_paragraph.' . $lang->code, $translation->contact_us_paragraph ?? '')); ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="blog_section_title_[<?php echo e($lang->code); ?>]" class="font-weight-bold">Blog
                                            Section Title (<?php echo e($lang->name); ?>)</label>
                                        <input type="text" name="blog_section_title[<?php echo e($lang->code); ?>]"
                                            class="form-control shadow-sm" id="blog_section_title_[<?php echo e($lang->code); ?>]"
                                            value="<?php echo e(old('blog_section_title.' . $lang->code, $translation->blog_section_title ?? '')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="blog_section_paragraph_<?php echo e($lang->code); ?>" class="font-weight-bold">Blog
                                            Section Paragraph(<?php echo e($lang->name); ?>)</label>
                                        <textarea name="blog_section_paragraph[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm"
                                            id="blog_section_paragraph_<?php echo e($lang->code); ?>"
                                            rows="4"><?php echo e(old('blog_section_paragraph.' . $lang->code, $translation->blog_section_paragraph ?? '')); ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="contact_us_detail_title_[<?php echo e($lang->code); ?>]" class="font-weight-bold">FAQ
                                            Section Title (<?php echo e($lang->name); ?>)</label>
                                        <input type="text" name="contact_us_detail_title[<?php echo e($lang->code); ?>]"
                                            class="form-control shadow-sm" id="contact_us_detail_title_[<?php echo e($lang->code); ?>]"
                                            value="<?php echo e(old('contact_us_detail_title.' . $lang->code, $translation->contact_us_detail_title ?? '')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="contact_us_detail_paragraph_<?php echo e($lang->code); ?>" class="font-weight-bold">FAQ
                                            Section Paragraph(<?php echo e($lang->name); ?>)</label>
                                        <textarea name="contact_us_detail_paragraph[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm"
                                            id="contact_us_detail_paragraph_<?php echo e($lang->code); ?>"
                                            rows="4"><?php echo e(old('contact_us_detail_paragraph.' . $lang->code, $translation->contact_us_detail_paragraph ?? '')); ?></textarea>
                                    </div>






                                    <div class="form-group">
                                        <label for="where_find_us_section_title_[<?php echo e($lang->code); ?>]"
                                            class="font-weight-bold">Where Find Us Section Title (<?php echo e($lang->name); ?>)</label>
                                        <input type="text" name="where_find_us_section_title[<?php echo e($lang->code); ?>]"
                                            class="form-control shadow-sm" id="where_find_us_section_title_[<?php echo e($lang->code); ?>]"
                                            value="<?php echo e(old('where_find_us_section_title.' . $lang->code, $translation->where_find_us_section_title ?? '')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="where_find_us_section_paragraph_<?php echo e($lang->code); ?>"
                                            class="font-weight-bold">Where Find Us Section Paragraph (<?php echo e($lang->name); ?>)</label>
                                        <textarea name="where_find_us_section_paragraph[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm"
                                            id="where_find_us_section_paragraph_<?php echo e($lang->code); ?>"
                                            rows="4"><?php echo e(old('where_find_us_section_paragraph.' . $lang->code, $translation->where_find_us_section_paragraph ?? '')); ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="required_documents_section_title_[<?php echo e($lang->code); ?>]"
                                            class="font-weight-bold">Required Documents Section Title
                                            (<?php echo e($lang->name); ?>)</label>
                                        <input type="text" name="required_documents_section_title[<?php echo e($lang->code); ?>]"
                                            class="form-control shadow-sm"
                                            id="required_documents_section_title_[<?php echo e($lang->code); ?>]"
                                            value="<?php echo e(old('required_documents_section_title.' . $lang->code, $translation->required_documents_section_title ?? '')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="required_documents_section_paragraph_<?php echo e($lang->code); ?>"
                                            class="font-weight-bold">Required Documents Section Paragraph
                                            (<?php echo e($lang->name); ?>)</label>
                                        <textarea name="required_documents_section_paragraph[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm"
                                            id="required_documents_section_paragraph_<?php echo e($lang->code); ?>"
                                            rows="4"><?php echo e(old('required_documents_section_paragraph.' . $lang->code, $translation->required_documents_section_paragraph ?? '')); ?></textarea>
                                    </div>


                                    <div class="form-group">
                                        <label for="instagram_section_title_[<?php echo e($lang->code); ?>]"
                                            class="font-weight-bold">Instagram Section Title (<?php echo e($lang->name); ?>)</label>
                                        <input type="text" name="instagram_section_title[<?php echo e($lang->code); ?>]"
                                            class="form-control shadow-sm" id=instagram_section_title_[<?php echo e($lang->code); ?>]"
                                            value="<?php echo e(old('instagram_section_title.' . $lang->code, $translation->instagram_section_title ?? '')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="instagram_section_paragraph_<?php echo e($lang->code); ?>"
                                            class="font-weight-bold">Instagram Section Paragraph (<?php echo e($lang->name); ?>)</label>
                                        <textarea name="instagram_section_paragraph[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm "
                                            id="instagram_section_paragraph_<?php echo e($lang->code); ?>"
                                            rows="4"><?php echo e(old('instagram_section_paragraph.' . $lang->code, $translation->instagram_section_paragraph ?? '')); ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="category_section_title_[<?php echo e($lang->code); ?>]"
                                            class="font-weight-bold">Category Section Title (<?php echo e($lang->name); ?>)</label>
                                        <input type="text" name="category_section_title[<?php echo e($lang->code); ?>]"
                                            class="form-control shadow-sm" id=category_section_title_[<?php echo e($lang->code); ?>]"
                                            value="<?php echo e(old('category_section_title.' . $lang->code, $translation->category_section_title ?? '')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="category_section_paragraph_<?php echo e($lang->code); ?>"
                                            class="font-weight-bold">Category Section Paragraph (<?php echo e($lang->name); ?>)</label>
                                        <textarea name="category_section_paragraph[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm"
                                            id="category_section_paragraph_<?php echo e($lang->code); ?>"
                                            rows="4"><?php echo e(old('category_section_paragraph.' . $lang->code, $translation->category_section_paragraph ?? '')); ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="brand_section_title_[<?php echo e($lang->code); ?>]" class="font-weight-bold">Brand
                                            Section Title (<?php echo e($lang->name); ?>)</label>
                                        <input type="text" name="brand_section_title[<?php echo e($lang->code); ?>]"
                                            class="form-control shadow-sm" id=brand_section_title_[<?php echo e($lang->code); ?>]"
                                            value="<?php echo e(old('brand_section_title.' . $lang->code, $translation->brand_section_title ?? '')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="brand_section_paragraph_<?php echo e($lang->code); ?>" class="font-weight-bold">Brand
                                            Section Paragraph (<?php echo e($lang->name); ?>)</label>
                                        <textarea name="brand_section_paragraph[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm"
                                            id="brand_section_paragraph_<?php echo e($lang->code); ?>"
                                            rows="4"><?php echo e(old('brand_section_paragraph.' . $lang->code, $translation->brand_section_paragraph ?? '')); ?></textarea>
                                    </div>


                                    <div class="form-group">
                                        <label for="footer_section_paragraph_<?php echo e($lang->code); ?>" class="font-weight-bold">Footer
                                            Section Paragraph (<?php echo e($lang->name); ?>)</label>
                                        <textarea name="footer_section_paragraph[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm"
                                            id="footer_section_paragraph_<?php echo e($lang->code); ?>"
                                            rows="4"><?php echo e(old('footer_section_paragraph.' . $lang->code, $translation->footer_section_paragraph ?? '')); ?></textarea>
                                    </div>


                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- SEO Data Tab Content -->
                    <div class="tab-pane fade" id="custom-tabs-seo" role="tabpanel" aria-labelledby="custom-tabs-seo-tab">
                        <ul class="nav nav-pills mb-3" id="pills-seo-tab" role="tablist">
                            <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php if($loop->first): ?> active <?php endif; ?> bg-light text-dark"
                                        id="pills-seo-<?php echo e($lang->code); ?>-tab" data-toggle="pill"
                                        href="#pills-seo-<?php echo e($lang->code); ?>" role="tab"
                                        aria-controls="pills-seo-<?php echo e($lang->code); ?>" aria-selected="true"><?php echo e($lang->name); ?></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <div class="tab-content shadow-sm p-3 mb-4 bg-white rounded" id="pills-seo-tabContent">
                            <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $translation = $item->translations->where('locale', $lang->code)->first();
                                ?>
                                <div class="tab-pane fade <?php if($loop->first): ?> show active <?php endif; ?>" id="pills-seo-<?php echo e($lang->code); ?>"
                                    role="tabpanel" aria-labelledby="pills-seo-<?php echo e($lang->code); ?>-tab">
                                    <div class="form-group">
                                        <label for="meta_title_<?php echo e($lang->code); ?>" class="font-weight-bold">Meta Title
                                            (<?php echo e($lang->name); ?>)</label>
                                        <input type="text" name="meta_title[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm" id="meta_title_<?php echo e($lang->code); ?>"
                                            value="<?php echo e(old('meta_title.' . $lang->code, $translation->meta_title ?? '')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_description_<?php echo e($lang->code); ?>" class="font-weight-bold">Meta
                                            Description (<?php echo e($lang->name); ?>)</label>
                                        <textarea name="meta_description[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm"
                                            id="meta_description_<?php echo e($lang->code); ?>"
                                            rows="3"><?php echo e(old('meta_description.' . $lang->code, $translation->meta_description ?? '')); ?></textarea>
                                    </div>
                                    <!-- Meta Keywords Field -->
                                    <!-- Meta Keywords Field -->
                                    <div class="form-group">
                                        <label for="meta_keywords_<?php echo e($lang->code); ?>" class="font-weight-bold">Meta Keywords
                                            (<?php echo e($lang->name); ?>)</label>

                                        <?php
                                            $metaKeywordsJson = $translation?->meta_keywords ?? '[]';
                                            $metaKeywords = json_decode($metaKeywordsJson, true);

                                            $keywordString = '';
                                            if (is_array($metaKeywords) && !empty($metaKeywords)) {
                                                if (isset($metaKeywords[0]) && is_array($metaKeywords[0]) && isset($metaKeywords[0]['value'])) {
                                                    $keywordString = implode(',', array_column($metaKeywords, 'value'));
                                                } else {
                                                    $keywordString = implode(',', $metaKeywords);
                                                }
                                            } elseif (is_string($metaKeywordsJson) && !empty($metaKeywordsJson) && $metaKeywordsJson !== '[]') {
                                                $keywordString = $metaKeywordsJson;
                                            }
                                        ?>

                                        <input type="text" name="meta_keywords[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm" id="meta_keywords_<?php echo e($lang->code); ?>"
                                            value="<?php echo e(old('meta_keywords.' . $lang->code, $keywordString)); ?>"
                                            data-role="tagsinput" placeholder="Enter meta keywords">
                                    </div>

                                    <div class="row card">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="robots_index_<?php echo e($lang->code); ?>" class="font-weight-bold">
                                                    Robot Index (<?php echo e($lang->name); ?>)
                                                </label>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" name="robots_index[<?php echo e($lang->code); ?>]"
                                                        class="custom-control-input" id="robots_index_<?php echo e($lang->code); ?>"
                                                        value="index" <?php echo e(old('meta_title.' . $lang->code, $translation->robots_index ?? '') === 'index' ? 'checked' : ''); ?>>
                                                    <label class="custom-control-label"
                                                        for="robots_index_<?php echo e($lang->code); ?>">Index</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="robots_follow_<?php echo e($lang->code); ?>" class="font-weight-bold">
                                                    Robot Follow (<?php echo e($lang->name); ?>)
                                                </label>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" name="robots_follow[<?php echo e($lang->code); ?>]"
                                                        class="custom-control-input" id="robots_follow_<?php echo e($lang->code); ?>"
                                                        value="follow" <?php echo e(old('meta_title.' . $lang->code, $translation->robots_follow ?? '') === 'follow' ? 'checked' : ''); ?>>
                                                    <label class="custom-control-label"
                                                        for="robots_follow_<?php echo e($lang->code); ?>">Follow</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dynamic SEO Questions/Answers Section -->
                                    <div class="seo-questions-container" id="seo-questions-<?php echo e($lang->code); ?>">
                                        <label class="font-weight-bold">SEO Questions/Answers (<?php echo e($lang->name); ?>)</label>
                                        <?php $__currentLoopData = $item->seoQuestions->where('locale', $lang->code); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $seoQuestion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                                                <div class="form-group">
                                                    <input type="text"
                                                        name="seo_questions[<?php echo e($lang->code); ?>][<?php echo e($index); ?>][question]"
                                                        class="form-control form-control-lg shadow-sm mb-2"
                                                        value="<?php echo e(old('seo_questions.' . $lang->code . '.' . $index . '.question', $seoQuestion->question_text)); ?>"
                                                        placeholder="Enter Question" />
                                                </div>
                                                <div class="form-group">
                                                    <textarea name="seo_questions[<?php echo e($lang->code); ?>][<?php echo e($index); ?>][answer]"
                                                        class="form-control form-control-lg shadow-sm"
                                                        placeholder="Enter Answer"><?php echo e(old('seo_questions.' . $lang->code . '.' . $index . '.answer', $seoQuestion->answer_text)); ?></textarea>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <button type="button" class="btn btn-info add-question mt-3" data-lang="<?php echo e($lang->code); ?>">
                                        <i class="fas fa-plus"></i> Add Question
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success btn-lg mt-3">
                    <i class="fas fa-save"></i> Update
                </button>
            </form>
        </div>
</div><?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.querySelectorAll('.video-upload').forEach(input => {
            input.addEventListener('change', function (event) {
                const previewId = this.getAttribute('data-preview');
                const file = event.target.files[0];
                if (file && file.type.includes('video')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const videoElement = document.getElementById(previewId);
                        videoElement.src = e.target.result;
                        videoElement.load();
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        $(document).ready(function () {
            // Function to dynamically add SEO Questions/Answers
            $('.add-question').on('click', function () {
                var lang = $(this).data('lang');
                var container = $('#seo-questions-' + lang);
                var count = container.find('.seo-question-group').length;
                var newQuestionGroup = `
                        <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                            <div class="form-group">
                                <input type="text" name="seo_questions[` + lang + `][` + count + `][question]" class="form-control form-control-lg shadow-sm mb-2" placeholder="Enter Question" />
                            </div>
                            <div class="form-group">
                                <textarea name="seo_questions[` + lang + `][` + count + `][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer"></textarea>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                        </div>`;
                container.append(newQuestionGroup);
            });

            // Function to remove an SEO Question/Answer
            $(document).on('click', '.remove-question', function () {
                $(this).closest('.seo-question-group').remove();
            });

            $('[data-toggle="tooltip"]').tooltip();
        });


        $(document).ready(function () {
            <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                var metaKeywordsInput = document.querySelector('#meta_keywords_<?php echo e($lang->code); ?>');
                if (metaKeywordsInput) {
                    new Tagify(metaKeywordsInput, {
                        placeholder: 'Enter meta keywords'
                    });
                }
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            });


    </script>
    <script>
        $(document).ready(function () {
            function formatCar(car) {
                if (!car.id) {
                    return car.text;
                }

                var $car = $(
                    '<span><img src="' + $(car.element).data('image') + '" style="width: 60px; height: 40px;" /> ' +
                    $(car.element).text() + '</span>'
                );
                return $car;
            }

            $('.car-select').select2({
                templateResult: formatCar,
                templateSelection: formatCar,
                allowClear: true,
                placeholder: "Select cars"
            });
        });
    </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\homes\edit.blade.php ENDPATH**/ ?>