<?php $__env->startSection('title', 'Edit Page: ' . ucfirst($page->name)); ?>

<?php $__env->startSection('page-title'); ?>
    Edit Page: <strong><?php echo e(ucfirst($page->name)); ?></strong>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.pages.index')); ?>">Pages</a></li>
    <li class="breadcrumb-item active"><?php echo e(ucfirst($page->name)); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('admin.pages.index')); ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Pages
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .page-edit-card {
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .nav-tabs .nav-link {
        border-radius: 8px 8px 0 0;
        margin-right: 4px;
        transition: all 0.3s;
    }
    .nav-tabs .nav-link:hover {
        background-color: #f8f9fa;
    }
    .nav-tabs .nav-link.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }
    .language-pill {
        border-radius: 20px;
        padding: 6px 16px;
        font-weight: 500;
        transition: all 0.3s;
    }
    .language-pill.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    .form-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .form-section-title {
        font-size: 18px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #dee2e6;
    }
    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 40px;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        transition: all 0.3s;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }
    .border-left-primary {
        border-left: 4px solid #007bff !important;
    }
    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }
    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }
    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card page-edit-card">
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-file-alt text-primary mr-2"></i>
                        Edit Page Content
                    </h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.pages.update', $page->slug)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Main Tabs -->
                        <ul class="nav nav-tabs mb-4" id="mainTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab">
                                    <i class="fas fa-cog mr-2"></i>General
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="content-tab" data-toggle="tab" href="#content" role="tab">
                                    <i class="fas fa-language mr-2"></i>Content (Multi-language)
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" id="mainTabsContent">
                            <!-- General Tab -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel">
                                <div class="form-section">
                                    <h5 class="form-section-title">
                                        <i class="fas fa-info-circle mr-2"></i>Page Information
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Page Name</label>
                                                <input type="text" class="form-control" value="<?php echo e($page->name); ?>" disabled>
                                                <small class="form-text text-muted">This is the display name of the page</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Page Slug</label>
                                                <input type="text" class="form-control" value="<?php echo e($page->slug); ?>" disabled>
                                                <small class="form-text text-muted">URL identifier for this page</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="is_active" class="custom-control-input"
                                                   id="is_active" value="1"
                                                   <?php echo e(old('is_active', $page->is_active) ? 'checked' : ''); ?>>
                                            <label class="custom-control-label font-weight-bold" for="is_active">
                                                Active Status
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Toggle to enable/disable this page</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Content Tab (Multi-language) -->
                            <div class="tab-pane fade" id="content" role="tabpanel">
                                <!-- Language Pills -->
                                <div class="mb-4">
                                    <ul class="nav nav-pills" id="languageTabs" role="tablist">
                                        <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="nav-item mr-2 mb-2">
                                                <a class="nav-link language-pill <?php if($loop->first): ?> active <?php endif; ?>" 
                                                   id="lang-<?php echo e($lang->code); ?>-tab" 
                                                   data-toggle="pill" 
                                                   href="#lang-<?php echo e($lang->code); ?>" 
                                                   role="tab">
                                                    <i class="fas fa-globe mr-1"></i><?php echo e($lang->name); ?> (<?php echo e(strtoupper($lang->code)); ?>)
                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>

                                <!-- Language Content -->
                                <div class="tab-content" id="languageTabsContent">
                                    <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $translation = $page->translations->where('locale', $lang->code)->first();
                                        ?>
                                        <div class="tab-pane fade <?php if($loop->first): ?> show active <?php endif; ?>" 
                                             id="lang-<?php echo e($lang->code); ?>" 
                                             role="tabpanel">
                                            
                                            <div class="form-section">
                                                <h5 class="form-section-title">
                                                    <i class="fas fa-heading mr-2"></i>Page Content (<?php echo e($lang->name); ?>)
                                                </h5>

                                                <div class="form-group">
                                                    <label for="title_<?php echo e($lang->code); ?>" class="font-weight-bold">
                                                        Title <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" 
                                                           name="title[<?php echo e($lang->code); ?>]" 
                                                           id="title_<?php echo e($lang->code); ?>"
                                                           class="form-control form-control-lg <?php $__errorArgs = ['title.' . $lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                           value="<?php echo e(old('title.' . $lang->code, $translation->title ?? '')); ?>"
                                                           placeholder="Enter page title">
                                                    <?php $__errorArgs = ['title.' . $lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>

                                                <div class="form-group">
                                                    <label for="description_<?php echo e($lang->code); ?>" class="font-weight-bold">
                                                        Description
                                                    </label>
                                                    <textarea name="description[<?php echo e($lang->code); ?>]" 
                                                              id="description_<?php echo e($lang->code); ?>"
                                                              class="form-control <?php $__errorArgs = ['description.' . $lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                              rows="5"
                                                              placeholder="Enter main description"><?php echo e(old('description.' . $lang->code, $translation->description ?? '')); ?></textarea>
                                                    <?php $__errorArgs = ['description.' . $lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>

                                                <div class="form-group">
                                                    <label for="sub_description_<?php echo e($lang->code); ?>" class="font-weight-bold">
                                                        Sub Description
                                                    </label>
                                                    <textarea name="sub_description[<?php echo e($lang->code); ?>]" 
                                                              id="sub_description_<?php echo e($lang->code); ?>"
                                                              class="form-control <?php $__errorArgs = ['sub_description.' . $lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                              rows="4"
                                                              placeholder="Enter sub description"><?php echo e(old('sub_description.' . $lang->code, $translation->sub_description ?? '')); ?></textarea>
                                                    <?php $__errorArgs = ['sub_description.' . $lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>

                                            <?php if($page->slug === 'home'): ?>
                                            <!-- Sections (Only for Home Page) -->
                                            <div class="form-section mt-4">
                                                <h5 class="form-section-title">
                                                    <i class="fas fa-th-large mr-2"></i>Page Sections (<?php echo e($lang->name); ?>)
                                                </h5>

                                                <!-- Category Section -->
                                                <div class="card mb-3 border-left-primary">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0"><i class="fas fa-tags mr-2"></i>Category Section</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="category_section_title_<?php echo e($lang->code); ?>" class="font-weight-bold">
                                                                Category Section Title
                                                            </label>
                                                            <input type="text" 
                                                                   name="category_section_title[<?php echo e($lang->code); ?>]" 
                                                                   id="category_section_title_<?php echo e($lang->code); ?>"
                                                                   class="form-control"
                                                                   value="<?php echo e(old('category_section_title.' . $lang->code, $translation->category_section_title ?? '')); ?>"
                                                                   placeholder="Enter category section title">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="category_section_description_<?php echo e($lang->code); ?>" class="font-weight-bold">
                                                                Category Section Description
                                                            </label>
                                                            <textarea name="category_section_description[<?php echo e($lang->code); ?>]" 
                                                                      id="category_section_description_<?php echo e($lang->code); ?>"
                                                                      class="form-control"
                                                                      rows="3"
                                                                      placeholder="Enter category section description"><?php echo e(old('category_section_description.' . $lang->code, $translation->category_section_description ?? '')); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Brands Section -->
                                                <div class="card mb-3 border-left-info">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0"><i class="fas fa-industry mr-2"></i>Brands Section</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="brands_section_title_<?php echo e($lang->code); ?>" class="font-weight-bold">
                                                                Brands Section Title
                                                            </label>
                                                            <input type="text" 
                                                                   name="brands_section_title[<?php echo e($lang->code); ?>]" 
                                                                   id="brands_section_title_<?php echo e($lang->code); ?>"
                                                                   class="form-control"
                                                                   value="<?php echo e(old('brands_section_title.' . $lang->code, $translation->brands_section_title ?? '')); ?>"
                                                                   placeholder="Enter brands section title">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="brands_section_description_<?php echo e($lang->code); ?>" class="font-weight-bold">
                                                                Brands Section Description
                                                            </label>
                                                            <textarea name="brands_section_description[<?php echo e($lang->code); ?>]" 
                                                                      id="brands_section_description_<?php echo e($lang->code); ?>"
                                                                      class="form-control"
                                                                      rows="3"
                                                                      placeholder="Enter brands section description"><?php echo e(old('brands_section_description.' . $lang->code, $translation->brands_section_description ?? '')); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Special Offers Section -->
                                                <div class="card mb-3 border-left-warning">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0"><i class="fas fa-percent mr-2"></i>Special Offers Section</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="special_offers_title_<?php echo e($lang->code); ?>" class="font-weight-bold">
                                                                Special Offers Title
                                                            </label>
                                                            <input type="text" 
                                                                   name="special_offers_title[<?php echo e($lang->code); ?>]" 
                                                                   id="special_offers_title_<?php echo e($lang->code); ?>"
                                                                   class="form-control"
                                                                   value="<?php echo e(old('special_offers_title.' . $lang->code, $translation->special_offers_title ?? '')); ?>"
                                                                   placeholder="Enter special offers title">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="special_offers_description_<?php echo e($lang->code); ?>" class="font-weight-bold">
                                                                Special Offers Description
                                                            </label>
                                                            <textarea name="special_offers_description[<?php echo e($lang->code); ?>]" 
                                                                      id="special_offers_description_<?php echo e($lang->code); ?>"
                                                                      class="form-control"
                                                                      rows="3"
                                                                      placeholder="Enter special offers description"><?php echo e(old('special_offers_description.' . $lang->code, $translation->special_offers_description ?? '')); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Only on Us Section -->
                                                <div class="card mb-3 border-left-success">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0"><i class="fas fa-star mr-2"></i>Only on Us Section</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="only_on_us_title_<?php echo e($lang->code); ?>" class="font-weight-bold">
                                                                Only on Us Title
                                                            </label>
                                                            <input type="text" 
                                                                   name="only_on_us_title[<?php echo e($lang->code); ?>]" 
                                                                   id="only_on_us_title_<?php echo e($lang->code); ?>"
                                                                   class="form-control"
                                                                   value="<?php echo e(old('only_on_us_title.' . $lang->code, $translation->only_on_us_title ?? '')); ?>"
                                                                   placeholder="Enter only on us title">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="only_on_us_description_<?php echo e($lang->code); ?>" class="font-weight-bold">
                                                                Only on Us Description
                                                            </label>
                                                            <textarea name="only_on_us_description[<?php echo e($lang->code); ?>]" 
                                                                      id="only_on_us_description_<?php echo e($lang->code); ?>"
                                                                      class="form-control"
                                                                      rows="3"
                                                                      placeholder="Enter only on us description"><?php echo e(old('only_on_us_description.' . $lang->code, $translation->only_on_us_description ?? '')); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-save btn-lg text-white">
                                <i class="fas fa-save mr-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\pages\edit.blade.php ENDPATH**/ ?>