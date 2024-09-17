<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\ShoppingPlace;
use App\Models\City;
use App\Models\Meta;

class ShoppingPlaceController extends Controller
{
    /**
     * Display list of shoppingPlaces
     */
    public function index()
    {
        try {
            $shoppingPlaces = ShoppingPlace::orderBy('id','desc')->paginate(12);
            return view('Shoppingplace.index')->with(['shoppingPlaces' => $shoppingPlaces]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in ShoppingPlace index.');
        }
    }

    /**
     * Create shoppingPlace form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('Shoppingplace.edit',compact('cities'));
    }

    /**
     * Store shopping form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            
            $request->validate([
                            'name'            => 'required|string',
                            'title'           => 'required|string',
                            'image'           => 'sometimes|string',
                            'image_alt'       => 'sometimes|string',
                            'repeater_content'=> 'sometimes|array',
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
            
            $response = ShoppingPlace::create([
                                        'name'            => $data['name'],
                                        'title'           => $data['title'],
                                        'image'           => $data['image'],
                                        'image_alt'       => $data['image_alt'],
                                        'repeater_content'=> $data['repeater_content'],
                                        'city_id'         => $data['city_id'],
                                        'meta_id'         => $meta->id,
                                    ]);


            return Redirect::route('Shoppingplace.index',$response->id)->with('success', 'Shopping place created successfully');
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in ShoppingPlace store.');
        }
    }

    /**
     * Display the shoppingPlace form.
     */
    public function edit(Request $request, $id): View
    {
        $cities = City::select('id','name')->get();
        $shoppingPlace  = ShoppingPlace::findOrFail($id);
        $meta   = [];
        if(!empty($shoppingPlace->meta_id)){
            $meta = Meta::findOrFail($shoppingPlace->meta_id);    
        }
        
        return view('Shoppingplace.edit',compact('cities', 'shoppingPlace', 'meta'));
    }

    /**
     * Update the shoppingPlace information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['place_id' => $id]);
            
            $request->validate([
                            'place_id'        => 'required|integer|exists:shopping_places,id',
                            'name'            => 'required|string',
                            'title'           => 'required|string',
                            'image'           => 'sometimes|string',
                            'image_alt'       => 'sometimes|string',
                            'repeater_content'=> 'sometimes|array',
                            'city_id'         => 'required|integer|exists:cities,id',
                            'meta_title'      => 'required|string|nullable',
                            'meta_description'=> 'required|string|nullable',
                            'keywords'        => 'required|string|nullable'
                        ]);

            $shoppingPlace = ShoppingPlace::find($id);

            /* Insert Meta */
            if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$shoppingPlace->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }
            
            $response = ShoppingPlace::where('id', $shoppingPlace->id)
                                ->update([
                                        'name'            => $data['name'],
                                        'title'           => $data['title'],
                                        'image'           => $data['image'],
                                        'image_alt'       => $data['image_alt'],
                                        'repeater_content'=> $data['repeater_content'],
                                        'city_id'         => $data['city_id'],
                                        'meta_id'         => $shoppingPlace->meta_id,
                                    ]);


            return Redirect::route('Shoppingplace.index',$response)->with('success', 'Shopping place updated successfully');
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in ShoppingPlace update.');
        }
    }

    /**
     * Delete the shoppingPlace.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['place_id' => $id]);
            $request->validate(['place_id' => 'required|integer|exists:shopping_places,id']);       

            $shoppingPlace = ShoppingPlace::find($id);
            if(!empty($shoppingPlace->meta_id)){
                Meta::destroy($shoppingPlace->meta_id);
            }

            $response = $shoppingPlace->destroy($id);
            return Redirect::route('Shoppingplace.index',$response)->with('success', 'Shopping place deleted successfully');
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in ShoppingPlace destroy.');
        }
    }
}
