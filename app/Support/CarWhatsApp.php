<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CarWhatsApp
{
    public static function url(?string $recipient, array $car): ?string
    {
        $baseUrl = self::baseUrl($recipient);
        if ($baseUrl === null) {
            return null;
        }

        $separator = str_contains($baseUrl, '?') ? '&' : '?';

        return $baseUrl . $separator . 'text=' . rawurlencode(self::message($car));
    }

    public static function message(array $car): string
    {
        $lines = [
            __('website.car_details.whatsapp_inquiry.intro'),
            '',
            '*' . self::title($car) . '*',
            __('website.car_details.whatsapp_inquiry.price_label') . ': ' . implode(', ', [
                self::formatPriceSegment(self::price($car, 'daily'), __('website.car_details.whatsapp_inquiry.day'), $car),
                self::formatPriceSegment(self::price($car, 'weekly'), __('website.car_details.whatsapp_inquiry.week'), $car),
                self::formatPriceSegment(self::price($car, 'monthly'), __('website.car_details.whatsapp_inquiry.month'), $car),
            ]),
        ];

        $detailsUrl = trim((string) (Arr::get($car, 'details_url') ?? ''));
        if ($detailsUrl !== '') {
            $lines[] = __('website.car_details.whatsapp_inquiry.listing_link_label') . ': ' . $detailsUrl;
        }

        $lines[] = '';
        $lines[] = __('website.car_details.whatsapp_inquiry.outro');

        return implode("\n", $lines);
    }

    private static function title(array $car): string
    {
        $name = trim((string) (Arr::get($car, 'name') ?? ''));

        if ($name === '') {
            $name = collect([
                Arr::get($car, 'brand_name'),
                Arr::get($car, 'model_name'),
                Arr::get($car, 'category_name'),
            ])
                ->map(fn ($value) => trim((string) $value))
                ->filter()
                ->unique()
                ->implode(' ');
        }

        $year = trim((string) (Arr::get($car, 'year') ?? ''));
        if ($year !== '' && !preg_match('/(?:^|\D)' . preg_quote($year, '/') . '(?:$|\D)/u', $name)) {
            $name = trim($name . ' ' . $year);
        }

        return $name !== '' ? $name : __('website.common.car');
    }

    private static function price(array $car, string $period): mixed
    {
        $nestedDiscount = Arr::get($car, 'prices.' . $period . '_discount');
        if ($nestedDiscount !== null && $nestedDiscount !== '') {
            return $nestedDiscount;
        }

        $nestedMain = Arr::get($car, 'prices.' . $period . '_main');
        if ($nestedMain !== null && $nestedMain !== '') {
            return $nestedMain;
        }

        return Arr::get($car, $period . '_price');
    }

    private static function formatPriceSegment(mixed $price, string $periodLabel, array $car): string
    {
        if ($price === null || $price === '') {
            return __('website.common.call_for_price') . '/' . $periodLabel;
        }

        $currency = trim((string) (Arr::get($car, 'currency_symbol') ?? ''));
        $numericPrice = (float) $price;
        $formattedPrice = floor($numericPrice) === $numericPrice
            ? (string) (int) $numericPrice
            : rtrim(rtrim(number_format($numericPrice, 2, '.', ''), '0'), '.');

        return ($currency !== '' ? $currency . ' ' : '') . $formattedPrice . '/' . $periodLabel;
    }

    private static function baseUrl(?string $recipient): ?string
    {
        $value = trim((string) $recipient);
        if ($value === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $value);
        if ($digits !== '') {
            return 'https://wa.me/' . $digits;
        }

        return Str::startsWith($value, ['http://', 'https://']) ? $value : null;
    }
}
