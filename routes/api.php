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
use App\Models\BackgroundHistory;
use App\Models\GeneralInformation;
use App\Models\HowToReach;
use App\Models\Location;
use App\Models\SafetyInformation;
use App\Models\City;
use App\Models\ReligiousPlace;
use App\Models\SightSeeing;
use App\Models\ThingsToBeNoted;
use App\Models\ThingsToDo;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/* Destinations nav */
Route::get('/destinations', function (Request $request) {
    $destinations = Destination::select(['id','name','slug','image','image_alt','destination_type'])
                                ->get();
    
    return response()->json($destinations);
});

/* Destinations */
Route::get('/destination/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $destination = Destination::where('slug', $slug)
                                ->with(['city:id,name,slug,time_to_visit','meta:id,meta_title,meta_description,keywords'])
                                ->first();
    
    return response()->json($destination);
});

/* Collages and Schools */
Route::get('/collage/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $city  = City::where('slug',$slug)->select('id','slug')->first();

    $collages = CollageAndSchool::where('city_id', $city->id)
                                ->select(['id','name','slug','address','board','description','image','image_alt','website_url'])
                                ->get();
    $other_data = [
                    'name'              => '',
                    'title'             => '',
                    'meta_title'        => '',
                    'meta_description'  => '',
                    'meta_keywords'     => '', 
                ];
    
    return response()->json(['collages' => $collages, 'other_data' => $other_data]);
});

/* Hospitals */
Route::get('/hospital/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $city  = City::where('slug',$slug)->select('id','slug')->first();
    $hospitals = Hospital::where('city_id', $city->id)
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
                    'title' => '',
                    'meta_title'        => '',
                    'meta_description'  => '',
                    'meta_keywords'     => '', 
                ];
    
    return response()->json(['hospitals' => $hospitals, 'other_data' => $other_data]);
});

/* Hotels */
Route::get('/hotel/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $city  = City::where('slug',$slug)->select('id','slug')->first();
    $hotels = Hotel::where('city_id', $city->id)
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
                    'title' => '',
                    'meta_title'        => '',
                    'meta_description'  => '',
                    'meta_keywords'     => '', 
                ];
    
    return response()->json(['hotels' => $hotels, 'other_data' => $other_data]);
});

/* Pharmacy */
Route::get('/pharmacy/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $city  = City::where('slug',$slug)->select('id','slug')->first();
    $pharmacies = Pharmacy::where('city_id', $city->id)
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
                    'title' => '',
                    'meta_title'        => '',
                    'meta_description'  => '',
                    'meta_keywords'     => '', 
                ];
    
    return response()->json(['pharmacies' => $pharmacies, 'other_data' => $other_data]);
});

/* Restaurant */
Route::get('/restaurant/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $city  = City::where('slug',$slug)->select('id','slug')->first();
    $restaurants = Restaurant::where('city_id', $city->id)
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
                    'title' => '',
                    'meta_title'        => '',
                    'meta_description'  => '',
                    'meta_keywords'     => '', 
                ];
    
    return response()->json(['restaurants' => $restaurants, 'other_data' => $other_data]);
});

/* Shopping places */
Route::get('/shoppingplace/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $city  = City::where('slug',$slug)->select('id','slug')->first();
    $shopping_places = ShoppingPlace::where('city_id', $city->id)
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

/* Background history */
Route::get('/backgroundhistory/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $city  = City::where('slug',$slug)->select('id','slug')->first();
    $background_history = BackgroundHistory::where('city_id', $city->id)
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

    return response()->json(['background_history' => $background_history]);
});

/* General information */
Route::get('/generalinformation/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $city  = City::where('slug',$slug)->select('id','slug')->first();
    $general_information = GeneralInformation::where('city_id', $city->id)
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

    return response()->json(['general_information' => $general_information]);
});

/* How to reach */
Route::get('/howtoreach/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $city  = City::where('slug',$slug)->select('id','slug')->first();
    $howtoreach = HowToReach::where('city_id', $city->id)
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

    return response()->json(['howtoreach' => $howtoreach]);
});

/* Location */
Route::get('/location/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $city  = City::where('slug',$slug)->select('id','slug')->first();
    $Locations = Location::where('city_id', $city->id)
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

    return response()->json(['Locations' => $Locations]);
});

/* Safety information */
Route::get('/safetyinformation/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $city  = City::where('slug',$slug)->select('id','slug')->first();
    $safetyinformation = SafetyInformation::where('city_id', $city->id)
                                ->with(['city:id,name,slug,time_to_visit','meta:id,meta_title,meta_description,keywords'])
                                ->select(['id',
                                          'name',
                                          'title',
                                          'city_id',
                                          'meta_id',
                                          'image',
                                          'image_alt',
                                          'description',
                                          'repeater_content',])
                                ->first();

    return response()->json(['safetyinformation' => $safetyinformation]);
});

/* Religious places */
Route::get('/religiousplace/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $city  = City::where('slug',$slug)->select('id','slug')->first();
    $religiousplace = ReligiousPlace::where('city_id', $city->id)
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

    return response()->json(['religiousplace' => $religiousplace]);
});

/* Sight seeings */
Route::get('/sightseeing/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $city  = City::where('slug',$slug)->select('id','slug')->first();
    $sightseeing = SightSeeing::where('city_id', $city->id)
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

    return response()->json(['sightseeing' => $sightseeing]);
});

/* Things To Be Noted */
Route::get('/thingstobenoted/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $city  = City::where('slug',$slug)->select('id','slug')->first();
    $thingstobenoted = ThingsToBeNoted::where('city_id', $city->id)
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

    return response()->json(['thingstobenoted' => $thingstobenoted]);
});

/* Things to do */
Route::get('/thingstodo/{slug}', function (Request $request, $slug) {
    $slug = strtolower($slug); // Convert slug to lowercase
    $city  = City::where('slug',$slug)->select('id','slug')->first();
    $thingstodo = ThingsToDo::where('city_id', $city->id)
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

    return response()->json(['thingstodo' => $thingstodo]);
});