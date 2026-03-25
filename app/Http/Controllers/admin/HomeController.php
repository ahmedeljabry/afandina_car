<?php

namespace App\Http\Controllers\admin;

use App\Models\Home;
use App\Models\Language;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeController extends GenericController
{
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
        $this->nonTranslatableFields = ['page_name', 'is_active', 'hero_type'];
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
            'meta_title.*' => 'nullable|string|max:255',
            'meta_description.*' => 'nullable|string',
            'meta_keywords.*' => 'nullable|string',
            'seo_questions.*.*.question' => 'nullable|string',
            'seo_questions.*.*.answer' => 'nullable|string',
            'robots_index.*' => 'nullable',
            'robots_follow.*' => 'nullable',
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
        $activeLanguageMap = collect($this->data['activeLanguages'] ?? [])->keyBy('code');

        return [
            ['code' => 'en', 'name' => data_get($activeLanguageMap, 'en.name', 'English')],
            ['code' => 'ar', 'name' => data_get($activeLanguageMap, 'ar.name', 'Arabic')],
        ];
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
        foreach ($translationFallbackKeys as $fieldName => $key) {
            $prefillValues[$fieldName] = [
                'en' => trans($key, [], 'en'),
                'ar' => trans($key, [], 'ar'),
            ];
        }

        $prefillValues['rental_stat_1_value'] = ['en' => '16', 'ar' => '16'];
        $prefillValues['rental_stat_1_suffix'] = ['en' => 'K+', 'ar' => 'K+'];
        $prefillValues['rental_stat_2_value'] = ['en' => '2547', 'ar' => '2547'];
        $prefillValues['rental_stat_2_suffix'] = ['en' => 'K+', 'ar' => 'K+'];
        $prefillValues['rental_stat_3_value'] = ['en' => '625', 'ar' => '625'];
        $prefillValues['rental_stat_3_suffix'] = ['en' => 'K+', 'ar' => 'K+'];
        $prefillValues['rental_stat_4_value'] = ['en' => '15000', 'ar' => '15000'];
        $prefillValues['rental_stat_4_suffix'] = ['en' => 'K+', 'ar' => 'K+'];

        return $prefillValues;
    }

    private function buildEditorTabs(): array
    {
        return [
            ['id' => 'overview', 'label' => 'Overview', 'icon' => 'fas fa-house'],
            ['id' => 'hero', 'label' => 'Hero', 'icon' => 'fas fa-wand-magic-sparkles'],
            ['id' => 'features', 'label' => 'Features', 'icon' => 'fas fa-bolt'],
            ['id' => 'rental', 'label' => 'Rental & Stats', 'icon' => 'fas fa-chart-line'],
            ['id' => 'headings', 'label' => 'Headings', 'icon' => 'fas fa-heading'],
            ['id' => 'testimonials', 'label' => 'Testimonials', 'icon' => 'fas fa-quote-right'],
            ['id' => 'support', 'label' => 'Support', 'icon' => 'fas fa-life-ring'],
            ['id' => 'shared', 'label' => 'Shared', 'icon' => 'fas fa-layer-group'],
            ['id' => 'seo', 'label' => 'SEO', 'icon' => 'fas fa-magnifying-glass'],
        ];
    }

    private function homeLanguages()
    {
        $homeLocales = ['en', 'ar'];

        return Language::query()
            ->whereIn('code', $homeLocales)
            ->get()
            ->sortBy(function ($language) use ($homeLocales) {
                return array_search($language->code, $homeLocales, true);
            })
            ->values();
    }

    private function normalizeMetaKeywords(Request $request): void
    {
        $request->merge([
            'meta_keywords' => $this->normalizeMetaKeywordsPayload((array) $request->input('meta_keywords', [])),
        ]);
    }

    private function normalizeMetaKeywordsPayload(array $metaKeywords): array
    {
        $normalized = [];

        foreach ($metaKeywords as $locale => $value) {
            $normalized[$locale] = $this->serializeKeywordList($value);
        }

        return $normalized;
    }

    private function serializeKeywordList($value): ?string
    {
        if (is_array($value)) {
            $keywords = collect($value)
                ->map(function ($item) {
                    if (is_array($item)) {
                        return trim((string) ($item['value'] ?? ''));
                    }

                    return trim((string) $item);
                })
                ->filter()
                ->unique()
                ->values();

            return $keywords->isEmpty()
                ? null
                : $keywords->map(fn($keyword) => ['value' => $keyword])->toJson();
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $keywords = collect($decoded)
                ->map(function ($item) {
                    if (is_array($item)) {
                        return trim((string) ($item['value'] ?? ''));
                    }

                    return trim((string) $item);
                })
                ->filter()
                ->unique()
                ->values();

            return $keywords->isEmpty()
                ? null
                : $keywords->map(fn($keyword) => ['value' => $keyword])->toJson();
        }

        $keywords = collect(preg_split('/[\r\n,]+/', $value) ?: [])
            ->map(fn($keyword) => trim((string) $keyword))
            ->filter()
            ->unique()
            ->values();

        return $keywords->isEmpty()
            ? null
            : $keywords->map(fn($keyword) => ['value' => $keyword])->toJson();
    }

    private function replaceHomeMedia(Request $request, Home $home): void
    {
        $fileMap = [
            'hero_header_video_path' => 'homes/hero',
            'hero_header_image_path' => 'homes/hero',
        ];

        foreach ($fileMap as $field => $directory) {
            if (!$request->hasFile($field)) {
                continue;
            }

            if (filled($home->{$field})) {
                Storage::disk('public')->delete($home->{$field});
            }

            $home->{$field} = $request->file($field)->store($directory, 'public');
        }

        $home->save();
    }
}
