<?php

namespace App\Traits;

use App\Models\Car;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait DeduplicatesCars
{
    protected function uniqueRepresentativeCarIds(Builder $query, string $representative = 'min'): Collection
    {
        $aggregate = strtolower($representative) === 'max' ? 'MAX' : 'MIN';
        $translationAlias = 'car_translation_dedupe';
        $signatureExpression = $this->carDeduplicationSignatureExpression('cars', $translationAlias);

        return (clone $query)
            ->leftJoin("car_translations as {$translationAlias}", function ($join) use ($translationAlias) {
                $join->on("{$translationAlias}.car_id", '=', 'cars.id')
                    ->where("{$translationAlias}.locale", '=', 'en');
            })
            ->selectRaw("{$aggregate}(cars.id) as car_id")
            ->groupBy(DB::raw($signatureExpression))
            ->pluck('car_id')
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->values();
    }

    protected function uniqueCarCountsByColumn(string $column, ?Builder $query = null): Collection
    {
        $allowedColumns = ['brand_id', 'category_id', 'year_id', 'car_model_id'];

        if (!in_array($column, $allowedColumns, true)) {
            throw new \InvalidArgumentException("Unsupported car grouping column [{$column}].");
        }

        $translationAlias = 'car_translation_dedupe';
        $signatureExpression = $this->carDeduplicationSignatureExpression('cars', $translationAlias);
        $baseQuery = $query ? clone $query : Car::query();

        return $baseQuery
            ->leftJoin("car_translations as {$translationAlias}", function ($join) use ($translationAlias) {
                $join->on("{$translationAlias}.car_id", '=', 'cars.id')
                    ->where("{$translationAlias}.locale", '=', 'en');
            })
            ->whereNotNull("cars.{$column}")
            ->selectRaw("cars.{$column} as grouping_id")
            ->selectRaw("COUNT(DISTINCT {$signatureExpression}) as cars_count")
            ->groupBy("cars.{$column}")
            ->pluck('cars_count', 'grouping_id')
            ->mapWithKeys(fn ($count, $groupingId) => [(int) $groupingId => (int) $count]);
    }

    protected function uniqueCarCount(Builder $query): int
    {
        return $this->uniqueRepresentativeCarIds($query)->count();
    }

    protected function dedupeCarsCollection(Collection $cars, ?int $limit = null): Collection
    {
        $deduped = $cars
            ->unique(fn ($car) => $car instanceof Car ? $this->carDeduplicationKey($car) : null)
            ->values();

        return $limit !== null ? $deduped->take($limit)->values() : $deduped;
    }

    protected function carDeduplicationKey(Car $car): string
    {
        $translations = $car->relationLoaded('translations')
            ? $car->translations
            : $car->translations()->get();

        $name = trim((string) (
            $translations->firstWhere('locale', 'en')?->name
            ?? $translations->first()?->name
            ?? $car->slug
            ?? ('car-' . $car->id)
        ));

        return implode('|', [
            Str::lower($name),
            $this->normalizeCarDeduplicationValue($car->brand_id),
            $this->normalizeCarDeduplicationValue($car->car_model_id),
            $this->normalizeCarDeduplicationValue($car->year_id),
            $this->normalizeCarDeduplicationValue($car->gear_type_id),
            $this->normalizeCarDeduplicationValue($car->color_id),
            $this->normalizeCarDeduplicationValue($car->daily_main_price),
            $this->normalizeCarDeduplicationValue($car->daily_discount_price),
            $this->normalizeCarDeduplicationValue($car->weekly_main_price),
            $this->normalizeCarDeduplicationValue($car->weekly_discount_price),
            $this->normalizeCarDeduplicationValue($car->monthly_main_price),
            $this->normalizeCarDeduplicationValue($car->monthly_discount_price),
            $this->normalizeCarDeduplicationValue($car->door_count),
            $this->normalizeCarDeduplicationValue($car->passenger_capacity),
            $this->normalizeCarDeduplicationValue($car->luggage_capacity),
        ]);
    }

    protected function carDeduplicationSignatureExpression(string $carTable = 'cars', string $translationAlias = 'car_translation_dedupe'): string
    {
        $nameExpression = "COALESCE(NULLIF(LOWER(TRIM({$translationAlias}.name)), ''), CONCAT('car-', {$carTable}.id))";

        $parts = [
            $nameExpression,
            "COALESCE({$carTable}.brand_id, 0)",
            "COALESCE({$carTable}.car_model_id, 0)",
            "COALESCE({$carTable}.year_id, 0)",
            "COALESCE({$carTable}.gear_type_id, 0)",
            "COALESCE({$carTable}.color_id, 0)",
            "COALESCE(CAST({$carTable}.daily_main_price AS CHAR), '')",
            "COALESCE(CAST({$carTable}.daily_discount_price AS CHAR), '')",
            "COALESCE(CAST({$carTable}.weekly_main_price AS CHAR), '')",
            "COALESCE(CAST({$carTable}.weekly_discount_price AS CHAR), '')",
            "COALESCE(CAST({$carTable}.monthly_main_price AS CHAR), '')",
            "COALESCE(CAST({$carTable}.monthly_discount_price AS CHAR), '')",
            "COALESCE({$carTable}.door_count, 0)",
            "COALESCE({$carTable}.passenger_capacity, 0)",
            "COALESCE({$carTable}.luggage_capacity, 0)",
        ];

        return "CONCAT_WS('|', " . implode(', ', $parts) . ')';
    }

    protected function normalizeCarDeduplicationValue(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return is_numeric($value)
            ? number_format((float) $value, 2, '.', '')
            : trim((string) $value);
    }
}
