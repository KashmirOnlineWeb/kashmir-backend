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
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\ShoppingPlaceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\BackgroundHistoryController;
use App\Http\Controllers\Admin\GeneralInformationController;
use App\Http\Controllers\Admin\HowToReachController;
use App\Http\Controllers\Admin\SafetyInformationController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\ThingsToDoController;
use App\Http\Controllers\Admin\ReligiousPlaceController;
use App\Http\Controllers\Admin\SightSeeingController;
use App\Http\Controllers\Admin\ThingsToBeNotedController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\MenusController;

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

    /* Hospital */
    Route::resource('/hospital', HospitalController::class)->names('hospital');

    /* Pharmacy */
    Route::resource('/pharmacy', PharmacyController::class)->names('pharmacy');

    /* Collage and school */
    Route::resource('/collageandschool', CollageAndSchoolController::class)->names('collageandschool');

    /* Restaurant */
    Route::resource('/restaurant', RestaurantController::class)->names('restaurant');

    /* Shopping Place */
    Route::resource('/shoppingplace', ShoppingPlaceController::class)->names('shoppingplace');

    /* Background History */
    Route::resource('/backgroundhistory', BackgroundHistoryController::class)->names('backgroundhistory');

    /* General Information */
    Route::resource('/generalinformation', GeneralInformationController::class)->names('generalinformation');

    /* Safety Information */
    Route::resource('/safetyinformation', SafetyInformationController::class)->names('safetyinformation');

    /* How to reach */
    Route::resource('/howtoreach', HowToReachController::class)->names('howtoreach');

    /* Location */
    Route::resource('/location', LocationController::class)->names('location');

    /* Things to do */
    Route::resource('/thingstodo', ThingsToDoController::class)->names('thingstodo');

    /* Religious Places */
    Route::resource('/religiousplace', ReligiousPlaceController::class)->names('religiousplace');

    /* Sight Seeing */
    Route::resource('/sightseeing', SightSeeingController::class)->names('sightseeing');

    /* Things to be noted */
    Route::resource('/thingstobenoted', ThingsToBeNotedController::class)->names('thingstobenoted');

    /* Page */
    Route::resource('/page', PageController::class)->names('page');

    /* Menus */
    Route::resource('/menus', MenusController::class)->names('menus');

    /* Package */
    Route::resource('/package', PackageController::class)->names('package');
});

Route::prefix('api')->middleware(['auth'])->group(function () {
    /* Helper */
    Route::post('image', [HelperController::class, 'uploadImage'])->name('image');
});