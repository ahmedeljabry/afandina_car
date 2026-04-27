<?php

namespace App\Http\Controllers\admin;

use App\Models\Home;
use App\Models\Language;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class HomeController extends GenericController
{
    private const HOME_LOCALES = ['en', 'ar', 'ru'];

    public function __construct()
    {
        parent::__construct('home');
        $this->data['activeLanguages'] = $this->homeLanguages();

        $this->translatableFields = [
            'hero_title_prefix',
            'hero_title_highlight',
            'hero_title_suffix',
            'hero_banner_paragraph',
            'hero_customers_label',
            'hero_customers_subtitle',
            'hero_browse_cars_label',
            'hero_browse_blogs_label',
            'hero_starting_from_label',
            'hero_per_day_label',
            'hero_available_for_rent_label',
            'feature_section_title',
            'feature_section_paragraph',
            'feature_item_1_title',
            'feature_item_1_description',
            'feature_item_2_title',
            'feature_item_2_description',
            'feature_item_3_title',
            'feature_item_3_description',
            'feature_item_4_title',
            'feature_item_4_description',
            'feature_item_5_title',
            'feature_item_5_description',
            'feature_item_6_title',
            'feature_item_6_description',
            'rental_section_title',
            'rental_section_paragraph',
            'rental_step_1_title',
            'rental_step_1_description',
            'rental_step_2_title',
            'rental_step_2_description',
            'rental_step_3_title',
            'rental_step_3_description',
            'rental_stat_1_value',
            'rental_stat_1_suffix',
            'rental_stat_1_label',
            'rental_stat_2_value',
            'rental_stat_2_suffix',
            'rental_stat_2_label',
            'rental_stat_3_value',
            'rental_stat_3_suffix',
            'rental_stat_3_label',
            'rental_stat_4_value',
            'rental_stat_4_suffix',
            'rental_stat_4_label',
            'hero_header_title',
            'featured_cars_section_title',
            'featured_cars_section_paragraph',
            'car_only_section_title',
            'car_only_section_paragraph',
            'category_section_title',
            'category_section_paragraph',
            'brand_section_title',
            'brand_section_paragraph',
            'blog_section_title',
            'blog_section_paragraph',
            'testimonial_section_title',
            'testimonial_section_paragraph',
            'testimonial_review_1',
            'testimonial_client_1_name',
            'testimonial_client_1_location',
            'testimonial_review_2',
            'testimonial_client_2_name',
            'testimonial_client_2_location',
            'testimonial_review_3',
            'testimonial_client_3_name',
            'testimonial_client_3_location',
            'testimonial_cta_label',
            'contact_us_title',
            'contact_us_paragraph',
            'contact_us_detail_title',
            'contact_us_detail_paragraph',
            'special_offers_section_title',
            'special_offers_section_paragraph',
            'why_choose_us_section_title',
            'why_choose_us_section_paragraph',
            'faq_section_title',
            'faq_section_paragraph',
            'mileage_policy',
            'fuel_policy',
            'deposit_policy',
            'rental_policy',
            'where_find_us_section_title',
            'where_find_us_section_paragraph',
            'required_documents_section_title',
            'required_documents_section_paragraph',
            'footer_section_paragraph',
            'instagram_section_title',
            'instagram_section_paragraph',
            'support_item_1_text',
            'support_item_2_text',
            'support_item_3_text',
            'support_item_4_text',
            'support_item_5_text',
        ];
        $this->nonTranslatableFields = [
            'page_name',
            'is_active',
            'hero_type',
            'rental_section_image_path',
            'rental_section_grid_image_path',
        ];
    }

    public function edit($id): View
    {
        $this->data['item'] = Home::query()
            ->with(['translations', 'seoQuestions'])
            ->findOrFail($id);

        $this->data['homeLocales'] = $this->buildEditorLocales();
        $this->data['translationsByLocale'] = collect($this->data['homeLocales'])
            ->mapWithKeys(function (array $locale): array {
                return [
                    $locale['code'] => $this->data['item']->translations->firstWhere('locale', $locale['code']),
                ];
            })
            ->all();
        $this->data['prefillValues'] = $this->buildPrefillValues();
        $this->data['editorSections'] = $this->buildEditorSections();
        $this->data['editorTabs'] = $this->buildEditorTabs();
        $this->data['clientSliderItems'] = $this->data['item']->clientSliderItemsForEditor();

        return view('pages.admin.homes.edit', $this->data);
    }

    public function store(Request $request)
    {
        $this->syncActiveFlag($request);
        $this->normalizeMetaKeywords($request);
        $validatedData = $request->validate($this->buildValidationRules());

        DB::beginTransaction();

        try {
            $row = $this->model::create($this->extractBaseData($validatedData));

            $this->handleModelTranslations($validatedData, $row);
            $this->replaceHomeMedia($request, $row);
            $this->replaceClientSliderItems($request, $row);
            $this->handleStoreSEOQuestions($validatedData, $row);

            DB::commit();

            return redirect()
                ->route('admin.' . $this->modelName . '.edit', $row->id)
                ->with('success', 'Home Page created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Error occurred while creating Home Page: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $this->syncActiveFlag($request);
        $this->normalizeMetaKeywords($request);
        $validatedData = $request->validate($this->buildValidationRules());

        DB::beginTransaction();

        try {
            $row = Home::query()->findOrFail($id);

            foreach ($this->extractBaseData($validatedData) as $field => $value) {
                $row->{$field} = $value;
            }
            $row->save();

            $this->handleModelTranslations($validatedData, $row, $id);
            $this->replaceHomeMedia($request, $row);
            $this->replaceClientSliderItems($request, $row);
            $this->handleUpdateSEOQuestions($validatedData, $row);

            DB::commit();

            return back()->with('success', 'Home Page updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Error occurred while updating Home Page: ' . $e->getMessage())
                ->withInput();
        }
    }

    protected function extractBaseData(array $validatedData): array
    {
        return [
            'page_name' => $validatedData['page_name'] ?? 'home',
            'is_active' => $validatedData['is_active'] ?? false,
            'hero_type' => $validatedData['hero_type'] ?? 'image',
        ];
    }

    private function syncActiveFlag(Request $request): void
    {
        $request->merge([
            'is_active' => $request->boolean('is_active'),
        ]);
    }

    private function buildValidationRules(): array
    {
        $rules = [
            'page_name' => 'required|string|max:255',
            'hero_type' => 'required|in:video,image',
            'hero_header_video_path' => 'nullable|mimes:mp4,webm,ogg,mov|max:102400',
            'hero_header_image_path' => 'nullable|mimes:jpg,jpeg,png,svg,webp|max:10240',
            'rental_section_image_path' => 'nullable|mimes:jpg,jpeg,png,svg,webp|max:10240',
            'rental_section_grid_image_path' => 'nullable|mimes:jpg,jpeg,png,svg,webp|max:10240',
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'seo_questions.*.*.question' => 'nullable|string',
            'seo_questions.*.*.answer' => 'nullable|string',
            'robots_index.*' => 'nullable',
            'robots_follow.*' => 'nullable',
            'client_slider_items' => 'nullable|array',
            'client_slider_items.*.path' => 'nullable|string|max:2048',
            'client_slider_items.*.alt' => 'nullable|string|max:255',
            'client_slider_items.*.url' => 'nullable|url|max:2048',
            'client_slider_items.*.is_active' => 'nullable',
            'client_slider_items.*.remove_image' => 'nullable',
            'client_slider_images' => 'nullable|array',
            'client_slider_images.*' => 'nullable|mimes:jpg,jpeg,png,svg,webp|max:10240',
            'is_active' => 'boolean',
        ];

        foreach ($this->translatableFields as $field) {
            $rules[$field . '.*'] = 'nullable|string';
        }

        foreach ([
            'rental_stat_1_value',
            'rental_stat_2_value',
            'rental_stat_3_value',
            'rental_stat_4_value',
        ] as $field) {
            $rules[$field . '.*'] = 'nullable|string|max:50';
        }

        return $rules;
    }

    protected function handleModelTranslations($validatedData, $model, $id = null)
    {
        foreach ($this->data['activeLanguages'] as $language) {
            $langCode = $language->code;

            $translationData = [];
            foreach ($this->translatableFields as $field) {
                if (isset($validatedData[$field][$langCode])) {
                    $translationData[$field] = $validatedData[$field][$langCode];
                }
            }

            $translationData['locale'] = $langCode;
            $translationData['meta_title'] = $validatedData['meta_title'][$langCode] ?? null;
            $translationData['meta_description'] = $validatedData['meta_description'][$langCode] ?? null;
            $translationData['meta_keywords'] = $validatedData['meta_keywords'][$langCode] ?? null;
            $translationData['robots_index'] = isset($validatedData['robots_index'][$langCode]) ? 'index' : 'noindex';
            $translationData['robots_follow'] = isset($validatedData['robots_follow'][$langCode]) ? 'follow' : 'nofollow';

            $model->translations()->updateOrCreate(
                ['locale' => $langCode],
                $translationData
            );
        }
    }

    private function buildEditorLocales(): array
    {
        return collect($this->data['activeLanguages'] ?? [])
            ->map(fn (Language $language): array => [
                'code' => $language->code,
                'name' => $language->name,
            ])
            ->values()
            ->all();
    }

    private function buildPrefillValues(): array
    {
        $translationFallbackKeys = [
            'hero_title_prefix' => 'website.home.hero.title_prefix',
            'hero_title_highlight' => 'website.home.hero.title_highlight',
            'hero_title_suffix' => 'website.home.hero.title_suffix',
            'hero_banner_paragraph' => 'website.home.hero.banner_paragraph',
            'hero_customers_label' => 'website.home.hero.customers_label',
            'hero_customers_subtitle' => 'website.home.hero.customers_subtitle',
            'hero_browse_cars_label' => 'website.home.hero.browse_cars',
            'hero_browse_blogs_label' => 'website.home.hero.browse_blogs',
            'hero_starting_from_label' => 'website.home.hero.starting_from',
            'hero_per_day_label' => 'website.home.hero.per_day',
            'hero_available_for_rent_label' => 'website.home.hero.available_for_rent',
            'feature_section_title' => 'website.home.features.section_title',
            'feature_section_paragraph' => 'website.home.features.section_paragraph',
            'feature_item_1_title' => 'website.home.features.best_deal.title',
            'feature_item_1_description' => 'website.home.features.best_deal.description',
            'feature_item_2_title' => 'website.home.features.doorstep_delivery.title',
            'feature_item_2_description' => 'website.home.features.doorstep_delivery.description',
            'feature_item_3_title' => 'website.home.features.low_security_deposit.title',
            'feature_item_3_description' => 'website.home.features.low_security_deposit.description',
            'feature_item_4_title' => 'website.home.features.latest_cars.title',
            'feature_item_4_description' => 'website.home.features.latest_cars.description',
            'feature_item_5_title' => 'website.home.features.customer_support.title',
            'feature_item_5_description' => 'website.home.features.customer_support.description',
            'feature_item_6_title' => 'website.home.features.no_hidden_charges.title',
            'feature_item_6_description' => 'website.home.features.no_hidden_charges.description',
            'rental_section_title' => 'website.home.rental.title',
            'rental_section_paragraph' => 'website.home.rental.paragraph',
            'rental_step_1_title' => 'website.home.rental.step1_title',
            'rental_step_1_description' => 'website.home.rental.step1_description',
            'rental_step_2_title' => 'website.home.rental.step2_title',
            'rental_step_2_description' => 'website.home.rental.step2_description',
            'rental_step_3_title' => 'website.home.rental.step3_title',
            'rental_step_3_description' => 'website.home.rental.step3_description',
            'rental_stat_1_label' => 'website.home.stats.happy_customers',
            'rental_stat_2_label' => 'website.home.stats.count_of_cars',
            'rental_stat_3_label' => 'website.home.stats.locations_to_pickup',
            'rental_stat_4_label' => 'website.home.stats.total_kilometers',
            'support_item_1_text' => 'website.home.support.best_rate',
            'support_item_2_text' => 'website.home.support.free_cancellation',
            'support_item_3_text' => 'website.home.support.best_security',
            'support_item_4_text' => 'website.home.support.latest_update',
            'support_item_5_text' => 'website.home.support.trusted_proof',
        ];

        $prefillValues = [];
        $localeCodes = collect($this->data['activeLanguages'] ?? [])
            ->pluck('code')
            ->filter()
            ->unique()
            ->values();

        foreach ($translationFallbackKeys as $fieldName => $key) {
            $prefillValues[$fieldName] = $localeCodes
                ->mapWithKeys(function (string $localeCode) use ($key): array {
                    $value = trans($key, [], $localeCode);

                    if ($value === $key) {
                        $value = trans($key, [], 'en');
                    }

                    return [$localeCode => $value === $key ? '' : $value];
                })
                ->all();
        }

        foreach ([
            'rental_stat_1_value' => '16',
            'rental_stat_1_suffix' => 'K+',
            'rental_stat_2_value' => '2547',
            'rental_stat_2_suffix' => 'K+',
            'rental_stat_3_value' => '625',
            'rental_stat_3_suffix' => 'K+',
            'rental_stat_4_value' => '15000',
            'rental_stat_4_suffix' => 'K+',
        ] as $fieldName => $value) {
            $prefillValues[$fieldName] = $localeCodes
                ->mapWithKeys(fn (string $localeCode): array => [$localeCode => $value])
                ->all();
        }

        return $prefillValues;
    }

    private function buildEditorTabs(): array
    {
        return [
            ['id' => 'overview', 'label' => 'Overview', 'icon' => 'fas fa-house'],
            ['id' => 'hero', 'label' => 'Hero', 'icon' => 'fas fa-wand-magic-sparkles'],
            ['id' => 'features', 'label' => 'Features', 'icon' => 'fas fa-bolt'],
            ['id' => 'rental', 'label' => 'Rent Steps', 'icon' => 'fas fa-route'],
            ['id' => 'headings', 'label' => 'Headings', 'icon' => 'fas fa-heading'],
            ['id' => 'testimonials', 'label' => 'Testimonials', 'icon' => 'fas fa-quote-right'],
            ['id' => 'clients', 'label' => 'Client Slider', 'icon' => 'fas fa-images'],
            ['id' => 'support', 'label' => 'Support', 'icon' => 'fas fa-life-ring'],
            ['id' => 'shared', 'label' => 'Shared & Terms', 'icon' => 'fas fa-layer-group'],
            ['id' => 'seo', 'label' => 'SEO', 'icon' => 'fas fa-magnifying-glass'],
        ];
    }

    private function buildEditorSections(): array
    {
        return [
            'hero' => [
                'anchor' => 'home-pane-hero',
                'title' => 'Hero Banner',
                'description' => 'Main banner copy and hero labels shown on the homepage.',
                'fields' => [
                    ['name' => 'hero_title_prefix', 'label' => 'Title Prefix'],
                    ['name' => 'hero_title_highlight', 'label' => 'Title Highlight'],
                    ['name' => 'hero_title_suffix', 'label' => 'Title Suffix'],
                    ['name' => 'hero_banner_paragraph', 'label' => 'Banner Paragraph', 'type' => 'textarea', 'rows' => 4, 'width' => 'col-12'],
                    ['name' => 'hero_customers_label', 'label' => 'Customers Label'],
                    ['name' => 'hero_customers_subtitle', 'label' => 'Customers Subtitle'],
                    ['name' => 'hero_browse_cars_label', 'label' => 'Browse Cars Button'],
                    ['name' => 'hero_browse_blogs_label', 'label' => 'Browse Blogs Button'],
                    ['name' => 'hero_starting_from_label', 'label' => 'Starting From Label'],
                    ['name' => 'hero_per_day_label', 'label' => 'Per Day Label'],
                    ['name' => 'hero_available_for_rent_label', 'label' => 'Available For Rent Label'],
                ],
            ],
            'features' => [
                'anchor' => 'home-pane-features',
                'title' => 'Features',
                'description' => 'Feature section heading and the six feature cards.',
                'fields' => [
                    ['name' => 'feature_section_title', 'label' => 'Section Title'],
                    ['name' => 'feature_section_paragraph', 'label' => 'Section Paragraph', 'type' => 'textarea', 'rows' => 3, 'width' => 'col-12'],
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
            'rental' => [
                'anchor' => 'home-pane-rental',
                'title' => 'Rent Our Cars in 3 Steps',
                'description' => 'Control the homepage rental steps section, section images, and counter values.',
                'media_fields' => [
                    [
                        'name' => 'rental_section_image_path',
                        'label' => 'Main Section Image',
                        'description' => 'Large car image on the left side of the 3 steps section.',
                        'fallback_asset' => 'website/assets/img/about/rent-car.png',
                    ],
                    [
                        'name' => 'rental_section_grid_image_path',
                        'label' => 'Grid Overlay Image',
                        'description' => 'Small overlay image displayed on top of the main section image.',
                        'fallback_asset' => 'website/assets/img/about/car-grid.png',
                    ],
                ],
                'fields' => [
                    ['name' => 'rental_section_title', 'label' => 'Section Title'],
                    ['name' => 'rental_section_paragraph', 'label' => 'Section Paragraph', 'type' => 'textarea', 'rows' => 3, 'width' => 'col-12'],
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
            'headings' => [
                'anchor' => 'home-pane-headings',
                'title' => 'Section Headings',
                'description' => 'Titles and descriptions for the main homepage content blocks.',
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
            ],
            'testimonials' => [
                'anchor' => 'home-pane-testimonials',
                'title' => 'Testimonials',
                'description' => 'Three testimonial cards and the CTA button.',
                'fields' => [
                    ['name' => 'testimonial_section_title', 'label' => 'Section Title'],
                    ['name' => 'testimonial_section_paragraph', 'label' => 'Section Paragraph', 'type' => 'textarea', 'rows' => 3],
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
            ],
            'support' => [
                'anchor' => 'home-pane-support',
                'title' => 'Support Ticker',
                'description' => 'Scrolling support labels shown in the support marquee.',
                'fields' => [
                    ['name' => 'support_item_1_text', 'label' => 'Support Item 1'],
                    ['name' => 'support_item_2_text', 'label' => 'Support Item 2'],
                    ['name' => 'support_item_3_text', 'label' => 'Support Item 3'],
                    ['name' => 'support_item_4_text', 'label' => 'Support Item 4'],
                    ['name' => 'support_item_5_text', 'label' => 'Support Item 5'],
                ],
            ],
            'shared' => [
                'anchor' => 'home-pane-shared',
                'title' => 'Shared Content & Car Detail Terms',
                'description' => 'Edit shared homepage copy plus the Rental Terms content shown on every car details page.',
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
                    ['name' => 'mileage_policy', 'label' => 'Mileage Policy (Car Details)', 'type' => 'textarea', 'rows' => 4, 'width' => 'col-12'],
                    ['name' => 'fuel_policy', 'label' => 'Fuel Policy (Car Details)', 'type' => 'textarea', 'rows' => 4, 'width' => 'col-12'],
                    ['name' => 'deposit_policy', 'label' => 'Deposit Policy (Car Details)', 'type' => 'textarea', 'rows' => 4, 'width' => 'col-12'],
                    ['name' => 'rental_policy', 'label' => 'Rental Policy (Car Details)', 'type' => 'textarea', 'rows' => 4, 'width' => 'col-12'],
                    ['name' => 'footer_section_paragraph', 'label' => 'Footer Section Paragraph', 'type' => 'textarea', 'rows' => 4, 'width' => 'col-12'],
                ],
            ],
        ];
    }

    private function homeLanguages()
    {
        $priorityLocales = self::HOME_LOCALES;
        $activeLanguages = Language::query()
            ->active()
            ->get()
            ->keyBy('code');

        $orderedLocales = collect($priorityLocales)
            ->map(function (string $code) use ($activeLanguages): Language {
                return $activeLanguages->get($code) ?: new Language([
                    'code' => $code,
                    'name' => $this->fallbackLanguageName($code),
                    'is_active' => true,
                ]);
            });

        $additionalLocales = $activeLanguages
            ->reject(fn (Language $language, string $code): bool => in_array($code, $priorityLocales, true))
            ->sortBy('id')
            ->values();

        return $orderedLocales
            ->concat($additionalLocales)
            ->values();
    }

    private function fallbackLanguageName(string $code): string
    {
        return [
            'en' => 'English',
            'ar' => 'Arabic',
            'ru' => 'Russian',
        ][$code] ?? strtoupper($code);
    }

    private function replaceHomeMedia(Request $request, Home $home): void
    {
        $fileMap = [
            'hero_header_video_path' => 'homes/hero',
            'hero_header_image_path' => 'homes/hero',
            'rental_section_image_path' => 'homes/rental',
            'rental_section_grid_image_path' => 'homes/rental',
        ];

        foreach ($fileMap as $field => $directory) {
            if (!$request->hasFile($field)) {
                continue;
            }

            if (filled($home->{$field})) {
                Storage::disk('public')->delete($home->{$field});

                if ($field === 'hero_header_image_path') {
                    Storage::disk('public')->delete($this->optimizedHeroImagePath($home->{$field}));
                    Storage::disk('public')->delete($this->optimizedHeroImagePath($home->{$field}, 'mobile'));
                }
            }

            $storedPath = $request->file($field)->store($directory, 'public');

            if ($field === 'hero_header_image_path') {
                $this->generateOptimizedHeroImage($storedPath);
            }

            $home->{$field} = $storedPath;
        }

        $home->save();
    }

    private function replaceClientSliderItems(Request $request, Home $home): void
    {
        $existingItems = $home->clientSliderItemsForEditor();
        $submittedItems = (array) $request->input('client_slider_items', []);
        $uploadedImages = $request->file('client_slider_images', []);
        $slotCount = max(count($existingItems), count($submittedItems), 6);
        $clientSliderItems = [];

        for ($index = 0; $index < $slotCount; $index++) {
            $hasSubmittedSlot = array_key_exists($index, $submittedItems);
            $submittedItem = (array) ($submittedItems[$index] ?? []);
            $existingItem = $existingItems[$index] ?? [];
            $path = trim((string) ($submittedItem['path'] ?? data_get($existingItem, 'path', '')));
            $removeImage = filter_var($submittedItem['remove_image'] ?? false, FILTER_VALIDATE_BOOLEAN);

            if ($removeImage) {
                $this->deleteStoredClientSliderImage($path);
                $path = '';
            }

            if (is_array($uploadedImages) && isset($uploadedImages[$index])) {
                $this->deleteStoredClientSliderImage($path);
                $path = $uploadedImages[$index]->store('homes/client-slider', 'public');
            }

            $alt = trim((string) ($submittedItem['alt'] ?? data_get($existingItem, 'alt', '')));
            $url = trim((string) ($submittedItem['url'] ?? data_get($existingItem, 'url', '')));

            $clientSliderItems[] = [
                'path' => $path !== '' ? $path : null,
                'alt' => $alt !== '' ? $alt : 'Client logo ' . ($index + 1),
                'url' => $url !== '' ? $url : null,
                'is_active' => $hasSubmittedSlot
                    ? filter_var($submittedItem['is_active'] ?? false, FILTER_VALIDATE_BOOLEAN)
                    : filter_var(data_get($existingItem, 'is_active', true), FILTER_VALIDATE_BOOLEAN),
            ];
        }

        $home->client_slider_items = $clientSliderItems;
        $home->save();
    }

    private function deleteStoredClientSliderImage(?string $path): void
    {
        $path = ltrim((string) $path, '/');

        if ($path === '' || !Str::startsWith($path, 'homes/client-slider/')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    private function optimizedHeroImagePath(string $path, string $variant = 'default'): string
    {
        $pathInfo = pathinfo($path);
        $directory = ($pathInfo['dirname'] ?? '.') === '.' ? '' : ($pathInfo['dirname'] . '/');
        $filename = $pathInfo['filename'] ?? $path;

        if ($variant === 'mobile') {
            return $directory . $filename . '-mobile.webp';
        }

        return $directory . $filename . '.webp';
    }

    private function generateOptimizedHeroImage(string $path): void
    {
        $source = Storage::disk('public')->path($path);
        $optimizedPath = Storage::disk('public')->path($this->optimizedHeroImagePath($path));
        $mobileOptimizedPath = Storage::disk('public')->path($this->optimizedHeroImagePath($path, 'mobile'));

        if (!is_file($source)) {
            return;
        }

        $directory = dirname($optimizedPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        Image::make($source)
            ->orientate()
            ->resize(720, null, function ($constraint): void {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('webp', 76)
            ->save($optimizedPath);

        Image::make($source)
            ->orientate()
            ->resize(360, null, function ($constraint): void {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('webp', 70)
            ->save($mobileOptimizedPath);
    }
}
