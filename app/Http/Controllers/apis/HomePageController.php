<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdvertisementResource;
use App\Http\Resources\BrandResource;
use App\Http\Resources\HomeResource;
use App\Http\Resources\SeoResource;
use App\Http\Resources\ShortVideoResource;
use App\Models\About;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Home;
use App\Models\HomeTranslation;
use App\Models\Service;
use App\Models\Short_video;
use App\Traits\DBTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomePageController extends Controller
{

    use DBTrait;
    public function index(Request $request){
        $language = $request->header('Accept-Language') ?? 'en';
        // $brands = $this->getBrandsList($language);
        $onlyOnAfandina = $this->getCars($language,'only_on_afandina',20);
        $specialOffers = $this->getCars($language,'is_flash_sale',20);
        $homeData = $this->getHome($language);
        $advertisements = $this->getAdvertisements($language);
        $contactData = Contact::first();
        $services = $this->getServicesList($language);
        $documents = $this->getDocumentsList($language);
        $locations = $this->getLocationsList($language);
        $shortVideos = Short_video::get();

        $data = [
            'homeData' => $homeData,
            'contactData' => $contactData,
            'onlyOnAfandina' => $onlyOnAfandina,
            'advertisements' => $advertisements,
            'specialOffers' => $specialOffers,
            'services' => $services,
            'locations' => $locations,
            'documents' => $documents,
            'shortVideos' => $shortVideos,
        ];

        return new HomeResource($data);
    }

    public function search(Request $request)
    {
        $language = $request->header('Accept-Language') ?? 'en';
        // Get the search term from the user input
        $searchTerm = $request->input('query');

        // Fetch categories matching the search term
        $categories = Category::join('category_translations', function ($join) use ($language) {
            $join->on('categories.id', '=', 'category_translations.category_id')
                ->where('category_translations.locale', '=', $language);
        })
            ->leftJoin('category_translations as category_translations_en', function ($join) {
                $join->on('categories.id', '=', 'category_translations_en.category_id')
                    ->where('category_translations_en.locale', '=', 'en');
            })
            ->leftJoin('category_translations as category_translations_ar', function ($join) {
                $join->on('categories.id', '=', 'category_translations_ar.category_id')
                    ->where('category_translations_ar.locale', '=', 'ar');
            })
            ->where(function ($query) use ($searchTerm) {
                $query->whereHas('translations', function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%");
                });
            })
            ->select(
                'categories.id',
                'categories.image_path',
                'categories.slug',
                'category_translations.name',
                'category_translations_en.name as name_en',
                'category_translations_ar.name as name_ar'
            )
            ->withCount('cars') // Count the number of cars for each brand
            ->limit(5) // Adjust the limit as needed
            ->get();

        // Fetch brands matching the search term
        $brands = Brand::join('brand_translations', function ($join) use ($language) {
            $join->on('brands.id', '=', 'brand_translations.brand_id')
                ->where('brand_translations.locale', '=', $language);
        })
            ->leftJoin('brand_translations as brand_translations_en', function ($join) {
                $join->on('brands.id', '=', 'brand_translations_en.brand_id')
                    ->where('brand_translations_en.locale', '=', 'en');
            })
            ->leftJoin('brand_translations as brand_translations_ar', function ($join) {
                $join->on('brands.id', '=', 'brand_translations_ar.brand_id')
                    ->where('brand_translations_ar.locale', '=', 'ar');
            })
            ->where(function ($query) use ($searchTerm) {
                $query->whereHas('translations', function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%");
                });
            })
            ->select(
                'brands.id',
                'brands.logo_path',
                'brands.slug',
                'brand_translations.name',
                'brand_translations_en.name as name_en',
                'brand_translations_ar.name as name_ar'
            )
            ->withCount('cars') // Count the number of cars for each brand
            ->limit(5) // Adjust the limit as needed
            ->get();

        // Fetch cars matching the search term
        $cars = Car::join('car_translations', function ($join) use ($language) {
            $join->on('cars.id', '=', 'car_translations.car_id')
                ->where('car_translations.locale', '=', $language);
        })
            ->leftJoin('car_translations as car_translations_en', function ($join) {
                $join->on('cars.id', '=', 'car_translations_en.car_id')
                    ->where('car_translations_en.locale', '=', 'en');
            })
            ->leftJoin('car_translations as car_translations_ar', function ($join) {
                $join->on('cars.id', '=', 'car_translations_ar.car_id')
                    ->where('car_translations_ar.locale', '=', 'ar');
            })
            ->where(function ($query) use ($searchTerm) {
                $query->whereHas('translations', function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%");
                });
            })
            ->select(
                'cars.id',
                'cars.default_image_path',
                'cars.slug',
                'car_translations.name',
                DB::raw('COALESCE(car_translations_en.name, car_translations.name) as name_en'),
                DB::raw('COALESCE(car_translations_ar.name, car_translations.name) as name_ar')
            )
            ->limit(5) // Adjust the limit as needed
            ->get();

        // Combine results and return as JSON response
        return response()->json([
            "data"=>[
                'categories' => $categories,
                'brands' => $brands,
                'cars' => $cars
            ],
            'status' =>'success'
        ]);
    }

    public function SEO(){
        $home = Home::first();
        $aboutAs = About::first();
        return [
            'home'=>new SeoResource($home),
            'about_us'=>new SeoResource($aboutAs),
        ];
    }

}
