@php
    $footerLogo = $footerLogo ?? asset('admin/dist/logo/website_logos/logo_light.svg');
    $footerDescription = $footerDescription ?? null;
    $footerCompanyName = $footerCompanyName ?? config('app.name', 'Afandina Car Rental');
    $footerHomeTranslation = $footerHomeTranslation ?? null;
    $quickLinks = $quickLinks ?? collect();
    $supportItems = $supportItems ?? collect();
    $socialLinks = $socialLinks ?? collect();
    $footerBrands = $footerBrands ?? collect();
    $footerCategories = $footerCategories ?? collect();
    $footerLocations = $footerLocations ?? collect();
    $paymentMethods = $paymentMethods ?? collect();
@endphp

<footer class="footer footer-four">
    <div class="footer-top aos" data-aos="fade-up">
        <div class="container">
            <div class="row row-gap-4">
                <div class="col-lg-4">
                    <div class="footer-contact footer-widget">
                        <div class="footer-logo">
                            <img src="{{ $footerLogo }}" class="img-fluid aos" alt="Logo">
                        </div>

                        @if (filled($footerDescription))
                            <div class="footer-contact-info">
                                <p>{{ $footerDescription }}</p>
                            </div>
                        @endif

                        @if ($socialLinks->isNotEmpty())
                            <ul class="social-icon">
                                @foreach ($socialLinks as $socialLink)
                                    <li>
                                        <a href="{{ $socialLink['url'] }}" target="_blank" rel="noopener noreferrer">
                                            <i class="{{ $socialLink['icon'] }}"></i>
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
                            @foreach ($quickLinks as $link)
                                <li>
                                    <a href="{{ $link['url'] }}">
                                        <i class="{{ $link['icon'] }} me-1"></i>{{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget footer-menu">
                        <h5 class="footer-title">
                            <i class="bx bx-headphone me-1"></i>{{ __('website.footer.support') }}
                        </h5>
                        <ul>
                            @forelse ($supportItems as $supportItem)
                                <li>
                                    <a href="{{ $supportItem['url'] ?: 'javascript:void(0);' }}">
                                        <i class="{{ $supportItem['icon'] }} me-1"></i>{{ $supportItem['label'] }}
                                    </a>
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
                                <a href="{{ $brand['url'] }}" class="btn btn-outline-light btn-sm rounded-pill">
                                    {{ $brand['name'] }}
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
                                <a href="{{ $category['url'] }}" class="btn btn-outline-light btn-sm rounded-pill">
                                    {{ $category['name'] }}
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
                                <a href="{{ $location['url'] }}" class="btn btn-outline-light btn-sm rounded-pill">
                                    {{ $location['name'] }}
                                </a>
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
                            @foreach ($paymentMethods as $paymentMethod)
                                <a href="javascript:void(0);">
                                    <img src="{{ $paymentMethod }}" alt="payment">
                                </a>
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
