<?php

namespace App\Jobs;

use App\Models\Car;
use App\Services\MetaCatalogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncCarsToMetaCatalog implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $timeout = 300;

    public function __construct(
        private readonly ?array $carIds = null,
        private readonly string $mode = 'all',
        private readonly ?int $requestedBy = null
    ) {
    }

    public function handle(MetaCatalogService $catalogService): void
    {
        $query = Car::query()
            ->with([
                'translations',
                'brand.translations',
                'category.translations',
                'gearType.translations',
                'year',
                'color.translations',
                'carModel.translations',
            ])
            ->orderBy('id');

        if (is_array($this->carIds) && !empty($this->carIds)) {
            $query->whereIn('id', $this->carIds);
        }

        $query->chunkById(250, function ($cars) use ($catalogService): void {
            $catalogService->syncCars($cars, $this->mode, $this->requestedBy);
        });
    }
}
