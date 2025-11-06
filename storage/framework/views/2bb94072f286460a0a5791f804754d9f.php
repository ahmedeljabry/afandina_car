<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('admin.logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>

        <form id="logout-form" action="<?php echo e(route('admin.logout')); ?>" method="POST" style="display: none;">
            <?php echo csrf_field(); ?>
        </form>

    </ul>
</nav>
<!-- /.navbar -->
<?php /**PATH D:\car_rental-src-2025-11-05\car_rental\resources\views/includes/admin/navbar.blade.php ENDPATH**/ ?>