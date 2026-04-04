@php
    $footerLogo = $footerLogo ?? asset('admin/dist/logo/website_logos/logo_light.svg');
    $footerDescription = $footerDescription ?? null;
    $footerCompanyName = $footerCompanyName ?? config('app.name', 'Afandina Car Rental');
    $footerHomeTranslation = $footerHomeTranslation ?? null;
    $footerBrands = collect($footerBrands ?? []);
    $footerCategories = collect($footerCategories ?? []);
    $footerSupportItems = collect($footerSupportItems ?? $supportItems ?? [])
        ->filter(fn ($item) => is_array($item) && filled(data_get($item, 'label')))
        ->values();
    $footerSocialLinks = collect($footerSocialLinks ?? $socialLinks ?? [])
        ->filter(fn ($item) => is_array($item) && filled(data_get($item, 'url')))
        ->values();
    $footerSocialLabels = [
        'facebook' => __('website.footer.social.facebook'),
        'instagram' => __('website.footer.social.instagram'),
        'twitter' => __('website.footer.social.twitter'),
        'linkedin' => __('website.footer.social.linkedin'),
        'youtube' => __('website.footer.social.youtube'),
        'tiktok' => __('website.footer.social.tiktok'),
        'snapchat' => __('website.footer.social.snapchat'),
        'whatsapp' => __('website.footer.social.whatsapp'),
    ];
    $footerLocations = collect($footerLocations ?? []);
    $footerPaymentMethods = collect($footerPaymentMethods ?? $paymentMethods ?? []);
@endphp

<footer class="footer footer-four">
    <div class="footer-top">
        <div class="container">
            <div class="row row-gap-4">
                <div class="col-lg-4">
                    <div class="footer-contact footer-widget">
                        <div class="footer-logo">
                            <img src="{{ $footerLogo }}" class="img-fluid aos" alt="Logo" loading="lazy" fetchpriority="low" decoding="async">
                        </div>

                        @if (filled($footerDescription))
                            <div class="footer-contact-info">
                                <p>{{ $footerDescription }}</p>
                            </div>
                        @endif

                        @if ($footerSocialLinks->isNotEmpty())
                            <ul class="social-icon">
                                @foreach ($footerSocialLinks as $socialLink)
                                    @php
                                        $socialKey = (string) data_get($socialLink, 'key', '');
                                        $socialUrl = data_get($socialLink, 'url');
                                        $socialIcon = data_get($socialLink, 'icon', 'fa-solid fa-link');
                                        $socialPlatform = $footerSocialLabels[$socialKey] ?? __('website.footer.social.profile');
                                        $socialAriaLabel = __('website.footer.social.open_platform', [
                                            'platform' => $socialPlatform,
                                            'company' => $footerCompanyName,
                                        ]);
                                    @endphp
                                    <li>
                                        <a
                                            href="{{ $socialUrl }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            aria-label="{{ $socialAriaLabel }}"
                                            title="{{ $socialAriaLabel }}">
                                            <i class="{{ $socialIcon }}" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget footer-menu">
                        <h5 class="footer-title">
                            <i class="bx bx-link-alt me-1"></i>{{ __('website.footer.quick_links') }}
                        </h5>
                        <ul>
                            <li>
                                <a href="{{ route('website.about.index') }}">
                                    <i class="bx bxs-info-circle me-1"></i>{{ __('website.footer.links.about_us') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('website.blogs.index') }}">
                                    <i class="bx bxs-notepad me-1"></i>{{ __('website.footer.links.blog') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('website.contact.index') }}">
                                    <i class="bx bxs-envelope me-1"></i>{{ __('website.footer.links.contact_us') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget footer-menu">
                        <h5 class="footer-title">
                            <i class="bx bx-headphone me-1"></i>{{ __('website.footer.support') }}
                        </h5>
                        <ul>
                            @forelse ($footerSupportItems as $supportItem)
                                @php
                                    $supportUrl = data_get($supportItem, 'url');
                                    $supportIcon = data_get($supportItem, 'icon', 'bx bx-link');
                                    $supportLabel = data_get($supportItem, 'label');
                                @endphp
                                <li>
                                    @if (filled($supportUrl))
                                        <a href="{{ $supportUrl }}">
                                            <i class="{{ $supportIcon }} me-1"></i>{{ $supportLabel }}
                                        </a>
                                    @else
                                        <span>
                                            <i class="{{ $supportIcon }} me-1"></i>{{ $supportLabel }}
                                        </span>
                                    @endif
                                </li>
                            @empty
                                <li>{{ __('website.footer.no_support_details') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row row-gap-4 mt-3">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget">
                        <h5 class="footer-title">
                            <i class="bx bxs-car me-1"></i>
                            {{ $footerHomeTranslation?->brand_section_title ?? __('website.footer.brands_section') }}
                        </h5>

                        <div class="d-flex flex-wrap gap-2">
                            @forelse ($footerBrands as $brand)
                                <a href="{{ route('website.cars.brand', ['brand' => data_get($brand, 'slug')]) }}" class="btn btn-outline-light btn-sm rounded-pill">
                                    {{ data_get($brand, 'name') }}
                                </a>
                            @empty
                                <span class="text-muted">{{ __('website.footer.empty_brands') }}</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget">
                        <h5 class="footer-title">
                            <i class="bx bxs-category me-1"></i>
                            {{ $footerHomeTranslation?->category_section_title ?? __('website.footer.categories_section') }}
                        </h5>

                        <div class="d-flex flex-wrap gap-2">
                            @forelse ($footerCategories as $category)
                                <a href="{{ route('website.cars.category', ['category' => data_get($category, 'slug')]) }}" class="btn btn-outline-light btn-sm rounded-pill">
                                    {{ data_get($category, 'name') }}
                                </a>
                            @empty
                                <span class="text-muted">{{ __('website.footer.empty_categories') }}</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="footer-widget">
                        <h5 class="footer-title">
                            <i class="bx bxs-map me-1"></i>
                            {{ $footerHomeTranslation?->where_find_us_section_title ?? __('website.footer.locations') }}
                        </h5>

                        <div class="d-flex flex-wrap gap-2">
                            @forelse ($footerLocations as $location)
                                <span class="btn btn-outline-light btn-sm rounded-pill">
                                    {{ $location['name'] }}
                                </span>
                            @empty
                                <span class="text-muted">{{ __('website.footer.empty_locations') }}</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="copyright">
                <div class="row align-items-center row-gap-3">
                    <div class="col-lg-4">
                        <div class="copyright-text">
                            <p>&copy; {{ now()->year }} {{ $footerCompanyName }}. {{ __('website.footer.rights_reserved') }}</p>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="text-center mb-2 text-white-50">{{ __('website.footer.available_payment_methods') }}</div>
                        <div class="payment-list">
                            @foreach ($footerPaymentMethods as $paymentMethod)
                                <span>
                                    <img src="{{ $paymentMethod }}" alt="payment" loading="lazy" fetchpriority="low" decoding="async">
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <ul class="privacy-link">
                            <li><a href="{{ route('home') }}">{{ __('website.nav.home') }}</a></li>
                            <li><a href="{{ route('website.cars.index') }}">{{ __('website.nav.all_cars') }}</a></li>
                            <li><a href="{{ route('website.blogs.index') }}">{{ __('website.nav.blogs') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
