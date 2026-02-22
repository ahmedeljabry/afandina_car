<?php $__env->startSection('title', __('website.blog.page_title')); ?>

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

        $search = $filters['search'] ?? '';
        $sort = $filters['sort'] ?? 'newest';
        $perPage = (int) ($filters['per_page'] ?? 6);

        $perPageOptions = [6, 9, 12, 18];
        $sortOptions = [
            'newest' => __('website.blog.sort.newest'),
            'oldest' => __('website.blog.sort.oldest'),
        ];
    ?>

    <div class="breadcrumb-bar">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title"><?php echo e(__('website.blog.page_title')); ?></h2>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('website.nav.home')); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('website.blog.page_title')); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="sort-section">
        <div class="container">
            <div class="sortby-sec">
                <div class="sorting-div">
                    <div class="row d-flex align-items-center">
                        <div class="col-xl-4 col-lg-4 col-sm-12 col-12">
                            <div class="count-search">
                                <p>
                                    <?php echo e(__('website.blog.showing_results', [
                                            'from' => $blogs->firstItem() ?? 0,
                                            'to' => $blogs->lastItem() ?? 0,
                                            'total' => $blogs->total(),
                                        ])); ?>

                                </p>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-8 col-sm-12 col-12">
                            <form id="blog-filter-form" method="GET" action="<?php echo e(route('website.blogs.index')); ?>">
                                <input type="hidden" name="search" value="<?php echo e($search); ?>">
                                <div class="product-filter-group">
                                    <div class="sortbyset">
                                        <ul>
                                            <li>
                                                <span class="sortbytitle"><?php echo e(__('website.blog.sort.show')); ?> : </span>
                                                <div class="sorting-select select-one">
                                                    <select class="form-control select" name="per_page" onchange="this.form.submit();">
                                                        <?php $__currentLoopData = $perPageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($option); ?>" <?php echo e($perPage === $option ? 'selected' : ''); ?>><?php echo e($option); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </li>
                                            <li>
                                                <span class="sortbytitle"><?php echo e(__('website.blog.sort.sort_by')); ?> </span>
                                                <div class="sorting-select select-two">
                                                    <select class="form-control select" name="sort" onchange="this.form.submit();">
                                                        <?php $__currentLoopData = $sortOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sortKey => $sortLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($sortKey); ?>" <?php echo e($sort === $sortKey ? 'selected' : ''); ?>><?php echo e($sortLabel); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="blog-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <?php if($blogs->count() > 0): ?>
                        <div class="row row-gap-4">
                            <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $blogTitle = $blog['title'] ?? __('website.blog.common.untitled');
                                    $blogUrl = $blog['details_url'] ?? 'javascript:void(0);';
                                    $blogImage = $storageUrl($blog['image_path'] ?? null, $assetUrl('img/blog/blog-1.jpg'));
                                    $blogExcerpt = $blog['excerpt'] ?? null;
                                    $blogSlug = $blog['slug'] ?? null;
                                ?>
                                <div class="col-lg-6 col-md-6 d-lg-flex">
                                    <div class="blog grid-blog flex-fill">
                                        <div class="blog-image">
                                            <a href="<?php echo e($blogUrl); ?>"><img class="img-fluid" src="<?php echo e($blogImage); ?>" alt="<?php echo e($blogTitle); ?>"></a>
                                        </div>
                                        <div class="blog-content">
                                            <?php if(filled($blogSlug)): ?>
                                                <p class="blog-category">
                                                    <a href="<?php echo e(route('website.blogs.index')); ?>"><span><?php echo e(Str::headline($blogSlug)); ?></span></a>
                                                </p>
                                            <?php endif; ?>

                                            <h3 class="blog-title"><a href="<?php echo e($blogUrl); ?>"><?php echo e(Str::limit($blogTitle, 72)); ?></a></h3>

                                            <?php if(filled($blogExcerpt)): ?>
                                                <p class="blog-description"><?php echo e($blogExcerpt); ?></p>
                                            <?php endif; ?>

                                            <?php if(filled($blog['published_on'] ?? null)): ?>
                                                <ul class="meta-item">
                                                    <li class="date-icon"><i class="fa-solid fa-calendar-days"></i> <span><?php echo e($blog['published_on']); ?></span></li>
                                                </ul>
                                            <?php endif; ?>

                                            <a href="<?php echo e($blogUrl); ?>" class="viewlink btn btn-primary">
                                                <?php echo e(__('website.blog.read_more')); ?> <i class="feather-arrow-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="blog-pagination mt-4">
                            <?php echo e($blogs->onEachSide(1)->links('pagination::bootstrap-5')); ?>

                        </div>
                    <?php else: ?>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <h5 class="mb-2"><?php echo e(__('website.blog.empty_title')); ?></h5>
                                <p class="text-muted mb-0"><?php echo e(__('website.blog.empty_subtitle')); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4 theiaStickySidebar">
                    <div class="rightsidebar">
                        <div class="card">
                            <h4><img src="<?php echo e($assetUrl('img/icons/details-icon.svg')); ?>" alt="icon"> <?php echo e(__('website.blog.search_label')); ?></h4>
                            <form action="<?php echo e(route('website.blogs.index')); ?>" method="GET" class="filter-content looking-input input-block mb-0">
                                <input type="hidden" name="sort" value="<?php echo e($sort); ?>">
                                <input type="hidden" name="per_page" value="<?php echo e($perPage); ?>">
                                <input type="text" class="form-control" name="search" value="<?php echo e($search); ?>" placeholder="<?php echo e(__('website.blog.search_placeholder')); ?>">
                            </form>
                        </div>

                        <?php if($recentBlogs->isNotEmpty()): ?>
                            <div class="card mb-0">
                                <h4><i class="feather-tag"></i><?php echo e(__('website.blog.latest_posts')); ?></h4>
                                <?php $__currentLoopData = $recentBlogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recentBlog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $recentTitle = $recentBlog['title'] ?? __('website.blog.common.untitled');
                                        $recentUrl = $recentBlog['details_url'] ?? 'javascript:void(0);';
                                        $recentImage = $storageUrl($recentBlog['image_path'] ?? null, $assetUrl('img/blog/blog-3.jpg'));
                                    ?>
                                    <div class="article">
                                        <div class="article-blog">
                                            <a href="<?php echo e($recentUrl); ?>">
                                                <img class="img-fluid" src="<?php echo e($recentImage); ?>" alt="<?php echo e($recentTitle); ?>">
                                            </a>
                                        </div>
                                        <div class="article-content">
                                            <h5><a href="<?php echo e($recentUrl); ?>"><?php echo e(Str::limit($recentTitle, 55)); ?></a></h5>
                                            <?php if(filled($recentBlog['published_on'] ?? null)): ?>
                                                <div class="article-date">
                                                    <i class="fa-solid fa-calendar-days"></i>
                                                    <span><?php echo e($recentBlog['published_on']); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.website', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views/website/blog.blade.php ENDPATH**/ ?>