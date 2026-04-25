@extends('layouts.admin_layout')

@section('title', 'Home CMS')

@section('page-title')
    {{ __('Home CMS') }}
@endsection

@include('includes.admin.form_theme')

@push('styles')
    <style>
        .home-editor-shell { display: grid; gap: 1.5rem; }
        .home-editor-topbar, .home-editor-pane, .home-upload-card, .home-status-card, .home-editor-section, .home-seo-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.07);
        }

        .home-editor-topbar {
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            background: linear-gradient(135deg, #0f172a, #1d4ed8 60%, #38bdf8);
            color: #fff;
            border: 0;
        }

        .home-editor-topbar p { color: rgba(255, 255, 255, 0.78); margin: 0; }
        .home-editor-topbar .btn { border-radius: 999px; }
        .home-editor-pane, .home-editor-section, .home-seo-card { padding: 1.5rem; }
        .home-editor-tabs, .home-locale-nav, .home-seo-locale-nav {
            display: flex;
            gap: .75rem;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: .25rem;
        }

        .home-editor-tabs .nav-link, .home-locale-nav .nav-link, .home-seo-locale-nav .nav-link {
            border-radius: 999px;
            border: 1px solid #dbe2f0;
            background: #fff;
            color: #334155;
            font-weight: 700;
            white-space: nowrap;
        }

        .home-editor-tabs .nav-link.active, .home-locale-nav .nav-link.active, .home-seo-locale-nav .nav-link.active {
            background: linear-gradient(135deg, #2563eb, #0ea5e9);
            border-color: transparent;
            color: #fff;
        }

        .home-editor-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem; }
        .home-upload-card, .home-status-card { padding: 1.25rem; }
        .home-upload-card h5, .home-status-card h5 { margin-bottom: .35rem; }
        .home-upload-card p, .home-status-card p { color: #64748b; margin-bottom: 1rem; }

        .home-media-preview {
            width: 100%;
            min-height: 260px;
            border-radius: 20px;
            object-fit: cover;
            background: #0f172a;
            border: 1px solid #dbe2f0;
        }

        .home-media-empty {
            min-height: 260px;
            border-radius: 20px;
            border: 1px dashed #cbd5e1;
            background: linear-gradient(160deg, #f8fafc, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: #64748b;
            text-align: center;
        }

        .home-upload-dropzone {
            margin-top: 1rem;
            display: block;
            padding: 1rem 1.1rem;
            border-radius: 18px;
            border: 1px dashed #93c5fd;
            background: #eff6ff;
            cursor: pointer;
        }

        .home-upload-dropzone strong { display: block; color: #1d4ed8; }
        .home-upload-dropzone small { color: #64748b; }
        .home-upload-filename { margin-top: .75rem; color: #475569; font-size: .9rem; }
        .home-status-metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .9rem; margin-top: 1rem; }

        .home-metric-box {
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: .95rem;
        }

        .home-metric-box span { display: block; color: #64748b; font-size: .82rem; font-weight: 600; }
        .home-metric-box strong { display: block; color: #0f172a; margin-top: .2rem; font-size: 1.02rem; }
        .home-section-kicker { color: #2563eb; text-transform: uppercase; letter-spacing: .08em; font-size: .78rem; font-weight: 800; }
        .home-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: .45rem .85rem;
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 700;
        }

        .home-field-card {
            padding: 1rem;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            height: 100%;
        }

        .home-field-input { border-radius: 14px; background: #fff; }
        .home-switch-card .form-check { display: flex; align-items: center; gap: .75rem; min-height: 44px; margin-bottom: 0; }
        .home-switch-card .form-check-input { margin: 0; float: none; width: 2.6rem; height: 1.35rem; cursor: pointer; }
        .home-switch-card .form-check-label { margin: 0; color: #0f172a; font-weight: 700; }
        .home-client-slider-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
        .home-client-slot {
            padding: 1rem;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .home-client-logo-preview {
            width: 100%;
            height: 120px;
            border-radius: 16px;
            border: 1px dashed #cbd5e1;
            background: #fff;
            object-fit: contain;
            padding: 1rem;
        }

        .home-client-logo-empty {
            height: 120px;
            border-radius: 16px;
            border: 1px dashed #cbd5e1;
            background: #fff;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 1rem;
        }

        .seo-question-group {
            border: 1px solid #dbe2f0;
            border-radius: 18px;
            padding: 1rem;
            background: #f8fafc;
        }

        .seo-question-group + .seo-question-group { margin-top: .85rem; }

        @media (max-width: 991.98px) {
            .home-editor-grid, .home-status-metrics, .home-client-slider-grid { grid-template-columns: 1fr; }
            .home-editor-topbar { flex-direction: column; align-items: stretch; }
        }
    </style>
@endpush

@section('content')
    @php
        $heroVideoUrl = $item->hero_header_video_path ? asset('storage/' . $item->hero_header_video_path) : null;
        $heroImageUrl = $item->hero_header_image_path ? asset('storage/' . $item->hero_header_image_path) : null;
        $heroImagePlaceholder = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='960' height='620' viewBox='0 0 960 620'%3E%3Crect width='100%25' height='100%25' fill='%23eff6ff'/%3E%3Ctext x='50%25' y='50%25' fill='%23475569' font-size='34' text-anchor='middle' dy='.3em'%3EHero Image Preview%3C/text%3E%3C/svg%3E";
        $seoQuestionsByLocale = $item->seoQuestions->groupBy('locale');
    @endphp

    <form action="{{ route('admin.' . $modelName . '.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="page_name" value="{{ old('page_name', $item->page_name) }}">

        <div class="home-editor-shell">
            <div class="form-hero">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                    <div>
                        <h2>{{ __('Homepage Content Manager') }}</h2>
                        <p>{{ __('Manage homepage media, section copy, slider logos, and SEO from one tabbed workspace for English, Arabic, and Russian.') }}</p>
                    </div>
                    <div class="d-flex flex-wrap">
                        <span class="hero-pill"><i class="fas fa-language"></i>{{ count($homeLocales) }} {{ __('Locales') }}</span>
                        <span class="hero-pill"><i class="fas fa-photo-film"></i>{{ __('Direct media upload') }}</span>
                        <span class="hero-pill"><i class="fas fa-house"></i>{{ __('Homepage ID #:id', ['id' => $item->id]) }}</span>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-0">
                    <strong>{{ __('Please correct the following errors:') }}</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="home-editor-topbar">
                <div>
                    <div class="fw-semibold">{{ __('Homepage record is ready for editing') }}</div>
                    <p>{{ __('Hero image and video now save directly, so updates are no longer blocked by queued file jobs.') }}</p>
                </div>
                <button type="submit" class="btn btn-warning text-dark fw-semibold px-4">{{ __('Save Home Page') }}</button>
            </div>

            <ul class="nav nav-pills home-editor-tabs" role="tablist">
                @foreach ($editorTabs as $tab)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" type="button"
                            data-bs-toggle="pill" data-bs-target="#tab-{{ $tab['id'] }}" role="tab"
                            aria-controls="tab-{{ $tab['id'] }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            <i class="{{ $tab['icon'] }} me-1"></i>{{ $tab['label'] }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active home-editor-pane" id="tab-overview" role="tabpanel">
                    <div class="home-editor-grid mb-4">
                        <div class="home-upload-card">
                            <h5>{{ __('Hero Video') }}</h5>
                            <p>{{ __('Upload the looping homepage background video used when hero type is set to video.') }}</p>

                            @if ($heroVideoUrl)
                                <video id="hero_video_preview" class="home-media-preview" controls>
                                    <source src="{{ $heroVideoUrl }}">
                                </video>
                            @else
                                <div class="home-media-empty" id="hero_video_empty">{{ __('No hero video uploaded yet.') }}</div>
                                <video id="hero_video_preview" class="home-media-preview d-none" controls></video>
                            @endif

                            <label class="home-upload-dropzone" for="hero_header_video_path">
                                <strong>{{ __('Choose Hero Video') }}</strong>
                                <small>{{ __('MP4, WEBM, OGG, MOV up to 100 MB') }}</small>
                            </label>
                            <input type="file" name="hero_header_video_path" id="hero_header_video_path"
                                class="d-none home-upload-input @error('hero_header_video_path') is-invalid @enderror"
                                accept="video/*" data-preview-target="hero_video_preview" data-empty-target="hero_video_empty" data-file-label="hero_video_filename">
                            <div class="home-upload-filename" id="hero_video_filename">{{ __('No new video selected') }}</div>
                            @error('hero_header_video_path')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="home-upload-card">
                            <h5>{{ __('Hero Image') }}</h5>
                            <p>{{ __('Upload the poster image or static homepage hero image.') }}</p>

                            @if ($heroImageUrl)
                                <img id="hero_image_preview" src="{{ $heroImageUrl }}" alt="{{ __('Hero image preview') }}" class="home-media-preview">
                            @else
                                <div class="home-media-empty" id="hero_image_empty">{{ __('No hero image uploaded yet.') }}</div>
                                <img id="hero_image_preview" src="{{ $heroImagePlaceholder }}" alt="{{ __('Hero image preview') }}" class="home-media-preview d-none">
                            @endif

                            <label class="home-upload-dropzone" for="hero_header_image_path">
                                <strong>{{ __('Choose Hero Image') }}</strong>
                                <small>{{ __('JPG, PNG, SVG, WEBP up to 10 MB') }}</small>
                            </label>
                            <input type="file" name="hero_header_image_path" id="hero_header_image_path"
                                class="d-none home-upload-input @error('hero_header_image_path') is-invalid @enderror"
                                accept="image/*" data-preview-target="hero_image_preview" data-empty-target="hero_image_empty" data-file-label="hero_image_filename">
                            <div class="home-upload-filename" id="hero_image_filename">{{ __('No new image selected') }}</div>
                            @error('hero_header_image_path')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="home-status-card">
                        <h5>{{ __('Status & Delivery') }}</h5>
                        <p>{{ __('Switch between image and video hero mode, and keep the homepage enabled for the storefront.') }}</p>
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-4">
                                <label for="hero_type" class="font-weight-bold">{{ __('Hero Media Type') }}</label>
                                <select name="hero_type" id="hero_type" class="form-control home-field-input @error('hero_type') is-invalid @enderror">
                                    <option value="video" @selected(old('hero_type', $item->hero_type) === 'video')>{{ __('Video') }}</option>
                                    <option value="image" @selected(old('hero_type', $item->hero_type) === 'image')>{{ __('Image') }}</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="font-weight-bold d-block">{{ __('Homepage Status') }}</label>
                                <div class="home-field-card home-switch-card mt-2">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input" @checked((bool) old('is_active', $item->is_active))>
                                        <label class="form-check-label" for="is_active">{{ __('Homepage is active') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label class="font-weight-bold">{{ __('Page Name') }}</label>
                                <input type="text" class="form-control home-field-input" value="{{ old('page_name', $item->page_name) }}" readonly>
                            </div>
                        </div>

                        <div class="home-status-metrics">
                            <div class="home-metric-box">
                                <span>{{ __('Current Hero Type') }}</span>
                                <strong>{{ strtoupper(old('hero_type', $item->hero_type)) }}</strong>
                            </div>
                            <div class="home-metric-box">
                                <span>{{ __('Current Image') }}</span>
                                <strong>{{ $item->hero_header_image_path ? __('Uploaded') : __('Missing') }}</strong>
                            </div>
                            <div class="home-metric-box">
                                <span>{{ __('Current Video') }}</span>
                                <strong>{{ $item->hero_header_video_path ? __('Uploaded') : __('Missing') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach (['hero', 'features', 'rental', 'headings', 'testimonials', 'support', 'shared'] as $sectionKey)
                    <div class="tab-pane fade home-editor-pane" id="tab-{{ $sectionKey }}" role="tabpanel">
                        @include('pages.admin.homes.partials.section-panel', [
                            'section' => $editorSections[$sectionKey],
                            'homeLocales' => $homeLocales,
                            'translationsByLocale' => $translationsByLocale,
                            'prefillValues' => $prefillValues,
                        ])
                    </div>
                @endforeach

                <div class="tab-pane fade home-editor-pane" id="tab-clients" role="tabpanel">
                    @include('pages.admin.homes.partials.client-slider-panel', [
                        'clientSliderItems' => $clientSliderItems,
                    ])
                </div>

                <div class="tab-pane fade home-editor-pane" id="tab-seo" role="tabpanel">
                    <div class="home-seo-card">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3 mb-4">
                            <div>
                                <span class="home-section-kicker">{{ __('Search Setup') }}</span>
                                <h4 class="mb-1">{{ __('Homepage SEO') }}</h4>
                                <p class="text-muted mb-0">{{ __('Meta data, robots directives, and structured question blocks for each supported locale.') }}</p>
                            </div>
                            <span class="home-badge">{{ count($homeLocales) }} {{ __('Locales') }}</span>
                        </div>

                        <ul class="nav nav-pills home-seo-locale-nav mb-4" role="tablist">
                            @foreach ($homeLocales as $locale)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" type="button"
                                        data-bs-toggle="pill" data-bs-target="#seo-{{ $locale['code'] }}" role="tab">
                                        {{ $locale['name'] }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content">
                            @foreach ($homeLocales as $locale)
                                @php
                                    $localeCode = $locale['code'];
                                    $translation = $translationsByLocale[$localeCode] ?? null;
                                    $metaKeywordsJson = $translation?->meta_keywords ?? '';
                                    $metaKeywords = json_decode((string) $metaKeywordsJson, true);
                                    $metaKeywordsValue = '';

                                    if (is_array($metaKeywords) && !empty($metaKeywords)) {
                                        if (isset($metaKeywords[0]) && is_array($metaKeywords[0]) && isset($metaKeywords[0]['value'])) {
                                            $metaKeywordsValue = implode(',', array_column($metaKeywords, 'value'));
                                        } else {
                                            $metaKeywordsValue = implode(',', $metaKeywords);
                                        }
                                    } elseif (is_string($metaKeywordsJson) && $metaKeywordsJson !== '[]') {
                                        $metaKeywordsValue = $metaKeywordsJson;
                                    }

                                    $seoRows = old('seo_questions.' . $localeCode);
                                    if (!is_array($seoRows)) {
                                        $seoRows = $seoQuestionsByLocale->get($localeCode, collect())->map(function ($seoQuestion) {
                                            return ['question' => $seoQuestion->question_text, 'answer' => $seoQuestion->answer_text];
                                        })->values()->all();
                                    }
                                    if (empty($seoRows)) {
                                        $seoRows = [['question' => '', 'answer' => '']];
                                    }
                                @endphp

                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="seo-{{ $localeCode }}" role="tabpanel">
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <div class="home-field-card">
                                                <label for="meta_title_{{ $localeCode }}" class="font-weight-bold mb-2">{{ __('Meta Title') }}</label>
                                                <input type="text" name="meta_title[{{ $localeCode }}]" id="meta_title_{{ $localeCode }}" class="form-control home-field-input" value="{{ old('meta_title.' . $localeCode, $translation?->meta_title ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="home-field-card">
                                                <label for="meta_keywords_{{ $localeCode }}" class="font-weight-bold mb-2">{{ __('Meta Keywords') }}</label>
                                                <input type="text" name="meta_keywords[{{ $localeCode }}]" id="meta_keywords_{{ $localeCode }}" class="form-control home-field-input" value="{{ old('meta_keywords.' . $localeCode, $metaKeywordsValue) }}" placeholder="{{ __('keyword one, keyword two') }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="home-field-card">
                                                <label for="meta_description_{{ $localeCode }}" class="font-weight-bold mb-2">{{ __('Meta Description') }}</label>
                                                <textarea name="meta_description[{{ $localeCode }}]" id="meta_description_{{ $localeCode }}" class="form-control home-field-input" rows="4">{{ old('meta_description.' . $localeCode, $translation?->meta_description ?? '') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="home-field-card home-switch-card">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" name="robots_index[{{ $localeCode }}]" id="robots_index_{{ $localeCode }}" class="form-check-input" value="index" @checked(old('robots_index.' . $localeCode, $translation?->robots_index ?? 'index') === 'index')>
                                                    <label class="form-check-label" for="robots_index_{{ $localeCode }}">{{ __('Allow indexing') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="home-field-card home-switch-card">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" name="robots_follow[{{ $localeCode }}]" id="robots_follow_{{ $localeCode }}" class="form-check-input" value="follow" @checked(old('robots_follow.' . $localeCode, $translation?->robots_follow ?? 'follow') === 'follow')>
                                                    <label class="form-check-label" for="robots_follow_{{ $localeCode }}">{{ __('Allow link follow') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-4 mb-3">
                                        <div>
                                            <h5 class="mb-1">{{ __('SEO Questions') }}</h5>
                                            <p class="text-muted mb-0">{{ __('Optional structured content for search engines. Empty rows are ignored.') }}</p>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary rounded-pill add-seo-question" data-lang="{{ $localeCode }}">{{ __('Add Question') }}</button>
                                    </div>

                                    <div class="seo-questions-container" id="seo-questions-{{ $localeCode }}">
                                        @foreach ($seoRows as $index => $seoRow)
                                            <div class="seo-question-group">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">{{ __('Question') }}</label>
                                                    <input type="text" name="seo_questions[{{ $localeCode }}][{{ $index }}][question]" class="form-control home-field-input" value="{{ data_get($seoRow, 'question', '') }}">
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label class="font-weight-bold">{{ __('Answer') }}</label>
                                                    <textarea name="seo_questions[{{ $localeCode }}][{{ $index }}][answer]" class="form-control home-field-input" rows="3">{{ data_get($seoRow, 'answer', '') }}</textarea>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill remove-seo-question">{{ __('Remove') }}</button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success btn-lg rounded-pill px-4">
                    <i class="fas fa-save me-1"></i>{{ __('Save Home Page') }}
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const legacyTabMap = {
            '#home-section-general': '#tab-overview',
            '#home-section-hero': '#tab-hero',
            '#home-section-features': '#tab-features',
            '#home-section-rental': '#tab-rental',
            '#home-section-headings': '#tab-headings',
            '#home-section-testimonials': '#tab-testimonials',
            '#home-section-clients': '#tab-clients',
            '#home-section-support': '#tab-support',
            '#home-section-shared': '#tab-shared',
            '#home-section-seo': '#tab-seo'
        };

        function activateTabByHash(hash) {
            const normalizedHash = legacyTabMap[hash] || hash;

            if (!normalizedHash) {
                return;
            }

            const trigger = document.querySelector('[data-bs-target="' + normalizedHash + '"]');
            if (!trigger || typeof bootstrap === 'undefined' || !bootstrap.Tab) {
                return;
            }

            bootstrap.Tab.getOrCreateInstance(trigger).show();
            if (normalizedHash !== hash) {
                history.replaceState(null, '', normalizedHash);
            }

            const targetPane = document.querySelector(normalizedHash);
            if (targetPane) {
                targetPane.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        activateTabByHash(window.location.hash);

        window.addEventListener('hashchange', function () {
            activateTabByHash(window.location.hash);
        });

        document.querySelectorAll('.home-editor-tabs .nav-link').forEach(function (trigger) {
            trigger.addEventListener('shown.bs.tab', function () {
                const target = this.getAttribute('data-bs-target');
                if (!target) {
                    return;
                }

                history.replaceState(null, '', target);
            });
        });

        document.querySelectorAll('.home-upload-input').forEach(function (input) {
            input.addEventListener('change', function () {
                const file = this.files && this.files[0];
                const previewElement = document.getElementById(this.dataset.previewTarget);
                const emptyElement = this.dataset.emptyTarget ? document.getElementById(this.dataset.emptyTarget) : null;
                const fileLabel = this.dataset.fileLabel ? document.getElementById(this.dataset.fileLabel) : null;

                if (fileLabel) {
                    fileLabel.textContent = file ? file.name : "{{ __('No file selected') }}";
                }

                if (!file || !previewElement) {
                    return;
                }

                const objectUrl = URL.createObjectURL(file);
                previewElement.classList.remove('d-none');

                if (previewElement.tagName === 'IMG') {
                    previewElement.src = objectUrl;
                } else if (previewElement.tagName === 'VIDEO') {
                    previewElement.src = objectUrl;
                    previewElement.load();
                }

                if (emptyElement) {
                    emptyElement.classList.add('d-none');
                }
            });
        });

        document.querySelectorAll('.add-seo-question').forEach(function (button) {
            button.addEventListener('click', function () {
                const lang = this.getAttribute('data-lang');
                const container = document.getElementById('seo-questions-' + lang);
                const count = container.querySelectorAll('.seo-question-group').length;
                const wrapper = document.createElement('div');
                wrapper.className = 'seo-question-group';
                wrapper.innerHTML =
                    '<div class="form-group">' +
                        '<label class="font-weight-bold">{{ __('Question') }}</label>' +
                        '<input type="text" name="seo_questions[' + lang + '][' + count + '][question]" class="form-control home-field-input">' +
                    '</div>' +
                    '<div class="form-group mb-2">' +
                        '<label class="font-weight-bold">{{ __('Answer') }}</label>' +
                        '<textarea name="seo_questions[' + lang + '][' + count + '][answer]" class="form-control home-field-input" rows="3"></textarea>' +
                    '</div>' +
                    '<button type="button" class="btn btn-sm btn-outline-danger rounded-pill remove-seo-question">{{ __('Remove') }}</button>';
                container.appendChild(wrapper);
            });
        });

        document.addEventListener('click', function (event) {
            if (!event.target.classList.contains('remove-seo-question')) {
                return;
            }

            const group = event.target.closest('.seo-question-group');
            if (!group) {
                return;
            }

            const container = group.parentElement;
            if (container.querySelectorAll('.seo-question-group').length === 1) {
                group.querySelectorAll('input, textarea').forEach(function (field) {
                    field.value = '';
                });
                return;
            }

            group.remove();
        });
    </script>
@endpush
