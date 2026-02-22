<?php $__env->startSection('title', 'Edit ' . $modelName); ?>

<?php $__env->startSection('page-title'); ?>
    <?php echo e(isset($item) ? __('Edit :entity', ['entity' => $modelName]) : __('Add :entity', ['entity' => $modelName])); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('includes.admin.form_theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php $__env->startSection('content'); ?>

    <?php
        $languageCount = isset($activeLanguages) ? $activeLanguages->count() : 0;
        $formStats = [];
        if ($languageCount) {
            $formStats[] = ['icon' => 'fas fa-language', 'label' => $languageCount . ' ' . __('Locales')];
        }
        $formStats[] = ['icon' => 'fas fa-layer-group', 'label' => __('Guided workflow')];
        $formStats[] = ['icon' => 'fas fa-save', 'label' => __('Content safety')];
        $formTitle = isset($item)
            ? __('Update :entity', ['entity' => $modelName])
            : __('Add :entity', ['entity' => $modelName]);
        $formDescription = isset($item)
            ? __('Review the content, adjust translations and assets, then save confidently.')
            : __('Complete the details below to publish a polished entry.');
    ?>

    <?php echo $__env->make('includes.admin.form_header', [
        'title' => $formTitle,
        'description' => $formDescription,
        'stats' => $formStats
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- Display errors -->


    <div class="card form-card card-primary card-outline card-tabs shadow-lg">
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
                            <label for="symbol" class="font-weight-bold">Symbol</label>
                            <input type="text" name="symbol" class="form-control form-control-lg shadow-sm"
                                id="general_field" value="<?php echo e(old('symbol', $item->symbol)); ?>">
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label for="code" class="font-weight-bold">Code</label>
                                <input type="text" name="code" class="form-control form-control-lg shadow-sm" id="code"
                                    value="<?php echo e(old('code', $item->code)); ?>">
                            </div>
                            <div class="form-group">
                                <label for="exchange_rate" class="font-weight-bold">Exchange Rate</label>
                                <input type="number" name="exchange_rate" class="form-control form-control-lg shadow-sm"
                                    id="exchange_rate" value="<?php echo e(old('exchange_rate', $item->exchange_rate)); ?>">
                            </div>


                            <div class="form-group">
                                <label for="is_default" class="font-weight-bold">Default</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="is_default" class="custom-control-input" id="is_default"
                                        value="<?php echo e($item->is_default); ?>" <?php echo e($item->is_default ? 'checked' : ''); ?>>
                                    <label class="custom-control-label" for="is_default">Default</label>
                                </div>
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
<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\currencies\edit.blade.php ENDPATH**/ ?>