<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Destination;
use App\Models\Meta;
use App\Models\City;

class DestinationController extends Controller
{
    /**
     * Display list of destinations
     */
    public function index()
    {
        try {
            $destinations = Destination::orderBy('id','desc')->paginate(12);
            return view('Destination.index')->with(['destinations' => $destinations]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in destination index.');
        }
    }

    /**
     * Create the destination information.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('Destination.edit',compact('cities'));
    }

    /**
     * Store the destination form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->validate([
                            'name'             => 'required|string',
                            'slug'             => 'required|string',
                            //'title'            => 'required|string',
                            'image'            => 'sometimes|string',
                            'image_alt'        => 'sometimes|string',
                            'image_gallery'    => 'sometimes|array',
                            'destination_type' => 'required|integer|min:1|digits_between:1,2',
                            'city_id'          => 'required|integer|exists:cities,id',
                            'meta_title'       => 'required|string|nullable',
                            'meta_description' => 'required|string|nullable',
                            'keywords'         => 'required|string|nullable'
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
            
            $response = Destination::create([
                                        'name'              => $data['name'],
                                        'slug'              => $data['slug'],
                                        //'title'             => $data['title'],
                                        //'short_description' => $data['short_description'],
                                        'description'       => $data['description'],
                                        'highlights_content'=> $data['highlights_content'],
                                        'image'             => $data['image'],
                                        'image_alt'         => $data['image_alt'],
                                        'image_gallery'     => $data['image_gallery'],
                                        'destination_type'  => $data['destination_type'],
                                        'meta_id'           => $meta->id,
                                        'city_id'           => $data['city_id'],
                                    ]);

            return Redirect::route('destination.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in destination store.');
        }
    }

    /**
     * Display the destination form.
     */
    public function edit(Request $request, $id): View
    {   
        $cities = City::select('id','name')->get();
        $destination  = Destination::findOrFail($id);
        $meta   = [];
        if(!empty($destination->meta_id)){
            $meta   = Meta::findOrFail($destination->meta_id);    
        }
        
        return view('Destination.edit',compact('cities', 'destination', 'meta'));
    }
    
    /**
     * Update the destination information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['destination_id' => $id]);
            
            $request->validate([
                            'destination_id'   => 'required|integer|exists:destinations,id',
                            'name'             => 'required|string',
                            'slug'             => 'required|string',
                            //'title'            => 'required|string',
                            'image'            => 'sometimes|string',
                            'image_alt'        => 'sometimes|string',
                            'image_gallery'    => 'sometimes|array',
                            'destination_type' => 'required|integer|min:1|digits_between:1,2',
                            'city_id'          => 'required|integer|exists:cities,id',
                            'meta_title'       => 'required|string|nullable',
                            'meta_description' => 'required|string|nullable',
                            'keywords'         => 'required|string|nullable'
                        ]);

            $destination = Destination::find($id);

            /* Insert Meta */
            if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$destination->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }
            
            $response = Destination::where('id', $destination->id)
                                ->update([
                                        'name'              => $data['name'],
                                        'slug'              => $data['slug'],
                                        //'title'             => $data['title'],
                                        //'short_description' => $data['short_description'],
                                        'description'       => $data['description'],
                                        'highlights_content'=> $data['highlights_content'],
                                        'image'             => $data['image'],
                                        'image_alt'         => $data['image_alt'],
                                        'image_gallery'     => $data['image_gallery'],
                                        'destination_type'  => $data['destination_type'],
                                        'meta_id'           => $destination->meta_id,
                                        'city_id'           => $data['city_id'],
                                    ]);


            return Redirect::route('destination.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in destination update.');
        }
    }

    /**
     * Delete the Destination.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['destination_id' => $id]);
                
            $request->validate(['destination_id' => 'required|integer|exists:destinations,id']);
            $destination = Destination::find($id);
            if(!empty($destination->meta_id)){
                Meta::destroy($destination->meta_id);
            }
            $response = $destination->destroy($id);
            return Redirect::route('Destination.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in destination destroy.');
        }
    }
}
