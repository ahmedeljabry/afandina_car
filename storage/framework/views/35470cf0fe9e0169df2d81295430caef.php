<?php $__env->startSection('title', $blogDetails['title'] ?? __('website.blog.page_title')); ?>

<?php $__env->startSection('content'); ?>
    <?php
        use Illuminate\Support\Str;

        $assetUrl = static fn (string $path): string => asset('website/assets/' . ltrim($path, '/'));

        $storageUrl = static function (?string $path, ?string $fallback = null): ?string {
            if (blank($path)) {
                return $fallback;
            }

            if (Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            return asset('storage/' . ltrim($path, '/'));
        };

        $blogTitle = $blogDetails['title'] ?? __('website.blog.common.untitled');
        $blogImage = $storageUrl($blogDetails['image_path'] ?? null, $assetUrl('img/blog/blog-1.jpg'));
    ?>

    <div class="breadcrumb-bar">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title"><?php echo e($blogTitle); ?></h2>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('website.nav.home')); ?></a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('website.blogs.index')); ?>"><?php echo e(__('website.blog.page_title')); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo e(Str::limit($blogTitle, 55)); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="blogbanner" style="background-image: url('<?php echo e($blogImage); ?>'); background-size: cover; background-position: center;">
        <div class="blogbanner-content">
            <?php if(filled($blogDetails['slug'] ?? null)): ?>
                <span class="blog-hint"><?php echo e(Str::headline($blogDetails['slug'])); ?></span>
            <?php endif; ?>
            <h1><?php echo e($blogTitle); ?></h1>
            <?php if(filled($blogDetails['published_on'] ?? null)): ?>
                <ul class="entry-meta meta-item justify-content-center">
                    <li class="date-icon"><i class="fa-solid fa-calendar-days"></i> <?php echo e($blogDetails['published_on']); ?></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="blog-section">
        <div class="container">
            <?php if(filled($blogDetails['description'] ?? null)): ?>
                <div class="blog-description">
                    <p><?php echo e($blogDetails['description']); ?></p>
                </div>
            <?php endif; ?>

            <?php if(filled($blogDetails['content'] ?? null)): ?>
                <div class="blog-description">
                    <?php echo $blogDetails['content']; ?>

                </div>
            <?php endif; ?>

            <?php if($previousPost || $nextPost): ?>
                <div class="blogdetails-pagination">
                    <ul>
                        <?php if($previousPost): ?>
                            <li>
                                <a href="<?php echo e($previousPost['url']); ?>" class="prev-link"><i class="fas fa-regular fa-arrow-left"></i> <?php echo e(__('website.blog.navigation.previous')); ?></a>
                                <a href="<?php echo e($previousPost['url']); ?>"><h3><?php echo e(Str::limit($previousPost['title'], 70)); ?></h3></a>
                            </li>
                        <?php endif; ?>

                        <?php if($nextPost): ?>
                            <li>
                                <a href="<?php echo e($nextPost['url']); ?>" class="next-link"><?php echo e(__('website.blog.navigation.next')); ?> <i class="fas fa-regular fa-arrow-right"></i></a>
                                <a href="<?php echo e($nextPost['url']); ?>"><h3><?php echo e(Str::limit($nextPost['title'], 70)); ?></h3></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if($relatedCars->isNotEmpty()): ?>
                <section class="car-section pt-4">
                    <div class="section-heading heading-four" data-aos="fade-down">
                        <h2><?php echo e(__('website.blog.related_cars_title')); ?></h2>
                    </div>

                    <div class="row row-gap-4">
                        <?php $__currentLoopData = $relatedCars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $carTitle = $car['name'] ?? __('website.common.car');
                                $carUrl = $car['details_url'] ?? 'javascript:void(0);';
                                $carImage = $storageUrl($car['image_path'] ?? null, $assetUrl('img/cars/car-01.jpg'));
                            ?>
                            <div class="col-lg-3 col-md-6 d-flex">
                                <div class="listing-item flex-fill">
                                    <div class="listing-img">
                                        <a href="<?php echo e($carUrl); ?>">
                                            <img src="<?php echo e($carImage); ?>" class="img-fluid" alt="<?php echo e($carTitle); ?>">
                                        </a>
                                    </div>
                                    <div class="listing-content">
                                        <h3 class="listing-title mb-1"><a href="<?php echo e($carUrl); ?>"><?php echo e(Str::limit($carTitle, 38)); ?></a></h3>
                                        <?php if(filled($car['brand_name'] ?? null)): ?>
                                            <p class="mb-1 text-muted"><?php echo e($car['brand_name']); ?></p>
                                        <?php endif; ?>
                                        <div class="d-flex flex-wrap gap-2 text-muted small">
                                            <?php if(filled($car['category_name'] ?? null)): ?>
                                                <span><?php echo e($car['category_name']); ?></span>
                                            <?php endif; ?>
                                            <?php if(filled($car['year'] ?? null)): ?>
                                                <span><?php echo e($car['year']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if($relatedBlogs->isNotEmpty()): ?>
                <section class="blog-section-four pt-4">
                    <div class="section-heading heading-four" data-aos="fade-down">
                        <h2><?php echo e(__('website.blog.related_posts_title')); ?></h2>
                    </div>

                    <div class="row row-gap-3 justify-content-center">
                        <?php $__currentLoopData = $relatedBlogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedBlog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $relatedTitle = $relatedBlog['title'] ?? __('website.blog.common.untitled');
                                $relatedUrl = $relatedBlog['details_url'] ?? 'javascript:void(0);';
                                $relatedImage = $storageUrl($relatedBlog['image_path'] ?? null, $assetUrl('img/blog/blog-11.jpg'));
                            ?>
                            <div class="col-lg-4 col-md-6 d-flex">
                                <div class="blog-item flex-fill">
                                    <div class="blog-img">
                                        <a href="<?php echo e($relatedUrl); ?>">
                                            <img src="<?php echo e($relatedImage); ?>" class="img-fluid" alt="<?php echo e($relatedTitle); ?>">
                                        </a>
                                    </div>
                                    <div class="blog-content">
                                        <h5 class="title">
                                            <a href="<?php echo e($relatedUrl); ?>"><?php echo e(Str::limit($relatedTitle, 65)); ?></a>
                                        </h5>
                                        <?php if(filled($relatedBlog['excerpt'] ?? null)): ?>
                                            <p><?php echo e($relatedBlog['excerpt']); ?></p>
                                        <?php endif; ?>
                                        <?php if(filled($relatedBlog['published_on'] ?? null)): ?>
                                            <p class="date d-inline-flex align-center mb-0">
                                                <i class="bx bx-calendar me-1"></i><?php echo e($relatedBlog['published_on']); ?>

                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.website', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\website\blog-details.blade.php ENDPATH**/ ?>