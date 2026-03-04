<?php if (! $__env->hasRenderedOnce('admin-blog-editor-theme')): $__env->markAsRenderedOnce('admin-blog-editor-theme'); ?>
    <?php $__env->startPush('styles'); ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
        <style>
            .blog-editor-layout {
                display: grid;
                gap: 1.5rem;
                grid-template-columns: minmax(0, 1fr);
            }

            .blog-editor-hero {
                position: relative;
                overflow: hidden;
                border: 1px solid rgba(15, 23, 42, 0.08);
                border-radius: 24px;
                padding: 1.75rem;
                background:
                    radial-gradient(circle at top right, rgba(251, 191, 36, 0.34), transparent 34%),
                    linear-gradient(135deg, #0f172a 0%, #1d4ed8 58%, #0891b2 100%);
                color: #fff;
                box-shadow: 0 22px 48px rgba(15, 23, 42, 0.18);
            }

            .blog-editor-hero::after {
                content: "";
                position: absolute;
                inset: auto -60px -90px auto;
                width: 220px;
                height: 220px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.14);
            }

            .blog-editor-hero h2 {
                position: relative;
                z-index: 1;
                margin-bottom: 0.45rem;
                color: #fff;
                font-size: clamp(1.65rem, 2.8vw, 2.35rem);
            }

            .blog-editor-hero p {
                position: relative;
                z-index: 1;
                margin-bottom: 0;
                max-width: 720px;
                color: rgba(255, 255, 255, 0.86);
            }

            .blog-editor-hero-metrics {
                position: relative;
                z-index: 1;
                display: flex;
                flex-wrap: wrap;
                gap: 0.65rem;
                margin-top: 1.1rem;
            }

            .blog-editor-chip {
                display: inline-flex;
                align-items: center;
                gap: 0.45rem;
                padding: 0.55rem 0.95rem;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.14);
                border: 1px solid rgba(255, 255, 255, 0.12);
                font-size: 0.85rem;
                font-weight: 600;
            }

            .blog-editor-main-card,
            .blog-editor-sidecard {
                border: 1px solid rgba(15, 23, 42, 0.08);
                border-radius: 22px;
                background: #fff;
                box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
            }

            .blog-editor-main-card .card-body,
            .blog-editor-sidecard .card-body {
                padding: 1.5rem;
            }

            .blog-editor-tabbar {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
                padding: 1.5rem 1.5rem 0;
            }

            .blog-editor-tabbar .nav-link {
                border: 0;
                border-radius: 14px;
                padding: 0.85rem 1rem;
                background: #f8fafc;
                color: #475569;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                box-shadow: inset 0 0 0 1px #e2e8f0;
            }

            .blog-editor-tabbar .nav-link:hover,
            .blog-editor-tabbar .nav-link:focus {
                color: #0f172a;
                background: #f1f5f9;
            }

            .blog-editor-tabbar .nav-link.active {
                background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
                color: #fff;
                box-shadow: none;
            }

            .blog-editor-pane {
                padding-top: 0.5rem;
            }

            .blog-editor-panel {
                border: 1px solid #e2e8f0;
                border-radius: 20px;
                background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
                padding: 1.2rem;
                height: 100%;
            }

            .blog-editor-panel-title {
                margin-bottom: 0.85rem;
                color: #0f172a;
                font-size: 1rem;
                font-weight: 700;
            }

            .blog-editor-kicker {
                display: inline-block;
                margin-bottom: 0.65rem;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                font-size: 0.72rem;
                font-weight: 700;
            }

            .blog-editor-preview-box {
                position: relative;
                width: 100%;
                min-height: 300px;
                border-radius: 20px;
                border: 1px dashed #cbd5e1;
                background:
                    linear-gradient(135deg, rgba(148, 163, 184, 0.12), rgba(148, 163, 184, 0.03)),
                    #f8fafc;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
            }

            .blog-editor-preview-box img {
                width: 100%;
                height: 100%;
                min-height: 300px;
                object-fit: cover;
            }

            .blog-editor-hint {
                color: #64748b;
                font-size: 0.88rem;
                line-height: 1.55;
            }

            .blog-editor-label {
                margin-bottom: 0.45rem;
                color: #334155;
                font-weight: 700;
                font-size: 0.92rem;
            }

            .blog-editor .form-control,
            .blog-editor .form-select {
                border-radius: 14px;
                border-color: #dbe2f0;
                padding: 0.78rem 0.95rem;
                min-height: 48px;
                box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
            }

            .blog-editor textarea.form-control {
                min-height: 120px;
            }

            .blog-editor .form-control:focus,
            .blog-editor .form-select:focus {
                border-color: rgba(29, 78, 216, 0.48);
                box-shadow: 0 0 0 0.25rem rgba(29, 78, 216, 0.12);
            }

            .blog-editor .form-check-input {
                width: 2.75rem;
                height: 1.45rem;
                margin-top: 0;
                cursor: pointer;
            }

            .blog-editor .form-check-input:checked {
                background-color: #1d4ed8;
                border-color: #1d4ed8;
            }

            .blog-editor-switch {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1rem;
                padding: 1rem 1.1rem;
                border: 1px solid #dbe2f0;
                border-radius: 18px;
                background: #fff;
            }

            .blog-editor-switch strong {
                display: block;
                margin-bottom: 0.2rem;
                color: #0f172a;
                font-size: 0.95rem;
            }

            .blog-editor-switch span {
                display: block;
                color: #64748b;
                font-size: 0.84rem;
                line-height: 1.5;
            }

            .blog-editor-langbar {
                display: flex;
                flex-wrap: wrap;
                gap: 0.6rem;
                margin-bottom: 1rem;
            }

            .blog-editor-langbar .nav-link {
                border: 0;
                border-radius: 999px;
                padding: 0.55rem 0.95rem;
                background: #eef2ff;
                color: #4338ca;
                font-weight: 700;
                font-size: 0.84rem;
            }

            .blog-editor-langbar .nav-link.active {
                background: #4338ca;
                color: #fff;
            }

            .blog-editor-langpane {
                border: 1px solid #e2e8f0;
                border-radius: 20px;
                background: #fff;
                padding: 1.2rem;
            }

            .blog-editor-seo-box {
                border: 1px solid #e2e8f0;
                border-radius: 18px;
                background: #f8fafc;
                padding: 1rem;
                height: 100%;
            }

            .blog-editor-seo-box h6 {
                margin-bottom: 0.55rem;
                color: #0f172a;
                font-size: 0.92rem;
                font-weight: 700;
            }

            .blog-editor-question {
                border: 1px solid #dbe2f0;
                border-radius: 18px;
                background: #fff;
                padding: 1rem;
            }

            .blog-editor-question + .blog-editor-question {
                margin-top: 0.85rem;
            }

            .blog-editor-question-actions {
                display: flex;
                justify-content: flex-end;
                margin-top: 0.75rem;
            }

            .blog-editor-ghost-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.45rem;
                border-radius: 14px;
                padding: 0.75rem 1rem;
                font-weight: 700;
            }

            .blog-editor-submitbar {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 0.9rem;
                margin-top: 1.4rem;
                padding-top: 1.2rem;
                border-top: 1px solid #e2e8f0;
            }

            .blog-editor-submitbar p {
                margin-bottom: 0;
                color: #64748b;
                font-size: 0.9rem;
            }

            .blog-editor-sidecard {
                position: sticky;
                top: 96px;
            }

            .blog-editor-checklist {
                display: grid;
                gap: 0.85rem;
            }

            .blog-editor-checklist-item {
                display: flex;
                align-items: flex-start;
                gap: 0.7rem;
                padding: 0.85rem 0.95rem;
                border-radius: 16px;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
            }

            .blog-editor-checklist-item i {
                margin-top: 0.1rem;
                color: #1d4ed8;
            }

            .blog-editor-checklist-item strong {
                display: block;
                margin-bottom: 0.15rem;
                color: #0f172a;
                font-size: 0.9rem;
            }

            .blog-editor-checklist-item span {
                color: #64748b;
                font-size: 0.82rem;
                line-height: 1.5;
            }

            .blog-editor-sidecard .blog-editor-preview-box {
                min-height: 180px;
            }

            .blog-editor-sidecard .blog-editor-preview-box img {
                min-height: 180px;
            }

            .blog-editor .select2-container {
                width: 100% !important;
            }

            .blog-editor .select2-container--bootstrap4 .select2-selection {
                border-radius: 14px;
                border-color: #dbe2f0;
                min-height: 48px;
                padding: 0.2rem 0.35rem;
                box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
            }

            .blog-editor .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
                margin-top: 0.35rem;
                border-radius: 999px;
                border: 0;
                background: #e0e7ff;
                color: #3730a3;
                padding: 0.2rem 0.65rem;
            }

            .blog-editor .select2-container--bootstrap4 .select2-selection--single {
                height: auto;
            }

            .blog-editor .select2-container--bootstrap4 .select2-selection__rendered {
                color: #334155;
                line-height: 1.9;
            }

            .blog-editor .tagify {
                width: 100%;
                min-height: 48px;
                border-radius: 14px;
                border-color: #dbe2f0;
                padding: 0.35rem 0.5rem;
                box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
            }

            .blog-editor-empty {
                border-radius: 18px;
                padding: 1rem;
                background: #f8fafc;
                border: 1px dashed #cbd5e1;
                color: #64748b;
                font-size: 0.9rem;
            }

            @media (min-width: 1200px) {
                .blog-editor-layout {
                    grid-template-columns: minmax(0, 1fr) 320px;
                    align-items: start;
                }
            }

            @media (max-width: 991.98px) {
                .blog-editor-tabbar {
                    padding: 1.15rem 1.15rem 0;
                }

                .blog-editor-main-card .card-body,
                .blog-editor-sidecard .card-body {
                    padding: 1.15rem;
                }

                .blog-editor-sidecard {
                    position: static;
                }
            }
        </style>
    <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH D:\afandina\resources\views\includes\admin\blog_editor_theme.blade.php ENDPATH**/ ?>