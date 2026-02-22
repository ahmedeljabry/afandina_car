<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title><?php echo $__env->yieldContent('title'); ?></title>

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php echo e(asset('website/assets/img/favicon.png')); ?>">

    <?php if(app()->getLocale() == 'ar'): ?>
    	<link rel="stylesheet" href="<?php echo e(asset('website/rtl/assets/css/bootstrap.rtl.min.css')); ?>">
        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/rtl/assets/plugins/fontawesome/css/fontawesome.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('website/rtl/assets/plugins/fontawesome/css/all.min.css')); ?>">

        <!-- Select2 CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/rtl/assets/plugins/select2/css/select2.min.css')); ?>">

        <!-- Datepicker CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/rtl/assets/css/bootstrap-datetimepicker.min.css')); ?>">

        <!-- Aos CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/rtl/assets/plugins/aos/aos.css')); ?>">

        <!-- Fearther CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/rtl/assets/css/feather.css')); ?>">

        <!-- Owl carousel CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/rtl/assets/css/owl.carousel.min.css')); ?>">

        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/rtl/assets/plugins/flatpickr/flatpickr.min.css')); ?>">

        <!-- Fancybox CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/rtl/assets/plugins/fancybox/fancybox.css')); ?>">

        <!-- Slick CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/rtl/assets/plugins/slick/slick.css')); ?>">

        <!-- Boxicons CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/rtl/assets/plugins/boxicons/css/boxicons.min.css')); ?>">

        <!-- Main CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/rtl/assets/css/style.css')); ?>">
    <?php else: ?>
    	<!-- Bootstrap CSS -->
	    <link rel="stylesheet" href="<?php echo e(asset('website/assets/css/bootstrap.min.css')); ?>">
        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/assets/plugins/fontawesome/css/fontawesome.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('website/assets/plugins/fontawesome/css/all.min.css')); ?>">

        <!-- Select2 CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/assets/plugins/select2/css/select2.min.css')); ?>">

        <!-- Datepicker CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/assets/css/bootstrap-datetimepicker.min.css')); ?>">

        <!-- Aos CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/assets/plugins/aos/aos.css')); ?>">

        <!-- Fearther CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/assets/css/feather.css')); ?>">

        <!-- Owl carousel CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/assets/css/owl.carousel.min.css')); ?>">

        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/assets/plugins/flatpickr/flatpickr.min.css')); ?>">

        <!-- Fancybox CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/assets/plugins/fancybox/fancybox.css')); ?>">

        <!-- Slick CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/assets/plugins/slick/slick.css')); ?>">

        <!-- Boxicons CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/assets/plugins/boxicons/css/boxicons.min.css')); ?>">

        <!-- Main CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('website/assets/css/style.css')); ?>">
    <?php endif; ?>


    <?php echo $__env->yieldPushContent('css'); ?>

</head>
<body>
    <div class="main-wrapper home-three">
        <?php echo $__env->make('includes.website.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->yieldContent('content'); ?>
        <?php echo $__env->make('includes.website.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <?php echo $__env->make('includes.website.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH D:\afandina\resources\views\layouts\website.blade.php ENDPATH**/ ?>