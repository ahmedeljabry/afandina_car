@extends('layouts.website')
@section('title', $carDetails['name'] ?? __('website.car_details.page_title'))

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

        $carName = $carDetails['name'] ?? __('website.common.car');
        $primaryImage = $storageUrl(($carDetails['image_paths']->first() ?? null), $assetUrl('img/cars/slider-01.jpg'));
    @endphp

    <div class="breadcrumb-bar">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title">{{ $carName }}</h2>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('website.nav.home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('website.cars.index') }}">{{ __('website.nav.all_cars') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $carName }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="product-detail-head">
        <div class="container">
            <div class="detail-page-head">
                <div class="detail-headings">
                    <div class="star-rated">
                        <ul class="list-rating">
                            @if(!empty($carDetails['category_name']))
                                <li>
                                    <div class="car-brand">
                                        <span><img src="{{ $assetUrl('img/icons/car-icon.svg') }}" alt="Category"></span>
                                        {{ $carDetails['category_name'] }}
                                    </div>
                                </li>
                            @endif
                            @if(!empty($carDetails['year']))
                                <li><span class="year">{{ $carDetails['year'] }}</span></li>
                            @endif
                        </ul>
                        <div class="camaro-info">
                            <h3>{{ $carName }}</h3>
                            <div class="camaro-location">
                                @if(!empty($carDetails['brand_name']))
                                    <div class="camaro-location-inner">
                                        <i class='bx bx-purchase-tag'></i>
                                        <span>{{ __('website.car_details.labels.brand') }}: {{ $carDetails['brand_name'] }}</span>
                                    </div>
                                @endif
                                @if(!empty($carDetails['model_name']))
                                    <div class="camaro-location-inner">
                                        <i class='bx bx-car'></i>
                                        <span>{{ __('website.car_details.labels.model') }}: {{ $carDetails['model_name'] }}</span>
                                    </div>
                                @endif
                                @if(!empty($carDetails['listed_on']))
                                    <div class="camaro-location-inner">
                                        <i class='bx bx-calendar'></i>
                                        <span>{{ __('website.car_details.labels.listed_on') }}: {{ $carDetails['listed_on'] }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if(!empty($carDetails['status']))
                    <div class="details-btn">
                        <span class="total-badge">
                            <i class='bx bx-check-circle'></i>
                            {{ $carDetails['status_label'] ?? ucfirst((string) $carDetails['status']) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="section product-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="detail-product">
                        <div class="slider detail-bigimg">
                            @forelse($carDetails['image_paths'] as $imagePath)
                                <div class="product-img">
                                    <img src="{{ $storageUrl($imagePath, $primaryImage) }}" alt="{{ $carName }}">
                                </div>
                            @empty
                                <div class="product-img">
                                    <img src="{{ $primaryImage }}" alt="{{ $carName }}">
                                </div>
                            @endforelse
                        </div>

                        @if($carDetails['image_paths']->count() > 1)
                            <div class="slider slider-nav-thumbnails">
                                @foreach($carDetails['image_paths'] as $imagePath)
                                    <div>
                                        <img src="{{ $storageUrl($imagePath, $primaryImage) }}" alt="{{ $carName }} thumbnail">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if(!empty($carDetails['description']) || !empty($carDetails['long_description']))
                        <div class="review-sec mb-0">
                            <div class="review-header">
                                <h4>{{ __('website.car_details.sections.description') }}</h4>
                            </div>
                            <div class="description-list">
                                @if(!empty($carDetails['description']))
                                    <p>{{ $carDetails['description'] }}</p>
                                @endif

                                @if(!empty($carDetails['long_description']))
                                    {!! $carDetails['long_description'] !!}
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($carDetails['specifications']->count() > 0)
                        <div class="review-sec specification-card">
                            <div class="review-header">
                                <h4>{{ __('website.car_details.sections.specifications') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="lisiting-featues">
                                    <div class="row">
                                        @foreach($carDetails['specifications'] as $spec)
                                            <div class="featureslist d-flex align-items-center col-xl-4 col-md-6 col-sm-6">
                                                <div class="feature-img">
                                                    <img src="{{ $assetUrl($spec['icon']) }}" alt="{{ $spec['label'] }}">
                                                </div>
                                                <div class="featues-info">
                                                    <span>{{ $spec['label'] }}</span>
                                                    <h6>{{ $spec['value'] }}</h6>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($carDetails['features']->count() > 0)
                        <div class="review-sec pb-0">
                            <div class="review-header">
                                <h4>{{ __('website.car_details.sections.features') }}</h4>
                            </div>
                            <div class="lisiting-service">
                                <div class="row">
                                    @foreach($carDetails['features'] as $feature)
                                        <div class="servicelist d-flex align-items-center col-xl-4 col-sm-6">
                                            <div class="service-img d-flex align-items-center justify-content-center">
                                                @if(!empty($feature['icon_class']))
                                                    <i class="{{ $feature['icon_class'] }}"></i>
                                                @else
                                                    <img src="{{ $assetUrl('img/icons/service-01.svg') }}" alt="{{ __('website.common.feature_icon') }}">
                                                @endif
                                            </div>
                                            <div class="service-info">
                                                <p>{{ $feature['name'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4 theiaStickySidebar">
                    <div class="review-sec mb-4">
                        <div class="review-header">
                            <h4>{{ __('website.car_details.sections.rental_prices') }}</h4>
                        </div>

                        @if($carDetails['pricing']->count() > 0)
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tbody>
                                        @foreach($carDetails['pricing'] as $price)
                                            <tr>
                                                <td>
                                                    <h6 class="mb-1">{{ $price['label'] }}</h6>
                                                    @if(!empty($price['mileage']))
                                                        <small class="text-muted">{{ __('website.units.km_included', ['count' => number_format($price['mileage'])]) }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <h6 class="mb-1">{{ $price['currency'] }}{{ number_format($price['price']) }}</h6>
                                                    @if($price['main_price'] > $price['price'])
                                                        <small class="text-muted text-decoration-line-through">
                                                            {{ $price['currency'] }}{{ number_format($price['main_price']) }}
                                                        </small>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="mb-0 text-muted">{{ __('website.car_details.contact_for_pricing') }}</p>
                        @endif
                    </div>

                    @if($carDetails['highlights']->count() > 0)
                        <div class="review-sec mb-0">
                            <div class="review-header">
                                <h4>{{ __('website.car_details.sections.highlights') }}</h4>
                            </div>
                            <ul class="list-unstyled mb-0">
                                @foreach($carDetails['highlights'] as $highlight)
                                    <li class="mb-2 d-flex align-items-center">
                                        <i class="fa-solid fa-check text-success me-2"></i>
                                        <span>{{ $highlight }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if($relatedCars->count() > 0)
        <section class="car-section pt-0">
            <div class="container">
                <div class="section-heading heading-four" data-aos="fade-down">
                    <h2>{{ __('website.car_details.sections.related_cars') }}</h2>
                    <p>{{ __('website.car_details.related_cars_subtitle') }}</p>
                </div>

                <div class="row row-gap-4">
                    @foreach($relatedCars as $car)
                        <div class="col-lg-3 col-md-6 d-flex">
                            @include('website.partials.car-card', [
                                'car' => $car,
                                'assetUrl' => $assetUrl,
                                'storageUrl' => $storageUrl,
                                'showDiscount' => true,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
