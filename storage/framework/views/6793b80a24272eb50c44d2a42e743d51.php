<?php $__env->startSection('title', __('Website Settings')); ?>
<?php $__env->startSection('page-title', __('Website Settings')); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Website Settings')); ?></li>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('includes.admin.form_theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .branding-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(300px, 1fr);
            gap: 1.5rem;
        }

        .branding-card {
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            background: #fff;
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.08);
        }

        .branding-card .card-header {
            padding: 1.15rem 1.35rem;
            border-bottom: 1px solid #eef2f7;
            background: linear-gradient(135deg, #f8fafc, #eff6ff);
        }

        .branding-card .card-header h5,
        .branding-card .card-header h6 {
            margin: 0;
            font-weight: 700;
            color: #0f172a;
        }

        .branding-card .card-body {
            padding: 1.35rem;
        }

        .branding-preview {
            border: 1px dashed #cbd5e1;
            border-radius: 20px;
            padding: 1rem;
            background: #f8fafc;
            text-align: center;
        }

        .branding-preview--dark {
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .branding-preview img {
            max-width: 100%;
            max-height: 96px;
            object-fit: contain;
        }

        .branding-preview--favicon img {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #fff;
            padding: 6px;
        }

        .branding-help {
            margin: 0;
            font-size: 0.9rem;
            line-height: 1.7;
            color: #64748b;
        }

        .branding-facts {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .branding-facts li + li {
            margin-top: 0.85rem;
        }

        .branding-facts strong {
            display: block;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 0.3rem;
        }

        .branding-facts span {
            display: block;
            color: #0f172a;
            font-weight: 600;
        }

        @media (max-width: 1199.98px) {
            .branding-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="form-hero">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
            <div>
                <h2 class="mb-1"><?php echo e(__('Branding & Identity')); ?></h2>
                <p class="mb-0"><?php echo e(__('Manage the global website name, primary logo, dark logo, and favicon from one place.')); ?></p>
            </div>
            <div class="d-flex flex-wrap mt-3 mt-lg-0">
                <span class="hero-pill"><i class="ti ti-world"></i><?php echo e(__('Frontend ready')); ?></span>
                <span class="hero-pill"><i class="ti ti-layout-dashboard"></i><?php echo e(__('Admin branding linked')); ?></span>
            </div>
        </div>
    </div>

    <div class="branding-grid">
        <div class="card branding-card">
            <div class="card-header">
                <h5><?php echo e(__('Update Website Branding')); ?></h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.website-settings.update')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="form-group">
                        <label for="site_name" class="font-weight-bold"><?php echo e(__('Site Name')); ?></label>
                        <input
                            type="text"
                            id="site_name"
                            name="site_name"
                            class="form-control shadow-sm <?php $__errorArgs = ['site_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('site_name', $settings->site_name)); ?>"
                            placeholder="<?php echo e(__('Enter your website name')); ?>"
                        >
                        <?php $__errorArgs = ['site_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="logo_path" class="font-weight-bold"><?php echo e(__('Main Logo')); ?></label>
                        <div class="custom-file">
                            <input type="file" id="logo_path" name="logo_path" class="custom-file-input <?php $__errorArgs = ['logo_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <label class="custom-file-label" for="logo_path"><?php echo e(__('Choose main logo')); ?></label>
                        </div>
                        <?php $__errorArgs = ['logo_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="dark_logo_path" class="font-weight-bold"><?php echo e(__('Dark Background Logo')); ?></label>
                        <div class="custom-file">
                            <input type="file" id="dark_logo_path" name="dark_logo_path" class="custom-file-input <?php $__errorArgs = ['dark_logo_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <label class="custom-file-label" for="dark_logo_path"><?php echo e(__('Choose dark logo')); ?></label>
                        </div>
                        <?php $__errorArgs = ['dark_logo_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="favicon_path" class="font-weight-bold"><?php echo e(__('Favicon')); ?></label>
                        <div class="custom-file">
                            <input type="file" id="favicon_path" name="favicon_path" class="custom-file-input <?php $__errorArgs = ['favicon_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <label class="custom-file-label" for="favicon_path"><?php echo e(__('Choose favicon')); ?></label>
                        </div>
                        <?php $__errorArgs = ['favicon_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i><?php echo e(__('Save Settings')); ?>

                    </button>
                </form>
            </div>
        </div>

        <div class="d-flex flex-column gap-3">
            <div class="card branding-card mb-3">
                <div class="card-header">
                    <h6><?php echo e(__('Live Preview')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="branding-preview mb-3">
                        <img src="<?php echo e($logoPreview); ?>" alt="<?php echo e(__('Main logo')); ?>">
                    </div>
                    <div class="branding-preview branding-preview--dark mb-3">
                        <img src="<?php echo e($darkLogoPreview); ?>" alt="<?php echo e(__('Dark logo')); ?>">
                    </div>
                    <div class="branding-preview branding-preview--favicon">
                        <img src="<?php echo e($faviconPreview); ?>" alt="<?php echo e(__('Favicon')); ?>">
                    </div>
                </div>
            </div>

            <div class="card branding-card">
                <div class="card-header">
                    <h6><?php echo e(__('Where It Applies')); ?></h6>
                </div>
                <div class="card-body">
                    <p class="branding-help"><?php echo e(__('These settings are used across the public website and the admin interface.')); ?></p>
                    <ul class="branding-facts mt-3">
                        <li>
                            <strong><?php echo e(__('Main Logo')); ?></strong>
                            <span><?php echo e(__('Frontend header, admin login, and admin navigation.')); ?></span>
                        </li>
                        <li>
                            <strong><?php echo e(__('Dark Logo')); ?></strong>
                            <span><?php echo e(__('Footer and dark-background branding areas.')); ?></span>
                        </li>
                        <li>
                            <strong><?php echo e(__('Favicon')); ?></strong>
                            <span><?php echo e(__('Browser tab icon for frontend pages and admin pages.')); ?></span>
                        </li>
                        <li>
                            <strong><?php echo e(__('Site Name')); ?></strong>
                            <span><?php echo e(__('Frontend page titles, footer copyright, and admin title text.')); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\website_settings\edit.blade.php ENDPATH**/ ?>