<?php $__env->startSection('title', 'Add ' . $modelName); ?>

<?php $__env->startSection('page-title'); ?>
    Add <?php echo e($modelName); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


    <div class="card card-primary card-outline card-tabs shadow-lg">
        <div class="card-header p-0 pt-1 border-bottom-0 bg-light">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active text-dark" id="custom-tabs-general-tab" data-toggle="pill"
                        href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-general" aria-selected="true">
                        <i class="fas fa-info-circle"></i> General Data
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <form action="<?php echo e(route('admin.' . $modelName . '.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="tab-content" id="custom-tabs-three-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel"
                        aria-labelledby="custom-tabs-general-tab">

                        <div class="form-group">
                            <label for="instagram_url" class="font-weight-bold">Instagram Video URL</label>
                            <input type="url" name="instagram_url" id="instagram_url" class="form-control"
                                value="<?php echo e(old('instagram_url')); ?>" placeholder="Enter Instagram video URL" required>
                        </div>

                        <!-- Preview Section -->
                        <div id="instagramPreview" class="mt-3" style="display: none;">
                            <label class="font-weight-bold">Instagram Video Preview</label>
                            <video id="instagramVideo" controls autoplay muted loop
                                style="width: 100%; height: auto; border: none;"></video>
                        </div>

                        <div class="form-group">
                            <label for="is_active" class="font-weight-bold">Active</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" <?php echo e(old('is_active') ? 'checked' : ''); ?>>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-lg mt-3">
                    <i class="fas fa-save"></i> Save
                </button>
            </form>
        </div>
</div><?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.getElementById('instagram_url').addEventListener('input', function () {
            const url = this.value;
            const videoElement = document.getElementById('instagramVideo');
            const previewSection = document.getElementById('instagramPreview');

            if (url) {
                // Display the preview section
                previewSection.style.display = 'block';

                // Set the video source
                videoElement.src = url;
            } else {
                // Hide the preview section if no URL
                previewSection.style.display = 'none';
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\instagrams\create.blade.php ENDPATH**/ ?>