<?php $__env->startSection('title', __('Home Page')); ?>

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Afandina')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Basic Settings')); ?></li>
    <li class="breadcrumb-item active"><?php echo e(__('About Us')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><?php echo e(__('About Us')); ?></h3>
        </div>

        <form>
            <div class="card-body">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo e(__('Email address')); ?></label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="<?php echo e(__('Enter email')); ?>">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1"><?php echo e(__('Description')); ?></label>
                    <textarea style="height: 100px" id="summernote"></textarea>
                </div>
                <div class="form-group">
                    <label for="exampleInputFile"><?php echo e(__('File input')); ?></label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile"><?php echo e(__('Choose file')); ?></label>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text"><?php echo e(__('Upload')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1"><?php echo e(__('Check me out')); ?></label>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><?php echo e(__('Submit')); ?></button>
            </div>
        </form>
    </div>

    <div class="card mt-2">
        <div class="card-body">
            <textarea id="codeMirrorDemo"></textarea>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(function () {
            $('#summernote').summernote();
            CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
                mode: "htmlmixed",
                theme: "monokai"
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\main_setting\about_us.blade.php ENDPATH**/ ?>