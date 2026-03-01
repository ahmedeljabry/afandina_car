<?php
    $navbarConfig = is_array($adminNavbar ?? null) ? $adminNavbar : [];
    $sidebarLogo = $navbarConfig['logo'] ?? asset('admin/dist/logo/website_logos/logo_light.svg');
    $sidebarSmallLogo = $navbarConfig['small_logo'] ?? $sidebarLogo;
    $sidebarDarkLogo = $navbarConfig['dark_logo'] ?? $sidebarLogo;
    $brandName = $adminSiteName ?? config('app.name', 'Admin');
?>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo logo-normal">
            <img src="<?php echo e($sidebarLogo); ?>" alt="<?php echo e($brandName); ?>">
        </a>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo-small">
            <img src="<?php echo e($sidebarSmallLogo); ?>" alt="<?php echo e($brandName); ?>">
        </a>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="dark-logo">
            <img src="<?php echo e($sidebarDarkLogo); ?>" alt="<?php echo e($brandName); ?>">
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
                        <li class="<?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">
                                <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-title sidebar-section-title"><span>Manage</span></li>
                <li class="sidebar-section-group">
                    <ul>
                        <li>
                            <a href="<?php echo e(route('admin.locations.index')); ?>">
                                <i class="ti ti-map-pin"></i><span>Locations</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-title sidebar-section-title"><span>RENTALS</span></li>
                <li class="sidebar-section-group">
                    <ul>
                        <li>
                            <a href="<?php echo e(route('admin.cars.index')); ?>">
                                <i class="ti ti-car"></i><span>Cars</span>
                            </a>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-device-camera-phone"></i><span>Car Attributes</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="<?php echo e(route('admin.brands.index')); ?>">Brands</a></li>
                                <li><a href="<?php echo e(route('admin.car_models.index')); ?>">Models</a></li>
                                <li><a href="<?php echo e(route('admin.colors.index')); ?>">Colors</a></li>
                                <li><a href="<?php echo e(route('admin.features.index')); ?>">Features</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>	
                <li class="menu-title sidebar-section-title"><span>CMS</span></li>
                <li class="sidebar-section-group">
                    <ul>
                        <li>
                            <a href="<?php echo e(route('admin.pages.index')); ?>" >
                                <i class="ti ti-file-invoice"></i><span>Pages</span>
                            </a>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-device-desktop-analytics"></i><span>Blogs</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="<?php echo e(route('admin.blogs.index')); ?>">All Blogs</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-question-mark"></i><span>FAQ’s</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="<?php echo e(route('admin.faqs.index')); ?>">FAQ's</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="menu-title sidebar-section-title"><span>SUPPORT</span></li>
                <li class="sidebar-section-group">
                    <ul>
                        <li>
                            <a href="<?php echo e(route('admin.contact-messages.index')); ?>" >
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
                                <li class="<?php echo e(request()->routeIs('admin.website-settings.*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('admin.website-settings.edit')); ?>">Branding</a>
                                </li>
                                <li>
                                    <a href="<?php echo e(route('admin.languages.index')); ?>">Language</a>
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
                                    <a href="<?php echo e(route('admin.currencies.index')); ?>">Currencies</a>
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
                                    <a href="<?php echo e(route('admin.sitemap')); ?>">Sitemap</a>
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
<?php /**PATH D:\afandina\resources\views\includes\admin\sidebar.blade.php ENDPATH**/ ?>