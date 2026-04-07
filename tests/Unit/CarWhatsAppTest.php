<?php

namespace Tests\Unit;

use App\Support\CarWhatsApp;
use Tests\TestCase;

class CarWhatsAppTest extends TestCase
{
    public function test_it_builds_a_car_specific_whatsapp_url(): void
    {
        app()->setLocale('en');

        $url = CarWhatsApp::url('+971 50 123 4567', [
            'name' => 'Mercedes Benz E450 Convertible',
            'year' => 2020,
            'daily_price' => 490,
            'weekly_price' => 3500,
            'monthly_price' => 9999,
            'currency_symbol' => 'AED',
            'details_url' => 'https://www.oneclickdrive.com/details/index/search-car-rentals-Dubai/Mercedes/Benz/E450/Convertible/?id=39824',
        ]);

        $this->assertNotNull($url);
        $this->assertStringStartsWith('https://wa.me/971501234567?text=', $url);

        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

        $this->assertSame(
            "Hi, I submitted an inquiry via Afandina for this rental:\n\n*Mercedes Benz E450 Convertible 2020*\nPrice: AED 490/day, AED 3500/week, AED 9999/month\nListing Link: https://www.oneclickdrive.com/details/index/search-car-rentals-Dubai/Mercedes/Benz/E450/Convertible/?id=39824\n\nI'd like more details. Is it available on...",
            $query['text'] ?? null
        );
    }

    public function test_it_returns_null_without_a_recipient(): void
    {
        $this->assertNull(CarWhatsApp::url(null, [
            'name' => 'Mercedes Benz E450 Convertible',
            'year' => 2020,
        ]));
    }
}
