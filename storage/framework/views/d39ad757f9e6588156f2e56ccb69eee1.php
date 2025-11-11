<?php $__env->startSection('title', 'Add ' . $modelName); ?>

<?php $__env->startSection('page-title'); ?>
    Add <?php echo e($modelName); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .preview-item {
            position: relative;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            text-align: center;
            overflow: hidden;
        }

        .preview-item img,
        .preview-item iframe {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .remove-preview {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-size: 14px;
            line-height: 30px;
            text-align: center;
            cursor: pointer;
        }

        .remove-preview:hover {
            background-color: #e60000;
        }

        .preview-item:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .loader-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loader {
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid #3498db;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
                <div class="card card-primary card-outline card-tabs shadow-lg">
                    <div class="card-header p-0 pt-1 border-bottom-0 bg-light">
                        <!-- Tabs Header -->
                        <ul class="nav nav-tabs nav-tabs-modern" id="custom-tabs-three-tab" role="tablist">
                            <!-- General Data Tab -->
                            <li class="nav-item">
                                <a class="nav-link active text-dark" id="custom-tabs-general-tab" data-toggle="pill" href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-general" aria-selected="true">
                                    <i class="fas fa-info-circle"></i> General Data
                                </a>
                            </li>
                            <!-- Translated Data Tab -->
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="custom-tabs-translated-tab" data-toggle="pill" href="#custom-tabs-translated" role="tab" aria-controls="custom-tabs-translated" aria-selected="false">
                                    <i class="fas fa-language"></i> Translated Data
                                </a>
                            </li>
                            <!-- SEO Data Tab -->
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="custom-tabs-seo-tab" data-toggle="pill" href="#custom-tabs-seo" role="tab" aria-controls="custom-tabs-seo" aria-selected="false">
                                    <i class="fas fa-search"></i> SEO Data
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <!-- Form -->
                        <form id="createCarForm" action="<?php echo e(route('admin.'.$modelName.'.store')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="tab-content tab-modern" id="custom-tabs-three-tabContent">
                                <!-- General Data Tab Content -->
                                <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-general-tab">

                                    <!-- General Data Tab Content -->
                                    <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-general-tab">
                                        <!-- Car Information -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Car Information</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="brand_id" class="font-weight-bold">Brand</label>
                                                            <select name="brand_id" id="brand_id" class="form-control shadow-sm select2 <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                <option value="">-- Select Brand --</option>
                                                                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($brand->id); ?>" <?php echo e(old('brand_id') == $brand->id ? 'selected' : ''); ?>>
                                                                        <?php echo e($brand->translations()->first()->name); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                            <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="model_id" class="font-weight-bold">Model</label>
                                                            <select name="model_id" id="model_id" class="form-control shadow-sm select2">
                                                                <option value="">-- Select Model --</option>
                                                                <!-- Models will be populated dynamically here -->
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="category_id" class="font-weight-bold">Car Category</label>
                                                            <select name="category_id" id="category_id" class="form-control shadow-sm select2 <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                <option value="">-- Select Category --</option>
                                                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                                                                        <?php echo e($category->translations()->first()->name); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                            <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="gearType_id" class="font-weight-bold">Gear Type</label>
                                                            <select name="gear_type_id" id="gear_type_id" class="form-control shadow-sm select2 <?php $__errorArgs = ['gear_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                <option value="">-- Select Gear Type --</option>
                                                                <?php $__currentLoopData = $gearTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gearType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($gearType->id); ?>" <?php echo e(old('gear_type_id') == $gearType->id ? 'selected' : ''); ?>>
                                                                        <?php echo e($gearType->translations()->first()->name); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                            <?php $__errorArgs = ['gear_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="color_id" class="font-weight-bold">Color</label>
                                                            <select name="color_id" id="color_id" class="form-control shadow-sm select2 <?php $__errorArgs = ['color_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                <option value="">-- Select Color --</option>
                                                                <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($color->id); ?>" style="background-color: <?php echo e($color->color_code); ?>; color: <?php echo e($color->color_code == '#FFFFFF' ? '#000000' : '#FFFFFF'); ?>;" <?php echo e(old('color_id') == $color->id ? 'selected' : ''); ?>>
                                                                        <?php echo e($color->translations()->first()->name); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                            <?php $__errorArgs = ['color_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="year_id" class="font-weight-bold">Car Year</label>
                                                            <select name="year_id" id="year_id" class="form-control shadow-sm select2">
                                                                <option value="">-- Select Year --</option>
                                                                <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($year->id); ?>" <?php echo e(old('year_id') == $year->id ? 'selected' : ''); ?>>
                                                                        <?php echo e($year->year); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                            <!-- Car Details -->
                                            <div class="card mb-4">
                                                <div class="card-header bg-light">
                                                    <h3 class="card-title">Car Details</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="door_count" class="font-weight-bold">Number of Doors</label>
                                                                <input type="number" name="door_count" class="form-control shadow-sm <?php $__errorArgs = ['door_count'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" max="6" min="1" id="door_count" value="<?php echo e(old('door_count')); ?>">
                                                                <?php $__errorArgs = ['door_count'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong><?php echo e($message); ?></strong>
                                                                    </span>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="luggage_capacity" class="font-weight-bold">Number Of Luggage Capacity</label>
                                                                <input type="number" name="luggage_capacity" class="form-control shadow-sm <?php $__errorArgs = ['luggage_capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" max="20" min="0" id="luggage_capacity" value="<?php echo e(old('luggage_capacity')); ?>">
                                                                <?php $__errorArgs = ['luggage_capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong><?php echo e($message); ?></strong>
                                                                    </span>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="passenger_capacity" class="font-weight-bold">Number of passenger</label>
                                                                <input type="number" name="passenger_capacity" class="form-control shadow-sm <?php $__errorArgs = ['passenger_capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" max="20" min="1" id="passenger_capacity" value="<?php echo e(old('passenger_capacity')); ?>">
                                                                <?php $__errorArgs = ['passenger_capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong><?php echo e($message); ?></strong>
                                                                    </span>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- Pricing Information -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Pricing Information</h3>
                                            </div>
                                            <div class="card-body">
                                                <label>Daily: </label>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="daily_main_price" class="font-weight-bold">Daily Main Price</label>
                                                            <input type="number" name="daily_main_price" class="form-control shadow-sm <?php $__errorArgs = ['daily_main_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="daily_main_price" value="<?php echo e(old('daily_main_price')); ?>">
                                                            <?php $__errorArgs = ['daily_main_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="daily_discount_price" class="font-weight-bold">Daily Price With Discount</label>
                                                            <input type="number" name="daily_discount_price" class="form-control shadow-sm <?php $__errorArgs = ['daily_discount_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="daily_discount_price" value="<?php echo e(old('daily_discount_price')); ?>">
                                                            <?php $__errorArgs = ['daily_discount_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="daily_mileage_included" class="font-weight-bold">Daily Mileage Included</label>
                                                            <input type="number" name="daily_mileage_included" class="form-control shadow-sm <?php $__errorArgs = ['daily_mileage_included'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="daily_mileage_included" value="<?php echo e(old('daily_mileage_included')); ?>">
                                                            <?php $__errorArgs = ['daily_mileage_included'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <label>Weakly: </label>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="weekly_main_price" class="font-weight-bold">Weekly Main Price</label>
                                                            <input type="text" name="weekly_main_price" class="form-control shadow-sm <?php $__errorArgs = ['weekly_main_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="weekly_main_price" value="<?php echo e(old('weekly_main_price')); ?>">
                                                            <?php $__errorArgs = ['weekly_main_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="weekly_discount_price" class="font-weight-bold">Weekly Price With Discount</label>
                                                            <input type="number" name="weekly_discount_price" class="form-control shadow-sm <?php $__errorArgs = ['weekly_discount_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="weekly_discount_price" value="<?php echo e(old('weekly_discount_price')); ?>">
                                                            <?php $__errorArgs = ['weekly_discount_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="weekly_mileage_included" class="font-weight-bold">Weekly Mileage Included</label>
                                                            <input type="number" name="weekly_mileage_included" class="form-control shadow-sm <?php $__errorArgs = ['weekly_mileage_included'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="weekly_mileage_included" value="<?php echo e(old('weekly_mileage_included')); ?>">
                                                            <?php $__errorArgs = ['weekly_mileage_included'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <label>Monthly: </label>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="monthly_main_price" class="font-weight-bold">Monthly Main Price</label>
                                                            <input type="number" name="monthly_main_price" class="form-control shadow-sm <?php $__errorArgs = ['monthly_main_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="monthly_main_price" value="<?php echo e(old('monthly_main_price')); ?>">
                                                            <?php $__errorArgs = ['monthly_main_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="monthly_discount_price" class="font-weight-bold">Monthly Price With Discount</label>
                                                            <input type="number" name="monthly_discount_price" class="form-control shadow-sm <?php $__errorArgs = ['monthly_discount_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="monthly_discount_price" value="<?php echo e(old('monthly_discount_price')); ?>">
                                                            <?php $__errorArgs = ['monthly_discount_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="monthly_mileage_included" class="font-weight-bold">Monthly Mileage Included</label>
                                                            <input type="number" name="monthly_mileage_included" class="form-control shadow-sm <?php $__errorArgs = ['monthly_mileage_included'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="monthly_mileage_included" value="<?php echo e(old('monthly_mileage_included')); ?>">
                                                            <?php $__errorArgs = ['monthly_mileage_included'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                        <!-- Car Features -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Car Features</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="insurance_included" class="custom-control-input" id="insurance_included" <?php echo e(old('insurance_included') ? 'checked' : ''); ?>>
                                                            <label class="custom-control-label" for="insurance_included">Insurance Included</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="free_delivery" class="custom-control-input" id="free_delivery" <?php echo e(old('free_delivery') ? 'checked' : ''); ?>>
                                                            <label class="custom-control-label" for="free_delivery">Free Delivery</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="crypto_payment_accepted" class="custom-control-input" id="crypto_payment_accepted" <?php echo e(old('crypto_payment_accepted') ? 'checked' : ''); ?>>
                                                            <label class="custom-control-label" for="crypto_payment_accepted">Crypto Payment Accepted</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="is_featured" class="custom-control-input" id="is_featured" <?php echo e(old('is_featured') ? 'checked' : ''); ?>>
                                                            <label class="custom-control-label" for="is_featured">Featured</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="is_flash_sale" class="custom-control-input" id="is_flash_sale" <?php echo e(old('is_flash_sale') ? 'checked' : ''); ?>>
                                                            <label class="custom-control-label" for="is_flash_sale">Flash Sale</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="only_on_afandina" class="custom-control-input" id="only_on_afandina" <?php echo e(old('only_on_afandina') ? 'checked' : ''); ?>>
                                                            <label class="custom-control-label" for="only_on_afandina">Only On Afandina</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Car Features</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="features" class="font-weight-bold">Features related to Post</label>
                                                        <select id="features" class="form-control car-select" name="features[]" multiple="multiple" style="width: 100%;">
                                                            <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($feature->id); ?>" data-icon="<?php echo e($feature->icon->icon_class); ?>">
                                                                    <?php echo e($feature->translations()->first()->name); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- For multiple images -->

                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Upload Media Files</h3>
                                            </div>
                                            <div class="card-body">

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Default Image</label>
                                                            <div class="custom-file">
                                                                <input type="file" name="default_image_path" class="custom-file-input image-upload" id="default_image_path" data-preview="imagePreviewLogo">
                                                                <label class="custom-file-label" for="default_image_path">Upload Default Image</label>
                                                            </div>
                                                            <div class="mt-3">
                                                                <img id="imagePreviewLogo" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='250' height='250' viewBox='0 0 250 250'%3E%3Crect width='100%25' height='100%25' fill='%23ddd'/%3E%3Ctext x='50%25' y='50%25' fill='%23555' font-size='20' text-anchor='middle' dy='.3em'%3E600x200%3C/text%3E%3C/svg%3E" alt="Logo Preview" class="shadow image-rectangle-preview" style="max-height: 250px; width: 250px; object-fit: cover; border: 2px solid #ddd;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label for="file_path">Upload Media Files (Images & Videos):</label>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" name="media[]" id="media-files" multiple accept="image/*,video/mp4,video/webm,video/ogg">
                                                                <label class="custom-file-label" for="media-files">Choose files</label>
                                                            </div>
                                                            <small class="form-text text-muted">
                                                                Supported formats: Images (JPG, PNG, GIF, WebP) and Videos (MP4, WebM, OGG). Maximum file size: 100MB
                                                            </small>
                                                        </div>

                                                        <div id="preview" class="preview-grid mt-3">
                                                            <!-- Preview items will be added here -->
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="alt">Alt Text (for all):</label>
                                                            <input type="text" class="form-control" name="alt" placeholder="Add alt text for images or videos">
                                                        </div>

                                                        <!-- Preview Section -->
                                                        <h2 class="mt-5">Preview</h2>
                                                        <div id="preview" class="row preview-grid"></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>






                                        <!-- Activation Status -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Status</h3>
                                            </div>
                                            <div class="card-body">

                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label">Is Active?</label>

                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" <?php echo e(old('is_active') ? 'checked' : ''); ?>>
                                                            <label class="custom-control-label" for="is_active">Active</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="status" class="font-weight-bold">Car Status</label>
                                                            <select name="status" id="status" class="form-control shadow-sm <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                <option value="">-- Select Car Status --</option>
                                                                <option value="available" <?php echo e(old('status') == "available" ? 'selected' : ''); ?>>
                                                                    Available
                                                                </option>
                                                                <option value="not_available" <?php echo e(old('status') == "not_available" ? 'selected' : ''); ?>>
                                                                    Not Available
                                                                </option>
                                                            </select>
                                                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Translated Data Tab Content with Sub-tabs for Languages -->
                                <div class="tab-pane fade" id="custom-tabs-translated" role="tabpanel" aria-labelledby="custom-tabs-translated-tab">
                                    <div class="card shadow-sm border-0 mb-4">
                                        <div class="card-body d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                                            <div class="d-flex align-items-center mb-3 mb-lg-0">
                                                <span class="badge badge-primary mr-3"><i class="fas fa-robot"></i></span>
                                                <div>
                                                    <h5 class="mb-1 font-weight-bold">AI Content Assistant</h5>
                                                    <p class="mb-0 text-muted">Choose the language you want to auto-fill. Existing text in that language will be replaced.</p>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-wrap ai-language-buttons">
                                                <button type="button" class="btn btn-success mb-2 mr-2 generate-ai-all">
                                                    <i class="fas fa-magic mr-1"></i> Generate Content
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?php if($loop->first): ?> active <?php endif; ?> bg-light text-dark" id="pills-<?php echo e($lang->code); ?>-tab" data-toggle="pill" href="#pills-<?php echo e($lang->code); ?>" role="tab" aria-controls="pills-<?php echo e($lang->code); ?>" aria-selected="true"><?php echo e($lang->name); ?></a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <div class="tab-content shadow-sm p-3 mb-4 bg-white rounded" id="pills-tabContent">
                                        <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="tab-pane fade <?php if($loop->first): ?> show active <?php endif; ?>" id="pills-<?php echo e($lang->code); ?>" role="tabpanel" aria-labelledby="pills-<?php echo e($lang->code); ?>-tab">
                                                <div class="form-group">
                                                    <label for="name_<?php echo e($lang->code); ?>" class="font-weight-bold">Name (<?php echo e($lang->name); ?>)</label>
                                                    <input type="text" name="name[<?php echo e($lang->code); ?>]" class="form-control form-control-lg shadow-sm <?php $__errorArgs = ['name.'.$lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name_<?php echo e($lang->code); ?>" value="<?php echo e(old('name.'.$lang->code)); ?>">
                                                    <?php $__errorArgs = ['name.'.$lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong><?php echo e($message); ?></strong>
                                                        </span>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description_<?php echo e($lang->code); ?>" class="font-weight-bold">Description (<?php echo e($lang->name); ?>)</label>
                                                    <textarea name="description[<?php echo e($lang->code); ?>]" class="form-control form-control-lg shadow-sm <?php $__errorArgs = ['description.'.$lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description_<?php echo e($lang->code); ?>" rows="4"><?php echo e(old('description.'.$lang->code)); ?></textarea>
                                                    <?php $__errorArgs = ['description.'.$lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong><?php echo e($message); ?></strong>
                                                        </span>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>


                                                <div class="form-group">
                                                    <label for="long_description_<?php echo e($lang->code); ?>" class="font-weight-bold">Long Description (<?php echo e($lang->name); ?>)</label>
                                                    <textarea name="long_description[<?php echo e($lang->code); ?>]" class="form-control form-control-lg shadow-sm teny-editor <?php $__errorArgs = ['long_description.'.$lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="long_description_<?php echo e($lang->code); ?>"><?php echo e(old('long_description.'.$lang->code)); ?></textarea>
                                                    <?php $__errorArgs = ['long_description.'.$lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong><?php echo e($message); ?></strong>
                                                        </span>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>

                                <!-- SEO Data Tab Content -->
                                <div class="tab-pane fade" id="custom-tabs-seo" role="tabpanel" aria-labelledby="custom-tabs-seo-tab">
                                    <ul class="nav nav-pills mb-3" id="pills-seo-tab" role="tablist">
                                        <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?php if($loop->first): ?> active <?php endif; ?> bg-light text-dark" id="pills-seo-<?php echo e($lang->code); ?>-tab" data-toggle="pill" href="#pills-seo-<?php echo e($lang->code); ?>" role="tab" aria-controls="pills-seo-<?php echo e($lang->code); ?>" aria-selected="true"><?php echo e($lang->name); ?></a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <div class="tab-content shadow-sm p-3 mb-4 bg-white rounded" id="pills-seo-tabContent">
                                        <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="tab-pane fade <?php if($loop->first): ?> show active <?php endif; ?>" id="pills-seo-<?php echo e($lang->code); ?>" role="tabpanel" aria-labelledby="pills-seo-<?php echo e($lang->code); ?>-tab">
                                                <div class="form-group">
                                                    <label for="meta_title_<?php echo e($lang->code); ?>" class="font-weight-bold">Meta Title (<?php echo e($lang->name); ?>)</label>
                                                    <input type="text" name="meta_title[<?php echo e($lang->code); ?>]" class="form-control form-control-lg shadow-sm <?php $__errorArgs = ['meta_title.'.$lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meta_title_<?php echo e($lang->code); ?>" value="<?php echo e(old('meta_title.'.$lang->code)); ?>">
                                                    <?php $__errorArgs = ['meta_title.'.$lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong><?php echo e($message); ?></strong>
                                                        </span>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                                <div class="form-group">
                                                    <label for="meta_description_<?php echo e($lang->code); ?>" class="font-weight-bold">Meta Description (<?php echo e($lang->name); ?>)</label>
                                                    <textarea name="meta_description[<?php echo e($lang->code); ?>]" class="form-control form-control-lg shadow-sm <?php $__errorArgs = ['meta_description.'.$lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meta_description_<?php echo e($lang->code); ?>" rows="3"><?php echo e(old('meta_description.'.$lang->code)); ?></textarea>
                                                    <?php $__errorArgs = ['meta_description.'.$lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong><?php echo e($message); ?></strong>
                                                        </span>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                                <div class="form-group">
                                                    <label for="meta_keywords_<?php echo e($lang->code); ?>" class="font-weight-bold">Meta Keywords (<?php echo e($lang->name); ?>)</label>
                                                    <input type="text" name="meta_keywords[<?php echo e($lang->code); ?>]" class="form-control form-control-lg shadow-sm <?php $__errorArgs = ['meta_keywords.'.$lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meta_keywords_<?php echo e($lang->code); ?>" data-role="tagsinput" value="<?php echo e(old('meta_keywords.'.$lang->code)); ?>">
                                                    <?php $__errorArgs = ['meta_keywords.'.$lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong><?php echo e($message); ?></strong>
                                                        </span>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>

                                                <div class="row card">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="robots_index_<?php echo e($lang->code); ?>" class="font-weight-bold">
                                                                Robot Index (<?php echo e($lang->name); ?>)
                                                            </label>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox"
                                                                       name="robots_index[<?php echo e($lang->code); ?>]"
                                                                       class="custom-control-input"
                                                                       id="robots_index_<?php echo e($lang->code); ?>"
                                                                       value="index"
                                                                    <?php echo e(old('robots_index.'.$lang->code, $currentValues['robots_index'][$lang->code] ?? '') === 'index' ? 'checked' : ''); ?>>
                                                                <label class="custom-control-label" for="robots_index_<?php echo e($lang->code); ?>">Index</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="robots_follow_<?php echo e($lang->code); ?>" class="font-weight-bold">
                                                                Robot Follow (<?php echo e($lang->name); ?>)
                                                            </label>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox"
                                                                       name="robots_follow[<?php echo e($lang->code); ?>]"
                                                                       class="custom-control-input"
                                                                       id="robots_follow_<?php echo e($lang->code); ?>"
                                                                       value="follow"
                                                                    <?php echo e(old('robots_follow.'.$lang->code, $currentValues['robots_follow'][$lang->code] ?? '') === 'follow' ? 'checked' : ''); ?>>
                                                                <label class="custom-control-label" for="robots_follow_<?php echo e($lang->code); ?>">Follow</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Dynamic SEO Questions/Answers Section -->
                                                <div class="seo-questions-container" id="seo-questions-<?php echo e($lang->code); ?>">
                                                    <label class="font-weight-bold">SEO Questions/Answers (<?php echo e($lang->name); ?>)</label>
                                                    <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                                                        <div class="form-group">
                                                            <input type="text" name="seo_questions[<?php echo e($lang->code); ?>][0][question]" class="form-control form-control-lg shadow-sm mb-2" placeholder="Enter Question" />
                                                        </div>
                                                        <div class="form-group">
                                                            <textarea name="seo_questions[<?php echo e($lang->code); ?>][0][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer"></textarea>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-info add-question mt-3" data-lang="<?php echo e($lang->code); ?>">
                                                    <i class="fas fa-plus"></i> Add Question
                                                </button>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-success btn-lg mt-3">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </form>
                    </div>
                </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Array to store selected media files
        let selectedFiles = [];
        const tagifyInstances = {};
        const aiLanguages = <?php echo json_encode($activeLanguages->pluck('code')->toArray(), 15, 512) ?>;

        function escapeHtml(value) {
            if (value === null || value === undefined) {
                return '';
            }

            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function buildSeoQuestionGroup(lang, index, qa = {question: '', answer: ''}) {
            const question = escapeHtml(qa.question ?? '');
            const answer = escapeHtml(qa.answer ?? '');

            return `
                <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                    <div class="form-group">
                        <input type="text" name="seo_questions[${lang}][${index}][question]" class="form-control form-control-lg shadow-sm mb-2" placeholder="Enter Question" value="${question}" />
                    </div>
                    <div class="form-group">
                        <textarea name="seo_questions[${lang}][${index}][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer">${answer}</textarea>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                </div>
            `;
        }

        function collectAiContext(lang) {
            const context = {};

            const brandOption = $('#brand_id option:selected');
            context.brand = brandOption.val() ? brandOption.text().trim() : null;

            const modelOption = $('#model_id option:selected');
            context.model = modelOption.val() ? modelOption.text().trim() : null;

            const yearOption = $('#year_id option:selected');
            context.year = yearOption.val() ? yearOption.text().trim() : null;

            const colorOption = $('#color_id option:selected');
            context.color = colorOption.val() ? colorOption.text().trim() : null;

            const gearTypeOption = $('#gear_type_id option:selected');
            context.gear_type = gearTypeOption.val() ? gearTypeOption.text().trim() : null;

            const categoryNames = $('#category_id option:selected').map(function () {
                const value = $(this).val();
                return value ? $(this).text().trim() : null;
            }).get().filter(Boolean);

            context.categories = categoryNames;
            context.primary_category = categoryNames[0] || null;

            const englishNameField = $('#name_en');
            const englishName = englishNameField.length ? englishNameField.val().trim() : '';
            if (englishName.length) {
                context.original_name = englishName;
            }

            context.target_language = lang;

            const featureNames = $('#features option:selected').map(function () {
                const text = $(this).text().trim();
                return text.length ? text : null;
            }).get().filter(Boolean);

            context.features = featureNames;

            context.daily_price = $('#daily_main_price').val();
            context.weekly_price = $('#weekly_main_price').val();
            context.monthly_price = $('#monthly_main_price').val();
            context.passenger_capacity = $('#passenger_capacity').val();
            context.door_count = $('#door_count').val();
            context.luggage_capacity = $('#luggage_capacity').val();
            context.daily_main_price = $('#daily_main_price').val();
            context.daily_discount_price = $('#daily_discount_price').val();
            context.daily_mileage_included = $('#daily_mileage_included').val();
            context.weekly_main_price = $('#weekly_main_price').val();
            context.weekly_discount_price = $('#weekly_discount_price').val();
            context.weekly_mileage_included = $('#weekly_mileage_included').val();
            context.monthly_main_price = $('#monthly_main_price').val();
            context.monthly_discount_price = $('#monthly_discount_price').val();
            context.monthly_mileage_included = $('#monthly_mileage_included').val();

            context.insurance_included = $('#insurance_included').is(':checked');
            context.free_delivery = $('#free_delivery').is(':checked');
            context.is_flash_sale = $('#is_flash_sale').is(':checked');
            context.is_featured = $('#is_featured').is(':checked');
            context.only_on_afandina = $('#only_on_afandina').is(':checked');
            context.crypto_payment_accepted = $('#crypto_payment_accepted').is(':checked');

            return context;
        }

        function populateAiContent(lang, data) {
            if (!data) {
                return;
            }

            if (data.name) {
                $('#name_' + lang).val(data.name);
            }

            if (data.description) {
                $('#description_' + lang).val(data.description);
            }

            if (data.long_description) {
                $('#long_description_' + lang).val(data.long_description);
            }

            if (data.meta_title) {
                $('#meta_title_' + lang).val(data.meta_title);
            }

            if (data.meta_description) {
                $('#meta_description_' + lang).val(data.meta_description);
            }

            if (Array.isArray(data.meta_keywords)) {
                if (tagifyInstances[lang]) {
                    tagifyInstances[lang].removeAllTags();
                    tagifyInstances[lang].addTags(data.meta_keywords);
                } else {
                    $('#meta_keywords_' + lang).val(data.meta_keywords.join(', '));
                }
            }

            if (Array.isArray(data.seo_questions)) {
                const container = $('#seo-questions-' + lang);
                container.empty();

                data.seo_questions.forEach(function (qa, index) {
                    container.append(buildSeoQuestionGroup(lang, index, qa));
                });

                if (data.seo_questions.length === 0) {
                    container.append(buildSeoQuestionGroup(lang, 0));
                }
            }
        }

        function populateGeneralFields(data) {
            if (!data || typeof data !== 'object') {
                return;
            }

            const mappings = {
                door_count: '#door_count',
                luggage_capacity: '#luggage_capacity',
                passenger_capacity: '#passenger_capacity',
                daily_main_price: '#daily_main_price',
                daily_discount_price: '#daily_discount_price',
                daily_mileage_included: '#daily_mileage_included',
                weekly_main_price: '#weekly_main_price',
                weekly_discount_price: '#weekly_discount_price',
                weekly_mileage_included: '#weekly_mileage_included',
                monthly_main_price: '#monthly_main_price',
                monthly_discount_price: '#monthly_discount_price',
                monthly_mileage_included: '#monthly_mileage_included'
            };

            Object.entries(mappings).forEach(([key, selector]) => {
                const value = data[key];
                if (value !== undefined && value !== null && value !== '') {
                    const input = $(selector);
                    if (input.length) {
                        input.val(value);
                    }
                }
            });
        }

        // Handle media file preview
        document.getElementById('media-files').addEventListener('change', function(event) {
            var files = Array.from(event.target.files);
            selectedFiles = selectedFiles.concat(files);
            displayMediaPreviews();
        });

        // Function to display media previews
        function displayMediaPreviews() {
            var previewDiv = document.getElementById('preview');
            previewDiv.innerHTML = ''; // Clear previous previews

            selectedFiles.forEach((file, index) => {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let div = document.createElement('div');
                    div.classList.add('preview-item');
                    div.setAttribute('data-index', index);

                    if (file.type.startsWith('image/')) {
                        div.setAttribute('data-type', 'image');
                        div.innerHTML = `
                            <img src="${e.target.result}" class="img-fluid">
                            <button type="button" class="remove-preview" data-type="image" data-index="${index}">×</button>
                        `;
                    } else if (file.type.startsWith('video/')) {
                        div.setAttribute('data-type', 'video');
                        div.innerHTML = `
                            <video src="${e.target.result}" controls class="img-fluid"></video>
                            <button type="button" class="remove-preview" data-type="video" data-index="${index}">×</button>
                        `;
                    }
                    previewDiv.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        // Event delegation for preview removal
        document.getElementById('preview').addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-preview')) {
                let previewType = event.target.getAttribute('data-type');
                let inputIndex = event.target.getAttribute('data-index');
                let previewItem = event.target.closest('.preview-item');

                if (previewType === 'youtube') {
                    let input = document.querySelector(`.youtube-link[data-index="${inputIndex}"]`);
                    if (input) {
                        input.value = '';
                        delete input.dataset.previewId;
                    }
                } else {
                    selectedFiles.splice(inputIndex, 1);
                    displayMediaPreviews();
                    return;
                }
                previewItem.remove();
            }
        });

        $(document).ready(function() {
            // Function to dynamically add SEO Questions/Answers
            $('.add-question').on('click', function() {
                var lang = $(this).data('lang');
                var container = $('#seo-questions-' + lang);
                var count = container.find('.seo-question-group').length;
                console.log('Adding question for language:', lang, 'Count:', count); // Debugging line
                var newQuestionGroup = `
                    <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                        <div class="form-group">
                            <input type="text" name="seo_questions[` + lang + `][` + count + `][question]" class="form-control form-control-lg shadow-sm mb-2" placeholder="Enter Question" />
                        </div>
                        <div class="form-group">
                            <textarea name="seo_questions[` + lang + `][` + count + `][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer"></textarea>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                    </div>`;
                container.append(newQuestionGroup);
            });

            // Function to remove an SEO Question/Answer
            $(document).on('click', '.remove-question', function() {
                $(this).closest('.seo-question-group').remove();
            });


            <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            {
                const metaKeywordsInput = document.querySelector('#meta_keywords_<?php echo e($lang->code); ?>');
                if (metaKeywordsInput && typeof Tagify !== 'undefined') {
                    tagifyInstances['<?php echo e($lang->code); ?>'] = new Tagify(metaKeywordsInput, {
                        placeholder: 'Enter meta keywords'
                    });
                } else if (metaKeywordsInput) {
                    metaKeywordsInput.setAttribute('placeholder', 'Enter meta keywords (comma separated)');
                }
            }
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        });


        $(document).ready(function() {
            $('#brand_id').change(function() {
                var brandId = $(this).val();
                var modelSelect = $('#model_id');

                modelSelect.empty().append('<option value="">-- Select Model --</option>');

                if (brandId) {
                    $.ajax({
                        url: "<?php echo e(route('admin.get.models', '')); ?>/" + brandId,
                        type: "GET",
                        success: function(data) {
                            data.forEach(function(model) {
                                modelSelect.append('<option value="' + model.id + '">' + model.name + '</option>');
                            });
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
            // Handle form submission
            $('#createCarForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var submitBtn = form.find('button[type="submit"]');
                var formData = new FormData(this);

                // Show loading overlay
                $('#loader-overlay').css('display', 'flex');

                // Disable submit button
                submitBtn.prop('disabled', true);

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Hide loader
                        $('#loader-overlay').hide();

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: true,
                                confirmButtonText: 'OK',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = response.redirect;
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        // Hide loading overlay
                        $('#loader-overlay').hide();

                        // Enable submit button
                        submitBtn.prop('disabled', false);

                        var errors = xhr.responseJSON.errors;

                        // Clear previous errors
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();

                        if (errors) {
                            // Show error alert at the top
                            var errorHtml = '<div class="alert alert-danger alert-dismissible fade show shadow-sm mt-3 p-4 rounded-lg" role="alert">' +
                                '<div class="d-flex">' +
                                '<i class="fas fa-exclamation-triangle mr-2" style="font-size: 24px;"></i>' +
                                '<div class="flex-grow-1">' +
                                '<h5 class="alert-heading mb-2">Please correct the following errors:</h5>' +
                                '<ul class="mb-0 pl-3">';

                            $.each(errors, function(key, messages) {
                                messages.forEach(function(message) {
                                    errorHtml += '<li>' + message + '</li>';

                                    // Add error class and message to form field
                                    var input = $('[name="' + key + '"]');
                                    if (input.length) {
                                        input.addClass('is-invalid');
                                        if (!input.next('.invalid-feedback').length) {
                                            input.after('<div class="invalid-feedback">' + message + '</div>');
                                        }
                                    }
                                });
                            });

                            errorHtml += '</ul></div>' +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                '<span aria-hidden="true">&times;</span>' +
                                '</button>' +
                                '</div></div>';

                            // Remove any existing error alerts
                            $('.alert-danger').remove();
                            // Add the new error alert at the top of the form
                            form.before(errorHtml);
                        }

                        // Also show in SweetAlert
                        var errorMessage = '<ul class="list-unstyled mb-0">';
                        $.each(errors, function(key, messages) {
                            messages.forEach(function(message) {
                                errorMessage += '<li><i class="fas fa-times-circle mr-2" style="color: #f44336;"></i>' + message + '</li>';
                            });
                        });
                        errorMessage += '</ul>';

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error!',
                            html: errorMessage,
                            showConfirmButton: true,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });

        function generateContentForLanguage(lang, options = {}) {
            const opts = Object.assign({
                button: null,
                manageLoader: true,
                silent: false
            }, options);

            return new Promise((resolve, reject) => {
                const nameField = $('#name_' + lang);
                let nameValue = (nameField.val() || '').trim();
                const englishNameField = $('#name_en');
                const englishName = englishNameField.length ? englishNameField.val().trim() : '';

                if (!nameValue.length && lang !== 'en' && englishName.length) {
                    nameValue = englishName;
                }

                if (!nameValue.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Car name required',
                        text: 'Please enter at least one car name so the AI can generate content.'
                    });
                    nameField.focus();
                    return reject(new Error('Car name required for AI generation (' + lang.toUpperCase() + ')'));
                }

                const payload = {
                    language: lang,
                    name: nameValue,
                    context: collectAiContext(lang)
                };

                const button = opts.button && opts.button.length ? opts.button : null;
                let originalHtml = null;
                if (button) {
                    originalHtml = button.html();
                    button.data('original-html', originalHtml);
                    button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Generating...');
                }

                if (opts.manageLoader) {
                    $('#loader-overlay').css('display', 'flex');
                }

                $.ajax({
                    url: "<?php echo e(route('admin.cars.generate-content')); ?>",
                    method: 'POST',
                    data: JSON.stringify(payload),
                    contentType: 'application/json',
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        if (response && response.success) {
                            populateAiContent(lang, response.data);
                            const tabTrigger = $('#pills-' + lang + '-tab');
                            if (tabTrigger.length) {
                                tabTrigger.tab('show');
                            }
                            if (!opts.silent) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'AI content generated',
                                    text: 'Review the generated ' + lang.toUpperCase() + ' copy and adjust it as needed.',
                                    timer: 2500,
                                    showConfirmButton: false
                                });
                            }
                            resolve(response.data);
                        } else {
                            const message = response && response.message ? response.message : 'Unable to generate AI content right now.';
                            if (!opts.silent) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Generation failed',
                                    text: message
                                });
                            }
                            reject(new Error(message));
                        }
                    },
                    error: function(xhr) {
                        let message = 'Unable to generate AI content right now.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        if (!opts.silent) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Generation failed',
                                text: message
                            });
                        }
                        reject(new Error(message));
                    },
                    complete: function() {
                        if (opts.manageLoader) {
                            $('#loader-overlay').hide();
                        }
                        if (button) {
                            button.prop('disabled', false).html(button.data('original-html'));
                        }
                    }
                });
            });
        }

        $(document).ready(function() {
            $(document).on('click', '.generate-ai-content', function() {
                const $button = $(this);
                const lang = $button.data('lang');
                generateContentForLanguage(lang, { button: $button })
                    .catch(() => {});
            });

            $(document).on('click', '.generate-ai-all', async function() {
                const $button = $(this);
                if (!aiLanguages.length) {
                    Swal.fire({
                        icon: 'info',
                        title: 'No languages configured',
                        text: 'Please add at least one active language before using AI generation.'
                    });
                    return;
                }

                const englishNameField = $('#name_en');
                const englishName = englishNameField.length ? englishNameField.val().trim() : '';

                if (!englishName) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Add English name',
                        text: 'Enter the car name in English before generating AI content.'
                    });
                    if (englishNameField.length) {
                        englishNameField.focus();
                    }
                    return;
                }

                const originalHtml = $button.html();
                $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Generating...');
                $('#loader-overlay').css('display', 'flex');

                const languages = Array.from(new Set(['en', ...aiLanguages]));

                try {
                    for (const lang of languages) {
                        const data = await generateContentForLanguage(lang, {
                            manageLoader: false,
                            silent: true
                        });

                        if (lang === 'en') {
                            populateGeneralFields(data);
                        }
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'All content generated',
                        text: 'General details and translations were filled automatically. Review before saving.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                } catch (error) {
                    const message = error && error.message ? error.message : 'Unable to generate AI content right now.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Generation interrupted',
                        text: message
                    });
                } finally {
                    $('#loader-overlay').hide();
                    $button.prop('disabled', false).html(originalHtml);
                }
            });
        });

        $(document).ready(function() {
            function formatFeature(feature) {
                if (!feature.id) {
                    return feature.text;
                }

                var $feature = $(
                    '<span><i class="' + $(feature.element).data('icon') + '"></i> ' +
                    feature.text + '</span>'
                );
                return $feature;
            }

            $('.car-select').select2({
                templateResult: formatFeature,
                templateSelection: formatFeature,
                allowClear: true,
                placeholder: "Select features"
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\car_rental-src-2025-11-05\car_rental\resources\views/pages/admin/cars/create.blade.php ENDPATH**/ ?>