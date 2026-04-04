<?php

namespace Tests\Unit;

use App\Support\HomePageSchema;
use Tests\TestCase;

class HomePageSchemaTest extends TestCase
{
    public function test_it_builds_homepage_graph_with_faqs(): void
    {
        $schema = HomePageSchema::build([
            'page_url' => 'https://afandinacarrental.com/en',
            'site_name' => 'Afandina Car Rental',
            'site_description' => 'Compare rental cars in Dubai.',
            'logo_url' => 'https://afandinacarrental.com/storage/logo.svg',
            'primary_image_url' => 'https://afandinacarrental.com/storage/hero.webp',
            'email' => 'info@afandinacarrental.com',
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
                'postal_code' => '',
                'country' => 'United Arab Emirates',
            ],
            'in_language' => 'en-AE',
            'headline' => 'Rent a Car Dubai',
            'date_published' => '2025-01-01T00:00:00+00:00',
            'date_modified' => '2025-02-01T00:00:00+00:00',
            'price_range' => 'AED 150 - AED 5000',
            'faq_items' => [
                [
                    'question' => 'How do I rent a car?',
                    'answer' => 'Choose a car and contact us directly.',
                ],
            ],
        ]);

        $this->assertSame('https://schema.org', $schema['@context']);
        $this->assertCount(7, $schema['@graph']);
        $this->assertSame('Organization', $schema['@graph'][0]['@type']);
        $this->assertSame(['AutoRental', 'LocalBusiness'], $schema['@graph'][1]['@type']);
        $this->assertSame('25.184291', $schema['@graph'][1]['geo']['latitude']);
        $this->assertSame('FAQPage', $schema['@graph'][6]['@type']);
        $this->assertSame('How do I rent a car?', $schema['@graph'][6]['mainEntity'][0]['name']);
    }
}
