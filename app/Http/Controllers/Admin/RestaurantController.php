<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
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
                            //'status'          => 'required|integer|digits_between:0,1',
                            'restaurant_type' => 'sometimes|string',
                            'address'         => 'sometimes|string',
                            'contact'         => 'sometimes|digits:10',
                            'google_map'      => 'sometimes|string',
                            'slug'            => 'sometimes|string',
                            'image'           => 'sometimes|string|nullable',
                            'image_alt'       => 'sometimes|string|nullable',
                            'city_id'         => 'required|integer|exists:cities,id',
                            /*'meta_title'      => 'required|string|nullable',
                            'meta_description'=> 'required|string|nullable',
                            'keywords'        => 'required|string|nullable'*/
                        ]);

            /* Insert Meta */
            /*if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::create([
                            'meta_title'       => $data['meta_title'],
                            'meta_description' => $data['meta_description'],
                            'keywords'         => $data['keywords'],
                            'status'           => 1,
                        ]);
            }*/

            $response = Restaurant::create([
                                        'name'            => $data['name'],
                                        //'status'          => $data['status'],
                                        'description'     => $data['description'],
                                        'restaurant_type' => $data['restaurant_type'],
                                        'address'         => $data['address'],
                                        'contact'         => $data['contact'],
                                        'google_map'      => $data['google_map'],
                                        'slug'            => $data['slug'],
                                        'image'           => $data['image'],
                                        'image_alt'       => $data['image_alt'],
                                        //'meta_id'       => $meta->id,
                                        'city_id'         => $data['city_id'],
                                    ]);

            return Redirect::route('restaurant.index',$response->id)->with('success', 'Restaurant created successfully');
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in restaurant store.');
        }
    }

    public function edit(Request $request, $id)
    {
        $cities      = City::select('id','name')->get();
        $restaurant  = Restaurant::findOrFail($id);
        $meta   = [];
        /*if(!empty($restaurant->meta_id)){
            $meta   = Meta::findOrFail($restaurant->meta_id);    
        }*/
        
        return view('Restaurant.edit',compact('cities', 'restaurant', 'meta'));
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            $request->merge(['restaurant_id' => $id]);
            
            $request->validate([
                            'restaurant_id'   => 'required|integer|exists:restaurants,id',
                            'name'            => 'required|string',
                            //'status'          => 'required|integer|digits_between:0,1',
                            'restaurant_type' => 'sometimes|string',
                            'address'         => 'sometimes|string',
                            'contact'         => 'sometimes|digits:10',
                            'google_map'      => 'sometimes|string',
                            'slug'            => 'sometimes|string',
                            'image'           => 'sometimes|string|nullable',
                            'image_alt'       => 'sometimes|string|nullable',
                            'city_id'         => 'required|integer|exists:cities,id',
                            /*'meta_title'      => 'required|string|nullable',
                            'meta_description'=> 'required|string|nullable',
                            'keywords'        => 'required|string|nullable'*/
                        ]);

            $restaurant = Restaurant::find($id);

            /* Insert Meta */
            /*if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$restaurant->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }*/
            
            $response = Restaurant::where('id', $restaurant->id)
                                ->update([
                                        'name'            => $data['name'],
                                        //'status'          => $data['status'],
                                        'description'     => $data['description'],
                                        'restaurant_type' => $data['restaurant_type'],
                                        'address'         => $data['address'],
                                        'contact'         => $data['contact'],
                                        'google_map'      => $data['google_map'],
                                        'slug'            => $data['slug'],
                                        'image'           => $data['image'],
                                        'image_alt'       => $data['image_alt'],
                                        //'meta_id'       => $restaurant->meta_id,
                                        'city_id'         => $data['city_id'],
                                    ]);


            return Redirect::route('restaurant.index',$response)->with('success', 'Restaurant updated successfully');
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
            /*if(!empty($restaurant->meta_id)){
                Meta::destroy($restaurant->meta_id);
            }*/
            $response = $restaurant->destroy($id);
            return Redirect::route('restaurant.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in restaurant destroy.');
        }
    }
}
