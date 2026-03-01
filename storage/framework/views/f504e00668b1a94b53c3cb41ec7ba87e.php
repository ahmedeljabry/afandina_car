<?php if (! $__env->hasRenderedOnce('admin-datatable-theme')): $__env->markAsRenderedOnce('admin-datatable-theme'); ?>
    <?php $__env->startPush('styles'); ?>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
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
            .management-card .table-responsive {
                border: 1px solid #e5e7eb;
                border-radius: 18px;
                background: #fff;
            }
            .management-table {
                margin-bottom: 0;
            }
            .management-table thead th {
                text-transform: uppercase;
                font-size: .8rem;
                letter-spacing: .05em;
                border-top: none;
                border-bottom-width: 1px;
            }
            .management-table tbody td {
                padding-top: 1rem;
                padding-bottom: 1rem;
                border-color: #eef2f7;
            }
            .management-table tbody tr:hover {
                background: rgba(76, 110, 245, .04);
            }
            .management-table tbody td .btn-group {
                display: inline-flex;
                align-items: center;
                gap: .5rem;
            }
            .management-table tbody td .btn-group > .btn {
                min-width: 2.5rem;
                min-height: 2.5rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 12px !important;
                padding: .5rem .75rem;
                line-height: 1;
            }
            .management-table tbody td .btn-outline-info {
                background: rgba(14, 165, 233, .08);
                border-color: rgba(14, 165, 233, .35);
                color: #0284c7;
            }
            .management-table tbody td .btn-outline-info:hover,
            .management-table tbody td .btn-outline-info:focus {
                background: #0284c7;
                border-color: #0284c7;
                color: #fff;
            }
            .management-table tbody td .btn-outline-danger {
                background: rgba(239, 68, 68, .08);
                border-color: rgba(239, 68, 68, .35);
                color: #dc2626;
            }
            .management-table tbody td .btn-outline-danger:hover,
            .management-table tbody td .btn-outline-danger:focus {
                background: #dc2626;
                border-color: #dc2626;
                color: #fff;
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
            .dataTables_wrapper .dataTables_length label,
            .dataTables_wrapper .dataTables_filter label {
                font-weight: 600;
                color: #475569;
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
        <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH D:\afandina\resources\views\includes\admin\datatable_theme.blade.php ENDPATH**/ ?>