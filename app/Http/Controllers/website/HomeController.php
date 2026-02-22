<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Faq;
use App\Models\Home;
use App\Models\Service;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale() ?? 'en';

        [$currencyRate, $currencySymbol] = $this->resolveCurrencyContext($locale);

        // ── Home page settings & section headings ────────────────────────────
        $home = Home::with('translations')
            ->where('is_active', true)
            ->first();

        $homeTranslation = $this->translationFor($home, $locale);

        // ── Categories ───────────────────────────────────────────────────────
        $categories = Category::query()
            ->with('translations')
            ->where('is_active', true)
            ->withCount(['cars as cars_count' => fn($q) => $q->where('is_active', true)])
            ->get()
            ->map(fn(Category $category) => [
                'id'         => $category->id,
                'name'       => $this->translationFor($category, $locale)?->name ?? '',
                'slug'       => $this->translationFor($category, $locale)?->slug,
                'image_path' => $category->image_path,
                'cars_count' => (int) $category->cars_count,
                'url'        => route('website.cars.index', ['category[]' => $category->id]),
            ]);

        // ── Featured Cars ─────────────────────────────────────────────────────
        $featuredCars = Car::query()
            ->with(['translations', 'brand.translations', 'gearType.translations', 'year', 'images'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get()
            ->map(fn(Car $car) => $this->mapCarCardData($car, $locale, $currencyRate, $currencySymbol));

        // ── Popular / Slider Cars (flash-sale → featured fallback) ───────────
        $popularCars = Car::query()
            ->with(['translations', 'brand.translations', 'gearType.translations', 'year'])
            ->where('is_active', true)
            ->where('is_flash_sale', true)
            ->latest()
            ->take(6)
            ->get();

        if ($popularCars->isEmpty()) {
            $popularCars = Car::query()
                ->with(['translations', 'brand.translations', 'gearType.translations', 'year'])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->latest()
                ->take(6)
                ->get();
        }

        $popularCars = $popularCars->map(
            fn(Car $car) => $this->mapCarCardData($car, $locale, $currencyRate, $currencySymbol)
        );

        // ── Brands ────────────────────────────────────────────────────────────
        $brands = Brand::query()
            ->with('translations')
            ->where('is_active', true)
            ->get()
            ->map(fn(Brand $brand) => [
                'name'      => $this->translationFor($brand, $locale)?->name ?? '',
                'logo_path' => $brand->logo_path,
                'url'       => route('website.cars.index', ['brand[]' => $brand->id]),
            ]);

        // ── Blogs ─────────────────────────────────────────────────────────────
        $blogs = Blog::query()
            ->with('translations')
            ->where('is_active', true)
            ->where('show_in_home', true)
            ->latest()
            ->take(3)
            ->get()
            ->map(fn(Blog $blog) => [
                'id'           => $blog->id,
                'title'        => $this->translationFor($blog, $locale)?->title ?? '',
                'description'  => $this->translationFor($blog, $locale)?->description,
                'image_path'   => $blog->image_path,
                'published_on' => $blog->created_at?->translatedFormat('d M, Y'),
                'url'          => route('website.blogs.show', ['blog' => $blog->slug ?: $blog->id]),
            ]);

        // ── FAQs ──────────────────────────────────────────────────────────────
        $faqs = Faq::query()
            ->with('translations')
            ->where('is_active', true)
            ->where('show_in_home', true)
            ->take(6)
            ->get()
            ->map(fn(Faq $faq) => [
                'id'       => $faq->id,
                'question' => $this->translationFor($faq, $locale)?->question ?? '',
                'answer'   => $this->translationFor($faq, $locale)?->answer ?? '',
            ]);

        // ── Min price for hero banner ─────────────────────────────────────────
        $minRawPrice = Car::query()
            ->where('is_active', true)
            ->whereNotNull('daily_main_price')
            ->min('daily_main_price');

        $minPrice = $minRawPrice ? (int) ceil((float) $minRawPrice * $currencyRate) : null;

        // ── Footer accordion: all categories & brands ─────────────────────────
        $allCategories = Category::query()
            ->with('translations')
            ->where('is_active', true)
            ->get()
            ->map(fn(Category $c) => [
                'name' => $this->translationFor($c, $locale)?->name ?? '',
                'url'  => route('website.cars.index', ['category[]' => $c->id]),
            ]);

        $allBrands = Brand::query()
            ->with('translations')
            ->where('is_active', true)
            ->get()
            ->map(fn(Brand $b) => [
                'name' => $this->translationFor($b, $locale)?->name ?? '',
                'url'  => route('website.cars.index', ['brand[]' => $b->id]),
            ]);

        return view('website.home', compact(
            'home',
            'homeTranslation',
            'categories',
            'featuredCars',
            'popularCars',
            'brands',
            'blogs',
            'faqs',
            'minPrice',
            'currencySymbol',
            'allCategories',
            'allBrands',
        ));
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function mapCarCardData(Car $car, string $locale, float $currencyRate, string $currencySymbol): array
    {
        $carTranslation   = $this->translationFor($car, $locale);
        $brandTranslation = $this->translationFor($car->brand, $locale);
        $gearTranslation  = $this->translationFor($car->gearType, $locale);

        $dailyMain     = $car->daily_main_price     ? (float) $car->daily_main_price     : null;
        $dailyDiscount = $car->daily_discount_price ? (float) $car->daily_discount_price : null;
        $effectivePrice = ($dailyDiscount && $dailyDiscount < $dailyMain) ? $dailyDiscount : $dailyMain;

        $images = isset($car->images)
            ? $car->images->pluck('file_path')->filter()->values()->toArray()
            : [];

        return [
            'id'               => $car->id,
            'name'             => $carTranslation?->name ?? '',
            'brand_name'       => $brandTranslation?->name,
            'gear_type'        => $gearTranslation?->name,
            'year'             => $car->year?->year,
            'status'           => $car->status,
            'is_featured'      => (bool) $car->is_featured,
            'image_path'       => $car->default_image_path,
            'images'           => $images,
            'daily_price'      => $effectivePrice ? (int) ceil($effectivePrice * $currencyRate) : null,
            'daily_main_price' => $dailyMain ? (int) ceil($dailyMain * $currencyRate) : null,
            'currency_symbol'  => $currencySymbol,
            'passenger_capacity' => $car->passenger_capacity,
            'details_url'      => route('website.cars.show', ['car' => $this->carRouteKey($car, $locale)]),
        ];
    }

    private function carRouteKey(Car $car, string $locale): string
    {
        $slug = $this->translationFor($car, $locale)?->slug
            ?? $this->translationFor($car, 'en')?->slug
            ?? $car->translations?->first(fn ($translation) => filled($translation->slug))?->slug;

        return (string) ($slug ?: $car->id);
    }

    private function resolveCurrencyContext(string $locale): array
    {
        $currency = Currency::with('translations')
            ->where('is_default', true)
            ->first()
            ?? Currency::with('translations')->first();

        $rate   = max((float) ($currency?->exchange_rate ?? 1), 0.0001);
        $symbol = $this->translationFor($currency, $locale)?->name
            ?? $this->translationFor($currency, 'en')?->name
            ?? $currency?->code
            ?? $currency?->symbol
            ?? '$';

        return [$rate, $symbol];
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
