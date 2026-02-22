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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/vendors.css')); ?>">
    <!-- END VENDOR CSS-->
    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/app.css')); ?>">
    <!-- END ROBUST CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/core/menu/menu-types/vertical-menu.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/core/colors/palette-gradient.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin/dist/css/custom.css')); ?>">
    <!-- END Page Level CSS-->
    <link rel="stylesheet" href="<?php echo e(asset('admin/plugins/fontawesome-free/css/all.min.css')); ?>">
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
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm mt-3 p-4 rounded-lg" role="alert">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle mr-2" style="font-size: 24px;"></i>
                            <div class="flex-grow-1">
                                <h5 class="alert-heading mb-2"><?php echo e(__('Please correct the following errors:')); ?></h5>
                                <ul class="mb-0 pl-3">
                                    <?php $__currentLoopData = $errors->getBag('default')->toArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $errorMessages): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $__currentLoopData = $errorMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="<?php echo e(__('Close')); ?>">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(session('success')): ?>
                    <noscript>
                        <div class="alert alert-success alert-dismissible fade show shadow-sm p-2 px-3 rounded">
                            <strong><?php echo e(__('Success')); ?>:</strong> <?php echo e(session('success')); ?>

                            <button type="button" class="close" data-dismiss="alert" aria-label="<?php echo e(__('Close')); ?>">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </noscript>
                <?php endif; ?>
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>

    <?php echo $__env->make('includes.admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('includes.admin.footer_scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php $__currentLoopData = ['success' => 'success', 'error' => 'error', 'warning' => 'warning', 'info' => 'info']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(session($type)): ?>
                    Swal.fire({
                        icon: '<?php echo e($icon); ?>',
                        title: "<?php echo e(ucfirst($icon)); ?>",
                        text: <?php echo json_encode(session($type), 15, 512) ?>,
                        timer: 3500,
                        showConfirmButton: false
                    });
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH D:\afandina\resources\views\layouts\admin_layout.blade.php ENDPATH**/ ?>