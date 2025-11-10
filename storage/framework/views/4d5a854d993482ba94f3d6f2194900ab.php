<?php
    $activeStep = $activeStep ?? 'details';
    $steps = [
        ['key' => 'list', 'icon' => 'icon-list', 'label' => __('Browse Cars')],
        ['key' => 'details', 'icon' => 'icon-info', 'label' => __('General Details')],
        ['key' => 'media', 'icon' => 'icon-picture', 'label' => __('Media & Gallery')],
        ['key' => 'seo', 'icon' => 'icon-magnifier', 'label' => __('SEO & Content')],
        ['key' => 'publish', 'icon' => 'icon-check', 'label' => __('Publish')],
    ];
?>

<div class="row crud-overview mb-2">
    <div class="col-lg-12 col-md-12 mb-2">
        <div class="card crud-wizard h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <h5 class="mb-0"><?php echo e(__('Car Management Flow')); ?></h5>
                        <small class="text-muted"><?php echo e(__('Follow the steps to complete the operation')); ?></small>
                    </div>
                    <span class="badge badge-light-primary text-uppercase"><?php echo e(__('Cars CRUD')); ?></span>
                </div>
                <div class="crud-steps mt-1">
                    <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="crud-step <?php echo e($activeStep === $step['key'] ? 'active' : ''); ?>">
                            <span class="step-icon"><i class="<?php echo e($step['icon']); ?>"></i></span>
                            <span class="step-label"><?php echo e($step['label']); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\car_rental-src-2025-11-05\car_rental\resources\views/pages/admin/cars/partials/crud_header.blade.php ENDPATH**/ ?>