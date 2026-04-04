<?php

namespace Tests\Unit;

use App\Support\ListingPageSchema;
use Tests\TestCase;

class ListingPageSchemaTest extends TestCase
{
    public function test_it_builds_listing_page_schema_blocks(): void
    {
        $schema = ListingPageSchema::build([
            'site_url' => 'https://afandinacarrental.com/en',
            'page_url' => 'https://afandinacarrental.com/en/brand/mclaren',
            'site_name' => 'Afandina Car Rental',
            'page_name' => 'Rent McLaren Dubai',
            'description' => 'Rent McLaren in Dubai with daily and monthly pricing.',
            'logo_url' => 'https://afandinacarrental.com/storage/logo.svg',
            'primary_image_url' => 'https://afandinacarrental.com/storage/mclaren.webp',
            'telephone' => '+971505512025',
            'has_map' => 'https://www.google.com/maps/search/?api=1&query=25.184291,55.264059',
            'same_as' => [
                'facebook.com/afandinacars',
                'https://instagram.com/afandinacars',
            ],
            'address' => [
                'address_line1' => 'Business Bay',
                'city' => 'Dubai',
                'state' => 'Dubai',
                'country' => 'United Arab Emirates',
                'postal_code' => '00000',
            ],
            'date_published' => '2025-01-01T00:00:00+00:00',
            'date_modified' => '2025-02-01T00:00:00+00:00',
            'search_url_template' => 'https://afandinacarrental.com/en/brand/mclaren?search={search_term_string}',
            'breadcrumb_items' => [
                ['url' => 'https://afandinacarrental.com/en', 'name' => 'Home'],
                ['url' => 'https://afandinacarrental.com/en#home-brands', 'name' => 'Brands'],
                ['url' => 'https://afandinacarrental.com/en/brand/mclaren', 'name' => 'McLaren'],
            ],
            'faq_items' => [
                ['question' => 'How do I rent a McLaren?', 'answer' => 'Choose the car and contact us.'],
            ],
            'products' => [
                [
                    'id' => 77,
                    'name' => 'McLaren 720S Spider 2025',
                    'image' => 'https://afandinacarrental.com/storage/cars/720s.webp',
                    'brand_name' => 'McLaren',
                    'category_name' => 'Supercar',
                    'passenger_capacity' => 2,
                    'year' => 2025,
                    'daily_price' => 3499,
                    'monthly_price' => 80500,
                    'currency_code' => 'AED',
                    'status' => 'available',
                    'url' => 'https://afandinacarrental.com/en/product/mclaren-720s-spider-2025',
                ],
            ],
        ]);

        $this->assertSame('LocalBusiness', $schema['local_business']['@type']);
        $this->assertSame('25.184291', $schema['local_business']['geo']['latitude']);
        $this->assertSame('WebPage', $schema['page_graph']['@graph'][0]['@type']);
        $this->assertSame('BreadcrumbList', $schema['breadcrumb']['@type']);
        $this->assertSame('FAQPage', $schema['faq']['@type']);
        $this->assertCount(1, $schema['products']);
        $this->assertSame('Product', $schema['products'][0]['@type']);
        $this->assertSame('3499', $schema['products'][0]['offers'][0]['price']);
    }
}
