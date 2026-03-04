@php
    $seoQuestions = $item->seoQuestions->where('locale', $lang->code)->values();
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

    $sections = [
        [
            'title' => 'Hero Section',
            'description' => 'Banner copy, CTA labels, and hero pricing text.',
            'fields' => [
                ['name' => 'hero_title_prefix', 'label' => 'Title Prefix'],
                ['name' => 'hero_title_highlight', 'label' => 'Title Highlight'],
                ['name' => 'hero_title_suffix', 'label' => 'Title Suffix'],
                ['name' => 'hero_banner_paragraph', 'label' => 'Banner Paragraph', 'type' => 'textarea', 'width' => 'col-12', 'rows' => 4],
                ['name' => 'hero_customers_label', 'label' => 'Customers Label'],
                ['name' => 'hero_customers_subtitle', 'label' => 'Customers Subtitle'],
                ['name' => 'hero_browse_cars_label', 'label' => 'Browse Cars Button'],
                ['name' => 'hero_browse_blogs_label', 'label' => 'Browse Blogs Button'],
                ['name' => 'hero_starting_from_label', 'label' => 'Starting From Label'],
                ['name' => 'hero_per_day_label', 'label' => 'Per Day Label'],
                ['name' => 'hero_available_for_rent_label', 'label' => 'Available For Rent Label'],
            ],
        ],
        [
            'title' => 'Feature Section',
            'description' => 'Headline and the six feature cards.',
            'fields' => [
                ['name' => 'feature_section_title', 'label' => 'Section Title'],
                ['name' => 'feature_section_paragraph', 'label' => 'Section Paragraph', 'type' => 'textarea', 'width' => 'col-12', 'rows' => 3],
                ['name' => 'feature_item_1_title', 'label' => 'Feature 1 Title'],
                ['name' => 'feature_item_1_description', 'label' => 'Feature 1 Description', 'type' => 'textarea', 'rows' => 3],
                ['name' => 'feature_item_2_title', 'label' => 'Feature 2 Title'],
                ['name' => 'feature_item_2_description', 'label' => 'Feature 2 Description', 'type' => 'textarea', 'rows' => 3],
                ['name' => 'feature_item_3_title', 'label' => 'Feature 3 Title'],
                ['name' => 'feature_item_3_description', 'label' => 'Feature 3 Description', 'type' => 'textarea', 'rows' => 3],
                ['name' => 'feature_item_4_title', 'label' => 'Feature 4 Title'],
                ['name' => 'feature_item_4_description', 'label' => 'Feature 4 Description', 'type' => 'textarea', 'rows' => 3],
                ['name' => 'feature_item_5_title', 'label' => 'Feature 5 Title'],
                ['name' => 'feature_item_5_description', 'label' => 'Feature 5 Description', 'type' => 'textarea', 'rows' => 3],
                ['name' => 'feature_item_6_title', 'label' => 'Feature 6 Title'],
                ['name' => 'feature_item_6_description', 'label' => 'Feature 6 Description', 'type' => 'textarea', 'rows' => 3],
            ],
        ],
        [
            'title' => 'Rental Section',
            'description' => 'Rental steps plus stat counters. Keep values numeric for the counter animation.',
            'fields' => [
                ['name' => 'rental_section_title', 'label' => 'Section Title'],
                ['name' => 'rental_section_paragraph', 'label' => 'Section Paragraph', 'type' => 'textarea', 'width' => 'col-12', 'rows' => 3],
                ['name' => 'rental_step_1_title', 'label' => 'Step 1 Title'],
                ['name' => 'rental_step_1_description', 'label' => 'Step 1 Description', 'type' => 'textarea', 'rows' => 3],
                ['name' => 'rental_step_2_title', 'label' => 'Step 2 Title'],
                ['name' => 'rental_step_2_description', 'label' => 'Step 2 Description', 'type' => 'textarea', 'rows' => 3],
                ['name' => 'rental_step_3_title', 'label' => 'Step 3 Title'],
                ['name' => 'rental_step_3_description', 'label' => 'Step 3 Description', 'type' => 'textarea', 'rows' => 3],
                ['name' => 'rental_stat_1_value', 'label' => 'Stat 1 Value'],
                ['name' => 'rental_stat_1_suffix', 'label' => 'Stat 1 Suffix'],
                ['name' => 'rental_stat_1_label', 'label' => 'Stat 1 Label'],
                ['name' => 'rental_stat_2_value', 'label' => 'Stat 2 Value'],
                ['name' => 'rental_stat_2_suffix', 'label' => 'Stat 2 Suffix'],
                ['name' => 'rental_stat_2_label', 'label' => 'Stat 2 Label'],
                ['name' => 'rental_stat_3_value', 'label' => 'Stat 3 Value'],
                ['name' => 'rental_stat_3_suffix', 'label' => 'Stat 3 Suffix'],
                ['name' => 'rental_stat_3_label', 'label' => 'Stat 3 Label'],
                ['name' => 'rental_stat_4_value', 'label' => 'Stat 4 Value'],
                ['name' => 'rental_stat_4_suffix', 'label' => 'Stat 4 Suffix'],
                ['name' => 'rental_stat_4_label', 'label' => 'Stat 4 Label'],
            ],
        ],
    ];

    $sections[] = [
        'title' => 'Testimonials',
        'description' => 'Three testimonial cards and the CTA button.',
        'fields' => [
            ['name' => 'testimonial_section_title', 'label' => 'Section Title'],
            ['name' => 'testimonial_section_paragraph', 'label' => 'Section Paragraph', 'type' => 'textarea', 'width' => 'col-12', 'rows' => 3],
            ['name' => 'testimonial_review_1', 'label' => 'Review 1', 'type' => 'textarea', 'rows' => 4],
            ['name' => 'testimonial_client_1_name', 'label' => 'Client 1 Name'],
            ['name' => 'testimonial_client_1_location', 'label' => 'Client 1 Location'],
            ['name' => 'testimonial_review_2', 'label' => 'Review 2', 'type' => 'textarea', 'rows' => 4],
            ['name' => 'testimonial_client_2_name', 'label' => 'Client 2 Name'],
            ['name' => 'testimonial_client_2_location', 'label' => 'Client 2 Location'],
            ['name' => 'testimonial_review_3', 'label' => 'Review 3', 'type' => 'textarea', 'rows' => 4],
            ['name' => 'testimonial_client_3_name', 'label' => 'Client 3 Name'],
            ['name' => 'testimonial_client_3_location', 'label' => 'Client 3 Location'],
            ['name' => 'testimonial_cta_label', 'label' => 'CTA Button Label'],
        ],
    ];

    $sections[] = [
        'title' => 'Support Ticker',
        'description' => 'Five short labels used in the horizontal support marquee.',
        'fields' => [
            ['name' => 'support_item_1_text', 'label' => 'Support Item 1'],
            ['name' => 'support_item_2_text', 'label' => 'Support Item 2'],
            ['name' => 'support_item_3_text', 'label' => 'Support Item 3'],
            ['name' => 'support_item_4_text', 'label' => 'Support Item 4'],
            ['name' => 'support_item_5_text', 'label' => 'Support Item 5'],
        ],
    ];

    $sections[] = [
        'title' => 'Homepage Section Headings',
        'description' => 'Titles and descriptions already used for categories, cars, brands, blogs, and FAQ.',
        'fields' => [
            ['name' => 'category_section_title', 'label' => 'Category Section Title'],
            ['name' => 'category_section_paragraph', 'label' => 'Category Section Paragraph', 'type' => 'textarea', 'rows' => 3],
            ['name' => 'featured_cars_section_title', 'label' => 'Featured Cars Section Title'],
            ['name' => 'featured_cars_section_paragraph', 'label' => 'Featured Cars Section Paragraph', 'type' => 'textarea', 'rows' => 3],
            ['name' => 'brand_section_title', 'label' => 'Brand Section Title'],
            ['name' => 'brand_section_paragraph', 'label' => 'Brand Section Paragraph', 'type' => 'textarea', 'rows' => 3],
            ['name' => 'car_only_section_title', 'label' => 'Only On Section Title'],
            ['name' => 'car_only_section_paragraph', 'label' => 'Only On Section Paragraph', 'type' => 'textarea', 'rows' => 3],
            ['name' => 'blog_section_title', 'label' => 'Blog Section Title'],
            ['name' => 'blog_section_paragraph', 'label' => 'Blog Section Paragraph', 'type' => 'textarea', 'rows' => 3],
            ['name' => 'faq_section_title', 'label' => 'FAQ Section Title'],
            ['name' => 'faq_section_paragraph', 'label' => 'FAQ Section Paragraph', 'type' => 'textarea', 'rows' => 3],
        ],
    ];

    $sections[] = [
        'title' => 'Shared CMS Content',
        'description' => 'Legacy and shared copy used by API responses and other frontend screens.',
        'fields' => [
            ['name' => 'hero_header_title', 'label' => 'Legacy Hero Header Title'],
            ['name' => 'contact_us_title', 'label' => 'Contact Us Title'],
            ['name' => 'contact_us_paragraph', 'label' => 'Contact Us Paragraph', 'type' => 'textarea', 'rows' => 3],
            ['name' => 'contact_us_detail_title', 'label' => 'Contact Detail Title'],
            ['name' => 'contact_us_detail_paragraph', 'label' => 'Contact Detail Paragraph', 'type' => 'textarea', 'rows' => 3],
            ['name' => 'special_offers_section_title', 'label' => 'Special Offers Section Title'],
            ['name' => 'special_offers_section_paragraph', 'label' => 'Special Offers Section Paragraph', 'type' => 'textarea', 'rows' => 3],
            ['name' => 'why_choose_us_section_title', 'label' => 'Why Choose Us Section Title'],
            ['name' => 'why_choose_us_section_paragraph', 'label' => 'Why Choose Us Section Paragraph', 'type' => 'textarea', 'rows' => 3],
            ['name' => 'where_find_us_section_title', 'label' => 'Where Find Us Section Title'],
            ['name' => 'where_find_us_section_paragraph', 'label' => 'Where Find Us Section Paragraph', 'type' => 'textarea', 'rows' => 3],
            ['name' => 'required_documents_section_title', 'label' => 'Required Documents Section Title'],
            ['name' => 'required_documents_section_paragraph', 'label' => 'Required Documents Section Paragraph', 'type' => 'textarea', 'rows' => 3],
            ['name' => 'instagram_section_title', 'label' => 'Instagram Section Title'],
            ['name' => 'instagram_section_paragraph', 'label' => 'Instagram Section Paragraph', 'type' => 'textarea', 'rows' => 3],
            ['name' => 'footer_section_paragraph', 'label' => 'Footer Section Paragraph', 'type' => 'textarea', 'width' => 'col-12', 'rows' => 4],
        ],
    ];
@endphp

<div class="border rounded p-4 mb-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-4">
        <div>
            <h5 class="mb-1">{{ $lang->name }}</h5>
            <p class="text-muted mb-0">Homepage text for the `{{ $lang->code }}` locale.</p>
        </div>
        <span class="badge badge-light border text-dark">{{ strtoupper($lang->code) }}</span>
    </div>

    @foreach ($sections as $section)
        <div class="card border shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-1">{{ $section['title'] }}</h6>
                <p class="text-muted mb-0">{{ $section['description'] }}</p>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($section['fields'] as $field)
                        @php
                            $fieldName = $field['name'];
                            $fieldValue = old($fieldName . '.' . $lang->code, data_get($translation, $fieldName, ''));
                            $fieldType = $field['type'] ?? 'text';
                            $fieldWidth = $field['width'] ?? 'col-md-6';
                            $fieldRows = $field['rows'] ?? 3;
                        @endphp

                        <div class="{{ $fieldWidth }} mb-3">
                            <label for="{{ $fieldName }}_{{ $lang->code }}" class="font-weight-bold">{{ $field['label'] }}</label>
                            @if ($fieldType === 'textarea')
                                <textarea
                                    name="{{ $fieldName }}[{{ $lang->code }}]"
                                    id="{{ $fieldName }}_{{ $lang->code }}"
                                    class="form-control @error($fieldName . '.' . $lang->code) is-invalid @enderror"
                                    rows="{{ $fieldRows }}"
                                >{{ $fieldValue }}</textarea>
                            @else
                                <input
                                    type="text"
                                    name="{{ $fieldName }}[{{ $lang->code }}]"
                                    id="{{ $fieldName }}_{{ $lang->code }}"
                                    class="form-control @error($fieldName . '.' . $lang->code) is-invalid @enderror"
                                    value="{{ $fieldValue }}"
                                >
                            @endif
                            @error($fieldName . '.' . $lang->code)
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <div class="card border shadow-sm mb-0">
        <div class="card-header bg-white">
            <h6 class="mb-1">SEO & Metadata</h6>
            <p class="text-muted mb-0">Meta fields and FAQ-style SEO content for the homepage.</p>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="meta_title_{{ $lang->code }}" class="font-weight-bold">Meta Title</label>
                <input
                    type="text"
                    name="meta_title[{{ $lang->code }}]"
                    id="meta_title_{{ $lang->code }}"
                    class="form-control @error('meta_title.' . $lang->code) is-invalid @enderror"
                    value="{{ old('meta_title.' . $lang->code, $translation?->meta_title ?? '') }}"
                >
                @error('meta_title.' . $lang->code)
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="meta_description_{{ $lang->code }}" class="font-weight-bold">Meta Description</label>
                <textarea
                    name="meta_description[{{ $lang->code }}]"
                    id="meta_description_{{ $lang->code }}"
                    class="form-control @error('meta_description.' . $lang->code) is-invalid @enderror"
                    rows="3"
                >{{ old('meta_description.' . $lang->code, $translation?->meta_description ?? '') }}</textarea>
                @error('meta_description.' . $lang->code)
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="meta_keywords_{{ $lang->code }}" class="font-weight-bold">Meta Keywords</label>
                <input
                    type="text"
                    name="meta_keywords[{{ $lang->code }}]"
                    id="meta_keywords_{{ $lang->code }}"
                    class="form-control @error('meta_keywords.' . $lang->code) is-invalid @enderror"
                    value="{{ old('meta_keywords.' . $lang->code, $metaKeywordsValue) }}"
                    placeholder="keyword one, keyword two"
                >
                @error('meta_keywords.' . $lang->code)
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="border rounded p-3 h-100">
                        <div class="form-check">
                            <input
                                type="checkbox"
                                name="robots_index[{{ $lang->code }}]"
                                class="form-check-input"
                                id="robots_index_{{ $lang->code }}"
                                value="index"
                                @checked(old('robots_index.' . $lang->code, $translation?->robots_index ?? '') === 'index')
                            >
                            <label class="form-check-label font-weight-bold" for="robots_index_{{ $lang->code }}">Allow indexing</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="border rounded p-3 h-100">
                        <div class="form-check">
                            <input
                                type="checkbox"
                                name="robots_follow[{{ $lang->code }}]"
                                class="form-check-input"
                                id="robots_follow_{{ $lang->code }}"
                                value="follow"
                                @checked(old('robots_follow.' . $lang->code, $translation?->robots_follow ?? '') === 'follow')
                            >
                            <label class="form-check-label font-weight-bold" for="robots_follow_{{ $lang->code }}">Allow link follow</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
                <div>
                    <h6 class="mb-1">SEO Questions</h6>
                    <p class="text-muted mb-0">Optional structured content for search engines.</p>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary add-seo-question" data-lang="{{ $lang->code }}">Add Question</button>
            </div>

            <div class="seo-questions-container" id="seo-questions-{{ $lang->code }}">
                @forelse ($seoQuestions as $index => $seoQuestion)
                    <div class="seo-question-group border rounded p-3 mb-3">
                        <div class="form-group">
                            <label class="font-weight-bold">Question</label>
                            <input
                                type="text"
                                name="seo_questions[{{ $lang->code }}][{{ $index }}][question]"
                                class="form-control"
                                value="{{ old('seo_questions.' . $lang->code . '.' . $index . '.question', $seoQuestion->question_text) }}"
                            >
                        </div>
                        <div class="form-group mb-2">
                            <label class="font-weight-bold">Answer</label>
                            <textarea
                                name="seo_questions[{{ $lang->code }}][{{ $index }}][answer]"
                                class="form-control"
                                rows="3"
                            >{{ old('seo_questions.' . $lang->code . '.' . $index . '.answer', $seoQuestion->answer_text) }}</textarea>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-seo-question">Remove</button>
                    </div>
                @empty
                    <div class="seo-question-group border rounded p-3 mb-3">
                        <div class="form-group">
                            <label class="font-weight-bold">Question</label>
                            <input
                                type="text"
                                name="seo_questions[{{ $lang->code }}][0][question]"
                                class="form-control"
                                value="{{ old('seo_questions.' . $lang->code . '.0.question') }}"
                            >
                        </div>
                        <div class="form-group mb-2">
                            <label class="font-weight-bold">Answer</label>
                            <textarea
                                name="seo_questions[{{ $lang->code }}][0][answer]"
                                class="form-control"
                                rows="3"
                            >{{ old('seo_questions.' . $lang->code . '.0.answer') }}</textarea>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-seo-question">Remove</button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
