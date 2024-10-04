<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Location;
use App\Models\City;
use App\Models\Meta;

class LocationController extends Controller
{
    /**
     * Display list of location
     */
    public function index()
    {
        try {
            $locations = Location::orderBy('id','desc')->paginate(12);
            return view('Location.index')->with(['locations' => $locations]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in Location index.');
        }
    }

    /**
     * Create location form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('Location.edit',compact('cities'));
    }

    /**
     * Store location form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            
            $request->validate([
                            'name'             => 'required|string',
                            'title'            => 'required|string',
                            'image'            => 'sometimes|string|nullable',
                            'image_alt'        => 'sometimes|string|nullable',
                            'repeater_content' => 'sometimes|array',
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
            
            $response = Location::create([
                                        'name'             => $data['name'],
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        'meta_id'          => $meta->id,
                                        'city_id'          => $data['city_id'],
                                    ]);

            return Redirect::route('location.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in Location store.');
        }
    }

    /**
     * Display the Location form.
     */
    public function edit(Request $request, $id): View
    {
        $cities             = City::select('id','name')->get();
        $location          = Location::findOrFail($id);
        $meta               = [];
        if(!empty($location->meta_id)){
            $meta = Meta::findOrFail($location->meta_id);
        }
        
        $location->repeater_content = json_decode($location->repeater_content);
        
        return view('Location.edit',compact('cities', 'location','meta'));
    }

    /**
     * Update the location information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['location_id' => $id]);
            
            $request->validate([
                            'location_id'       => 'required|integer|exists:locations,id',
                            'name'             => 'required|string',
                            'title'            => 'required|string',
                            'image'            => 'sometimes|string|nullable',
                            'image_alt'        => 'sometimes|string|nullable',
                            'repeater_content' => 'sometimes|array',
                            'city_id'          => 'required|integer|exists:cities,id',
                            'meta_title'       => 'required|string|nullable',
                            'meta_description' => 'required|string|nullable',
                            'keywords'         => 'required|string|nullable'
                        ]);

            $location = Location::find($id);

            /* Insert Meta */
            if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$location->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }
            
            $response = Location::where('id', $location->id)
                                ->update([
                                        'name'             => $data['name'],
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        'meta_id'          => $location->meta_id,
                                        'city_id'          => $data['city_id']
                                    ]);


            return Redirect::route('location.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in location update.');
        }
    }

    /**
     * Delete the location.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['location_id' => $id]);
                
            $request->validate(['location_id' => 'required|integer|exists:locations,id']);
            $location = Location::find($id);
            if(!empty($location->meta_id)){
                Meta::destroy($location->meta_id);
            }
            $response = $location->destroy($id);
            return Redirect::route('location.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in location destroy.');
        }
    }
}
