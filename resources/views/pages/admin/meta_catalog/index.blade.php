@extends('layouts.admin_layout')

@section('title', __('Meta Catalog Sync'))
@section('page-title', __('Meta Catalog Sync'))

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Meta Catalog Sync') }}</li>
@endsection

@section('page-actions')
    <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center">
        <i class="ti ti-arrow-left me-1"></i>{{ __('Back to Cars') }}
    </a>
@endsection

@push('styles')
    <style>
        .catalog-hero {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 20px;
            background: linear-gradient(135deg, #111827 0%, #0f766e 56%, #14b8a6 100%);
            color: #fff;
            overflow: hidden;
            position: relative;
        }

        .catalog-card {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.07);
        }

        .catalog-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            border-radius: 999px;
            padding: 6px 12px;
            font-weight: 700;
            font-size: 12px;
        }

        .catalog-status.ready {
            color: #0f766e;
            background: rgba(20, 184, 166, 0.14);
        }

        .catalog-status.missing {
            color: #b91c1c;
            background: rgba(248, 113, 113, 0.16);
        }

        .log-pill {
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .log-pill.success {
            color: #0f766e;
            background: rgba(20, 184, 166, 0.14);
        }

        .log-pill.running,
        .log-pill.queued {
            color: #1d4ed8;
            background: rgba(59, 130, 246, 0.14);
        }

        .log-pill.partial {
            color: #92400e;
            background: rgba(251, 191, 36, 0.22);
        }

        .log-pill.failed {
            color: #b91c1c;
            background: rgba(248, 113, 113, 0.16);
        }
    </style>
@endpush

@section('content')
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card catalog-hero mb-3">
        <div class="card-body p-4 position-relative">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <h3 class="mb-2">{{ __('Facebook / Meta Catalog') }}</h3>
                    <p class="mb-0 text-white-50">
                        {{ __('Push current car availability, pricing, images, and listing links to your Meta catalog from one place.') }}
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    @if($isConfigured)
                        <span class="catalog-status ready"><i class="ti ti-circle-check"></i>{{ __('Configured') }}</span>
                    @else
                        <span class="catalog-status missing"><i class="ti ti-alert-triangle"></i>{{ __('Needs credentials') }}</span>
                    @endif
                    <div class="small mt-2 text-white-50">
                        {{ __('Graph: :version / :endpoint', ['version' => $graphVersion, 'endpoint' => $batchEndpoint]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @unless($isConfigured)
        <div class="alert alert-warning">
            {{ __('Add META_CATALOG_ID and META_CATALOG_ACCESS_TOKEN to your environment before running a live sync.') }}
        </div>
    @endunless

    <div class="row g-3 mb-3">
        <div class="col-lg-5">
            <div class="card catalog-card h-100">
                <div class="card-header border-0 pb-0">
                    <h5 class="mb-0">{{ __('Sync All Cars') }}</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">{{ __('Queue one background job that sends every car in your fleet to Meta in safe batches.') }}</p>
                    <form method="POST" action="{{ route('admin.meta-catalog.sync-all') }}">
                        @csrf
                        <button type="submit" class="btn btn-dark d-inline-flex align-items-center">
                            <i class="ti ti-refresh me-1"></i>{{ __('Sync All Cars') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card catalog-card h-100">
                <div class="card-header border-0 pb-0">
                    <h5 class="mb-0">{{ __('Sync One Car') }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.meta-catalog.sync-selected') }}" class="row g-2 align-items-end">
                        @csrf
                        <div class="col-md-8">
                            <label for="car_id" class="form-label">{{ __('Select Car') }}</label>
                            <select name="car_id" id="car_id" class="form-control select2" required>
                                <option value="">{{ __('Choose a car') }}</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car['id'] }}">{{ $car['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center">
                                <i class="ti ti-upload me-1"></i>{{ __('Sync Selected') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card catalog-card">
        <div class="card-header border-0 pb-0 d-flex align-items-center justify-content-between">
            <h5 class="mb-0">{{ __('Sync History') }}</h5>
            <small class="text-muted">{{ __('Latest jobs') }}</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Mode') }}</th>
                            <th>{{ __('Car') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Counts') }}</th>
                            <th>{{ __('Message') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            @php
                                $carTranslation = $log->car?->translations?->firstWhere('locale', 'en')
                                    ?? $log->car?->translations?->first();
                            @endphp
                            <tr>
                                <td>
                                    <div>{{ $log->created_at?->format('Y-m-d H:i') }}</div>
                                    <small class="text-muted">{{ optional($log->requestedBy)->name ?: __('System') }}</small>
                                </td>
                                <td>{{ ucfirst($log->mode) }}</td>
                                <td>{{ $carTranslation?->name ?? ($log->car_id ? __('Car #:id', ['id' => $log->car_id]) : __('All cars')) }}</td>
                                <td><span class="log-pill {{ $log->status }}">{{ ucfirst($log->status) }}</span></td>
                                <td>
                                    <div class="small">
                                        {{ __('Total') }}: <strong>{{ $log->total_count }}</strong><br>
                                        {{ __('OK') }}: <strong>{{ $log->success_count }}</strong><br>
                                        {{ __('Failed') }}: <strong>{{ $log->failed_count }}</strong>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $log->message ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">{{ __('No catalog sync jobs yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2({ width: '100%' });
            }

            document.addEventListener('admin:sync-notifications', function (event) {
                const changedNotifications = event.detail?.changedNotifications || [];
                const shouldRefresh = changedNotifications.some(function (item) {
                    return ['success', 'partial', 'failed'].includes(item.status);
                });

                if (shouldRefresh) {
                    window.setTimeout(function () {
                        window.location.reload();
                    }, 1800);
                }
            });
        });
    </script>
@endpush
