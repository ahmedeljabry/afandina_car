<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Location;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AboutController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale() ?? 'en';

        $about = About::query()
            ->with('translations')
            ->where('is_active', true)
            ->first()
            ?? About::query()->with('translations')->first();

        $aboutTranslation = $this->translationFor($about, $locale);
        $aboutEnglishTranslation = $this->translationFor($about, 'en');

        $localizedField = fn (string $field, ?string $fallbackKey = null): ?string => $this->localizedField(
            $aboutTranslation,
            $aboutEnglishTranslation,
            $locale,
            $field,
            $fallbackKey
        );

        $faqs = Faq::query()
            ->with('translations')
            ->where('is_active', true)
            ->take(7)
            ->get()
            ->map(function (Faq $faq) use ($locale): ?array {
                $translation = $this->translationFor($faq, $locale);
                if (blank($translation?->question) || blank($translation?->answer)) {
                    return null;
                }

                return [
                    'id' => $faq->id,
                    'question' => $translation->question,
                    'answer' => $translation->answer,
                ];
            })
            ->filter()
            ->values();

        $aboutData = [
            'page_title' => $localizedField('about_main_header_title', 'website.about.page_title'),
            'agency_title' => $localizedField('about_our_agency_title', 'website.about.about_agency_title'),
            'main_title' => $localizedField('about_main_header_title', 'website.about.page_title'),
            'main_paragraph' => $localizedField('about_main_header_paragraph', 'website.about.fallback.main_paragraph'),
            'why_choose_title' => $localizedField('why_choose_title', 'website.about.cards.why_choose_title'),
            'why_choose_content' => $localizedField('why_choose_content', 'website.about.cards.why_choose_content'),
            'our_vision_title' => $localizedField('our_vision_title', 'website.about.cards.our_vision_title'),
            'our_vision_content' => $localizedField('our_vision_content', 'website.about.cards.our_vision_content'),
            'our_mission_title' => $localizedField('our_mission_title', 'website.about.cards.our_mission_title'),
            'our_mission_content' => $localizedField('our_mission_content', 'website.about.cards.our_mission_content'),
            'why_choose_image_url' => $this->storageUrl(
                $about?->why_choose_image_path,
                asset('website/assets/img/about-us.png')
            ),
            'our_mission_image_url' => $this->storageUrl(
                $about?->our_mission_image_path,
                asset('website/assets/img/bg/choose-left.png')
            ),
        ];

        $stats = [
            'active_cars' => Car::query()->where('is_active', true)->count(),
            'active_brands' => Brand::query()->where('is_active', true)->count(),
            'active_locations' => Location::query()->where('is_active', true)->count(),
            'active_categories' => Category::query()->where('is_active', true)->count(),
        ];

        $aboutMetaTitle = $localizedField('meta_title', 'website.about.page_title');
        $aboutMetaDescription = $localizedField('meta_description', 'website.seo.default_description');

        return view('website.about-us', compact(
            'about',
            'aboutTranslation',
            'aboutMetaTitle',
            'aboutMetaDescription',
            'aboutData',
            'faqs',
            'stats',
        ));
    }

    private function storageUrl(?string $path, ?string $fallback = null): ?string
    {
        if (blank($path)) {
            return $fallback;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    private function translationFor($model, string $locale): mixed
    {
        if (!$model || !isset($model->translations) || !($model->translations instanceof Collection)) {
            return null;
        }

        return $model->translations->firstWhere('locale', $locale)
            ?? $model->translations->first();
    }

    private function localizedField($translation, $defaultTranslation, string $locale, string $field, ?string $fallbackKey = null): ?string
    {
        $value = trim((string) data_get($translation, $field, ''));
        $defaultValue = trim((string) data_get($defaultTranslation, $field, ''));

        if ($locale !== 'en' && ($value === '' || ($defaultValue !== '' && $value === $defaultValue))) {
            return $fallbackKey ? __($fallbackKey) : null;
        }

        if ($value !== '') {
            return data_get($translation, $field);
        }

        if ($fallbackKey) {
            return __($fallbackKey);
        }

        return data_get($defaultTranslation, $field);
    }
}
