<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\MetaController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\HospitalController;
use App\Http\Controllers\Admin\PharmacyController;
use App\Http\Controllers\Admin\CollageAndSchoolController;
use App\Http\Controllers\Admin\HelperController;

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    /* Category */
    Route::resource('/category', CategoryController::class)->names('category');

    /* Meta */
    Route::resource('/meta', MetaController::class)->names('meta');

    /* City */
    Route::resource('/city', CityController::class)->names('city');

    /* Destination */
    Route::resource('/destination', DestinationController::class)->names('destination');

    /* Hotel */
    Route::resource('/hotel', HotelController::class)->names('hotel');

    /* Destination */
    Route::resource('/hospital', HospitalController::class)->names('hospital');

    /* Destination */
    Route::resource('/pharmacy', PharmacyController::class)->names('pharmacy');

    /* Destination */
    Route::resource('/collageandschool', CollageAndSchoolController::class)->names('collageandschool');

    //Route::post('books/store', [HelperController::class, 'store'])->name('books.store');
});

Route::prefix('api')->middleware(['auth'])->group(function () {
    /* Helper */
    Route::post('image', [HelperController::class, 'uploadImage'])->name('image');
});