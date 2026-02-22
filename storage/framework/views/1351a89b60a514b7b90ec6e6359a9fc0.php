<?php
    $robustAppAssets = asset('app-assets');
?>

<!-- fixed-top-->
<nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-semi-dark navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-md-none mr-auto">
                    <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a>
                </li>
                <li class="nav-item">
                    <a class="navbar-brand" href="<?php echo e(route('admin.dashboard')); ?>">
                        <img class="brand-logo" alt="<?php echo e(config('app.name', 'Admin')); ?> logo" src="<?php echo e($robustAppAssets); ?>/images/logo/logo-light-sm.png">
                        <h3 class="brand-text"><?php echo e(config('app.name', 'Robust Admin')); ?></h3>
                    </a>
                </li>
                <li class="nav-item d-md-none">
                    <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="fa fa-ellipsis-v"></i></a>
                </li>
            </ul>
        </div>
        <div class="navbar-container content">
            <div class="collapse navbar-collapse" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">
                    <li class="nav-item d-none d-md-block">
                        <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a>
                    </li>
                    <?php echo $__env->yieldContent('navbar-left'); ?>
                </ul>
                <ul class="nav navbar-nav float-right">
                    <?php echo $__env->yieldContent('navbar-right'); ?>
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <span class="avatar avatar-online">
                                <img src="<?php echo e($robustAppAssets); ?>/images/portrait/small/avatar-s-1.png" alt="avatar">
                            </span>
                            <span class="user-name"><?php echo e(auth()->user()->name ?? __('Admin User')); ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="<?php echo e(route('admin.dashboard')); ?>"><i class="ft-user"></i> <?php echo e(__('Profile')); ?></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo e(route('admin.logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ft-power"></i> <?php echo e(__('Logout')); ?>

                            </a>
                            <form id="logout-form" action="<?php echo e(route('admin.logout')); ?>" method="POST" class="d-none">
                                <?php echo csrf_field(); ?>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<?php /**PATH D:\afandina\resources\views\includes\admin\navbar.blade.php ENDPATH**/ ?>