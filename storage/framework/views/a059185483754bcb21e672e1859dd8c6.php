<?php $__env->startSection('title', __('Cars')); ?>
<?php echo $__env->make('includes.admin.form_theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('page-title', __('Cars')); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Cars')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('admin.cars.create')); ?>" class="btn btn-primary d-inline-flex align-items-center me-2 mb-2">
        <i class="ti ti-plus me-1"></i><?php echo e(__('Add Car')); ?>

    </a>
    <?php if(request()->query()): ?>
        <a href="<?php echo e(route('admin.cars.index')); ?>" class="btn btn-outline-secondary d-inline-flex align-items-center mb-2">
            <i class="ti ti-x me-1"></i><?php echo e(__('Clear Filters')); ?>

        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .cars-hero {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 20px;
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 65%, #0ea5e9 100%);
            color: #fff;
            overflow: hidden;
            position: relative;
        }

        .cars-hero::after {
            content: "";
            position: absolute;
            right: -80px;
            top: -80px;
            width: 210px;
            height: 210px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
        }

        .cars-hero .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            margin-right: 8px;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .cars-filter-card,
        .cars-table-card {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
        }

        .cars-filter-card .form-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .cars-filter-card .form-control {
            border-radius: 12px;
        }

        .cars-table-card .table th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .cars-table-card .table td {
            vertical-align: middle;
            border-color: #eef2f7;
        }

        .car-thumb {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 4px 10px;
            font-weight: 600;
            font-size: 12px;
        }

        .status-pill.ok {
            color: #0f766e;
            background: rgba(20, 184, 166, 0.16);
        }

        .status-pill.no {
            color: #b91c1c;
            background: rgba(248, 113, 113, 0.18);
        }

        .flag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .flag-badge {
            border-radius: 999px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 600;
            background: #f1f5f9;
            color: #334155;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $locale = app()->getLocale() ?: 'en';

        $translateName = function ($entity) use ($locale): string {
            if (!$entity || !method_exists($entity, 'translations')) {
                return __('N/A');
            }

            $translations = $entity->relationLoaded('translations')
                ? $entity->translations
                : $entity->translations()->get();

            $translation = $translations->firstWhere('locale', $locale) ?? $translations->first();

            return filled($translation?->name) ? $translation->name : __('N/A');
        };

        $resolveImage = function (?string $path): string {
            if (blank($path)) {
                return asset('admin/assets/img/car/car-01.jpg');
            }

            if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '//'])) {
                return $path;
            }

            if (\Illuminate\Support\Str::startsWith($path, 'storage/')) {
                return asset($path);
            }

            return asset('storage/' . ltrim($path, '/'));
        };

        $itemsCollection = $items instanceof \Illuminate\Pagination\AbstractPaginator
            ? collect($items->items())
            : collect($items);

        $totalCars = $items instanceof \Illuminate\Pagination\AbstractPaginator ? $items->total() : $itemsCollection->count();
        $activeCars = $itemsCollection->where('is_active', true)->count();
        $availableCars = $itemsCollection->where('status', 'available')->count();
        $featuredCars = $itemsCollection->where('is_featured', true)->count();

        $selectedBrand = request()->filled('brand')
            ? $brands->firstWhere('id', (int) request('brand'))
            : null;
    ?>

    <div class="card cars-hero mb-3">
        <div class="card-body p-4 position-relative">
            <div class="d-flex justify-content-between flex-wrap gap-3">
                <div>
                    <h3 class="mb-2"><?php echo e(__('Fleet Management')); ?></h3>
                    <p class="mb-0 text-white-50"><?php echo e(__('Manage your cars, refine filters, and keep stock visibility aligned with availability.')); ?></p>
                </div>
                <div class="text-md-end">
                    <span class="chip"><i class="ti ti-car"></i><?php echo e(__('Total: :count', ['count' => $totalCars])); ?></span>
                    <span class="chip"><i class="ti ti-check"></i><?php echo e(__('Active (page): :count', ['count' => $activeCars])); ?></span>
                    <span class="chip"><i class="ti ti-circle-check"></i><?php echo e(__('Available (page): :count', ['count' => $availableCars])); ?></span>
                    <span class="chip"><i class="ti ti-star"></i><?php echo e(__('Featured (page): :count', ['count' => $featuredCars])); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card cars-filter-card mb-3">
        <div class="card-header border-0 pb-0">
            <h5 class="mb-0"><?php echo e(__('Filters')); ?></h5>
        </div>
        <div class="card-body">
            <form id="carFilterForm" method="GET" action="<?php echo e(route('admin.cars.index')); ?>" class="row g-3">
                <div class="col-lg-2 col-md-4">
                    <label for="brand" class="form-label"><?php echo e(__('Brand')); ?></label>
                    <select name="brand" id="brand" class="form-control select2">
                        <option value=""><?php echo e(__('All Brands')); ?></option>
                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($brand->id); ?>" <?php echo e((string) request('brand') === (string) $brand->id ? 'selected' : ''); ?>>
                                <?php echo e($translateName($brand)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-lg-2 col-md-4">
                    <label for="model" class="form-label"><?php echo e(__('Model')); ?></label>
                    <select name="model" id="model" class="form-control select2" <?php echo e($selectedBrand ? '' : 'disabled'); ?>>
                        <option value=""><?php echo e(__('All Models')); ?></option>
                        <?php if($selectedBrand): ?>
                            <?php $__currentLoopData = $selectedBrand->carModels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($model->id); ?>" <?php echo e((string) request('model') === (string) $model->id ? 'selected' : ''); ?>>
                                    <?php echo e($translateName($model)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-lg-2 col-md-4">
                    <label for="category" class="form-label"><?php echo e(__('Category')); ?></label>
                    <select name="category" id="category" class="form-control select2">
                        <option value=""><?php echo e(__('All Categories')); ?></option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e((string) request('category') === (string) $category->id ? 'selected' : ''); ?>>
                                <?php echo e($translateName($category)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-lg-2 col-md-4">
                    <label for="year" class="form-label"><?php echo e(__('Year')); ?></label>
                    <select name="year" id="year" class="form-control select2">
                        <option value=""><?php echo e(__('All Years')); ?></option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year->id); ?>" <?php echo e((string) request('year') === (string) $year->id ? 'selected' : ''); ?>>
                                <?php echo e($year->year); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label for="search" class="form-label"><?php echo e(__('Search')); ?></label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        class="form-control"
                        value="<?php echo e(request('search')); ?>"
                        placeholder="<?php echo e(__('Search by car name or description')); ?>"
                    >
                </div>

                <div class="col-lg-1 col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark w-100 d-inline-flex align-items-center justify-content-center">
                        <i class="ti ti-filter me-1"></i><?php echo e(__('Go')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card cars-table-card">
        <div class="card-header d-flex align-items-center justify-content-between border-0 pb-0">
            <h5 class="mb-0"><?php echo e(__('Cars List')); ?></h5>
            <small class="text-muted"><?php echo e(__('Showing :count item(s) on this page', ['count' => $itemsCollection->count()])); ?></small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo e(__('Car')); ?></th>
                            <th><?php echo e(__('Category')); ?></th>
                            <th><?php echo e(__('Year')); ?></th>
                            <th><?php echo e(__('Prices')); ?></th>
                            <th><?php echo e(__('Flags')); ?></th>
                            <th><?php echo e(__('Status')); ?></th>
                            <th class="text-end"><?php echo e(__('Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $carName = $translateName($item);
                                $brandName = $translateName($item->brand);
                                $modelName = $translateName($item->carModel);
                                $categoryName = $translateName($item->category);
                                $rowNumber = ($items->currentPage() - 1) * $items->perPage() + $loop->iteration;
                            ?>
                            <tr>
                                <td><?php echo e($rowNumber); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo e($resolveImage($item->default_image_path)); ?>" alt="<?php echo e($carName); ?>" class="car-thumb me-2">
                                        <div>
                                            <h6 class="mb-1 fs-14"><?php echo e($carName); ?></h6>
                                            <small class="text-muted"><?php echo e($brandName); ?> <?php if($modelName !== __('N/A')): ?> / <?php echo e($modelName); ?> <?php endif; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo e($categoryName); ?></td>
                                <td><?php echo e(optional($item->year)->year ?? __('N/A')); ?></td>
                                <td>
                                    <div class="small">
                                        <div><?php echo e(__('Daily')); ?>: <strong><?php echo e(is_null($item->daily_main_price) ? '-' : number_format((float) $item->daily_main_price, 2)); ?></strong></div>
                                        <div><?php echo e(__('Weekly')); ?>: <strong><?php echo e(is_null($item->weekly_main_price) ? '-' : number_format((float) $item->weekly_main_price, 2)); ?></strong></div>
                                        <div><?php echo e(__('Monthly')); ?>: <strong><?php echo e(is_null($item->monthly_main_price) ? '-' : number_format((float) $item->monthly_main_price, 2)); ?></strong></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flag-list">
                                        <?php if($item->is_featured): ?>
                                            <span class="flag-badge"><?php echo e(__('Featured')); ?></span>
                                        <?php endif; ?>
                                        <?php if($item->is_flash_sale): ?>
                                            <span class="flag-badge"><?php echo e(__('Flash Sale')); ?></span>
                                        <?php endif; ?>
                                        <?php if($item->free_delivery): ?>
                                            <span class="flag-badge"><?php echo e(__('Free Delivery')); ?></span>
                                        <?php endif; ?>
                                        <?php if($item->insurance_included): ?>
                                            <span class="flag-badge"><?php echo e(__('Insurance')); ?></span>
                                        <?php endif; ?>
                                        <?php if(!$item->is_featured && !$item->is_flash_sale && !$item->free_delivery && !$item->insurance_included): ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if($item->is_active): ?>
                                        <span class="status-pill ok"><i class="ti ti-circle-check"></i><?php echo e(__('Active')); ?></span>
                                    <?php else: ?>
                                        <span class="status-pill no"><i class="ti ti-circle-x"></i><?php echo e(__('Inactive')); ?></span>
                                    <?php endif; ?>

                                    <div class="mt-1">
                                        <?php if($item->status === 'available'): ?>
                                            <span class="badge bg-success-subtle text-success"><?php echo e(__('Available')); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger"><?php echo e(__('Not Available')); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="<?php echo e(route('admin.cars.edit', $item->id)); ?>" class="btn btn-sm btn-outline-primary" title="<?php echo e(__('Edit')); ?>">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.cars.edit_images', $item->id)); ?>" class="btn btn-sm btn-outline-info" title="<?php echo e(__('Images')); ?>">
                                            <i class="ti ti-photo"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.cars.destroy', $item->id)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('Are you sure you want to delete this car?')); ?>')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="<?php echo e(__('Delete')); ?>">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5"><?php echo e(__('No cars found for the selected filters.')); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($items instanceof \Illuminate\Pagination\AbstractPaginator): ?>
                <div class="mt-3">
                    <?php echo e($items->withQueryString()->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(function () {
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2({ width: '100%' });
            }

            const $brand = $('#brand');
            const $model = $('#model');
            const selectedModelId = <?php echo json_encode(request('model'), 15, 512) ?>;
            const modelsEndpointTemplate = <?php echo json_encode(route('admin.cars.models', ['brand' => '__brand__']), 512) ?>;
            const allModelsLabel = <?php echo json_encode(__('All Models'), 15, 512) ?>;

            function resetModels() {
                $model.empty().append(new Option(allModelsLabel, ''));
                $model.prop('disabled', true);
            }

            function loadModels(brandId, selectedId = null) {
                resetModels();

                if (!brandId) {
                    return;
                }

                $model.prop('disabled', false);

                $.get(modelsEndpointTemplate.replace('__brand__', brandId), function (models) {
                    models.forEach(function (model) {
                        const isSelected = selectedId && String(selectedId) === String(model.id);
                        const option = new Option(model.name, model.id, false, isSelected);
                        $model.append(option);
                    });

                    if (typeof $.fn.select2 !== 'undefined') {
                        $model.trigger('change.select2');
                    }
                });
            }

            const initialBrand = $brand.val();
            if (initialBrand) {
                loadModels(initialBrand, selectedModelId);
            } else {
                resetModels();
            }

            $brand.on('change', function () {
                loadModels($(this).val(), null);
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\cars\index.blade.php ENDPATH**/ ?>