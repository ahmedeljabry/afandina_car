<?php
    $title = $title ?? '';
    $description = $description ?? '';
    $stats = $stats ?? [];
?>

<div class="form-hero">
    <div class="flex-grow-1">
        <h2 class="mb-1"><?php echo e($title); ?></h2>
        <?php if($description): ?>
            <p class="mb-0"><?php echo e($description); ?></p>
        <?php endif; ?>
    </div>
    <?php if(!empty($stats)): ?>
        <div class="d-flex flex-wrap mt-3 mt-md-0 justify-content-start justify-content-md-end">
            <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(empty($stat['label'])) continue; ?>
                <span class="hero-pill">
                    <?php if(!empty($stat['icon'])): ?>
                        <i class="<?php echo e($stat['icon']); ?>"></i>
                    <?php endif; ?>
                    <?php echo e($stat['label']); ?>

                </span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH D:\car_rental-src-2025-11-05\car_rental\resources\views/includes/admin/form_header.blade.php ENDPATH**/ ?>