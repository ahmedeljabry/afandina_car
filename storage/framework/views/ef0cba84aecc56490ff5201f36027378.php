<?php $__env->startSection('title', 'Add ' . $modelName); ?>

<?php $__env->startSection('content'); ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="display-4">Add <?php echo e($modelName); ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.' . $modelName . '.index')); ?>" style="text-transform: capitalize;"><?php echo e($modelName); ?> List</a></li>
                            <li class="breadcrumb-item active" style="text-transform: capitalize;">Add <?php echo e($modelName); ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>



        <section class="content">
            <div class="container-fluid">

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm mt-3 p-4 rounded-lg" role="alert" style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle mr-2" style="font-size: 24px; color: #f44336;"></i>
                            <div class="flex-grow-1">
                                <strong>Oops! We found some issues:</strong>
                                <ol class="mt-2 mb-0 pl-4" style="list-style: decimal;">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ol>
                            </div>
                            <button type="button" class="close ml-3" data-dismiss="alert" aria-label="Close" style="font-size: 20px;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>



                <div class="card card-primary card-outline card-tabs shadow-lg">
                    <div class="card-header p-0 pt-1 border-bottom-0 bg-light">
                        <!-- Tabs Header -->
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <!-- General Data Tab -->
                            <li class="nav-item">
                                <a class="nav-link active text-dark" id="custom-tabs-general-tab" data-toggle="pill" href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-general" aria-selected="true">
                                    <i class="fas fa-info-circle"></i> General Data
                                </a>
                            </li>
                            <!-- Translated Data Tab -->
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="custom-tabs-translated-tab" data-toggle="pill" href="#custom-tabs-translated" role="tab" aria-controls="custom-tabs-translated" aria-selected="false">
                                    <i class="fas fa-language"></i> Translated Data
                                </a>
                            </li>
                            <!-- SEO Data Tab -->
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="custom-tabs-seo-tab" data-toggle="pill" href="#custom-tabs-seo" role="tab" aria-controls="custom-tabs-seo" aria-selected="false">
                                    <i class="fas fa-search"></i> SEO Data
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <!-- Form -->
                        <form action="<?php echo e(route('admin.'.$modelName.'.store')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <!-- General Data Tab Content -->
                                <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-general-tab">
                                    <div class="form-group">
                                        <label for="brand_id" class="font-weight-bold">Brand</label>
                                        <select name="brand_id" id="brand_id" class="form-control form-control-lg shadow-sm">
                                            <option value="">-- Select Brand --</option>
                                            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($brand->id); ?>" <?php echo e(old('brand_id') == $brand->id ? 'selected' : ''); ?>>
                                                    <?php echo e($brand->translations()->first()->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="is_active" class="font-weight-bold">Active</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" <?php echo e(old('is_active')); ?>>
                                            <label class="custom-control-label" for="is_active">Active</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Translated Data Tab Content with Sub-tabs for Languages -->
                                <div class="tab-pane fade" id="custom-tabs-translated" role="tabpanel" aria-labelledby="custom-tabs-translated-tab">
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?php if($loop->first): ?> active <?php endif; ?> bg-light text-dark" id="pills-<?php echo e($lang->code); ?>-tab" data-toggle="pill" href="#pills-<?php echo e($lang->code); ?>" role="tab" aria-controls="pills-<?php echo e($lang->code); ?>" aria-selected="true"><?php echo e($lang->name); ?></a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <div class="tab-content shadow-sm p-3 mb-4 bg-white rounded" id="pills-tabContent">
                                        <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="tab-pane fade <?php if($loop->first): ?> show active <?php endif; ?>" id="pills-<?php echo e($lang->code); ?>" role="tabpanel" aria-labelledby="pills-<?php echo e($lang->code); ?>-tab">
                                                <div class="form-group">
                                                    <label for="name_<?php echo e($lang->code); ?>" class="font-weight-bold">Name (<?php echo e($lang->name); ?>)</label>
                                                    <input type="text" name="name[<?php echo e($lang->code); ?>]" class="form-control form-control-lg shadow-sm" id="name_<?php echo e($lang->code); ?>" value="<?php echo e(old('name.'.$lang->code)); ?>">
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
                                                <a class="nav-link <?php if($loop->first): ?> active <?php endif; ?> bg-light text-dark" id="pills-seo-<?php echo e($lang->code); ?>-tab" data-toggle="pill" href="#pills-seo-<?php echo e($lang->code); ?>" role="tab" aria-controls="pills-seo-<?php echo e($lang->code); ?>" aria-selected="true"><?php echo e($lang->name); ?></a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <div class="tab-content shadow-sm p-3 mb-4 bg-white rounded" id="pills-seo-tabContent">
                                        <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="tab-pane fade <?php if($loop->first): ?> show active <?php endif; ?>" id="pills-seo-<?php echo e($lang->code); ?>" role="tabpanel" aria-labelledby="pills-seo-<?php echo e($lang->code); ?>-tab">
                                                <div class="form-group">
                                                    <label for="meta_title_<?php echo e($lang->code); ?>" class="font-weight-bold">Meta Title (<?php echo e($lang->name); ?>)</label>
                                                    <input type="text" name="meta_title[<?php echo e($lang->code); ?>]" class="form-control form-control-lg shadow-sm" id="meta_title_<?php echo e($lang->code); ?>" value="<?php echo e(old('meta_title.'.$lang->code)); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="meta_description_<?php echo e($lang->code); ?>" class="font-weight-bold">Meta Description (<?php echo e($lang->name); ?>)</label>
                                                    <textarea name="meta_description[<?php echo e($lang->code); ?>]" class="form-control form-control-lg shadow-sm" id="meta_description_<?php echo e($lang->code); ?>" rows="3"><?php echo e(old('meta_description.'.$lang->code)); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="meta_keywords_<?php echo e($lang->code); ?>" class="font-weight-bold">Meta Keywords (<?php echo e($lang->name); ?>)</label>
                                                    <input type="text" name="meta_keywords[<?php echo e($lang->code); ?>]" class="form-control form-control-lg shadow-sm" id="meta_keywords_<?php echo e($lang->code); ?>" data-role="tagsinput" value="<?php echo e(old('meta_keywords.'.$lang->code)); ?>">
                                                </div>

                                                <!-- Dynamic SEO Questions/Answers Section -->
                                                <div class="seo-questions-container" id="seo-questions-<?php echo e($lang->code); ?>">
                                                    <label class="font-weight-bold">SEO Questions/Answers (<?php echo e($lang->name); ?>)</label>
                                                    <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                                                        <div class="form-group">
                                                            <input type="text" name="seo_questions[<?php echo e($lang->code); ?>][0][question]" class="form-control form-control-lg shadow-sm mb-2" placeholder="Enter Question" />
                                                        </div>
                                                        <div class="form-group">
                                                            <textarea name="seo_questions[<?php echo e($lang->code); ?>][0][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer"></textarea>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                                                    </div>
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
                                <i class="fas fa-save"></i> Save
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>

    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            // Function to dynamically add SEO Questions/Answers
            $('.add-question').on('click', function() {
                var lang = $(this).data('lang');
                var container = $('#seo-questions-' + lang);
                var count = container.find('.seo-question-group').length;
                console.log('Adding question for language:', lang, 'Count:', count); // Debugging line
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
            $(document).on('click', '.remove-question', function() {
                $(this).closest('.seo-question-group').remove();
            });


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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\car_rental-src-2025-11-05\car_rental\resources\views/pages/admin/car_models/create.blade.php ENDPATH**/ ?>