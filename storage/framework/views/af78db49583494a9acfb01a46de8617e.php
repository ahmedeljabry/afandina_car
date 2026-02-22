<?php $__env->startSection('title', 'إضافة ' . $modelName); ?>

<?php $__env->startSection('content'); ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">إضافة <?php echo e($modelName); ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                            <li class="breadcrumb-item active">إضافة <?php echo e($modelName); ?></li>
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
                                <h3 class="card-title">إضافة <?php echo e($modelName); ?></h3>
                            </div>
                            <!-- form start -->
                            <form action="<?php echo e(route('admin.' . $modelName . '.store')); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="card-body">

                                    <!-- Car Information -->
                                    <div class="form-group">
                                        <h4>معلومات <?php echo e($modelName); ?></h4>
                                    </div>
                                    <!-- Styled Horizontal Rule -->
                                    <hr style="border: 0; height: 1px; background: linear-gradient(to right, #007bff, #00d8ff); margin: 30px 0;">
                                    <!-- Name Fields -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="name_en">اسم السيارة (بالإنجليزية)</label>
                                            <input type="text" name="name_en" class="form-control <?php $__errorArgs = ['name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name_en" value="<?php echo e(old('name_en')); ?>" placeholder="أدخل اسم السيارة بالإنجليزية">
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
                                            <label for="name_ar">اسم السيارة (بالعربية)</label>
                                            <input type="text" name="name_ar" class="form-control <?php $__errorArgs = ['name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name_ar" value="<?php echo e(old('name_ar')); ?>" placeholder="أدخل اسم السيارة بالعربية">
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

                                    <!-- Brand, Category, Car Type -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="brand_id">العلامة التجارية</label>
                                            <select name="brand_id" id="brand_id" class="form-control <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <option value="">اختر العلامة التجارية</option>
                                                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($brand->id); ?>" <?php echo e(old('brand_id') == $brand->id ? 'selected' : ''); ?>>
                                                        <?php echo e($brand->name_en); ?> (<?php echo e($brand->name_ar); ?>)
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['brand_id'];
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
                                            <label for="category_id">الفئة</label>
                                            <select name="category_id" id="category_id" class="form-control <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <option value="">اختر الفئة</option>
                                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                                                        <?php echo e($category->name_en); ?> (<?php echo e($category->name_ar); ?>)
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['category_id'];
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



                                    <!-- Lookup Fields -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="body_style_id">نمط الجسم</label>
                                            <select name="body_style_id" id="body_style_id" class="form-control <?php $__errorArgs = ['body_style_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <option value="">اختر نمط الجسم</option>
                                                <?php $__currentLoopData = $bodyStyles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bodyStyle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($bodyStyle->id); ?>" <?php echo e(old('body_style_id') == $bodyStyle->id ? 'selected' : ''); ?>>
                                                        <?php echo e($bodyStyle->name_en); ?> (<?php echo e($bodyStyle->name_ar); ?>)
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['body_style_id'];
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
                                            <label for="car_maker_id">صانع السيارة</label>
                                            <select name="car_maker_id" id="car_maker_id" class="form-control <?php $__errorArgs = ['car_maker_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <option value="">اختر صانع السيارة</option>
                                                <?php $__currentLoopData = $carMakers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $carMaker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($carMaker->id); ?>" <?php echo e(old('car_maker_id') == $carMaker->id ? 'selected' : ''); ?>>
                                                        <?php echo e($carMaker->name_en); ?> (<?php echo e($carMaker->name_ar); ?>)
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['car_maker_id'];
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

                                    <div class="form-group">
                                        <label for="includes">المميزات الاضافية:</label>
                                        <?php $__currentLoopData = $includedFeatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $include): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="includes[]" value="<?php echo e($include->id); ?>" id="include_<?php echo e($include->id); ?>" <?php echo e(in_array($include->id, old('includes', [])) ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="include_<?php echo e($include->id); ?>"><?php echo e($include->name_en); ?> (<?php echo e($include->name_ar); ?>)</label>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>



                                    <hr style="margin-top: 10px;">
                                    <!-- SEO Fields -->
                                    <div class="form-group">
                                        <h4>بيانات SEO</h4>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="meta_title_en">Meta Title (بالإنجليزية)</label>
                                            <input type="text" name="meta_title_en" class="form-control <?php $__errorArgs = ['meta_title_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meta_title_en" value="<?php echo e(old('meta_title_en')); ?>" placeholder="أدخل Meta Title بالإنجليزية">
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
                                            <label for="meta_title_ar">Meta Title (بالعربية)</label>
                                            <input type="text" name="meta_title_ar" class="form-control <?php $__errorArgs = ['meta_title_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meta_title_ar" value="<?php echo e(old('meta_title_ar')); ?>" placeholder="أدخل Meta Title بالعربية">
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
                                            <label for="meta_description_en">Meta Description (بالإنجليزية)</label>
                                            <textarea name="meta_description_en" class="form-control <?php $__errorArgs = ['meta_description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meta_description_en" rows="3" placeholder="أدخل Meta Description بالإنجليزية"><?php echo e(old('meta_description_en')); ?></textarea>
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
                                            <label for="meta_description_ar">Meta Description (بالعربية)</label>
                                            <textarea name="meta_description_ar" class="form-control <?php $__errorArgs = ['meta_description_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meta_description_ar" rows="3" placeholder="أدخل Meta Description بالعربية"><?php echo e(old('meta_description_ar')); ?></textarea>
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
                                            <label for="meta_keywords_en">Meta Keywords (بالإنجليزية)</label>
                                            <textarea name="meta_keywords_en" class="form-control <?php $__errorArgs = ['meta_keywords_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meta_keywords_en" rows="3" placeholder="أدخل Meta Keywords بالإنجليزية"><?php echo e(old('meta_keywords_en')); ?></textarea>
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
                                            <label for="meta_keywords_ar">Meta Keywords (بالعربية)</label>
                                            <textarea name="meta_keywords_ar" class="form-control <?php $__errorArgs = ['meta_keywords_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="meta_keywords_ar" rows="3" placeholder="أدخل Meta Keywords بالعربية"><?php echo e(old('meta_keywords_ar')); ?></textarea>
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


                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">حفظ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const imageUploadInput = document.getElementById('image-upload');
                const imagePreviewsContainer = document.getElementById('image-previews');
                const removedFilesInput = document.getElementById('removed-files');

                imageUploadInput.addEventListener('change', function () {
                    updatePreviews();
                });

                function updatePreviews() {
                    imagePreviewsContainer.innerHTML = ''; // Clear previous previews
                    const files = Array.from(imageUploadInput.files);

                    files.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const previewDiv = document.createElement('div');
                            previewDiv.classList.add('col-md-3', 'mb-3');

                            previewDiv.innerHTML = `
                        <div class="card">
                            <img src="${e.target.result}" class="card-img-top" alt="Preview Image" style="height: 150px; object-fit: cover;">
                            <div class="card-body text-center">
                                <input type="radio" name="default_image" value="${index}" ${index === 0 ? 'checked' : ''}>
                                <label>تعيين كافتراضي</label>
                                <button type="button" class="btn btn-sm btn-danger mt-2 remove-image" data-index="${index}">إزالة</button>
                            </div>
                        </div>
                    `;
                            imagePreviewsContainer.appendChild(previewDiv);
                        };
                        reader.readAsDataURL(file);
                    });
                }

                function attachRemoveListeners() {
                    imagePreviewsContainer.addEventListener('click', function (e) {
                        if (e.target && e.target.matches('.remove-image')) {
                            removeImage(e.target);
                        }
                    });
                }

                function removeImage(button) {
                    const indexToRemove = parseInt(button.getAttribute('data-index'));
                    removeFile(indexToRemove);
                }

                function removeFile(indexToRemove) {
                    const files = Array.from(imageUploadInput.files);
                    const remainingFiles = files.filter((_, index) => index !== indexToRemove);

                    // Create a new DataTransfer object
                    const dataTransfer = new DataTransfer();
                    remainingFiles.forEach(file => dataTransfer.items.add(file));

                    imageUploadInput.files = dataTransfer.files;

                    // Update the removed files input
                    let removedFileIndexes = removedFilesInput.value ? removedFilesInput.value.split(',') : [];
                    if (!removedFileIndexes.includes(indexToRemove.toString())) {
                        removedFileIndexes.push(indexToRemove);
                        removedFileIndexes = Array.from(new Set(removedFileIndexes)); // Remove duplicates
                        removedFilesInput.value = removedFileIndexes.join(',');
                    }

                    updatePreviews();
                }

                // Initial call to set up the event listeners
                attachRemoveListeners();
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>











<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\old\cars\create.blade.php ENDPATH**/ ?>