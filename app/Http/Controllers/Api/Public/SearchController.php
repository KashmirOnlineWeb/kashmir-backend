<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\ApiResponse;
use Illuminate\Support\Facades\DB;

use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\Hospital;
use App\Models\Package;
use App\Models\Pharmacy;
use App\Models\ThingsToDo;

class SearchController extends Controller
{
    /**
     * Website main search used for name of hotels,destinations,packages,restaurants,pharmacies,things to do,hospitals.
     * */
    public function mainSearch(Request $request, $keyword = NULL)
    {
        try {
            $request->merge(['keyword' => $keyword]);
            $data = $request->validate(['keyword' => ['required']]);

            /* Request result as per model type */
            $hotels       = Hotel::select('id','name','city_id','slug', DB::raw('"hotel" as type'))
                                ->where('name', 'like', '%' . $keyword . '%');

            $restaurants  = Restaurant::select('id','name','city_id','slug', DB::raw('"restaurant" as type'))
                                ->where('name', 'like', '%' . $keyword . '%')
                                ->union($hotels);

            $hospitals    = Hospital::select('id','name','city_id','slug', DB::raw('"hospital" as type'))
                                ->where('name', 'like', '%' . $keyword . '%')
                                ->union($restaurants);

            $thingsToDo   = ThingsToDo::select('id','title','city_id', DB::raw('"" as slug'), DB::raw('"thingsToDo" as type'))
                                ->where('title', 'like', '%' . $keyword . '%')
                                ->union($hospitals);
            
            $pharmacies   = Pharmacy::select('id','name','city_id','slug', DB::raw('"pharmacy" as type'))
                                ->where('name', 'like', '%' . $keyword . '%')
                                ->union($thingsToDo);

            $packages     = Package::select('id','name','city_id','slug', DB::raw('"package" as type'))
                                ->where('name', 'like', '%' . $keyword . '%')
                                ->union($pharmacies);                                

            $destinations = Destination::select('id','name','city_id','slug', DB::raw('"destination" as type'))
                                ->where('name', 'like', '%' . $keyword . '%')
                                ->union($packages)->with('city:id,name,slug')
                                ->take(5)
                                ->get();
 
            $records      = $destinations;
            
            return ApiResponse::send(200, null, $records);
        } catch (Exception $e) {
            return ApiResponse::send(400, 'Something went wrong.'); 
        }
    }
}
