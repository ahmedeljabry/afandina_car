<?php $__env->startSection('title', ' عرض'. $modelName); ?>

<?php $__env->startSection('content'); ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><?php echo e($modelName); ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                            <li class="breadcrumb-item active"><?php echo e($modelName); ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?php echo e($modelName); ?></h3>
                                <a href="<?php echo e(route('admin.'.$modelName.'.create')); ?>" class="btn btn-primary float-right">
                                    <i class="fas fa-plus"></i> إضافة <?php echo e($modelName); ?>

                                </a>
                            </div>
                            <div class="card-body">
                                <?php if(session('success')): ?>
                                    <div class="alert alert-success">
                                        <?php echo e(session('success')); ?>

                                    </div>
                                <?php endif; ?>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>الرقم</th>
                                        <th> اسم ال<?php echo e($modelName); ?> (بالإنجليزية)</th>
                                        <th> اسم ال<?php echo e($modelName); ?> (بالعربية)</th>
                                        <th>الشعار</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item->id); ?></td>
                                            <td><?php echo e($item->name_en); ?></td>
                                            <td><?php echo e($item->name_ar); ?></td>
                                            <td>
                                                <?php if($item->category_image): ?>
                                                    <img src="<?php echo e(asset('storage/' . $item->category_image)); ?>"
                                                         alt="<?php echo e($item->name_en); ?>" class="brand-logo" style="width: 100px;">
                                                <?php else: ?>
                                                    لا يوجد صورة
                                                <?php endif; ?>
                                            </td>
                                            <td>

                                                <a href="<?php echo e(route('admin.'.$modelName.'.show', $item->id)); ?>"
                                                   class="btn btn-success btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('admin.'.$modelName.'.edit', $item->id)); ?>"
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('admin.'.$modelName.'.destroy', $item->id)); ?>"
                                                      method="POST"
                                                      class="delete-form"
                                                      style="display:inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                <?php echo e($items->links()); ?> <!-- Pagination links -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Handle delete confirmation
        $(document).on('click', '.delete-btn', function (e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'هل أنت متأكد من الحذف؟',
                text: "لن تتمكن من التراجع عن هذا!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، حذف!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit the form
                }
            })
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\old\categories\index.blade.php ENDPATH**/ ?>