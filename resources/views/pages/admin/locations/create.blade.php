@extends('layouts.admin_layout')

@section('title', 'Add ' . $modelName)


@section('page-title')
    {{ isset($item) ? __('Edit :entity', ['entity' => $modelName]) : __('Add :entity', ['entity' => $modelName]) }}
@endsection

@include('includes.admin.form_theme')



@section('content')

    @php
        $languageCount = isset($activeLanguages) ? $activeLanguages->count() : 0;
        $formStats = [];
        if ($languageCount) {
            $formStats[] = ['icon' => 'fas fa-language', 'label' => $languageCount . ' ' . __('Locales')];
        }
        $formStats[] = ['icon' => 'fas fa-layer-group', 'label' => __('Guided workflow')];
        $formStats[] = ['icon' => 'fas fa-save', 'label' => __('Content safety')];
        $formTitle = isset($item)
            ? __('Update :entity', ['entity' => $modelName])
            : __('Add :entity', ['entity' => $modelName]);
        $formDescription = isset($item)
            ? __('Review the content, adjust translations and assets, then save confidently.')
            : __('Complete the details below to publish a polished entry.');
    @endphp

    @include('includes.admin.form_header', [
        'title' => $formTitle,
        'description' => $formDescription,
        'stats' => $formStats
    ])






    <div class="card form-card card-primary card-outline card-tabs shadow-lg">
        <div class="card-header p-0 pt-1 border-bottom-0 bg-light">
            <!-- Tabs Header -->
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <!-- General Data Tab -->
                <li class="nav-item">
                    <a class="nav-link active text-dark" id="custom-tabs-general-tab" data-toggle="pill"
                        href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-general" aria-selected="true">
                        <i class="fas fa-info-circle"></i> General Data
                    </a>
                </li>
                <!-- Translated Data Tab -->
                <li class="nav-item">
                    <a class="nav-link text-dark" id="custom-tabs-translated-tab" data-toggle="pill"
                        href="#custom-tabs-translated" role="tab" aria-controls="custom-tabs-translated"
                        aria-selected="false">
                        <i class="fas fa-language"></i> Translated Data
                    </a>
                </li>
                <!-- SEO Data Tab -->
                <li class="nav-item">
                    <a class="nav-link text-dark" id="custom-tabs-seo-tab" data-toggle="pill" href="#custom-tabs-seo"
                        role="tab" aria-controls="custom-tabs-seo" aria-selected="false">
                        <i class="fas fa-search"></i> SEO Data
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <!-- Form -->
            <form action="{{ route('admin.' . $modelName . '.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="tab-content" id="custom-tabs-three-tabContent">
                    <!-- General Data Tab Content -->
                    <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel"
                        aria-labelledby="custom-tabs-general-tab">

                        <div class="form-group">
                            <label for="is_active" class="font-weight-bold">Active</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" {{ old('is_active')}}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>

                    <!-- Translated Data Tab Content with Sub-tabs for Languages -->
                    <div class="tab-pane fade" id="custom-tabs-translated" role="tabpanel"
                        aria-labelledby="custom-tabs-translated-tab">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            @foreach($activeLanguages as $lang)
                                <li class="nav-item">
                                    <a class="nav-link @if(session('active_language') == $lang->code || (session('active_language') == null && $loop->first)) active @endif bg-light text-dark"
                                        id="pills-{{ $lang->code }}-tab" data-toggle="pill" href="#pills-{{ $lang->code }}"
                                        role="tab" aria-controls="pills-{{ $lang->code }}"
                                        aria-selected="true">{{ $lang->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content shadow-sm p-3 mb-4 bg-white rounded" id="pills-tabContent">
                            @foreach($activeLanguages as $lang)
                                <div class="tab-pane fade @if(session('active_language') == $lang->code || (session('active_language') == null && $loop->first)) show active @endif"
                                    id="pills-{{ $lang->code }}" role="tabpanel" aria-labelledby="pills-{{ $lang->code }}-tab">
                                    <div class="form-group">
                                        <label for="name_{{ $lang->code }}" class="font-weight-bold">Name
                                            ({{ $lang->name }})</label>
                                        <input type="text" name="name[{{ $lang->code }}]"
                                            class="form-control form-control-lg shadow-sm" id="name_{{ $lang->code }}"
                                            value="{{ old('name.' . $lang->code) }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="title_{{ $lang->code }}" class="font-weight-bold">Section Title
                                            ({{ $lang->name }})</label>
                                        <input type="text" name="title[{{ $lang->code }}]"
                                            class="form-control form-control-lg shadow-sm" id="title_{{ $lang->code }}"
                                            value="{{ old('title.' . $lang->code) }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="description_{{ $lang->code }}" class="font-weight-bold">Description
                                            ({{ $lang->name }})</label>
                                        <textarea name="description[{{ $lang->code }}]"
                                            class="form-control form-control-lg shadow-sm" id="description_{{ $lang->code }}"
                                            rows="4">{{ old('description.' . $lang->code) }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="article_{{ $lang->code }}" class="font-weight-bold">Article
                                            ({{ $lang->name }})</label>
                                        <textarea name="article[{{ $lang->code }}]"
                                            class="form-control form-control-lg shadow-sm tinymce"
                                            id="article_{{ $lang->code }}" rows="5">{{ old('article.' . $lang->code) }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- SEO Data Tab Content -->
                    <div class="tab-pane fade" id="custom-tabs-seo" role="tabpanel" aria-labelledby="custom-tabs-seo-tab">
                        <ul class="nav nav-pills mb-3" id="pills-seo-tab" role="tablist">
                            @foreach($activeLanguages as $lang)
                                <li class="nav-item">
                                    <a class="nav-link @if(session('active_language') == $lang->code || (session('active_language') == null && $loop->first)) active @endif bg-light text-dark"
                                        id="pills-seo-{{ $lang->code }}-tab" data-toggle="pill"
                                        href="#pills-seo-{{ $lang->code }}" role="tab"
                                        aria-controls="pills-seo-{{ $lang->code }}" aria-selected="true">{{ $lang->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content shadow-sm p-3 mb-4 bg-white rounded" id="pills-seo-tabContent">
                            @foreach($activeLanguages as $lang)
                                <div class="tab-pane fade @if(session('active_language') == $lang->code || (session('active_language') == null && $loop->first)) show active @endif"
                                    id="pills-seo-{{ $lang->code }}" role="tabpanel"
                                    aria-labelledby="pills-seo-{{ $lang->code }}-tab">
                                    <div class="form-group">
                                        <label for="meta_title_{{ $lang->code }}" class="font-weight-bold">Meta Title
                                            ({{ $lang->name }})</label>
                                        <input type="text" name="meta_title[{{ $lang->code }}]"
                                            class="form-control form-control-lg shadow-sm" id="meta_title_{{ $lang->code }}"
                                            value="{{ old('meta_title.' . $lang->code) }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_description_{{ $lang->code }}" class="font-weight-bold">Meta
                                            Description ({{ $lang->name }})</label>
                                        <textarea name="meta_description[{{ $lang->code }}]"
                                            class="form-control form-control-lg shadow-sm "
                                            id="meta_description_{{ $lang->code }}"
                                            rows="3">{{ old('meta_description.' . $lang->code) }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_keywords_{{ $lang->code }}" class="font-weight-bold">Meta Keywords
                                            ({{ $lang->name }})</label>
                                        <input type="text" name="meta_keywords[{{ $lang->code }}]"
                                            class="form-control form-control-lg shadow-sm" id="meta_keywords_{{ $lang->code }}"
                                            data-role="tagsinput" value="{{ old('meta_keywords.' . $lang->code) }}">
                                    </div>

                                    <!-- Dynamic SEO Questions/Answers Section -->
                                    <div class="seo-questions-container" id="seo-questions-{{ $lang->code }}">
                                        <label class="font-weight-bold">SEO Questions/Answers ({{ $lang->name }})</label>
                                        <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                                            <div class="form-group">
                                                <input type="text" name="seo_questions[{{ $lang->code }}][0][question]"
                                                    class="form-control form-control-lg shadow-sm mb-2"
                                                    placeholder="Enter Question" />
                                            </div>
                                            <div class="form-group">
                                                <textarea name="seo_questions[{{ $lang->code }}][0][answer]"
                                                    class="form-control form-control-lg shadow-sm"
                                                    placeholder="Enter Answer"></textarea>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-info add-question mt-3" data-lang="{{ $lang->code }}">
                                        <i class="fas fa-plus"></i> Add Question
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success btn-lg mt-3">
                    <i class="fas fa-save"></i> Save
                </button>
            </form>
        </div>
</div>@endsection


@push('scripts')

    <!-- Custom JS -->
    <script>
        $(document).ready(function () {
            // Function to dynamically add SEO Questions/Answers
            $('.add-question').on('click', function () {
                var lang = $(this).data('lang');
                var container = $('#seo-questions-' + lang);
                var count = container.find('.seo-question-group').length;
                console.log('Adding question for language:', lang, 'Count:', count); // Debugging line
                var newQuestionGroup = `
                        <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                            <div class="form-group">
                                <input type="text" name="seo_questions[` + lang + `][` + count + `][question]" class="form-control form-control-lg shadow-sm mb-2" placeholder="Enter Question" />
                            </div>
                            <div class="form-group">
                                <textarea name="seo_questions[` + lang + `][` + count + `][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer"></textarea>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                        </div>`;
                container.append(newQuestionGroup);
            });

            // Function to remove an SEO Question/Answer
            $(document).on('click', '.remove-question', function () {
                $(this).closest('.seo-question-group').remove();
            });


            @foreach($activeLanguages as $lang)
                var metaKeywordsInput = document.querySelector('#meta_keywords_{{ $lang->code }}');
                if (metaKeywordsInput) {
                    new Tagify(metaKeywordsInput, {
                        placeholder: 'Enter meta keywords'
                    });
                }
            @endforeach
            });
    </script>
@endpush