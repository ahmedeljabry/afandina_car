@extends('layouts.website')
@section('title', __('website.blog.page_title'))

@section('content')
    @php
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
    @endphp

    <div class="breadcrumb-bar">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title">{{ __('website.blog.page_title') }}</h2>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('website.nav.home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('website.blog.page_title') }}</li>
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
                                    {{
                                        __('website.blog.showing_results', [
                                            'from' => $blogs->firstItem() ?? 0,
                                            'to' => $blogs->lastItem() ?? 0,
                                            'total' => $blogs->total(),
                                        ])
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-8 col-sm-12 col-12">
                            <form id="blog-filter-form" method="GET" action="{{ route('website.blogs.index') }}">
                                <input type="hidden" name="search" value="{{ $search }}">
                                <div class="product-filter-group">
                                    <div class="sortbyset">
                                        <ul>
                                            <li>
                                                <span class="sortbytitle">{{ __('website.blog.sort.show') }} : </span>
                                                <div class="sorting-select select-one">
                                                    <select class="form-control select" name="per_page" onchange="this.form.submit();">
                                                        @foreach($perPageOptions as $option)
                                                            <option value="{{ $option }}" {{ $perPage === $option ? 'selected' : '' }}>{{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </li>
                                            <li>
                                                <span class="sortbytitle">{{ __('website.blog.sort.sort_by') }} </span>
                                                <div class="sorting-select select-two">
                                                    <select class="form-control select" name="sort" onchange="this.form.submit();">
                                                        @foreach($sortOptions as $sortKey => $sortLabel)
                                                            <option value="{{ $sortKey }}" {{ $sort === $sortKey ? 'selected' : '' }}>{{ $sortLabel }}</option>
                                                        @endforeach
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
                    @if($blogs->count() > 0)
                        <div class="row row-gap-4">
                            @foreach($blogs as $blog)
                                @php
                                    $blogTitle = $blog['title'] ?? __('website.blog.common.untitled');
                                    $blogUrl = $blog['details_url'] ?? 'javascript:void(0);';
                                    $blogImage = $storageUrl($blog['image_path'] ?? null, $assetUrl('img/blog/blog-1.jpg'));
                                    $blogExcerpt = $blog['excerpt'] ?? null;
                                    $blogSlug = $blog['slug'] ?? null;
                                @endphp
                                <div class="col-lg-6 col-md-6 d-lg-flex">
                                    <div class="blog grid-blog flex-fill">
                                        <div class="blog-image">
                                            <a href="{{ $blogUrl }}"><img class="img-fluid" src="{{ $blogImage }}" alt="{{ $blogTitle }}"></a>
                                        </div>
                                        <div class="blog-content">
                                            @if(filled($blogSlug))
                                                <p class="blog-category">
                                                    <a href="{{ route('website.blogs.index') }}"><span>{{ Str::headline($blogSlug) }}</span></a>
                                                </p>
                                            @endif

                                            <h3 class="blog-title"><a href="{{ $blogUrl }}">{{ Str::limit($blogTitle, 72) }}</a></h3>

                                            @if(filled($blogExcerpt))
                                                <p class="blog-description">{{ $blogExcerpt }}</p>
                                            @endif

                                            @if(filled($blog['published_on'] ?? null))
                                                <ul class="meta-item">
                                                    <li class="date-icon"><i class="fa-solid fa-calendar-days"></i> <span>{{ $blog['published_on'] }}</span></li>
                                                </ul>
                                            @endif

                                            <a href="{{ $blogUrl }}" class="viewlink btn btn-primary">
                                                {{ __('website.blog.read_more') }} <i class="feather-arrow-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="blog-pagination mt-4">
                            {{ $blogs->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <h5 class="mb-2">{{ __('website.blog.empty_title') }}</h5>
                                <p class="text-muted mb-0">{{ __('website.blog.empty_subtitle') }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4 theiaStickySidebar">
                    <div class="rightsidebar">
                        <div class="card">
                            <h4><img src="{{ $assetUrl('img/icons/details-icon.svg') }}" alt="icon"> {{ __('website.blog.search_label') }}</h4>
                            <form action="{{ route('website.blogs.index') }}" method="GET" class="filter-content looking-input input-block mb-0">
                                <input type="hidden" name="sort" value="{{ $sort }}">
                                <input type="hidden" name="per_page" value="{{ $perPage }}">
                                <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="{{ __('website.blog.search_placeholder') }}">
                            </form>
                        </div>

                        @if($recentBlogs->isNotEmpty())
                            <div class="card mb-0">
                                <h4><i class="feather-tag"></i>{{ __('website.blog.latest_posts') }}</h4>
                                @foreach($recentBlogs as $recentBlog)
                                    @php
                                        $recentTitle = $recentBlog['title'] ?? __('website.blog.common.untitled');
                                        $recentUrl = $recentBlog['details_url'] ?? 'javascript:void(0);';
                                        $recentImage = $storageUrl($recentBlog['image_path'] ?? null, $assetUrl('img/blog/blog-3.jpg'));
                                    @endphp
                                    <div class="article">
                                        <div class="article-blog">
                                            <a href="{{ $recentUrl }}">
                                                <img class="img-fluid" src="{{ $recentImage }}" alt="{{ $recentTitle }}">
                                            </a>
                                        </div>
                                        <div class="article-content">
                                            <h5><a href="{{ $recentUrl }}">{{ Str::limit($recentTitle, 55) }}</a></h5>
                                            @if(filled($recentBlog['published_on'] ?? null))
                                                <div class="article-date">
                                                    <i class="fa-solid fa-calendar-days"></i>
                                                    <span>{{ $recentBlog['published_on'] }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
