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
    <title><?php echo e($adminSiteName ?? config('app.name', 'Afandina')); ?> <?php if (! empty(trim($__env->yieldContent('title')))): ?>| <?php echo $__env->yieldContent('title'); ?><?php endif; ?></title>

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo e($adminFavicon ?? asset('website/assets/img/favicon.png')); ?>">

	<!-- Apple Touch Icon -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo e($adminFavicon ?? asset('website/assets/img/favicon.png')); ?>">

	<!-- Theme Settings Js -->
	<script src="<?php echo e(asset('admin/assets/js/theme-script.js')); ?>"></script>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/bootstrap.min.css')); ?>">

	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="<?php echo e(asset('admin/assets/plugins/fontawesome/css/fontawesome.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('admin/assets/plugins/fontawesome/css/all.min.css')); ?>">

	<!-- Tabler Icon CSS -->
	<link rel="stylesheet" href="<?php echo e(asset('admin/assets/plugins/tabler-icons/tabler-icons.min.css')); ?>">

    <!-- Daterangepikcer CSS -->
	<link rel="stylesheet" href="<?php echo e(asset('admin/assets/plugins/daterangepicker/daterangepicker.css')); ?>">

	<!-- Main CSS -->
	<link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/style.css')); ?>">
	<style>
		.page-wrapper .table-responsive {
			border: 1px solid #eef2f7;
			border-radius: 18px;
			background: #fff;
		}

		.page-wrapper .table {
			margin-bottom: 0;
			--bs-table-bg: transparent;
		}

		.page-wrapper .table > :not(caption) > * > * {
			padding: 0.95rem 0.9rem;
			vertical-align: middle;
			border-bottom-color: #eef2f7;
		}

		.page-wrapper .table thead th {
			background: #f8fafc;
			color: #334155;
			font-size: 0.78rem;
			font-weight: 700;
			letter-spacing: 0.06em;
			text-transform: uppercase;
			border-bottom: 1px solid #e2e8f0;
			white-space: nowrap;
		}

		.page-wrapper .table tbody tr:hover {
			background: rgba(59, 130, 246, 0.04);
		}

		.page-wrapper .table tbody td:last-child {
			white-space: nowrap;
		}

		.page-wrapper .table tbody td .btn-group,
		.page-wrapper .table tbody tr td .action-icon {
			display: inline-flex;
			align-items: center;
			gap: 0.45rem;
			flex-wrap: nowrap;
		}

		.page-wrapper .table tbody td .btn-group form,
		.page-wrapper .table tbody td form {
			display: inline-block;
			margin: 0;
		}

		.page-wrapper .table tbody td .btn-group > .btn,
		.page-wrapper .table tbody td a.btn.btn-sm,
		.page-wrapper .table tbody td button.btn.btn-sm,
		.page-wrapper .table tbody td form .btn.btn-sm {
			min-width: 2.45rem;
			min-height: 2.45rem;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 0.35rem;
			padding: 0.45rem 0.7rem;
			border-radius: 12px;
			line-height: 1;
		}

		.page-wrapper .table tbody td .btn i {
			font-size: 0.95rem;
			line-height: 1;
		}

		.page-wrapper .table .badge {
			border-radius: 999px;
		}

		@media (max-width: 767.98px) {
			.page-wrapper .table > :not(caption) > * > * {
				padding: 0.8rem 0.75rem;
			}
		}
	</style>
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