@extends('layouts.website')
@section('title', $carDetails['name'] ?? __('website.car_details.page_title'))

@section('content')
    @php
        use App\Support\CarWhatsApp;
        use App\Support\CmsHtmlLinks;
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
        $formatCarCardTitle = static function (?string $name): string {
            $fullName = trim((string) $name);
            $words = preg_split('/\s+/', $fullName, -1, PREG_SPLIT_NO_EMPTY) ?: [];

            if (count($words) <= 1) {
                return $fullName;
            }

            return implode(' ', array_slice($words, 1, 4));
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

        $galleryLightboxItems = $galleryImages
            ->map(function (array $image) use ($storageUrl, $mainImage, $carName) {
                return [
                    'src' => $storageUrl($image['file_path'] ?? null, $mainImage),
                    'thumbSrc' => $storageUrl($image['thumbnail_path'] ?? ($image['file_path'] ?? null), $mainImage),
                    'caption' => $image['alt'] ?? $carName,
                ];
            })
            ->filter(fn (array $image) => filled($image['src']))
            ->values();

        $galleryImageCount = max(1, $galleryImages->count());

        $decodeStoredHtml = static function (?string $value): string {
            $value = trim((string) $value);

            if (preg_match('/&lt;\\/?[a-z][\\s\\S]*?&gt;/i', $value)) {
                return html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }

            return $value;
        };

        $shortDescription = $decodeStoredHtml($carDetails['description'] ?? '');
        $longDescription = $decodeStoredHtml($carDetails['long_description'] ?? '');
        $preferredDescription = filled(trim(strip_tags($longDescription))) || preg_match('/<\\/?[a-z][\\s\\S]*?>/i', $longDescription)
            ? $longDescription
            : $shortDescription;

        $descriptionHasHtml = preg_match('/<\\/?[a-z][\\s\\S]*?>/i', $preferredDescription);
        $descriptionPlainText = trim(preg_replace('/\\s+/', ' ', strip_tags($preferredDescription)));

        if (filled($descriptionPlainText)) {
            $descriptionHtml = $descriptionHasHtml
                ? $preferredDescription
                : collect(preg_split('/(?:\\r\\n|\\r|\\n){2,}/', $preferredDescription) ?: [])
                    ->map(fn ($value) => trim((string) $value))
                    ->filter(fn ($value) => filled($value))
                    ->map(fn ($value) => '<p>' . nl2br(e($value), false) . '</p>')
                    ->implode('');
        } else {
            $descriptionHtml = '<p>' . e(__('website.car_details.not_available')) . '</p>';
            $descriptionPlainText = __('website.car_details.not_available');
        }
        $descriptionHtml = CmsHtmlLinks::redirectBrokenInternalLinks($descriptionHtml);

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
        $sidebarDescription = Str::limit($descriptionPlainText, 180);
        $carSlug = $carDetails['slug'] ?? __('website.car_details.not_available');
        $carDetailsUrl = $carDetails['details_url'] ?? url()->current();
        $brandUrl = $carDetails['brand_url'] ?? route('website.cars.index');
        $categoryUrl = $carDetails['category_url'] ?? route('website.cars.index');
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
        $hasPhoneHref = $phoneHref !== 'javascript:void(0);';
        $buildCarWhatsAppHref = static fn (array $carData): ?string => CarWhatsApp::url($contact?->whatsapp, $carData);
        $carDetailsWhatsAppHref = $buildCarWhatsAppHref($carDetails);

        $relatedItems = collect($relatedCars ?? [])
            ->unique(fn ($item) => data_get($item, 'details_url') ?: data_get($item, 'id'))
            ->values();
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
                    'door_count' => $carDetails['door_count'] ?? null,
                    'daily_price' => $dailyPrice,
                    'weekly_price' => $weeklyPrice,
                    'monthly_price' => $monthlyPrice,
                    'currency_symbol' => $currencySymbol,
                    'image_path' => $carDetails['image_path'] ?? null,
                    'is_featured' => (bool) ($carDetails['is_featured'] ?? false),
                    'details_url' => url()->current(),
                ],
            ]);
        }

        $schemaImageUrls = $galleryImages
            ->map(fn ($image) => $storageUrl($image['file_path'] ?? null))
            ->filter(fn ($url) => filled($url))
            ->unique()
            ->values();

        if ($schemaImageUrls->isEmpty() && filled($mainImage)) {
            $schemaImageUrls = collect([$mainImage]);
        }

        $schemaDescription = trim((string) ($descriptionPlainText ?: $sidebarDescription ?: $carName));
        $schemaAvailability = $statusRaw === 'available'
            ? 'https://schema.org/InStock'
            : 'https://schema.org/OutOfStock';
        $schemaPriceValidUntil = now()->addYear()->toDateString();
        $schemaCurrencyCode = (string) ($carDetails['currency_code'] ?? 'AED');

        $schemaOffers = collect([
            ['label' => 'daily', 'price' => $dailyPrice],
            ['label' => 'weekly', 'price' => $weeklyPrice],
            ['label' => 'monthly', 'price' => $monthlyPrice],
        ])->filter(fn ($offer) => filled($offer['price']))
            ->map(function (array $offer) use ($schemaCurrencyCode, $schemaAvailability, $schemaPriceValidUntil, $carDetailsUrl) {
                return [
                    '@type' => 'Offer',
                    'price' => (string) $offer['price'],
                    'priceCurrency' => $schemaCurrencyCode,
                    'priceValidUntil' => $schemaPriceValidUntil,
                    'availability' => $schemaAvailability,
                    'url' => $carDetailsUrl,
                ];
            })
            ->values();

        $productSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $sidebarTitle,
            'url' => $carDetailsUrl,
            'sku' => (string) ($carDetails['id'] ?? ''),
            'mpn' => (string) ($carDetails['id'] ?? ''),
            'image' => $schemaImageUrls->all(),
            'description' => $schemaDescription,
            'brand' => [
                '@type' => 'Brand',
                'name' => $brandName,
            ],
            'category' => $categoryName,
        ];

        if (filled($colorName)) {
            $productSchema['color'] = $colorName;
        }

        if ($schemaOffers->isNotEmpty()) {
            $productSchema['offers'] = $schemaOffers->all();
        }
    @endphp

    @push('head')
        <script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
    @endpush

    <style>
        .car-details-gallery-prices {
            margin-top: 20px;
            padding: 20px;
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
        }

        .car-details-gallery-prices h4 {
            margin: 0 0 14px;
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .car-details-gallery-price-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
        }

        .car-details-gallery-price-card {
            background: #fff;
            border-radius: 14px;
            padding: 14px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        .car-details-gallery-price-card .price-label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #6b7280;
        }

        .car-details-gallery-price-card .price-main-wrap {
            display: flex;
            align-items: baseline;
            gap: 8px;
        }

        .car-details-gallery-price-card .price-main-wrap strong {
            font-size: 22px;
            line-height: 1.1;
            color: #111827;
        }

        .car-details-gallery-price-card .price-main-wrap del {
            color: #9ca3af;
            font-size: 14px;
        }

        .car-details-gallery-price-card .price-meta {
            display: block;
            margin-top: 6px;
            color: #2563eb;
            font-size: 13px;
            font-weight: 600;
        }

        .product-img {
            position: relative;
        }

        .car-details-gallery-trigger {
            display: block;
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            cursor: zoom-in;
        }

        .car-details-gallery-trigger img {
            display: block;
            width: 100%;
        }

        .car-details-gallery-hint {
            position: absolute;
            top: 18px;
            inset-inline-end: 18px;
            width: 44px;
            height: 44px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(17, 24, 39, 0.72);
            color: #fff;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.2);
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .car-details-gallery-trigger:hover .car-details-gallery-hint,
        .car-details-gallery-trigger:focus-visible .car-details-gallery-hint {
            transform: scale(1.05);
            background: rgba(37, 99, 235, 0.86);
        }

        .car-details-gallery-trigger:focus-visible {
            outline: 3px solid rgba(37, 99, 235, 0.3);
            outline-offset: 4px;
        }

        .details-car-grid .related-cars-card .related-card-title a {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .details-car-grid .related-cars-card .listing-details-group ul li {
            min-width: 0;
        }

        .details-car-grid .related-cars-card .listing-details-group ul li p {
            min-width: 0;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .details-car-grid .related-cars-card .listing-action-group .listing-action-btn {
            flex: 1 1 0;
            width: 0;
            min-width: 0;
            height: 48px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 600;
            padding: 0 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: background-color .25s ease, border-color .25s ease, color .25s ease, box-shadow .25s ease;
        }

        .details-car-grid .related-cars-card .listing-action-group .listing-action-btn .action-label {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .details-car-grid .related-cars-card .listing-action-group .whatsapp-btn {
            background: #1faf64;
            border-color: #1faf64;
            color: #fff;
        }

        .details-car-grid .related-cars-card .listing-action-group .whatsapp-btn:hover,
        .details-car-grid .related-cars-card .listing-action-group .whatsapp-btn:focus {
            background: #14824a;
            border-color: #14824a;
            color: #fff;
            box-shadow: none;
        }

        .details-car-grid .related-cars-card .listing-action-group .call-btn {
            background: #dc2626;
            border: 1px solid #dc2626;
            color: #fff;
        }

        .details-car-grid .related-cars-card .listing-action-group .call-btn:hover,
        .details-car-grid .related-cars-card .listing-action-group .call-btn:focus {
            background: #b91c1c;
            border-color: #b91c1c;
            color: #fff;
        }

        .details-car-grid .related-cars-card .listing-action-group .disabled {
            opacity: 0.55;
            pointer-events: none;
        }

        .read-more .more-link {
            border: 0;
            background: transparent;
            padding: 0;
            padding-inline-start: 20px;
            cursor: pointer;
        }

        .read-more .more-link:focus-visible {
            outline: 2px solid #121212;
            outline-offset: 3px;
        }

        .car-details-editor-content {
            color: #4f5761;
            font-size: 16px;
            line-height: 1.75;
        }

        .car-details-editor-content > *:last-child {
            margin-bottom: 0;
        }

        .car-details-editor-content p,
        .car-details-editor-content li {
            color: #4f5761;
            font-size: 16px;
            line-height: 1.75;
        }

        .car-details-editor-content p {
            margin-bottom: 14px;
        }

        .car-details-editor-content h1,
        .car-details-editor-content h2,
        .car-details-editor-content h3,
        .car-details-editor-content h4,
        .car-details-editor-content h5,
        .car-details-editor-content h6 {
            margin-top: 18px;
            margin-bottom: 10px;
            color: #1f1f1f;
            font-weight: 700;
            line-height: 1.35;
        }

        .car-details-editor-content h1 {
            font-size: 30px;
        }

        .car-details-editor-content h2 {
            font-size: 26px;
        }

        .car-details-editor-content h3 {
            font-size: 23px;
        }

        .car-details-editor-content h4,
        .car-details-editor-content h5,
        .car-details-editor-content h6 {
            font-size: 20px;
        }

        .car-details-editor-content ul,
        .car-details-editor-content ol {
            margin: 0 0 16px;
            padding-inline-start: 1.4rem;
        }

        .car-details-editor-content li {
            margin-bottom: 8px;
        }

        .car-details-editor-content li[data-list="bullet"] {
            list-style-type: disc;
        }

        .car-details-editor-content li[data-list="ordered"] {
            list-style-type: decimal;
        }

        .car-details-editor-content .ql-ui {
            display: none;
        }

        .car-details-editor-content .ql-align-center {
            text-align: center;
        }

        .car-details-editor-content .ql-align-right {
            text-align: right;
        }

        .car-details-editor-content .ql-align-justify {
            text-align: justify;
        }

        .car-details-editor-content .ql-indent-1 {
            padding-inline-start: 3em;
        }

        .car-details-editor-content .ql-indent-2 {
            padding-inline-start: 6em;
        }

        .car-details-editor-content .ql-indent-3 {
            padding-inline-start: 9em;
        }

        .car-details-editor-content blockquote {
            margin: 0 0 16px;
            padding: 12px 16px;
            border-inline-start: 4px solid #127384;
            background: #f7fbfc;
            color: #334155;
        }

        .car-details-editor-content a {
            color: #127384;
            text-decoration: underline;
        }

        .car-details-editor-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
        }

        .car-details-editor-content table {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: collapse;
        }

        .car-details-editor-content table th,
        .car-details-editor-content table td {
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .car-details-sidebar-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 24px;
        }

        .sidebar-action-btn {
            min-height: 56px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px;
            font-size: 15px;
            font-weight: 700;
            border: 1px solid transparent;
            box-shadow: none;
            transition: background-color .25s ease, border-color .25s ease, color .25s ease, box-shadow .25s ease;
        }

        .sidebar-action-btn i {
            font-size: 18px;
        }

        .sidebar-call-btn {
            background: #dc2626;
            border-color: #dc2626;
            color: #fff;
        }

        .sidebar-call-btn:hover,
        .sidebar-call-btn:focus {
            background: #b91c1c;
            border-color: #b91c1c;
            color: #fff;
        }

        .sidebar-whatsapp-btn {
            background: #1faf64;
            border-color: #1faf64;
            color: #fff;
        }

        .sidebar-whatsapp-btn:hover,
        .sidebar-whatsapp-btn:focus {
            background: #14824a;
            border-color: #14824a;
            color: #fff;
            box-shadow: none;
        }

        .sidebar-action-btn.is-disabled {
            opacity: 0.55;
            pointer-events: none;
        }

        .car-details-mobile-contact-bar {
            display: none;
        }

        @media (max-width: 991.98px) {
            .product-details {
                padding-bottom: 120px;
            }

            .car-details-sidebar-actions {
                display: none;
            }

            .car-details-mobile-contact-bar {
                position: fixed;
                left: 12px;
                right: 12px;
                bottom: calc(12px + env(safe-area-inset-bottom));
                z-index: 1040;
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
                padding: 12px;
                border-radius: 18px;
                background: rgba(255, 255, 255, 0.96);
                box-shadow: 0 20px 40px rgba(15, 23, 42, 0.18);
                backdrop-filter: blur(14px);
            }
        }

        @media (max-width: 575.98px) {
            .car-details-gallery-prices {
                margin-top: 16px;
                padding: 16px;
            }

            .car-details-gallery-price-card {
                padding: 12px;
            }

            .car-details-gallery-price-card .price-main-wrap strong {
                font-size: 20px;
            }

            .car-details-gallery-hint {
                top: 12px;
                inset-inline-end: 12px;
                width: 38px;
                height: 38px;
            }

            .car-details-mobile-contact-bar {
                left: 10px;
                right: 10px;
                bottom: calc(10px + env(safe-area-inset-bottom));
                gap: 10px;
                padding: 10px;
            }

            .car-details-mobile-contact-bar .sidebar-action-btn {
                min-height: 52px;
                padding: 10px 12px;
                font-size: 14px;
            }

            .details-car-grid .related-cars-card .listing-action-group .listing-action-btn {
                height: 46px;
                font-size: 14px;
                padding: 0 10px;
            }
        }
    </style>

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
                                        <a href="{{ $categoryUrl }}" class="text-reset text-decoration-none">{{ $categoryName }}</a>
                                    </div>
                                </li>
                                <li>
                                    <span class="year">{{ $yearValue }}</span>
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
                                        <span>{{ __('website.car_details.labels.brand') }} : <a href="{{ $brandUrl }}" class="text-reset text-decoration-none">{{ $brandName }}</a></span>
                                    </div>
                                    <div class="camaro-location-inner">
                                        <i class='bx bx-car'></i>
                                        <span>{{ __('website.car_details.labels.listed_on') }} : {{ $listedOn }}</span>
                                    </div>
                                    <div class="camaro-location-inner">
                                        <i class='bx bx-purchase-tag-alt'></i>
                                        <span>Slug : <a href="{{ $carDetailsUrl }}" class="text-reset text-decoration-none">{{ $carSlug }}</a></span>
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
                                        <a href="{{ $imageUrl }}"
                                           class="car-details-gallery-trigger"
                                           data-gallery-index="{{ $loop->index }}"
                                           aria-label="{{ __('website.car_details.sections.gallery') }} {{ $loop->iteration }} / {{ $galleryImageCount }}">
                                            <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}">
                                            <span class="car-details-gallery-hint" aria-hidden="true">
                                                <i class="fa-solid fa-expand"></i>
                                            </span>
                                        </a>
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
                            @if ($sidebarPriceRows->isNotEmpty())
                                <div class="car-details-gallery-prices">
                                    <h4>{{ __('website.car_details.sections.rental_prices') }}</h4>
                                    <div class="car-details-gallery-price-grid">
                                        @foreach ($sidebarPriceRows as $sidebarPriceRow)
                                            <div class="car-details-gallery-price-card">
                                                <span class="price-label">{{ $sidebarPriceRow['label'] }}</span>
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
                                </div>
                            @endif
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
                            <div class="description-list car-details-editor-content">
                                {!! $descriptionHtml !!}
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
                                @if ($hasPhoneHref)
                                    <a href="{{ $phoneHref }}"
                                       class="btn sidebar-action-btn sidebar-call-btn">
                                        <i class="fa-solid fa-phone"></i>
                                        {{ __('website.car_details.sidebar.call_us') }}
                                    </a>
                                @else
                                    <button
                                        type="button"
                                        class="btn sidebar-action-btn sidebar-call-btn is-disabled"
                                        aria-disabled="true"
                                        disabled>
                                        <i class="fa-solid fa-phone"></i>
                                        {{ __('website.car_details.sidebar.call_us') }}
                                    </button>
                                @endif
                                @if ($carDetailsWhatsAppHref)
                                    <a href="{{ $carDetailsWhatsAppHref }}"
                                       class="btn sidebar-action-btn sidebar-whatsapp-btn"
                                       target="_blank"
                                       rel="noopener noreferrer">
                                        <i class="fa-brands fa-whatsapp"></i>
                                        {{ __('website.car_details.owner_details.chat_whatsapp') }}
                                    </a>
                                @else
                                    <button
                                        type="button"
                                        class="btn sidebar-action-btn sidebar-whatsapp-btn is-disabled"
                                        aria-disabled="true"
                                        disabled>
                                        <i class="fa-brands fa-whatsapp"></i>
                                        {{ __('website.car_details.owner_details.chat_whatsapp') }}
                                    </button>
                                @endif
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
                                        $relatedCardTitle = $formatCarCardTitle($relatedName);
                                        $relatedBrand = $relatedItem['brand_name'] ?? __('website.common.brand');
                                        $relatedCategory = $relatedItem['category_name'] ?? __('website.common.category');
                                        $relatedGear = $relatedItem['gear_type_name'] ?? __('website.car_details.not_available');
                                        $relatedYear = $relatedItem['year'] ?? __('website.car_details.not_available');
                                        $relatedPassenger = isset($relatedItem['passenger_capacity']) ? __('website.units.persons', ['count' => $relatedItem['passenger_capacity']]) : __('website.car_details.not_available');
                                        $relatedDoors = isset($relatedItem['door_count']) ? __('website.units.doors', ['count' => $relatedItem['door_count']]) : __('website.car_details.not_available');
                                        $relatedMileage = isset($relatedItem['daily_mileage_included']) ? __('website.units.km_value', ['count' => $relatedItem['daily_mileage_included']]) : __('website.car_details.not_available');
                                        $relatedDailyPrice = $relatedItem['daily_price'] ?? null;
                                        $relatedMonthlyPrice = $relatedItem['monthly_price'] ?? null;
                                        $relatedCurrency = $relatedItem['currency_symbol'] ?? $currencySymbol;
                                        $relatedUrl = $relatedItem['details_url'] ?? route('website.cars.index');
                                        $relatedWhatsAppHref = $buildCarWhatsAppHref($relatedItem);
                                    @endphp
                                        <!-- owl carousel item -->
                                        <div class="rental-car-item">
                                            <div class="listing-item related-cars-card pb-0">
                                                <div class="listing-img">
                                                    <a href="{{ $relatedUrl }}">
                                                        <img src="{{ $relatedImage }}" class="img-fluid" alt="{{ $relatedName }}">
                                                    </a>
                                                    <span class="featured-text d-inline-block"
                                                          style="max-width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        {{ $relatedBrand }}
                                                    </span>
                                                </div>
                                                <div class="listing-content">
                                                    <div class="listing-features d-flex align-items-end justify-content-between">
                                                        <div class="list-rating">
                                                            <h3 class="listing-title related-card-title mb-0">
                                                                <a href="{{ $relatedUrl }}" title="{{ $relatedName }}">{{ $relatedCardTitle }}</a>
                                                            </h3>
                                                        </div>
                                                        <div class="list-km">
                                                            <span class="km-count">
                                                                <img src="{{ $assetUrl('img/icons/map-pin.svg') }}" alt="Year">{{ $relatedYear }}
                                                            </span>
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
                                                                <p>{{ $relatedCategory }}</p>
                                                            </li>
                                                        </ul>
                                                        <ul>
                                                            <li>
                                                                <span><img src="{{ $assetUrl('img/icons/car-parts-04.svg') }}" alt="Doors"></span>
                                                                <p>{{ $relatedDoors }}</p>
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
                                                    <div class="listing-price-section"
                                                         style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 8px; padding: 12px; margin-bottom: 15px;">
                                                        <div class="d-flex justify-content-between align-items-center gap-2">
                                                            <div class="price-box text-center flex-fill"
                                                                 style="background: #fff; border-radius: 6px; padding: 10px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                                                <div style="font-size: 11px; color: #6c757d; font-weight: 500; text-transform: uppercase; margin-bottom: 4px;">
                                                                    <i class="feather-sun" style="font-size: 12px;"></i>
                                                                    {{ __('website.units.per_day') }}
                                                                </div>
                                                                @if($relatedDailyPrice)
                                                                    <div style="font-size: 16px; font-weight: 700; color: #f66962;">
                                                                        {{ $formatPrice($relatedDailyPrice, $relatedCurrency) }}
                                                                    </div>
                                                                @else
                                                                    <div style="font-size: 13px; color: #6c757d;">
                                                                        {{ __('website.common.call_for_price') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="price-box text-center flex-fill"
                                                                 style="background: #fff; border-radius: 6px; padding: 10px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                                                <div style="font-size: 11px; color: #6c757d; font-weight: 500; text-transform: uppercase; margin-bottom: 4px;">
                                                                    <i class="feather-calendar" style="font-size: 12px;"></i>
                                                                    {{ __('website.units.per_month') }}
                                                                </div>
                                                                @if($relatedMonthlyPrice)
                                                                    <div style="font-size: 16px; font-weight: 700; color: #127384;">
                                                                        {{ $formatPrice($relatedMonthlyPrice, $relatedCurrency) }}
                                                                    </div>
                                                                @else
                                                                    <div style="font-size: 13px; color: #6c757d;">-</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="listing-button d-flex gap-2 listing-action-group">
                                                        @if ($relatedWhatsAppHref)
                                                            <a href="{{ $relatedWhatsAppHref }}"
                                                               class="btn listing-action-btn whatsapp-btn"
                                                               target="_blank"
                                                               rel="noopener noreferrer">
                                                                <i class="fa-brands fa-whatsapp"></i>
                                                                <span class="action-label">{{ __('website.car_details.owner_details.chat_whatsapp') }}</span>
                                                            </a>
                                                        @else
                                                            <button
                                                                type="button"
                                                                class="btn listing-action-btn whatsapp-btn disabled"
                                                                aria-disabled="true"
                                                                disabled>
                                                                <i class="fa-brands fa-whatsapp"></i>
                                                                <span class="action-label">{{ __('website.car_details.owner_details.chat_whatsapp') }}</span>
                                                            </button>
                                                        @endif
                                                        @if ($hasPhoneHref)
                                                            <a href="{{ $phoneHref }}"
                                                               class="btn listing-action-btn call-btn">
                                                                <i class="fa-solid fa-phone"></i>
                                                                <span class="action-label">{{ __('website.car_details.sidebar.call_us') }}</span>
                                                            </a>
                                                        @else
                                                            <button
                                                                type="button"
                                                                class="btn listing-action-btn call-btn disabled"
                                                                aria-disabled="true"
                                                                disabled>
                                                                <i class="fa-solid fa-phone"></i>
                                                                <span class="action-label">{{ __('website.car_details.sidebar.call_us') }}</span>
                                                            </button>
                                                        @endif
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

        <div class="car-details-mobile-contact-bar">
            @if ($hasPhoneHref)
                <a href="{{ $phoneHref }}"
                   class="btn sidebar-action-btn sidebar-call-btn">
                    <i class="fa-solid fa-phone"></i>
                    {{ __('website.car_details.sidebar.call_us') }}
                </a>
            @else
                <button
                    type="button"
                    class="btn sidebar-action-btn sidebar-call-btn is-disabled"
                    aria-disabled="true"
                    disabled>
                    <i class="fa-solid fa-phone"></i>
                    {{ __('website.car_details.sidebar.call_us') }}
                </button>
            @endif
            @if ($carDetailsWhatsAppHref)
                <a href="{{ $carDetailsWhatsAppHref }}"
                   class="btn sidebar-action-btn sidebar-whatsapp-btn"
                   target="_blank"
                   rel="noopener noreferrer">
                    <i class="fa-brands fa-whatsapp"></i>
                    {{ __('website.car_details.owner_details.chat_whatsapp') }}
                </a>
            @else
                <button
                    type="button"
                    class="btn sidebar-action-btn sidebar-whatsapp-btn is-disabled"
                    aria-disabled="true"
                    disabled>
                    <i class="fa-brands fa-whatsapp"></i>
                    {{ __('website.car_details.owner_details.chat_whatsapp') }}
                </button>
            @endif
        </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const galleryItems = @json($galleryLightboxItems->all());

            if (!window.Fancybox || !Array.isArray(galleryItems) || galleryItems.length === 0) {
                return;
            }

            document.querySelectorAll('.car-details-gallery-trigger').forEach(function (trigger) {
                trigger.addEventListener('click', function (event) {
                    event.preventDefault();

                    const startIndex = Number.parseInt(this.dataset.galleryIndex || '0', 10);

                    window.Fancybox.show(galleryItems, {
                        startIndex: Number.isNaN(startIndex) ? 0 : startIndex,
                    });
                });
            });
        });
    </script>
@endpush
