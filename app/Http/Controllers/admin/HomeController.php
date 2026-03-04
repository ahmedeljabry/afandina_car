<?php
namespace App\Http\Controllers\admin;

use App\Models\Language;
use Illuminate\Http\Request;

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
        $this->nonTranslatableFields = ['page_name','is_active','hero_type'];
        $this->uploadedfiles = [
            'hero_header_video_path',
            'hero_header_image_path',
        ];
    }

    public function store(Request $request)
    {
        $this->validationRules = $this->buildValidationRules();
        return parent::store($request);
    }

    public function update(Request $request, $id)
    {
        $this->syncActiveFlag($request);
        $this->validationRules = $this->buildValidationRules();
        parent::update($request, $id);
        return back()->with('success', 'Home Page updated successfully.');
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

}
