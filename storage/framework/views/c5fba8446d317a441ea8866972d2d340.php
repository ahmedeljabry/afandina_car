<?php $__env->startSection('title', 'List of ' . $modelName); ?>

<?php $__env->startSection('page-title'); ?>
    <?php echo e($modelName); ?> List
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .filter-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .filter-section label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .filter-section .form-control {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 8px 12px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .filter-section .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .filter-section .btn {
            padding: 8px 15px;
            font-weight: 500;
        }

        .filter-section .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .filter-section .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .filter-section .fas {
            margin-left: 5px;
        }

        /* تحسينات للـ select2 */
        .select2-container--default .select2-selection--single {
            height: 38px;
            line-height: 38px;
            border: 1px solid #ced4da;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .card {
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
            margin-bottom: 1rem;
        }

        .table img.car-thumbnail {
            width: 100px;
            height: 70px;
            object-fit: cover;
            border-radius: 4px;
            transition: transform 0.3s ease;
        }

        .table img.car-thumbnail:hover {
            transform: scale(1.5);
            cursor: pointer;
        }

        .btn-group {
            gap: 5px;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('pages.admin.cars.partials.crud_header', ['activeStep' => 'list'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Filter Cars</h4>
        </div>
        <div class="card-body">
            <form id="carFilterForm" method="GET" class="row g-3">
                <div class="col-md-2">
                    <label for="brand" class="form-label">Brand</label>
                    <select name="brand" id="brand" class="form-control select2">
                        <option value="">All Brands</option>
                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($brand->id); ?>" <?php echo e(request('brand') == $brand->id ? 'selected' : ''); ?>>
                                <?php
                                    $translation = $brand->translations->where('locale', 'en')->first();
                                    $name = $translation ? $translation->name : ($brand->translations->first() ? $brand->translations->first()->name : 'N/A');
                                ?>
                                <?php echo e($name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="model" class="form-label">Model</label>
                    <select name="model" id="model" class="form-control select2" <?php echo e(!request('brand') ? 'disabled' : ''); ?>>
                        <option value="">All Models</option>
                        <?php if(request('brand')): ?>
                            <?php $__currentLoopData = $brands->find(request('brand'))->carModels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $translation = $model->translations->where('locale', 'en')->first();
                                    $name = $translation ? $translation->name : ($model->translations->first() ? $model->translations->first()->name : 'N/A');
                                ?>
                                <option value="<?php echo e($model->id); ?>" <?php echo e(request('model') == $model->id ? 'selected' : ''); ?>>
                                    <?php echo e($name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="category" class="form-label">Category</label>
                    <select name="category" id="category" class="form-control select2">
                        <option value="">All Categories</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $translation = $category->translations->where('locale', 'en')->first();
                                $name = $translation ? $translation->name : ($category->translations->first() ? $category->translations->first()->name : 'N/A');
                            ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                

                <div class="col-md-2">
                    <label for="year" class="form-label">Year</label>
                    <select name="year" id="year" class="form-control select2">
                        <option value="">All Years</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year->id); ?>" <?php echo e(request('year') == $year->id ? 'selected' : ''); ?>>
                                <?php echo e($year->year); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Search by name..."
                        value="<?php echo e(request('search')); ?>">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="<?php echo e(route('admin.cars.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Cars List -->
    <div class="card mt-4">
        <div class="card-header">
            <h4 class="card-title">Cars List</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Category</th>
                            <th>Rent Periods</th>
                            <th>Year</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <?php if($item->default_image_path): ?>
                                        <img src="<?php echo e(asset('storage/' . $item->default_image_path)); ?>" alt="Car Image"
                                            class="car-thumbnail" data-toggle="tooltip" title="Click to enlarge">
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $translation = $item->translations->where('locale', 'en')->first();
                                        $name = $translation ? $translation->name : ($item->translations->first() ? $item->translations->first()->name : 'N/A');
                                    ?>
                                    <?php echo e($name); ?>

                                </td>
                                <td>
                                    <?php
                                        $brand = $item->brand;
                                        $brandName = $brand && $brand->translations ?
                                            ($brand->translations->where('locale', 'en')->first()?->name ??
                                                $brand->translations->first()?->name ?? 'N/A') : 'N/A';
                                    ?>
                                    <?php echo e($brandName); ?>

                                </td>
                                <td>
                                    <?php
                                        $model = $item->carModel;
                                        $modelName = $model && $model->translations ?
                                            ($model->translations->where('locale', 'en')->first()?->name ??
                                                $model->translations->first()?->name ?? 'N/A') : 'N/A';
                                    ?>
                                    <?php echo e($modelName); ?>

                                </td>
                                <td>
                                    <?php
                                        $category = $item->category;
                                        $categoryName = $category && $category->translations ?
                                            ($category->translations->where('locale', 'en')->first()?->name ??
                                                $category->translations->first()?->name ?? 'N/A') : 'N/A';
                                    ?>
                                    <?php echo e($categoryName); ?>

                                </td>
                                <td>
                                    <?php
                                        $rentPeriod = $item->periods;
                                        foreach ($rentPeriod as $period) {
                                            $periodName = $period && $period->translations ?
                                                ($period->translations->where('locale', 'en')->first()?->name ??
                                                    $period->translations->first()?->name ?? 'N/A') : 'N/A';
                                            echo '<span class="badge bg-warning">' . $periodName . '</span> <br>';
                                        }
                                    ?>
                                </td>

                                <td><?php echo e(optional($item->year)->year ?? 'N/A'); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo e(route('admin.cars.edit', $item->id)); ?>" class="btn btn-sm btn-primary"
                                            data-toggle="tooltip" title="Edit Car">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.cars.edit_images', $item->id)); ?>" class="btn btn-sm btn-info"
                                            data-toggle="tooltip" title="Manage Images">
                                            <i class="fas fa-images"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.cars.destroy', $item->id)); ?>" method="POST"
                                            class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip"
                                                title="Delete Car"
                                                onclick="return confirm('Are you sure you want to delete this car?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="10" class="text-center">No cars found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <?php echo e($items->links()); ?>

            </div>
        </div>
</div><?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function () {
            // تفعيل tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // تفعيل select2 للقوائم المنسدلة
            $('.select2').select2({
                width: '100%',
                dir: 'rtl'
            });

            // تحديث الموديلات عند تغيير الماركة
            $('#brand').on('change', function () {
                var brandId = $(this).val();
                var modelSelect = $('#model');

                // إعادة تعيين قائمة الموديلات
                modelSelect.empty().append('<option value="">All Models</option>');

                if (brandId) {
                    // تفعيل قائمة الموديلات
                    modelSelect.prop('disabled', false);

                    // جلب الموديلات من السيرفر
                    $.get('/admin/cars/models/' + brandId, function (models) {
                        models.forEach(function (model) {
                            modelSelect.append(new Option(model.name, model.id));
                        });
                        modelSelect.trigger('change');
                    });
                } else {
                    // تعطيل قائمة الموديلات إذا لم يتم اختيار ماركة
                    modelSelect.prop('disabled', true);
                }
            });

            // إزالة الأحداث التلقائية وجعل البحث فقط عند الضغط على زر التصفية
            $('#carFilterForm').on('submit', function (e) {
                e.preventDefault();
                this.submit();
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\car_rental-src-2025-11-05\car_rental\resources\views/pages/admin/cars/index.blade.php ENDPATH**/ ?>