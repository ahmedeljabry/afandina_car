<?php
namespace App\Http\Controllers\admin;
use Illuminate\Http\Request;

class HomeController extends GenericController
{
    public function __construct()
    {
        parent::__construct('home');
        $this->translatableFields = [
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
        ];
        $this->nonTranslatableFields = ['page_name','is_active','hero_type'];
        $this->uploadedfiles = [
            'hero_header_video_path',
            'hero_header_image_path',
        ];
    }

    public function store(Request $request)
    {
        $this->syncActiveFlag($request);
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

        return $rules;
    }

}
