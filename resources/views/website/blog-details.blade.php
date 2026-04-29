@extends('layouts.website')
@section('title', $blogDetails['title'] ?? __('website.blog.page_title'))

@push('css')
    <style>
        .blog-editor-content {
            color: #4f5761;
            font-size: 16px;
            line-height: 1.75;
        }

        .blog-editor-content > *:last-child {
            margin-bottom: 0;
        }

        .blog-editor-content p,
        .blog-editor-content li {
            color: #4f5761;
            font-size: 16px;
            line-height: 1.75;
        }

        .blog-editor-content p {
            margin-bottom: 14px;
        }

        .blog-editor-content h1,
        .blog-editor-content h2,
        .blog-editor-content h3,
        .blog-editor-content h4,
        .blog-editor-content h5,
        .blog-editor-content h6 {
            margin-top: 18px;
            margin-bottom: 10px;
            color: #1f1f1f;
            font-weight: 700;
            line-height: 1.35;
        }

        .blog-editor-content h1 {
            font-size: 30px;
        }

        .blog-editor-content h2 {
            font-size: 26px;
        }

        .blog-editor-content h3 {
            font-size: 23px;
        }

        .blog-editor-content h4,
        .blog-editor-content h5,
        .blog-editor-content h6 {
            font-size: 20px;
        }

        .blog-editor-content ul,
        .blog-editor-content ol {
            margin: 0 0 16px;
            padding-inline-start: 1.4rem;
        }

        .blog-editor-content ul {
            list-style: disc;
        }

        .blog-editor-content ol {
            list-style: decimal;
        }

        .blog-editor-content li {
            display: list-item;
            margin-bottom: 8px;
        }

        .blog-editor-content li[data-list="bullet"] {
            list-style-type: disc;
        }

        .blog-editor-content li[data-list="ordered"] {
            list-style-type: decimal;
        }

        .blog-editor-content .ql-ui {
            display: none;
        }

        .blog-editor-content .ql-align-center {
            text-align: center;
        }

        .blog-editor-content .ql-align-right {
            text-align: right;
        }

        .blog-editor-content .ql-align-justify {
            text-align: justify;
        }

        .blog-editor-content .ql-direction-rtl {
            direction: rtl;
            text-align: inherit;
        }

        .blog-editor-content .ql-indent-1 {
            padding-inline-start: 3em;
        }

        .blog-editor-content .ql-indent-2 {
            padding-inline-start: 6em;
        }

        .blog-editor-content .ql-indent-3 {
            padding-inline-start: 9em;
        }

        .blog-editor-content blockquote {
            margin: 0 0 16px;
            padding: 12px 16px;
            border-inline-start: 4px solid #127384;
            background: #f7fbfc;
            color: #334155;
        }

        .blog-editor-content a {
            color: #127384;
            text-decoration: underline;
        }

        .blog-editor-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
        }

        .blog-editor-content table {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: collapse;
        }

        .blog-editor-content table th,
        .blog-editor-content table td {
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }
    </style>
@endpush

@section('content')
    @php
        use App\Support\CmsHtmlLinks;
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
        $blogContent = CmsHtmlLinks::redirectBrokenInternalLinks($blogDetails['content'] ?? '');
    @endphp

    <div class="breadcrumb-bar">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title">{{ $blogTitle }}</h2>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('website.nav.home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('website.blogs.index') }}">{{ __('website.blog.page_title') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($blogTitle, 55) }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="blogbanner" style="background-image: url('{{ $blogImage }}'); background-size: cover; background-position: center;">
        <div class="blogbanner-content">
            @if(filled($blogDetails['slug'] ?? null))
                <span class="blog-hint">{{ Str::headline($blogDetails['slug']) }}</span>
            @endif
            <h1>{{ $blogTitle }}</h1>
            @if(filled($blogDetails['published_on'] ?? null))
                <ul class="entry-meta meta-item justify-content-center">
                    <li class="date-icon"><i class="fa-solid fa-calendar-days"></i> {{ $blogDetails['published_on'] }}</li>
                </ul>
            @endif
        </div>
    </div>

    <div class="blog-section">
        <div class="container">
            @if(filled($blogDetails['description'] ?? null))
                <div class="blog-description">
                    <p>{{ $blogDetails['description'] }}</p>
                </div>
            @endif

            @if(filled($blogContent))
                <div class="blog-description blog-editor-content">
                    {!! $blogContent !!}
                </div>
            @endif

            @if($previousPost || $nextPost)
                <div class="blogdetails-pagination">
                    <ul>
                        @if($previousPost)
                            @php
                                $previousUrl = data_get($previousPost, 'details_url', data_get($previousPost, 'url', route('website.blogs.index')));
                            @endphp
                            <li>
                                <a href="{{ $previousUrl }}" class="prev-link"><i class="fas fa-regular fa-arrow-left"></i> {{ __('website.blog.navigation.previous') }}</a>
                                <a href="{{ $previousUrl }}"><h3>{{ Str::limit($previousPost['title'], 70) }}</h3></a>
                            </li>
                        @endif

                        @if($nextPost)
                            @php
                                $nextUrl = data_get($nextPost, 'details_url', data_get($nextPost, 'url', route('website.blogs.index')));
                            @endphp
                            <li>
                                <a href="{{ $nextUrl }}" class="next-link">{{ __('website.blog.navigation.next') }} <i class="fas fa-regular fa-arrow-right"></i></a>
                                <a href="{{ $nextUrl }}"><h3>{{ Str::limit($nextPost['title'], 70) }}</h3></a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            @if($relatedCars->isNotEmpty())
                <section class="car-section pt-4">
                    <div class="section-heading heading-four" data-aos="fade-down">
                        <h2>{{ __('website.blog.related_cars_title') }}</h2>
                    </div>

                    <div class="row row-gap-4">
                        @foreach($relatedCars as $car)
                            @php
                                $carTitle = $car['name'] ?? __('website.common.car');
                                $carUrl = $car['details_url'] ?? route('website.cars.index');
                                $carImage = $storageUrl($car['image_path'] ?? null, $assetUrl('img/cars/car-01.jpg'));
                            @endphp
                            <div class="col-lg-3 col-md-6 d-flex">
                                <div class="listing-item flex-fill">
                                    <div class="listing-img">
                                        <a href="{{ $carUrl }}">
                                            <img src="{{ $carImage }}" class="img-fluid" alt="{{ $carTitle }}">
                                        </a>
                                    </div>
                                    <div class="listing-content">
                                        <h3 class="listing-title mb-1"><a href="{{ $carUrl }}">{{ Str::limit($carTitle, 38) }}</a></h3>
                                        @if(filled($car['brand_name'] ?? null))
                                            <p class="mb-1 text-muted">{{ $car['brand_name'] }}</p>
                                        @endif
                                        <div class="d-flex flex-wrap gap-2 text-muted small">
                                            @if(filled($car['category_name'] ?? null))
                                                <span>{{ $car['category_name'] }}</span>
                                            @endif
                                            @if(filled($car['year'] ?? null))
                                                <span>{{ $car['year'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($relatedBlogs->isNotEmpty())
                <section class="blog-section-four pt-4">
                    <div class="section-heading heading-four" data-aos="fade-down">
                        <h2>{{ __('website.blog.related_posts_title') }}</h2>
                    </div>

                    <div class="row row-gap-3 justify-content-center">
                        @foreach($relatedBlogs as $relatedBlog)
                            @php
                                $relatedTitle = $relatedBlog['title'] ?? __('website.blog.common.untitled');
                                $relatedUrl = $relatedBlog['details_url'] ?? route('website.blogs.index');
                                $relatedImage = $storageUrl($relatedBlog['image_path'] ?? null, $assetUrl('img/blog/blog-11.jpg'));
                            @endphp
                            <div class="col-lg-4 col-md-6 d-flex">
                                <div class="blog-item flex-fill">
                                    <div class="blog-img">
                                        <a href="{{ $relatedUrl }}">
                                            <img src="{{ $relatedImage }}" class="img-fluid" alt="{{ $relatedTitle }}">
                                        </a>
                                    </div>
                                    <div class="blog-content">
                                        <h5 class="title">
                                            <a href="{{ $relatedUrl }}">{{ Str::limit($relatedTitle, 65) }}</a>
                                        </h5>
                                        @if(filled($relatedBlog['excerpt'] ?? null))
                                            <p>{{ $relatedBlog['excerpt'] }}</p>
                                        @endif
                                        @if(filled($relatedBlog['published_on'] ?? null))
                                            <p class="date d-inline-flex align-center mb-0">
                                                <i class="bx bx-calendar me-1"></i>{{ $relatedBlog['published_on'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
