
<?php $__env->startSection('title', $carDetails['name'] ?? __('website.car_details.page_title')); ?>

<?php $__env->startSection('content'); ?>
    <?php
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
    ?>

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
                                            <img src="<?php echo e($assetUrl('img/icons/car-icon.svg')); ?>" alt="img">
                                        </span>
                                        <?php echo e($categoryName); ?>

                                    </div>
                                </li>
                                <li>
                                    <span class="year"><?php echo e($yearValue); ?></span>
                                </li>
                                <li class="ratings">
                                    <i class="fas fa-star filled"></i>
                                    <i class="fas fa-star filled"></i>
                                    <i class="fas fa-star filled"></i>
                                    <i class="fas fa-star filled"></i>
                                    <i class="fas fa-star filled"></i>
                                    <span class="d-inline-block average-list-rating">(<?php echo e(number_format($reviewScore, 1)); ?>)</span>
                                </li>
                            </ul>
                            <div class="camaro-info">
                                <h3><?php echo e($carName); ?></h3>
                                <div class="camaro-location">
                                    <div class="camaro-location-inner">
                                        <i class='bx bx-map'></i>
                                        <span><?php echo e(__('website.car_details.labels.location')); ?> : <?php echo e(filled($fullAddress) ? $fullAddress : __('website.car_details.not_available')); ?></span>
                                    </div>
                                    <div class="camaro-location-inner">
                                        <i class='bx bx-show'></i>
                                        <span><?php echo e(__('website.car_details.labels.brand')); ?> : <?php echo e($brandName); ?></span>
                                    </div>
                                    <div class="camaro-location-inner">
                                        <i class='bx bx-car'></i>
                                        <span><?php echo e(__('website.car_details.labels.listed_on')); ?> : <?php echo e($listedOn); ?></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="details-btn">
                        <span class="total-badge"><i class='bx bx-calendar-edit'></i><?php echo e(__('website.car_details.labels.status')); ?> : <?php echo e($statusLabel); ?></span>
                        <a href="<?php echo e(route('website.cars.index')); ?>"> <i class='bx bx-git-compare'></i><?php echo e(__('website.nav.all_cars')); ?></a>
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
                                    <span class="badge-km"><i class="fa-solid fa-person-walking"></i><?php echo e($statusLabel); ?></span>
                                    <a href="javascript:void(0);" class="fav-icon"><i class="fa-regular fa-heart"></i></a>
                                </div>
                                <ul>
                                    <li class="del-airport"><i class="fa-solid fa-check"></i><?php echo e(__('website.car_details.highlights.free_delivery')); ?> : <?php echo e(($carDetails['free_delivery'] ?? false) ? __('website.car_details.yes') : __('website.car_details.no')); ?></li>
                                    <li class="del-home"><i class="fa-solid fa-check"></i><?php echo e(__('website.car_details.highlights.insurance_included')); ?> : <?php echo e(($carDetails['insurance_included'] ?? false) ? __('website.car_details.yes') : __('website.car_details.no')); ?></li>
                                </ul>
                            </div>
                            <div class="slider detail-bigimg">
                                <?php $__currentLoopData = $galleryImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $imageUrl = $storageUrl($image['file_path'] ?? null, $mainImage);
                                        $imageAlt = $image['alt'] ?? $carName;
                                    ?>
                                    <div class="product-img">
                                        <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($imageAlt); ?>">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="slider slider-nav-thumbnails">
                                <?php $__currentLoopData = $galleryImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $thumbnailUrl = $storageUrl($image['thumbnail_path'] ?? ($image['file_path'] ?? null), $mainImage);
                                        $thumbnailAlt = $image['alt'] ?? $carName;
                                    ?>
                                    <div><img src="<?php echo e($thumbnailUrl); ?>" alt="<?php echo e($thumbnailAlt); ?>"></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="review-sec pb-0">
                            <div class="review-header">
                                <h4><?php echo e(__('website.car_details.sections.extra_services')); ?></h4>
                            </div>
                            <div class="lisiting-service">
                                <div class="row">
                                    <?php $__currentLoopData = $serviceItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $serviceItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="servicelist d-flex align-items-center col-xxl-3 col-xl-4 col-sm-6">
                                            <div class="service-img">
                                                <img src="<?php echo e($assetUrl($serviceItem['icon'])); ?>" alt="Icon">
                                            </div>
                                            <div class="service-info">
                                                <p><?php echo e($serviceItem['name']); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>

						<!-- Listing Section -->
                        <div class="review-sec mb-0">
                            <div class="review-header">
                                <h4><?php echo e(__('website.car_details.sections.description')); ?></h4>
                            </div>
                            <div class="description-list">
                                <?php $__currentLoopData = $primaryDescription; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paragraph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <p><?php echo e($paragraph); ?></p>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if($moreDescription->isNotEmpty()): ?>
                                    <div class="read-more">
                                        <div class="more-text">
                                            <?php $__currentLoopData = $moreDescription; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paragraph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <p><?php echo e($paragraph); ?></p>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <a href="javascript:void(0);" class="more-link"><?php echo e(__('website.car_details.show_more')); ?></a>
                                   </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- /Listing Section -->

                        <!-- Specifications -->
                        <div class="review-sec specification-card ">
                            <div class="review-header">
                                <h4><?php echo e(__('website.car_details.sections.specifications')); ?></h4>
                            </div>
                            <div class="card-body">
                                <div class="lisiting-featues">
                                    <div class="row">
                                        <?php $__currentLoopData = $specificationItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specificationItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="featureslist d-flex align-items-center col-xl-3 col-md-4 col-sm-6">
                                                <div class="feature-img">
                                                    <img src="<?php echo e($assetUrl($specificationItem['icon'])); ?>" alt="Icon">
                                                </div>
                                                <div class="featues-info">
                                                    <span><?php echo e($specificationItem['label']); ?></span>
                                                    <h6><?php echo e($specificationItem['value']); ?></h6>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Specifications -->

                        <!-- Car Features -->
                        <div class="review-sec listing-feature">
                            <div class="review-header">
                                <h4><?php echo e(__('website.car_details.sections.features')); ?></h4>
                            </div>
                            <div class="listing-description">
                                <div class="row">
                                    <?php $__currentLoopData = $featureColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $featureColumn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-4">
                                            <ul>
                                                <?php $__currentLoopData = $featureColumn; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><span><i class="bx bx-check-double"></i></span><?php echo e($feature['name']); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                        <!-- /Car Features -->

                        <!-- Tariff -->
                        <div class="review-sec listing-feature">
                            <div class="review-header">
                                <h4><?php echo e(__('website.car_details.sections.rental_prices')); ?></h4>
                            </div>
                            <div class="table-responsive">
								<table class="table border mb-3">
									<thead class="thead-dark">
										<tr>
											<th><?php echo e(__('website.car_details.pricing.table_plan')); ?></th>
											<th><?php echo e(__('website.car_details.pricing.table_price')); ?></th>
											<th><?php echo e(__('website.car_details.pricing.table_included_mileage')); ?></th>
											<th><?php echo e(__('website.car_details.pricing.table_extra_mileage')); ?></th>
										</tr>
									</thead>
									<tbody>
                                        <?php $__currentLoopData = $tariffRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tariffRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($tariffRow['name']); ?></td>
                                                <td><?php echo e($formatPrice($tariffRow['price'], $currencySymbol)); ?></td>
                                                <td><?php echo e(filled($tariffRow['mileage']) ? __('website.units.km_included', ['count' => $tariffRow['mileage']]) : __('website.car_details.not_available')); ?></td>
                                                <td><?php echo e(__('website.car_details.contact_for_pricing')); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</tbody>
								</table>
							</div>
                        </div>
                        <!-- /Tariff -->

                        <!-- Gallery -->
                        <div class="review-sec mb-0 pb-0">
                            <div class="review-header">
                                <h4><?php echo e(__('website.car_details.sections.gallery')); ?></h4>
                            </div>
                            <div class="gallery-list">
                                <ul>
                                    <?php $__currentLoopData = $galleryImages->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $galleryImage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $galleryBig = $storageUrl($galleryImage['file_path'] ?? null, $mainImage);
                                            $galleryThumb = $storageUrl($galleryImage['thumbnail_path'] ?? ($galleryImage['file_path'] ?? null), $mainImage);
                                            $galleryAlt = $galleryImage['alt'] ?? $carName;
                                        ?>
                                        <li>
                                            <div class="gallery-widget">
                                                <a href="<?php echo e($galleryBig); ?>" data-fancybox="gallery1">
                                                    <img class="img-fluid" alt="<?php echo e($galleryAlt); ?>" src="<?php echo e($galleryThumb); ?>">
                                                </a>
                                            </div>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                        <!-- /Gallery -->

                    </div>
                    <div class="col-lg-4 theiaStickySidebar">
                        <aside class="car-details-sidebar-card">
                            <div class="car-details-sidebar-head">
                                <h3><?php echo e($sidebarTitle); ?></h3>
                                <p><?php echo e($sidebarDescription); ?></p>
                            </div>

                            <ul class="car-details-sidebar-stats">
                                <?php $__currentLoopData = $carSidebarStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidebarStat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <i class="<?php echo e($sidebarStat['icon']); ?>"></i>
                                        <span><?php echo e($sidebarStat['value']); ?></span>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>

                            <div class="car-details-sidebar-overview">
                                <h4><?php echo e(__('website.car_details.sidebar.car_overview')); ?></h4>
                                <div class="car-details-overview-grid">
                                    <?php $__currentLoopData = $carOverviewItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $overviewItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="car-details-overview-item">
                                            <span class="overview-label"><?php echo e($overviewItem['label']); ?></span>
                                            <?php if(($overviewItem['type'] ?? '') === 'color'): ?>
                                                <span class="car-color-dot" style="background-color: <?php echo e($colorDotHex); ?>;" title="<?php echo e($overviewItem['value']); ?>"></span>
                                                <small><?php echo e($overviewItem['value']); ?></small>
                                            <?php else: ?>
                                                <strong><?php echo e($overviewItem['value']); ?></strong>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <?php if($sidebarPriceRows->isNotEmpty()): ?>
                                <div class="car-details-sidebar-prices">
                                    <?php $__currentLoopData = $sidebarPriceRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidebarPriceRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="car-details-price-row">
                                            <div class="price-main-wrap">
                                                <strong><?php echo e($formatAmount($sidebarPriceRow['current'])); ?></strong>
                                                <?php if(filled($sidebarPriceRow['old'])): ?>
                                                    <del><?php echo e($formatAmount($sidebarPriceRow['old'])); ?></del>
                                                <?php endif; ?>
                                            </div>
                                            <span class="price-meta"><?php echo e(trim($currencySymbol . ' ' . $sidebarPriceRow['unit'])); ?></span>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>

                            <?php if($sidebarHighlights->isNotEmpty()): ?>
                                <ul class="car-details-sidebar-highlights">
                                    <?php $__currentLoopData = $sidebarHighlights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidebarHighlight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span><?php echo e($sidebarHighlight['label']); ?></span>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php endif; ?>

                            <div class="car-details-sidebar-actions">
                                <a href="<?php echo e($whatsAppHref); ?>"
                                   class="btn sidebar-action-btn sidebar-whatsapp-btn <?php if($whatsAppHref === 'javascript:void(0);'): ?> is-disabled <?php endif; ?>"
                                   <?php if($whatsAppHref !== 'javascript:void(0);'): ?>
                                       target="_blank" rel="noopener noreferrer"
                                   <?php endif; ?>>
                                    <i class="fa-brands fa-whatsapp"></i>
                                    <?php echo e(__('website.car_details.owner_details.chat_whatsapp')); ?>

                                </a>
                                <a href="<?php echo e($phoneHref); ?>"
                                   class="btn sidebar-action-btn sidebar-call-btn <?php if($phoneHref === 'javascript:void(0);'): ?> is-disabled <?php endif; ?>">
                                    <i class="fa-solid fa-phone"></i>
                                    <?php echo e(__('website.car_details.sidebar.call_us')); ?>

                                </a>
                            </div>
                        </aside>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="details-car-grid">
                            <div class="details-slider-heading">
                                <h3><?php echo e(__('website.car_details.sections.related_cars')); ?></h3>
                                <p><?php echo e(__('website.car_details.related_cars_subtitle')); ?></p>
                            </div>
                            <div class="owl-carousel rental-deal-slider details-car owl-theme">

                                <?php $__currentLoopData = $relatedItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
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
                                    ?>
                                        <!-- owl carousel item -->
                                        <div class="rental-car-item">
                                            <div class="listing-item pb-0">
                                                <div class="listing-img">
                                                    <a href="<?php echo e($relatedUrl); ?>">
                                                        <img src="<?php echo e($relatedImage); ?>" class="img-fluid" alt="<?php echo e($relatedName); ?>">
                                                    </a>
                                                    <span class="featured-text"><?php echo e($relatedBrand); ?></span>
                                                </div>
                                                <div class="listing-content">
                                                    <div class="listing-features d-flex align-items-end justify-content-between">
                                                        <div class="list-rating">
                                                            <h3 class="listing-title">
                                                                <a href="<?php echo e($relatedUrl); ?>"><?php echo e($relatedName); ?></a>
                                                            </h3>
                                                            <div class="list-rating">
                                                                <i class="fas fa-star filled"></i>
                                                                <i class="fas fa-star filled"></i>
                                                                <i class="fas fa-star filled"></i>
                                                                <i class="fas fa-star filled"></i>
                                                                <i class="fas fa-star"></i>
                                                                <span>(<?php echo e(number_format($reviewScore, 1)); ?>) <?php echo e($reviewCount); ?> Reviews</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="listing-details-group">
                                                        <ul>
                                                            <li>
                                                                <span><img src="<?php echo e($assetUrl('img/icons/car-parts-01.svg')); ?>" alt="Auto"></span>
                                                                <p><?php echo e($relatedGear); ?></p>
                                                            </li>
                                                            <li>
                                                                <span><img src="<?php echo e($assetUrl('img/icons/car-parts-02.svg')); ?>" alt="Mileage"></span>
                                                                <p><?php echo e($relatedMileage); ?></p>
                                                            </li>
                                                            <li>
                                                                <span><img src="<?php echo e($assetUrl('img/icons/car-parts-03.svg')); ?>" alt="Category"></span>
                                                                <p><?php echo e($relatedItem['category_name'] ?? __('website.common.category')); ?></p>
                                                            </li>
                                                        </ul>
                                                        <ul>
                                                            <li>
                                                                <span><img src="<?php echo e($assetUrl('img/icons/car-parts-04.svg')); ?>" alt="Brand"></span>
                                                                <p><?php echo e($relatedBrand); ?></p>
                                                            </li>
                                                            <li>
                                                                <span><img src="<?php echo e($assetUrl('img/icons/car-parts-05.svg')); ?>" alt="Year"></span>
                                                                <p><?php echo e($relatedYear); ?></p>
                                                            </li>
                                                            <li>
                                                                <span><img src="<?php echo e($assetUrl('img/icons/car-parts-06.svg')); ?>" alt="Persons"></span>
                                                                <p><?php echo e($relatedPassenger); ?></p>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="listing-location-details">
                                                        <div class="listing-price">
                                                            <span><i class="feather-map-pin"></i></span><?php echo e(filled($fullAddress) ? $fullAddress : __('website.car_details.not_available')); ?>

                                                        </div>
                                                        <div class="listing-price">
                                                            <h6><?php echo e($formatPrice($relatedPrice, $relatedCurrency)); ?> <span><?php echo e(__('website.units.per_day')); ?></span></h6>
                                                        </div>
                                                    </div>
                                                    <div class="listing-button">
                                                        <a href="<?php echo e($relatedUrl); ?>" class="btn btn-order"><span><i class="feather-calendar me-2"></i></span><?php echo e(__('website.common.rent_now')); ?></a>
                                                    </div>
                                                </div>
                                                <?php if(($relatedItem['is_featured'] ?? false) === true): ?>
                                                    <div class="feature-text">
                                                        <span class="bg-danger"><?php echo e(__('website.common.featured')); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <!-- /owl carousel item -->
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

						</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.website', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views/website/car-details.blade.php ENDPATH**/ ?>