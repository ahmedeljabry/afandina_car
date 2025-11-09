@extends('layouts.admin_layout')

@section('title', __('Dashboard'))
@section('page-title', __('Dashboard Overview'))
@section('breadcrumbs')
    <li class="breadcrumb-item active">{{ __('Overview') }}</li>
@endsection
@section('page-actions')
    <a href="{{ route('admin.cars.create') }}" class="btn btn-info">
        <i class="ft-plus"></i> {{ __('Add Car') }}
    </a>
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-info">
        <i class="ft-edit-2"></i> {{ __('Manage Content') }}
    </a>
@endsection

@section('content')
    @php
        $totalListings = $carsByCategory->sum('cars_count');
    @endphp

    <section id="dashboard-metrics">
        <div class="row match-height">
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="media-body text-left">
                                    <h3 class="info">{{ $carCount }}</h3>
                                    <h6>{{ __('Total Cars') }}</h6>
                                </div>
                                <div class="align-self-center">
                                    <i class="icon-target info font-large-2"></i>
                                </div>
                            </div>
                            <span class="text-muted">{{ __('Active listings across the fleet') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="media-body text-left">
                                    <h3 class="success">{{ $brandCount }}</h3>
                                    <h6>{{ __('Brands') }}</h6>
                                </div>
                                <div class="align-self-center">
                                    <i class="icon-badge success font-large-2"></i>
                                </div>
                            </div>
                            <span class="text-muted">{{ __('Manufacturers currently configured') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="media-body text-left">
                                    <h3 class="warning">{{ $categoryCount }}</h3>
                                    <h6>{{ __('Categories') }}</h6>
                                </div>
                                <div class="align-self-center">
                                    <i class="icon-layers warning font-large-2"></i>
                                </div>
                            </div>
                            <span class="text-muted">{{ __('Segments customers can browse') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="media-body text-left">
                                    <h3 class="danger">{{ $totalListings }}</h3>
                                    <h6>{{ __('Published Listings') }}</h6>
                                </div>
                                <div class="align-self-center">
                                    <i class="icon-graph danger font-large-2"></i>
                                </div>
                            </div>
                            <span class="text-muted">{{ __('Combined inventory per category') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="dashboard-quick-actions">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0"><i class="icon-lightning mr-1"></i> {{ __('Quick Actions') }}</h4>
                <span class="text-muted small">{{ __('Jump to the most common workflows') }}</span>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-xl-2 col-md-4 col-6 mb-1">
                        <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-info btn-block">
                            <i class="icon-directions"></i> {{ __('Cars') }}
                        </a>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-1">
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-primary btn-block">
                            <i class="icon-badge"></i> {{ __('Brands') }}
                        </a>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-1">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-warning btn-block">
                            <i class="icon-grid"></i> {{ __('Categories') }}
                        </a>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-1">
                        <a href="{{ route('admin.features.index') }}" class="btn btn-outline-success btn-block">
                            <i class="icon-plus"></i> {{ __('Features') }}
                        </a>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-1">
                        <a href="{{ route('admin.languages.index') }}" class="btn btn-outline-secondary btn-block">
                            <i class="icon-globe"></i> {{ __('Languages') }}
                        </a>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 mb-1">
                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-danger btn-block">
                            <i class="icon-book-open"></i> {{ __('Blog') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="dashboard-details" class="mt-2">
        <div class="row match-height">
            <div class="col-xl-6 col-md-12">
                <div class="card card-border-info">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="icon-directions mr-1"></i>
                            {{ __('Latest Added Cars') }}
                        </h4>
                        <div class="heading-elements">
                            <a href="{{ route('admin.cars.create') }}" class="btn btn-sm btn-info">
                                <i class="ft-plus"></i> {{ __('Add Car') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table class="table table-hover table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('Car') }}</th>
                                        <th>{{ __('Brand') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th class="text-center">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($latestCars as $car)
                                        <tr>
                                            <td class="text-bold-600">
                                                {{ $car->translations->first()->name ?? __('N/A') }}
                                                <div class="table-meta text-muted">
                                                    <i class="ft-clock mr-25"></i>
                                                    {{ optional($car->created_at)->diffForHumans() ?? __('Unknown') }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-light-info">
                                                    <i class="icon-badge mr-25"></i>
                                                    {{ $car->brand->translations->first()->name ?? __('N/A') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-light-warning">
                                                    <i class="icon-grid mr-25"></i>
                                                    {{ $car->category->translations->first()->name ?? __('N/A') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.cars.edit', $car->id) }}" class="btn btn-primary">
                                                        <i class="ft-edit"></i>
                                                    </a>
                                                    <a href="{{ route('admin.cars.edit_images', $car->id) }}" class="btn btn-secondary">
                                                        <i class="ft-image"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                {{ __('No cars have been added yet.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-12">
                <div class="card card-border-success">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="icon-pie-chart mr-1"></i>
                            {{ __('Cars by Category') }}
                        </h4>
                        <div class="heading-elements">
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-success">
                                <i class="ft-plus"></i> {{ __('Add Category') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('Category') }}</th>
                                        <th class="text-center">{{ __('Cars') }}</th>
                                        <th style="width: 45%">{{ __('Distribution') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $progressColors = ['bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-primary', 'bg-secondary'];
                                    @endphp
                                    @forelse($carsByCategory as $category)
                                        @php
                                            $categoryPercentage = $carCount > 0 ? ($category->cars_count / $carCount) * 100 : 0;
                                            $barClass = $progressColors[$loop->index % count($progressColors)];
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.categories.edit', $category->id) }}">
                                                    {{ $category->translations->first()->name ?? __('N/A') }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-pill badge-light-success">
                                                    {{ $category->cars_count }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar {{ $barClass }}" role="progressbar"
                                                         style="width: {{ $categoryPercentage }}%"
                                                         aria-valuenow="{{ $categoryPercentage }}" aria-valuemin="0"
                                                         aria-valuemax="100"></div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">{{ number_format($categoryPercentage, 1) }}%</small>
                                                    <small class="text-muted">
                                                        {{ __('Share of total cars') }}
                                                    </small>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">
                                                {{ __('No categories available yet.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
