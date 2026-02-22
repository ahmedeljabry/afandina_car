@php
    use Illuminate\Support\Str;

    $carName = $car['name'] ?? __('website.common.car');
    $carImage = $storageUrl($car['image_path'] ?? null, $assetUrl('img/cars/car-11.jpg'));
    $carBrand = $car['brand_name'] ?? __('website.common.brand');
    $carCategory = $car['category_name'] ?? __('website.common.category');
    $statusRaw = (string) ($car['status'] ?? 'available');
    $carStatus = __('website.status.' . $statusRaw);
    if ($carStatus === 'website.status.' . $statusRaw) {
        $carStatus = ucfirst(str_replace('_', ' ', $statusRaw));
    }
    $carPrice = $car['daily_price'] ?? null;
    $carMainPrice = $car['daily_main_price'] ?? null;
    $carCurrency = $car['currency_symbol'] ?? '$';
    $carDetailsUrl = $car['details_url'] ?? (!empty($car['id']) ? route('website.cars.show', ['car' => $car['id']]) : 'javascript:void(0);');
    $showDiscount = $showDiscount ?? false;

    $specItems = collect([
        ['icon' => $assetUrl('img/icons/car-parts-01.svg'), 'alt' => 'Gear', 'value' => $car['gear_type_name'] ?? null],
        ['icon' => $assetUrl('img/icons/car-parts-02.svg'), 'alt' => 'Mileage', 'value' => isset($car['daily_mileage_included']) ? __('website.units.km_value', ['count' => $car['daily_mileage_included']]) : null],
        ['icon' => $assetUrl('img/icons/car-parts-03.svg'), 'alt' => 'Passengers', 'value' => isset($car['passenger_capacity']) ? __('website.units.persons', ['count' => $car['passenger_capacity']]) : null],
        ['icon' => $assetUrl('img/icons/car-parts-05.svg'), 'alt' => 'Year', 'value' => $car['year'] ?? null],
    ])->filter(fn (array $item) => filled($item['value']))->values();
@endphp

<div class="listing-item listing-item-two flex-fill">
    <div class="listing-img">
        <a href="{{ $carDetailsUrl }}">
            <img src="{{ $carImage }}" class="img-fluid" alt="{{ $carName }}">
        </a>
        <div class="fav-item">
            <div class="d-flex align-items-center gap-2">
                @if($showDiscount && !empty($car['discount_rate']))
                    <span class="featured-text">{{ (int) $car['discount_rate'] }}% OFF</span>
                @else
                    <span class="featured-text">{{ $carBrand }}</span>
                @endif
                <span class="availability">{{ $carStatus }}</span>
            </div>
            <a href="javascript:void(0);" class="fav-icon">
                <i class="feather-heart"></i>
            </a>
        </div>
        <span class="location"><i class="bx bx-map me-1"></i>{{ $carCategory }}</span>
    </div>
    <div class="listing-content">
        <div class="listing-features d-flex align-items-center justify-content-between">
            <div class="list-rating">
                <h3 class="listing-title">
                    <a href="{{ $carDetailsUrl }}">{{ Str::limit($carName, 36) }}</a>
                </h3>
                <p class="mb-0 text-muted">{{ $carBrand }}</p>
            </div>
            <div class="text-end">
                @if($carPrice)
                    <h4 class="price mb-0">{{ $carCurrency }}{{ number_format((int) $carPrice) }} <span>{{ __('website.units.per_day') }}</span></h4>
                    @if($showDiscount && $carMainPrice && $carMainPrice > $carPrice)
                        <small class="text-muted text-decoration-line-through">
                            {{ $carCurrency }}{{ number_format((int) $carMainPrice) }}
                        </small>
                    @endif
                @else
                    <h4 class="price mb-0">{{ __('website.common.call_for_price') }}</h4>
                @endif
            </div>
        </div>
        @if($specItems->isNotEmpty())
            <div class="listing-details-group">
                <ul>
                    @foreach($specItems as $item)
                        <li>
                            <img src="{{ $item['icon'] }}" alt="{{ $item['alt'] }}">
                            <p>{{ $item['value'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
