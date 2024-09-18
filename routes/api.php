<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Destination;
use App\Models\CollageAndSchool;
use App\Models\Meta;
use App\Models\Hospital;
use App\Models\Hotel;
use App\Models\Pharmacy;
use App\Models\Restaurant;
use App\Models\ShoppingPlace;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/* Destinations */
Route::get('/destination/{slug}', function (Request $request, $slug) {
    $destination = Destination::where('slug', $slug)
                                ->with(['city:id,name,slug,time_to_visit','meta:id,meta_title,meta_description,keywords'])
                                ->first();
    
    return response()->json($destination);
});

/* Collages and Schools */
Route::get('/collage/{city_id}', function (Request $request, $city_id) {
    $collages = CollageAndSchool::where('city_id', $city_id)
                                ->select(['id','name','slug','address','board','description','image','image_alt','website_url'])
                                ->get();
    $other_data = [
                    'name'  => '',
                    'title' => ''
                ];
    
    return response()->json(['collages' => $collages, 'other_data' => $other_data]);
});

/* Hospitals */
Route::get('/hospital/{city_id}', function (Request $request, $city_id) {
    $hospitals = Hospital::where('city_id', $city_id)
                                ->select(['id',
                                          'name',
                                          'slug',
                                          'address',
                                          'contact',
                                          'content',
                                          'description',
                                          'facilities',
                                          'google_map',
                                          'how_to_reach',
                                          'image',
                                          'image_alt',
                                          'introduction',
                                          'referral_system',
                                          'trauma_services',
                                          'website_url'])
                                ->get();
    $other_data = [
                    'name'  => '',
                    'title' => ''
                ];
    
    return response()->json(['hospitals' => $hospitals, 'other_data' => $other_data]);
});

/* Hotels */
Route::get('/hotel/{city_id}', function (Request $request, $city_id) {
    $hotels = Hotel::where('city_id', $city_id)
                                ->select(['id',
                                          'name',
                                          'slug',
                                          'amenities',
                                          'balcony',
                                          'breakfast',
                                          'contact',
                                          'content',
                                          'image',
                                          'image_alt',
                                          'location',
                                          'price',
                                          'star',
                                          'tax',
                                          'total_lobbys',
                                          'total_rooms',
                                          'total_washrooms',
                                          'highlights_content'])
                                ->get();
    $other_data = [
                    'name'  => '',
                    'title' => ''
                ];
    
    return response()->json(['hotels' => $hotels, 'other_data' => $other_data]);
});

/* Pharmacy */
Route::get('/pharmacy/{city_id}', function (Request $request, $city_id) {
    $pharmacies = Pharmacy::where('city_id', $city_id)
                                ->select(['id',
                                          'name',
                                          'slug',
                                          'google_map',
                                          'location',
                                          'working_hours',
                                          'contact',
                                          'image',
                                          'image_alt'])
                                ->get();
    $other_data = [
                    'name'  => '',
                    'title' => ''
                ];
    
    return response()->json(['pharmacies' => $pharmacies, 'other_data' => $other_data]);
});

/* Restaurant */
Route::get('/restaurant/{city_id}', function (Request $request, $city_id) {
    $restaurants = Restaurant::where('city_id', $city_id)
                                ->select(['id',
                                          'name',
                                          'description',
                                          'restaurant_type',
                                          'address',
                                          'contact',
                                          'google_map',
                                          'slug',
                                          'image',
                                          'image_alt'])
                                ->get();
    $other_data = [
                    'name'  => '',
                    'title' => ''
                ];
    
    return response()->json(['restaurants' => $restaurants, 'other_data' => $other_data]);
});

/* Shopping places */
Route::get('/shoppingplace/{city_id}', function (Request $request, $city_id) {
    $shopping_places = ShoppingPlace::where('city_id', $city_id)
                                ->with(['city:id,name,slug,time_to_visit','meta:id,meta_title,meta_description,keywords'])
                                ->select(['id',
                                          'name',
                                          'title',
                                          'city_id',
                                          'meta_id',
                                          'image',
                                          'image_alt',
                                          'repeater_content',])
                                ->first();

    return response()->json(['shopping_places' => $shopping_places]);
});