<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\SafetyInformation;
use App\Models\City;
use App\Models\Meta;

class SafetyInformationController extends Controller
{
    /**
     * Display list of safetyinformation
     */
    public function index()
    {
        try {
            $safetyinformation = SafetyInformation::orderBy('id','desc')->paginate(12);
            return view('Safetyinformation.index')->with(['safetyinformation' => $safetyinformation]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in safetyinformation index.');
        }
    }

    /**
     * Create safetyinformation form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('Safetyinformation.edit',compact('cities'));
    }

    /**
     * Store safetyinformation form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            
            $request->validate([
                            //'name'             => 'required|string',
                            'title'            => 'required|string',
                            //'image'            => 'sometimes|string|nullable',
                            'image_alt'        => 'sometimes|string|nullable',
                            'repeater_content' => 'sometimes|array',
                            'description'      => 'required|array',
                            'city_id'          => 'required|integer|exists:cities,id',
                            'meta_title'       => 'required|string|nullable',
                            'meta_description' => 'required|string|nullable',
                            'keywords'         => 'required|string|nullable'
                        ],[
                            'description' => 'Add atleat one safety number.'
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
            
            $response = SafetyInformation::create([
                                        'name'             => isset($data['name']) ? $data['name'] : NULL,
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'description'      => json_encode($data['description']),
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        'meta_id'          => $meta->id,
                                        'city_id'          => $data['city_id'],
                                    ]);

            return Redirect::route('safetyinformation.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in safetyinformation store.');
        }
    }

    /**
     * Display the safetyinformation form.
     */
    public function edit(Request $request, $id): View
    {
        $cities             = City::select('id','name')->get();
        $safetyinformation = SafetyInformation::findOrFail($id);
        $meta               = [];
        if(!empty($safetyinformation->meta_id)){
            $meta = Meta::findOrFail($safetyinformation->meta_id);    
        }
        
        $safetyinformation->repeater_content = json_decode($safetyinformation->repeater_content);
        $safetyinformation->description = json_decode($safetyinformation->description);
        
        return view('Safetyinformation.edit',compact('cities', 'safetyinformation','meta'));
    }

    /**
     * Update the safetyinformation information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['safety_id' => $id]);
            
            $request->validate([
                            'safety_id'        => 'required|integer|exists:safety_information,id',
                            //'name'             => 'required|string',
                            'title'            => 'required|string',
                            //'image'            => 'sometimes|string|nullable',
                            'image_alt'        => 'sometimes|string|nullable',
                            'repeater_content' => 'sometimes|array',
                            'description'      => 'sometimes|array',
                            'city_id'          => 'required|integer|exists:cities,id',
                            'meta_title'       => 'required|string|nullable',
                            'meta_description' => 'required|string|nullable',
                            'keywords'         => 'required|string|nullable'
                        ]);

            $safetyinformation = SafetyInformation::find($id);

            /* Insert Meta */
            if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$safetyinformation->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }
            
            $response = SafetyInformation::where('id', $safetyinformation->id)
                                ->update([
                                        'name'             => isset($data['name']) ? $data['name'] : NULL,
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        'description' =>      json_encode($data['description']),
                                        'meta_id'          => $safetyinformation->meta_id,
                                        'city_id'          => $data['city_id']
                                    ]);


            return Redirect::route('safetyinformation.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in safetyinformation update.');
        }
    }

    /**
     * Delete the safetyinformation.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['safety_id' => $id]);
                
            $request->validate(['safety_id' => 'required|integer|exists:safety_information,id']);
            $safetyinformation = SafetyInformation::find($id);
            if(!empty($safetyinformation->meta_id)){
                Meta::destroy($safetyinformation->meta_id);
            }
            $response = $safetyinformation->destroy($id);
            return Redirect::route('safetyinformation.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in safetyinformation destroy.');
        }
    }
}
