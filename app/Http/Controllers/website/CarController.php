<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Car;
use App\Models\Contact;
use App\Models\Currency;
use App\Models\Faq;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale() ?? 'en';
        [$currencyRate, $currencySymbol] = $this->resolveCurrencyContext($locale);

        $translationFor = fn ($model) => $this->translationFor($model, $locale);

        $brandIds = collect((array) $request->input('brand', []))
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => (int) $value)
            ->values()
            ->all();

        $categoryIds = collect((array) $request->input('category', []))
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => (int) $value)
            ->values()
            ->all();

        $yearIds = collect((array) $request->input('year', []))
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => (int) $value)
            ->values()
            ->all();

        $search = trim((string) $request->input('search', ''));
        $availableOnly = $request->boolean('available_only');

        $perPage = (int) $request->input('per_page', 9);
        if (!in_array($perPage, [6, 9, 12, 18, 24], true)) {
            $perPage = 9;
        }

        $sort = (string) $request->input('sort', 'newest');

        $carsQuery = Car::query()
            ->with([
                'translations',
                'brand.translations',
                'category.translations',
                'gearType.translations',
                'year',
            ])
            ->where('is_active', true);

        if (!empty($brandIds)) {
            $carsQuery->whereIn('brand_id', $brandIds);
        }

        if (!empty($categoryIds)) {
            $carsQuery->whereIn('category_id', $categoryIds);
        }

        if (!empty($yearIds)) {
            $carsQuery->whereIn('year_id', $yearIds);
        }

        if ($availableOnly) {
            $carsQuery->where('status', 'available');
        }

        if (filled($search)) {
            $carsQuery->whereHas('translations', function ($query) use ($search, $locale) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                })->where('locale', $locale);
            });
        }

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
            ->through(fn (Car $car) => $this->mapCarCardData($car, $locale, $currencyRate, $currencySymbol));

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

        return view('website.cars', compact('cars', 'filters'));
    }

    public function show(Car $car)
    {
        if (!$car->is_active) {
            abort(404);
        }

        $locale = app()->getLocale() ?? 'en';
        [$currencyRate, $currencySymbol] = $this->resolveCurrencyContext($locale);

        $car->load([
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

        $carDetails = $this->mapCarDetailsData($car, $locale, $currencyRate, $currencySymbol);

        $relatedCars = Car::query()
            ->with([
                'translations',
                'brand.translations',
                'category.translations',
                'gearType.translations',
                'year',
            ])
            ->where('is_active', true)
            ->where('id', '!=', $car->id)
            ->when($car->category_id, function ($query) use ($car) {
                $query->where('category_id', $car->category_id);
            })
            ->latest('id')
            ->take(6)
            ->get()
            ->map(fn (Car $relatedCar) => $this->mapCarCardData($relatedCar, $locale, $currencyRate, $currencySymbol));

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

    private function mapCarCardData(Car $car, string $locale, float $currencyRate, string $currencySymbol): array
    {
        $carTranslation = $this->translationFor($car, $locale);
        $brandTranslation = $this->translationFor($car->brand, $locale);
        $categoryTranslation = $this->translationFor($car->category, $locale);
        $gearTypeTranslation = $this->translationFor($car->gearType, $locale);

        $dailyMainPrice = $car->daily_main_price ? (float) $car->daily_main_price : null;
        $dailyDiscountPrice = $car->daily_discount_price ? (float) $car->daily_discount_price : null;
        $effectiveDailyPrice = $dailyDiscountPrice ?? $dailyMainPrice;

        $discountRate = null;
        if ($dailyMainPrice && $dailyDiscountPrice && $dailyDiscountPrice < $dailyMainPrice) {
            $discountRate = (int) ceil((($dailyMainPrice - $dailyDiscountPrice) / $dailyMainPrice) * 100);
        }

        return [
            'id' => $car->id,
            'details_url' => route('website.cars.show', ['car' => $car->id]),
            'name' => $carTranslation?->name ?? __('website.common.car'),
            'brand_name' => $brandTranslation?->name,
            'category_name' => $categoryTranslation?->name,
            'gear_type_name' => $gearTypeTranslation?->name,
            'year' => $car->year?->year,
            'image_path' => $car->default_image_path,
            'status' => $car->status,
            'is_featured' => (bool) $car->is_featured,
            'daily_price' => $effectiveDailyPrice ? (int) ceil($effectiveDailyPrice * $currencyRate) : null,
            'daily_main_price' => $dailyMainPrice ? (int) ceil($dailyMainPrice * $currencyRate) : null,
            'daily_mileage_included' => $car->daily_mileage_included,
            'passenger_capacity' => $car->passenger_capacity,
            'door_count' => $car->door_count,
            'discount_rate' => $discountRate,
            'currency_symbol' => $currencySymbol,
        ];
    }

    private function mapCarDetailsData(Car $car, string $locale, float $currencyRate, string $currencySymbol): array
    {
        $carTranslation = $this->translationFor($car, $locale);
        $brandTranslation = $this->translationFor($car->brand, $locale);
        $categoryTranslation = $this->translationFor($car->category, $locale);
        $gearTypeTranslation = $this->translationFor($car->gearType, $locale);
        $colorTranslation = $this->translationFor($car->color, $locale);
        $carModelTranslation = $this->translationFor($car->carModel, $locale);

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
            'category_name' => $categoryTranslation?->name,
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

        return [$currencyRate, $currencySymbol];
    }

    private function translationFor($model, string $locale): mixed
    {
        if (!$model || !isset($model->translations) || !($model->translations instanceof Collection)) {
            return null;
        }

        return $model->translations->firstWhere('locale', $locale) ?? $model->translations->first();
    }
}
