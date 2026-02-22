@extends('layouts.website')
@section('title', __('website.nav.all_cars'))

@section('content')
    @php
        use Illuminate\Support\Str;

        $assetUrl = static fn (string $path): string => asset('website/assets/' . ltrim($path, '/'));

        $storageUrl = static function (?string $path, ?string $fallback = null): ?string {
            if (blank($path)) {
                return $fallback;
            }

            if (Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            return asset('storage/' . ltrim($path, '/'));
        };

        $selectedBrandIds = collect($filters['selected_brand_ids'] ?? [])->map(fn ($id) => (int) $id)->all();
        $selectedCategoryIds = collect($filters['selected_category_ids'] ?? [])->map(fn ($id) => (int) $id)->all();
        $selectedYearIds = collect($filters['selected_year_ids'] ?? [])->map(fn ($id) => (int) $id)->all();

        $search = $filters['search'] ?? '';
        $availableOnly = (bool) ($filters['available_only'] ?? false);
        $sort = $filters['sort'] ?? 'newest';
        $perPage = (int) ($filters['per_page'] ?? 9);

        $perPageOptions = [6, 9, 12, 18, 24];
        $sortOptions = [
            'newest' => __('website.cars.sort.newest'),
            'price_low' => __('website.cars.sort.price_low'),
            'price_high' => __('website.cars.sort.price_high'),
            'year_new' => __('website.cars.sort.year_new'),
            'year_old' => __('website.cars.sort.year_old'),
        ];
    @endphp

    <div class="breadcrumb-bar">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title">{{ __('website.cars.page_title') }}</h2>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('website.nav.home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('website.nav.all_cars') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="sort-section">
        <div class="container">
            <div class="sortby-sec">
                <div class="sorting-div">
                    <div class="row d-flex align-items-center">
                        <div class="col-xl-4 col-lg-3 col-sm-12 col-12">
                            <div class="count-search">
                                <p>
                                    {{
                                        __('website.cars.showing_results', [
                                            'from' => $cars->firstItem() ?? 0,
                                            'to' => $cars->lastItem() ?? 0,
                                            'total' => $cars->total(),
                                        ])
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-9 col-sm-12 col-12">
                            <div class="product-filter-group">
                                <div class="sortbyset">
                                    <ul>
                                        <li>
                                            <span class="sortbytitle">{{ __('website.cars.sort.show') }} : </span>
                                            <div class="sorting-select select-one">
                                                <select class="form-control select" name="per_page" form="cars-filter-form" onchange="document.getElementById('cars-filter-form').submit();">
                                                    @foreach($perPageOptions as $option)
                                                        <option value="{{ $option }}" {{ $perPage === $option ? 'selected' : '' }}>{{ $option }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </li>
                                        <li>
                                            <span class="sortbytitle">{{ __('website.cars.sort.sort_by') }} </span>
                                            <div class="sorting-select select-two">
                                                <select class="form-control select" name="sort" form="cars-filter-form" onchange="document.getElementById('cars-filter-form').submit();">
                                                    @foreach($sortOptions as $sortKey => $sortLabel)
                                                        <option value="{{ $sortKey }}" {{ $sort === $sortKey ? 'selected' : '' }}>{{ $sortLabel }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="grid-listview">
                                    <ul>
                                        <li>
                                            <a href="javascript:void(0);" class="active">
                                                <i class="feather-grid"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section car-listing pt-0">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-sm-12 col-12 theiaStickySidebar">
                    <form id="cars-filter-form" action="{{ route('website.cars.index') }}" method="GET" autocomplete="off" class="sidebar-form">
                        <div class="sidebar-heading">
                            <h3>{{ __('website.cars.filters.title') }}</h3>
                        </div>

                        <div class="product-search">
                            <div class="form-custom">
                                <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="{{ __('website.cars.filters.search_placeholder') }}">
                                <span><img src="{{ $assetUrl('img/icons/search.svg') }}" alt="search"></span>
                            </div>
                        </div>

                        <div class="product-availability">
                            <h6>{{ __('website.cars.filters.availability') }}</h6>
                            <div class="status-toggle">
                                <input id="available_only" class="check" type="checkbox" name="available_only" value="1" {{ $availableOnly ? 'checked' : '' }}>
                                <label for="available_only" class="checktoggle">checkbox</label>
                            </div>
                        </div>

                        <div class="accord-list">
                            @if(($filters['brands'] ?? collect())->isNotEmpty())
                                <div class="accordion" id="accordionBrand">
                                    <div class="card-header-new" id="headingBrand">
                                        <h6 class="filter-title">
                                            <a href="javascript:void(0);" class="w-100" data-bs-toggle="collapse" data-bs-target="#collapseBrand" aria-expanded="true" aria-controls="collapseBrand">
                                                {{ __('website.cars.filters.brand') }}
                                                <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapseBrand" class="collapse show" aria-labelledby="headingBrand">
                                        <div class="card-body-chat">
                                            <div class="selectBox-cont">
                                                @foreach($filters['brands'] as $brand)
                                                    <label class="custom_check w-100">
                                                        <input type="checkbox" name="brand[]" value="{{ $brand['id'] }}" {{ in_array((int) $brand['id'], $selectedBrandIds, true) ? 'checked' : '' }}>
                                                        <span class="checkmark"></span>
                                                        {{ $brand['name'] }} ({{ $brand['cars_count'] }})
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(($filters['categories'] ?? collect())->isNotEmpty())
                                <div class="accordion" id="accordionCategory">
                                    <div class="card-header-new" id="headingCategory">
                                        <h6 class="filter-title">
                                            <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse" data-bs-target="#collapseCategory" aria-expanded="false" aria-controls="collapseCategory">
                                                {{ __('website.cars.filters.category') }}
                                                <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapseCategory" class="collapse" aria-labelledby="headingCategory">
                                        <div class="card-body-chat">
                                            <div class="selectBox-cont">
                                                @foreach($filters['categories'] as $category)
                                                    <label class="custom_check w-100">
                                                        <input type="checkbox" name="category[]" value="{{ $category['id'] }}" {{ in_array((int) $category['id'], $selectedCategoryIds, true) ? 'checked' : '' }}>
                                                        <span class="checkmark"></span>
                                                        {{ $category['name'] }} ({{ $category['cars_count'] }})
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(($filters['years'] ?? collect())->isNotEmpty())
                                <div class="accordion" id="accordionYear">
                                    <div class="card-header-new" id="headingYear">
                                        <h6 class="filter-title">
                                            <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse" data-bs-target="#collapseYear" aria-expanded="false" aria-controls="collapseYear">
                                                {{ __('website.cars.filters.year') }}
                                                <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapseYear" class="collapse" aria-labelledby="headingYear">
                                        <div class="card-body-chat">
                                            <div class="selectBox-cont">
                                                @foreach($filters['years'] as $year)
                                                    <label class="custom_check w-100">
                                                        <input type="checkbox" name="year[]" value="{{ $year['id'] }}" {{ in_array((int) $year['id'], $selectedYearIds, true) ? 'checked' : '' }}>
                                                        <span class="checkmark"></span>
                                                        {{ $year['year'] }} ({{ $year['cars_count'] }})
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="d-inline-flex align-items-center justify-content-center btn w-100 btn-primary filter-btn">
                            <span><i class="feather-filter me-2"></i></span>{{ __('website.cars.filters.apply') }}
                        </button>
                        <a href="{{ route('website.cars.index') }}" class="reset-filter">{{ __('website.cars.filters.reset') }}</a>
                    </form>
                </div>

                <div class="col-lg-9">
                    @if($cars->count() > 0)
                        <div class="row">
                            @foreach($cars as $car)
                                @php
                                    $carName = $car['name'] ?? __('website.common.car');
                                    $carUrl = $car['details_url'] ?? 'javascript:void(0);';
                                    $carImage = $storageUrl($car['image_path'] ?? null, $assetUrl('img/cars/car-01.jpg'));
                                    $carBrand = $car['brand_name'] ?? null;
                                    $carCategory = $car['category_name'] ?? null;
                                    $carStatusRaw = (string) ($car['status'] ?? 'available');
                                    $carStatusLabel = __('website.status.' . $carStatusRaw);

                                    if ($carStatusLabel === 'website.status.' . $carStatusRaw) {
                                        $carStatusLabel = ucfirst(str_replace('_', ' ', $carStatusRaw));
                                    }

                                    $carPrice = $car['daily_price'] ?? null;
                                    $carMainPrice = $car['daily_main_price'] ?? null;
                                    $carCurrency = $car['currency_symbol'] ?? '$';
                                    $isFeatured = (bool) ($car['is_featured'] ?? false);

                                    $specItems = collect([
                                        ['icon' => 'img/icons/car-parts-01.svg', 'value' => $car['gear_type_name'] ?? null],
                                        ['icon' => 'img/icons/car-parts-02.svg', 'value' => isset($car['daily_mileage_included']) ? __('website.units.km_value', ['count' => $car['daily_mileage_included']]) : null],
                                        ['icon' => 'img/icons/car-parts-03.svg', 'value' => isset($car['door_count']) ? __('website.units.doors', ['count' => $car['door_count']]) : null],
                                        ['icon' => 'img/icons/car-parts-05.svg', 'value' => $car['year'] ?? null],
                                        ['icon' => 'img/icons/car-parts-06.svg', 'value' => isset($car['passenger_capacity']) ? __('website.units.persons', ['count' => $car['passenger_capacity']]) : null],
                                        ['icon' => 'img/icons/car-parts-04.svg', 'value' => $carCategory],
                                    ])->filter(fn (array $item) => filled($item['value']))->values();

                                    $specChunks = $specItems->chunk(3)->values();
                                    $primarySpecs = $specChunks->get(0, collect());
                                    $secondarySpecs = $specChunks->get(1, collect());
                                @endphp

                                <div class="col-xxl-4 col-lg-6 col-md-6 col-12">
                                    <div class="listing-item">
                                        <div class="listing-img">
                                            <a href="{{ $carUrl }}">
                                                <img src="{{ $carImage }}" class="img-fluid" alt="{{ $carName }}">
                                            </a>
                                            <div class="fav-item justify-content-end">
                                                <a href="javascript:void(0)" class="fav-icon">
                                                    <i class="feather-heart"></i>
                                                </a>
                                            </div>
                                            @if(filled($carBrand))
                                                <span class="featured-text">{{ $carBrand }}</span>
                                            @endif
                                        </div>

                                        <div class="listing-content">
                                            <div class="listing-features d-flex align-items-end justify-content-between">
                                                <div class="list-rating">
                                                    <h3 class="listing-title">
                                                        <a href="{{ $carUrl }}">{{ Str::limit($carName, 34) }}</a>
                                                    </h3>
                                                    @if(filled($carBrand))
                                                        <span class="d-block text-muted">{{ $carBrand }}</span>
                                                    @endif
                                                </div>
                                                <div class="list-km">
                                                    <span class="km-count">{{ $carStatusLabel }}</span>
                                                </div>
                                            </div>

                                            @if($primarySpecs->isNotEmpty() || $secondarySpecs->isNotEmpty())
                                                <div class="listing-details-group">
                                                    @if($primarySpecs->isNotEmpty())
                                                        <ul>
                                                            @foreach($primarySpecs as $spec)
                                                                <li>
                                                                    <span><img src="{{ $assetUrl($spec['icon']) }}" alt="spec"></span>
                                                                    <p>{{ $spec['value'] }}</p>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif

                                                    @if($secondarySpecs->isNotEmpty())
                                                        <ul>
                                                            @foreach($secondarySpecs as $spec)
                                                                <li>
                                                                    <span><img src="{{ $assetUrl($spec['icon']) }}" alt="spec"></span>
                                                                    <p>{{ $spec['value'] }}</p>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="listing-location-details">
                                                @if(filled($carCategory))
                                                    <div class="listing-price">
                                                        <span><i class="feather-map-pin"></i></span>{{ $carCategory }}
                                                    </div>
                                                @endif
                                                <div class="listing-price">
                                                    @if($carPrice)
                                                        <h6>
                                                            {{ $carCurrency }}{{ number_format((int) $carPrice) }} <span>{{ __('website.units.per_day') }}</span>
                                                            @if($carMainPrice && $carMainPrice > $carPrice)
                                                                <small class="text-muted text-decoration-line-through d-block">
                                                                    {{ $carCurrency }}{{ number_format((int) $carMainPrice) }}
                                                                </small>
                                                            @endif
                                                        </h6>
                                                    @else
                                                        <h6>{{ __('website.common.call_for_price') }}</h6>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="listing-button">
                                                <a href="{{ $carUrl }}" class="btn btn-order">
                                                    <span><i class="feather-calendar me-2"></i></span>{{ __('website.common.rent_now') }}
                                                </a>
                                            </div>
                                        </div>

                                        @if($isFeatured)
                                            <div class="feature-text">
                                                <span class="bg-danger">{{ __('website.common.featured') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="blog-pagination">
                            {{ $cars->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <h5 class="mb-2">{{ __('website.cars.empty_title') }}</h5>
                                <p class="text-muted mb-0">{{ __('website.cars.empty_subtitle') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
