<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\website\HomeController;
use App\Http\Controllers\admin\CarController as AdminCarController;
use App\Http\Controllers\website\AboutController as WebsiteAboutController;
use App\Http\Controllers\website\ContactController as WebsiteContactController;
use App\Http\Controllers\website\CarController as WebsiteCarController;
use App\Http\Controllers\website\BlogController as WebsiteBlogController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localize', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
    ],
    function (): void {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/about-us', [WebsiteAboutController::class, 'index'])->name('website.about.index');
        Route::get('/contact-us', [WebsiteContactController::class, 'index'])->name('website.contact.index');
        Route::post('/contact-us', [WebsiteContactController::class, 'store'])->name('website.contact.store');
        Route::get('/cars', [WebsiteCarController::class, 'index'])->name('website.cars.index');
        Route::get('/cars', [WebsiteCarController::class, 'show'])->name('website.cars.show');
        Route::get('/blogs', [WebsiteBlogController::class, 'index'])->name('website.blogs.index');
        Route::get('/blogs/{blog}', [WebsiteBlogController::class, 'show'])->name('website.blogs.show');
    }
);

Route::get('/cars/images/check-status/{carId}', [AdminCarController::class, 'checkImageProcessingStatus']);

Route::post('/cars/{id}/upload-image', [AdminCarController::class, 'uploadImage'])->name('cars.upload-image');
Route::post('/cars/{id}/upload-default-image', [AdminCarController::class, 'uploadDefaultImage'])->name('cars.upload-default-image');


Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::delete('/cars/delete-image/{id}', [AdminCarController::class, 'deleteImage'])->name('cars.delete-image');
    // Car image management routes
    Route::post('/cars/delete-selected-images', [AdminCarController::class, 'deleteMultipleImages'])->name('cars.delete-selected-images');
    Route::delete('/cars/delete-all-images/{carId}', [AdminCarController::class, 'deleteAllImages'])->name('cars.delete-all-images');
    Route::post('/cars/delete-selected-images', [AdminCarController::class, 'deleteSelectedImages'])
        ->name('admin.cars.delete-selected-images');

    Route::post('/cars/delete-all-images/{car}', [AdminCarController::class, 'deleteAllImages'])
        ->name('admin.cars.delete-all-images');
});
