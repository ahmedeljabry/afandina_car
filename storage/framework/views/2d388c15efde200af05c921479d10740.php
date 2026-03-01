<?php $__env->startSection('title', __('Dashboard')); ?>
<?php $__env->startSection('page-title', __('Admin Dashboard')); ?>
<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Overview')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('admin.cars.create')); ?>" class="btn btn-primary d-inline-flex align-items-center me-2 mb-2">
        <i class="ti ti-plus me-1"></i><?php echo e(__('Add Car')); ?>

    </a>
    <a href="<?php echo e(route('admin.blogs.create')); ?>" class="btn btn-dark d-inline-flex align-items-center me-2 mb-2">
        <i class="ti ti-pencil-plus me-1"></i><?php echo e(__('New Blog')); ?>

    </a>
    <button type="button" class="btn btn-outline-primary d-inline-flex align-items-center mb-2" id="generate-sitemap-btn">
        <i class="ti ti-map me-1"></i><?php echo e(__('Generate Sitemap')); ?>

    </button>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .dashboard-hero {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 20px;
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 65%, #0ea5e9 100%);
            color: #fff;
            overflow: hidden;
            position: relative;
        }

        .dashboard-hero::after {
            content: "";
            position: absolute;
            right: -80px;
            top: -80px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.14);
        }

        .dashboard-hero .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.15);
            font-size: 13px;
            margin-right: 8px;
            margin-bottom: 8px;
        }

        .dashboard-kpi {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
            transition: all 0.2s ease;
        }

        .dashboard-kpi:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 24px rgba(15, 23, 42, 0.08);
        }

        .kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .icon-cars {
            background: rgba(37, 99, 235, 0.14);
            color: #2563eb;
        }

        .icon-categories {
            background: rgba(249, 115, 22, 0.14);
            color: #f97316;
        }

        .icon-brands {
            background: rgba(14, 165, 233, 0.14);
            color: #0ea5e9;
        }

        .icon-blogs {
            background: rgba(16, 185, 129, 0.14);
            color: #10b981;
        }

        .block-card {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
        }

        .mini-stat {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 12px;
            padding: 10px 12px;
        }

        .activity-item {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 12px 0;
            border-bottom: 1px dashed rgba(15, 23, 42, 0.12);
        }

        .activity-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .activity-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: rgba(37, 99, 235, 0.12);
            color: #2563eb;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .dashboard-table td, .dashboard-table th {
            vertical-align: middle;
        }

        .quick-link-btn {
            border: 1px solid rgba(255, 255, 255, 0.28);
            color: #fff;
            background: transparent;
        }

        .quick-link-btn:hover {
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $locale = app()->getLocale() ?: 'en';

        $translateValue = function ($model, string $field = 'name') use ($locale): string {
            if (!$model || !isset($model->translations)) {
                return __('N/A');
            }

            $translation = $model->translations->firstWhere('locale', $locale)
                ?? $model->translations->first();

            return filled($translation?->{$field}) ? $translation->{$field} : __('N/A');
        };

        $resolveCarImage = function (?string $imagePath): string {
            if (blank($imagePath)) {
                return asset('admin/assets/img/car/car-01.jpg');
            }

            if (\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://', '//'])) {
                return $imagePath;
            }

            return asset(ltrim($imagePath, '/'));
        };

        $growthText = ($carsGrowth >= 0 ? '+' : '') . number_format($carsGrowth, 1) . '%';
        $growthClass = $carsGrowth >= 0 ? 'text-success' : 'text-danger';
    ?>

    <div class="row g-3 mb-3">
        <div class="col-xxl-8 d-flex">
            <div class="card dashboard-hero w-100">
                <div class="card-body p-4 p-md-5 position-relative">
                    <div class="d-flex justify-content-between flex-wrap gap-3">
                        <div class="pe-md-5">
                            <h3 class="mb-2"><?php echo e(__('Welcome back, :name', ['name' => auth()->user()->name ?? __('Admin')])); ?></h3>
                            <p class="mb-4 text-white-50"><?php echo e(__('Your operations snapshot is up to date. Monitor inventory, content, and customer activity in one place.')); ?></p>
                            <div class="mb-3">
                                <?php $__currentLoopData = $quickActions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e($action['url']); ?>" class="btn quick-link-btn btn-sm d-inline-flex align-items-center me-2 mb-2">
                                        <i class="<?php echo e($action['icon']); ?> me-1"></i><?php echo e(__($action['label'])); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="text-md-end">
                            <div class="chip"><i class="ti ti-car"></i><?php echo e(__('Total Cars: :count', ['count' => $totalCars])); ?></div>
                            <div class="chip"><i class="ti ti-sparkles"></i><?php echo e(__('Featured: :count', ['count' => $featuredCars])); ?></div>
                            <div class="chip"><i class="ti ti-mail"></i><?php echo e(__('New Messages (7d): :count', ['count' => $newMessagesThisWeek])); ?></div>
                            <div class="chip"><i class="ti ti-trending-up"></i><?php echo e(__('Cars Growth: :rate', ['rate' => $growthText])); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4 d-flex">
            <div class="card block-card w-100">
                <div class="card-header border-0 pb-0">
                    <h5 class="mb-0"><?php echo e(__('Fleet Health')); ?></h5>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $fleetDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-medium"><?php echo e(__($item['label'])); ?></span>
                                <span class="text-muted"><?php echo e($item['count']); ?> (<?php echo e(number_format($item['percent'], 1)); ?>%)</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" style="width: <?php echo e($item['percent']); ?>%; background-color: <?php echo e($item['color']); ?>"></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6 d-flex">
            <div class="card dashboard-kpi w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="kpi-icon icon-cars"><i class="ti ti-car"></i></span>
                        <span class="badge bg-success-subtle text-success"><?php echo e(__('Active: :count', ['count' => $activeCars])); ?></span>
                    </div>
                    <h4 class="mb-1"><?php echo e(number_format($totalCars)); ?></h4>
                    <p class="mb-0 text-muted"><?php echo e(__('Total Cars')); ?></p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 d-flex">
            <div class="card dashboard-kpi w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="kpi-icon icon-categories"><i class="ti ti-layout-grid"></i></span>
                        <span class="badge bg-warning-subtle text-warning"><?php echo e(__('Active: :count', ['count' => $activeCategories])); ?></span>
                    </div>
                    <h4 class="mb-1"><?php echo e(number_format($totalCategories)); ?></h4>
                    <p class="mb-0 text-muted"><?php echo e(__('Categories')); ?></p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 d-flex">
            <div class="card dashboard-kpi w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="kpi-icon icon-brands"><i class="ti ti-badge"></i></span>
                        <span class="badge bg-info-subtle text-info"><?php echo e(__('Active: :count', ['count' => $activeBrands])); ?></span>
                    </div>
                    <h4 class="mb-1"><?php echo e(number_format($totalBrands)); ?></h4>
                    <p class="mb-0 text-muted"><?php echo e(__('Brands')); ?></p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 d-flex">
            <div class="card dashboard-kpi w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="kpi-icon icon-blogs"><i class="ti ti-news"></i></span>
                        <span class="badge bg-success-subtle text-success"><?php echo e(__('Active: :count', ['count' => $activeBlogs])); ?></span>
                    </div>
                    <h4 class="mb-1"><?php echo e(number_format($totalBlogs)); ?></h4>
                    <p class="mb-0 text-muted"><?php echo e(__('Blog Posts')); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-xl-8 d-flex">
            <div class="card block-card w-100">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h5 class="mb-1"><?php echo e(__('Cars Added Trend')); ?></h5>
                        <p class="text-muted mb-0"><?php echo e(__('Last 6 months')); ?></p>
                    </div>
                    <div class="text-end">
                        <div class="mini-stat mb-1">
                            <span class="text-muted"><?php echo e(__('This Month')); ?></span>
                            <h6 class="mb-0"><?php echo e($carsThisMonth); ?></h6>
                        </div>
                        <span class="<?php echo e($growthClass); ?> fw-semibold"><?php echo e($growthText); ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="carsGrowthChart" style="min-height: 300px;"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 d-flex">
            <div class="card block-card w-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"><?php echo e(__('Recent Messages')); ?></h5>
                    <a href="<?php echo e(route('admin.contact-messages.index')); ?>" class="text-decoration-underline"><?php echo e(__('View All')); ?></a>
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $latestMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="activity-item">
                            <span class="activity-icon"><i class="ti ti-mail"></i></span>
                            <div class="flex-grow-1">
                                <h6 class="fs-14 mb-1"><?php echo e($message->full_name ?: __('Contact Request')); ?></h6>
                                <p class="text-muted mb-1"><?php echo e(\Illuminate\Support\Str::limit($message->subject ?: $message->message, 60)); ?></p>
                                <small class="text-muted"><?php echo e(optional($message->created_at)->diffForHumans()); ?></small>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted mb-0"><?php echo e(__('No messages yet.')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-xl-7 d-flex">
            <div class="card block-card w-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"><?php echo e(__('Latest Cars')); ?></h5>
                    <a href="<?php echo e(route('admin.cars.index')); ?>" class="text-decoration-underline"><?php echo e(__('Manage Cars')); ?></a>
                </div>
                <div class="card-body pb-0">
                    <div class="table-responsive">
                        <table class="table dashboard-table align-middle">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Car')); ?></th>
                                    <th><?php echo e(__('Brand')); ?></th>
                                    <th><?php echo e(__('Category')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th class="text-end"><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $latestCars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar me-2">
                                                    <img src="<?php echo e($resolveCarImage($car->default_image_path)); ?>" alt="<?php echo e(__('Car')); ?>" class="rounded">
                                                </span>
                                                <div>
                                                    <h6 class="fs-14 mb-1"><?php echo e($translateValue($car, 'name')); ?></h6>
                                                    <small class="text-muted"><?php echo e(optional($car->created_at)->diffForHumans()); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo e($translateValue($car->brand, 'name')); ?></td>
                                        <td><?php echo e($translateValue($car->category, 'name')); ?></td>
                                        <td>
                                            <?php if($car->status === 'available'): ?>
                                                <span class="badge bg-success-subtle text-success"><?php echo e(__('Available')); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger"><?php echo e(__('Not Available')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <a href="<?php echo e(route('admin.cars.edit', $car->id)); ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4"><?php echo e(__('No cars available.')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5 d-flex">
            <div class="card block-card w-100">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__('Top Segments')); ?></h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-3"><?php echo e(__('Top Categories')); ?></h6>
                    <?php $__empty_1 = true; $__currentLoopData = $topCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $percent = $totalCars > 0 ? round(($category->cars_count / $totalCars) * 100, 1) : 0;
                        ?>
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span><?php echo e($translateValue($category, 'name')); ?></span>
                                <small class="text-muted"><?php echo e($category->cars_count); ?> (<?php echo e($percent); ?>%)</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: <?php echo e($percent); ?>%;"></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted"><?php echo e(__('No category data.')); ?></p>
                    <?php endif; ?>

                    <hr>

                    <h6 class="mb-3"><?php echo e(__('Top Brands')); ?></h6>
                    <?php $__empty_1 = true; $__currentLoopData = $topBrands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $brandPercent = $totalCars > 0 ? round(($brand->cars_count / $totalCars) * 100, 1) : 0;
                        ?>
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span><?php echo e($translateValue($brand, 'name')); ?></span>
                                <small class="text-muted"><?php echo e($brand->cars_count); ?> (<?php echo e($brandPercent); ?>%)</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-info" style="width: <?php echo e($brandPercent); ?>%;"></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted mb-0"><?php echo e(__('No brand data.')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-6 d-flex">
            <div class="card block-card w-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"><?php echo e(__('Recent Blogs')); ?></h5>
                    <a href="<?php echo e(route('admin.blogs.index')); ?>" class="text-decoration-underline"><?php echo e(__('View All')); ?></a>
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $recentBlogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="activity-item">
                            <span class="activity-icon"><i class="ti ti-news"></i></span>
                            <div class="flex-grow-1">
                                <h6 class="fs-14 mb-1"><?php echo e($translateValue($blog, 'title')); ?></h6>
                                <small class="text-muted"><?php echo e(optional($blog->created_at)->diffForHumans()); ?></small>
                            </div>
                            <a href="<?php echo e(route('admin.blogs.edit', $blog->id)); ?>" class="btn btn-sm btn-outline-dark">
                                <i class="ti ti-edit"></i>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted mb-0"><?php echo e(__('No blogs published yet.')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-6 d-flex">
            <div class="card block-card w-100">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__('Live Activity')); ?></h5>
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="activity-item">
                            <span class="activity-icon"><i class="<?php echo e($activity['icon']); ?>"></i></span>
                            <div class="flex-grow-1">
                                <h6 class="fs-14 mb-1"><?php echo e(__($activity['title'])); ?></h6>
                                <p class="mb-1 text-muted"><?php echo e($activity['meta']); ?></p>
                                <small class="text-muted"><?php echo e(optional($activity['time'])->diffForHumans()); ?></small>
                            </div>
                            <a href="<?php echo e($activity['url']); ?>" class="btn btn-sm btn-light">
                                <i class="ti ti-chevron-right"></i>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted mb-0"><?php echo e(__('No recent activity.')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartEl = document.getElementById('carsGrowthChart');
            if (chartEl && typeof ApexCharts !== 'undefined') {
                const monthlyLabels = <?php echo json_encode($monthlyCarsData->pluck('label')->values(), 15, 512) ?>;
                const monthlyCounts = <?php echo json_encode($monthlyCarsData->pluck('count')->values(), 15, 512) ?>;

                const carsGrowthChart = new ApexCharts(chartEl, {
                    chart: {
                        type: 'area',
                        height: 300,
                        toolbar: { show: false }
                    },
                    series: [{
                        name: "<?php echo e(__('Cars Added')); ?>",
                        data: monthlyCounts
                    }],
                    xaxis: {
                        categories: monthlyLabels,
                        labels: { style: { fontSize: '12px' } }
                    },
                    yaxis: {
                        min: 0,
                        forceNiceScale: true,
                        labels: { style: { fontSize: '12px' } }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    colors: ['#2563EB'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 0.4,
                            opacityFrom: 0.45,
                            opacityTo: 0.05,
                            stops: [0, 90, 100]
                        }
                    },
                    grid: {
                        borderColor: '#E5E7EB',
                        strokeDashArray: 4
                    },
                    dataLabels: { enabled: false },
                    tooltip: {
                        y: {
                            formatter: function (value) {
                                return value + " <?php echo e(__('cars')); ?>";
                            }
                        }
                    }
                });

                carsGrowthChart.render();
            }

            const sitemapBtn = document.getElementById('generate-sitemap-btn');
            if (!sitemapBtn) {
                return;
            }

            sitemapBtn.addEventListener('click', function () {
                const originalHTML = sitemapBtn.innerHTML;
                sitemapBtn.disabled = true;
                sitemapBtn.innerHTML = '<i class="ti ti-loader-2 me-1"></i><?php echo e(__('Generating...')); ?>';

                $.ajax({
                    url: '<?php echo e(route("admin.sitemap.generate")); ?>',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: '<?php echo e(__('Success')); ?>',
                                text: response.message || '<?php echo e(__('Sitemap generated successfully!')); ?>',
                                timer: 2500,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function (xhr) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: '<?php echo e(__('Error')); ?>',
                                text: xhr.responseJSON?.message || '<?php echo e(__('Failed to generate sitemap.')); ?>'
                            });
                        }
                    },
                    complete: function () {
                        sitemapBtn.disabled = false;
                        sitemapBtn.innerHTML = originalHTML;
                    }
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\dashboard.blade.php ENDPATH**/ ?>