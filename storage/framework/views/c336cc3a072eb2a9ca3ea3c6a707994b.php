<?php $__env->startSection('title', ' تعديل '.$modelName ); ?>

<?php $__env->startSection('content'); ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"> تعديل <?php echo e($modelName); ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.'.$modelName.'.index')); ?>"><?php echo e($modelName); ?></a></li>
                            <li class="breadcrumb-item active"> تعديل <?php echo e($modelName); ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"> تعديل <?php echo e($modelName); ?></h3>
                            </div>
                            <!-- form start -->
                            <form action="<?php echo e(route('admin.'.$modelName.'.update', $item->id)); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="card-body">
                                    <!-- Brand Information -->
                                    <div class="form-group">
                                        <h4> معلومات <?php echo e($modelName); ?></h4>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="name_en"> اسم <?php echo e($modelName); ?> (بالإنجليزية) <span class="text-danger">*</span></label>
                                            <input type="text" name="name_en" class="form-control <?php $__errorArgs = ['name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name_en" value="<?php echo e(old('name_en', $item->name_en)); ?>" placeholder="أدخل اسم <?php echo e($modelName); ?> بالإنجليزية" required>
                                            <?php $__errorArgs = ['name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="name_ar"> اسم <?php echo e($modelName); ?> (بالعربية) <span class="text-danger">*</span></label>
                                            <input type="text" name="name_ar" class="form-control <?php $__errorArgs = ['name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name_ar" value="<?php echo e(old('name_ar', $item->name_ar)); ?>" placeholder="أدخل اسم <?php echo e($modelName); ?> بالعربية" required>
                                            <?php $__errorArgs = ['name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>

                                    <!-- Logo Upload -->
                                    <div class="form-group mt-4">
                                        <h4>تحميل الصورة</h4>
                                    </div>

                                    <div class="form-group">
                                        <div class="mb-3">
                                            <img id="imagePreview" src="<?php echo e(asset('storage/' . $item->category_image)); ?>" alt="Image Preview" class="brand-logo img-thumbnail" style="max-height: 100px;">
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="category_image" class="custom-file-input <?php $__errorArgs = ['category_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="category_image" onchange="previewImage(event)">
                                            <label class="custom-file-label" for="category_image">اختر صورة</label>
                                        </div>
                                        <?php $__errorArgs = ['logo_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <!-- Styled Horizontal Rule -->
                                    <hr style="border: 0; height: 1px; background: linear-gradient(to right, #007bff, #00d8ff); margin: 30px 0;">

                                    <!-- Meta Information -->
                                    <div class="form-group">
                                        <h4>معلومات السيو</h4>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="meta_title_en">العنوان التعريفي (بالإنجليزية)</label>
                                            <input type="text" name="meta_title_en" class="form-control <?php $__errorArgs = ['meta_title_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meta_title_en" value="<?php echo e(old('meta_title_en', $item->meta_title_en)); ?>" placeholder="أدخل العنوان التعريفي بالإنجليزية">
                                            <?php $__errorArgs = ['meta_title_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="meta_title_ar">العنوان التعريفي (بالعربية)</label>
                                            <input type="text" name="meta_title_ar" class="form-control <?php $__errorArgs = ['meta_title_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meta_title_ar" value="<?php echo e(old('meta_title_ar', $item->meta_title_ar)); ?>" placeholder="أدخل العنوان التعريفي بالعربية">
                                            <?php $__errorArgs = ['meta_title_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="meta_description_en">الوصف التعريفي (بالإنجليزية)</label>
                                            <textarea name="meta_description_en" class="form-control <?php $__errorArgs = ['meta_description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meta_description_en" placeholder="أدخل الوصف التعريفي بالإنجليزية"><?php echo e(old('meta_description_en', $item->meta_description_en)); ?></textarea>
                                            <?php $__errorArgs = ['meta_description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="meta_description_ar">الوصف التعريفي (بالعربية)</label>
                                            <textarea name="meta_description_ar" class="form-control <?php $__errorArgs = ['meta_description_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meta_description_ar" placeholder="أدخل الوصف التعريفي بالعربية"><?php echo e(old('meta_description_ar', $item->meta_description_ar)); ?></textarea>
                                            <?php $__errorArgs = ['meta_description_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="meta_keywords_en">الكلمات المفتاحية (بالإنجليزية)</label>
                                            <input type="text" name="meta_keywords_en" class="form-control <?php $__errorArgs = ['meta_keywords_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meta_keywords_en" value="<?php echo e(old('meta_keywords_en', $item->meta_keywords_en)); ?>" placeholder="أدخل الكلمات المفتاحية بالإنجليزية">
                                            <?php $__errorArgs = ['meta_keywords_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="meta_keywords_ar">الكلمات المفتاحية (بالعربية)</label>
                                            <input type="text" name="meta_keywords_ar" class="form-control <?php $__errorArgs = ['meta_keywords_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meta_keywords_ar" value="<?php echo e(old('meta_keywords_ar', $item->meta_keywords_ar)); ?>" placeholder="أدخل الكلمات المفتاحية بالعربية">
                                            <?php $__errorArgs = ['meta_keywords_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer d-flex justify-content-between align-items-center">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ التعديلات</button>
                                    <div id="loading_spinner" style="display: none;">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">جاري التحميل...</span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <!-- Include Tagify library -->
        <script>
            function previewImage(event) {
                const imagePreview = document.getElementById('imagePreview');
                const file = event.target.files[0];
                const reader = new FileReader();

                reader.onload = function() {
                    imagePreview.src = reader.result;
                    imagePreview.style.display = 'block';
                }

                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Tagify on keywords inputs
                new Tagify(document.getElementById('meta_keywords_en'), {
                    placeholder: 'أدخل الكلمات المفتاحية بالإنجليزية',
                });

                new Tagify(document.getElementById('meta_keywords_ar'), {
                    placeholder: 'أدخل الكلمات المفتاحية بالعربية',
                });
            });
        </script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\old\categories\edit.blade.php ENDPATH**/ ?>