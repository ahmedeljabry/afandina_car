<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Home extends Model
{
    use HasFactory;

    public const DEFAULT_CLIENT_SLIDER_ITEMS = [
        ['path' => 'website/assets/img/clients/client-01.svg', 'alt' => 'Client logo 1', 'url' => null, 'is_active' => true],
        ['path' => 'website/assets/img/clients/client-02.svg', 'alt' => 'Client logo 2', 'url' => null, 'is_active' => true],
        ['path' => 'website/assets/img/clients/client-03.svg', 'alt' => 'Client logo 3', 'url' => null, 'is_active' => true],
        ['path' => 'website/assets/img/clients/client-04.svg', 'alt' => 'Client logo 4', 'url' => null, 'is_active' => true],
        ['path' => 'website/assets/img/clients/client-05.svg', 'alt' => 'Client logo 5', 'url' => null, 'is_active' => true],
        ['path' => 'website/assets/img/clients/client-06.svg', 'alt' => 'Client logo 6', 'url' => null, 'is_active' => true],
    ];

    protected $guarded = [];

    protected $casts = [
        'client_slider_items' => 'array',
        'is_active' => 'boolean',
    ];

    public function clientSliderItemsForEditor(int $minimumSlots = 6): array
    {
        $items = is_array($this->client_slider_items) && !empty($this->client_slider_items)
            ? $this->client_slider_items
            : self::DEFAULT_CLIENT_SLIDER_ITEMS;

        $items = collect($items)
            ->values()
            ->map(fn ($item, int $index): array => self::normalizeClientSliderItem((array) $item, $index))
            ->all();

        while (count($items) < $minimumSlots) {
            $items[] = self::normalizeClientSliderItem([], count($items));
        }

        return $items;
    }

    public function activeClientSliderItems(): array
    {
        return collect($this->clientSliderItemsForEditor(0))
            ->filter(fn (array $item): bool => (bool) $item['is_active'] && filled($item['path']))
            ->values()
            ->all();
    }

    private static function normalizeClientSliderItem(array $item, int $index): array
    {
        return [
            'path' => filled($item['path'] ?? null) ? (string) $item['path'] : null,
            'alt' => filled($item['alt'] ?? null) ? (string) $item['alt'] : 'Client logo ' . ($index + 1),
            'url' => filled($item['url'] ?? null) ? (string) $item['url'] : null,
            'is_active' => array_key_exists('is_active', $item) ? filter_var($item['is_active'], FILTER_VALIDATE_BOOLEAN) : true,
        ];
    }

    //relations
    public function translations(): HasMany
    {
        return $this->hasMany(HomeTranslation::class);
    }

    public function seoQuestions(): MorphMany
    {
        return $this->morphMany(SeoQuestion::class, 'seo_questionable');
    }


}
