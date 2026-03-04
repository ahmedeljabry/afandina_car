<?php $__env->startSection('title', 'List of ' . $modelName); ?>


<?php echo $__env->make('includes.admin.datatable_theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('page-title'); ?>
    <?php echo e($modelName); ?> List
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php
        $itemsCollection = $items instanceof \Illuminate\Pagination\AbstractPaginator ? collect($items->items()) : collect($items);
        $totalItems = $itemsCollection->count();
        $activeItems = $itemsCollection->where('is_active', true)->count();
        $inactiveItems = max($totalItems - $activeItems, 0);
    ?>

    <div class="management-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h2 class="mb-1"><?php echo e(__('Manage')); ?> <?php echo e($modelName); ?></h2>
                <p class="mb-0"><?php echo e(__('Keep this dataset polished and ready for your storefront experience.')); ?></p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="stat-pill">
                    <i class="fas fa-layer-group"></i> <?php echo e($totalItems); ?> <?php echo e(__('entries')); ?>

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
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title text-dark"><?php echo e($modelName); ?> List</h3>
                            <a href="<?php echo e(route('admin.' . $modelName . '.create')); ?>" class="btn btn-primary shadow-sm">
                                <i class="fas fa-plus"></i> Add <?php echo e($modelName); ?>

                            </a>
                        </div>
                    </div>

                    <div class="card-body pt-1">
                        <div class="table-responsive">
                            <table id="languages-table" class="table table-hover table-striped table-modern w-100 datatable">
                                <thead class="bg-dark text-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Flag</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($item->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($item->code ?? 'N/A'); ?></td>
                                        <td><?php echo e($item->flag ?? 'N/A'); ?></td>
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
                        <div class="d-flex justify-content-center">
                            <?php echo e($items->links()); ?>

                        </div>
                    </div>
                </div>
        <?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <script>
        $(function () {
            const $table = $('#languages-table');
            if (!$table.length || !$table.find('tbody tr').length) {
                return;
            }

            $table.DataTable({
                order: [[1, 'asc']],
                autoWidth: false,
                pageLength: 10,
                columnDefs: [
                    { orderable: false, targets: [-1] }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "<?php echo e(__('Search :entity', ['entity' => $modelName])); ?>",
                    lengthMenu: "<?php echo e(__('Show _MENU_ entries')); ?>",
                    info: "<?php echo e(__('Showing _START_ to _END_ of _TOTAL_ entries')); ?>",
                    infoEmpty: "<?php echo e(__('Showing 0 to 0 of 0 entries')); ?>",
                    zeroRecords: "<?php echo e(__('No matching records found')); ?>",
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

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\languages\index.blade.php ENDPATH**/ ?>