<?php $__env->startSection('title', 'List of ' . $modelName); ?>

<?php $__env->startSection('page-title'); ?>
    <?php echo e($modelName); ?> List
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\templates\empty.blade.php ENDPATH**/ ?>