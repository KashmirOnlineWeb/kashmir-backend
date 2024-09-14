<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Hotel;
use App\Models\City;
use App\Models\Meta;

class HotelController extends Controller
{
    /**
     * Display list of hotels
     */
    public function index()
    {
        try {
            $hotels = Hotel::orderBy('id','desc')->paginate(12);
            return view('Hotel.index')->with(['hotels' => $hotels]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in hotel index.');
        }
    }

    /**
     * Create hotel form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('Hotel.edit',compact('cities'));
    }

    /**
     * Store hotel form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            
            $request->validate([
                            'name'            => 'required|string',
                            'slug'            => 'required|string',
                            'amenities'       => 'sometimes|array',
                            'balcony'         => 'sometimes|integer|digits_between:0,1',
                            'breakfast'       => 'sometimes|integer|digits_between:0,1',
                            'contact'         => 'sometimes|digits:10',
                            'image'           => 'sometimes|string',
                            'image_alt'       => 'sometimes|string',
                            'location'        => 'sometimes|string',
                            'price'           => 'sometimes|numeric',
                            'star'            => 'sometimes|string',
                            'tax'             => 'sometimes|numeric',
                            'total_lobbys'    => 'sometimes|integer',
                            'total_rooms'     => 'sometimes|integer',
                            'total_washrooms' => 'sometimes|integer',
                            'status'          => 'required|integer|digits_between:0,1',
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
            
            $response = Hotel::create([
                                        'name'              => $data['name'],
                                        'slug'              => $data['slug'],
                                        'amenities'         => json_encode($data['amenities']),
                                        'balcony'           => $data['balcony'],
                                        'breakfast'         => $data['breakfast'],
                                        'contact'           => $data['contact'],
                                        'content'           => $data['content'],
                                        'image'             => $data['image'],
                                        'image_alt'         => $data['image_alt'],
                                        'location'          => $data['location'],
                                        'price'             => $data['price'],
                                        'star'              => $data['star'],
                                        'tax'               => $data['tax'],
                                        'total_lobbys'      => $data['total_lobbys'],
                                        'total_rooms'       => $data['total_rooms'],
                                        'total_washrooms'   => $data['total_washrooms'],
                                        'highlights_content'=> $data['highlights_content'],
                                        'status'            => $data['status'],
                                        'meta_id'           => $meta->id,
                                        'city_id'           => $data['city_id'],
                                    ]);


            return Redirect::route('hotel.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in hotel store.');
        }
    }

    /**
     * Display the hotel form.
     */
    public function edit(Request $request, $id): View
    {
        $cities = City::select('id','name')->get();
        $hotel  = Hotel::findOrFail($id);
        $meta   = [];
        if(!empty($hotel->meta_id)){
            $meta   = Meta::findOrFail($hotel->meta_id);    
        }
        
        $hotel->amenities = json_decode($hotel->amenities);
        
        return view('hotel.edit',compact('cities', 'hotel', 'meta'));
    }

    /**
     * Update the hotel information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['hotel_id' => $id]);
            
            $request->validate([
                            'hotel_id'        => 'required|integer|exists:hotels,id',
                            'name'            => 'required|string',
                            'slug'            => 'required|string',
                            'amenities'       => 'sometimes|array',
                            'balcony'         => 'sometimes|integer|digits_between:0,1',
                            'breakfast'       => 'sometimes|integer|digits_between:0,1',
                            'contact'         => 'sometimes|digits:10',
                            'image'           => 'sometimes|string',
                            'image_alt'       => 'sometimes|string',
                            'location'        => 'sometimes|string',
                            'price'           => 'sometimes|numeric',
                            'star'            => 'sometimes|string',
                            'tax'             => 'sometimes|numeric',
                            'total_lobbys'    => 'sometimes|integer',
                            'total_rooms'     => 'sometimes|integer',
                            'total_washrooms' => 'sometimes|integer',
                            'status'          => 'required|integer|digits_between:0,1',
                            'city_id'         => 'required|integer|exists:cities,id',
                            'meta_title'      => 'required|string|nullable',
                            'meta_description'=> 'required|string|nullable',
                            'keywords'        => 'required|string|nullable'
                        ]);

            $hotel = Hotel::find($id);

            /* Insert Meta */
            if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$hotel->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }
            
            $response = Hotel::where('id', $hotel->id)
                                ->update([
                                        'name'              => $data['name'],
                                        'slug'              => $data['slug'],
                                        'amenities'         => json_encode($data['amenities']),
                                        'balcony'           => $data['balcony'],
                                        'breakfast'         => $data['breakfast'],
                                        'contact'           => $data['contact'],
                                        'content'           => $data['content'],
                                        'image'             => $data['image'],
                                        'image_alt'         => $data['image_alt'],
                                        'location'          => $data['location'],
                                        'price'             => $data['price'],
                                        'star'              => $data['star'],
                                        'tax'               => $data['tax'],
                                        'total_lobbys'      => $data['total_lobbys'],
                                        'total_rooms'       => $data['total_rooms'],
                                        'total_washrooms'   => $data['total_washrooms'],
                                        'highlights_content'=> $data['highlights_content'],
                                        'status'            => $data['status'],
                                        'meta_id'           => $hotel->meta_id,
                                        'city_id'           => $data['city_id'],
                                    ]);


            return Redirect::route('hotel.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in hotel update.');
        }
    }

    /**
     * Delete the hotel.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['hotel_id' => $id]);
                
            $request->validate(['hotel_id'        => 'required|integer|exists:hotels,id']);       
            $hotel = Hotel::find($id);
            if(!empty($hotel->meta_id)){
                Meta::destroy($hotel->meta_id);
            }
            $response = $hotel->destroy($id);
            return Redirect::route('hotel.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in hotel update.');
        }

    }
}
