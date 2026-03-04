<?php $__env->startSection('title', 'List of ' . $modelName); ?>

<?php $__env->startSection('page-title'); ?>
    <?php echo e($modelName); ?> List
<?php $__env->stopSection(); ?>

<?php echo $__env->make('includes.admin.datatable_theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $itemsCollection = $items instanceof \Illuminate\Pagination\AbstractPaginator ? collect($items->items()) : collect($items);
        $totalItems = $itemsCollection->count();
        $activeItems = $itemsCollection->where('is_active', true)->count();
        $featuredItems = $itemsCollection->where('show_in_home', true)->count();
        $inactiveItems = max($totalItems - $activeItems, 0);
    ?>

    <div class="management-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h2 class="mb-1"><?php echo e(__('Manage')); ?> <?php echo e($modelName); ?></h2>
                <p><?php echo e(__('Highlight premium services and keep their visibility in sync.')); ?></p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="stat-pill">
                    <i class="fas fa-layer-group"></i> <?php echo e($totalItems); ?> <?php echo e(__('entries')); ?>

                </span>
                <span class="stat-pill">
                    <i class="fas fa-home"></i> <?php echo e($featuredItems); ?> <?php echo e(__('featured')); ?>

                </span>
                <span class="stat-pill">
                    <i class="fas fa-toggle-on"></i> <?php echo e($activeItems); ?> <?php echo e(__('active')); ?>

                </span>
                <span class="stat-pill">
                    <i class="fas fa-toggle-off"></i> <?php echo e($inactiveItems); ?> <?php echo e(__('inactive')); ?>

                </span>
            </div>
        </div>
    </div>

    <div class="card management-card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                <div>
                    <h4 class="mb-1"><?php echo e($modelName); ?> <?php echo e(__('Portfolio')); ?></h4>
                    <p class="text-muted mb-0"><?php echo e(__('Control homepage presence and activation from a single table.')); ?></p>
                </div>
                <div class="management-toolbar text-md-right">
                    <a href="<?php echo e(route('admin.' . $modelName . '.create')); ?>" class="btn btn-primary shadow-sm">
                        <i class="fas fa-plus mr-50"></i> <?php echo e(__('Add')); ?> <?php echo e($modelName); ?>

                    </a>
                </div>
            </div>
        </div>
        <div class="card-body pt-1">
            <div class="table-responsive">
                <table id="services-table" class="table table-hover table-striped management-table w-100">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 70px;">#</th>
                            <th><?php echo e(__('Image')); ?></th>
                            <th><?php echo e(__('Name')); ?></th>
                            <th><?php echo e(__('Visibility')); ?></th>
                            <th><?php echo e(__('Created')); ?></th>
                            <th class="text-center" style="width: 160px;"><?php echo e(__('Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <?php if($item->image_path): ?>
                                        <img src="<?php echo e(asset('storage/' . $item->image_path)); ?>" alt="<?php echo e($item->translations->first()->name ?? __('Service image')); ?>" class="media-thumb">
                                    <?php else: ?>
                                        <span class="text-muted"><?php echo e(__('N/A')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-bold-600">
                                    <?php echo e($item->translations->first()->name ?? __('N/A')); ?>

                                </td>
                                <td>
                                    <div class="mb-50">
                                        <span class="status-pill <?php echo e($item->is_active ? 'active' : 'inactive'); ?>">
                                            <i class="fas <?php echo e($item->is_active ? 'fa-check-circle' : 'fa-times-circle'); ?>"></i>
                                            <?php echo e($item->is_active ? __('Active') : __('Inactive')); ?>

                                        </span>
                                    </div>
                                    <div class="toggle-stack">
                                        <div>
                                            <div class="toggle-label"><?php echo e(__('Show in Home')); ?></div>
                                            <label class="switch mb-0">
                                                <input type="checkbox"
                                                       class="toggle-status"
                                                       data-model="<?php echo e($modelName); ?>"
                                                       data-attribute="show_in_home"
                                                       data-id="<?php echo e($item->id); ?>"
                                                       <?php echo e($item->show_in_home ? 'checked' : ''); ?>>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div>
                                            <div class="toggle-label"><?php echo e(__('Active')); ?></div>
                                            <label class="switch mb-0">
                                                <input type="checkbox"
                                                       class="toggle-status"
                                                       data-model="<?php echo e($modelName); ?>"
                                                       data-attribute="is_active"
                                                       data-id="<?php echo e($item->id); ?>"
                                                       <?php echo e($item->is_active ? 'checked' : ''); ?>>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo e($item->created_at ? $item->created_at->format('d M, Y') : __('N/A')); ?></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo e(route('admin.' . $modelName . '.edit', $item->id)); ?>"
                                           class="btn btn-outline-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-outline-danger delete-btn"
                                                data-id="<?php echo e($item->id); ?>"
                                                data-model="<?php echo e($modelName); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr class="empty-row">
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle mr-1"></i> <?php echo e(__('No services found yet.')); ?>

                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if(method_exists($items, 'links')): ?>
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-end">
                    <?php echo e($items->links()); ?>

                </div>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(function () {
            const $table = $('#services-table');
            if (!$table.length || !$table.find('tbody tr').not('.empty-row').length) {
                return;
            }

            $table.DataTable({
                order: [[2, 'asc']],
                autoWidth: false,
                pageLength: 10,
                columnDefs: [
                    { orderable: false, targets: [0, 1, 3, 5] }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "<?php echo e(__('Search services')); ?>",
                    lengthMenu: "<?php echo e(__('Show _MENU_ entries')); ?>",
                    info: "<?php echo e(__('Showing _START_ to _END_ of _TOTAL_ entries')); ?>",
                    infoEmpty: "<?php echo e(__('Showing 0 to 0 of 0 entries')); ?>",
                    zeroRecords: "<?php echo e(__('No matching services found')); ?>",
                    paginate: {
                        first: "<?php echo e(__('First')); ?>",
                        previous: "<?php echo e(__('Previous')); ?>",
                        next: "<?php echo e(__('Next')); ?>",
                        last: "<?php echo e(__('Last')); ?>"
                    }
                },
                dom:
                    "<'row align-items-center mb-2'<'col-sm-6'l><'col-sm-6 text-sm-right'f>>" +
                    "t" +
                    "<'row align-items-center mt-2'<'col-sm-5'i><'col-sm-7'p>>"
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\services\index.blade.php ENDPATH**/ ?>