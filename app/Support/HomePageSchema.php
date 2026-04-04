<?php

namespace App\Support;

use Illuminate\Support\Str;

class HomePageSchema
{
    public static function build(array $data): array
    {
        $pageUrl = trim((string) ($data['page_url'] ?? ''));
        $siteName = trim((string) ($data['site_name'] ?? ''));
        $siteDescription = trim((string) ($data['site_description'] ?? ''));
        $logoUrl = self::normalizeUrl($data['logo_url'] ?? null);
        $primaryImageUrl = self::normalizeUrl($data['primary_image_url'] ?? null);
        $email = trim((string) ($data['email'] ?? ''));
        $telephone = trim((string) ($data['telephone'] ?? ''));
        $hasMap = self::normalizeUrl($data['has_map'] ?? null);
        $sameAs = collect((array) ($data['same_as'] ?? []))
            ->map(fn ($url) => self::normalizeUrl($url))
            ->filter()
            ->unique()
            ->values()
            ->all();
        $address = self::buildAddress($data['address'] ?? []);
        $coordinates = self::extractCoordinates($hasMap);
        $language = trim((string) ($data['in_language'] ?? 'en-AE'));
        $headline = trim((string) ($data['headline'] ?? $siteName));
        $datePublished = self::formatDate($data['date_published'] ?? null);
        $dateModified = self::formatDate($data['date_modified'] ?? null);
        $priceRange = trim((string) ($data['price_range'] ?? ''));
        $openingHours = trim((string) ($data['opening_hours'] ?? ''));
        $caption = trim((string) ($data['primary_image_caption'] ?? $siteName));

        $organizationId = self::fragmentId($pageUrl, 'organization');
        $logoId = self::fragmentId($pageUrl, 'logo');
        $businessId = self::fragmentId($pageUrl, 'autorental');
        $placeId = self::fragmentId($pageUrl, 'place');
        $websiteId = self::fragmentId($pageUrl, 'website');
        $primaryImageId = self::fragmentId($pageUrl, 'primaryimage');
        $articleId = self::fragmentId($pageUrl, 'article');
        $faqPageId = self::fragmentId($pageUrl, 'faq');

        $graph = [
            self::clean([
                '@type' => 'Organization',
                '@id' => $organizationId,
                'name' => $siteName,
                'url' => $pageUrl,
                'email' => $email,
                'logo' => $logoUrl ? [
                    '@type' => 'ImageObject',
                    '@id' => $logoId,
                    'url' => $logoUrl,
                    'caption' => $siteName,
                ] : null,
                'sameAs' => $sameAs,
                'address' => $address,
            ]),
            self::clean([
                '@type' => ['AutoRental', 'LocalBusiness'],
                '@id' => $businessId,
                'name' => $siteName,
                'url' => $pageUrl,
                'description' => $siteDescription,
                'image' => $primaryImageUrl ?: $logoUrl,
                'logo' => $logoUrl ? ['@id' => $logoId] : null,
                'telephone' => $telephone,
                'email' => $email,
                'openingHours' => $openingHours,
                'priceRange' => $priceRange,
                'address' => $address,
                'geo' => $coordinates ? [
                    '@type' => 'GeoCoordinates',
                    'latitude' => $coordinates['latitude'],
                    'longitude' => $coordinates['longitude'],
                ] : null,
                'hasMap' => $hasMap,
                'areaServed' => self::buildAreaServed($data['address'] ?? []),
                'parentOrganization' => [
                    '@id' => $organizationId,
                ],
            ]),
            self::clean([
                '@type' => 'Place',
                '@id' => $placeId,
                'geo' => $coordinates ? [
                    '@type' => 'GeoCoordinates',
                    'latitude' => $coordinates['latitude'],
                    'longitude' => $coordinates['longitude'],
                ] : null,
                'hasMap' => $hasMap,
                'address' => $address,
            ]),
            self::clean([
                '@type' => 'WebSite',
                '@id' => $websiteId,
                'url' => $pageUrl,
                'name' => $siteName,
                'publisher' => [
                    '@id' => $organizationId,
                ],
                'inLanguage' => $language,
            ]),
            self::clean([
                '@type' => 'ImageObject',
                '@id' => $primaryImageId,
                'url' => $primaryImageUrl ?: $logoUrl,
                'caption' => $caption,
                'inLanguage' => $language,
            ]),
            self::clean([
                '@type' => 'Article',
                '@id' => $articleId,
                'headline' => $headline,
                'datePublished' => $datePublished,
                'dateModified' => $dateModified,
                'author' => [
                    '@id' => $organizationId,
                ],
                'publisher' => [
                    '@id' => $organizationId,
                ],
                'description' => $siteDescription,
                'image' => [
                    '@id' => $primaryImageId,
                ],
                'mainEntityOfPage' => [
                    '@id' => $pageUrl,
                ],
            ]),
        ];

        $faqItems = collect((array) ($data['faq_items'] ?? []))
            ->map(function ($faq): ?array {
                $question = trim((string) data_get($faq, 'question', ''));
                $answer = trim((string) data_get($faq, 'answer', ''));

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

        if ($faqItems !== []) {
            $graph[] = [
                '@type' => 'FAQPage',
                '@id' => $faqPageId,
                'mainEntity' => $faqItems,
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter($graph)),
        ];
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
            'postalCode' => data_get($address, 'postal_code'),
            'addressCountry' => data_get($address, 'country'),
        ]);

        return $value === [] ? null : $value;
    }

    private static function buildAreaServed(array $address): ?array
    {
        $servedAddress = self::clean([
            '@type' => 'PostalAddress',
            'addressLocality' => data_get($address, 'city'),
            'addressCountry' => data_get($address, 'country'),
        ]);

        if ($servedAddress === []) {
            return null;
        }

        return [
            '@type' => 'Place',
            'address' => $servedAddress,
        ];
    }

    private static function fragmentId(string $pageUrl, string $fragment): string
    {
        return rtrim($pageUrl, '#') . '#'.$fragment;
    }

    private static function formatDate(mixed $value): ?string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        $string = trim((string) $value);

        return $string !== '' ? $string : null;
    }

    private static function extractCoordinates(?string $url): ?array
    {
        if (blank($url)) {
            return null;
        }

        $patterns = [
            '/@(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/',
            '/[?&](?:query|q)=(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/',
            '/!3d(-?\d+(?:\.\d+)?)!4d(-?\d+(?:\.\d+)?)/',
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

        return is_string($value) ? trim($value) : $value;
    }
}
