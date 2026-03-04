<?php $__env->startSection('title', 'عرض البراند'); ?>

<?php $__env->startSection('content'); ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"> عرض <?php echo e($modelName); ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.'.$modelName.'.index')); ?>">البراندات</a></li>
                            <li class="breadcrumb-item active"> عرض <?php echo e($modelName); ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"> معلومات <?php echo e($modelName); ?></h3>
                            </div>
                            <div class="card-body">
                                <!-- Brand Information -->
                                <div class="form-group">
                                    <h4><i class="fas fa-info-circle"></i> تفاصيل  <?php echo e($modelName); ?></h4>
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <th> اسم  <?php echo e($modelName); ?> (بالإنجليزية):</th>
                                            <td><?php echo e($item->name_en); ?></td>
                                        </tr>
                                        <tr>
                                            <th> اسم  <?php echo e($modelName); ?> (بالعربية):</th>
                                            <td><?php echo e($item->name_ar); ?></td>
                                        </tr>

                                        <tr>
                                            <th>slug (بالإنجليزية):</th>
                                            <td><?php echo e($item->slug_en); ?></td>
                                        </tr>
                                        <tr>
                                            <th>slug (بالعربية):</th>
                                            <td><?php echo e($item->slug_en); ?></td>
                                        </tr>


                                        <tr>
                                            <th>صورة  <?php echo e($modelName); ?>:</th>
                                            <td>
                                                <?php if($item->logo_path): ?>
                                                    <img src="<?php echo e(asset('storage/' . $item->logo_path)); ?>" alt="Brand Logo" class="brand-logo img-thumbnail" style="max-height: 100px;">
                                                <?php else: ?>
                                                    <span>لا يوجد شعار مرفوع</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- SEO Information -->
                                <div class="form-group mt-4">
                                    <h4><i class="fas fa-search"></i> معلومات السيو</h4>
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <th>العنوان التعريفي (بالإنجليزية):</th>
                                            <td><?php echo e($item->meta_title_en); ?></td>
                                        </tr>
                                        <tr>
                                            <th>العنوان التعريفي (بالعربية):</th>
                                            <td><?php echo e($item->meta_title_ar); ?></td>
                                        </tr>
                                        <tr>
                                            <th>الوصف التعريفي (بالإنجليزية):</th>
                                            <td><?php echo e($item->meta_description_en); ?></td>
                                        </tr>
                                        <tr>
                                            <th>الوصف التعريفي (بالعربية):</th>
                                            <td><?php echo e($item->meta_description_ar); ?></td>
                                        </tr>
                                        <tr>
                                            <th>الكلمات المفتاحية (بالإنجليزية):</th>
                                            <td>
                                                <?php $__currentLoopData = json_decode($item->meta_keywords_en); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge badge-primary"><?php echo e($keyword->value); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>الكلمات المفتاحية (بالعربية):</th>
                                            <td>
                                                <?php $__currentLoopData = json_decode($item->meta_keywords_ar); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge badge-primary"><?php echo e($keyword->value); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <a href="<?php echo e(route('admin.'.$modelName.'.index')); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> العودة</a>
                                <div>
                                    <a href="<?php echo e(route('admin.'.$modelName.'.edit', $item->id)); ?>" class="btn btn-warning"><i class="fas fa-edit"></i> تعديل</a>
                                    <form action="<?php echo e(route('admin.'.$modelName.'.destroy', $item->id)); ?>" method="POST" style="display:inline-block;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا  <?php echo e($modelName); ?>؟');"><i class="fas fa-trash-alt"></i> حذف</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\old\brands\show.blade.php ENDPATH**/ ?>