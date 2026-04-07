@once
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
        <style>
            .car-studio{--ink:#14213d;--muted:#64748b;--line:#d7e1ea;--soft:#f5f7fb;--surface:#fff;--accent:#ef7d1a;--accent-strong:#d96a0b;--cool:#0f766e;--shadow:0 24px 60px rgba(20,33,61,.12);color:var(--ink)}
            .car-studio__hero{position:relative;overflow:hidden;display:grid;grid-template-columns:minmax(0,1.6fr) minmax(280px,1fr);gap:1.5rem;margin-bottom:1.5rem;padding:2rem;border-radius:32px;background:radial-gradient(circle at top right,rgba(255,204,153,.28),transparent 30%),radial-gradient(circle at bottom left,rgba(15,118,110,.22),transparent 35%),linear-gradient(135deg,#102542 0%,#1f3b73 42%,#0f766e 100%);box-shadow:0 28px 70px rgba(16,37,66,.22)}
            .car-studio__hero::before,.car-studio__hero::after{content:"";position:absolute;border-radius:999px;opacity:.16;pointer-events:none;background:#fff}
            .car-studio__hero::before{width:220px;height:220px;top:-100px;right:12%}
            .car-studio__hero::after{width:180px;height:180px;bottom:-90px;left:18%}
            .car-studio__hero-copy,.car-studio__hero-metrics{position:relative;z-index:1}
            .car-studio__eyebrow{display:inline-flex;align-items:center;gap:.55rem;padding:.55rem .95rem;border-radius:999px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.16);font-size:.78rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#fff}
            .car-studio__eyebrow::before{content:"";width:8px;height:8px;border-radius:50%;background:#ffd6ae;box-shadow:0 0 0 6px rgba(255,214,174,.18)}
            .car-studio__title{margin:1rem 0 0;font-size:clamp(2rem,3vw,3rem);line-height:1.04;font-weight:800;color:#fff}
            .car-studio__subtitle{max-width:720px;margin:1rem 0 0;color:rgba(255,255,255,.82);font-size:1rem;line-height:1.75}
            .car-studio__hero-tags{display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.25rem}
            .car-studio__hero-tag{display:inline-flex;align-items:center;padding:.6rem .9rem;border-radius:999px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.12);color:#fff;font-size:.82rem;font-weight:700}
            .car-studio__hero-metrics{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.9rem;align-content:start}
            .car-studio__metric{min-height:122px;padding:1rem;border-radius:24px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.14);backdrop-filter:blur(14px)}
            .car-studio__metric-label{display:block;margin-bottom:.45rem;color:rgba(255,255,255,.68);font-size:.74rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
            .car-studio__metric-value{display:block;color:#fff;font-size:1.22rem;line-height:1.3;font-weight:800;word-break:break-word}
            .car-studio__metric-note{display:block;margin-top:.45rem;color:rgba(255,255,255,.72);font-size:.82rem;line-height:1.45}
            .car-studio__main{position:relative}
            .studio-sidecard,.studio-frame,.studio-panel,.studio-language-pane,.studio-seo-pane{border:1px solid var(--line);border-radius:28px;background:var(--surface);box-shadow:var(--shadow)}
            .studio-sidecard{padding:1.15rem;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}
            .studio-sidecard__header{display:flex;align-items:center;gap:.8rem;margin-bottom:.9rem}
            .studio-sidecard__icon{width:42px;height:42px;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;background:linear-gradient(135deg,rgba(239,125,26,.14),rgba(15,118,110,.12));color:var(--ink);font-size:1.1rem}
            .studio-sidecard__title,.studio-frame__meta-copy h3,.studio-panel__title,.studio-ai-banner__copy h4,.studio-language-head h4{margin:0;font-weight:800;color:var(--ink)}
            .studio-sidecard__copy,.studio-frame__meta-copy p,.studio-panel__description,.studio-ai-banner__copy p,.studio-language-head p,.studio-submitbar__copy{margin:0;color:var(--muted);line-height:1.6}
            .studio-list{list-style:none;margin:0;padding:0}.studio-list li+li{margin-top:.75rem}
            .studio-list__item{padding:.9rem 1rem;border-radius:20px;background:var(--soft);border:1px solid var(--line)}
            .studio-list__label,.studio-label,.studio-panel__eyebrow,.studio-frame__eyebrow{display:block;font-size:.76rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
            .studio-list__label{margin-bottom:.3rem;color:#94a3b8}
            .studio-list__value{display:block;color:var(--ink);font-size:.92rem;line-height:1.5;font-weight:700}
            .studio-sidecard__image{overflow:hidden;border-radius:22px;border:1px solid var(--line);background:var(--soft)}.studio-sidecard__image img{width:100%;height:190px;object-fit:cover;display:block}
            .studio-frame{overflow:hidden;background:linear-gradient(180deg,#fbfcfe 0%,#fff 12%)}.studio-frame__header{padding:1rem;border-bottom:1px solid var(--line);background:linear-gradient(180deg,#f9fbff 0%,#fff 100%)}
            .studio-frame__meta{display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:.9rem}
            .studio-frame__eyebrow{display:inline-flex;align-items:center;gap:.45rem;color:var(--cool)}.studio-frame__eyebrow::before,.studio-panel__eyebrow::before{content:"";border-radius:50%}
            .studio-frame__eyebrow::before{width:8px;height:8px;background:var(--cool)}
            .studio-tab-nav{display:flex;flex-wrap:nowrap;gap:.8rem;margin:0;padding:.75rem;list-style:none;overflow-x:auto;border-radius:24px;background:#eef3f8}
            .studio-tab-nav .nav-item{flex:0 0 auto}
            .studio-tab-nav .nav-link{display:inline-flex;align-items:center;gap:.5rem;margin:0;padding:.85rem 1.15rem;border:none;border-radius:18px;background:transparent;color:var(--muted);font-weight:800;white-space:nowrap}
            .studio-tab-nav .nav-link.active{background:linear-gradient(135deg,var(--ink),#244a86);color:#fff !important;box-shadow:0 18px 34px rgba(20,33,61,.18)}
            .studio-tab-pane{padding:1rem}.studio-panel{margin-bottom:1rem;overflow:hidden}.studio-panel:last-child{margin-bottom:0}
            .studio-panel__header{display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;padding:1.15rem 1.25rem;border-bottom:1px solid var(--line);background:linear-gradient(135deg,#fffdf8,#f5fbff)}
            .studio-panel__eyebrow{display:inline-flex;align-items:center;gap:.4rem;margin-bottom:.45rem;color:var(--accent-strong)}.studio-panel__eyebrow::before{width:7px;height:7px;background:var(--accent)}
            .studio-panel__body,.studio-language-pane,.studio-seo-pane{padding:1.25rem}
            .studio-grid{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:1rem}
            .studio-span-12{grid-column:span 12}.studio-span-8{grid-column:span 8}.studio-span-6{grid-column:span 6}.studio-span-4{grid-column:span 4}.studio-span-3{grid-column:span 3}
            .studio-field{display:flex;flex-direction:column;gap:.45rem;min-width:0}
            .studio-label{margin:0;color:#415164}
            .studio-field .form-control,.car-studio .select2-container--bootstrap4 .select2-selection{min-height:52px;border-radius:18px !important;border:1px solid var(--line) !important;background:var(--soft) !important;box-shadow:none !important;color:var(--ink) !important}
            .studio-field .form-control{padding:.9rem 1rem}.studio-field textarea.form-control{min-height:150px;padding-top:.95rem}.car-studio .select2-container{width:100% !important}
            .studio-field .form-control:focus,.car-studio .select2-container--bootstrap4.select2-container--focus .select2-selection,.car-studio .select2-container--bootstrap4.select2-container--open .select2-selection{border-color:rgba(15,118,110,.34) !important;background:#fff !important;box-shadow:0 0 0 4px rgba(15,118,110,.12) !important}
            .car-studio .select2-container--bootstrap4 .select2-selection--single,.car-studio .select2-container--bootstrap4 .select2-selection--multiple{display:flex;align-items:center;min-height:52px;padding:.35rem .85rem}
            .car-studio .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered{display:flex;flex-wrap:wrap;gap:.35rem;padding:0}
            .car-studio .select2-container--bootstrap4 .select2-selection__choice{border:none;border-radius:999px;padding:.25rem .65rem;background:rgba(15,118,110,.12);color:var(--cool);font-weight:700}
            .studio-switch-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:.9rem}
            .studio-switch{position:relative}.studio-switch .custom-control{padding-left:0;min-height:100%}.studio-switch .custom-control-input{position:absolute;opacity:0;pointer-events:none}
            .studio-switch .custom-control-label{position:relative;display:flex;flex-direction:column;justify-content:center;min-height:96px;padding:1.15rem 1rem 1.15rem 4.2rem;border-radius:22px;border:1px solid var(--line);background:linear-gradient(180deg,#fff 0%,#f8fafc 100%);color:var(--ink);cursor:pointer;transition:border-color .2s ease,box-shadow .2s ease,background-color .2s ease}
            .studio-switch .custom-control-label::before{top:50%;left:1rem;width:2.35rem;height:1.3rem;border-radius:999px;border:none;background:#cbd5e1;transform:translateY(-50%);box-shadow:none}
            .studio-switch .custom-control-label::after{top:50%;left:calc(1rem + 2px);width:1rem;height:1rem;border-radius:50%;background:#fff;transform:translateY(-50%)}
            .studio-switch .custom-control-input:checked~.custom-control-label{border-color:rgba(15,118,110,.22);background:linear-gradient(180deg,#fff 0%,#eefbf9 100%);box-shadow:0 18px 30px rgba(15,118,110,.1);color:var(--cool)}
            .studio-switch .custom-control-input:checked~.custom-control-label::before{background:var(--cool)}
            .studio-switch .custom-control-input:checked~.custom-control-label::after{transform:translate(17px,-50%)}
            .studio-switch__title{display:block;margin-bottom:.3rem;font-size:.98rem;font-weight:800}.studio-switch__copy{display:block;color:var(--muted);font-size:.84rem;line-height:1.5}
            .studio-price-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem}
            .studio-price-card,.studio-media-card{padding:1rem;border-radius:24px;border:1px solid var(--line);background:linear-gradient(180deg,#fff 0%,#f9fbfd 100%)}
            .studio-price-card__label,.studio-media-count{display:inline-flex;align-items:center;padding:.45rem .75rem;border-radius:999px;font-size:.74rem;font-weight:800;letter-spacing:.06em}
            .studio-price-card__label{text-transform:uppercase;background:rgba(239,125,26,.1);color:var(--accent-strong)}.studio-price-card__body{margin-top:1rem;display:grid;gap:.95rem}
            .studio-media-grid{display:grid;grid-template-columns:minmax(0,.95fr) minmax(0,1.05fr);gap:1rem}
            .studio-media-card h4{margin:0;font-size:1rem;font-weight:800;color:var(--ink)}.studio-media-card p{margin:.45rem 0 0;color:var(--muted);line-height:1.6}
            .studio-dropzone{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.55rem;min-height:180px;margin-top:1rem;padding:1.5rem;border-radius:24px;border:1px dashed rgba(15,118,110,.34);background:radial-gradient(circle at top right,rgba(239,125,26,.12),transparent 35%),linear-gradient(180deg,#f8fffd 0%,#fff 100%);text-align:center;cursor:pointer;transition:transform .2s ease,border-color .2s ease}
            .studio-dropzone:hover{transform:translateY(-1px);border-color:var(--accent)}.studio-dropzone i{font-size:2rem;color:var(--cool)}.studio-dropzone strong{font-size:1rem;font-weight:800;color:var(--ink)}.studio-dropzone span{max-width:290px;color:var(--muted);line-height:1.6;font-size:.9rem}
            .studio-upload-meta,.studio-media-count{margin-top:.9rem;color:var(--muted);font-size:.88rem;line-height:1.6}.studio-media-count{background:rgba(15,118,110,.1);color:var(--cool)}
            .studio-preview-shell{margin-top:1rem;padding:.85rem;border-radius:24px;border:1px solid var(--line);background:#f8fafc}.image-rectangle-preview{width:100% !important;max-width:100%;max-height:260px;border-radius:20px;object-fit:cover;border:1px solid var(--line);background:#e2e8f0}
            .preview-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:.9rem}
            .preview-item{position:relative;overflow:hidden;padding:.55rem;border-radius:22px;background:#0f172a;box-shadow:0 16px 28px rgba(15,23,42,.18)}
            .preview-item img,.preview-item video,.preview-item iframe{width:100%;height:160px;border-radius:16px;object-fit:cover}.preview-name{margin-top:.55rem;color:#dbe7f4;font-size:.82rem;word-break:break-word}
            .preview-empty{display:flex;align-items:center;justify-content:center;min-height:180px;padding:1rem;border-radius:18px;border:1px dashed var(--line);background:linear-gradient(160deg,#f8fafc,#edf3f8);color:var(--muted);text-align:center;font-weight:700}
            .remove-preview{position:absolute;top:10px;right:10px;width:34px;height:34px;border:none;border-radius:50%;background:rgba(15,23,42,.9);color:#fff;font-size:1.1rem;box-shadow:0 10px 18px rgba(15,23,42,.24)}.remove-preview:hover{background:#dc2626}
            .studio-ai-banner{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1rem;padding:1.15rem 1.25rem;margin-bottom:1rem;border-radius:28px;border:1px solid rgba(15,118,110,.18);background:radial-gradient(circle at top right,rgba(239,125,26,.12),transparent 30%),linear-gradient(135deg,#effaf7 0%,#fff 100%);box-shadow:0 18px 34px rgba(15,118,110,.08)}
            .studio-language-nav{display:flex;flex-wrap:wrap;gap:.65rem;margin-bottom:1rem}.studio-language-nav .nav-link{border:1px solid var(--line);border-radius:16px;padding:.7rem .95rem;background:#f4f7fb;color:var(--muted);font-weight:800}
            .studio-language-nav .nav-link.active{background:linear-gradient(135deg,#fff4e8,#fff);border-color:rgba(239,125,26,.18);color:var(--accent-strong) !important;box-shadow:0 16px 28px rgba(239,125,26,.12)}
            .studio-language-head{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.9rem;margin-bottom:1rem}.studio-robots{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.9rem;margin-top:.5rem}
            .seo-question-group{padding:1rem !important;border:1px solid var(--line) !important;border-radius:22px !important;background:linear-gradient(180deg,#fff 0%,#f8fafc 100%);box-shadow:none !important}
            .studio-alert{margin-bottom:1rem;padding:1rem 1.15rem;border-radius:22px;border:1px solid rgba(220,38,38,.14);background:linear-gradient(180deg,#fff7f7 0%,#fff 100%);box-shadow:0 18px 34px rgba(220,38,38,.06)}
            .studio-alert h4{margin:0 0 .55rem;color:#991b1b;font-size:1rem;font-weight:800}.studio-alert ul{margin:0;padding-left:1.2rem;color:#7f1d1d}
            .studio-submitbar{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1rem;margin-top:1rem;padding:1rem 1.25rem;border-top:1px solid var(--line);background:linear-gradient(180deg,#fff 0%,#f8fbff 100%)}
            .studio-primary-btn,.studio-secondary-btn,.studio-ai-btn{display:inline-flex;align-items:center;justify-content:center;gap:.55rem;min-height:50px;padding:.85rem 1.3rem;border-radius:18px;font-weight:800;border:none;transition:transform .2s ease,box-shadow .2s ease,background .2s ease}
            .studio-primary-btn{background:linear-gradient(135deg,var(--accent),var(--accent-strong));color:#fff;box-shadow:0 18px 30px rgba(239,125,26,.2)}.studio-primary-btn:hover{transform:translateY(-1px);box-shadow:0 20px 34px rgba(239,125,26,.26);color:#fff}
            .studio-secondary-btn,.studio-ai-btn{background:linear-gradient(135deg,var(--ink),#244a86);color:#fff;box-shadow:0 18px 30px rgba(20,33,61,.16)}.studio-secondary-btn:hover,.studio-ai-btn:hover{transform:translateY(-1px);color:#fff}
            .studio-outline-btn{display:inline-flex;align-items:center;gap:.45rem;min-height:46px;padding:.72rem 1rem;border-radius:16px;border:1px solid var(--line);background:#fff;color:var(--ink);font-weight:800}.studio-outline-btn:hover{color:var(--cool);border-color:rgba(15,118,110,.22);background:#f5fffd}
            .studio-main-loader{position:absolute;inset:0;z-index:30;display:none;align-items:center;justify-content:center;flex-direction:column;gap:1rem;border-radius:30px;background:rgba(255,255,255,.8);backdrop-filter:blur(5px)}
            .studio-main-loader__spinner{width:64px;height:64px;border-radius:50%;border:5px solid #dbeafe;border-top-color:var(--cool);animation:carStudioSpin 1s linear infinite}.studio-main-loader__text{color:var(--ink);font-size:.95rem;font-weight:700}
            @keyframes carStudioSpin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
            .invalid-feedback{display:block}.is-invalid,.car-studio .select2-selection.is-invalid{border-color:rgba(220,38,38,.38) !important}
            @media (max-width:1399.98px){.car-studio__hero{grid-template-columns:1fr}.car-studio__hero-metrics{grid-template-columns:repeat(4,minmax(0,1fr))}}
            @media (max-width:1199.98px){.studio-price-grid,.studio-media-grid{grid-template-columns:1fr}}
            @media (max-width:991.98px){.car-studio__hero-metrics{grid-template-columns:repeat(2,minmax(0,1fr))}.studio-grid{grid-template-columns:repeat(6,minmax(0,1fr))}.studio-span-8,.studio-span-6,.studio-span-4,.studio-span-3{grid-column:span 6}}
            @media (max-width:767.98px){.car-studio__hero,.studio-frame,.studio-sidecard,.studio-panel,.studio-language-pane,.studio-seo-pane{border-radius:24px}.car-studio__hero{padding:1.35rem}.car-studio__hero-metrics{grid-template-columns:1fr}.studio-frame__meta,.studio-panel__header,.studio-language-head,.studio-submitbar,.studio-ai-banner{flex-direction:column;align-items:flex-start}.studio-robots{grid-template-columns:1fr}.studio-tab-pane,.studio-panel__body,.studio-language-pane,.studio-seo-pane{padding:1rem}}
        </style>
    @endpush
@endonce
