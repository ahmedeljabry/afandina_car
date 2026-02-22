<?php $__env->startSection('title', 'List of ' . $modelName); ?>

<?php echo $__env->make('includes.admin.datatable_theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .currencies-hero {
            background: linear-gradient(135deg, #4c6ef5, #6a82fb);
            border-radius: 22px;
            color: #fff;
            padding: 1.75rem 2rem;
            box-shadow: 0 16px 40px rgba(67, 86, 178, 0.35);
            margin-bottom: 1.5rem;
        }
        .currencies-hero h2 {
            font-weight: 600;
            margin-bottom: .25rem;
            color: #fff;
        }
        .currencies-hero .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .45rem .95rem;
            border-radius: 999px;
            background: rgba(255,255,255,.18);
            font-weight: 500;
            margin-right: .75rem;
        }
        .currencies-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }
        .currencies-card .card-header {
            border-bottom: 0;
            padding: 1.5rem 1.75rem 0;
        }
        .currencies-table thead th {
            text-transform: uppercase;
            font-size: .8rem;
            letter-spacing: .05em;
            border-top: none;
            border-bottom-width: 1px;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            border-radius: 999px;
            padding: .25rem .85rem;
            font-weight: 600;
            font-size: .85rem;
        }
        .status-pill.active {
            background: rgba(16, 185, 129, .15);
            color: #047857;
        }
        .status-pill.inactive {
            background: rgba(248, 113, 113, .18);
            color: #b91c1c;
        }
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 999px !important;
            border: 1px solid #dbe2f0;
            padding: .35rem 1rem;
            box-shadow: inset 0 1px 2px rgba(15,23,42,.06);
            background-color: #f8fafc;
        }
        .dataTables_wrapper .dataTables_length select {
            border-radius: 12px;
        }
        .dataTables_wrapper .dataTables_paginate .page-link {
            border-radius: 10px;
            margin: 0 .15rem;
        }
        .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
            background-color: #4c6ef5;
            border-color: #4c6ef5;
        }
        .currencies-toolbar .btn {
            border-radius: 30px;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $itemsCollection = $items instanceof \Illuminate\Pagination\AbstractPaginator ? collect($items->items()) : collect($items);
        $totalItems = $itemsCollection->count();
        $activeItems = $itemsCollection->where('is_active', true)->count();
        $inactiveItems = $itemsCollection->where('is_active', false)->count();
    ?>

    <div class="currencies-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h2 class="mb-1"><?php echo e(__('Manage')); ?> <?php echo e($modelName); ?></h2>
                <p class="mb-0 text-white-50"><?php echo e(__('Keep your pricing experience consistent across locales.')); ?></p>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="stat-pill"><i class="fas fa-layer-group"></i> <?php echo e($totalItems); ?> <?php echo e(__('listed')); ?></span>
                <span class="stat-pill"><i class="fas fa-toggle-on"></i> <?php echo e($activeItems); ?> <?php echo e(__('active')); ?></span>
                <span class="stat-pill"><i class="fas fa-toggle-off"></i> <?php echo e($inactiveItems); ?> <?php echo e(__('inactive')); ?></span>
            </div>
        </div>
    </div>

    <div class="card currencies-card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                <div>
                    <h4 class="mb-1"><?php echo e($modelName); ?> <?php echo e(__('Directory')); ?></h4>
                    <p class="text-muted mb-0"><?php echo e(__('Quickly filter, sort and manage available currencies.')); ?></p>
                </div>
                <div class="currencies-toolbar text-md-right">
                    <a href="<?php echo e(route('admin.' . $modelName . '.create')); ?>" class="btn btn-primary shadow-sm">
                        <i class="fas fa-plus mr-50"></i> <?php echo e(__('Add')); ?> <?php echo e($modelName); ?>

                    </a>
                </div>
            </div>
        </div>
        <div class="card-body pt-1">
            <div class="table-responsive">
                <table id="currencies-table" class="table table-hover table-striped table-modern currencies-table w-100">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 70px;">#</th>
                            <th><?php echo e(__('Name')); ?></th>
                            <th><?php echo e(__('Status')); ?></th>
                            <th><?php echo e(__('Created')); ?></th>
                            <th class="text-center" style="width: 160px;"><?php echo e(__('Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td class="text-bold-600">
                                    <?php echo e($item->translations->first()->name ?? __('N/A')); ?>

                                </td>
                                <td>
                                    <span class="status-pill <?php echo e($item->is_active ? 'active' : 'inactive'); ?>">
                                        <i class="fas <?php echo e($item->is_active ? 'fa-check-circle' : 'fa-times-circle'); ?>"></i>
                                        <?php echo e($item->is_active ? __('Active') : __('Inactive')); ?>

                                    </span>
                                    <div class="d-inline-block align-middle ml-1">
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
                                </td>
                                <td>
                                    <?php echo e($item->created_at ? $item->created_at->format('d M, Y') : __('N/A')); ?>

                                </td>
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
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle mr-1"></i> <?php echo e(__('No currencies found yet.')); ?>

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
            const $table = $('#currencies-table');
            if (!$table.length || !$table.find('tbody tr').length) {
                return;
            }

            $table.DataTable({
                order: [[1, 'asc']],
                autoWidth: false,
                pageLength: 10,
                columnDefs: [
                    { orderable: false, targets: [2, 4] }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "<?php echo e(__('Search currencies')); ?>",
                    lengthMenu: "<?php echo e(__('Show _MENU_ entries')); ?>",
                    info: "<?php echo e(__('Showing _START_ to _END_ of _TOTAL_ currencies')); ?>",
                    infoEmpty: "<?php echo e(__('Showing 0 to 0 of 0 currencies')); ?>",
                    zeroRecords: "<?php echo e(__('No matching currencies found')); ?>",
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

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\currencies\index.blade.php ENDPATH**/ ?>