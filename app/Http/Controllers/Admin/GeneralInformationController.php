<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\GeneralInformation;
use App\Models\City;
use App\Models\Meta;

class GeneralInformationController extends Controller
{
    /**
     * Display list of generalinformations
     */
    public function index()
    {
        try {
            $generalinformations = GeneralInformation::orderBy('id','desc')->paginate(12);
            return view('Generalinformation.index')->with(['generalinformations' => $generalinformations]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in GeneralInformation index.');
        }
    }

    /**
     * Create GeneralInformation form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('Generalinformation.edit',compact('cities'));
    }

    /**
     * Store GeneralInformation form.
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
            
            $response = GeneralInformation::create([
                                        'name'             => $data['name'],
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        'meta_id'          => $meta->id,
                                        'city_id'          => $data['city_id'],
                                    ]);

            return Redirect::route('generalinformation.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in GeneralInformation store.');
        }
    }

    /**
     * Display the GeneralInformation form.
     */
    public function edit(Request $request, $id): View
    {
        $cities             = City::select('id','name')->get();
        $generalinformation = GeneralInformation::findOrFail($id);
        $meta               = [];
        if(!empty($generalinformation->meta_id)){
            $meta = Meta::findOrFail($generalinformation->meta_id);    
        }
        
        $generalinformation->repeater_content = json_decode($generalinformation->repeater_content);
        
        return view('Generalinformation.edit',compact('cities', 'generalinformation','meta'));
    }

    /**
     * Update the GeneralInformation information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['general_id' => $id]);
            
            $request->validate([
                            'general_id'       => 'required|integer|exists:general_information,id',
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

            $generalinformation = GeneralInformation::find($id);

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
            
            $response = GeneralInformation::where('id', $generalinformation->id)
                                ->update([
                                        'name'             => $data['name'],
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        'meta_id'          => $generalinformation->meta_id,
                                        'city_id'          => $data['city_id']
                                    ]);


            return Redirect::route('generalinformation.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in GeneralInformation update.');
        }
    }

    /**
     * Delete the GeneralInformation.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['general_id' => $id]);
                
            $request->validate(['general_id' => 'required|integer|exists:general_information,id']);
            $generalinformation = GeneralInformation::find($id);
            if(!empty($generalinformation->meta_id)){
                Meta::destroy($generalinformation->meta_id);
            }
            $response = $generalinformation->destroy($id);
            return Redirect::route('generalinformation.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in GeneralInformation destroy.');
        }
    }
}
