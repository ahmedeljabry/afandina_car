@php
    $isEdit = isset($item) && $item->exists;
    $submitUrl = $isEdit ? route('admin.categories.update', $item->id) : route('admin.categories.store');
    $submitLabel = $isEdit ? __('Update Category') : __('Create Category');
    $previewImage = $isEdit && filled($item->image_path)
        ? asset('storage/' . $item->image_path)
        : "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='720' height='520' viewBox='0 0 720 520'%3E%3Crect width='100%25' height='100%25' fill='%23f8fafc'/%3E%3Ctext x='50%25' y='50%25' fill='%2364748b' font-size='28' text-anchor='middle' dy='.3em'%3ECategory Cover%3C/text%3E%3C/svg%3E";
    $translations = $isEdit ? $item->translations->keyBy('locale') : collect();
    $seoQuestions = $isEdit ? $item->seoQuestions->groupBy('locale') : collect();

    $keywordStringFor = function ($translation) {
        $metaKeywords = json_decode($translation?->meta_keywords ?? '[]', true);

        if (!is_array($metaKeywords)) {
            return '';
        }

        return implode(', ', array_filter(array_map(fn($keyword) => is_array($keyword) ? ($keyword['value'] ?? null) : null, $metaKeywords)));
    };

    $questionsFor = function (string $locale) use ($seoQuestions) {
        $oldQuestions = old("seo_questions.$locale");
        if (is_array($oldQuestions)) {
            return collect($oldQuestions)->values();
        }

        $storedQuestions = collect($seoQuestions->get($locale, []))
            ->map(fn($question) => ['question' => $question->question_text, 'answer' => $question->answer_text])
            ->values();

        return $storedQuestions->isNotEmpty() ? $storedQuestions : collect([['question' => '', 'answer' => '']]);
    };
@endphp

@include('includes.admin.form_theme')

@once
    @push('styles')
        <style>
            .category-shell { display: grid; gap: 1.5rem; }
            .category-topbar, .category-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 24px; box-shadow: 0 18px 38px rgba(15, 23, 42, 0.08); }
            .category-topbar { padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff; border: 0; }
            .category-topbar p { margin: 0; color: rgba(255,255,255,.72); }
            .category-grid { display: grid; grid-template-columns: 340px minmax(0,1fr); gap: 1.5rem; }
            .category-card { padding: 1.5rem; }
            .category-cover { border-radius: 20px; overflow: hidden; background: #f8fafc; border: 1px dashed #cbd5e1; min-height: 260px; }
            .category-cover img { width: 100%; height: 300px; object-fit: cover; display: block; }
            .category-metrics { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: .85rem; margin-top: 1rem; }
            .category-metric { border-radius: 18px; padding: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; }
            .category-metric span { display: block; color: #64748b; font-size: .85rem; }
            .category-metric strong { display: block; color: #0f172a; font-size: 1.05rem; margin-top: .15rem; }
            .category-nav { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1rem; }
            .category-nav .nav-link { border-radius: 999px; border: 1px solid #dbe2f0; color: #334155; font-weight: 700; }
            .category-nav .nav-link.active { background: linear-gradient(135deg, #2563eb, #0ea5e9); color: #fff; border-color: transparent; }
            .category-section { border: 1px solid #e2e8f0; border-radius: 22px; padding: 1.25rem; background: #fff; }
            .category-question { border: 1px solid #dbe2f0; border-radius: 18px; padding: 1rem; background: #f8fafc; }
            .category-question + .category-question { margin-top: .85rem; }
            .category-kicker { color: #2563eb; text-transform: uppercase; letter-spacing: .08em; font-size: .78rem; font-weight: 800; margin-bottom: .7rem; }
            @media (max-width: 991.98px) {
                .category-grid { grid-template-columns: 1fr; }
                .category-topbar { flex-direction: column; align-items: stretch; }
                .category-topbar .btn { width: 100%; }
            }
        </style>
    @endpush
@endonce

<div class="form-hero">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
        <div>
            <h2>{{ $isEdit ? __('Refine Category') : __('Create Category') }}</h2>
            <p>{{ $isEdit ? __('Update the cover, translations, and SEO setup for this category.') : __('Build a new category entry with complete multilingual content.') }}</p>
        </div>
        <div class="d-flex flex-wrap">
            <span class="hero-pill"><i class="fas fa-language"></i>{{ $activeLanguages->count() }} {{ __('Locales') }}</span>
            <span class="hero-pill"><i class="fas fa-image"></i>{{ __('Cover image') }}</span>
            <span class="hero-pill"><i class="fas fa-toggle-on"></i>{{ __('Enable or disable anytime') }}</span>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-4">
        <strong>{{ __('Please correct the following errors:') }}</strong>
        <ul class="mb-0 mt-2 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $submitUrl }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="category-shell">
        <div class="category-topbar">
            <div>
                <div class="fw-semibold">{{ $isEdit ? __('Editing category #:id', ['id' => $item->id]) : __('New category draft') }}</div>
                <p>{{ __('Slug is generated automatically from the English name. Empty SEO Q&A blocks are ignored on save.') }}</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-light">{{ __('Back to Categories') }}</a>
                <button type="submit" class="btn btn-warning text-dark fw-semibold">{{ $submitLabel }}</button>
            </div>
        </div>

        <div class="category-grid">
            <div class="category-card">
                <h5 class="mb-1">{{ __('Category Cover') }}</h5>
                <p class="text-muted mb-3">{{ __('Choose a strong representative image for the category.') }}</p>
                <div class="category-cover">
                    <img id="category-image-preview" src="{{ $previewImage }}" alt="{{ __('Category preview') }}">
                </div>
                <div class="form-group mt-3 mb-0">
                    <label for="image_path" class="font-weight-bold">{{ __('Upload Image') }}</label>
                    <input type="file" name="image_path" id="image_path" class="form-control @error('image_path') is-invalid @enderror" accept=".jpg,.jpeg,.png,.gif,.svg,.webp">
                    @error('image_path')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-grid gap-3">
                <div class="category-card">
                    <h5 class="mb-1">{{ __('Status & Metrics') }}</h5>
                    <p class="text-muted mb-3">{{ __('Disable categories without deleting them from the dashboard.') }}</p>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" {{ old('is_active', $isEdit ? $item->is_active : true) ? 'checked' : '' }}>
                        <label class="custom-control-label font-weight-bold" for="is_active">{{ __('Active on website') }}</label>
                    </div>
                    <div class="category-metrics">
                        <div class="category-metric">
                            <span>{{ __('Slug') }}</span>
                            <strong>{{ $isEdit && filled($item->slug ?? null) ? $item->slug : __('Auto-generated') }}</strong>
                        </div>
                        <div class="category-metric">
                            <span>{{ __('Assigned Cars') }}</span>
                            <strong>{{ $isEdit ? (int) ($item->cars_count ?? 0) : 0 }}</strong>
                        </div>
                        <div class="category-metric">
                            <span>{{ __('Active Cars') }}</span>
                            <strong>{{ $isEdit ? (int) ($item->active_cars_count ?? 0) : 0 }}</strong>
                        </div>
                        <div class="category-metric">
                            <span>{{ __('Updated') }}</span>
                            <strong>{{ $isEdit && $item->updated_at ? $item->updated_at->diffForHumans() : __('Not saved yet') }}</strong>
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <h5 class="mb-1">{{ __('Editorial Notes') }}</h5>
                    <p class="text-muted mb-0">{{ __('Keep names concise, descriptions useful, and metadata aligned with real search intent. Meta keywords should be separated by commas.') }}</p>
                </div>
            </div>
        </div>

        <div class="category-card">
            <h5 class="mb-1">{{ __('Localized Content') }}</h5>
            <p class="text-muted mb-3">{{ __('Complete the category copy and SEO settings for each active language.') }}</p>

            <ul class="nav nav-pills category-nav" role="tablist">
                @foreach ($activeLanguages as $lang)
                    <li class="nav-item">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="category-lang-{{ $lang->code }}-tab" data-toggle="pill" href="#category-lang-{{ $lang->code }}" role="tab">
                            {{ $lang->name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach ($activeLanguages as $lang)
                    @php
                        $translation = $translations->get($lang->code);
                        $questions = $questionsFor($lang->code);
                    @endphp
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="category-lang-{{ $lang->code }}" role="tabpanel">
                        <div class="d-grid gap-3">
                            <div class="category-section">
                                <div class="category-kicker">{{ __('Core Copy') }}</div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="name_{{ $lang->code }}">{{ __('Name') }} ({{ $lang->name }})</label>
                                            <input type="text" name="name[{{ $lang->code }}]" id="name_{{ $lang->code }}" class="form-control form-control-lg shadow-sm @error('name.' . $lang->code) is-invalid @enderror" value="{{ old('name.' . $lang->code, $translation?->name ?? '') }}">
                                            @error('name.' . $lang->code)
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="title_{{ $lang->code }}">{{ __('Section Title') }} ({{ $lang->name }})</label>
                                            <input type="text" name="title[{{ $lang->code }}]" id="title_{{ $lang->code }}" class="form-control form-control-lg shadow-sm" value="{{ old('title.' . $lang->code, $translation?->title ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="description_{{ $lang->code }}">{{ __('Description') }} ({{ $lang->name }})</label>
                                            <textarea name="description[{{ $lang->code }}]" id="description_{{ $lang->code }}" class="form-control form-control-lg shadow-sm @error('description.' . $lang->code) is-invalid @enderror" rows="4">{{ old('description.' . $lang->code, $translation?->description ?? '') }}</textarea>
                                            @error('description.' . $lang->code)
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label class="font-weight-bold" for="article_{{ $lang->code }}">{{ __('Article') }} ({{ $lang->name }})</label>
                                            <textarea name="article[{{ $lang->code }}]" id="article_{{ $lang->code }}" class="form-control form-control-lg shadow-sm" rows="8">{{ old('article.' . $lang->code, $translation?->article ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="category-section">
                                <div class="category-kicker">{{ __('SEO Signals') }}</div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="meta_title_{{ $lang->code }}">{{ __('Meta Title') }} ({{ $lang->name }})</label>
                                            <input type="text" name="meta_title[{{ $lang->code }}]" id="meta_title_{{ $lang->code }}" class="form-control form-control-lg shadow-sm" value="{{ old('meta_title.' . $lang->code, $translation?->meta_title ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="meta_keywords_{{ $lang->code }}">{{ __('Meta Keywords') }} ({{ $lang->name }})</label>
                                            <input type="text" name="meta_keywords[{{ $lang->code }}]" id="meta_keywords_{{ $lang->code }}" class="form-control form-control-lg shadow-sm" value="{{ old('meta_keywords.' . $lang->code, $keywordStringFor($translation)) }}">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label class="font-weight-bold" for="meta_description_{{ $lang->code }}">{{ __('Meta Description') }} ({{ $lang->name }})</label>
                                            <textarea name="meta_description[{{ $lang->code }}]" id="meta_description_{{ $lang->code }}" class="form-control form-control-lg shadow-sm" rows="3">{{ old('meta_description.' . $lang->code, $translation?->meta_description ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="category-section">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                    <div>
                                        <div class="category-kicker mb-1">{{ __('SEO Q&A') }}</div>
                                        <p class="text-muted mb-0">{{ __('Only rows with both question and answer will be saved.') }}</p>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary rounded-pill" data-add-question="{{ $lang->code }}">{{ __('Add Question') }}</button>
                                </div>
                                <div data-questions="{{ $lang->code }}" data-next-index="{{ $questions->count() }}">
                                    @foreach ($questions as $index => $question)
                                        <div class="category-question" data-question-item>
                                            <div class="form-group">
                                                <label class="font-weight-bold">{{ __('Question') }}</label>
                                                <input type="text" name="seo_questions[{{ $lang->code }}][{{ $index }}][question]" class="form-control shadow-sm" value="{{ $question['question'] ?? '' }}">
                                            </div>
                                            <div class="form-group mb-2">
                                                <label class="font-weight-bold">{{ __('Answer') }}</label>
                                                <textarea name="seo_questions[{{ $lang->code }}][{{ $index }}][answer]" class="form-control shadow-sm" rows="3">{{ $question['answer'] ?? '' }}</textarea>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" data-remove-question>{{ __('Remove') }}</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</form>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const imageInput = document.getElementById('image_path');
                const imagePreview = document.getElementById('category-image-preview');

                if (imageInput && imagePreview) {
                    imageInput.addEventListener('change', function (event) {
                        const file = event.target.files && event.target.files[0];
                        if (file) {
                            imagePreview.src = URL.createObjectURL(file);
                        }
                    });
                }

                document.querySelectorAll('[data-add-question]').forEach(function (button) {
                    button.addEventListener('click', function () {
                        const locale = button.getAttribute('data-add-question');
                        const container = document.querySelector('[data-questions="' + locale + '"]');
                        if (!container) {
                            return;
                        }

                        const index = Number(container.getAttribute('data-next-index') || 0);
                        const wrapper = document.createElement('div');
                        wrapper.className = 'category-question';
                        wrapper.setAttribute('data-question-item', '');
                        wrapper.innerHTML = `
                            <div class="form-group">
                                <label class="font-weight-bold">{{ __('Question') }}</label>
                                <input type="text" name="seo_questions[${locale}][${index}][question]" class="form-control shadow-sm">
                            </div>
                            <div class="form-group mb-2">
                                <label class="font-weight-bold">{{ __('Answer') }}</label>
                                <textarea name="seo_questions[${locale}][${index}][answer]" class="form-control shadow-sm" rows="3"></textarea>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" data-remove-question>{{ __('Remove') }}</button>
                        `;
                        container.appendChild(wrapper);
                        container.setAttribute('data-next-index', String(index + 1));
                    });
                });

                document.addEventListener('click', function (event) {
                    const button = event.target.closest('[data-remove-question]');
                    if (!button) {
                        return;
                    }

                    const wrapper = button.closest('[data-question-item]');
                    if (wrapper) {
                        wrapper.remove();
                    }
                });
            });
        </script>
    @endpush
@endonce
