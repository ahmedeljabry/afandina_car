<?php

namespace App\Http\Resources;

use App\Traits\HasLocalizedCardNames;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    use HasLocalizedCardNames;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale()??"en";
        $translations = $this->translations->where('locale', $locale)->first() ?? $this->translations->first();

        return [
            'id' => $this->id,
            'title' => $translations?->title,
            ...$this->localizedCardNames($this->resource, 'title'),
            'description' => $translations?->description,
            'slug' => $this->slug,
            'image_path' => $this->image_path ? asset('storage/'.$this->image_path) : null,
            'created_at' => $this->created_at ? $this->created_at->format('j M, Y') : null
        ];
    }
}
