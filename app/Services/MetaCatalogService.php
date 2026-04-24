<?php

namespace App\Services;

use App\Models\Car;
use App\Models\MetaCatalogSyncLog;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class MetaCatalogService
{
    public function isConfigured(): bool
    {
        return filled($this->catalogId()) && filled($this->accessToken());
    }

    public function syncCars(iterable $cars, string $mode = 'selected', ?int $requestedBy = null): MetaCatalogSyncLog
    {
        $cars = collect($cars)->filter(fn ($car): bool => $car instanceof Car)->values();

        $log = MetaCatalogSyncLog::create([
            'mode' => $mode,
            'car_id' => $cars->count() === 1 ? $cars->first()->id : null,
            'requested_by' => $requestedBy,
            'status' => 'running',
            'total_count' => $cars->count(),
            'started_at' => now(),
            'message' => 'Catalog sync started.',
        ]);

        if ($cars->isEmpty()) {
            return $this->finishLog($log, 'success', 0, 0, 'No cars matched this sync request.', []);
        }

        if (!$this->isConfigured()) {
            return $this->finishLog(
                $log,
                'failed',
                0,
                $cars->count(),
                'Meta catalog credentials are missing. Set META_CATALOG_ID and META_CATALOG_ACCESS_TOKEN.',
                []
            );
        }

        $responses = [];
        $successCount = 0;
        $failedCount = 0;
        $client = new Client([
            'timeout' => 45,
            'connect_timeout' => 15,
        ]);

        foreach ($cars->map(fn (Car $car): array => $this->buildCatalogRequest($car))->chunk(100) as $chunkIndex => $requests) {
            try {
                $response = $client->post($this->endpoint(), [
                    'form_params' => [
                        'access_token' => $this->accessToken(),
                        'item_type' => 'PRODUCT_ITEM',
                        'requests' => json_encode($requests->values()->all(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    ],
                ]);

                $body = json_decode((string) $response->getBody(), true) ?: [];
                $responses[] = [
                    'chunk' => $chunkIndex + 1,
                    'status_code' => $response->getStatusCode(),
                    'body' => $body,
                ];
                $successCount += $requests->count();
            } catch (Throwable $e) {
                Log::error('Meta catalog sync chunk failed', [
                    'mode' => $mode,
                    'message' => $e->getMessage(),
                ]);

                $responses[] = [
                    'chunk' => $chunkIndex + 1,
                    'error' => $e->getMessage(),
                ];
                $failedCount += $requests->count();
            }
        }

        $status = match (true) {
            $failedCount === 0 => 'success',
            $successCount > 0 => 'partial',
            default => 'failed',
        };

        $message = match ($status) {
            'success' => 'Meta catalog sync completed successfully.',
            'partial' => 'Meta catalog sync completed with some failed batches.',
            default => 'Meta catalog sync failed.',
        };

        return $this->finishLog($log, $status, $successCount, $failedCount, $message, $responses);
    }

    public function syncCar(Car $car, ?int $requestedBy = null): MetaCatalogSyncLog
    {
        return $this->syncCars([$car], 'single', $requestedBy);
    }

    private function finishLog(
        MetaCatalogSyncLog $log,
        string $status,
        int $successCount,
        int $failedCount,
        string $message,
        array $payload
    ): MetaCatalogSyncLog {
        $log->update([
            'status' => $status,
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'message' => $message,
            'response_payload' => $payload,
            'finished_at' => now(),
        ]);

        return $log->refresh();
    }

    private function buildCatalogRequest(Car $car): array
    {
        $car->loadMissing([
            'translations',
            'brand.translations',
            'category.translations',
            'gearType.translations',
            'year',
            'color.translations',
            'carModel.translations',
        ]);

        $name = $this->translationValue($car, 'name') ?: 'Rental Car #' . $car->id;
        $description = $this->translationValue($car, 'description')
            ?: trim(implode(' ', array_filter([
                $this->translationValue($car->brand, 'name'),
                $this->translationValue($car->carModel, 'name'),
                $car->year?->year,
                'available for rent from Afandina Car Rental.',
            ])));

        return [
            'method' => 'UPDATE',
            'retailer_id' => $this->retailerId($car),
            'data' => array_filter([
                'id' => $this->retailerId($car),
                'title' => Str::limit($name, 150, ''),
                'description' => Str::limit(strip_tags((string) $description), 5000, ''),
                'availability' => $car->is_active && $car->status === 'available' ? 'in stock' : 'out of stock',
                'condition' => 'used',
                'price' => $this->price($car),
                'currency' => $this->currency(),
                'link' => $this->carUrl($car),
                'image_link' => $this->assetUrl($car->default_image_path),
                'brand' => $this->translationValue($car->brand, 'name'),
                'product_type' => $this->translationValue($car->category, 'name'),
                'custom_label_0' => $this->translationValue($car->gearType, 'name'),
                'custom_label_1' => $car->year?->year ? (string) $car->year->year : null,
                'custom_label_2' => $this->translationValue($car->color, 'name'),
            ], fn ($value): bool => filled($value)),
        ];
    }

    private function translationValue($model, string $field): ?string
    {
        if (!$model || !isset($model->translations) || !($model->translations instanceof Collection)) {
            return null;
        }

        $translation = $model->translations->firstWhere('locale', 'en')
            ?? $model->translations->first();

        $value = $translation?->{$field};

        return filled($value) ? (string) $value : null;
    }

    private function retailerId(Car $car): string
    {
        return trim((string) config('services.meta_catalog.retailer_prefix', 'afandina-car')) . '-' . $car->id;
    }

    private function price(Car $car): string
    {
        $price = $car->daily_discount_price ?: $car->daily_main_price ?: $car->monthly_discount_price ?: $car->monthly_main_price ?: 0;

        return number_format((float) $price, 2, '.', '') . ' ' . $this->currency();
    }

    private function currency(): string
    {
        return (string) config('services.meta_catalog.currency', 'AED');
    }

    private function carUrl(Car $car): string
    {
        $slug = $car->translations->firstWhere('locale', 'en')?->slug
            ?? $car->translations->first(fn ($translation) => filled($translation->slug))?->slug
            ?? $car->slug
            ?? null;

        return filled($slug)
            ? route('website.cars.show', ['car' => $slug])
            : route('website.cars.index');
    }

    private function assetUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (Str::startsWith($path, ['storage/', 'website/', 'admin/'])) {
            return url($path);
        }

        return url('storage/' . $path);
    }

    private function endpoint(): string
    {
        $version = trim((string) config('services.meta_catalog.graph_version', 'v20.0'), '/');
        $batchEndpoint = trim((string) config('services.meta_catalog.batch_endpoint', 'items_batch'), '/');

        return "https://graph.facebook.com/{$version}/{$this->catalogId()}/{$batchEndpoint}";
    }

    private function catalogId(): ?string
    {
        return config('services.meta_catalog.catalog_id');
    }

    private function accessToken(): ?string
    {
        return config('services.meta_catalog.access_token');
    }
}
