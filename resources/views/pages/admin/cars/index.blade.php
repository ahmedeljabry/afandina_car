@extends('layouts.admin_layout')

@section('title', __('Cars'))
@include('includes.admin.form_theme')

@section('page-title', __('Cars'))

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Cars') }}</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.cars.create') }}" class="btn btn-primary d-inline-flex align-items-center me-2 mb-2">
        <i class="ti ti-plus me-1"></i>{{ __('Add Car') }}
    </a>
    @if(request()->query())
        <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center mb-2">
            <i class="ti ti-x me-1"></i>{{ __('Clear Filters') }}
        </a>
    @endif
@endsection

@push('styles')
    <style>
        .cars-hero {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 20px;
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 65%, #0ea5e9 100%);
            color: #fff;
            overflow: hidden;
            position: relative;
        }

        .cars-hero::after {
            content: "";
            position: absolute;
            right: -80px;
            top: -80px;
            width: 210px;
            height: 210px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
        }

        .cars-hero .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            margin-right: 8px;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .cars-filter-card,
        .cars-table-card {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
        }

        .cars-filter-card .form-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .cars-filter-card .form-control {
            border-radius: 12px;
        }

        .cars-table-card .table th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .cars-table-card .table td {
            vertical-align: middle;
            border-color: #eef2f7;
        }

        .car-thumb {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 4px 10px;
            font-weight: 600;
            font-size: 12px;
        }

        .status-pill.ok {
            color: #0f766e;
            background: rgba(20, 184, 166, 0.16);
        }

        .status-pill.no {
            color: #b91c1c;
            background: rgba(248, 113, 113, 0.18);
        }

        .flag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .flag-badge {
            border-radius: 999px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 600;
            background: #f1f5f9;
            color: #334155;
        }
    </style>
@endpush

@section('content')
    @php
        $locale = app()->getLocale() ?: 'en';

        $translateName = function ($entity) use ($locale): string {
            if (!$entity || !method_exists($entity, 'translations')) {
                return __('N/A');
            }

            $translations = $entity->relationLoaded('translations')
                ? $entity->translations
                : $entity->translations()->get();

            $translation = $translations->firstWhere('locale', $locale) ?? $translations->first();

            return filled($translation?->name) ? $translation->name : __('N/A');
        };

        $resolveImage = function (?string $path): string {
            if (blank($path)) {
                return asset('admin/assets/img/car/car-01.jpg');
            }

            if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '//'])) {
                return $path;
            }

            if (\Illuminate\Support\Str::startsWith($path, 'storage/')) {
                return asset($path);
            }

            return asset('storage/' . ltrim($path, '/'));
        };

        $itemsCollection = $items instanceof \Illuminate\Pagination\AbstractPaginator
            ? collect($items->items())
            : collect($items);

        $totalCars = $items instanceof \Illuminate\Pagination\AbstractPaginator ? $items->total() : $itemsCollection->count();
        $activeCars = $itemsCollection->where('is_active', true)->count();
        $availableCars = $itemsCollection->where('status', 'available')->count();
        $featuredCars = $itemsCollection->where('is_featured', true)->count();

        $selectedBrand = request()->filled('brand')
            ? $brands->firstWhere('id', (int) request('brand'))
            : null;
    @endphp

    <div class="card cars-hero mb-3">
        <div class="card-body p-4 position-relative">
            <div class="d-flex justify-content-between flex-wrap gap-3">
                <div>
                    <h3 class="mb-2">{{ __('Fleet Management') }}</h3>
                    <p class="mb-0 text-white-50">{{ __('Manage your cars, refine filters, and keep stock visibility aligned with availability.') }}</p>
                </div>
                <div class="text-md-end">
                    <span class="chip"><i class="ti ti-car"></i>{{ __('Total: :count', ['count' => $totalCars]) }}</span>
                    <span class="chip"><i class="ti ti-check"></i>{{ __('Active (page): :count', ['count' => $activeCars]) }}</span>
                    <span class="chip"><i class="ti ti-circle-check"></i>{{ __('Available (page): :count', ['count' => $availableCars]) }}</span>
                    <span class="chip"><i class="ti ti-star"></i>{{ __('Featured (page): :count', ['count' => $featuredCars]) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card cars-filter-card mb-3">
        <div class="card-header border-0 pb-0">
            <h5 class="mb-0">{{ __('Filters') }}</h5>
        </div>
        <div class="card-body">
            <form id="carFilterForm" method="GET" action="{{ route('admin.cars.index') }}" class="row g-3">
                <div class="col-lg-2 col-md-4">
                    <label for="brand" class="form-label">{{ __('Brand') }}</label>
                    <select name="brand" id="brand" class="form-control select2">
                        <option value="">{{ __('All Brands') }}</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ (string) request('brand') === (string) $brand->id ? 'selected' : '' }}>
                                {{ $translateName($brand) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-4">
                    <label for="model" class="form-label">{{ __('Model') }}</label>
                    <select name="model" id="model" class="form-control select2" {{ $selectedBrand ? '' : 'disabled' }}>
                        <option value="">{{ __('All Models') }}</option>
                        @if($selectedBrand)
                            @foreach($selectedBrand->carModels as $model)
                                <option value="{{ $model->id }}" {{ (string) request('model') === (string) $model->id ? 'selected' : '' }}>
                                    {{ $translateName($model) }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-lg-2 col-md-4">
                    <label for="category" class="form-label">{{ __('Category') }}</label>
                    <select name="category" id="category" class="form-control select2">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (string) request('category') === (string) $category->id ? 'selected' : '' }}>
                                {{ $translateName($category) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-4">
                    <label for="year" class="form-label">{{ __('Year') }}</label>
                    <select name="year" id="year" class="form-control select2">
                        <option value="">{{ __('All Years') }}</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ (string) request('year') === (string) $year->id ? 'selected' : '' }}>
                                {{ $year->year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label for="search" class="form-label">{{ __('Search') }}</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        class="form-control"
                        value="{{ request('search') }}"
                        placeholder="{{ __('Search by car name or description') }}"
                    >
                </div>

                <div class="col-lg-1 col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark w-100 d-inline-flex align-items-center justify-content-center">
                        <i class="ti ti-filter me-1"></i>{{ __('Go') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card cars-table-card">
        <div class="card-header d-flex align-items-center justify-content-between border-0 pb-0">
            <h5 class="mb-0">{{ __('Cars List') }}</h5>
            <small class="text-muted">{{ __('Showing :count item(s) on this page', ['count' => $itemsCollection->count()]) }}</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Car') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Year') }}</th>
                            <th>{{ __('Prices') }}</th>
                            <th>{{ __('Flags') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            @php
                                $carName = $translateName($item);
                                $brandName = $translateName($item->brand);
                                $modelName = $translateName($item->carModel);
                                $categoryName = $translateName($item->category);
                                $rowNumber = ($items->currentPage() - 1) * $items->perPage() + $loop->iteration;
                            @endphp
                            <tr>
                                <td>{{ $rowNumber }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $resolveImage($item->default_image_path) }}" alt="{{ $carName }}" class="car-thumb me-2">
                                        <div>
                                            <h6 class="mb-1 fs-14">{{ $carName }}</h6>
                                            <small class="text-muted">{{ $brandName }} @if($modelName !== __('N/A')) / {{ $modelName }} @endif</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $categoryName }}</td>
                                <td>{{ optional($item->year)->year ?? __('N/A') }}</td>
                                <td>
                                    <div class="small">
                                        <div>{{ __('Daily') }}: <strong>{{ is_null($item->daily_main_price) ? '-' : number_format((float) $item->daily_main_price, 2) }}</strong></div>
                                        <div>{{ __('Weekly') }}: <strong>{{ is_null($item->weekly_main_price) ? '-' : number_format((float) $item->weekly_main_price, 2) }}</strong></div>
                                        <div>{{ __('Monthly') }}: <strong>{{ is_null($item->monthly_main_price) ? '-' : number_format((float) $item->monthly_main_price, 2) }}</strong></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flag-list">
                                        @if($item->is_featured)
                                            <span class="flag-badge">{{ __('Featured') }}</span>
                                        @endif
                                        @if($item->is_flash_sale)
                                            <span class="flag-badge">{{ __('Flash Sale') }}</span>
                                        @endif
                                        @if($item->free_delivery)
                                            <span class="flag-badge">{{ __('Free Delivery') }}</span>
                                        @endif
                                        @if($item->insurance_included)
                                            <span class="flag-badge">{{ __('Insurance') }}</span>
                                        @endif
                                        @if(!$item->is_featured && !$item->is_flash_sale && !$item->free_delivery && !$item->insurance_included)
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($item->is_active)
                                        <span class="status-pill ok"><i class="ti ti-circle-check"></i>{{ __('Active') }}</span>
                                    @else
                                        <span class="status-pill no"><i class="ti ti-circle-x"></i>{{ __('Inactive') }}</span>
                                    @endif

                                    <div class="mt-1">
                                        @if($item->status === 'available')
                                            <span class="badge bg-success-subtle text-success">{{ __('Available') }}</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">{{ __('Not Available') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.cars.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="{{ __('Edit') }}">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.cars.edit_images', $item->id) }}" class="btn btn-sm btn-outline-info" title="{{ __('Images') }}">
                                            <i class="ti ti-photo"></i>
                                        </a>
                                        <form action="{{ route('admin.cars.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this car?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Delete') }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">{{ __('No cars found for the selected filters.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($items instanceof \Illuminate\Pagination\AbstractPaginator)
                <div class="mt-3">
                    {{ $items->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2({ width: '100%' });
            }

            const $brand = $('#brand');
            const $model = $('#model');
            const selectedModelId = @json(request('model'));
            const modelsEndpointTemplate = @json(route('admin.cars.models', ['brand' => '__brand__']));
            const allModelsLabel = @json(__('All Models'));

            function resetModels() {
                $model.empty().append(new Option(allModelsLabel, ''));
                $model.prop('disabled', true);
            }

            function loadModels(brandId, selectedId = null) {
                resetModels();

                if (!brandId) {
                    return;
                }

                $model.prop('disabled', false);

                $.get(modelsEndpointTemplate.replace('__brand__', brandId), function (models) {
                    models.forEach(function (model) {
                        const isSelected = selectedId && String(selectedId) === String(model.id);
                        const option = new Option(model.name, model.id, false, isSelected);
                        $model.append(option);
                    });

                    if (typeof $.fn.select2 !== 'undefined') {
                        $model.trigger('change.select2');
                    }
                });
            }

            const initialBrand = $brand.val();
            if (initialBrand) {
                loadModels(initialBrand, selectedModelId);
            } else {
                resetModels();
            }

            $brand.on('change', function () {
                loadModels($(this).val(), null);
            });
        });
    </script>
@endpush
