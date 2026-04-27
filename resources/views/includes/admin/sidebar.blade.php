@php
    $navbarConfig = is_array($adminNavbar ?? null) ? $adminNavbar : [];
    $sidebarLogo = $navbarConfig['logo'] ?? asset('admin/dist/logo/website_logos/logo_light.svg');
    $sidebarSmallLogo = $navbarConfig['small_logo'] ?? $sidebarLogo;
    $sidebarDarkLogo = $navbarConfig['dark_logo'] ?? $sidebarLogo;
    $brandName = $adminSiteName ?? config('app.name', 'Admin');
    $carAttributesTermsContext = request()->routeIs('admin.homes.edit') && request('context') === 'car-attributes';
    $carAttributesActive = request()->routeIs('admin.brands.*')
        || request()->routeIs('admin.categories.*')
        || request()->routeIs('admin.car_models.*')
        || request()->routeIs('admin.colors.*')
        || request()->routeIs('admin.features.*')
        || $carAttributesTermsContext;
    $homeCmsActive = request()->routeIs('admin.homes.*') && !$carAttributesTermsContext;
    $rentalTermsUrl = route('admin.homes.edit', ['home' => 1, 'context' => 'car-attributes']) . '#home-section-shared';
@endphp

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <a href="{{ route('admin.dashboard') }}" class="logo logo-normal">
            <img src="{{ $sidebarLogo }}" alt="{{ $brandName }}">
        </a>
        <a href="{{ route('admin.dashboard') }}" class="logo-small">
            <img src="{{ $sidebarSmallLogo }}" alt="{{ $brandName }}">
        </a>
        <a href="{{ route('admin.dashboard') }}" class="dark-logo">
            <img src="{{ $sidebarDarkLogo }}" alt="{{ $brandName }}">
        </a>
    </div>
    <!-- /Logo -->
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            
            <div class="form-group sidebar-search-wrap">
                <!-- Search -->
                <div class="input-group input-group-flat d-inline-flex w-100">
                    <span class="input-icon-addon">
                        <i class="ti ti-search"></i>
                        </span>
                    <input type="text" id="sidebar-search" class="form-control" placeholder="Search" autocomplete="off" aria-label="Search sidebar menu">
                    <span class="group-text">
                        <i class="ti ti-command"></i>
                    </span>
                </div>
                <!-- /Search -->
            </div>
            <ul>
                <li class="menu-title sidebar-section-title"><span>Main</span></li>
                <li class="sidebar-section-group">
                    <ul>
                        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-title sidebar-section-title"><span>Manage</span></li>
                <li class="sidebar-section-group">
                    <ul>
                        <li>
                            <a href="{{ route('admin.locations.index') }}">
                                <i class="ti ti-map-pin"></i><span>Locations</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-title sidebar-section-title"><span>RENTALS</span></li>
                <li class="sidebar-section-group">
                    <ul>
                        <li>
                            <a href="{{ route('admin.cars.index') }}">
                                <i class="ti ti-car"></i><span>Cars</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.meta-catalog.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.meta-catalog.index') }}">
                                <i class="ti ti-brand-facebook"></i><span>Meta Catalog</span>
                            </a>
                        </li>
                        <li class="submenu {{ $carAttributesActive ? 'active' : '' }}">
                            <a href="javascript:void(0);">
                                <i class="ti ti-device-camera-phone"></i><span>Car Attributes</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}">Brands</a></li>
                                <li><a class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">Categories</a></li>
                                <li><a class="{{ request()->routeIs('admin.car_models.*') ? 'active' : '' }}" href="{{ route('admin.car_models.index') }}">Models</a></li>
                                <li><a class="{{ request()->routeIs('admin.colors.*') ? 'active' : '' }}" href="{{ route('admin.colors.index') }}">Colors</a></li>
                                <li><a class="{{ request()->routeIs('admin.features.*') ? 'active' : '' }}" href="{{ route('admin.features.index') }}">Features</a></li>
                                <li><a class="{{ $carAttributesTermsContext ? 'active' : '' }}" href="{{ $rentalTermsUrl }}">Rental Terms</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>	
                <li class="menu-title sidebar-section-title"><span>CMS</span></li>
                <li class="sidebar-section-group">
                    <ul>
                        <li class="submenu {{ $homeCmsActive ? 'active' : '' }}">
                            <a href="javascript:void(0);">
                                <i class="ti ti-home"></i><span>Home</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="{{ $homeCmsActive ? 'active' : '' }}">
                                    <a href="{{ route('admin.homes.edit', 1) }}#tab-overview">Home Overview</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.homes.edit', 1) }}#tab-hero">Hero Banner</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.homes.edit', 1) }}#tab-features">Features</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.homes.edit', 1) }}#tab-rental">Rental &amp; Stats</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.homes.edit', 1) }}#tab-headings">Section Headings</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.homes.edit', 1) }}#tab-testimonials">Testimonials</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.homes.edit', 1) }}#tab-clients">Client Slider</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.homes.edit', 1) }}#tab-support">Support Ticker</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.homes.edit', 1) }}#tab-shared">Shared Content</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.homes.edit', 1) }}#tab-seo">SEO</a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.pages.index') }}">
                                <i class="ti ti-file-text"></i><span>Manage Pages</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.abouts.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.abouts.edit', 1) }}">
                                <i class="ti ti-info-circle"></i><span>About Us Page</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.contacts.edit') }}">
                                <i class="ti ti-address-book"></i><span>Contact Page</span>
                            </a>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-device-desktop-analytics"></i><span>Blogs</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('admin.blogs.index') }}">All Blogs</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-question-mark"></i><span>FAQ’s</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('admin.faqs.index') }}">FAQ's</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="menu-title sidebar-section-title"><span>SUPPORT</span></li>
                <li class="sidebar-section-group">
                    <ul>
                        <li>
                            <a href="{{ route('admin.contact-messages.index') }}" >
                                <i class="ti ti-messages"></i><span>Contact Messages</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-title sidebar-section-title"><span>SETTINGS & CONFIGURATION</span></li>
                <li class="sidebar-section-group">
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-world-cog"></i><span>Website Settings</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="{{ request()->routeIs('admin.website-settings.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.website-settings.edit') }}">Branding</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.languages.index') }}">Language</a>
                                </li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-settings-dollar"></i><span>Finance Settings</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('admin.currencies.index') }}">Currencies</a>
                                </li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-settings-2"></i><span>Other Settings</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('admin.sitemap') }}">Sitemap</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
