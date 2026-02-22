<?php return array (
  'menu' =>
  array (
    0 =>
    array (
      'title' => 'Dashboard',
      'icon' => 'icon-home',
      'route' => 'admin.dashboard',
    ),
    1 =>
    array (
      'title' => 'Settings',
      'icon' => 'icon-settings',
      'subMenu' =>
      array (
        0 =>
        array (
          'title' => 'Languages Settings',
          'route' => 'admin.languages.index',
        ),
        1 =>
        array (
          'title' => 'Currencies',
          'route' => 'admin.currencies.index',
        ),
        2 =>
        array (
          'title' => 'Static Translation',
          'route' => 'admin.static-translations.index',
        ),
        3 =>
        array (
          'title' => 'Manage Sitemap',
          'route' => 'admin.sitemap',
        ),
      ),
    ),
    2 =>
    array (
      'title' => 'Cars Management',
      'icon' => 'icon-layers',
      'subMenu' =>
      array (
        0 =>
        array (
          'title' => 'Brands',
          'route' => 'admin.brands.index',
        ),
        1 =>
        array (
          'title' => 'Models',
          'route' => 'admin.car_models.index',
        ),
        2 =>
        array (
          'title' => 'Car Categories',
          'route' => 'admin.categories.index',
        ),
        3 =>
        array (
          'title' => 'Gear Types',
          'route' => 'admin.gear_types.index',
        ),
        4 =>
        array (
          'title' => 'Car Listings',
          'route' => 'admin.cars.index',
        ),
      ),
    ),
    3 =>
    array (
      'title' => 'Attributes',
      'icon' => 'icon-equalizer',
      'subMenu' =>
      array (
        0 =>
        array (
          'title' => 'Colors',
          'route' => 'admin.colors.index',
        ),
        1 =>
        array (
          'title' => 'Locations',
          'route' => 'admin.locations.index',
        ),
        2 =>
        array (
          'title' => 'Features',
          'route' => 'admin.features.index',
        ),
      ),
    ),
    4 =>
    array (
      'title' => 'Blog & Content',
      'icon' => 'icon-notebook',
      'subMenu' =>
      array (
        0 =>
        array (
          'title' => 'Blog Posts',
          'route' => 'admin.blogs.index',
        ),
        1 =>
        array (
          'title' => 'Advertisement',
          'route' => 'admin.advertisements.index',
        ),
        2 =>
        array (
          'title' => 'FAQs',
          'route' => 'admin.faqs.index',
        ),
        3 =>
        array (
          'title' => 'Services',
          'route' => 'admin.services.index',
        ),
        4 =>
        array (
          'title' => 'Required Documents',
          'route' => 'admin.documents.index',
        ),
        5 =>
        array (
          'title' => 'Shot Videos',
          'route' => 'admin.short_videos.index',
        ),
      ),
    ),
    5 =>
    array (
      'title' => 'Pages Settings',
      'icon' => 'icon-screen-tablet',
      'subMenu' =>
      array (
        0 =>
        array (
          'title' => 'Manage Pages',
          'route' => 'admin.pages.index',
        ),
        1 =>
        array (
          'title' => 'Home Page',
          'route' => 'admin.homes.edit',
          'parameter' => '1',
        ),
        2 =>
        array (
          'title' => 'Contact Page',
          'route' => 'admin.contacts.edit',
        ),
        3 =>
        array (
          'title' => 'Contact Messages',
          'route' => 'admin.contact-messages.index',
        ),
        4 =>
        array (
          'title' => 'About Us Page',
          'route' => 'admin.abouts.edit',
          'parameter' => '1',
        ),
      ),
    ),
    6 =>
    array (
      'title' => 'Currency',
      'icon' => 'icon-bag',
      'subMenu' =>
      array (
        0 =>
        array (
          'title' => 'Add Currency',
          'route' => 'admin.currencies.create',
        ),
        1 =>
        array (
          'title' => 'List Currency',
          'route' => 'admin.currencies.index',
        ),
      ),
    ),
  ),
);
