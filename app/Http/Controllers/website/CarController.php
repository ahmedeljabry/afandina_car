<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Car;
use App\Models\Currency;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale() ?? 'en';
        [$currencyRate, $currencySymbol] = $this->resolveCurrencyContext();

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
        abort_unless((bool) $car->is_active, 404);

        $locale = app()->getLocale() ?? 'en';
        [$currencyRate, $currencySymbol] = $this->resolveCurrencyContext();

        $car->load([
            'translations',
            'brand.translations',
            'category.translations',
            'gearType.translations',
            'year',
            'color.translations',
            'carModel.translations',
            'features.translations',
            'features.icon',
            'images',
        ]);

        $carTranslation = $this->translationFor($car, $locale);
        $brandTranslation = $this->translationFor($car->brand, $locale);
        $categoryTranslation = $this->translationFor($car->category, $locale);
        $gearTypeTranslation = $this->translationFor($car->gearType, $locale);
        $colorTranslation = $this->translationFor($car->color, $locale);
        $carModelTranslation = $this->translationFor($car->carModel, $locale);

        $features = $car->features
            ->map(function ($feature) use ($locale) {
                $translation = $this->translationFor($feature, $locale);
                $name = $translation?->name;

                if (blank($name)) {
                    return null;
                }

                return [
                    'name' => $name,
                    'icon_class' => $feature->icon?->icon_class,
                ];
            })
            ->filter()
            ->values();

        $galleryPaths = collect($car->images)
            ->filter(function ($image) {
                return ($image->type ?? 'image') === 'image' && filled($image->file_path);
            })
            ->pluck('file_path');

        $primaryImagePath = $car->default_image_path ?: $galleryPaths->first();
        $imagePaths = $galleryPaths
            ->prepend($primaryImagePath)
            ->filter()
            ->unique()
            ->values();

        $specifications = collect([
            ['label' => __('website.car_details.specifications.brand'), 'value' => $brandTranslation?->name, 'icon' => 'img/specification/specification-icon-2.svg'],
            ['label' => __('website.car_details.specifications.category'), 'value' => $categoryTranslation?->name, 'icon' => 'img/specification/specification-icon-1.svg'],
            ['label' => __('website.car_details.specifications.model'), 'value' => $carModelTranslation?->name, 'icon' => 'img/specification/specification-icon-8.svg'],
            ['label' => __('website.car_details.specifications.gear_type'), 'value' => $gearTypeTranslation?->name, 'icon' => 'img/specification/specification-icon-3.svg'],
            ['label' => __('website.car_details.specifications.color'), 'value' => $colorTranslation?->name, 'icon' => 'img/specification/specification-icon-4.svg'],
            ['label' => __('website.car_details.specifications.year'), 'value' => $car->year?->year, 'icon' => 'img/specification/specification-icon-5.svg'],
            ['label' => __('website.car_details.specifications.doors'), 'value' => $car->door_count ? __('website.units.doors', ['count' => $car->door_count]) : null, 'icon' => 'img/specification/specification-icon-6.svg'],
            ['label' => __('website.car_details.specifications.passengers'), 'value' => $car->passenger_capacity ? __('website.units.persons', ['count' => $car->passenger_capacity]) : null, 'icon' => 'img/specification/specification-icon-7.svg'],
            ['label' => __('website.car_details.specifications.luggage'), 'value' => $car->luggage_capacity ? __('website.units.bags', ['count' => $car->luggage_capacity]) : null, 'icon' => 'img/specification/specification-icon-9.svg'],
        ])->filter(fn (array $item) => filled($item['value']))->values();

        $statusKey = $car->status === 'available' ? 'available' : 'not_available';
        $statusLabel = __('website.status.' . $statusKey);

        $highlights = collect([
            $car->insurance_included ? __('website.car_details.highlights.insurance_included') : null,
            $car->free_delivery ? __('website.car_details.highlights.free_delivery') : null,
            $car->crypto_payment_accepted ? __('website.car_details.highlights.crypto_payment') : null,
            filled($car->status) ? $statusLabel : null,
        ])->filter()->values();

        $pricing = collect([
            $this->buildPriceRow(
                __('website.car_details.pricing.per_day'),
                $car->daily_main_price,
                $car->daily_discount_price,
                $car->daily_mileage_included,
                $currencyRate,
                $currencySymbol
            ),
            $this->buildPriceRow(
                __('website.car_details.pricing.per_week'),
                $car->weekly_main_price,
                $car->weekly_discount_price,
                $car->weekly_mileage_included,
                $currencyRate,
                $currencySymbol
            ),
            $this->buildPriceRow(
                __('website.car_details.pricing.per_month'),
                $car->monthly_main_price,
                $car->monthly_discount_price,
                $car->monthly_mileage_included,
                $currencyRate,
                $currencySymbol
            ),
        ])->filter()->values();

        $carDetails = [
            'id' => $car->id,
            'name' => $carTranslation?->name ?? __('website.common.car'),
            'brand_name' => $brandTranslation?->name,
            'category_name' => $categoryTranslation?->name,
            'model_name' => $carModelTranslation?->name,
            'gear_type_name' => $gearTypeTranslation?->name,
            'year' => $car->year?->year,
            'status' => $car->status,
            'status_label' => $statusLabel,
            'description' => $carTranslation?->description,
            'long_description' => $carTranslation?->long_description,
            'image_paths' => $imagePaths,
            'specifications' => $specifications,
            'features' => $features,
            'highlights' => $highlights,
            'pricing' => $pricing,
            'listed_on' => optional($car->created_at)->format('d M, Y'),
        ];

        $relatedCarsQuery = Car::query()
            ->with([
                'translations',
                'brand.translations',
                'category.translations',
                'gearType.translations',
                'year',
            ])
            ->where('is_active', true)
            ->where('id', '!=', $car->id);

        if (filled($car->category_id)) {
            $relatedCarsQuery->where('category_id', $car->category_id);
        }

        $relatedCarsRaw = $relatedCarsQuery->latest('id')->limit(4)->get();

        if ($relatedCarsRaw->isEmpty()) {
            $relatedCarsRaw = Car::query()
                ->with([
                    'translations',
                    'brand.translations',
                    'category.translations',
                    'gearType.translations',
                    'year',
                ])
                ->where('is_active', true)
                ->where('id', '!=', $car->id)
                ->latest('id')
                ->limit(4)
                ->get();
        }

        $relatedCars = $relatedCarsRaw
            ->map(fn (Car $relatedCar) => $this->mapCarCardData($relatedCar, $locale, $currencyRate, $currencySymbol));

        return view('website.car-details', compact('carDetails', 'relatedCars'));
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

    private function buildPriceRow(
        string $label,
        $mainPrice,
        $discountPrice,
        $mileage,
        float $currencyRate,
        string $currencySymbol
    ): ?array {
        $main = filled($mainPrice) ? (float) $mainPrice : null;
        if (!$main) {
            return null;
        }

        $discount = filled($discountPrice) ? (float) $discountPrice : null;
        $effective = $discount && $discount < $main ? $discount : $main;

        return [
            'label' => $label,
            'price' => (int) ceil($effective * $currencyRate),
            'main_price' => (int) ceil($main * $currencyRate),
            'mileage' => filled($mileage) ? (int) $mileage : null,
            'currency' => $currencySymbol,
        ];
    }

    private function resolveCurrencyContext(): array
    {
        $currency = Currency::with('translations')->where('is_default', true)->first();
        if (!$currency) {
            $currency = Currency::with('translations')->first();
        }

        $currencyRate = (float) ($currency?->exchange_rate ?? 1);
        if ($currencyRate <= 0) {
            $currencyRate = 1;
        }

        $currencySymbol = $currency?->symbol ?? '$';

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
