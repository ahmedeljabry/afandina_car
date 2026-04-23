<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Currency;
use App\Models\Faq;
use App\Models\Home;
use App\Models\Service;
use App\Traits\DeduplicatesCars;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    use DeduplicatesCars;

    public function index()
    {
        $locale = app()->getLocale() ?? 'en';
        $homePageData = Cache::remember(
            "website.home.index.v4.{$locale}",
            now()->addMinutes(5),
            function () use ($locale) {
                [$currencyRate, $currencySymbol] = $this->resolveCurrencyContext($locale);

                // ── Home page settings & section headings ────────────────────
                $home = Home::with('translations')
                    ->where('is_active', true)
                    ->first();

                $homeTranslation = $this->translationFor($home, $locale);

                // ── Categories ───────────────────────────────────────────────
                $categories = Category::query()
                    ->with('translations')
                    ->where('is_active', true)
                    ->withCount(['cars as cars_count' => fn($q) => $q->where('is_active', true)])
                    ->get()
                    ->map(function (Category $category) use ($locale) {
                        $translation = $this->translationFor($category, $locale);
                        $categoryRouteKey = $this->categoryRouteKey($category, $locale);
                        $categorySlug = ctype_digit($categoryRouteKey) ? null : $categoryRouteKey;

                        return [
                            'id'         => $category->id,
                            'name'       => $translation?->name ?? '',
                            'slug'       => $categorySlug,
                            'image_path' => $category->image_path,
                            'cars_count' => (int) $category->cars_count,
                            'url'        => route('website.cars.category', ['category' => $categoryRouteKey]),
                        ];
                    });

                // ── Featured Cars ────────────────────────────────────────────
                $featuredCars = Car::query()
                    ->with(['translations', 'brand.translations', 'gearType.translations', 'year', 'images'])
                    ->where('cars.is_active', true)
                    ->where('cars.is_featured', true)
                    ->orderByDesc('cars.updated_at')
                    ->orderByDesc('cars.id')
                    ->take(6)
                    ->get()
                    ->map(fn(Car $car) => $this->mapCarCardData($car, $locale, $currencyRate, $currencySymbol));

                // ── Car Only / Slider Cars ───────────────────────────────────
                $popularCarIds = $this->uniqueRepresentativeCarIds(
                    Car::query()
                        ->where('is_active', true)
                        ->where('only_on_afandina', true)
                );

                $popularCars = Car::query()
                    ->with(['translations', 'brand.translations', 'gearType.translations', 'year'])
                    ->whereIn('cars.id', $popularCarIds->all())
                    ->latest('cars.id')
                    ->take(4)
                    ->get()
                    ->map(fn(Car $car) => $this->mapCarCardData($car, $locale, $currencyRate, $currencySymbol));

                // ── Brands ────────────────────────────────────────────────────
                $brands = Brand::query()
                    ->with('translations')
                    ->where('is_active', true)
                    ->latest('id')
                    ->take(12)
                    ->get()
                    ->map(function (Brand $brand) use ($locale) {
                        $brandSlug = $this->brandRouteKey($brand, $locale);

                        if (blank($brandSlug)) {
                            return null;
                        }

                        return [
                            'id' => $brand->id,
                            'name'      => $this->translationFor($brand, $locale)?->name ?? '',
                            'logo_path' => $brand->logo_path,
                            'slug'      => $brandSlug,
                            'url'       => route('website.cars.brand', ['brand' => $brandSlug]),
                        ];
                    })
                    ->filter()
                    ->values();

                // ── Blogs ─────────────────────────────────────────────────────
                $blogs = Blog::query()
                    ->with('translations')
                    ->where('is_active', true)
                    ->where('show_in_home', true)
                    ->latest()
                    ->take(3)
                    ->get()
                    ->map(fn(Blog $blog) => [
                        'id'           => $blog->id,
                        'slug'         => $blog->slug,
                        'title'        => $this->translationFor($blog, $locale)?->title ?? '',
                        'description'  => $this->translationFor($blog, $locale)?->description,
                        'image_path'   => $blog->image_path,
                        'published_on' => $blog->created_at?->translatedFormat('d M, Y'),
                        'url'          => route('website.blogs.show', ['blog' => $blog->slug ?: $blog->id]),
                    ]);

                // ── FAQs ──────────────────────────────────────────────────────
                $faqs = Faq::query()
                    ->with('translations')
                    ->where('is_active', true)
                    ->where('show_in_home', true)
                    ->get()
                    ->map(fn(Faq $faq) => [
                        'id'       => $faq->id,
                        'question' => $this->translationFor($faq, $locale)?->question ?? '',
                        'answer'   => $this->translationFor($faq, $locale)?->answer ?? '',
                    ]);

                // ── Min price for hero banner ─────────────────────────────────
                $priceStats = Car::query()
                    ->where('is_active', true)
                    ->selectRaw('MIN(COALESCE(daily_discount_price, daily_main_price)) as min_price')
                    ->selectRaw('MAX(COALESCE(daily_discount_price, daily_main_price)) as max_price')
                    ->first();

                $minRawPrice = $priceStats?->min_price;
                $maxRawPrice = $priceStats?->max_price;
                $minPrice = $minRawPrice ? (int) ceil((float) $minRawPrice * $currencyRate) : null;
                $maxPrice = $maxRawPrice ? (int) ceil((float) $maxRawPrice * $currencyRate) : null;

                // ── Footer accordion: all categories & brands ────────────────
                $allCategories = Category::query()
                    ->with('translations')
                    ->where('is_active', true)
                    ->orderBy('id')
                    ->take(18)
                    ->get()
                    ->map(function (Category $category) use ($locale) {
                        $categoryRouteKey = $this->categoryRouteKey($category, $locale);
                        $categorySlug = ctype_digit($categoryRouteKey) ? null : $categoryRouteKey;

                        return [
                            'id' => $category->id,
                            'name' => $this->translationFor($category, $locale)?->name ?? '',
                            'slug' => $categorySlug,
                            'url'  => route('website.cars.category', ['category' => $categoryRouteKey]),
                        ];
                    });

                $allBrands = Brand::query()
                    ->with('translations')
                    ->where('is_active', true)
                    ->orderBy('id')
                    ->take(18)
                    ->get()
                    ->map(function (Brand $brand) use ($locale) {
                        $brandSlug = $this->brandRouteKey($brand, $locale);

                        if (blank($brandSlug)) {
                            return null;
                        }

                        return [
                            'id' => $brand->id,
                            'name' => $this->translationFor($brand, $locale)?->name ?? '',
                            'slug' => $brandSlug,
                            'url'  => route('website.cars.brand', ['brand' => $brandSlug]),
                        ];
                    })
                    ->filter()
                    ->values();

                $contact = Contact::query()
                    ->where('is_active', true)
                    ->latest('id')
                    ->first()
                    ?? Contact::query()->latest('id')->first();

                return compact(
                    'home',
                    'homeTranslation',
                    'categories',
                    'featuredCars',
                    'popularCars',
                    'brands',
                    'blogs',
                    'faqs',
                    'minPrice',
                    'maxPrice',
                    'currencySymbol',
                    'allCategories',
                    'allBrands',
                    'contact',
                );
            }
        );

        return view('website.home', $homePageData);
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function mapCarCardData(Car $car, string $locale, float $currencyRate, string $currencySymbol): array
    {
        $carTranslation   = $this->translationFor($car, $locale);
        $brandTranslation = $this->translationFor($car->brand, $locale);
        $gearTranslation  = $this->translationFor($car->gearType, $locale);
        $carRouteKey = $this->carRouteKey($car, $locale);
        $brandRouteKey = $car->brand ? $this->brandRouteKey($car->brand, $locale) : null;
        $detailsUrl = filled($carRouteKey)
            ? route('website.cars.show', ['car' => $carRouteKey])
            : route('website.cars.index');

        $dailyMain     = $car->daily_main_price     ? (float) $car->daily_main_price     : null;
        $dailyDiscount = $car->daily_discount_price ? (float) $car->daily_discount_price : null;
        $effectivePrice = $dailyDiscount !== null && ($dailyMain === null || $dailyDiscount < $dailyMain)
            ? $dailyDiscount
            : $dailyMain;
        $weeklyMain = $car->weekly_main_price ? (float) $car->weekly_main_price : null;
        $weeklyDiscount = $car->weekly_discount_price ? (float) $car->weekly_discount_price : null;
        $effectiveWeeklyPrice = $weeklyDiscount !== null && ($weeklyMain === null || $weeklyDiscount < $weeklyMain)
            ? $weeklyDiscount
            : $weeklyMain;
        $monthlyMain = $car->monthly_main_price ? (float) $car->monthly_main_price : null;
        $monthlyDiscount = $car->monthly_discount_price ? (float) $car->monthly_discount_price : null;
        $effectiveMonthlyPrice = $monthlyDiscount !== null && ($monthlyMain === null || $monthlyDiscount < $monthlyMain)
            ? $monthlyDiscount
            : $monthlyMain;

        $images = $car->relationLoaded('images')
            ? $car->images->pluck('file_path')->filter()->values()->toArray()
            : [];

        return [
            'id'               => $car->id,
            'name'             => filled($carTranslation?->card_name) ? $carTranslation->card_name : ($carTranslation?->name ?? ''),
            'brand_name'       => $brandTranslation?->name,
            'brand_url'        => ($car->brand && filled($brandRouteKey))
                ? route('website.cars.brand', ['brand' => $brandRouteKey])
                : route('website.cars.index'),
            'gear_type'        => $gearTranslation?->name,
            'year'             => $car->year?->year,
            'status'           => $car->status,
            'is_featured'      => (bool) $car->is_featured,
            'is_popular'       => (bool) $car->is_popular,
            'image_path'       => $car->default_image_path,
            'images'           => $images,
            'daily_price'      => $effectivePrice ? (int) ceil($effectivePrice * $currencyRate) : null,
            'weekly_price'     => $effectiveWeeklyPrice ? (int) ceil($effectiveWeeklyPrice * $currencyRate) : null,
            'monthly_price'    => $effectiveMonthlyPrice ? (int) ceil($effectiveMonthlyPrice * $currencyRate) : null,
            'daily_main_price' => $dailyMain ? (int) ceil($dailyMain * $currencyRate) : null,
            'currency_symbol'  => $currencySymbol,
            'passenger_capacity' => $car->passenger_capacity,
            'details_url'      => $detailsUrl,
        ];
    }

    private function carRouteKey(Car $car, string $locale): ?string
    {
        $slug = $this->translationFor($car, $locale)?->slug
            ?? $this->translationFor($car, 'en')?->slug
            ?? $car->translations?->first(fn ($translation) => filled($translation->slug))?->slug
            ?? (filled($car->slug ?? null) ? (string) $car->slug : null);

        return filled($slug) ? (string) $slug : null;
    }

    private function brandRouteKey(Brand $brand, string $locale): ?string
    {
        $slug = $brand->slug;

        return filled($slug) ? (string) $slug : null;
    }

    private function categoryRouteKey(Category $category, string $locale): string
    {
        $slug = $category->slug;

        return filled($slug) ? (string) $slug : (string) $category->id;
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
