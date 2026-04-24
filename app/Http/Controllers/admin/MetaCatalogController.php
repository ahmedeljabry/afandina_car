<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Jobs\SyncCarsToMetaCatalog;
use App\Models\Car;
use App\Models\MetaCatalogSyncLog;
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
        SyncCarsToMetaCatalog::dispatch(null, 'all', auth()->id());

        return back()->with('success', 'Meta catalog sync for all cars has been queued.');
    }

    public function syncSelected(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
        ]);

        SyncCarsToMetaCatalog::dispatch([(int) $validated['car_id']], 'single', auth()->id());

        return back()->with('success', 'Meta catalog sync for the selected car has been queued.');
    }

    public function syncCar(Car $car): RedirectResponse
    {
        SyncCarsToMetaCatalog::dispatch([$car->id], 'single', auth()->id());

        return back()->with('success', 'Meta catalog sync for this car has been queued.');
    }
}
