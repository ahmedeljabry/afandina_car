<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Blog;
use App\Models\Car;
use App\Models\Category;
use App\Models\ContactWithUsMessage;
use App\Traits\DeduplicatesCars;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    use DeduplicatesCars;

    public function index()
    {
        $now = now();
        $thisMonthStart = $now->copy()->startOfMonth();
        $lastMonthStart = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonthNoOverflow()->endOfMonth();
        $representativeCarIds = $this->uniqueRepresentativeCarIds(Car::query());
        $representativeCarIdList = $representativeCarIds->all();

        $totalCars = count($representativeCarIdList);
        $activeCars = $this->uniqueCarCount(Car::query()->where('is_active', true));
        $availableCars = $this->uniqueCarCount(Car::query()->where('status', 'available'));
        $featuredCars = $this->uniqueCarCount(Car::query()->where('is_featured', true));

        $totalBrands = Brand::count();
        $activeBrands = Brand::where('is_active', true)->count();

        $totalCategories = Category::count();
        $activeCategories = Category::where('is_active', true)->count();

        $totalBlogs = Blog::count();
        $activeBlogs = Blog::where('is_active', true)->count();

        $carsThisMonth = Car::query()
            ->whereIn('id', $representativeCarIdList)
            ->where('created_at', '>=', $thisMonthStart)
            ->count();
        $carsLastMonth = Car::query()
            ->whereIn('id', $representativeCarIdList)
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();
        $carsGrowth = $carsLastMonth > 0
            ? (($carsThisMonth - $carsLastMonth) / $carsLastMonth) * 100
            : ($carsThisMonth > 0 ? 100 : 0);

        $latestCars = Car::with(['translations', 'brand.translations', 'category.translations'])
            ->whereIn('id', $representativeCarIdList)
            ->latest('id')
            ->take(8)
            ->get();

        $recentBlogs = Blog::with('translations')
            ->latest('id')
            ->take(6)
            ->get();

        $latestMessages = ContactWithUsMessage::query()
            ->latest('id')
            ->take(6)
            ->get();

        $newMessagesThisWeek = ContactWithUsMessage::query()
            ->where('created_at', '>=', $now->copy()->subDays(7))
            ->count();

        $topCategories = Category::with('translations')
            ->get()
            ->map(function (Category $category) use ($representativeCarIdList) {
                $category->cars_count = Car::query()
                    ->whereIn('id', $representativeCarIdList)
                    ->where('category_id', $category->id)
                    ->count();

                return $category;
            })
            ->sortByDesc('cars_count')
            ->take(6)
            ->values();

        $topBrands = Brand::with('translations')
            ->get()
            ->map(function (Brand $brand) use ($representativeCarIdList) {
                $brand->cars_count = Car::query()
                    ->whereIn('id', $representativeCarIdList)
                    ->where('brand_id', $brand->id)
                    ->count();

                return $brand;
            })
            ->sortByDesc('cars_count')
            ->take(6)
            ->values();

        $fleetDistribution = collect([
            ['label' => 'Active Cars', 'count' => $activeCars, 'color' => '#0d9488'],
            ['label' => 'Available Cars', 'count' => $availableCars, 'color' => '#2563eb'],
            ['label' => 'Featured Cars', 'count' => $featuredCars, 'color' => '#f59e0b'],
            ['label' => 'Inactive Cars', 'count' => max($totalCars - $activeCars, 0), 'color' => '#ef4444'],
        ])->map(function (array $item) use ($totalCars): array {
            $item['percent'] = $totalCars > 0 ? round(($item['count'] / $totalCars) * 100, 1) : 0;

            return $item;
        });

        $monthlyCarsData = collect(range(5, 0))->map(function (int $offset) use ($representativeCarIdList): array {
            $date = now()->copy()->subMonths($offset);

            return [
                'label' => $date->format('M'),
                'count' => Car::query()
                    ->whereIn('id', $representativeCarIdList)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        });

        $quickActions = [
            ['label' => 'Add Car', 'icon' => 'ti ti-plus', 'url' => route('admin.cars.create')],
            ['label' => 'Manage Cars', 'icon' => 'ti ti-car', 'url' => route('admin.cars.index')],
            ['label' => 'New Blog', 'icon' => 'ti ti-pencil-plus', 'url' => route('admin.blogs.create')],
            ['label' => 'Messages', 'icon' => 'ti ti-mail', 'url' => route('admin.contact-messages.index')],
        ];

        $activities = $this->buildActivityFeed($latestCars, $recentBlogs, $latestMessages);

        return view('pages.admin.dashboard', compact(
            'totalCars',
            'activeCars',
            'availableCars',
            'featuredCars',
            'totalBrands',
            'activeBrands',
            'totalCategories',
            'activeCategories',
            'totalBlogs',
            'activeBlogs',
            'carsThisMonth',
            'carsLastMonth',
            'carsGrowth',
            'latestCars',
            'recentBlogs',
            'latestMessages',
            'newMessagesThisWeek',
            'topCategories',
            'topBrands',
            'fleetDistribution',
            'monthlyCarsData',
            'quickActions',
            'activities'
        ));
    }

    private function buildActivityFeed(Collection $latestCars, Collection $recentBlogs, Collection $latestMessages): Collection
    {
        $activities = collect();

        foreach ($latestCars->take(4) as $car) {
            $carName = optional($car->translations->first())->name ?? ('Car #' . $car->id);

            $activities->push([
                'icon' => 'ti ti-car',
                'title' => 'Car listing created',
                'meta' => $carName,
                'time' => $car->created_at,
                'url' => route('admin.cars.edit', $car->id),
            ]);
        }

        foreach ($recentBlogs->take(3) as $blog) {
            $blogTitle = optional($blog->translations->first())->title ?? ('Blog #' . $blog->id);

            $activities->push([
                'icon' => 'ti ti-article',
                'title' => 'Blog post published',
                'meta' => $blogTitle,
                'time' => $blog->created_at,
                'url' => route('admin.blogs.edit', $blog->id),
            ]);
        }

        foreach ($latestMessages->take(3) as $message) {
            $activities->push([
                'icon' => 'ti ti-message-circle',
                'title' => 'New contact message',
                'meta' => $message->full_name ?: 'Contact request',
                'time' => $message->created_at,
                'url' => route('admin.contact-messages.index'),
            ]);
        }

        return $activities
            ->sortByDesc('time')
            ->take(8)
            ->values();
    }
}
