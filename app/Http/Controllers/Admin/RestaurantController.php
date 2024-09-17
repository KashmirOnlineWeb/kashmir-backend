<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\City;
use App\Models\Meta;

class RestaurantController extends Controller
{
    public function index()
    {
        try {
            $restaurants = Restaurant::orderBy('id','desc')->paginate(12);
            return view('Restaurant.index')->with(['restaurants' => $restaurants]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in restaurant index.');
        }
    }

    public function create()
    {
        $cities = City::select('id','name')->get();
        return view('Restaurant.edit',compact('cities'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $request->validate([
                            'name'            => 'required|string',
                            'status'          => 'required|integer|digits_between:0,1',
                            'image'           => 'sometimes|string',
                            'image_alt'       => 'sometimes|string',
                            'city_id'         => 'required|integer|exists:cities,id',
                            'meta_title'      => 'required|string|nullable',
                            'meta_description'=> 'required|string|nullable',
                            'keywords'        => 'required|string|nullable'
                        ]);

            /* Insert Meta */
            if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::create([
                            'meta_title'       => $data['meta_title'],
                            'meta_description' => $data['meta_description'],
                            'keywords'         => $data['keywords'],
                            'status'           => 1,
                        ]);
            }

            $response = Restaurant::create([
                                        'name'               => $data['name'],
                                        'status'             => $data['status'],
                                        'image'              => $data['image'],
                                        'image_alt'          => $data['image_alt'],
                                        'restaurant_content' => $data['restaurant_content'],
                                        'meta_id'            => $meta->id,
                                        'city_id'            => $data['city_id'],
                                    ]);

            return Redirect::route('Restaurant.index',$response->id)->with('success', 'Restaurant created successfully');
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in restaurant store.');
        }
    }

    public function edit(Restaurant $restaurant)
    {
        $cities      = City::select('id','name')->get();
        $restaurant  = Restaurant::findOrFail($id);
        $meta   = [];
        if(!empty($restaurant->meta_id)){
            $meta   = Meta::findOrFail($restaurant->meta_id);    
        }
        
        return view('Restaurant.edit',compact('cities', 'restaurant', 'meta'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        try {
            $data = $request->all();
            $request->merge(['restaurant_id' => $id]);
            
            $request->validate([
                            'restaurant_id'   => 'required|integer|exists:restaurants,id',
                            'name'            => 'required|string',
                            'status'          => 'required|integer|digits_between:0,1',
                            'image'           => 'sometimes|string',
                            'image_alt'       => 'sometimes|string',
                            'city_id'         => 'required|integer|exists:cities,id',
                            'meta_title'      => 'required|string|nullable',
                            'meta_description'=> 'required|string|nullable',
                            'keywords'        => 'required|string|nullable'
                        ]);

            $restaurant = Restaurant::find($id);

            /* Insert Meta */
            if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$restaurant->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }
            
            $response = Restaurant::where('id', $restaurant->id)
                                ->update([
                                        'name'               => $data['name'],
                                        'status'             => $data['status'],
                                        'image'              => $data['image'],
                                        'image_alt'          => $data['image_alt'],
                                        'restaurant_content' => $data['restaurant_content'],
                                        'meta_id'            => $restaurant->meta_id,
                                        'city_id'            => $data['city_id'],
                                    ]);


            return Redirect::route('Restaurant.index',$response)->with('success', 'Restaurant updated successfully');
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in restaurant update.');
        }
    }

    /**
     * Delete the restaurant.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['restaurant_id' => $id]);
                
            $request->validate(['restaurant_id' => 'required|integer|exists:restaurants,id']);
            $restaurant = Restaurant::find($id);
            if(!empty($restaurant->meta_id)){
                Meta::destroy($restaurant->meta_id);
            }
            $response = $restaurant->destroy($id);
            return Redirect::route('Restaurant.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in restaurant destroy.');
        }
    }
}
