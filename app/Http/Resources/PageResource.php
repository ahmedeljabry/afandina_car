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

        return [
            'page_data' => [
                'slug' => $this->slug,
                'name' => $this->name,
                'title' => $translation->title ?? null,
                'description' => $translation->description ?? null,
                'sub_description' => $translation->sub_description ?? null,
            ],
            'seo_data' => [
                'meta_title' => $translation->meta_title ?? null,
                'meta_description' => $translation->meta_description ?? null,
                'meta_keywords' => $translation->meta_keywords ?? null,
            ],
        ];
    }
}

