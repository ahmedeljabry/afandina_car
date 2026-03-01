@once
    @push('styles')
        <style>
            .car-workbench {
                position: relative;
                padding-bottom: 1.5rem;
            }

            .car-workbench__hero {
                position: relative;
                display: grid;
                grid-template-columns: minmax(0, 1.7fr) minmax(280px, 1fr);
                gap: 1.5rem;
                padding: 1.75rem;
                margin-bottom: 1.5rem;
                border-radius: 30px;
                overflow: hidden;
                background:
                    radial-gradient(circle at top right, rgba(125, 211, 252, 0.3), transparent 34%),
                    radial-gradient(circle at bottom left, rgba(96, 165, 250, 0.24), transparent 36%),
                    linear-gradient(135deg, #0f172a 0%, #1d4ed8 52%, #0ea5e9 100%);
                color: #ffffff;
                box-shadow: 0 28px 65px rgba(15, 23, 42, 0.24);
            }

            .car-workbench__hero::before,
            .car-workbench__hero::after {
                content: "";
                position: absolute;
                border-radius: 50%;
                pointer-events: none;
            }

            .car-workbench__hero::before {
                width: 180px;
                height: 180px;
                top: -60px;
                right: 24%;
                background: rgba(255, 255, 255, 0.08);
            }

            .car-workbench__hero::after {
                width: 140px;
                height: 140px;
                bottom: -54px;
                left: 16%;
                background: rgba(255, 255, 255, 0.08);
            }

            .car-workbench__hero-copy,
            .car-workbench__hero-cards {
                position: relative;
                z-index: 1;
            }

            .car-workbench__eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.45rem 0.85rem;
                margin-bottom: 1rem;
                border-radius: 999px;
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                background: rgba(255, 255, 255, 0.14);
                border: 1px solid rgba(255, 255, 255, 0.18);
            }

            .car-workbench__eyebrow::before {
                content: "";
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: #f8fafc;
                box-shadow: 0 0 0 6px rgba(248, 250, 252, 0.16);
            }

            .car-workbench__title {
                margin: 0;
                font-size: clamp(1.75rem, 2.4vw, 2.65rem);
                line-height: 1.08;
                font-weight: 800;
                color: #ffffff;
            }

            .car-workbench__subtitle {
                max-width: 760px;
                margin: 1rem 0 0;
                font-size: 0.98rem;
                line-height: 1.7;
                color: rgba(255, 255, 255, 0.84);
            }

            .car-workbench__hero-tags {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
                margin-top: 1.1rem;
            }

            .car-workbench__tag {
                display: inline-flex;
                align-items: center;
                padding: 0.55rem 0.9rem;
                border-radius: 999px;
                font-size: 0.82rem;
                font-weight: 600;
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.18);
            }

            .car-workbench__hero-cards {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.95rem;
                align-content: start;
            }

            .car-workbench__metric {
                min-height: 120px;
                padding: 1rem;
                border-radius: 22px;
                border: 1px solid rgba(255, 255, 255, 0.18);
                background: rgba(255, 255, 255, 0.11);
                backdrop-filter: blur(12px);
            }

            .car-workbench__metric-label {
                display: block;
                margin-bottom: 0.55rem;
                font-size: 0.76rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: rgba(255, 255, 255, 0.72);
            }

            .car-workbench__metric-value {
                display: block;
                font-size: 1.25rem;
                line-height: 1.25;
                font-weight: 700;
                color: #ffffff;
                word-break: break-word;
            }

            .car-workbench__metric-note {
                display: block;
                margin-top: 0.45rem;
                font-size: 0.82rem;
                color: rgba(255, 255, 255, 0.7);
            }

            .car-workbench__layout {
                display: grid;
                grid-template-columns: minmax(260px, 320px) minmax(0, 1fr);
                gap: 1.5rem;
                align-items: start;
            }

            .car-workbench__aside {
                position: sticky;
                top: 94px;
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .car-workbench__panel {
                padding: 1.2rem;
                border-radius: 24px;
                border: 1px solid #dbe8f5;
                background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
                box-shadow: 0 18px 38px rgba(15, 23, 42, 0.08);
            }

            .car-workbench__panel-head {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 0.95rem;
            }

            .car-workbench__panel-icon {
                width: 42px;
                height: 42px;
                border-radius: 14px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #dbeafe, #bfdbfe);
                color: #1d4ed8;
                font-size: 1.1rem;
            }

            .car-workbench__panel-title {
                margin: 0;
                font-size: 1rem;
                font-weight: 700;
                color: #0f172a;
            }

            .car-workbench__panel-copy {
                margin: 0;
                font-size: 0.9rem;
                line-height: 1.6;
                color: #64748b;
            }

            .car-workbench__steps,
            .car-workbench__facts {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .car-workbench__steps li,
            .car-workbench__facts li {
                padding: 0.85rem 0.95rem;
                border-radius: 18px;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
            }

            .car-workbench__steps li + li,
            .car-workbench__facts li + li {
                margin-top: 0.75rem;
            }

            .car-workbench__step-label,
            .car-workbench__fact-label {
                display: block;
                margin-bottom: 0.35rem;
                font-size: 0.76rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: #94a3b8;
            }

            .car-workbench__step-text,
            .car-workbench__fact-value {
                display: block;
                font-size: 0.92rem;
                line-height: 1.55;
                font-weight: 600;
                color: #0f172a;
            }

            .car-workbench__main {
                position: relative;
            }

            .car-workbench .loader-overlay {
                position: absolute;
                inset: 0;
                display: none;
                align-items: center;
                justify-content: center;
                flex-direction: column;
                border-radius: 30px;
                background: rgba(248, 250, 252, 0.82);
                backdrop-filter: blur(6px);
                z-index: 20;
            }

            .car-workbench .loader {
                width: 62px;
                height: 62px;
                border: 5px solid #dbeafe;
                border-top: 5px solid #2563eb;
                border-radius: 50%;
                animation: carWorkbenchSpin 0.9s linear infinite;
            }

            @keyframes carWorkbenchSpin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }

            .car-workbench__main > .card.form-card {
                margin: 0;
                overflow: hidden;
                border: 1px solid #dbe8f5;
                border-radius: 30px;
                background: linear-gradient(180deg, #f8fbff 0%, #ffffff 14%);
                box-shadow: 0 28px 60px rgba(15, 23, 42, 0.1);
            }

            .car-workbench__main > .card.form-card > .card-header {
                padding: 1rem 1rem 0;
                background: transparent !important;
                border-bottom: none;
            }

            .car-workbench__main > .card.form-card > .card-body {
                padding: 1rem;
            }

            .car-workbench .nav-tabs.nav-tabs-modern {
                display: flex;
                flex-wrap: nowrap;
                gap: 0.75rem;
                padding: 0.6rem;
                margin: 0;
                overflow-x: auto;
                border: none;
                border-radius: 24px;
                background: linear-gradient(135deg, #eff6ff, #f8fafc);
            }

            .car-workbench .nav-tabs.nav-tabs-modern .nav-item {
                margin: 0;
            }

            .car-workbench .nav-tabs.nav-tabs-modern .nav-link {
                border: none;
                border-radius: 18px;
                padding: 0.85rem 1.2rem;
                margin: 0;
                font-weight: 700;
                color: #475569 !important;
                background: transparent;
                white-space: nowrap;
                display: inline-flex;
                align-items: center;
                gap: 0.55rem;
            }

            .car-workbench .nav-tabs.nav-tabs-modern .nav-link.active {
                color: #ffffff !important;
                background: linear-gradient(135deg, #0f172a, #1d4ed8);
                box-shadow: 0 16px 28px rgba(37, 99, 235, 0.28);
            }

            .car-workbench .tab-content.tab-modern {
                margin-top: 1rem;
            }

            .car-workbench #custom-tabs-general > .card.mb-4 {
                border: none;
                border-radius: 24px;
                overflow: hidden;
                background: #ffffff;
                box-shadow: 0 18px 38px rgba(15, 23, 42, 0.07);
            }

            .car-workbench #custom-tabs-general > .card.mb-4 + .card.mb-4 {
                margin-top: 1rem;
            }

            .car-workbench #custom-tabs-general > .card.mb-4 > .card-header.bg-light {
                display: flex;
                align-items: center;
                padding: 1rem 1.25rem;
                background: linear-gradient(135deg, #f8fafc, #eef4ff) !important;
                border-bottom: 1px solid #e2e8f0;
            }

            .car-workbench #custom-tabs-general > .card.mb-4 > .card-header.bg-light .card-title {
                margin: 0;
                font-size: 1rem;
                font-weight: 800;
                color: #0f172a;
            }

            .car-workbench #custom-tabs-general > .card.mb-4 > .card-header.bg-light .card-title::before {
                content: "";
                display: inline-block;
                width: 10px;
                height: 10px;
                margin-right: 0.55rem;
                border-radius: 50%;
                background: #2563eb;
                box-shadow: 0 0 0 6px rgba(37, 99, 235, 0.12);
                vertical-align: middle;
            }

            .car-workbench #custom-tabs-general > .card.mb-4 > .card-body {
                padding: 1.25rem;
            }

            .car-workbench #custom-tabs-general .row + .row {
                margin-top: 0.2rem;
            }

            .car-workbench .form-group {
                margin-bottom: 1.15rem;
            }

            .car-workbench label.font-weight-bold,
            .car-workbench .form-label {
                display: block;
                margin-bottom: 0.55rem;
                font-size: 0.78rem;
                font-weight: 800 !important;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: #475569;
            }

            .car-workbench .form-control,
            .car-workbench .select2-container--bootstrap4 .select2-selection,
            .car-workbench .custom-file-label {
                min-height: 50px;
                border-radius: 16px !important;
                border: 1px solid #dbe5f1 !important;
                background: #f8fafc !important;
                box-shadow: none !important;
                transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
            }

            .car-workbench textarea.form-control {
                min-height: 130px;
            }

            .car-workbench .form-control,
            .car-workbench .custom-file-label {
                padding: 0.85rem 1rem;
                color: #0f172a;
            }

            .car-workbench .form-control:focus,
            .car-workbench .select2-container--bootstrap4.select2-container--focus .select2-selection,
            .car-workbench .select2-container--bootstrap4.select2-container--open .select2-selection {
                border-color: #60a5fa !important;
                background: #ffffff !important;
                box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12) !important;
            }

            .car-workbench .select2-container--bootstrap4 .select2-selection--single,
            .car-workbench .select2-container--bootstrap4 .select2-selection--multiple {
                display: flex;
                align-items: center;
                min-height: 50px;
                padding: 0.35rem 0.85rem;
            }

            .car-workbench .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered {
                display: flex;
                flex-wrap: wrap;
                gap: 0.35rem;
                padding: 0;
            }

            .car-workbench .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
                border: none;
                border-radius: 999px;
                padding: 0.2rem 0.65rem;
                margin: 0;
                background: #dbeafe;
                color: #1d4ed8;
                font-weight: 600;
            }

            .car-workbench .custom-file {
                width: 100%;
            }

            .car-workbench .custom-file-label {
                display: flex;
                align-items: center;
                overflow: hidden;
            }

            .car-workbench .custom-file-label::after {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 108px;
                border: none;
                color: #ffffff;
                background: linear-gradient(135deg, #0f172a, #1d4ed8);
            }

            .car-workbench .image-rectangle-preview {
                width: 100% !important;
                max-width: 260px;
                border: 1px solid #dbe5f1 !important;
                border-radius: 20px;
                box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08) !important;
            }

            .car-workbench .custom-control.custom-switch {
                min-height: auto;
                padding-left: 0;
            }

            .car-workbench .custom-control.custom-switch .custom-control-label {
                position: relative;
                width: 100%;
                min-height: 74px;
                margin: 0;
                padding: 1.2rem 1rem 1.2rem 4.1rem;
                border-radius: 20px;
                border: 1px solid #dbe5f1;
                background: #ffffff;
                font-size: 0.95rem;
                font-weight: 600;
                color: #0f172a;
                cursor: pointer;
                transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
            }

            .car-workbench .custom-control.custom-switch .custom-control-label::before {
                top: 50%;
                left: 1rem;
                width: 2.2rem;
                height: 1.2rem;
                border: none;
                border-radius: 999px;
                background: #cbd5e1;
                box-shadow: none;
                transform: translateY(-50%);
            }

            .car-workbench .custom-control.custom-switch .custom-control-label::after {
                top: 50%;
                left: calc(1rem + 2px);
                width: 0.95rem;
                height: 0.95rem;
                border-radius: 50%;
                background: #ffffff;
                transform: translateY(-50%);
            }

            .car-workbench .custom-control.custom-switch .custom-control-input:checked ~ .custom-control-label {
                border-color: #bfdbfe;
                background: #eff6ff;
                box-shadow: 0 12px 22px rgba(37, 99, 235, 0.1);
                color: #1d4ed8;
            }

            .car-workbench .custom-control.custom-switch .custom-control-input:checked ~ .custom-control-label::before {
                background: #2563eb;
            }

            .car-workbench .custom-control.custom-switch .custom-control-input:checked ~ .custom-control-label::after {
                transform: translate(16px, -50%);
            }

            .car-workbench .invalid-feedback {
                display: block;
                margin-top: 0.35rem;
                font-size: 0.84rem;
            }

            .car-workbench #custom-tabs-translated > .card,
            .car-workbench .studio-pane-surface {
                border: none;
                border-radius: 24px;
                background: linear-gradient(135deg, #eff6ff, #ffffff);
                box-shadow: 0 18px 36px rgba(15, 23, 42, 0.07);
            }

            .car-workbench .badge.badge-primary {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 48px;
                height: 48px;
                padding: 0;
                border-radius: 16px;
                color: #ffffff;
                background: linear-gradient(135deg, #0f172a, #1d4ed8);
            }

            .car-workbench #pills-tab,
            .car-workbench #pills-seo-tab {
                display: flex;
                flex-wrap: wrap;
                gap: 0.65rem;
                margin-bottom: 1rem !important;
            }

            .car-workbench #pills-tab .nav-item,
            .car-workbench #pills-seo-tab .nav-item {
                margin: 0;
            }

            .car-workbench #pills-tab .nav-link,
            .car-workbench #pills-seo-tab .nav-link {
                border: none;
                border-radius: 14px;
                padding: 0.75rem 1rem;
                font-weight: 700;
                color: #475569 !important;
                background: #e2e8f0 !important;
            }

            .car-workbench #pills-tab .nav-link.active,
            .car-workbench #pills-seo-tab .nav-link.active {
                color: #1d4ed8 !important;
                background: #ffffff !important;
                box-shadow: 0 14px 24px rgba(15, 23, 42, 0.08);
            }

            .car-workbench #pills-tabContent,
            .car-workbench #pills-seo-tabContent {
                padding: 1.25rem !important;
                border: none !important;
                border-radius: 24px !important;
                background: #ffffff !important;
                box-shadow: 0 20px 42px rgba(15, 23, 42, 0.07);
            }

            .car-workbench .studio-inline-card {
                margin: 0 0 1rem;
                margin-left: 0;
                margin-right: 0;
                padding: 1rem;
                border: none;
                border-radius: 20px;
                background: #f8fafc;
                box-shadow: inset 0 0 0 1px #e2e8f0;
            }

            .car-workbench .studio-inline-card > [class*="col-"] {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .car-workbench .seo-question-group {
                border: none !important;
                border-radius: 20px !important;
                background: #f8fafc;
                box-shadow: inset 0 0 0 1px #e2e8f0;
            }

            .car-workbench .preview-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 1rem;
            }

            .car-workbench .preview-item {
                position: relative;
                padding: 0.55rem;
                border: none;
                border-radius: 22px;
                overflow: hidden;
                background: #0f172a;
                box-shadow: 0 16px 30px rgba(15, 23, 42, 0.18);
            }

            .car-workbench .preview-item img,
            .car-workbench .preview-item video,
            .car-workbench .preview-item iframe {
                width: 100%;
                height: 180px;
                object-fit: cover;
                border-radius: 16px;
            }

            .car-workbench .remove-preview {
                position: absolute;
                top: 12px;
                right: 12px;
                width: 34px;
                height: 34px;
                border: none;
                border-radius: 50%;
                color: #ffffff;
                background: rgba(15, 23, 42, 0.9);
                box-shadow: 0 10px 18px rgba(15, 23, 42, 0.24);
            }

            .car-workbench .remove-preview:hover {
                background: #dc2626;
            }

            .car-workbench .btn-success,
            .car-workbench .generate-ai-all {
                border: none;
                border-radius: 18px;
                font-weight: 700;
                background: linear-gradient(135deg, #0f172a, #1d4ed8);
                box-shadow: 0 18px 30px rgba(15, 23, 42, 0.18);
            }

            .car-workbench .btn-success:hover,
            .car-workbench .generate-ai-all:hover {
                transform: translateY(-1px);
                box-shadow: 0 20px 34px rgba(37, 99, 235, 0.24);
            }

            .car-workbench .btn-info {
                border: none;
                border-radius: 14px;
                font-weight: 700;
                background: linear-gradient(135deg, #0891b2, #0ea5e9);
                box-shadow: 0 14px 24px rgba(14, 165, 233, 0.22);
            }

            .car-workbench .btn-danger {
                border: none;
                border-radius: 12px;
            }

            .car-workbench .btn-lg {
                padding: 1rem 1.5rem;
            }

            .car-workbench .btn-success.btn-lg {
                min-width: 180px;
                margin-top: 0.35rem !important;
            }

            .car-workbench .ti,
            .car-workbench .fas {
                vertical-align: middle;
            }

            .car-workbench .text-muted {
                color: #64748b !important;
            }

            @media (max-width: 1399.98px) {
                .car-workbench__hero {
                    grid-template-columns: 1fr;
                }

                .car-workbench__hero-cards {
                    grid-template-columns: repeat(4, minmax(0, 1fr));
                }
            }

            @media (max-width: 1199.98px) {
                .car-workbench__layout {
                    grid-template-columns: 1fr;
                }

                .car-workbench__aside {
                    position: static;
                    order: 2;
                }
            }

            @media (max-width: 991.98px) {
                .car-workbench__hero-cards {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 767.98px) {
                .car-workbench__hero,
                .car-workbench__panel,
                .car-workbench__main > .card.form-card,
                .car-workbench #custom-tabs-general > .card.mb-4,
                .car-workbench #pills-tabContent,
                .car-workbench #pills-seo-tabContent {
                    border-radius: 22px !important;
                }

                .car-workbench__hero {
                    padding: 1.25rem;
                }

                .car-workbench__hero-cards {
                    grid-template-columns: 1fr;
                }

                .car-workbench__main > .card.form-card > .card-body {
                    padding: 0.8rem;
                }

                .car-workbench #custom-tabs-general > .card.mb-4 > .card-body,
                .car-workbench #pills-tabContent,
                .car-workbench #pills-seo-tabContent {
                    padding: 1rem !important;
                }

                .car-workbench .nav-tabs.nav-tabs-modern {
                    padding: 0.5rem;
                }

                .car-workbench .nav-tabs.nav-tabs-modern .nav-link {
                    padding: 0.75rem 0.9rem;
                }

                .car-workbench .custom-control.custom-switch .custom-control-label {
                    min-height: 64px;
                    padding-right: 0.85rem;
                }
            }
        </style>
    @endpush
@endonce
