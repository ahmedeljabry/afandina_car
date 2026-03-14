<?php

use Illuminate\Support\Str;

if (!function_exists('isPlural')) {
    function isPlural($word)
    {
        return Str::plural($word) === $word;
    }
}

if (!function_exists('isSingular')) {
    function isSingular($word)
    {
        return Str::singular($word) === $word;
    }
}



function convertNumbersToArabic($value)
{
    $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

    if (is_numeric($value)) {
        return str_replace($englishNumbers, $arabicNumbers, (string) $value);
    }

    if (is_string($value)) {
        return str_replace($englishNumbers, $arabicNumbers, $value);
    }

    return $value; // Leave non-numeric values as is
}



function formatNumbersToArabic($data)
{
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = formatNumbersToArabic($value);
        }
    } elseif (is_object($data)) {
        foreach ($data as $key => $value) {
            $data->$key = formatNumbersToArabic($value);
        }
    } else {
        $data = convertNumbersToArabic($data);
    }

    return $data;
}

if (!function_exists('website_entity_link')) {
    function website_entity_link(mixed $item, string $routeName, string $parameterName, string $fallbackRoute = 'website.cars.index'): string
    {
        $directUrl = data_get($item, 'url');
        if (filled($directUrl)) {
            return (string) $directUrl;
        }

        $slug = data_get($item, 'slug');
        if (filled($slug)) {
            return route($routeName, [$parameterName => $slug]);
        }

        return route($fallbackRoute);
    }
}
