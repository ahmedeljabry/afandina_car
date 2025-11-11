<!-- BEGIN VENDOR JS-->
<script src="<?php echo e(asset('app-assets/vendors/js/vendors.min.js')); ?>"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->
<!-- END PAGE VENDOR JS-->
<!-- BEGIN ROBUST JS-->
<script src="<?php echo e(asset('app-assets/js/core/app-menu.js')); ?>"></script>
<script src="<?php echo e(asset('app-assets/js/core/app.js')); ?>"></script>
<!-- END ROBUST JS-->
<script src="https://cdn.ckeditor.com/4.25.1-lts/full/ckeditor.js"></script>
<script>
    CKEDITOR.editorConfig = function (config) {
        config.language = 'es';
        config.uiColor = '#F7B42C';
        config.height = 200;
        config.toolbarCanCollapse = true;
    };
    var editor = CKEDITOR.replaceAll('teny-editor');
</script>

<script src="<?php echo e(asset('admin/plugins/select2/js/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/dist/js/custom.js')); ?>"></script>
<?php /**PATH D:\car_rental-src-2025-11-05\car_rental\resources\views/includes/admin/footer_scripts.blade.php ENDPATH**/ ?>