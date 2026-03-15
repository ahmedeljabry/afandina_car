<?php

namespace App\Http\Resources;

use App\Traits\HasLocalizedCardNames;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    use HasLocalizedCardNames;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale() ?? 'en';
        $translation = $this->translations->where('locale', $locale)->first() ?? $this->translations->first();

        return [
            'id' => $this->id,
            'name' => $translation?->name,
            ...$this->localizedCardNames($this->resource),
            'slug' => $this->slug,
            'description' => $translation?->description,
            'image_path' => $this->image_path ? asset('storage/'.$this->image_path) : null,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
        ];
    }
}
