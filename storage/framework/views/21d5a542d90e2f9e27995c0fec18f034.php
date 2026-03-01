<?php if (! $__env->hasRenderedOnce('8993c582-688a-4e2b-b306-67624131239d')): $__env->markAsRenderedOnce('8993c582-688a-4e2b-b306-67624131239d'); ?>
    <?php $__env->startPush('styles'); ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
        <style>
            .form-hero {
                background: linear-gradient(135deg, #4c6ef5, #6a82fb);
                border-radius: 22px;
                color: #fff;
                padding: 1.75rem 2rem;
                box-shadow: 0 16px 40px rgba(67, 86, 178, 0.35);
                margin-bottom: 1.5rem;
            }
            .form-hero h2 {
                font-weight: 600;
                margin-bottom: .35rem;
                color: #fff;
            }
            .form-hero p {
                color: rgba(255,255,255,.85);
                margin-bottom: 0;
            }
            .form-hero .hero-pill {
                display: inline-flex;
                align-items: center;
                gap: .4rem;
                padding: .4rem .95rem;
                border-radius: 999px;
                background: rgba(255,255,255,.18);
                font-weight: 500;
                margin: .35rem .25rem 0 0;
                white-space: nowrap;
            }
            .form-card {
                border: none !important;
                border-radius: 22px;
                box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
            }
            .form-card .card-header {
                border-bottom: none;
                background: transparent;
            }
            .form-section-title {
                font-size: .85rem;
                text-transform: uppercase;
                letter-spacing: .08em;
                color: #94a3b8;
                margin-bottom: .6rem;
                font-weight: 600;
            }
            .custom-file-label::after {
                content: "<?php echo e(__('Browse')); ?>";
            }
            .select2-container--bootstrap4 .select2-selection {
                border-radius: 14px;
                min-height: 44px;
                padding: .25rem .75rem;
                border: 1px solid #d0d7e2;
            }
            .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered li {
                border-radius: 12px;
            }
            .form-floating-label {
                font-weight: 600;
                color: #475569;
            }
            .form-control.shadow-sm {
                border-radius: 14px;
            }
        </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script>
            $(function () {
                if (typeof $.fn.select2 === 'undefined') {
                    return;
                }
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH D:\afandina\resources\views/includes/admin/form_theme.blade.php ENDPATH**/ ?>