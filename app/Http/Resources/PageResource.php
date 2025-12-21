<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $locale = app()->getLocale() ?? 'en';
        $translation = $this->translations->where('locale', $locale)->first();

        $pageData = [
            'slug' => $this->slug,
            'name' => $this->name,
            'title' => $translation->title ?? null,
            'description' => $translation->description ?? null,
            'sub_description' => $translation->sub_description ?? null,
        ];

        // Add sections only for home page
        if ($this->slug === 'home') {
            $pageData['category_section'] = [
                'title' => $translation->category_section_title ?? null,
                'description' => $translation->category_section_description ?? null,
            ];
            $pageData['brands_section'] = [
                'title' => $translation->brands_section_title ?? null,
                'description' => $translation->brands_section_description ?? null,
            ];
            $pageData['special_offers'] = [
                'title' => $translation->special_offers_title ?? null,
                'description' => $translation->special_offers_description ?? null,
            ];
            $pageData['only_on_us'] = [
                'title' => $translation->only_on_us_title ?? null,
                'description' => $translation->only_on_us_description ?? null,
            ];
        }

        return [
            'page_data' => $pageData,
            'seo_data' => [
                'meta_title' => $translation->meta_title ?? null,
                'meta_description' => $translation->meta_description ?? null,
                'meta_keywords' => $translation->meta_keywords ?? null,
            ],
        ];
    }
}

