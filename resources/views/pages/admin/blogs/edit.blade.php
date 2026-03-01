@extends('layouts.admin_layout')

@section('title', 'Edit ' . $modelName)

@section('page-title')
    {{ __('Edit :entity', ['entity' => $modelName]) }}
@endsection

@section('page-actions')
    <a href="{{ route('admin.' . $modelName . '.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center mb-2">
        <i class="fas fa-arrow-left me-1"></i>{{ __('Back to List') }}
    </a>
@endsection

@include('includes.admin.blog_editor_theme')

@section('content')
    @php
        $placeholderImage = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='800' height='560' viewBox='0 0 800 560'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0' y1='0' x2='1' y2='1'%3E%3Cstop stop-color='%23dbeafe'/%3E%3Cstop offset='1' stop-color='%23e2e8f0'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23g)'/%3E%3Cpath d='M0 430L170 280l120 90 165-180 120 115 225-175v430H0z' fill='%23cbd5e1'/%3E%3Ctext x='50%25' y='50%25' fill='%23475569' font-size='28' text-anchor='middle' dy='.3em'%3EFeature Image Preview%3C/text%3E%3C/svg%3E";
        $rawImagePath = ltrim((string) ($item->image_path ?? ''), '/');
        $imagePreview = $rawImagePath !== ''
            ? (str_starts_with($rawImagePath, 'storage/') ? asset($rawImagePath) : asset('storage/' . $rawImagePath))
            : $placeholderImage;
        $selectedCarIds = collect(old('cars', $item->cars->pluck('id')->all()))->map(fn ($value) => (string) $value)->all();
        $translationsByLocale = $item->translations->keyBy('locale');
        $seoQuestionsByLocale = $item->seoQuestions->groupBy('locale');
    @endphp

    <div class="blog-editor-layout">
        <div>
            <div class="blog-editor-hero mb-4">
                <h2>{{ __('Shape the Story') }}</h2>
                <p>{{ __('Refine the article, improve language coverage, and tighten SEO from one cleaner editing workspace.') }}</p>
                <div class="blog-editor-hero-metrics">
                    <span class="blog-editor-chip"><i class="fas fa-language"></i>{{ __(':count Locale(s)', ['count' => $activeLanguages->count()]) }}</span>
                    <span class="blog-editor-chip"><i class="fas fa-image"></i>{{ __('Cover managed') }}</span>
                    <span class="blog-editor-chip"><i class="fas fa-search"></i>{{ __('SEO maintained') }}</span>
                    <span class="blog-editor-chip"><i class="fas fa-pen"></i>{{ __('Live revisions') }}</span>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                    <strong>{{ __('Please review the highlighted fields.') }}</strong>
                    <div class="small mt-1">{{ $errors->first() }}</div>
                </div>
            @endif

            <form action="{{ route('admin.' . $modelName . '.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="blog-editor">
                @csrf
                @method('PUT')

                <div class="card blog-editor-main-card">
                    <div class="blog-editor-tabbar nav nav-pills" role="tablist">
                        <button class="nav-link active" type="button" data-bs-toggle="pill" data-bs-target="#blog-edit-general" role="tab">
                            <i class="fas fa-pen-nib"></i>{{ __('General') }}
                        </button>
                        <button class="nav-link" type="button" data-bs-toggle="pill" data-bs-target="#blog-edit-translated" role="tab">
                            <i class="fas fa-language"></i>{{ __('Translated Content') }}
                        </button>
                        <button class="nav-link" type="button" data-bs-toggle="pill" data-bs-target="#blog-edit-seo" role="tab">
                            <i class="fas fa-chart-line"></i>{{ __('SEO & Discovery') }}
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active blog-editor-pane" id="blog-edit-general" role="tabpanel">
                                <div class="row g-4">
                                    <div class="col-lg-5">
                                        <div class="blog-editor-panel h-100">
                                            <span class="blog-editor-kicker">{{ __('Cover Story') }}</span>
                                            <h5 class="blog-editor-panel-title">{{ __('Feature Image') }}</h5>
                                            <div class="blog-editor-preview-box mb-3">
                                                <img src="{{ $imagePreview }}" alt="{{ __('Blog preview') }}" id="blogImagePreview">
                                            </div>
                                            <label for="image_path" class="blog-editor-label">{{ __('Replace Image') }}</label>
                                            <input type="file" name="image_path" id="image_path" class="form-control @error('image_path') is-invalid @enderror" accept=".jpg,.jpeg,.png,.svg,.webp">
                                            @error('image_path')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <p class="blog-editor-hint mt-3 mb-0">{{ __('Swap the cover only when it improves readability in cards, banners, and social previews.') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-lg-7">
                                        <div class="blog-editor-panel">
                                            <span class="blog-editor-kicker">{{ __('Connections') }}</span>
                                            <h5 class="blog-editor-panel-title">{{ __('Post Settings') }}</h5>

                                            <div class="mb-4">
                                                <label for="cars" class="blog-editor-label">{{ __('Cars Related to This Post') }}</label>
                                                <select class="form-control car-select" name="cars[]" id="cars" multiple="multiple">
                                                    @foreach ($cars as $car)
                                                        @php
                                                            $carTranslation = $car->translations->first();
                                                            $carImage = $car->default_image_path
                                                                ? asset('storage/' . ltrim($car->default_image_path, '/'))
                                                                : asset('admin/assets/img/car/car-01.jpg');
                                                        @endphp
                                                        <option value="{{ $car->id }}" data-image="{{ $carImage }}" @selected(in_array((string) $car->id, $selectedCarIds, true))>
                                                            {{ $carTranslation->name ?? __('Car #:id', ['id' => $car->id]) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <p class="blog-editor-hint mt-2 mb-0">{{ __('Keep related vehicles aligned with the story so editorial content still drives discovery.') }}</p>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="blog-editor-switch">
                                                        <div>
                                                            <strong>{{ __('Publish State') }}</strong>
                                                            <span>{{ __('Control whether this article is active in listings and the storefront.') }}</span>
                                                        </div>
                                                        <div class="form-check form-switch m-0">
                                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" @checked((bool) old('is_active', $item->is_active))>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="blog-editor-switch">
                                                        <div>
                                                            <strong>{{ __('Homepage Spotlight') }}</strong>
                                                            <span>{{ __('Decide whether this article should stay featured on the homepage.') }}</span>
                                                        </div>
                                                        <div class="form-check form-switch m-0">
                                                            <input class="form-check-input" type="checkbox" name="show_in_home" id="show_in_home" value="1" @checked((bool) old('show_in_home', $item->show_in_home))>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade blog-editor-pane" id="blog-edit-translated" role="tabpanel">
                                <div class="nav nav-pills blog-editor-langbar" role="tablist">
                                    @foreach ($activeLanguages as $lang)
                                        <button class="nav-link @if($loop->first) active @endif" type="button" data-bs-toggle="pill" data-bs-target="#blog-edit-lang-{{ $lang->code }}" role="tab">
                                            {{ $lang->name }}
                                        </button>
                                    @endforeach
                                </div>

                                <div class="tab-content">
                                    @foreach ($activeLanguages as $lang)
                                        @php
                                            $translation = $translationsByLocale->get($lang->code);
                                        @endphp
                                        <div class="tab-pane fade @if($loop->first) show active @endif" id="blog-edit-lang-{{ $lang->code }}" role="tabpanel">
                                            <div class="blog-editor-langpane">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label for="title_{{ $lang->code }}" class="blog-editor-label">{{ __('Title') }} ({{ $lang->name }})</label>
                                                        <input type="text" name="title[{{ $lang->code }}]" id="title_{{ $lang->code }}" class="form-control @error('title.' . $lang->code) is-invalid @enderror" value="{{ old('title.' . $lang->code, $translation->title ?? '') }}">
                                                        @error('title.' . $lang->code)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="description_{{ $lang->code }}" class="blog-editor-label">{{ __('Description') }} ({{ $lang->name }})</label>
                                                        <textarea name="description[{{ $lang->code }}]" id="description_{{ $lang->code }}" class="form-control @error('description.' . $lang->code) is-invalid @enderror" rows="4">{{ old('description.' . $lang->code, $translation->description ?? '') }}</textarea>
                                                        @error('description.' . $lang->code)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="content_{{ $lang->code }}" class="blog-editor-label">{{ __('Content') }} ({{ $lang->name }})</label>
                                                        <textarea name="content[{{ $lang->code }}]" id="content_{{ $lang->code }}" class="form-control tinymce @error('content.' . $lang->code) is-invalid @enderror" rows="10">{{ old('content.' . $lang->code, $translation->content ?? '') }}</textarea>
                                                        @error('content.' . $lang->code)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="tab-pane fade blog-editor-pane" id="blog-edit-seo" role="tabpanel">
                                <div class="nav nav-pills blog-editor-langbar" role="tablist">
                                    @foreach ($activeLanguages as $lang)
                                        <button class="nav-link @if($loop->first) active @endif" type="button" data-bs-toggle="pill" data-bs-target="#blog-edit-seo-lang-{{ $lang->code }}" role="tab">
                                            {{ $lang->name }}
                                        </button>
                                    @endforeach
                                </div>

                                <div class="tab-content">
                                    @foreach ($activeLanguages as $lang)
                                        @php
                                            $translation = $translationsByLocale->get($lang->code);
                                            $metaKeywords = json_decode($translation->meta_keywords ?? '[]', true);
                                            $metaKeywords = is_array($metaKeywords) ? $metaKeywords : [];
                                            $keywordString = collect($metaKeywords)
                                                ->map(function ($keyword) {
                                                    if (is_array($keyword)) {
                                                        return $keyword['value'] ?? null;
                                                    }

                                                    return is_string($keyword) ? $keyword : null;
                                                })
                                                ->filter()
                                                ->implode(',');
                                            $seoQuestionRows = old('seo_questions.' . $lang->code);
                                            if (!is_array($seoQuestionRows)) {
                                                $seoQuestionRows = $seoQuestionsByLocale->get($lang->code, collect())
                                                    ->map(fn ($question) => ['question' => $question->question_text, 'answer' => $question->answer_text])
                                                    ->values()
                                                    ->all();
                                            }
                                            if ($seoQuestionRows === []) {
                                                $seoQuestionRows = [['question' => '', 'answer' => '']];
                                            }
                                        @endphp
                                        <div class="tab-pane fade @if($loop->first) show active @endif" id="blog-edit-seo-lang-{{ $lang->code }}" role="tabpanel">
                                            <div class="blog-editor-langpane">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label for="meta_title_{{ $lang->code }}" class="blog-editor-label">{{ __('Meta Title') }} ({{ $lang->name }})</label>
                                                        <input type="text" name="meta_title[{{ $lang->code }}]" id="meta_title_{{ $lang->code }}" class="form-control" value="{{ old('meta_title.' . $lang->code, $translation->meta_title ?? '') }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="meta_description_{{ $lang->code }}" class="blog-editor-label">{{ __('Meta Description') }} ({{ $lang->name }})</label>
                                                        <textarea name="meta_description[{{ $lang->code }}]" id="meta_description_{{ $lang->code }}" class="form-control" rows="4">{{ old('meta_description.' . $lang->code, $translation->meta_description ?? '') }}</textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="meta_keywords_{{ $lang->code }}" class="blog-editor-label">{{ __('Meta Keywords') }} ({{ $lang->name }})</label>
                                                        <input type="text" name="meta_keywords[{{ $lang->code }}]" id="meta_keywords_{{ $lang->code }}" class="form-control blog-editor-meta-keywords" value="{{ old('meta_keywords.' . $lang->code, $keywordString) }}" placeholder="{{ __('Enter meta keywords') }}">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="blog-editor-seo-box">
                                                            <h6>{{ __('Search Indexing') }}</h6>
                                                            <p class="blog-editor-hint mb-3">{{ __('Allow search engines to index this locale.') }}</p>
                                                            <div class="form-check form-switch m-0">
                                                                <input class="form-check-input" type="checkbox" name="robots_index[{{ $lang->code }}]" id="robots_index_{{ $lang->code }}" value="index" @checked(old('robots_index.' . $lang->code, $translation->robots_index ?? '') === 'index')>
                                                                <label class="form-check-label ms-2" for="robots_index_{{ $lang->code }}">{{ __('Index') }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="blog-editor-seo-box">
                                                            <h6>{{ __('Link Following') }}</h6>
                                                            <p class="blog-editor-hint mb-3">{{ __('Allow crawlers to follow outgoing links.') }}</p>
                                                            <div class="form-check form-switch m-0">
                                                                <input class="form-check-input" type="checkbox" name="robots_follow[{{ $lang->code }}]" id="robots_follow_{{ $lang->code }}" value="follow" @checked(old('robots_follow.' . $lang->code, $translation->robots_follow ?? '') === 'follow')>
                                                                <label class="form-check-label ms-2" for="robots_follow_{{ $lang->code }}">{{ __('Follow') }}</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="blog-editor-label d-block">{{ __('SEO Questions & Answers') }} ({{ $lang->name }})</label>
                                                        <div class="blog-editor-question-list" id="seo-questions-{{ $lang->code }}" data-next-index="{{ count($seoQuestionRows) }}">
                                                            @foreach ($seoQuestionRows as $index => $seoQuestion)
                                                                <div class="blog-editor-question seo-question-group">
                                                                    <div class="row g-3">
                                                                        <div class="col-12">
                                                                            <input type="text" name="seo_questions[{{ $lang->code }}][{{ $index }}][question]" class="form-control" value="{{ $seoQuestion['question'] ?? '' }}" placeholder="{{ __('Enter question') }}">
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <textarea name="seo_questions[{{ $lang->code }}][{{ $index }}][answer]" class="form-control" rows="3" placeholder="{{ __('Enter answer') }}">{{ $seoQuestion['answer'] ?? '' }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="blog-editor-question-actions">
                                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-question"><i class="fas fa-trash"></i>{{ __('Remove') }}</button>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <button type="button" class="btn btn-outline-primary blog-editor-ghost-btn mt-3 add-question" data-lang="{{ $lang->code }}">
                                                            <i class="fas fa-plus"></i>{{ __('Add Question') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="blog-editor-submitbar">
                            <p>{{ __('Review the updated content across all tabs before saving the changes.') }}</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.' . $modelName . '.index') }}" class="btn btn-outline-secondary blog-editor-ghost-btn">
                                    <i class="fas fa-arrow-left"></i>{{ __('Back to List') }}
                                </a>
                                <button type="submit" class="btn btn-primary blog-editor-ghost-btn">
                                    <i class="fas fa-save"></i>{{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card blog-editor-sidecard">
            <div class="card-body">
                <span class="blog-editor-kicker">{{ __('Editing Focus') }}</span>
                <h5 class="blog-editor-panel-title">{{ __('Publishing Checklist') }}</h5>
                <div class="blog-editor-preview-box mb-3">
                    <img src="{{ $imagePreview }}" alt="{{ __('Sidebar preview') }}" id="blogImagePreviewMirror">
                </div>
                <div class="blog-editor-checklist">
                    <div class="blog-editor-checklist-item">
                        <i class="fas fa-image"></i>
                        <div>
                            <strong>{{ __('Cover Control') }}</strong>
                            <span>{{ __('Refresh the image only when the article needs a stronger visual direction.') }}</span>
                        </div>
                    </div>
                    <div class="blog-editor-checklist-item">
                        <i class="fas fa-language"></i>
                        <div>
                            <strong>{{ __('Locale Review') }}</strong>
                            <span>{{ __('Keep title, description, and content aligned across every active language.') }}</span>
                        </div>
                    </div>
                    <div class="blog-editor-checklist-item">
                        <i class="fas fa-search"></i>
                        <div>
                            <strong>{{ __('SEO Review') }}</strong>
                            <span>{{ __('Maintain keywords, robots rules, and Q&A content so search coverage stays stable.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const imageInput = document.getElementById('image_path');
            const previews = [document.getElementById('blogImagePreview'), document.getElementById('blogImagePreviewMirror')];

            if (imageInput) {
                imageInput.addEventListener('change', function (event) {
                    const file = event.target.files && event.target.files[0];
                    if (!file) {
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (loadEvent) {
                        previews.forEach(function (preview) {
                            if (preview) {
                                preview.src = loadEvent.target.result;
                            }
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }

            document.querySelectorAll('.add-question').forEach(function (button) {
                button.addEventListener('click', function () {
                    const lang = this.dataset.lang;
                    const container = document.getElementById('seo-questions-' + lang);
                    if (!container) {
                        return;
                    }

                    const count = Number(container.dataset.nextIndex || container.querySelectorAll('.seo-question-group').length);
                    container.dataset.nextIndex = String(count + 1);
                    const group = document.createElement('div');
                    group.className = 'blog-editor-question seo-question-group';
                    group.innerHTML = '<div class="row g-3"><div class="col-12"><input type="text" name="seo_questions[' + lang + '][' + count + '][question]" class="form-control" placeholder="{{ __('Enter question') }}"></div><div class="col-12"><textarea name="seo_questions[' + lang + '][' + count + '][answer]" class="form-control" rows="3" placeholder="{{ __('Enter answer') }}"></textarea></div></div><div class="blog-editor-question-actions"><button type="button" class="btn btn-outline-danger btn-sm remove-question"><i class="fas fa-trash"></i>{{ __('Remove') }}</button></div>';
                    container.appendChild(group);
                });
            });

            document.addEventListener('click', function (event) {
                const removeButton = event.target.closest('.remove-question');
                if (!removeButton) {
                    return;
                }

                const group = removeButton.closest('.seo-question-group');
                if (group) {
                    group.remove();
                }
            });

            if (window.jQuery && typeof window.jQuery.fn.select2 !== 'undefined') {
                const $ = window.jQuery;

                function formatCar(option) {
                    if (!option.id) {
                        return option.text;
                    }

                    const image = $(option.element).data('image');
                    return $('<span class="d-inline-flex align-items-center"><img src="' + image + '" alt="" style="width:44px;height:32px;object-fit:cover;border-radius:8px;margin-right:8px;"><span>' + $(option.element).text() + '</span></span>');
                }

                $('.car-select').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: @json(__('Select cars')),
                    allowClear: true,
                    templateResult: formatCar,
                    templateSelection: formatCar
                });
            }

            if (typeof Tagify !== 'undefined') {
                document.querySelectorAll('.blog-editor-meta-keywords').forEach(function (input) {
                    new Tagify(input, {
                        placeholder: @json(__('Enter meta keywords'))
                    });
                });
            }
        });
    </script>
@endpush
