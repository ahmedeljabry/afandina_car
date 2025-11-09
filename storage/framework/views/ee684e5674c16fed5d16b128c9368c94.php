<!DOCTYPE html>
<html class="loading" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-textdirection="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description"
        content="Robust admin is super flexible, powerful, clean &amp; modern responsive admin template.">
    <meta name="keywords"
        content="admin template, robust admin template, dashboard template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Afandina Admin Panel | <?php echo $__env->yieldContent('title'); ?></title>
    <link rel="apple-touch-icon" href="<?php echo e(asset('admin/dist/logo/afandina.svg')); ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('admin/dist/logo/afandina.svg')); ?>">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700"
        rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/vendors.css')); ?>">
    <!-- END VENDOR CSS-->
    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/app.css')); ?>">
    <!-- END ROBUST CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/core/menu/menu-types/vertical-menu.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/core/colors/palette-gradient.css')); ?>">
    <!-- END Page Level CSS-->
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="vertical-layout vertical-menu 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="2-columns">
    <?php echo $__env->make('includes.admin.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('includes.admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-8 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">
                        <?php echo $__env->yieldContent('page-title', __('Dashboard')); ?>
                    </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Home')); ?></a>
                                </li>
                                <?php if (! empty(trim($__env->yieldContent('breadcrumbs')))): ?>
                                    <?php echo $__env->yieldContent('breadcrumbs'); ?>
                                <?php else: ?>
                                    <li class="breadcrumb-item active">
                                        <?php echo $__env->yieldContent('page-title', __('Dashboard')); ?>
                                    </li>
                                <?php endif; ?>
                            </ol>
                        </div>
                    </div>
                </div>
                <?php if (! empty(trim($__env->yieldContent('page-actions')))): ?>
                <div class="content-header-right col-md-4 col-12">
                    <div class="btn-group float-md-right">
                        <?php echo $__env->yieldContent('page-actions'); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="content-body">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm p-2 px-3 rounded">
                        <strong><?php echo e(__('Success')); ?>:</strong> <?php echo e(session('success')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="<?php echo e(__('Close')); ?>">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>

    <?php echo $__env->make('includes.admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('includes.admin.footer_scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH D:\car_rental-src-2025-11-05\car_rental\resources\views/layouts/admin_layout.blade.php ENDPATH**/ ?>