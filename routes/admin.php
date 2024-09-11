<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\MetaController;

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
});