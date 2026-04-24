<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Jobs\SyncCarsToMetaCatalog;
use App\Models\Car;
use App\Models\MetaCatalogSyncLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MetaCatalogController extends Controller
{
    public function index()
    {
        $cars = Car::query()
            ->with(['translations', 'brand.translations', 'carModel.translations'])
            ->latest('id')
            ->get()
            ->map(function (Car $car): array {
                $translation = $car->translations->firstWhere('locale', 'en') ?? $car->translations->first();
                $brand = $car->brand?->translations?->firstWhere('locale', 'en')
                    ?? $car->brand?->translations?->first();
                $model = $car->carModel?->translations?->firstWhere('locale', 'en')
                    ?? $car->carModel?->translations?->first();

                return [
                    'id' => $car->id,
                    'name' => trim(implode(' ', array_filter([
                        $translation?->name,
                        $brand?->name ? '(' . $brand->name . ')' : null,
                        $model?->name ? '- ' . $model->name : null,
                    ]))) ?: 'Car #' . $car->id,
                ];
            });

        $logs = MetaCatalogSyncLog::query()
            ->with(['car.translations', 'requestedBy'])
            ->latest('id')
            ->paginate(12);

        return view('pages.admin.meta_catalog.index', [
            'cars' => $cars,
            'logs' => $logs,
            'isConfigured' => filled(config('services.meta_catalog.catalog_id'))
                && filled(config('services.meta_catalog.access_token')),
            'catalogId' => config('services.meta_catalog.catalog_id'),
            'graphVersion' => config('services.meta_catalog.graph_version'),
            'batchEndpoint' => config('services.meta_catalog.batch_endpoint'),
        ]);
    }

    public function syncAll(): RedirectResponse
    {
        $carCount = Car::query()->count();

        if ($carCount === 0) {
            MetaCatalogSyncLog::create([
                'mode' => 'all',
                'requested_by' => auth()->id(),
                'status' => 'success',
                'total_count' => 0,
                'message' => 'No cars matched this sync request.',
                'started_at' => now(),
                'finished_at' => now(),
            ]);

            return back()->with('info', 'No cars are available to sync right now.');
        }

        $log = MetaCatalogSyncLog::create([
            'mode' => 'all',
            'requested_by' => auth()->id(),
            'status' => 'queued',
            'total_count' => $carCount,
            'message' => 'Meta catalog sync is queued and waiting to start.',
        ]);

        SyncCarsToMetaCatalog::dispatch($log->id, null, 'all', auth()->id());

        return back()->with('success', 'Meta catalog sync has been sent. You will be notified when it starts and finishes.');
    }

    public function syncSelected(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
        ]);

        $carId = (int) $validated['car_id'];
        $log = MetaCatalogSyncLog::create([
            'mode' => 'single',
            'car_id' => $carId,
            'requested_by' => auth()->id(),
            'status' => 'queued',
            'total_count' => 1,
            'message' => 'Meta catalog sync is queued for the selected car.',
        ]);

        SyncCarsToMetaCatalog::dispatch($log->id, [$carId], 'single', auth()->id());

        return back()->with('success', 'Selected car sync has been sent. You will be notified when it starts and finishes.');
    }

    public function syncCar(Car $car): RedirectResponse
    {
        $log = MetaCatalogSyncLog::create([
            'mode' => 'single',
            'car_id' => $car->id,
            'requested_by' => auth()->id(),
            'status' => 'queued',
            'total_count' => 1,
            'message' => 'Meta catalog sync is queued for this car.',
        ]);

        SyncCarsToMetaCatalog::dispatch($log->id, [$car->id], 'single', auth()->id());

        return back()->with('success', 'Car sync has been sent. You will be notified when it starts and finishes.');
    }

    public function notificationFeed(): JsonResponse
    {
        $logs = MetaCatalogSyncLog::query()
            ->with('car.translations')
            ->where('requested_by', auth()->id())
            ->where('created_at', '>=', now()->subDays(7))
            ->latest('updated_at')
            ->take(8)
            ->get()
            ->values();

        return response()->json([
            'notifications' => $logs->map(function (MetaCatalogSyncLog $log): array {
                return [
                    'id' => $log->id,
                    'status' => $log->status,
                    'type' => $log->notificationType(),
                    'title' => $log->notificationTitle(),
                    'message' => $log->notificationMessage(),
                    'time' => $log->notificationTimeLabel(),
                    'updated_at' => optional($log->updated_at)->toISOString(),
                    'url' => route('admin.meta-catalog.index'),
                    'unread' => $log->isUnreadNotification(),
                ];
            })->all(),
            'unread_count' => $logs->filter(fn (MetaCatalogSyncLog $log): bool => $log->isUnreadNotification())->count(),
        ]);
    }
}
