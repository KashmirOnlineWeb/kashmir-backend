<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\SightSeeing;
use App\Models\City;
use App\Models\Meta;

class SightSeeingController extends Controller
{
    /**
     * Display list of sightseeing
     */
    public function index()
    {
        try {
            $sightseeings = SightSeeing::orderBy('id','desc')->paginate(12);
            return view('Sightseeing.index')->with(['sightseeings' => $sightseeings]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in sightseeing index.');
        }
    }

    /**
     * Create sightseeing form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('Sightseeing.edit',compact('cities'));
    }

    /**
     * Store sightseeing form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            
            $request->validate([
                            //'name'             => 'required|string',
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
            
            $response = SightSeeing::create([
                                        'name'             => isset($data['name']) ? $data['name'] : NULL,
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        'meta_id'          => $meta->id,
                                        'city_id'          => $data['city_id'],
                                    ]);

            return Redirect::route('sightseeing.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in sightseeing store.');
        }
    }

    /**
     * Display the sightseeing form.
     */
    public function edit(Request $request, $id): View
    {
        $cities            = City::select('id','name')->get();
        $sightseeing       = SightSeeing::findOrFail($id);
        $meta              = [];
        if(!empty($sightseeing->meta_id)){
            $meta = Meta::findOrFail($sightseeing->meta_id);
        }
        
        $sightseeing->repeater_content = json_decode($sightseeing->repeater_content);
        
        return view('Sightseeing.edit',compact('cities', 'sightseeing','meta'));
    }

    /**
     * Update the sightseeing information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['sightseeing_id' => $id]);
            
            $request->validate([
                            'sightseeing_id'   => 'required|integer|exists:sight_seeings,id',
                            //'name'             => 'required|string',
                            'title'            => 'required|string',
                            'image'            => 'sometimes|string|nullable',
                            'image_alt'        => 'sometimes|string|nullable',
                            'repeater_content' => 'sometimes|array',
                            'city_id'          => 'required|integer|exists:cities,id',
                            'meta_title'       => 'required|string|nullable',
                            'meta_description' => 'required|string|nullable',
                            'keywords'         => 'required|string|nullable'
                        ]);

            $sightseeing = SightSeeing::find($id);

            /* Insert Meta */
            if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$sightseeing->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }
            
            $response = SightSeeing::where('id', $sightseeing->id)
                                ->update([
                                        'name'             => isset($data['name']) ? $data['name'] : NULL,
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        'meta_id'          => $sightseeing->meta_id,
                                        'city_id'          => $data['city_id']
                                    ]);


            return Redirect::route('sightseeing.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in sightseeing update.');
        }
    }

    /**
     * Delete the sightseeing.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['sightseeing_id' => $id]);
                
            $request->validate(['sightseeing_id' => 'required|integer|exists:sight_seeings,id']);
            $sightseeing = SightSeeing::find($id);
            if(!empty($sightseeing->meta_id)){
                Meta::destroy($sightseeing->meta_id);
            }
            $response = $sightseeing->destroy($id);
            return Redirect::route('sightseeing.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in sightseeing destroy.');
        }
    }
}
