<?php $__env->startSection('title', 'List of ' . $modelName); ?>

<?php $__env->startSection('content'); ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="display-4"><?php echo e($modelName); ?> List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active"><?php echo e($modelName); ?> List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm p-4 rounded-lg" role="alert">
                        <strong>Success:</strong> <?php echo e(session('success')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="card card-outline card-shadow mb-4" style="border: 1px solid #dcdcdc; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title text-dark"><?php echo e($modelName); ?> List</h3>
                            <a href="<?php echo e(route('admin.' . $modelName . '.create')); ?>" class="btn btn-primary shadow-sm">
                                <i class="fas fa-plus"></i> Add <?php echo e($modelName); ?>

                            </a>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-responsive-xl" style="background-color: #f9f9f9;">
                                <thead class="bg-dark text-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Brand</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($item->translations->first()->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($item->brand->translations()->where('locale','en')->first()->name ?? 'N/A'); ?></td>
                                        <td>
                                            <!-- Custom Toggle Switch -->
                                            <label class="switch">
                                                <input type="checkbox" class="toggle-status" data-model="<?php echo e($modelName); ?>" data-attribute="is_active" data-id="<?php echo e($item->id); ?>" <?php echo e($item->is_active ? 'checked' : ''); ?>>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td><?php echo e($item->created_at ? $item->created_at->format('d M, Y') : 'N/A'); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                


                                                <a href="<?php echo e(route('admin.' . $modelName . '.edit', $item->id)); ?>" class="btn btn-info btn-sm shadow-sm mr-1">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm shadow-sm delete-btn" data-id="<?php echo e($item->id); ?>" data-model="<?php echo e($modelName); ?>">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>

                                            </div>
                                        </td>


                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-end">
                            <?php echo e($items->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\car_rental-src-2025-11-05\car_rental\resources\views/pages/admin/car_models/index.blade.php ENDPATH**/ ?>