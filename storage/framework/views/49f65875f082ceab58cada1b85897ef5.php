<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="brand-link">
            <img src="<?php echo e(asset('admin/dist/img/AdminLTELogo.png')); ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Avandina</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo e(asset('admin/dist/img/user2-160x160.jpg')); ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo e(Auth::user()->name); ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php $__currentLoopData = config('sidebar.menu'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <!-- If no subMenu, link directly -->
                    <?php if(empty($menu['subMenu'])): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route($menu['route'])); ?>" class="nav-link <?php echo e(request()->routeIs($menu['route']) ? 'active' : ''); ?>">
                                <i class="nav-icon <?php echo e($menu['icon']); ?>"></i>
                                <p><?php echo e($menu['title']); ?></p>
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- If there is a subMenu -->
                        <li class="nav-item <?php echo e(request()->routeIs(collect($menu['subMenu'])->pluck('route')->toArray()) ? 'menu-open' : ''); ?>">
                            <a href="#" class="nav-link <?php echo e(request()->routeIs(collect($menu['subMenu'])->pluck('route')->toArray()) ? 'active' : ''); ?>">
                                <i class="nav-icon <?php echo e($menu['icon']); ?>"></i>
                                <p>
                                    <?php echo e($menu['title']); ?>

                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php $__currentLoopData = $menu['subMenu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subMenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="nav-item">
                                        <a href="<?php echo e(route($subMenu['route'],$subMenu['parameter']??null)); ?>" class="nav-link <?php echo e(request()->routeIs($subMenu['route']) ? 'active' : ''); ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p><?php echo e($subMenu['title']); ?></p>
                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<?php /**PATH D:\car_rental-src-2025-11-05\car_rental\resources\views/includes/admin/sidebar.blade.php ENDPATH**/ ?>