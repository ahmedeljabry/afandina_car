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
            'page_title' => $aboutTranslation?->about_main_header_title,
            'agency_title' => $aboutTranslation?->about_our_agency_title,
            'main_title' => $aboutTranslation?->about_main_header_title,
            'main_paragraph' => $aboutTranslation?->about_main_header_paragraph,
            'why_choose_title' => $aboutTranslation?->why_choose_title,
            'why_choose_content' => $aboutTranslation?->why_choose_content,
            'our_vision_title' => $aboutTranslation?->our_vision_title,
            'our_vision_content' => $aboutTranslation?->our_vision_content,
            'our_mission_title' => $aboutTranslation?->our_mission_title,
            'our_mission_content' => $aboutTranslation?->our_mission_content,
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

        return view('website.about-us', compact(
            'about',
            'aboutTranslation',
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
}
