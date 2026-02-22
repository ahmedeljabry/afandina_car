@extends('layouts.website')
@section('title', __('website.cars.page_title'))

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

        $brands = collect($filters['brands'] ?? []);
        $categories = collect($filters['categories'] ?? []);
        $years = collect($filters['years'] ?? []);

        $selectedBrandIds = collect($filters['selected_brand_ids'] ?? [])->map(fn ($id) => (int) $id)->all();
        $selectedCategoryIds = collect($filters['selected_category_ids'] ?? [])->map(fn ($id) => (int) $id)->all();
        $selectedYearIds = collect($filters['selected_year_ids'] ?? [])->map(fn ($id) => (int) $id)->all();

        $search = (string) ($filters['search'] ?? '');
        $availableOnly = (bool) ($filters['available_only'] ?? false);
        $sort = (string) ($filters['sort'] ?? 'newest');
        $perPage = (int) ($filters['per_page'] ?? 9);

        $from = $cars->firstItem() ?? 0;
        $to = $cars->lastItem() ?? 0;
        $total = $cars->total();

        $currentPage = $cars->currentPage();
        $lastPage = $cars->lastPage();
        $startPage = max(1, $currentPage - 1);
        $endPage = min($lastPage, $currentPage + 1);
    @endphp

    <!-- Breadscrumb Section -->
    <div class="breadcrumb-bar">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title">{{ __('website.cars.page_title') }}</h2>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('website.nav.home') }}</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('website.nav.all_cars') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('website.cars.page_title') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- /Breadscrumb Section -->

    <!-- Search -->
    <div class="section-search page-search">
        <div class="container">
            <div class="search-box-banner">
                <form action="{{ route('website.cars.index') }}" method="GET">
                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">

                    <ul class="align-items-center">
                        <li class="column-group-main">
                            <div class="input-block">
                                <label>{{ __('website.cars.filters.search_placeholder') }}</label>
                                <div class="group-img">
                                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="{{ __('website.cars.filters.search_placeholder') }}">
                                    <span><i class="feather-search"></i></span>
                                </div>
                            </div>
                        </li>
                        <li class="column-group-main">
                            <div class="input-block">
                                <label>{{ __('website.cars.filters.brand') }}</label>
                            </div>
                            <div class="input-block-wrapp">
                                <div class="input-block date-widget">
                                    <div class="group-img">
                                        <select name="brand[]" class="form-control select">
                                            <option value="">{{ __('website.cars.filters.brand') }}</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand['id'] }}" @selected(in_array((int) $brand['id'], $selectedBrandIds, true))>
                                                    {{ $brand['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span><i class="feather-grid"></i></span>
                                    </div>
                                </div>
                                <div class="input-block time-widge">
                                    <div class="group-img">
                                        <select name="category[]" class="form-control select">
                                            <option value="">{{ __('website.cars.filters.category') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category['id'] }}" @selected(in_array((int) $category['id'], $selectedCategoryIds, true))>
                                                    {{ $category['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span><i class="feather-tag"></i></span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="column-group-main">
                            <div class="input-block">
                                <label>{{ __('website.cars.filters.year') }}</label>
                            </div>
                            <div class="input-block-wrapp">
                                <div class="input-block date-widge">
                                    <div class="group-img">
                                        <select name="year[]" class="form-control select">
                                            <option value="">{{ __('website.cars.filters.year') }}</option>
                                            @foreach($years as $year)
                                                <option value="{{ $year['id'] }}" @selected(in_array((int) $year['id'], $selectedYearIds, true))>
                                                    {{ $year['year'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span><i class="feather-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="input-block time-widge">
                                    <div class="group-img d-flex align-items-center gap-2 ps-3">
                                        <input type="checkbox" id="top_available_only" name="available_only" value="1" @checked($availableOnly)>
                                        <label for="top_available_only" class="mb-0">{{ __('website.cars.filters.availability') }}</label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="column-group-last">
                            <div class="input-block">
                                <div class="search-btn">
                                    <button class="btn search-button" type="submit">
                                        <i class="fa fa-search" aria-hidden="true"></i>{{ __('website.cars.filters.apply') }}
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
    <!-- /Search -->

    <!-- Sort By -->
    <div class="sort-section">
        <div class="container">
            <div class="sortby-sec">
                <div class="sorting-div">
                    <div class="row d-flex align-items-center">
                        <div class="col-xl-4 col-lg-3 col-sm-12 col-12">
                            <div class="count-search">
                                <p>{{ __('website.cars.showing_results', ['from' => $from, 'to' => $to, 'total' => $total]) }}</p>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-9 col-sm-12 col-12">
                            <form action="{{ route('website.cars.index') }}" method="GET" class="product-filter-group">
                                <input type="hidden" name="search" value="{{ $search }}">
                                @if($availableOnly)
                                    <input type="hidden" name="available_only" value="1">
                                @endif
                                @foreach($selectedBrandIds as $brandId)
                                    <input type="hidden" name="brand[]" value="{{ $brandId }}">
                                @endforeach
                                @foreach($selectedCategoryIds as $categoryId)
                                    <input type="hidden" name="category[]" value="{{ $categoryId }}">
                                @endforeach
                                @foreach($selectedYearIds as $yearId)
                                    <input type="hidden" name="year[]" value="{{ $yearId }}">
                                @endforeach

                                <div class="sortbyset">
                                    <ul>
                                        <li>
                                            <span class="sortbytitle">{{ __('website.cars.sort.show') }} : </span>
                                            <div class="sorting-select select-one">
                                                <select class="form-control select" name="per_page" onchange="this.form.submit()">
                                                    @foreach([6, 9, 12, 18, 24] as $size)
                                                        <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </li>
                                        <li>
                                            <span class="sortbytitle">{{ __('website.cars.sort.sort_by') }}</span>
                                            <div class="sorting-select select-two">
                                                <select class="form-control select" name="sort" onchange="this.form.submit()">
                                                    <option value="newest" @selected($sort === 'newest')>{{ __('website.cars.sort.newest') }}</option>
                                                    <option value="price_low" @selected($sort === 'price_low')>{{ __('website.cars.sort.price_low') }}</option>
                                                    <option value="price_high" @selected($sort === 'price_high')>{{ __('website.cars.sort.price_high') }}</option>
                                                    <option value="year_new" @selected($sort === 'year_new')>{{ __('website.cars.sort.year_new') }}</option>
                                                    <option value="year_old" @selected($sort === 'year_old')>{{ __('website.cars.sort.year_old') }}</option>
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
                                        <li>
                                            <a href="javascript:void(0);">
                                                <i class="feather-list"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);">
                                                <i class="feather-map-pin"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Sort By -->

    <!-- Car Grid View -->
    <section class="section car-listing pt-0">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-sm-12 col-12 theiaStickySidebar">
                    <form action="{{ route('website.cars.index') }}" method="GET" autocomplete="off" class="sidebar-form">
                        <div class="sidebar-heading">
                            <h3>{{ __('website.cars.filters.title') }}</h3>
                        </div>

                        <div class="product-search">
                            <div class="form-custom">
                                <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="{{ __('website.cars.filters.search_placeholder') }}">
                                <span><img src="{{ $assetUrl('img/icons/search.svg') }}" alt="img"></span>
                            </div>
                        </div>

                        <div class="product-availability">
                            <h6>{{ __('website.cars.filters.availability') }}</h6>
                            <div class="status-toggle">
                                <input id="mobile_notifications" class="check" type="checkbox" name="available_only" value="1" @checked($availableOnly)>
                                <label for="mobile_notifications" class="checktoggle">checkbox</label>
                            </div>
                        </div>

                        <div class="accord-list">
                            <div class="accordion" id="accordionMain1">
                                <div class="card-header-new" id="headingOne">
                                    <h6 class="filter-title">
                                        <a href="javascript:void(0);" class="w-100" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            {{ __('website.cars.filters.brand') }}
                                            <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </h6>
                                </div>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionMain1">
                                    <div class="card-body-chat">
                                        <div class="selectBox-cont">
                                            @foreach($brands as $brand)
                                                <label class="custom_check w-100">
                                                    <input type="checkbox" name="brand[]" value="{{ $brand['id'] }}" @checked(in_array((int) $brand['id'], $selectedBrandIds, true))>
                                                    <span class="checkmark"></span>{{ $brand['name'] }} ({{ $brand['cars_count'] ?? 0 }})
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion" id="accordionMain2">
                                <div class="card-header-new" id="headingTwo">
                                    <h6 class="filter-title">
                                        <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                            {{ __('website.cars.filters.category') }}
                                            <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </h6>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionMain2">
                                    <div class="card-body-chat">
                                        <div class="selectBox-cont">
                                            @foreach($categories as $category)
                                                <label class="custom_check w-100">
                                                    <input type="checkbox" name="category[]" value="{{ $category['id'] }}" @checked(in_array((int) $category['id'], $selectedCategoryIds, true))>
                                                    <span class="checkmark"></span>{{ $category['name'] }} ({{ $category['cars_count'] ?? 0 }})
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion" id="accordionMain3">
                                <div class="card-header-new" id="headingYear">
                                    <h6 class="filter-title">
                                        <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse" data-bs-target="#collapseYear" aria-expanded="true" aria-controls="collapseYear">
                                            {{ __('website.cars.filters.year') }}
                                            <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                                        </a>
                                    </h6>
                                </div>
                                <div id="collapseYear" class="collapse" aria-labelledby="headingYear" data-bs-parent="#accordionMain3">
                                    <div class="card-body-chat">
                                        <div class="selectBox-cont">
                                            @foreach($years as $year)
                                                <label class="custom_check w-100">
                                                    <input type="checkbox" name="year[]" value="{{ $year['id'] }}" @checked(in_array((int) $year['id'], $selectedYearIds, true))>
                                                    <span class="checkmark"></span>{{ $year['year'] }} ({{ $year['cars_count'] ?? 0 }})
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <input type="hidden" name="per_page" value="{{ $perPage }}">

                        <button type="submit" class="d-inline-flex align-items-center justify-content-center btn w-100 btn-primary filter-btn">
                            <span><i class="feather-filter me-2"></i></span>{{ __('website.cars.filters.apply') }}
                        </button>
                        <a href="{{ route('website.cars.index') }}" class="reset-filter">{{ __('website.cars.filters.reset') }}</a>
                    </form>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        @forelse($cars as $car)
                            @php
                                $carName = $car['name'] ?? __('website.common.car');
                                $carImage = $storageUrl($car['image_path'] ?? null, $assetUrl('img/cars/car-01.jpg'));
                                $carBrand = $car['brand_name'] ?? __('website.common.brand');
                                $carCategory = $car['category_name'] ?? __('website.common.category');
                                $carStatusRaw = (string) ($car['status'] ?? 'available');
                                $carStatus = __('website.status.' . $carStatusRaw);
                                if ($carStatus === 'website.status.' . $carStatusRaw) {
                                    $carStatus = ucfirst(str_replace('_', ' ', $carStatusRaw));
                                }
                                $carPrice = $car['daily_price'] ?? null;
                                $carMainPrice = $car['daily_main_price'] ?? null;
                                $carCurrency = $car['currency_symbol'] ?? '$';
                                $carUrl = $car['details_url'] ?? 'javascript:void(0);';
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
                                        <span class="featured-text">{{ $carBrand }}</span>
                                    </div>
                                    <div class="listing-content">
                                        <div class="listing-features d-flex align-items-end justify-content-between">
                                            <div class="list-rating">
                                                <a href="javascript:void(0)" class="author-img">
                                                    <img src="{{ $assetUrl('img/profiles/avatar-04.jpg') }}" alt="author">
                                                </a>
                                                <h3 class="listing-title">
                                                    <a href="{{ $carUrl }}">{{ Str::limit($carName, 36) }}</a>
                                                </h3>
                                                <div class="list-rating">
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star"></i>
                                                    <span>{{ $carStatus }}</span>
                                                </div>
                                            </div>
                                            <div class="list-km">
                                                <span class="km-count"><img src="{{ $assetUrl('img/icons/map-pin.svg') }}" alt="author">{{ $car['year'] ?? '-' }}</span>
                                            </div>
                                        </div>
                                        <div class="listing-details-group">
                                            <ul>
                                                <li>
                                                    <span><img src="{{ $assetUrl('img/icons/car-parts-01.svg') }}" alt="Auto"></span>
                                                    <p>{{ $car['gear_type_name'] ?? '-' }}</p>
                                                </li>
                                                <li>
                                                    <span><img src="{{ $assetUrl('img/icons/car-parts-02.svg') }}" alt="KM"></span>
                                                    <p>{{ isset($car['daily_mileage_included']) ? __('website.units.km_value', ['count' => $car['daily_mileage_included']]) : '-' }}</p>
                                                </li>
                                                <li>
                                                    <span><img src="{{ $assetUrl('img/icons/car-parts-03.svg') }}" alt="Category"></span>
                                                    <p>{{ $carCategory }}</p>
                                                </li>
                                            </ul>
                                            <ul>
                                                <li>
                                                    <span><img src="{{ $assetUrl('img/icons/car-parts-04.svg') }}" alt="Doors"></span>
                                                    <p>{{ isset($car['door_count']) ? __('website.units.doors', ['count' => $car['door_count']]) : '-' }}</p>
                                                </li>
                                                <li>
                                                    <span><img src="{{ $assetUrl('img/icons/car-parts-05.svg') }}" alt="Year"></span>
                                                    <p>{{ $car['year'] ?? '-' }}</p>
                                                </li>
                                                <li>
                                                    <span><img src="{{ $assetUrl('img/icons/car-parts-06.svg') }}" alt="Persons"></span>
                                                    <p>{{ isset($car['passenger_capacity']) ? __('website.units.persons', ['count' => $car['passenger_capacity']]) : '-' }}</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="listing-location-details">
                                            <div class="listing-price">
                                                <span><i class="feather-map-pin"></i></span>{{ $carCategory }}
                                            </div>
                                            <div class="listing-price">
                                                @if($carPrice)
                                                    <h6>{{ $carCurrency }}{{ number_format((int) $carPrice) }} <span>{{ __('website.units.per_day') }}</span></h6>
                                                    @if($carMainPrice && $carMainPrice > $carPrice)
                                                        <small class="text-muted text-decoration-line-through">{{ $carCurrency }}{{ number_format((int) $carMainPrice) }}</small>
                                                    @endif
                                                @else
                                                    <h6>{{ __('website.common.call_for_price') }}</h6>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="listing-button">
                                            <a href="{{ $carUrl }}" class="btn btn-order"><span><i class="feather-calendar me-2"></i></span>{{ __('website.common.rent_now') }}</a>
                                        </div>
                                    </div>
                                    @if(!empty($car['is_featured']))
                                        <div class="feature-text">
                                            <span class="bg-danger">{{ __('website.common.featured') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <h4>{{ __('website.cars.empty_title') }}</h4>
                                    <p class="mb-0">{{ __('website.cars.empty_subtitle') }}</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if($cars->hasPages())
                        <div class="blog-pagination">
                            <nav>
                                <ul class="pagination page-item justify-content-center">
                                    <li class="previtem {{ $cars->onFirstPage() ? 'disabled' : '' }}">
                                        @if($cars->onFirstPage())
                                            <span class="page-link"><i class="fas fa-regular fa-arrow-left me-2"></i> {{ __('website.blog.navigation.previous') }}</span>
                                        @else
                                            <a class="page-link" href="{{ $cars->previousPageUrl() }}"><i class="fas fa-regular fa-arrow-left me-2"></i> {{ __('website.blog.navigation.previous') }}</a>
                                        @endif
                                    </li>

                                    <li class="justify-content-center pagination-center">
                                        <div class="page-group">
                                            <ul>
                                                @if($startPage > 1)
                                                    <li class="page-item"><a class="page-link" href="{{ $cars->url(1) }}">1</a></li>
                                                @endif

                                                @for($page = $startPage; $page <= $endPage; $page++)
                                                    <li class="page-item">
                                                        <a class="{{ $page === $currentPage ? 'active ' : '' }}page-link" href="{{ $cars->url($page) }}">{{ $page }}</a>
                                                    </li>
                                                @endfor

                                                @if($endPage < $lastPage)
                                                    <li class="page-item"><a class="page-link" href="{{ $cars->url($lastPage) }}">{{ $lastPage }}</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </li>

                                    <li class="nextlink {{ $cars->hasMorePages() ? '' : 'disabled' }}">
                                        @if($cars->hasMorePages())
                                            <a class="page-link" href="{{ $cars->nextPageUrl() }}">{{ __('website.blog.navigation.next') }} <i class="fas fa-regular fa-arrow-right ms-2"></i></a>
                                        @else
                                            <span class="page-link">{{ __('website.blog.navigation.next') }} <i class="fas fa-regular fa-arrow-right ms-2"></i></span>
                                        @endif
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- /Car Grid View -->
@endsection
