<?php
    $menuItems = config('sidebar.menu', []);
?>

<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <?php $__currentLoopData = $menuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $subMenu = $menu['subMenu'] ?? [];
                    $hasChildren = filled($subMenu);
                    $childRoutes = $hasChildren ? collect($subMenu)->pluck('route')->filter()->toArray() : [];
                    $isActive = $hasChildren
                        ? collect($childRoutes)->contains(fn ($route) => request()->routeIs($route))
                        : (isset($menu['route']) ? request()->routeIs($menu['route']) : false);
                    $iconClass = $menu['icon'] ?? 'ft-circle';
                    $menuTitle = __($menu['title'] ?? 'Menu');
                ?>
                <li class="nav-item <?php echo e($hasChildren ? 'has-sub' : ''); ?> <?php echo e($isActive ? 'open active' : ''); ?>">
                    <?php if($hasChildren): ?>
                        <a href="#">
                            <i class="menu-icon <?php echo e($iconClass); ?>"></i>
                            <span class="menu-title"><?php echo e($menuTitle); ?></span>
                        </a>
                        <ul class="menu-content">
                            <?php $__currentLoopData = $subMenu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $subRouteName = $sub['route'] ?? null;
                                    $subRouteParams = $sub['parameter'] ?? null;
                                    $subUrl = $subRouteName ? route($subRouteName, $subRouteParams) : '#';
                                    $subActive = $subRouteName && request()->routeIs($subRouteName);
                                ?>
                                <li class="<?php echo e($subActive ? 'active' : ''); ?>">
                                    <a class="menu-item" href="<?php echo e($subUrl); ?>"><?php echo e(__($sub['title'] ?? 'Link')); ?></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php else: ?>
                        <a href="<?php echo e(isset($menu['route']) ? route($menu['route']) : '#'); ?>">
                            <i class="menu-icon <?php echo e($iconClass); ?>"></i>
                            <span class="menu-title"><?php echo e($menuTitle); ?></span>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>
<?php /**PATH D:\afandina\resources\views\includes\admin\sidebar.blade.php ENDPATH**/ ?>