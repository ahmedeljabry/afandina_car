<?php

namespace App\Support;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CmsHtmlLinks
{
    private static array $existsCache = [];

    public static function redirectBrokenInternalLinks(?string $html): string
    {
        $html = self::decodeStoredHtml(trim((string) $html));

        if ($html === '' || stripos($html, '<a') === false) {
            return $html;
        }

        return preg_replace_callback(
            '/(<a\b[^>]*\bhref\s*=\s*)(["\'])(.*?)\2/iu',
            static function (array $matches): string {
                $replacementUrl = self::replacementUrlForHref((string) $matches[3]);

                if ($replacementUrl === null) {
                    return $matches[0];
                }

                return $matches[1]
                    . $matches[2]
                    . htmlspecialchars($replacementUrl, ENT_QUOTES | ENT_HTML5, 'UTF-8', false)
                    . $matches[2];
            },
            $html
        ) ?? $html;
    }

    private static function decodeStoredHtml(string $html): string
    {
        if (preg_match('/&lt;\/?[a-z][\s\S]*?&gt;/i', $html)) {
            return html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return $html;
    }

    private static function replacementUrlForHref(string $href): ?string
    {
        $href = trim(html_entity_decode($href, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        if ($href === '' || Str::startsWith($href, ['#', '?'])) {
            return null;
        }

        $lowerHref = Str::lower($href);
        if (Str::startsWith($lowerHref, ['mailto:', 'tel:', 'sms:', 'whatsapp:', 'javascript:'])) {
            return null;
        }

        $parts = parse_url($href);
        if ($parts === false) {
            return self::homeUrl();
        }

        $host = isset($parts['host']) ? Str::lower((string) $parts['host']) : null;
        if ($host !== null && !self::isLocalHost($host)) {
            return null;
        }

        $path = (string) ($parts['path'] ?? '');
        if ($path === '') {
            return null;
        }

        return self::isValidInternalPath($path) ? null : self::homeUrl();
    }

    private static function isLocalHost(string $host): bool
    {
        $host = self::normalizeHost($host);
        $requestHost = self::normalizeHost(request()->getHost());
        $appHost = self::normalizeHost((string) parse_url((string) config('app.url'), PHP_URL_HOST));

        return $host !== '' && in_array($host, array_filter([$requestHost, $appHost]), true);
    }

    private static function normalizeHost(string $host): string
    {
        $host = Str::lower(trim($host));

        return Str::startsWith($host, 'www.') ? substr($host, 4) : $host;
    }

    private static function isValidInternalPath(string $path): bool
    {
        $segments = collect(explode('/', trim(urldecode($path), '/')))
            ->filter(fn ($segment) => $segment !== '')
            ->values();

        if ($segments->isEmpty()) {
            return true;
        }

        $supportedLocales = array_keys((array) config('laravellocalization.supportedLocales', []));
        $currentLocale = app()->getLocale();
        if (filled($currentLocale)) {
            $supportedLocales[] = $currentLocale;
        }

        if (in_array((string) $segments->first(), array_unique($supportedLocales), true)) {
            $segments = $segments->slice(1)->values();
        }

        if ($segments->isEmpty()) {
            return true;
        }

        $firstSegment = Str::lower((string) $segments->get(0));
        $secondSegment = (string) $segments->get(1, '');

        if (in_array($firstSegment, ['about-us', 'contact-us', 'all-cars'], true)) {
            return $segments->count() === 1;
        }

        if ($firstSegment === 'blogs') {
            return $segments->count() === 1
                || ($segments->count() === 2 && self::activeBlogExists($secondSegment));
        }

        if ($segments->count() !== 2) {
            return false;
        }

        return match ($firstSegment) {
            'brand' => self::activeBrandExists($secondSegment),
            'category' => self::activeCategoryExists($secondSegment),
            'product' => self::activeCarExists($secondSegment),
            default => false,
        };
    }

    private static function activeBrandExists(string $identifier): bool
    {
        return self::remembered('brand:' . $identifier, static function () use ($identifier): bool {
            if (!Schema::hasColumn('brands', 'slug') && !ctype_digit($identifier)) {
                return false;
            }

            return Brand::query()
                ->where('is_active', true)
                ->where(function ($query) use ($identifier): void {
                    if (Schema::hasColumn('brands', 'slug')) {
                        $query->where('slug', $identifier);
                    }

                    if (ctype_digit($identifier)) {
                        $query->orWhere('id', (int) $identifier);
                    }
                })
                ->exists();
        });
    }

    private static function activeCategoryExists(string $identifier): bool
    {
        return self::remembered('category:' . $identifier, static function () use ($identifier): bool {
            if (!Schema::hasColumn('categories', 'slug') && !ctype_digit($identifier)) {
                return false;
            }

            return Category::query()
                ->where('is_active', true)
                ->where(function ($query) use ($identifier): void {
                    if (Schema::hasColumn('categories', 'slug')) {
                        $query->where('slug', $identifier);
                    }

                    if (ctype_digit($identifier)) {
                        $query->orWhere('id', (int) $identifier);
                    }
                })
                ->exists();
        });
    }

    private static function activeCarExists(string $slug): bool
    {
        return self::remembered('car:' . $slug, static function () use ($slug): bool {
            return Schema::hasColumn('car_translations', 'slug')
                && Car::query()
                    ->where('is_active', true)
                    ->whereHas('translations', fn ($query) => $query->where('slug', $slug))
                    ->exists();
        });
    }

    private static function activeBlogExists(string $identifier): bool
    {
        return self::remembered('blog:' . $identifier, static function () use ($identifier): bool {
            if (!Schema::hasColumn('blogs', 'slug') && !ctype_digit($identifier)) {
                return false;
            }

            return Blog::query()
                ->where('is_active', true)
                ->where(function ($query) use ($identifier): void {
                    if (Schema::hasColumn('blogs', 'slug')) {
                        $query->where('slug', $identifier);
                    }

                    if (ctype_digit($identifier)) {
                        $query->orWhere('id', (int) $identifier);
                    }
                })
                ->exists();
        });
    }

    private static function remembered(string $key, callable $resolver): bool
    {
        if (!array_key_exists($key, self::$existsCache)) {
            self::$existsCache[$key] = (bool) $resolver();
        }

        return self::$existsCache[$key];
    }

    private static function homeUrl(): string
    {
        return route('home');
    }
}
