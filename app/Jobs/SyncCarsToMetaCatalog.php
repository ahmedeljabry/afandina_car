<?php

namespace App\Jobs;

use App\Models\Car;
use App\Models\MetaCatalogSyncLog;
use App\Services\MetaCatalogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SyncCarsToMetaCatalog implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $timeout = 300;

    public function __construct(
        private readonly int $logId,
        private readonly ?array $carIds = null,
        private readonly string $mode = 'all',
        private readonly ?int $requestedBy = null
    ) {
    }

    public function handle(MetaCatalogService $catalogService): void
    {
        $log = MetaCatalogSyncLog::query()->findOrFail($this->logId);

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

        $totalCount = (clone $query)->count();
        $catalogService->markLogAsRunning($log, $totalCount);

        if ($totalCount === 0) {
            $catalogService->completeExistingLog($log, 0, 0, []);
            return;
        }

        if (!$catalogService->isConfigured()) {
            $catalogService->failExistingLog(
                $log,
                'Meta catalog credentials are missing. Set META_CATALOG_ID and META_CATALOG_ACCESS_TOKEN.'
            );
            return;
        }

        $responses = [];
        $successCount = 0;
        $failedCount = 0;

        $query->chunkById(250, function ($cars) use ($catalogService, &$responses, &$successCount, &$failedCount): void {
            $result = $catalogService->syncBatch($cars, $this->mode);
            $responses = array_merge($responses, $result['responses']);
            $successCount += $result['success_count'];
            $failedCount += $result['failed_count'];
        });

        $catalogService->completeExistingLog($log, $successCount, $failedCount, $responses);
    }

    public function failed(?Throwable $exception): void
    {
        $log = MetaCatalogSyncLog::query()->find($this->logId);

        if (!$log) {
            return;
        }

        $log->update([
            'status' => 'failed',
            'message' => $exception?->getMessage() ?: 'Meta catalog sync failed unexpectedly.',
            'finished_at' => now(),
        ]);
    }
}
