<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\ReligiousPlace;
use App\Models\City;
use App\Models\Meta;

class ReligiousPlaceController extends Controller
{
    /**
     * Display list of religious
     */
    public function index()
    {
        try {
            $religious = ReligiousPlace::orderBy('id','desc')->paginate(12);
            return view('Religiousplace.index')->with(['religious' => $religious]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in religious index.');
        }
    }

    /**
     * Create religious form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('Religiousplace.edit',compact('cities'));
    }

    /**
     * Store religious form.
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
            
            $response = ReligiousPlace::create([
                                        'name'             => isset($data['name']) ? $data['name'] : NULL,
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        'meta_id'          => $meta->id,
                                        'city_id'          => $data['city_id'],
                                    ]);

            return Redirect::route('religiousplace.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in religious store.');
        }
    }

    /**
     * Display the religious form.
     */
    public function edit(Request $request, $id): View
    {
        $cities             = City::select('id','name')->get();
        $religious          = ReligiousPlace::findOrFail($id);
        $meta               = [];
        if(!empty($religious->meta_id)){
            $meta = Meta::findOrFail($religious->meta_id);
        }
        
        $religious->repeater_content = json_decode($religious->repeater_content);
        
        return view('Religiousplace.edit',compact('cities', 'religious','meta'));
    }

    /**
     * Update the religious information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['religious_id' => $id]);
            
            $request->validate([
                            'religious_id'     => 'required|integer|exists:religious_places,id',
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

            $religious = ReligiousPlace::find($id);

            /* Insert Meta */
            if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$religious->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }
            
            $response = ReligiousPlace::where('id', $religious->id)
                                ->update([
                                        'name'             => isset($data['name']) ? $data['name'] : NULL,
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        'meta_id'          => $religious->meta_id,
                                        'city_id'          => $data['city_id']
                                    ]);


            return Redirect::route('religiousplace.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in religious update.');
        }
    }

    /**
     * Delete the religious.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['religious_id' => $id]);
                
            $request->validate(['religious_id' => 'required|integer|exists:religious_places,id']);
            $religious = ReligiousPlace::find($id);
            if(!empty($religious->meta_id)){
                Meta::destroy($religious->meta_id);
            }
            $response = $religious->destroy($id);
            return Redirect::route('religiousplace.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in religious destroy.');
        }
    }
}
