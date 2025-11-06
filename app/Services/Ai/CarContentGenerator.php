<?php

namespace App\Services\Ai;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class CarContentGenerator
{
    public function generate(array $payload): array
    {
        $apiKey = config('services.openai.api_key');

        if (blank($apiKey)) {
            throw new RuntimeException('OpenAI API key is not configured.');
        }

        $model = config('services.openai.model', 'gpt-4o-mini');
        $language = $payload['language'] ?? 'en';
        $name = $payload['name'] ?? '';
        $context = Arr::wrap($payload['context'] ?? []);

        $contextSummary = $this->buildContextSummary($context);

        $systemPrompt = "You are an expert automotive copywriter creating concise, SEO-focused marketing copy. "
            . "Always respond in the requested language ({$language}) and output clean JSON only.";

        $userInstructions = [
            "Generate marketing copy for a car rental listing using the following details:",
            "Car name: {$name}",
        ];

        if ($contextSummary) {
            $userInstructions[] = "Additional context:\n{$contextSummary}";
        }

        $userInstructions = array_merge($userInstructions, [
            "Return a JSON object with the following keys:",
            'name: localized vehicle name suitable for listings.',
            'description: short persuasive paragraph (~70 words).',
            'long_description: detailed multi-paragraph copy (~220 words) with line breaks.',
            'door_count: integer number of doors (e.g., 2).',
            'luggage_capacity: integer luggage capacity (e.g., 2).',
            'passenger_capacity: integer passenger capacity (e.g., 4).',
            'daily_main_price: numeric daily price without currency symbol.',
            'daily_discount_price: numeric discounted daily price or null if not applicable.',
            'daily_mileage_included: numeric mileage included per day.',
            'weekly_main_price: numeric weekly price without currency symbol.',
            'weekly_discount_price: numeric discounted weekly price or null if not applicable.',
            'weekly_mileage_included: numeric mileage included per week.',
            'monthly_main_price: numeric monthly price without currency symbol.',
            'monthly_discount_price: numeric discounted monthly price or null if not applicable.',
            'monthly_mileage_included: numeric mileage included per month.',
            'meta_title: under 60 characters, include branding if possible.',
            'meta_description: under 160 characters, compelling CTA.',
            'meta_keywords: array of 6 unique keywords (each under 35 characters).',
            'seo_questions: array of 3 objects with question and answer fields. Answers <= 60 words.',
        ]);

        $userMessage = implode("\n", $userInstructions)
            . "\n\nEnsure meta_keywords is an array of strings and seo_questions follows the example: "
            . '[{"question": "...", "answer": "..."}]. Do not include markdown fences.';

        $response = Http::timeout(40)
            ->withHeader('Authorization', "Bearer {$apiKey}")
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'temperature' => 0.7,
                'max_tokens' => 900,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
            ]);

        if (!$response->successful()) {
            throw new RuntimeException(
                'OpenAI API request failed with status ' . $response->status()
            );
        }

        $content = data_get($response->json(), 'choices.0.message.content');
        $decoded = $this->decodeResponse($content);

        return [
            'name' => Str::of(data_get($decoded, 'name', ''))->trim()->toString(),
            'description' => Str::of(data_get($decoded, 'description', ''))->trim()->toString(),
            'long_description' => Str::of(data_get($decoded, 'long_description', ''))->trim()->toString(),
            'meta_title' => Str::limit(data_get($decoded, 'meta_title', ''), 120),
            'meta_description' => Str::of(data_get($decoded, 'meta_description', ''))->trim()->toString(),
            'meta_keywords' => $this->normalizeStringArray(data_get($decoded, 'meta_keywords', [])),
            'seo_questions' => $this->normalizeQaArray(data_get($decoded, 'seo_questions', [])),
            'door_count' => data_get($decoded, 'door_count'),
            'luggage_capacity' => data_get($decoded, 'luggage_capacity'),
            'passenger_capacity' => data_get($decoded, 'passenger_capacity'),
            'daily_main_price' => data_get($decoded, 'daily_main_price'),
            'daily_discount_price' => data_get($decoded, 'daily_discount_price'),
            'daily_mileage_included' => data_get($decoded, 'daily_mileage_included'),
            'weekly_main_price' => data_get($decoded, 'weekly_main_price'),
            'weekly_discount_price' => data_get($decoded, 'weekly_discount_price'),
            'weekly_mileage_included' => data_get($decoded, 'weekly_mileage_included'),
            'monthly_main_price' => data_get($decoded, 'monthly_main_price'),
            'monthly_discount_price' => data_get($decoded, 'monthly_discount_price'),
            'monthly_mileage_included' => data_get($decoded, 'monthly_mileage_included'),
        ];
    }

    protected function decodeResponse(?string $content): array
    {
        if (blank($content)) {
            return [];
        }

        $decoded = json_decode($content, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        if (preg_match('/\{.*\}/s', $content, $matches)) {
            $fallback = json_decode($matches[0], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($fallback)) {
                return $fallback;
            }
        }

        return [];
    }

    protected function buildContextSummary(array $context): string
    {
        $parts = [];

        foreach (['original_name', 'brand', 'model', 'year', 'color', 'gear_type', 'primary_category'] as $key) {
            if (!empty($context[$key])) {
                $label = Str::of($key)->replace('_', ' ')->title();
                $parts[] = "{$label}: {$context[$key]}";
            }
        }

        if (!empty($context['categories']) && is_array($context['categories'])) {
            $parts[] = 'Assigned categories: ' . implode(', ', $context['categories']);
        }

        if (!empty($context['features']) && is_array($context['features'])) {
            $parts[] = 'Key features: ' . implode(', ', $context['features']);
        }

        foreach (['daily_price', 'weekly_price', 'monthly_price'] as $priceKey) {
            if (!empty($context[$priceKey])) {
                $label = Str::of($priceKey)->replace('_', ' ')->title();
                $parts[] = "{$label}: {$context[$priceKey]}";
            }
        }

        $detailedPricing = [
            'daily_main_price' => 'Daily main price',
            'daily_discount_price' => 'Daily discount price',
            'weekly_main_price' => 'Weekly main price',
            'weekly_discount_price' => 'Weekly discount price',
            'monthly_main_price' => 'Monthly main price',
            'monthly_discount_price' => 'Monthly discount price',
        ];

        foreach ($detailedPricing as $key => $label) {
            if (!empty($context[$key])) {
                $parts[] = "{$label}: {$context[$key]}";
            }
        }

        $mileageKeys = [
            'daily_mileage_included' => 'Daily mileage included',
            'weekly_mileage_included' => 'Weekly mileage included',
            'monthly_mileage_included' => 'Monthly mileage included',
        ];

        foreach ($mileageKeys as $key => $label) {
            if (!empty($context[$key])) {
                $parts[] = "{$label}: {$context[$key]}";
            }
        }

        foreach (['passenger_capacity', 'door_count', 'luggage_capacity'] as $specKey) {
            if (!empty($context[$specKey])) {
                $label = Str::of($specKey)->replace('_', ' ')->title();
                $parts[] = "{$label}: {$context[$specKey]}";
            }
        }

        $boolFlags = [
            'insurance_included' => 'Insurance included',
            'free_delivery' => 'Free delivery',
            'is_flash_sale' => 'Flash sale offer',
            'is_featured' => 'Featured listing',
            'only_on_afandina' => 'Exclusive listing',
            'crypto_payment_accepted' => 'Cryptocurrency payments accepted',
        ];

        foreach ($boolFlags as $key => $label) {
            if (!empty($context[$key])) {
                $parts[] = $label;
            }
        }

        return implode("\n", $parts);
    }

    protected function normalizeStringArray($values): array
    {
        if (is_string($values)) {
            $values = explode(',', $values);
        }

        if (!is_array($values)) {
            return [];
        }

        return collect($values)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function normalizeQaArray($questions): array
    {
        if (!is_array($questions)) {
            return [];
        }

        return collect($questions)
            ->map(function ($item) {
                $question = trim((string) data_get($item, 'question', ''));
                $answer = trim((string) data_get($item, 'answer', ''));

                if ($question === '' || $answer === '') {
                    return null;
                }

                return [
                    'question' => $question,
                    'answer' => $answer,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
