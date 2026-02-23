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

            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }

            return asset('storage/' . ltrim($path, '/'));
        };

        $formatPrice = static function ($price, string $currencySymbol): string {
            if (!filled($price)) {
                return __('website.common.call_for_price');
            }

            $currencyLabel = trim((string) $currencySymbol);

            return ($currencyLabel !== '' ? $currencyLabel . ' ' : '') . number_format((float) $price);
        };
        $formatAmount = static fn ($price): string => number_format((float) $price);

        $carName = $carDetails['name'] ?? __('website.common.car');
        $brandName = $carDetails['brand_name'] ?? __('website.common.brand');
        $categoryName = $carDetails['category_name'] ?? __('website.common.category');
        $modelName = $carDetails['model_name'] ?? __('website.car_details.not_available');
        $yearValue = $carDetails['year'] ?? __('website.car_details.not_available');
        $listedOn = $carDetails['listed_on'] ?? __('website.car_details.not_available');
        $currencySymbol = $carDetails['currency_symbol'] ?? '$';

        $statusRaw = (string) ($carDetails['status'] ?? 'available');
        $statusLabel = __('website.status.' . $statusRaw);
        if ($statusLabel === 'website.status.' . $statusRaw) {
            $statusLabel = ucfirst(str_replace('_', ' ', $statusRaw));
        }

        $mainImage = $storageUrl($carDetails['image_path'] ?? null, $assetUrl('img/cars/slider-01.jpg'));
        $galleryImages = collect($carDetails['images'] ?? [])
            ->filter(fn ($image) => ($image['type'] ?? 'image') === 'image' && filled($image['file_path'] ?? null))
            ->values();

        if ($galleryImages->isEmpty()) {
            $galleryImages = collect([
                [
                    'file_path' => $carDetails['image_path'] ?? null,
                    'thumbnail_path' => $carDetails['image_path'] ?? null,
                    'alt' => $carName,
                ],
            ])->values();
        }

        $descriptionParagraphs = collect([
            trim(strip_tags((string) ($carDetails['description'] ?? ''))),
            trim(strip_tags((string) ($carDetails['long_description'] ?? ''))),
        ])->filter(fn ($value) => filled($value))->values();

        if ($descriptionParagraphs->isEmpty()) {
            $descriptionParagraphs = collect([__('website.car_details.not_available')]);
        }

        $primaryDescription = $descriptionParagraphs->take(2);
        $moreDescription = $descriptionParagraphs->slice(2);

        $features = collect($carDetails['features'] ?? [])
            ->filter(fn ($feature) => filled($feature['name'] ?? null))
            ->values();

        if ($features->isEmpty()) {
            $features = collect([
                ['name' => $categoryName],
                ['name' => $brandName],
                ['name' => $modelName],
                ['name' => $carDetails['gear_type_name'] ?? __('website.car_details.not_available')],
                ['name' => $carDetails['color_name'] ?? __('website.car_details.not_available')],
                ['name' => ($carDetails['free_delivery'] ?? false) ? __('website.car_details.highlights.free_delivery') : null],
                ['name' => ($carDetails['insurance_included'] ?? false) ? __('website.car_details.highlights.insurance_included') : null],
            ])->filter(fn ($feature) => filled($feature['name'] ?? null))->values();
        }

        $featureColumns = $features->chunk((int) max(1, ceil(max(1, $features->count()) / 3)));

        $serviceIcons = [
            'img/icons/service-01.svg',
            'img/icons/service-02.svg',
            'img/icons/service-03.svg',
            'img/icons/service-04.svg',
            'img/icons/service-05.svg',
            'img/icons/service-06.svg',
            'img/icons/service-07.svg',
            'img/icons/service-08.svg',
        ];

        $serviceItems = $features->take(8)->values()->map(function (array $feature, int $index) use ($serviceIcons) {
            return [
                'icon' => $serviceIcons[$index % count($serviceIcons)],
                'name' => $feature['name'],
            ];
        });

        $specificationItems = collect([
            ['icon' => 'img/specification/specification-icon-1.svg', 'label' => __('website.car_details.specifications.body'), 'value' => $categoryName],
            ['icon' => 'img/specification/specification-icon-2.svg', 'label' => __('website.car_details.specifications.make'), 'value' => $brandName],
            ['icon' => 'img/specification/specification-icon-3.svg', 'label' => __('website.car_details.specifications.transmission'), 'value' => $carDetails['gear_type_name'] ?? __('website.car_details.not_available')],
            ['icon' => 'img/specification/specification-icon-4.svg', 'label' => __('website.car_details.specifications.model'), 'value' => $modelName],
            ['icon' => 'img/specification/specification-icon-5.svg', 'label' => __('website.car_details.specifications.mileage'), 'value' => isset($carDetails['daily_mileage_included']) ? __('website.units.km_value', ['count' => $carDetails['daily_mileage_included']]) : __('website.car_details.not_available')],
            ['icon' => 'img/specification/specification-icon-6.svg', 'label' => __('website.car_details.specifications.color'), 'value' => $carDetails['color_name'] ?? __('website.car_details.not_available')],
            ['icon' => 'img/specification/specification-icon-7.svg', 'label' => __('website.car_details.specifications.year'), 'value' => $yearValue],
            ['icon' => 'img/specification/specification-icon-8.svg', 'label' => __('website.car_details.specifications.insurance'), 'value' => ($carDetails['insurance_included'] ?? false) ? __('website.car_details.yes') : __('website.car_details.no')],
            ['icon' => 'img/specification/specification-icon-9.svg', 'label' => __('website.car_details.specifications.crypto_payment'), 'value' => ($carDetails['crypto_payment_accepted'] ?? false) ? __('website.car_details.yes') : __('website.car_details.no')],
            ['icon' => 'img/specification/specification-icon-10.svg', 'label' => __('website.car_details.specifications.doors'), 'value' => isset($carDetails['door_count']) ? __('website.units.doors', ['count' => $carDetails['door_count']]) : __('website.car_details.not_available')],
            ['icon' => 'img/specification/specification-icon-11.svg', 'label' => __('website.car_details.specifications.passengers'), 'value' => isset($carDetails['passenger_capacity']) ? __('website.units.persons', ['count' => $carDetails['passenger_capacity']]) : __('website.car_details.not_available')],
            ['icon' => 'img/specification/specification-icon-12.svg', 'label' => __('website.car_details.specifications.bags'), 'value' => isset($carDetails['luggage_capacity']) ? __('website.units.bags', ['count' => $carDetails['luggage_capacity']]) : __('website.car_details.not_available')],
        ])->values();

        $dailyPrice = $carDetails['prices']['daily_discount'] ?? $carDetails['prices']['daily_main'] ?? null;
        $weeklyPrice = $carDetails['prices']['weekly_discount'] ?? $carDetails['prices']['weekly_main'] ?? null;
        $monthlyPrice = $carDetails['prices']['monthly_discount'] ?? $carDetails['prices']['monthly_main'] ?? null;
        $dailyMainPrice = $carDetails['prices']['daily_main'] ?? null;
        $monthlyMainPrice = $carDetails['prices']['monthly_main'] ?? null;

        $tariffRows = collect([
            ['name' => __('website.car_details.pricing.daily'), 'price' => $dailyPrice, 'mileage' => $carDetails['daily_mileage_included'] ?? null],
            ['name' => __('website.car_details.pricing.weekly'), 'price' => $weeklyPrice, 'mileage' => $carDetails['weekly_mileage_included'] ?? null],
            ['name' => __('website.car_details.pricing.monthly'), 'price' => $monthlyPrice, 'mileage' => $carDetails['monthly_mileage_included'] ?? null],
        ])->filter(fn ($row) => filled($row['price']) || filled($row['mileage']))->values();

        if ($tariffRows->isEmpty()) {
            $tariffRows = collect([
                ['name' => __('website.car_details.pricing.daily'), 'price' => null, 'mileage' => null],
            ]);
        }

        $sidebarTitle = trim($carName . (filled($carDetails['year'] ?? null) ? ' - ' . $carDetails['year'] : ''));
        $sidebarDescription = Str::limit($descriptionParagraphs->first(), 200);
        $sidebarPassengerValue = isset($carDetails['passenger_capacity'])
            ? (string) $carDetails['passenger_capacity']
            : __('website.car_details.not_available');
        $sidebarDoorsValue = isset($carDetails['door_count'])
            ? (string) $carDetails['door_count']
            : __('website.car_details.not_available');

        $carSidebarStats = collect([
            ['icon' => 'fa-solid fa-user', 'value' => $sidebarPassengerValue],
            ['icon' => 'fa-solid fa-door-open', 'value' => $sidebarDoorsValue],
            ['icon' => 'fa-solid fa-car-side', 'value' => $categoryName],
        ])->values();

        $colorName = trim((string) ($carDetails['color_name'] ?? ''));
        $colorPalette = [
            'black' => '#111111',
            'white' => '#f5f5f5',
            'gray' => '#7a7a7a',
            'grey' => '#7a7a7a',
            'silver' => '#a6adb4',
            'red' => '#cf2f2f',
            'blue' => '#1f5fcc',
            'green' => '#2c8c4b',
            'yellow' => '#e3b321',
            'orange' => '#e57a1c',
            'brown' => '#6f4a36',
            'beige' => '#d8c7a3',
            'gold' => '#bfa15a',
            'أسود' => '#111111',
            'ابيض' => '#f5f5f5',
            'أبيض' => '#f5f5f5',
            'رمادي' => '#7a7a7a',
            'احمر' => '#cf2f2f',
            'أحمر' => '#cf2f2f',
            'ازرق' => '#1f5fcc',
            'أزرق' => '#1f5fcc',
            'اخضر' => '#2c8c4b',
            'أخضر' => '#2c8c4b',
        ];
        $normalizedColorName = Str::lower($colorName);
        $colorDotHex = preg_match('/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $colorName)
            ? $colorName
            : ($colorPalette[$normalizedColorName] ?? '#111111');

        $carOverviewItems = collect([
            ['label' => __('website.common.category'), 'value' => $categoryName],
            ['label' => __('website.car_details.labels.brand'), 'value' => $brandName],
            ['label' => __('website.car_details.labels.model'), 'value' => $modelName],
            ['label' => __('website.car_details.specifications.year'), 'value' => $yearValue],
            ['label' => __('website.car_details.specifications.color'), 'value' => filled($colorName) ? $colorName : __('website.car_details.not_available'), 'type' => 'color'],
        ])->values();

        $sidebarPriceRows = collect([
            [
                'label' => __('website.car_details.pricing.daily'),
                'current' => $dailyPrice,
                'old' => $dailyMainPrice,
                'unit' => __('website.units.per_day'),
            ],
            [
                'label' => __('website.car_details.pricing.monthly'),
                'current' => $monthlyPrice,
                'old' => $monthlyMainPrice,
                'unit' => __('website.car_details.sidebar.per_month'),
            ],
        ])->map(function (array $row) {
            $row['old'] = filled($row['old']) && filled($row['current']) && (float) $row['old'] > (float) $row['current']
                ? $row['old']
                : null;

            return $row;
        })->filter(fn (array $row) => filled($row['current']))->values();

        $sidebarHighlights = collect([
            ['label' => __('website.car_details.highlights.free_delivery'), 'enabled' => (bool) ($carDetails['free_delivery'] ?? false)],
            ['label' => __('website.car_details.highlights.insurance_included'), 'enabled' => (bool) ($carDetails['insurance_included'] ?? false)],
            ['label' => __('website.car_details.specifications.crypto_payment'), 'enabled' => (bool) ($carDetails['crypto_payment_accepted'] ?? false)],
        ])->filter(fn (array $item) => $item['enabled'])->values();

        $fullAddress = collect([
            $contact?->address_line1,
            $contact?->address_line2,
            $contact?->city,
            $contact?->state,
            $contact?->postal_code,
            $contact?->country,
        ])->filter()->implode(', ');

        $phone = $contact?->phone ?: $contact?->alternative_phone;
        $phoneHref = filled($phone) ? 'tel:' . preg_replace('/\s+/', '', $phone) : 'javascript:void(0);';

        $whatsAppHref = filled($contact?->whatsapp)
            ? (str_starts_with((string) $contact->whatsapp, 'http://') || str_starts_with((string) $contact->whatsapp, 'https://')
                ? $contact->whatsapp
                : 'https://wa.me/' . preg_replace('/\D+/', '', (string) $contact->whatsapp))
            : 'javascript:void(0);';

        $reviewItems = collect([
            [
                'name' => $contact?->name ?: $brandName,
                'date' => $listedOn,
                'content' => trim(strip_tags((string) ($carDetails['description'] ?? ''))),
                'image' => $assetUrl('img/profiles/avatar-01.jpg'),
            ],
            [
                'name' => $brandName,
                'date' => $listedOn,
                'content' => trim(strip_tags((string) ($carDetails['long_description'] ?? ''))),
                'image' => $assetUrl('img/profiles/avatar-02.jpg'),
            ],
            [
                'name' => $categoryName,
                'date' => $listedOn,
                'content' => trim(strip_tags((string) ($contact?->additional_info ?? ''))),
                'image' => $assetUrl('img/profiles/avatar-03.jpg'),
            ],
        ])->map(function ($review) {
            $review['content'] = filled($review['content'])
                ? Str::limit($review['content'], 260)
                : __('website.car_details.not_available');

            return $review;
        })->filter(fn ($review) => filled($review['name']))->values();

        if ($reviewItems->isEmpty()) {
            $reviewItems = collect([
                [
                    'name' => $carName,
                    'date' => $listedOn,
                    'content' => __('website.car_details.not_available'),
                    'image' => $assetUrl('img/profiles/avatar-01.jpg'),
                ],
            ]);
        }

        $ratingBreakdown = collect([
            ['label' => 'Service', 'score' => ($carDetails['free_delivery'] ?? false) ? 4.8 : 4.2],
            ['label' => 'Location', 'score' => filled($fullAddress) ? 4.7 : 4.0],
            ['label' => 'Value for Money', 'score' => filled($carDetails['discount_rate'] ?? null) ? 4.9 : 4.3],
            ['label' => 'Facilities', 'score' => min(5, max(3.8, 4 + ($features->count() / 10)))],
            ['label' => 'Cleanliness', 'score' => ($carDetails['is_featured'] ?? false) ? 4.9 : 4.4],
        ]);

        $reviewScore = round((float) $ratingBreakdown->avg('score'), 1);
        $reviewCount = $reviewItems->count();

        $relatedItems = collect($relatedCars ?? [])->values();
        if ($relatedItems->isEmpty()) {
            $relatedItems = collect([
                [
                    'name' => $carName,
                    'brand_name' => $brandName,
                    'category_name' => $categoryName,
                    'gear_type_name' => $carDetails['gear_type_name'] ?? null,
                    'year' => $yearValue,
                    'passenger_capacity' => $carDetails['passenger_capacity'] ?? null,
                    'daily_mileage_included' => $carDetails['daily_mileage_included'] ?? null,
                    'daily_price' => $dailyPrice,
                    'currency_symbol' => $currencySymbol,
                    'image_path' => $carDetails['image_path'] ?? null,
                    'is_featured' => (bool) ($carDetails['is_featured'] ?? false),
                    'details_url' => url()->current(),
                ],
            ]);
        }
    @endphp

        <!-- Detail Page Head-->
        <section class="product-detail-head">
            <div class="container">
                <div class="detail-page-head">
                    <div class="detail-headings">
                        <div class="star-rated">
                            <ul class="list-rating">
                                <li>
                                    <div class="car-brand">
                                        <span>
                                            <img src="{{ $assetUrl('img/icons/car-icon.svg') }}" alt="img">
                                        </span>
                                        {{ $categoryName }}
                                    </div>
                                </li>
                                <li>
                                    <span class="year">{{ $yearValue }}</span>
                                </li>
                                <li class="ratings">
                                    <i class="fas fa-star filled"></i>
                                    <i class="fas fa-star filled"></i>
                                    <i class="fas fa-star filled"></i>
                                    <i class="fas fa-star filled"></i>
                                    <i class="fas fa-star filled"></i>
                                    <span class="d-inline-block average-list-rating">({{ number_format($reviewScore, 1) }})</span>
                                </li>
                            </ul>
                            <div class="camaro-info">
                                <h3>{{ $carName }}</h3>
                                <div class="camaro-location">
                                    <div class="camaro-location-inner">
                                        <i class='bx bx-map'></i>
                                        <span>{{ __('website.car_details.labels.location') }} : {{ filled($fullAddress) ? $fullAddress : __('website.car_details.not_available') }}</span>
                                    </div>
                                    <div class="camaro-location-inner">
                                        <i class='bx bx-show'></i>
                                        <span>{{ __('website.car_details.labels.brand') }} : {{ $brandName }}</span>
                                    </div>
                                    <div class="camaro-location-inner">
                                        <i class='bx bx-car'></i>
                                        <span>{{ __('website.car_details.labels.listed_on') }} : {{ $listedOn }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="details-btn">
                        <span class="total-badge"><i class='bx bx-calendar-edit'></i>{{ __('website.car_details.labels.status') }} : {{ $statusLabel }}</span>
                        <a href="{{ route('website.cars.index') }}"> <i class='bx bx-git-compare'></i>{{ __('website.nav.all_cars') }}</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Detail Page Head-->

        <section class="section product-details">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="detail-product">
                            <div class="pro-info">
                                <div class="pro-badge">
                                    <span class="badge-km"><i class="fa-solid fa-person-walking"></i>{{ $statusLabel }}</span>
                                    <a href="javascript:void(0);" class="fav-icon"><i class="fa-regular fa-heart"></i></a>
                                </div>
                                <ul>
                                    <li class="del-airport"><i class="fa-solid fa-check"></i>{{ __('website.car_details.highlights.free_delivery') }} : {{ ($carDetails['free_delivery'] ?? false) ? __('website.car_details.yes') : __('website.car_details.no') }}</li>
                                    <li class="del-home"><i class="fa-solid fa-check"></i>{{ __('website.car_details.highlights.insurance_included') }} : {{ ($carDetails['insurance_included'] ?? false) ? __('website.car_details.yes') : __('website.car_details.no') }}</li>
                                </ul>
                            </div>
                            <div class="slider detail-bigimg">
                                @foreach ($galleryImages as $image)
                                    @php
                                        $imageUrl = $storageUrl($image['file_path'] ?? null, $mainImage);
                                        $imageAlt = $image['alt'] ?? $carName;
                                    @endphp
                                    <div class="product-img">
                                        <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}">
                                    </div>
                                @endforeach
                            </div>
                            <div class="slider slider-nav-thumbnails">
                                @foreach ($galleryImages as $image)
                                    @php
                                        $thumbnailUrl = $storageUrl($image['thumbnail_path'] ?? ($image['file_path'] ?? null), $mainImage);
                                        $thumbnailAlt = $image['alt'] ?? $carName;
                                    @endphp
                                    <div><img src="{{ $thumbnailUrl }}" alt="{{ $thumbnailAlt }}"></div>
                                @endforeach
                            </div>
                        </div>
                        <div class="review-sec pb-0">
                            <div class="review-header">
                                <h4>{{ __('website.car_details.sections.extra_services') }}</h4>
                            </div>
                            <div class="lisiting-service">
                                <div class="row">
                                    @foreach ($serviceItems as $serviceItem)
                                        <div class="servicelist d-flex align-items-center col-xxl-3 col-xl-4 col-sm-6">
                                            <div class="service-img">
                                                <img src="{{ $assetUrl($serviceItem['icon']) }}" alt="Icon">
                                            </div>
                                            <div class="service-info">
                                                <p>{{ $serviceItem['name'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

						<!-- Listing Section -->
                        <div class="review-sec mb-0">
                            <div class="review-header">
                                <h4>{{ __('website.car_details.sections.description') }}</h4>
                            </div>
                            <div class="description-list">
                                @foreach ($primaryDescription as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                                @if ($moreDescription->isNotEmpty())
                                    <div class="read-more">
                                        <div class="more-text">
                                            @foreach ($moreDescription as $paragraph)
                                                <p>{{ $paragraph }}</p>
                                            @endforeach
                                        </div>
                                        <a href="javascript:void(0);" class="more-link">{{ __('website.car_details.show_more') }}</a>
                                   </div>
                                @endif
                            </div>
                        </div>
                        <!-- /Listing Section -->

                        <!-- Specifications -->
                        <div class="review-sec specification-card ">
                            <div class="review-header">
                                <h4>{{ __('website.car_details.sections.specifications') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="lisiting-featues">
                                    <div class="row">
                                        @foreach ($specificationItems as $specificationItem)
                                            <div class="featureslist d-flex align-items-center col-xl-3 col-md-4 col-sm-6">
                                                <div class="feature-img">
                                                    <img src="{{ $assetUrl($specificationItem['icon']) }}" alt="Icon">
                                                </div>
                                                <div class="featues-info">
                                                    <span>{{ $specificationItem['label'] }}</span>
                                                    <h6>{{ $specificationItem['value'] }}</h6>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Specifications -->

                        <!-- Car Features -->
                        <div class="review-sec listing-feature">
                            <div class="review-header">
                                <h4>{{ __('website.car_details.sections.features') }}</h4>
                            </div>
                            <div class="listing-description">
                                <div class="row">
                                    @foreach ($featureColumns as $featureColumn)
                                        <div class="col-md-4">
                                            <ul>
                                                @foreach ($featureColumn as $feature)
                                                    <li><span><i class="bx bx-check-double"></i></span>{{ $feature['name'] }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- /Car Features -->

                        <!-- Tariff -->
                        <div class="review-sec listing-feature">
                            <div class="review-header">
                                <h4>{{ __('website.car_details.sections.rental_prices') }}</h4>
                            </div>
                            <div class="table-responsive">
								<table class="table border mb-3">
									<thead class="thead-dark">
										<tr>
											<th>{{ __('website.car_details.pricing.table_plan') }}</th>
											<th>{{ __('website.car_details.pricing.table_price') }}</th>
											<th>{{ __('website.car_details.pricing.table_included_mileage') }}</th>
											<th>{{ __('website.car_details.pricing.table_extra_mileage') }}</th>
										</tr>
									</thead>
									<tbody>
                                        @foreach ($tariffRows as $tariffRow)
                                            <tr>
                                                <td>{{ $tariffRow['name'] }}</td>
                                                <td>{{ $formatPrice($tariffRow['price'], $currencySymbol) }}</td>
                                                <td>{{ filled($tariffRow['mileage']) ? __('website.units.km_included', ['count' => $tariffRow['mileage']]) : __('website.car_details.not_available') }}</td>
                                                <td>{{ __('website.car_details.contact_for_pricing') }}</td>
                                            </tr>
                                        @endforeach
									</tbody>
								</table>
							</div>
                        </div>
                        <!-- /Tariff -->

                        <!-- Gallery -->
                        <div class="review-sec mb-0 pb-0">
                            <div class="review-header">
                                <h4>{{ __('website.car_details.sections.gallery') }}</h4>
                            </div>
                            <div class="gallery-list">
                                <ul>
                                    @foreach ($galleryImages->take(6) as $galleryImage)
                                        @php
                                            $galleryBig = $storageUrl($galleryImage['file_path'] ?? null, $mainImage);
                                            $galleryThumb = $storageUrl($galleryImage['thumbnail_path'] ?? ($galleryImage['file_path'] ?? null), $mainImage);
                                            $galleryAlt = $galleryImage['alt'] ?? $carName;
                                        @endphp
                                        <li>
                                            <div class="gallery-widget">
                                                <a href="{{ $galleryBig }}" data-fancybox="gallery1">
                                                    <img class="img-fluid" alt="{{ $galleryAlt }}" src="{{ $galleryThumb }}">
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!-- /Gallery -->

                    </div>
                    <div class="col-lg-4 theiaStickySidebar">
                        <aside class="car-details-sidebar-card">
                            <div class="car-details-sidebar-head">
                                <h3>{{ $sidebarTitle }}</h3>
                                <p>{{ $sidebarDescription }}</p>
                            </div>

                            <ul class="car-details-sidebar-stats">
                                @foreach ($carSidebarStats as $sidebarStat)
                                    <li>
                                        <i class="{{ $sidebarStat['icon'] }}"></i>
                                        <span>{{ $sidebarStat['value'] }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="car-details-sidebar-overview">
                                <h4>{{ __('website.car_details.sidebar.car_overview') }}</h4>
                                <div class="car-details-overview-grid">
                                    @foreach ($carOverviewItems as $overviewItem)
                                        <div class="car-details-overview-item">
                                            <span class="overview-label">{{ $overviewItem['label'] }}</span>
                                            @if (($overviewItem['type'] ?? '') === 'color')
                                                <span class="car-color-dot" style="background-color: {{ $colorDotHex }};" title="{{ $overviewItem['value'] }}"></span>
                                                <small>{{ $overviewItem['value'] }}</small>
                                            @else
                                                <strong>{{ $overviewItem['value'] }}</strong>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if ($sidebarPriceRows->isNotEmpty())
                                <div class="car-details-sidebar-prices">
                                    @foreach ($sidebarPriceRows as $sidebarPriceRow)
                                        <div class="car-details-price-row">
                                            <div class="price-main-wrap">
                                                <strong>{{ $formatAmount($sidebarPriceRow['current']) }}</strong>
                                                @if (filled($sidebarPriceRow['old']))
                                                    <del>{{ $formatAmount($sidebarPriceRow['old']) }}</del>
                                                @endif
                                            </div>
                                            <span class="price-meta">{{ trim($currencySymbol . ' ' . $sidebarPriceRow['unit']) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if ($sidebarHighlights->isNotEmpty())
                                <ul class="car-details-sidebar-highlights">
                                    @foreach ($sidebarHighlights as $sidebarHighlight)
                                        <li>
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span>{{ $sidebarHighlight['label'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="car-details-sidebar-actions">
                                <a href="{{ $whatsAppHref }}"
                                   class="btn sidebar-action-btn sidebar-whatsapp-btn @if ($whatsAppHref === 'javascript:void(0);') is-disabled @endif"
                                   @if ($whatsAppHref !== 'javascript:void(0);')
                                       target="_blank" rel="noopener noreferrer"
                                   @endif>
                                    <i class="fa-brands fa-whatsapp"></i>
                                    {{ __('website.car_details.owner_details.chat_whatsapp') }}
                                </a>
                                <a href="{{ $phoneHref }}"
                                   class="btn sidebar-action-btn sidebar-call-btn @if ($phoneHref === 'javascript:void(0);') is-disabled @endif">
                                    <i class="fa-solid fa-phone"></i>
                                    {{ __('website.car_details.sidebar.call_us') }}
                                </a>
                            </div>
                        </aside>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="details-car-grid">
                            <div class="details-slider-heading">
                                <h3>{{ __('website.car_details.sections.related_cars') }}</h3>
                                <p>{{ __('website.car_details.related_cars_subtitle') }}</p>
                            </div>
                            <div class="owl-carousel rental-deal-slider details-car owl-theme">

                                @foreach ($relatedItems as $relatedItem)
                                    @php
                                        $relatedImage = $storageUrl($relatedItem['image_path'] ?? null, $assetUrl('img/cars/car-03.jpg'));
                                        $relatedName = $relatedItem['name'] ?? __('website.common.car');
                                        $relatedBrand = $relatedItem['brand_name'] ?? __('website.common.brand');
                                        $relatedGear = $relatedItem['gear_type_name'] ?? __('website.car_details.not_available');
                                        $relatedYear = $relatedItem['year'] ?? __('website.car_details.not_available');
                                        $relatedPassenger = isset($relatedItem['passenger_capacity']) ? __('website.units.persons', ['count' => $relatedItem['passenger_capacity']]) : __('website.car_details.not_available');
                                        $relatedMileage = isset($relatedItem['daily_mileage_included']) ? __('website.units.km_value', ['count' => $relatedItem['daily_mileage_included']]) : __('website.car_details.not_available');
                                        $relatedPrice = $relatedItem['daily_price'] ?? null;
                                        $relatedCurrency = $relatedItem['currency_symbol'] ?? $currencySymbol;
                                        $relatedUrl = $relatedItem['details_url'] ?? route('website.cars.index');
                                    @endphp
                                        <!-- owl carousel item -->
                                        <div class="rental-car-item">
                                            <div class="listing-item pb-0">
                                                <div class="listing-img">
                                                    <a href="{{ $relatedUrl }}">
                                                        <img src="{{ $relatedImage }}" class="img-fluid" alt="{{ $relatedName }}">
                                                    </a>
                                                    <span class="featured-text">{{ $relatedBrand }}</span>
                                                </div>
                                                <div class="listing-content">
                                                    <div class="listing-features d-flex align-items-end justify-content-between">
                                                        <div class="list-rating">
                                                            <h3 class="listing-title">
                                                                <a href="{{ $relatedUrl }}">{{ $relatedName }}</a>
                                                            </h3>
                                                            <div class="list-rating">
                                                                <i class="fas fa-star filled"></i>
                                                                <i class="fas fa-star filled"></i>
                                                                <i class="fas fa-star filled"></i>
                                                                <i class="fas fa-star filled"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span>({{ number_format($reviewScore, 1) }}) {{ $reviewCount }} Reviews</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="listing-details-group">
                                                        <ul>
                                                            <li>
                                                                <span><img src="{{ $assetUrl('img/icons/car-parts-01.svg') }}" alt="Auto"></span>
                                                                <p>{{ $relatedGear }}</p>
                                                            </li>
                                                            <li>
                                                                <span><img src="{{ $assetUrl('img/icons/car-parts-02.svg') }}" alt="Mileage"></span>
                                                                <p>{{ $relatedMileage }}</p>
                                                            </li>
                                                            <li>
                                                                <span><img src="{{ $assetUrl('img/icons/car-parts-03.svg') }}" alt="Category"></span>
                                                                <p>{{ $relatedItem['category_name'] ?? __('website.common.category') }}</p>
                                                            </li>
                                                        </ul>
                                                        <ul>
                                                            <li>
                                                                <span><img src="{{ $assetUrl('img/icons/car-parts-04.svg') }}" alt="Brand"></span>
                                                                <p>{{ $relatedBrand }}</p>
                                                            </li>
                                                            <li>
                                                                <span><img src="{{ $assetUrl('img/icons/car-parts-05.svg') }}" alt="Year"></span>
                                                                <p>{{ $relatedYear }}</p>
                                                            </li>
                                                            <li>
                                                                <span><img src="{{ $assetUrl('img/icons/car-parts-06.svg') }}" alt="Persons"></span>
                                                                <p>{{ $relatedPassenger }}</p>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="listing-location-details">
                                                        <div class="listing-price">
                                                            <span><i class="feather-map-pin"></i></span>{{ filled($fullAddress) ? $fullAddress : __('website.car_details.not_available') }}
                                                        </div>
                                                        <div class="listing-price">
                                                            <h6>{{ $formatPrice($relatedPrice, $relatedCurrency) }} <span>{{ __('website.units.per_day') }}</span></h6>
                                                        </div>
                                                    </div>
                                                    <div class="listing-button">
                                                        <a href="{{ $relatedUrl }}" class="btn btn-order"><span><i class="feather-calendar me-2"></i></span>{{ __('website.common.rent_now') }}</a>
                                                    </div>
                                                </div>
                                                @if (($relatedItem['is_featured'] ?? false) === true)
                                                    <div class="feature-text">
                                                        <span class="bg-danger">{{ __('website.common.featured') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- /owl carousel item -->
                                @endforeach

						</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection
