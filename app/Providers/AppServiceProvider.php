<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Home;
use App\Models\Location;
use App\Models\Template;
use App\Traits\DeduplicatesCars;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    use DeduplicatesCars;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        View::composer('includes.website.header', function ($view): void {
            $locale = app()->getLocale() ?? 'en';
            $branding = $this->getBrandingSettings();
            $brandCounts = $this->uniqueCarCountsByColumn('brand_id', Car::query());

            $headerBrands = Brand::query()
                ->with('translations')
                ->where('is_active', true)
                ->get()
                ->map(function (Brand $brand) use ($locale, $brandCounts): ?array {
                    $brandTranslation = $brand->translations->firstWhere('locale', $locale)
                        ?? $brand->translations->firstWhere('locale', 'en')
                        ?? $brand->translations->first();
                    $name = trim((string) ($brandTranslation?->name ?? ''));
                    $slug = $brand->slug;

                    if ($name === '' || blank($slug)) {
                        return null;
                    }

                    return [
                        'id' => $brand->id,
                        'name' => $name,
                        'logo_path' => filled($brand->logo_path) ? $this->normalizeAssetPath($brand->logo_path, '') : null,
                        'cars_count' => (int) $brandCounts->get($brand->id, 0),
                        'slug' => $slug,
                        'url' => route('website.cars.brand', ['brand' => $slug]),
                    ];
                })
                ->filter()
                ->sort(function (array $left, array $right): int {
                    return ($right['cars_count'] <=> $left['cars_count'])
                        ?: ($left['id'] <=> $right['id']);
                })
                ->take(24)
                ->values();

            $headerCategories = Category::query()
                ->with('translations')
                ->withCount([
                    'cars as cars_count' => fn ($query) => $query->where('is_active', true),
                ])
                ->where('is_active', true)
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->orderByDesc('cars_count')
                ->latest('id')
                ->take(24)
                ->get()
                ->map(function (Category $category) use ($locale): ?array {
                    $translation = $category->translations->firstWhere('locale', $locale)
                        ?? $category->translations->firstWhere('locale', 'en')
                        ?? $category->translations->first();
                    $name = trim((string) ($translation?->name ?? ''));

                    if ($name === '') {
                        return null;
                    }

                    return [
                        'name' => $name,
                        'slug' => $category->slug,
                        'image_path' => $category->image_path,
                        'cars_count' => (int) ($category->cars_count ?? 0),
                    ];
                })
                ->filter()
                ->values();

            $view->with([
                'headerBrands' => $headerBrands,
                'headerCategories' => $headerCategories,
                'headerLogo' => $branding['logo'],
                'headerSiteName' => $branding['site_name'],
            ]);
        });

        View::composer('includes.website.footer', function ($view): void {
            $locale = app()->getLocale() ?? 'en';
            $branding = $this->getBrandingSettings();

            $translationFor = function ($model) use ($locale): mixed {
                if (!$model || !isset($model->translations) || !($model->translations instanceof Collection)) {
                    return null;
                }

                return $model->translations->firstWhere('locale', $locale)
                    ?? $model->translations->first();
            };

            $home = Home::query()
                ->with('translations')
                ->where('is_active', true)
                ->first();
            $homeTranslation = $translationFor($home);

            $contact = Contact::query()
                ->where('is_active', true)
                ->latest('id')
                ->first();

            $footerLocations = Location::query()
                ->with('translations')
                ->where('is_active', true)
                ->latest('id')
                ->take(21)
                ->get()
                ->map(function (Location $location) use ($translationFor): ?array {
                    $name = $translationFor($location)?->name;

                    if (blank($name)) {
                        return null;
                    }

                    return [
                        'name' => $name,
                    ];
                })
                ->filter()
                ->values();

            $footerCatalog = Cache::remember(
                "website.footer.catalog.{$locale}",
                now()->addMinutes(30),
                function () use ($locale): array {
                    $footerBrands = Brand::query()
                        ->with('translations')
                        ->where('is_active', true)
                        ->whereNotNull('slug')
                        ->where('slug', '!=', '')
                        ->latest('id')
                        ->take(36)
                        ->get()
                        ->map(function (Brand $brand) use ($locale): ?array {
                            $translation = $brand->translations->firstWhere('locale', $locale)
                                ?? $brand->translations->firstWhere('locale', 'en')
                                ?? $brand->translations->first();
                            $name = trim((string) ($translation?->name ?? ''));

                            if ($name === '') {
                                return null;
                            }

                            return [
                                'name' => $name,
                                'slug' => $brand->slug,
                            ];
                        })
                        ->filter()
                        ->values();

                    $footerCategories = Category::query()
                        ->with('translations')
                        ->where('is_active', true)
                        ->whereNotNull('slug')
                        ->where('slug', '!=', '')
                        ->latest('id')
                        ->take(24)
                        ->get()
                        ->map(function (Category $category) use ($locale): ?array {
                            $translation = $category->translations->firstWhere('locale', $locale)
                                ?? $category->translations->firstWhere('locale', 'en')
                                ?? $category->translations->first();
                            $name = trim((string) ($translation?->name ?? ''));

                            if ($name === '') {
                                return null;
                            }

                            return [
                                'name' => $name,
                                'slug' => $category->slug,
                            ];
                        })
                        ->filter()
                        ->values();

                    return [
                        'brands' => $footerBrands,
                        'categories' => $footerCategories,
                    ];
                }
            );

            $supportItems = collect([
                [
                    'label' => $contact?->phone,
                    'url' => $this->toTelLink($contact?->phone),
                    'icon' => 'bx bxs-phone-call',
                ],
                [
                    'label' => $contact?->alternative_phone,
                    'url' => $this->toWhatsappLink($contact?->alternative_phone),
                    'icon' => 'bx bxl-whatsapp',
                ],
                [
                    'label' => $contact?->email,
                    'url' => filled($contact?->email) ? 'mailto:' . $contact->email : null,
                    'icon' => 'bx bxs-envelope',
                ],
            ])->filter(fn (array $item) => filled($item['label']))->values();

            $socialLinks = collect([
                ['key' => 'facebook', 'url' => $contact?->facebook, 'icon' => 'fa-brands fa-facebook-f'],
                ['key' => 'instagram', 'url' => $contact?->instagram, 'icon' => 'fa-brands fa-instagram'],
                ['key' => 'twitter', 'url' => $contact?->twitter, 'icon' => 'fa-brands fa-twitter'],
                ['key' => 'linkedin', 'url' => $contact?->linkedin, 'icon' => 'fa-brands fa-linkedin-in'],
                ['key' => 'youtube', 'url' => $contact?->youtube, 'icon' => 'fa-brands fa-youtube'],
                ['key' => 'tiktok', 'url' => $contact?->tiktok, 'icon' => 'fa-brands fa-tiktok'],
                ['key' => 'snapchat', 'url' => $contact?->snapchat, 'icon' => 'fa-brands fa-snapchat'],
                ['key' => 'whatsapp', 'url' => $this->toWhatsappLink($contact?->whatsapp), 'icon' => 'fa-brands fa-whatsapp'],
            ])->map(function (array $item): ?array {
                $url = $this->normalizeExternalUrl($item['url'] ?? null);

                if (blank($url)) {
                    return null;
                }

                return [
                    'key' => $item['key'],
                    'url' => $url,
                    'icon' => $item['icon'],
                ];
            })->filter()->values();

            $paymentMethods = collect([
                asset('website/assets/img/icons/payment-01.svg'),
                asset('website/assets/img/icons/payment-02.svg'),
                asset('website/assets/img/icons/payment-03.svg'),
            ]);

            $view->with([
                'footerHomeTranslation' => $homeTranslation,
                'footerLogo' => $branding['dark_logo'],
                'footerDescription' => $homeTranslation?->footer_section_paragraph
                    ?? $homeTranslation?->contact_us_paragraph
                    ?? $contact?->additional_info,
                'footerCompanyName' => $branding['site_name'],
                'footerSupportItems' => $supportItems,
                'footerSocialLinks' => $socialLinks,
                'footerLocations' => $footerLocations,
                'footerBrands' => $footerCatalog['brands'],
                'footerCategories' => $footerCatalog['categories'],
                'footerPaymentMethods' => $paymentMethods,
            ]);
        });

        View::composer('layouts.website', function ($view): void {
            $branding = $this->getBrandingSettings();

            $view->with([
                'websiteSiteName' => $branding['site_name'],
                'websiteLogo' => $branding['logo'],
                'websiteFavicon' => $branding['favicon'],
            ]);
        });

        View::composer(['layouts.admin_layout', 'includes.admin.navbar', 'includes.admin.sidebar'], function ($view): void {
            $branding = $this->getBrandingSettings();
            $existingConfig = is_array($view->getData()['adminNavbar'] ?? null)
                ? $view->getData()['adminNavbar']
                : [];

            $view->with('adminNavbar', array_merge([
                'logo' => $branding['logo'],
                'small_logo' => $branding['logo'],
                'dark_logo' => $branding['dark_logo'],
            ], $existingConfig));

            $view->with([
                'adminSiteName' => $branding['site_name'],
                'adminFavicon' => $branding['favicon'],
            ]);
        });
    }

    private function getBrandingSettings(): array
    {
        static $branding = null;

        if ($branding !== null) {
            return $branding;
        }

        $template = Template::query()->latest('id')->first();
        $siteName = filled($template?->site_name)
            ? trim((string) $template->site_name)
            : config('app.name', 'Afandina Car Rental');
        $logo = $this->normalizeAssetPath(
            $template?->logo_path,
            asset('website/assets/img/logo.svg')
        );
        $darkLogo = $this->normalizeAssetPath(
            $template?->dark_logo_path ?? $template?->logo_path,
            $logo
        );
        $favicon = $this->normalizeAssetPath(
            $template?->favicon_path,
            asset('website/assets/img/favicon.png')
        );

        return $branding = [
            'site_name' => $siteName,
            'logo' => $logo,
            'dark_logo' => $darkLogo,
            'favicon' => $favicon,
        ];
    }

    private function normalizeAssetPath(?string $path, string $fallback): string
    {
        if (blank($path)) {
            return $fallback;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');
        if (Str::startsWith($normalizedPath, ['storage/', 'admin/', 'website/'])) {
            return asset($normalizedPath);
        }

        return asset('storage/' . $normalizedPath);
    }

    private function normalizeExternalUrl(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        if (Str::startsWith($url, ['http://', 'https://', 'mailto:', 'tel:'])) {
            return $url;
        }

        return 'https://' . ltrim($url, '/');
    }

    private function toTelLink(?string $phone): ?string
    {
        if (blank($phone)) {
            return null;
        }

        $normalized = preg_replace('/[^\d+]/', '', $phone);

        return filled($normalized) ? 'tel:' . $normalized : null;
    }

    private function toWhatsappLink(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        $digits = preg_replace('/\D+/', '', $value);

        return filled($digits) ? 'https://wa.me/' . $digits : null;
    }

}
