<!-- jQuery -->
<script src="<?php echo e(asset('admin/assets/js/jquery-3.7.1.min.js')); ?>"></script>

<!-- Feather Icon JS -->
<script src="<?php echo e(asset('admin/assets/js/feather.min.js')); ?>"></script>

<!-- Bootstrap Core JS -->
<script src="<?php echo e(asset('admin/assets/js/bootstrap.bundle.min.js')); ?>"></script>

<!-- Slimscroll JS -->
<script src="<?php echo e(asset('admin/assets/js/jquery.slimscroll.min.js')); ?>"></script>

<!-- Daterangepikcer JS -->
<script src="<?php echo e(asset('admin/assets/js/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/plugins/daterangepicker/daterangepicker.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/bootstrap-datetimepicker.min.js')); ?>"></script>

<!-- Bootstrap Tagsinput JS -->
<script src="<?php echo e(asset('admin/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js')); ?>"></script>

<!-- ApexChart JS -->
<script src="<?php echo e(asset('admin/assets/plugins/apexchart/apexcharts.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/plugins/apexchart/chart-data.js')); ?>"></script>

<!-- Peity Chart -->
<script src="<?php echo e(asset('admin/assets/plugins/peity/jquery.peity.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/plugins/peity/chart-data.js')); ?>"></script>

<!-- Custom JS -->
<script src="<?php echo e(asset('admin/assets/js/script.js')); ?>"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery) {
            var getCsrfToken = function () {
                return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            };

            $.ajaxSetup({
                beforeSend: function (xhr) {
                    var token = getCsrfToken();
                    if (token) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                }
            });

            // Keep admin session alive while user is filling forms / generating AI content.
            setInterval(function () {
                $.ajax({
                    url: "<?php echo e(route('admin.keep-alive')); ?>",
                    method: 'GET',
                    dataType: 'json',
                    global: false
                }).done(function (response) {
                    if (response && response.csrf_token) {
                        $('meta[name="csrf-token"]').attr('content', response.csrf_token);
                    }
                });
            }, 5 * 60 * 1000);

            var retrying419 = false;
            $(document).ajaxError(function (_event, jqXHR, ajaxSettings) {
                if (jqXHR.status !== 419 || retrying419) {
                    return;
                }

                retrying419 = true;

                $.ajax({
                    url: "<?php echo e(route('admin.keep-alive')); ?>",
                    method: 'GET',
                    dataType: 'json',
                    global: false
                }).done(function (response) {
                    if (response && response.csrf_token) {
                        $('meta[name="csrf-token"]').attr('content', response.csrf_token);
                    }

                    if (ajaxSettings && !ajaxSettings.__retriedAfter419) {
                        ajaxSettings.__retriedAfter419 = true;
                        $.ajax(ajaxSettings);
                    } else {
                        window.location.reload();
                    }
                }).fail(function () {
                    window.location.href = "<?php echo e(route('admin.login')); ?>";
                }).always(function () {
                    retrying419 = false;
                });
            });
        }

        <?php $__currentLoopData = ['success' => 'success', 'error' => 'error', 'warning' => 'warning', 'info' => 'info']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(session($type)): ?>
                Swal.fire({
                    icon: '<?php echo e($icon); ?>',
                    title: "<?php echo e(ucfirst($icon)); ?>",
                    text: <?php echo json_encode(session($type), 15, 512) ?>,
                    timer: 3500,
                    showConfirmButton: false
                });
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    });
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
<?php /**PATH D:\afandina\resources\views\includes\admin\footer_scripts.blade.php ENDPATH**/ ?>