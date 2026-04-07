<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ListingPageSchema
{
    public static function build(array $data): array
    {
        $siteUrl = trim((string) ($data['site_url'] ?? ''));
        $pageUrl = trim((string) ($data['page_url'] ?? $siteUrl));
        $siteName = trim((string) ($data['site_name'] ?? ''));
        $pageName = trim((string) ($data['page_name'] ?? $siteName));
        $description = trim((string) ($data['description'] ?? ''));
        $language = trim((string) ($data['in_language'] ?? 'en-AE'));
        $logoUrl = self::normalizeUrl($data['logo_url'] ?? null);
        $primaryImageUrl = self::normalizeUrl($data['primary_image_url'] ?? null);
        $telephone = trim((string) ($data['telephone'] ?? ''));
        $hasMap = self::normalizeUrl($data['has_map'] ?? null);
        $priceRange = trim((string) ($data['price_range'] ?? '$$$'));
        $sameAs = collect((array) ($data['same_as'] ?? []))
            ->map(fn ($url) => self::normalizeUrl($url))
            ->filter()
            ->unique()
            ->values()
            ->all();
        $address = self::buildAddress($data['address'] ?? []);
        $coordinates = self::extractCoordinates($hasMap);
        $datePublished = self::formatDate($data['date_published'] ?? null);
        $dateModified = self::formatDate($data['date_modified'] ?? null);
        $searchUrlTemplate = trim((string) ($data['search_url_template'] ?? ''));

        $websiteId = rtrim($siteUrl, '#') . '#website';
        $organizationId = rtrim($siteUrl, '#') . '#organization';
        $primaryImageId = rtrim($pageUrl, '#') . '#primaryimage';

        return [
            'local_business' => self::buildLocalBusiness(
                siteName: $siteName,
                siteUrl: $siteUrl,
                description: $description,
                sameAs: $sameAs,
                logoUrl: $logoUrl,
                primaryImageUrl: $primaryImageUrl,
                priceRange: $priceRange,
                coordinates: $coordinates,
                hasMap: $hasMap,
                telephone: $telephone,
                address: $address,
            ),
            'page_graph' => [
                '@context' => 'https://schema.org',
                '@graph' => array_values(array_filter([
                    self::clean([
                        '@type' => 'WebPage',
                        '@id' => $pageUrl,
                        'url' => $pageUrl,
                        'name' => $pageName,
                        'isPartOf' => [
                            '@id' => $websiteId,
                        ],
                        'primaryImageOfPage' => $primaryImageUrl ? [
                            '@id' => $primaryImageId,
                        ] : null,
                        'image' => $primaryImageUrl ? [
                            '@id' => $primaryImageId,
                        ] : null,
                        'thumbnailUrl' => $primaryImageUrl,
                        'datePublished' => $datePublished,
                        'dateModified' => $dateModified,
                        'description' => $description,
                        'inLanguage' => $language,
                        'potentialAction' => [[
                            '@type' => 'ReadAction',
                            'target' => [$pageUrl],
                        ]],
                    ]),
                    self::clean([
                        '@type' => 'WebSite',
                        '@id' => $websiteId,
                        'url' => $siteUrl,
                        'name' => $siteName,
                        'description' => $description,
                        'publisher' => [
                            '@id' => $organizationId,
                        ],
                        'potentialAction' => $searchUrlTemplate !== '' ? [[
                            '@type' => 'SearchAction',
                            'target' => [
                                '@type' => 'EntryPoint',
                                'urlTemplate' => $searchUrlTemplate,
                            ],
                            'query-input' => 'required name=search_term_string',
                        ]] : null,
                        'inLanguage' => $language,
                    ]),
                    self::clean([
                        '@type' => ['AutoRental', 'Organization'],
                        '@id' => $organizationId,
                        'name' => $pageName,
                        'url' => $pageUrl,
                        'logo' => $logoUrl,
                        'address' => $address,
                        'sameAs' => $sameAs,
                        'telephone' => $telephone,
                    ]),
                ])),
            ],
            'breadcrumb' => self::buildBreadcrumb($data['breadcrumb_items'] ?? []),
            'faq' => self::buildFaq($data['faq_items'] ?? []),
            'products' => self::buildProducts($data['products'] ?? []),
        ];
    }

    private static function buildLocalBusiness(
        string $siteName,
        string $siteUrl,
        string $description,
        array $sameAs,
        ?string $logoUrl,
        ?string $primaryImageUrl,
        string $priceRange,
        ?array $coordinates,
        ?string $hasMap,
        string $telephone,
        ?array $address
    ): array {
        return self::clean([
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $siteName,
            'alternateName' => array_values(array_filter([
                $siteName,
                $siteName !== '' ? $siteName . ' - Car Rental' : null,
            ])),
            'url' => $siteUrl,
            'sameAs' => $sameAs,
            'logo' => $logoUrl,
            'description' => $description,
            'priceRange' => $priceRange,
            'openingHoursSpecification' => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                    'Sunday',
                ],
                'opens' => '00:00',
                'closes' => '23:59',
            ],
            'geo' => $coordinates ? [
                '@type' => 'GeoCoordinates',
                'latitude' => $coordinates['latitude'],
                'longitude' => $coordinates['longitude'],
            ] : null,
            'hasMap' => $hasMap,
            'telephone' => $telephone,
            'address' => $address,
            'image' => $primaryImageUrl ?: $logoUrl,
        ]);
    }

    private static function buildBreadcrumb(array $items): ?array
    {
        $listItems = collect($items)
            ->map(function ($item, int $index): ?array {
                $name = trim((string) data_get($item, 'name', ''));
                $id = trim((string) data_get($item, 'url', ''));

                if ($name === '' || $id === '') {
                    return null;
                }

                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => [
                        '@id' => $id,
                        'name' => $name,
                    ],
                ];
            })
            ->filter()
            ->values()
            ->all();

        if ($listItems === []) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];
    }

    private static function buildFaq(array $items): ?array
    {
        $questions = collect($items)
            ->map(function ($item): ?array {
                $question = trim((string) data_get($item, 'question', ''));
                $answer = trim((string) data_get($item, 'answer', ''));

                if ($question === '' || $answer === '') {
                    return null;
                }

                return [
                    '@type' => 'Question',
                    'name' => $question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $answer,
                    ],
                ];
            })
            ->filter()
            ->values()
            ->all();

        if ($questions === []) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $questions,
        ];
    }

    private static function buildProducts(array $products): array
    {
        return collect($products)
            ->map(function ($product): ?array {
                $name = trim((string) data_get($product, 'name', ''));
                $imageUrl = self::normalizeUrl(data_get($product, 'image'));

                if ($name === '') {
                    return null;
                }

                $currencyCode = trim((string) data_get($product, 'currency_code', 'AED'));
                $availability = data_get($product, 'status') === 'available'
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock';
                $productUrl = trim((string) data_get($product, 'url', ''));

                $offers = collect([
                    data_get($product, 'daily_price'),
                    data_get($product, 'weekly_price'),
                    data_get($product, 'monthly_price'),
                ])->filter(fn ($price) => $price !== null && $price !== '')
                    ->map(function ($price) use ($currencyCode, $availability, $productUrl) {
                        return [
                            '@type' => 'Offer',
                            'price' => (string) $price,
                            'priceCurrency' => $currencyCode,
                            'priceValidUntil' => now()->addYear()->toDateString(),
                            'availability' => $availability,
                            'url' => $productUrl,
                        ];
                    })
                    ->values()
                    ->all();

                return self::clean([
                    '@context' => 'https://schema.org',
                    '@type' => 'Product',
                    'name' => $name,
                    'url' => $productUrl,
                    'image' => $imageUrl,
                    'description' => self::buildProductDescription($product),
                    'sku' => (string) data_get($product, 'id', ''),
                    'mpn' => (string) data_get($product, 'id', ''),
                    'brand' => filled(data_get($product, 'brand_name')) ? [
                        '@type' => 'Brand',
                        'name' => trim((string) data_get($product, 'brand_name')),
                    ] : null,
                    'category' => trim((string) data_get($product, 'category_name', '')),
                    'offers' => match (count($offers)) {
                        0 => null,
                        1 => $offers[0],
                        default => $offers,
                    },
                ]);
            })
            ->filter()
            ->values()
            ->all();
    }

    private static function buildProductDescription(array $product): string
    {
        $providedDescription = trim((string) data_get($product, 'description', ''));

        if ($providedDescription !== '') {
            return $providedDescription;
        }

        $name = trim((string) data_get($product, 'name', ''));
        $category = trim((string) data_get($product, 'category_name', ''));
        $passengers = data_get($product, 'passenger_capacity');
        $year = trim((string) data_get($product, 'year', ''));

        $description = $name !== '' ? 'Rent and Drive the ' . $name . '.' : 'Available rental car.';

        if ($category !== '') {
            $description .= ' This ' . Str::lower($category);

            if ($year !== '') {
                $description .= ' from ' . $year;
            }

            if ($passengers !== null && $passengers !== '') {
                $description .= ' fits ' . $passengers . ' passengers';
            }

            $description .= '.';
        }

        return trim($description);
    }

    private static function buildAddress(array $address): ?array
    {
        $value = self::clean([
            '@type' => 'PostalAddress',
            'streetAddress' => collect([
                data_get($address, 'address_line1'),
                data_get($address, 'address_line2'),
            ])->filter(fn ($item) => filled($item))->implode(', '),
            'addressLocality' => data_get($address, 'city'),
            'addressRegion' => data_get($address, 'state'),
            'addressCountry' => data_get($address, 'country'),
            'postalCode' => data_get($address, 'postal_code'),
        ]);

        return $value === [] ? null : $value;
    }

    private static function extractCoordinates(?string $url): ?array
    {
        if (blank($url)) {
            return null;
        }

        $patterns = [
            '/@(-?\\d+(?:\\.\\d+)?),(-?\\d+(?:\\.\\d+)?)/',
            '/[?&](?:query|q)=(-?\\d+(?:\\.\\d+)?),(-?\\d+(?:\\.\\d+)?)/',
            '/!3d(-?\\d+(?:\\.\\d+)?)!4d(-?\\d+(?:\\.\\d+)?)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches) === 1) {
                return [
                    'latitude' => $matches[1],
                    'longitude' => $matches[2],
                ];
            }
        }

        return null;
    }

    private static function formatDate(mixed $value): ?string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        $string = trim((string) $value);

        return $string !== '' ? $string : null;
    }

    private static function normalizeUrl(?string $url): ?string
    {
        $value = trim((string) $url);

        if ($value === '') {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        return 'https://' . ltrim($value, '/');
    }

    private static function clean(mixed $value): mixed
    {
        if (is_array($value)) {
            $isList = array_is_list($value);
            $cleaned = [];

            foreach ($value as $key => $item) {
                $item = self::clean($item);

                if ($item === null || $item === '' || $item === []) {
                    continue;
                }

                if ($isList) {
                    $cleaned[] = $item;
                } else {
                    $cleaned[$key] = $item;
                }
            }

            return $cleaned;
        }

        if ($value instanceof Collection) {
            return self::clean($value->all());
        }

        return is_string($value) ? trim($value) : $value;
    }
}
