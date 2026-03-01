<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
	<meta name="description" content="Dreamsrent - Bootstrap Admin Template">
	<meta name="keywords" content="admin, estimates, bootstrap, business, html5, responsive, Projects">
	<meta name="author" content="Dreams technologies - Bootstrap Admin Template">
	<meta name="robots" content="noindex, nofollow">
    <title>Afandina Admin Panel | <?php echo $__env->yieldContent('title'); ?></title>

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

	<!-- Apple Touch Icon -->
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png">

	<!-- Theme Settings Js -->
	<script src="<?php echo e(asset('admin/assets/js/theme-script.js')); ?>"></script>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/bootstrap.min.css')); ?>">

	<!-- Tabler Icon CSS -->
	<link rel="stylesheet" href="<?php echo e(asset('admin/assets/plugins/tabler-icons/tabler-icons.min.css')); ?>">

    <!-- Daterangepikcer CSS -->
	<link rel="stylesheet" href="<?php echo e(asset('admin/assets/plugins/daterangepicker/daterangepicker.css')); ?>">

	<!-- Main CSS -->
	<link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/style.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>


<body>
    <!-- Main Wrapper -->
	<div class="main-wrapper">
        
        
        <?php echo $__env->make('includes.admin.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <?php echo $__env->make('includes.admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
		<div class="page-wrapper">
            <div class="content pb-0">
                <!-- Breadcrumb -->
                <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
                    <div class="my-auto mb-2">
                        <h4 class="mb-1"><?php echo $__env->yieldContent('page-title', __('Admin Dashboard')); ?></h4>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Home')); ?></a>
                                </li> 
                                <?php if (! empty(trim($__env->yieldContent('breadcrumbs')))): ?>
                                    <?php echo $__env->yieldContent('breadcrumbs'); ?>
                                <?php else: ?>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        <?php echo $__env->yieldContent('page-title', __('Admin Dashboard')); ?>
                                    </li>
                                <?php endif; ?>
                            </ol>
                        </nav>
                    </div>
                    <?php if (! empty(trim($__env->yieldContent('page-actions')))): ?>
                        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap mb-2">
                            <?php echo $__env->yieldContent('page-actions'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- /Breadcrumb -->

                
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
        
        <?php echo $__env->make('includes.admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <?php echo $__env->make('includes.admin.footer_scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>

</html>
<?php /**PATH D:\afandina\resources\views\layouts\admin_layout.blade.php ENDPATH**/ ?>