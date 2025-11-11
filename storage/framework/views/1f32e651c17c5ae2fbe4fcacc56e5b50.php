

<?php $__env->startSection('title', __('Dashboard')); ?>
<?php $__env->startSection('page-title', __('Dashboard Overview')); ?>
<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item active"><?php echo e(__('Overview')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('admin.cars.create')); ?>" class="btn btn-info">
        <i class="ft-plus"></i> <?php echo e(__('Add Car')); ?>

    </a>
    <a href="<?php echo e(route('admin.blogs.index')); ?>" class="btn btn-outline-info">
        <i class="ft-edit-2"></i> <?php echo e(__('Manage Content')); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $totalListings = $carsByCategory->sum('cars_count');
    ?>

    <section id="dashboard-metrics">
        <div class="row match-height">
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="media-body text-left">
                                    <h3 class="info"><?php echo e($carCount); ?></h3>
                                    <h6><?php echo e(__('Total Cars')); ?></h6>
                                </div>
                                <div class="align-self-center">
                                    <i class="icon-target info font-large-2"></i>
                                </div>
                            </div>
                            <span class="text-muted"><?php echo e(__('Active listings across the fleet')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="media-body text-left">
                                    <h3 class="success"><?php echo e($brandCount); ?></h3>
                                    <h6><?php echo e(__('Brands')); ?></h6>
                                </div>
                                <div class="align-self-center">
                                    <i class="icon-badge success font-large-2"></i>
                                </div>
                            </div>
                            <span class="text-muted"><?php echo e(__('Manufacturers currently configured')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="media-body text-left">
                                    <h3 class="warning"><?php echo e($categoryCount); ?></h3>
                                    <h6><?php echo e(__('Categories')); ?></h6>
                                </div>
                                <div class="align-self-center">
                                    <i class="icon-layers warning font-large-2"></i>
                                </div>
                            </div>
                            <span class="text-muted"><?php echo e(__('Segments customers can browse')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="media-body text-left">
                                    <h3 class="danger"><?php echo e($totalListings); ?></h3>
                                    <h6><?php echo e(__('Published Listings')); ?></h6>
                                </div>
                                <div class="align-self-center">
                                    <i class="icon-graph danger font-large-2"></i>
                                </div>
                            </div>
                            <span class="text-muted"><?php echo e(__('Combined inventory per category')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="dashboard-quick-actions">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0"><i class="icon-lightning mr-1"></i> <?php echo e(__('Quick Actions')); ?></h4>
                <span class="text-muted small"><?php echo e(__('Jump to the most common workflows')); ?></span>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-xl-2 col-md-4 col-6 mb-1">
                        <a href="<?php echo e(route('admin.cars.index')); ?>" class="btn btn-outline-info btn-block">
                            <i class="icon-directions"></i> <?php echo e(__('Cars')); ?>

                        </a>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-1">
                        <a href="<?php echo e(route('admin.brands.index')); ?>" class="btn btn-outline-primary btn-block">
                            <i class="icon-badge"></i> <?php echo e(__('Brands')); ?>

                        </a>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-1">
                        <a href="<?php echo e(route('admin.categories.index')); ?>" class="btn btn-outline-warning btn-block">
                            <i class="icon-grid"></i> <?php echo e(__('Categories')); ?>

                        </a>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-1">
                        <a href="<?php echo e(route('admin.features.index')); ?>" class="btn btn-outline-success btn-block">
                            <i class="icon-plus"></i> <?php echo e(__('Features')); ?>

                        </a>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-1">
                        <a href="<?php echo e(route('admin.languages.index')); ?>" class="btn btn-outline-secondary btn-block">
                            <i class="icon-globe"></i> <?php echo e(__('Languages')); ?>

                        </a>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-1">
                        <a href="<?php echo e(route('admin.blogs.index')); ?>" class="btn btn-outline-danger btn-block">
                            <i class="icon-book-open"></i> <?php echo e(__('Blog')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="dashboard-details" class="mt-2">
        <div class="row match-height">
            <div class="col-xl-6 col-md-12">
                <div class="card card-border-info">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="icon-directions mr-1"></i>
                            <?php echo e(__('Latest Added Cars')); ?>

                        </h4>
                        <div class="heading-elements">
                            <a href="<?php echo e(route('admin.cars.create')); ?>" class="btn btn-sm btn-info">
                                <i class="ft-plus"></i> <?php echo e(__('Add Car')); ?>

                            </a>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table class="table table-hover table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th><?php echo e(__('Car')); ?></th>
                                        <th><?php echo e(__('Brand')); ?></th>
                                        <th><?php echo e(__('Category')); ?></th>
                                        <th class="text-center"><?php echo e(__('Actions')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $latestCars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="text-bold-600">
                                                <?php echo e($car->translations->first()->name ?? __('N/A')); ?>

                                                <div class="table-meta text-muted">
                                                    <i class="ft-clock mr-25"></i>
                                                    <?php echo e(optional($car->created_at)->diffForHumans() ?? __('Unknown')); ?>

                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-light-info">
                                                    <i class="icon-badge mr-25"></i>
                                                    <?php echo e($car->brand->translations->first()->name ?? __('N/A')); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-light-warning">
                                                    <i class="icon-grid mr-25"></i>
                                                    <?php echo e($car->category->translations->first()->name ?? __('N/A')); ?>

                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="<?php echo e(route('admin.cars.edit', $car->id)); ?>" class="btn btn-primary">
                                                        <i class="ft-edit"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('admin.cars.edit_images', $car->id)); ?>" class="btn btn-secondary">
                                                        <i class="ft-image"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                <?php echo e(__('No cars have been added yet.')); ?>

                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-12">
                <div class="card card-border-success">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="icon-pie-chart mr-1"></i>
                            <?php echo e(__('Cars by Category')); ?>

                        </h4>
                        <div class="heading-elements">
                            <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-sm btn-success">
                                <i class="ft-plus"></i> <?php echo e(__('Add Category')); ?>

                            </a>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th><?php echo e(__('Category')); ?></th>
                                        <th class="text-center"><?php echo e(__('Cars')); ?></th>
                                        <th style="width: 45%"><?php echo e(__('Distribution')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $progressColors = ['bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-primary', 'bg-secondary'];
                                    ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $carsByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $categoryPercentage = $carCount > 0 ? ($category->cars_count / $carCount) * 100 : 0;
                                            $barClass = $progressColors[$loop->index % count($progressColors)];
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo e(route('admin.categories.edit', $category->id)); ?>">
                                                    <?php echo e($category->translations->first()->name ?? __('N/A')); ?>

                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-pill badge-light-success">
                                                    <?php echo e($category->cars_count); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar <?php echo e($barClass); ?>" role="progressbar"
                                                         style="width: <?php echo e($categoryPercentage); ?>%"
                                                         aria-valuenow="<?php echo e($categoryPercentage); ?>" aria-valuemin="0"
                                                         aria-valuemax="100"></div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted"><?php echo e(number_format($categoryPercentage, 1)); ?>%</small>
                                                    <small class="text-muted">
                                                        <?php echo e(__('Share of total cars')); ?>

                                                    </small>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">
                                                <?php echo e(__('No categories available yet.')); ?>

                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\car_rental-src-2025-11-05\car_rental\resources\views/pages/admin/dashboard.blade.php ENDPATH**/ ?>