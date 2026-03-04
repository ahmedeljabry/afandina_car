<?php $__env->startSection('title', 'Edit ' . $modelName); ?>

<?php $__env->startSection('page-title'); ?>
    Edit <?php echo e($modelName); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?><!-- Display errors -->


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
                        <div class="form-group">
                            <label for="brand_id" class="font-weight-bold">Brand</label>
                            <select name="brand_id" id="brand_id" class="form-control form-control-lg shadow-sm">
                                <option value="">-- Select Brand --</option>
                                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($brand->id); ?>" <?php echo e($item->brand_id == $brand->id ? 'selected' : ''); ?>>
                                        <?php echo e($brand->translations()->first()->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
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
                                        <label for="name_<?php echo e($lang->code); ?>" class="font-weight-bold">Name
                                            (<?php echo e($lang->name); ?>)</label>
                                        <input type="text" name="name[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm" id="name_<?php echo e($lang->code); ?>"
                                            value="<?php echo e(old('name.' . $lang->code, $translation->name ?? '')); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="description_<?php echo e($lang->code); ?>" class="font-weight-bold">Description
                                            (<?php echo e($lang->name); ?>)</label>
                                        <textarea name="description[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm" id="description_<?php echo e($lang->code); ?>"
                                            rows="4"><?php echo e(old('description.' . $lang->code, $translation->description ?? '')); ?></textarea>
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
                                    <div class="form-group">
                                        <label for="meta_keywords_<?php echo e($lang->code); ?>" class="font-weight-bold">Meta Keywords
                                            (<?php echo e($lang->name); ?>)</label>

                                        <?php
                                            // Decode the JSON meta_keywords into an array
                                            $metaKeywords = json_decode($translation->meta_keywords ?? '[]', true);

                                            // Convert the array into a comma-separated string of keywords
                                            $keywordString = implode(',', array_column($metaKeywords, 'value'));
                                        ?>

                                        <input type="text" name="meta_keywords[<?php echo e($lang->code); ?>]"
                                            class="form-control form-control-lg shadow-sm" id="meta_keywords_<?php echo e($lang->code); ?>"
                                            value="<?php echo e(old('meta_keywords.' . $lang->code, $keywordString)); ?>"
                                            data-role="tagsinput" placeholder="Enter meta keywords">
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\templates\edit.blade.php ENDPATH**/ ?>