<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait HasLocalizedCardNames
{
    protected function localizedCardNames($model, string $attribute = 'name', ?string $fallback = null): array
    {
        $nameEn = $this->translatedCardValue($model, 'en', $attribute);
        $nameAr = $this->translatedCardValue($model, 'ar', $attribute);

        if (filled($fallback)) {
            $nameEn ??= $fallback;
            $nameAr ??= $fallback;
        }

        return [
            'name_en' => $nameEn,
            'name_ar' => $nameAr,
        ];
    }

    protected function translatedCardValue($model, string $locale, string $attribute = 'name'): ?string
    {
        $translations = $this->translationCollection($model);

        if (!$translations) {
            return null;
        }

        $translation = $translations->firstWhere('locale', $locale);
        $value = data_get($translation, $attribute);

        return filled($value) ? (string) $value : null;
    }

    protected function translationCollection($model): ?Collection
    {
        if (!$model) {
            return null;
        }

        if (isset($model->translations) && $model->translations instanceof Collection) {
            return $model->translations;
        }

        if (method_exists($model, 'translations')) {
            $translations = $model->translations()->get();

            return $translations instanceof Collection ? $translations : collect($translations);
        }

        return null;
    }
}
