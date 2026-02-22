<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale() ?? 'en';

        $search = trim((string) $request->input('search', ''));
        $perPage = (int) $request->input('per_page', 6);
        if (!in_array($perPage, [6, 9, 12, 18], true)) {
            $perPage = 6;
        }

        $sort = (string) $request->input('sort', 'newest');

        $blogsQuery = Blog::query()
            ->with('translations')
            ->where('is_active', true);

        if (filled($search)) {
            $blogsQuery->whereHas('translations', function ($query) use ($search, $locale) {
                $query->where('locale', $locale)
                    ->where(function ($subQuery) use ($search) {
                        $subQuery
                            ->where('title', 'like', '%' . $search . '%')
                            ->orWhere('description', 'like', '%' . $search . '%')
                            ->orWhere('content', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($sort === 'oldest') {
            $blogsQuery->oldest('blogs.id');
        } else {
            $blogsQuery->latest('blogs.id');
        }

        $blogs = $blogsQuery
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (Blog $blog) => $this->mapBlogCardData($blog, $locale));

        $recentBlogs = Blog::query()
            ->with('translations')
            ->where('is_active', true)
            ->latest('id')
            ->limit(4)
            ->get()
            ->map(fn (Blog $blog) => $this->mapBlogCardData($blog, $locale));

        $filters = [
            'search' => $search,
            'per_page' => $perPage,
            'sort' => $sort,
        ];

        return view('website.blog', compact('blogs', 'recentBlogs', 'filters'));
    }

    public function show(string $blog)
    {
        $locale = app()->getLocale() ?? 'en';

        $blogQuery = Blog::query()
            ->with([
                'translations',
                'cars.translations',
                'cars.brand.translations',
                'cars.category.translations',
                'cars.year',
            ])
            ->where('is_active', true);

        $blogModel = (clone $blogQuery)->where('slug', $blog)->first();

        if (!$blogModel && ctype_digit($blog)) {
            $blogModel = (clone $blogQuery)->where('id', (int) $blog)->first();
        }

        abort_if(!$blogModel, 404);

        $translation = $this->translationFor($blogModel, $locale);

        $blogDetails = [
            'id' => $blogModel->id,
            'title' => $translation?->title ?? __('website.blog.common.untitled'),
            'description' => filled($translation?->description)
                ? trim(strip_tags((string) $translation->description))
                : null,
            'content' => $translation?->content,
            'slug' => $blogModel->slug,
            'image_path' => $blogModel->image_path,
            'published_on' => $blogModel->created_at?->translatedFormat('d M, Y'),
        ];

        $relatedBlogs = Blog::query()
            ->with('translations')
            ->where('is_active', true)
            ->where('id', '!=', $blogModel->id)
            ->latest('id')
            ->limit(3)
            ->get()
            ->map(fn (Blog $relatedBlog) => $this->mapBlogCardData($relatedBlog, $locale));

        $relatedCars = $blogModel->cars
            ->filter(fn (Car $car) => (bool) $car->is_active)
            ->take(4)
            ->map(fn (Car $car) => $this->mapRelatedCarData($car, $locale))
            ->values();

        $previousBlog = Blog::query()
            ->with('translations')
            ->where('is_active', true)
            ->where('id', '<', $blogModel->id)
            ->orderByDesc('id')
            ->first();

        $nextBlog = Blog::query()
            ->with('translations')
            ->where('is_active', true)
            ->where('id', '>', $blogModel->id)
            ->orderBy('id')
            ->first();

        $previousPost = $previousBlog ? $this->mapBlogNavigationData($previousBlog, $locale) : null;
        $nextPost = $nextBlog ? $this->mapBlogNavigationData($nextBlog, $locale) : null;

        return view('website.blog-details', compact('blogDetails', 'relatedBlogs', 'relatedCars', 'previousPost', 'nextPost'));
    }

    private function mapBlogCardData(Blog $blog, string $locale): array
    {
        $translation = $this->translationFor($blog, $locale);

        return [
            'id' => $blog->id,
            'title' => $translation?->title ?? __('website.blog.common.untitled'),
            'description' => $translation?->description,
            'excerpt' => $this->buildExcerpt($translation?->description, $translation?->content),
            'slug' => $blog->slug,
            'image_path' => $blog->image_path,
            'published_on' => $blog->created_at?->translatedFormat('d M, Y'),
            'details_url' => $this->blogUrl($blog),
        ];
    }

    private function mapBlogNavigationData(Blog $blog, string $locale): array
    {
        $translation = $this->translationFor($blog, $locale);

        return [
            'title' => $translation?->title ?? __('website.blog.common.untitled'),
            'url' => $this->blogUrl($blog),
        ];
    }

    private function mapRelatedCarData(Car $car, string $locale): array
    {
        $carTranslation = $this->translationFor($car, $locale);
        $brandTranslation = $this->translationFor($car->brand, $locale);
        $categoryTranslation = $this->translationFor($car->category, $locale);

        return [
            'id' => $car->id,
            'name' => $carTranslation?->name ?? __('website.common.car'),
            'brand_name' => $brandTranslation?->name,
            'category_name' => $categoryTranslation?->name,
            'year' => $car->year?->year,
            'image_path' => $car->default_image_path,
            'details_url' => route('website.cars.show', ['car' => $car->id]),
        ];
    }

    private function buildExcerpt(mixed $description, mixed $content, int $limit = 160): ?string
    {
        $source = filled($description) ? $description : $content;
        if (blank($source)) {
            return null;
        }

        $normalized = preg_replace('/\s+/u', ' ', trim(strip_tags((string) $source)));

        return Str::limit((string) $normalized, $limit);
    }

    private function blogUrl(Blog $blog): string
    {
        return route('website.blogs.show', ['blog' => $blog->slug ?: $blog->id]);
    }

    private function translationFor($model, string $locale): mixed
    {
        if (!$model || !isset($model->translations) || !($model->translations instanceof Collection)) {
            return null;
        }

        return $model->translations->firstWhere('locale', $locale) ?? $model->translations->first();
    }
}
