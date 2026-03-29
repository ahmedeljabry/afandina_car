<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Car;
use App\Models\Contact;
use App\Models\Currency;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Year;
use App\Traits\DeduplicatesCars;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CarController extends Controller
{
    use DeduplicatesCars;

    public function index(Request $request)
    {
        $locale = app()->getLocale() ?? 'en';
        [$currencyRate, $currencySymbol, $currencyCode] = $this->resolveCurrencyContext($locale);

        $translationFor = fn($model) => $this->translationFor($model, $locale);

        $brandIds = collect((array) $request->input('brand', []))
            ->filter(fn($value) => filled($value))
            ->map(fn($value) => (int) $value)
            ->values()
            ->all();

        $categoryFilters = collect((array) $request->input('category', []))
            ->map(fn($value) => is_string($value) ? trim($value) : (string) $value)
            ->filter(fn($value) => filled($value))
            ->values();

        $categoryIdsFromQuery = $categoryFilters
            ->filter(fn($value) => ctype_digit((string) $value))
            ->map(fn($value) => (int) $value)
            ->filter(fn($value) => $value > 0)
            ->values();

        $categorySlugs = $categoryFilters
            ->filter(fn($value) => !ctype_digit((string) $value))
            ->map(fn($value) => (string) $value)
            ->values();

        $categoryIdsFromSlugs = collect();
        if ($categorySlugs->isNotEmpty()) {
            $categoryIdsFromSlugs = Category::query()
                ->whereIn('slug', $categorySlugs)
                ->pluck('id');
        }

        $categoryIds = $categoryIdsFromQuery
            ->merge($categoryIdsFromSlugs)
            ->map(fn($value) => (int) $value)
            ->filter(fn($value) => $value > 0)
            ->unique()
            ->values()
            ->all();

        $yearIds = collect((array) $request->input('year', []))
            ->filter(fn($value) => filled($value))
            ->map(fn($value) => (int) $value)
            ->values()
            ->all();

        $search = trim((string) $request->input('search', ''));
        $availableOnly = $request->boolean('available_only');

        $perPage = (int) $request->input('per_page', 9);
        if (!in_array($perPage, [6, 9, 12, 18, 24], true)) {
            $perPage = 9;
        }

        $sort = (string) $request->input('sort', 'newest');

        $carsBaseQuery = Car::query()->where('is_active', true);

        if (!empty($brandIds)) {
            $carsBaseQuery->whereIn('brand_id', $brandIds);
        }

        if (!empty($categoryIds)) {
            $carsBaseQuery->whereIn('category_id', $categoryIds);
        }

        if (!empty($yearIds)) {
            $carsBaseQuery->whereIn('year_id', $yearIds);
        }

        if ($availableOnly) {
            $carsBaseQuery->where('status', 'available');
        }

        if (filled($search)) {
            $carsBaseQuery->whereHas('translations', function ($query) use ($search, $locale) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                })->where('locale', $locale);
            });
        }

        $representativeCarIds = $this->uniqueRepresentativeCarIds($carsBaseQuery);

        $carsQuery = Car::query()
            ->with([
                'translations',
                'brand.translations',
                'category.translations',
                'gearType.translations',
                'year',
            ])
            ->whereIn('cars.id', $representativeCarIds->all());

        switch ($sort) {
            case 'price_low':
                $carsQuery->orderByRaw('COALESCE(daily_discount_price, daily_main_price) asc');
                break;
            case 'price_high':
                $carsQuery->orderByRaw('COALESCE(daily_discount_price, daily_main_price) desc');
                break;
            case 'year_new':
                $carsQuery->leftJoin('years', 'years.id', '=', 'cars.year_id')
                    ->orderBy('years.year', 'desc')
                    ->select('cars.*');
                break;
            case 'year_old':
                $carsQuery->leftJoin('years', 'years.id', '=', 'cars.year_id')
                    ->orderBy('years.year', 'asc')
                    ->select('cars.*');
                break;
            default:
                $carsQuery->latest('cars.id');
                break;
        }

        $cars = $carsQuery
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn(Car $car) => $this->mapCarCardData($car, $locale, $currencyRate, $currencySymbol, $currencyCode));

        $brands = Brand::query()
            ->with('translations')
            ->where('is_active', true)
            ->whereHas('cars', function ($query) {
                $query->where('is_active', true);
            })
            ->withCount([
                'cars as active_cars_count' => function ($query) {
                    $query->where('is_active', true);
                },
            ])
            ->orderByDesc('active_cars_count')
            ->get()
            ->map(function (Brand $brand) use ($translationFor) {
                $translation = $translationFor($brand);

                return [
                    'id' => $brand->id,
                    'name' => $translation?->name ?? 'Brand',
                    'cars_count' => (int) $brand->active_cars_count,
                ];
            });

        $categories = Category::query()
            ->with('translations')
            ->where('is_active', true)
            ->whereHas('cars', function ($query) {
                $query->where('is_active', true);
            })
            ->withCount([
                'cars as active_cars_count' => function ($query) {
                    $query->where('is_active', true);
                },
            ])
            ->orderByDesc('active_cars_count')
            ->get()
            ->map(function (Category $category) use ($translationFor) {
                $translation = $translationFor($category);

                return [
                    'id' => $category->id,
                    'name' => $translation?->name ?? 'Category',
                    'cars_count' => (int) $category->active_cars_count,
                ];
            });

        $years = Year::query()
            ->join('cars', 'cars.year_id', '=', 'years.id')
            ->where('cars.is_active', true)
            ->groupBy('years.id', 'years.year')
            ->selectRaw('years.id, years.year, COUNT(cars.id) as cars_count')
            ->orderBy('years.year', 'desc')
            ->get()
            ->map(function ($year) {
                return [
                    'id' => (int) $year->id,
                    'year' => $year->year,
                    'cars_count' => (int) $year->cars_count,
                ];
            });

        $filters = [
            'brands' => $brands,
            'categories' => $categories,
            'years' => $years,
            'selected_brand_ids' => $brandIds,
            'selected_category_ids' => $categoryIds,
            'selected_year_ids' => $yearIds,
            'search' => $search,
            'available_only' => $availableOnly,
            'sort' => $sort,
            'per_page' => $perPage,
        ];

        $contact = Contact::query()
            ->where('is_active', true)
            ->latest('id')
            ->first()
            ?? Contact::query()->latest('id')->first();

        $allCarsPage = Page::query()
            ->with('translations')
            ->where('slug', 'all-cars')
            ->where('is_active', true)
            ->first();

        $allCarsPageTranslation = $this->translationFor($allCarsPage, $locale);

        return view('website.cars', compact('cars', 'filters', 'contact', 'allCarsPageTranslation'));
    }

    public function brand(Request $request, string $brand)
    {
        $locale = app()->getLocale() ?? 'en';
        $requestedIdentifier = trim(urldecode($brand));
        $brandModel = $this->resolveBrand($requestedIdentifier, $locale);

        if (!$brandModel || !$brandModel->is_active) {
            abort(404);
        }

        $canonicalKey = $this->brandRouteKey($brandModel, $locale);
        if (blank($canonicalKey)) {
            abort(404);
        }

        if ($requestedIdentifier !== $canonicalKey) {
            return redirect()->route('website.cars.brand', ['brand' => $canonicalKey]);
        }

        $brandName = $this->translationFor($brandModel, $locale)?->name
            ?? $this->translationFor($brandModel, 'en')?->name
            ?? __('website.common.brand');
        $brandTranslation = $this->translationFor($brandModel, $locale)
            ?? $this->translationFor($brandModel, 'en');
        $brandContentTitle = filled($brandTranslation?->title)
            ? $brandTranslation->title
            : (filled($brandTranslation?->meta_title) ? $brandTranslation->meta_title : $brandName);
        $brandContentDescription = filled($brandTranslation?->description)
            ? $brandTranslation->description
            : ($brandTranslation?->meta_description ?? '');
        $brandContentArticle = filled($brandTranslation?->article)
            ? $brandTranslation->article
            : '';

        $listingRequest = $this->buildScopedListingRequest($request, [
            'brand' => [(int) $brandModel->id],
        ]);

        return $this->index($listingRequest)->with('listingContext', [
            'show_filters' => false,
            'action_url' => route('website.cars.brand', ['brand' => $canonicalKey]),
            'reset_url' => route('website.cars.brand', ['brand' => $canonicalKey]),
            'page_title' => $brandName,
            'meta_title' => $brandName . ' - ' . __('website.nav.all_cars'),
            'breadcrumb_parent_label' => __('website.nav.brands'),
            'breadcrumb_parent_url' => route('home') . '#home-brands',
            'seo_content_in_breadcrumb' => true,
            'content_title' => $brandContentTitle,
            'content_description' => $brandContentDescription,
            'content_article' => $brandContentArticle,
        ]);
    }

    public function category(Request $request, string $category)
    {
        $locale = app()->getLocale() ?? 'en';
        $requestedIdentifier = trim(urldecode($category));
        $categoryModel = $this->resolveCategory($requestedIdentifier, $locale);

        if (!$categoryModel || !$categoryModel->is_active) {
            abort(404);
        }

        $canonicalKey = $this->categoryRouteKey($categoryModel, $locale);
        if (blank($canonicalKey)) {
            abort(404);
        }

        if ($requestedIdentifier !== $canonicalKey) {
            return redirect()->route('website.cars.category', ['category' => $canonicalKey]);
        }

        $categoryName = $this->translationFor($categoryModel, $locale)?->name
            ?? $this->translationFor($categoryModel, 'en')?->name
            ?? __('website.common.category');
        $categoryTranslation = $this->translationFor($categoryModel, $locale)
            ?? $this->translationFor($categoryModel, 'en');

        $listingRequest = $this->buildScopedListingRequest($request, [
            'category' => [(int) $categoryModel->id],
        ]);

        return $this->index($listingRequest)->with('listingContext', [
            'show_filters' => false,
            'action_url' => route('website.cars.category', ['category' => $canonicalKey]),
            'reset_url' => route('website.cars.category', ['category' => $canonicalKey]),
            'page_title' => $categoryName,
            'meta_title' => $categoryName . ' - ' . __('website.nav.all_cars'),
            'breadcrumb_parent_label' => __('website.nav.categories'),
            'breadcrumb_parent_url' => route('home') . '#home-categories',
            'seo_content_in_breadcrumb' => true,
            'content_title' => $categoryTranslation?->title,
            'content_description' => $categoryTranslation?->description,
            'content_article' => $categoryTranslation?->article,
        ]);
    }

    public function show(string $car)
    {
        $locale = app()->getLocale() ?? 'en';
        $requestedIdentifier = trim(urldecode($car));
        $carModel = $this->resolveCar($requestedIdentifier, $locale);

        if (!$carModel || !$carModel->is_active) {
            abort(404);
        }

        $canonicalSlug = $this->carSlugForLocale($carModel, $locale);
        if (blank($canonicalSlug)) {
            abort(404);
        }

        if ($requestedIdentifier !== $canonicalSlug) {
            return redirect()->route('website.cars.show', ['car' => $canonicalSlug]);
        }

        [$currencyRate, $currencySymbol, $currencyCode] = $this->resolveCurrencyContext($locale);

        $carModel->load([
            'translations',
            'images',
            'brand.translations',
            'category.translations',
            'gearType.translations',
            'year',
            'color.translations',
            'carModel.translations',
            'features.translations',
            'features.icon',
        ]);

        $carDetails = $this->mapCarDetailsData($carModel, $locale, $currencyRate, $currencySymbol, $currencyCode);
        $currentCarGroupKey = $this->carDeduplicationKey($carModel);

        $relatedCarsBaseQuery = Car::query()
            ->where('is_active', true)
            ->where('cars.id', '!=', $carModel->id)
            ->when($carModel->category_id, function ($query) use ($carModel) {
                $query->where('category_id', $carModel->category_id);
            });

        $relatedCarIds = $this->uniqueRepresentativeCarIds($relatedCarsBaseQuery);

        $relatedCars = Car::query()
            ->with([
                'translations',
                'brand.translations',
                'category.translations',
                'gearType.translations',
                'year',
            ])
            ->whereIn('cars.id', $relatedCarIds->all())
            ->latest('cars.id')
            ->get()
            ->pipe(fn (Collection $cars) => $this->dedupeCarsCollection($cars))
            ->reject(fn (Car $relatedCar) => $this->carDeduplicationKey($relatedCar) === $currentCarGroupKey)
            ->take(6)
            ->values()
            ->map(fn(Car $relatedCar) => $this->mapCarCardData($relatedCar, $locale, $currencyRate, $currencySymbol, $currencyCode));

        $faqs = Faq::query()
            ->with('translations')
            ->where('is_active', true)
            ->take(4)
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

        $contact = Contact::query()
            ->where('is_active', true)
            ->latest('id')
            ->first()
            ?? Contact::query()->latest('id')->first();

        return view('website.car-details', compact('carDetails', 'relatedCars', 'faqs', 'contact'));
    }

    private function mapCarCardData(Car $car, string $locale, float $currencyRate, string $currencySymbol, string $currencyCode): array
    {
        $carTranslation = $this->translationFor($car, $locale);
        $brandTranslation = $this->translationFor($car->brand, $locale);
        $categoryTranslation = $this->translationFor($car->category, $locale);
        $gearTypeTranslation = $this->translationFor($car->gearType, $locale);
        $carRouteKey = $this->carRouteKey($car, $locale);
        $brandRouteKey = $car->brand ? $this->brandRouteKey($car->brand, $locale) : null;
        $categoryRouteKey = $car->category ? $this->categoryRouteKey($car->category, $locale) : '';
        $detailsUrl = filled($carRouteKey)
            ? route('website.cars.show', ['car' => $carRouteKey])
            : route('website.cars.index');

        $dailyMainPrice = $car->daily_main_price ? (float) $car->daily_main_price : null;
        $dailyDiscountPrice = $car->daily_discount_price ? (float) $car->daily_discount_price : null;
        $effectiveDailyPrice = $dailyDiscountPrice ?? $dailyMainPrice;

        $monthlyMainPrice = $car->monthly_main_price ? (float) $car->monthly_main_price : null;
        $monthlyDiscountPrice = $car->monthly_discount_price ? (float) $car->monthly_discount_price : null;
        $effectiveMonthlyPrice = $monthlyDiscountPrice ?? $monthlyMainPrice;

        $discountRate = null;
        if ($dailyMainPrice && $dailyDiscountPrice && $dailyDiscountPrice < $dailyMainPrice) {
            $discountRate = (int) ceil((($dailyMainPrice - $dailyDiscountPrice) / $dailyMainPrice) * 100);
        }

        return [
            'id' => $car->id,
            'details_url' => $detailsUrl,
            'name' => filled($carTranslation?->card_name) ? $carTranslation->card_name : ($carTranslation?->name ?? __('website.common.car')),
            'brand_name' => $brandTranslation?->name,
            'brand_url' => ($car->brand && filled($brandRouteKey))
                ? route('website.cars.brand', ['brand' => $brandRouteKey])
                : route('website.cars.index'),
            'category_name' => $categoryTranslation?->name,
            'category_url' => $car->category ? route('website.cars.category', ['category' => $categoryRouteKey]) : route('website.cars.index'),
            'gear_type_name' => $gearTypeTranslation?->name,
            'year' => $car->year?->year,
            'image_path' => $car->default_image_path,
            'status' => $car->status,
            'is_featured' => (bool) $car->is_featured,
            'daily_price' => $effectiveDailyPrice ? (int) ceil($effectiveDailyPrice * $currencyRate) : null,
            'daily_main_price' => $dailyMainPrice ? (int) ceil($dailyMainPrice * $currencyRate) : null,
            'monthly_price' => $effectiveMonthlyPrice ? (int) ceil($effectiveMonthlyPrice * $currencyRate) : null,
            'monthly_main_price' => $monthlyMainPrice ? (int) ceil($monthlyMainPrice * $currencyRate) : null,
            'daily_mileage_included' => $car->daily_mileage_included,
            'passenger_capacity' => $car->passenger_capacity,
            'door_count' => $car->door_count,
            'discount_rate' => $discountRate,
            'currency_symbol' => $currencySymbol,
            'currency_code' => $currencyCode,
        ];
    }

    private function resolveCar(string $identifier, string $locale): ?Car
    {
        $car = Car::query()
            ->whereHas('translations', function ($query) use ($identifier, $locale) {
                $query->where('locale', $locale)
                    ->where('slug', $identifier);
            })
            ->first();

        if ($car) {
            return $car;
        }

        $car = Car::query()
            ->whereHas('translations', function ($query) use ($identifier) {
                $query->where('slug', $identifier);
            })
            ->first();

        if ($car) {
            return $car;
        }

        return null;
    }

    private function carRouteKey(Car $car, string $locale): ?string
    {
        $slug = $this->carSlugForLocale($car, $locale);

        return filled($slug) ? (string) $slug : null;
    }

    private function carSlugForLocale(Car $car, string $locale): ?string
    {
        $translations = $car->relationLoaded('translations')
            ? $car->translations
            : $car->translations()->get();

        return $translations->firstWhere('locale', $locale)?->slug
            ?? $translations->firstWhere('locale', 'en')?->slug
            ?? $translations->first(fn($translation) => filled($translation->slug))?->slug
            ?? (filled($car->slug ?? null) ? (string) $car->slug : null);
    }

    private function mapCarDetailsData(Car $car, string $locale, float $currencyRate, string $currencySymbol, string $currencyCode): array
    {
        $carTranslation = $this->translationFor($car, $locale);
        $brandTranslation = $this->translationFor($car->brand, $locale);
        $categoryTranslation = $this->translationFor($car->category, $locale);
        $gearTypeTranslation = $this->translationFor($car->gearType, $locale);
        $colorTranslation = $this->translationFor($car->color, $locale);
        $carModelTranslation = $this->translationFor($car->carModel, $locale);
        $carRouteKey = $this->carRouteKey($car, $locale);
        $brandRouteKey = $car->brand ? $this->brandRouteKey($car->brand, $locale) : null;
        $categoryRouteKey = $car->category ? $this->categoryRouteKey($car->category, $locale) : '';

        $toPrice = static function ($value, float $rate): ?int {
            if ($value === null || $value === '') {
                return null;
            }

            return (int) ceil(((float) $value) * $rate);
        };

        $dailyMainPrice = $toPrice($car->daily_main_price, $currencyRate);
        $dailyDiscountPrice = $toPrice($car->daily_discount_price, $currencyRate);
        $weeklyMainPrice = $toPrice($car->weekly_main_price, $currencyRate);
        $weeklyDiscountPrice = $toPrice($car->weekly_discount_price, $currencyRate);
        $monthlyMainPrice = $toPrice($car->monthly_main_price, $currencyRate);
        $monthlyDiscountPrice = $toPrice($car->monthly_discount_price, $currencyRate);

        $discountRate = null;
        if ($dailyMainPrice && $dailyDiscountPrice && $dailyDiscountPrice < $dailyMainPrice) {
            $discountRate = (int) ceil((($dailyMainPrice - $dailyDiscountPrice) / $dailyMainPrice) * 100);
        }

        $images = collect();

        if (filled($car->default_image_path)) {
            $images->push([
                'file_path' => $car->default_image_path,
                'thumbnail_path' => $car->default_thumbnail_path ?? $car->default_image_path,
                'alt' => $carTranslation?->name ?? __('website.common.car'),
                'type' => 'image',
            ]);
        }

        foreach ($car->images as $image) {
            if (!filled($image->file_path)) {
                continue;
            }

            $images->push([
                'file_path' => $image->file_path,
                'thumbnail_path' => $image->thumbnail_path ?? $image->file_path,
                'alt' => $image->alt ?: ($carTranslation?->name ?? __('website.common.car')),
                'type' => $image->type ?: 'image',
            ]);
        }

        $features = $car->features
            ->map(function ($feature) use ($locale): ?array {
                $translation = $this->translationFor($feature, $locale);
                if (blank($translation?->name)) {
                    return null;
                }

                return [
                    'id' => $feature->id,
                    'name' => $translation->name,
                    'icon_class' => $feature->icon?->icon_class,
                ];
            })
            ->filter()
            ->values();

        return [
            'id' => $car->id,
            'slug' => $carRouteKey,
            'details_url' => filled($carRouteKey)
                ? route('website.cars.show', ['car' => $carRouteKey])
                : route('website.cars.index'),
            'name' => $carTranslation?->name ?? __('website.common.car'),
            'description' => $carTranslation?->description,
            'long_description' => $carTranslation?->long_description,
            'status' => $car->status,
            'is_featured' => (bool) $car->is_featured,
            'is_flash_sale' => (bool) $car->is_flash_sale,
            'only_on_afandina' => (bool) $car->only_on_afandina,
            'discount_rate' => $discountRate,
            'listed_on' => $car->created_at?->translatedFormat('d M, Y'),
            'brand_name' => $brandTranslation?->name,
            'brand_url' => ($car->brand && filled($brandRouteKey))
                ? route('website.cars.brand', ['brand' => $brandRouteKey])
                : route('website.cars.index'),
            'category_name' => $categoryTranslation?->name,
            'category_url' => $car->category ? route('website.cars.category', ['category' => $categoryRouteKey]) : route('website.cars.index'),
            'gear_type_name' => $gearTypeTranslation?->name,
            'color_name' => $colorTranslation?->name,
            'model_name' => $carModelTranslation?->name,
            'year' => $car->year?->year,
            'door_count' => $car->door_count,
            'luggage_capacity' => $car->luggage_capacity,
            'passenger_capacity' => $car->passenger_capacity,
            'daily_mileage_included' => $car->daily_mileage_included,
            'weekly_mileage_included' => $car->weekly_mileage_included,
            'monthly_mileage_included' => $car->monthly_mileage_included,
            'insurance_included' => (bool) $car->insurance_included,
            'free_delivery' => (bool) $car->free_delivery,
            'crypto_payment_accepted' => (bool) $car->crypto_payment_accepted,
            'image_path' => $car->default_image_path,
            'images' => $images->unique('file_path')->values()->all(),
            'features' => $features->all(),
            'prices' => [
                'daily_main' => $dailyMainPrice,
                'daily_discount' => $dailyDiscountPrice,
                'weekly_main' => $weeklyMainPrice,
                'weekly_discount' => $weeklyDiscountPrice,
                'monthly_main' => $monthlyMainPrice,
                'monthly_discount' => $monthlyDiscountPrice,
            ],
            'currency_symbol' => $currencySymbol,
            'currency_code' => $currencyCode,
        ];
    }
    private function resolveCurrencyContext(string $locale): array
    {
        $currency = Currency::with('translations')->where('is_default', true)->first();
        if (!$currency) {
            $currency = Currency::with('translations')->first();
        }

        $currencyRate = (float) ($currency?->exchange_rate ?? 1);
        if ($currencyRate <= 0) {
            $currencyRate = 1;
        }

        $currencySymbol = $this->translationFor($currency, $locale)?->name
            ?? $this->translationFor($currency, 'en')?->name
            ?? $currency?->code
            ?? $currency?->symbol
            ?? '$';

        $currencyCode = $currency?->code ?? 'AED';

        return [$currencyRate, $currencySymbol, $currencyCode];
    }

    private function translationFor($model, string $locale): mixed
    {
        if (!$model || !isset($model->translations) || !($model->translations instanceof Collection)) {
            return null;
        }

        return $model->translations->firstWhere('locale', $locale) ?? $model->translations->first();
    }

    private function buildScopedListingRequest(Request $request, array $forcedFilters): Request
    {
        $query = [
            'sort' => $request->query('sort', 'newest'),
            'per_page' => $request->query('per_page', 9),
            'page' => $request->query('page'),
        ];

        foreach ($forcedFilters as $key => $value) {
            $query[$key] = $value;
        }

        return $request->duplicate($query);
    }

    private function resolveBrand(string $identifier, string $locale): ?Brand
    {
        $brand = Brand::query()
            ->with('translations')
            ->where('slug', $identifier)
            ->first();

        if ($brand) {
            return $brand;
        }

        if (ctype_digit($identifier)) {
            return Brand::query()
                ->with('translations')
                ->find((int) $identifier);
        }

        return null;
    }

    private function resolveCategory(string $identifier, string $locale): ?Category
    {
        $category = Category::query()
            ->with('translations')
            ->where('slug', $identifier)
            ->first();

        if ($category) {
            return $category;
        }

        if (ctype_digit($identifier)) {
            return Category::query()
                ->with('translations')
                ->find((int) $identifier);
        }

        return null;
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
}
