@php
    $menuItems = config('sidebar.menu', []);
@endphp

<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            @foreach($menuItems as $menu)
                @php
                    $subMenu = $menu['subMenu'] ?? [];
                    $hasChildren = filled($subMenu);
                    $childRoutes = $hasChildren ? collect($subMenu)->pluck('route')->filter()->toArray() : [];
                    $isActive = $hasChildren
                        ? collect($childRoutes)->contains(fn ($route) => request()->routeIs($route))
                        : (isset($menu['route']) ? request()->routeIs($menu['route']) : false);
                    $iconClass = $menu['icon'] ?? 'ft-circle';
                    $menuTitle = __($menu['title'] ?? 'Menu');
                @endphp
                <li class="nav-item {{ $hasChildren ? 'has-sub' : '' }} {{ $isActive ? 'open active' : '' }}">
                    @if($hasChildren)
                        <a href="#">
                            <i class="menu-icon {{ $iconClass }}"></i>
                            <span class="menu-title">{{ $menuTitle }}</span>
                        </a>
                        <ul class="menu-content">
                            @foreach($subMenu as $sub)
                                @php
                                    $subRouteName = $sub['route'] ?? null;
                                    $subRouteParams = $sub['parameter'] ?? null;
                                    $subUrl = $subRouteName ? route($subRouteName, $subRouteParams) : '#';
                                    $subActive = $subRouteName && request()->routeIs($subRouteName);
                                @endphp
                                <li class="{{ $subActive ? 'active' : '' }}">
                                    <a class="menu-item" href="{{ $subUrl }}">{{ __($sub['title'] ?? 'Link') }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <a href="{{ isset($menu['route']) ? route($menu['route']) : '#' }}">
                            <i class="menu-icon {{ $iconClass }}"></i>
                            <span class="menu-title">{{ $menuTitle }}</span>
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
