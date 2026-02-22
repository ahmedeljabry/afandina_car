<?php if (! $__env->hasRenderedOnce('admin-datatable-theme')): $__env->markAsRenderedOnce('admin-datatable-theme'); ?>
    <?php $__env->startPush('styles'); ?>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
        <style>
            .management-hero {
                background: linear-gradient(135deg, #4c6ef5, #6a82fb);
                border-radius: 22px;
                color: #fff;
                padding: 1.75rem 2rem;
                box-shadow: 0 16px 40px rgba(67, 86, 178, 0.35);
                margin-bottom: 1.5rem;
            }
            .management-hero h2 {
                font-weight: 600;
                margin-bottom: .35rem;
                color: #fff;
            }
            .management-hero p {
                color: rgba(255,255,255,.85);
                margin-bottom: 0;
            }
            .management-hero .stat-pill {
                display: inline-flex;
                align-items: center;
                gap: .45rem;
                padding: .45rem 1rem;
                border-radius: 999px;
                background: rgba(255,255,255,.18);
                font-weight: 500;
                margin-right: .75rem;
            }
            .management-card {
                border: none;
                border-radius: 20px;
                box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
            }
            .management-card .card-header {
                border-bottom: 0;
                padding: 1.5rem 1.75rem 0;
            }
            .management-table thead th {
                text-transform: uppercase;
                font-size: .8rem;
                letter-spacing: .05em;
                border-top: none;
                border-bottom-width: 1px;
            }
            .status-pill {
                display: inline-flex;
                align-items: center;
                gap: .35rem;
                border-radius: 999px;
                padding: .25rem .85rem;
                font-weight: 600;
                font-size: .85rem;
            }
            .status-pill.active {
                background: rgba(16, 185, 129, .15);
                color: #047857;
            }
            .status-pill.inactive {
                background: rgba(248, 113, 113, .18);
                color: #b91c1c;
            }
            .dataTables_wrapper .dataTables_filter input {
                border-radius: 999px !important;
                border: 1px solid #dbe2f0;
                padding: .35rem 1rem;
                box-shadow: inset 0 1px 2px rgba(15,23,42,.06);
                background-color: #f8fafc;
            }
            .dataTables_wrapper .dataTables_length select {
                border-radius: 12px;
            }
            .dataTables_wrapper .dataTables_paginate .page-link {
                border-radius: 10px;
                margin: 0 .15rem;
            }
            .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
                background-color: #4c6ef5;
                border-color: #4c6ef5;
            }
            .management-toolbar .btn {
                border-radius: 30px;
            }
            .media-preview {
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 12px 25px rgba(15, 23, 42, 0.15);
            }
            .media-thumb {
                border-radius: 16px;
                width: 110px;
                height: 90px;
                object-fit: cover;
                box-shadow: 0 12px 25px rgba(15, 23, 42, 0.15);
            }
            .toggle-stack {
                display: flex;
                flex-direction: column;
                gap: .35rem;
            }
            .toggle-stack .toggle-label {
                font-size: .75rem;
                text-transform: uppercase;
                letter-spacing: .05em;
                color: #6b7280;
                font-weight: 600;
                margin-bottom: .05rem;
            }
        </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH D:\afandina\resources\views\includes\admin\datatable_theme.blade.php ENDPATH**/ ?>