<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo logo-normal">
            <img src="<?php echo e(asset('admin/assets/img/logo.svg')); ?>" alt="Logo">
        </a>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo-small">
            <img src="<?php echo e(asset('admin/assets/img/logo-small.svg')); ?>" alt="Logo">
        </a>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="dark-logo">
            <img src="<?php echo e(asset('admin/assets/img/logo-white.svg')); ?>" alt="Logo">
        </a>
    </div>
    <!-- /Logo -->
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            
            <div class="form-group">
                <!-- Search -->
                <div class="input-group input-group-flat d-inline-flex">
                    <span class="input-icon-addon">
                        <i class="ti ti-search"></i>
                        </span>
                    <input type="text" class="form-control" placeholder="Search">
                    <span class="group-text">
                        <i class="ti ti-command"></i>
                    </span>
                </div>
                <!-- /Search -->
            </div>
            <ul>
                <li class="menu-title"><span>Main</span></li>
                <li>
                    <ul>
                        <li class="<?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">
                                <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-title"><span>Manage</span></li>
                <li>
                    <ul>
                        <li>
                            <a href="<?php echo e(route('admin.locations.index')); ?>">
                                <i class="ti ti-map-pin"></i><span>Locations</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-title"><span>RENTALS</span></li>
                <li>
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
                <li class="menu-title"><span>CMS</span></li>
                <li>
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
                <li class="menu-title"><span>SUPPORT</span></li>
                <li>
                    <ul>
                        <li>
                            <a href="<?php echo e(route('admin.contact-messages.index')); ?>" >
                                <i class="ti ti-messages"></i><span>Contact Messages</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-title"><span>SETTINGS & CONFIGURATION</span></li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-world-cog"></i><span>Website Settings</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
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