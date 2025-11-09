<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Hospital;
use App\Models\City;
use App\Models\Meta;

class HospitalController extends Controller
{
    /**
     * Display list of hospitals
     */
    public function index()
    {
        try {
            $hospitals = Hospital::orderBy('id','desc')->paginate(12);
            return view('Hospital.index')->with(['hospitals' => $hospitals]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in Hospital index.');
        }
    }

    /**
     * Create Hospital form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('Hospital.edit',compact('cities'));
    }

    /**
     * Store Hospital form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->validate([
                            'name'            => 'required|string',
                            //'slug'            => 'required|string',
                            'address'         => 'sometimes|string',
                            'city_id'         => 'required|integer|exists:cities,id',
                            //'contact'         => 'sometimes|digits:10',
                            //'google_map'      => 'sometimes|string',
                            'image'           => 'sometimes|string|nullable',
                            'image_alt'       => 'sometimes|string|nullable',
                            //'website_url'     => 'sometimes|string',
                            //'meta_title'      => 'required|string|nullable',
                            //'meta_description'=> 'required|string|nullable',
                            //'keywords'        => 'required|string|nullable'
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

            $response = Hospital::create([
                                        'name'            => $data['name'],
                                        //'slug'            => $data['slug'],
                                        'address'         => $data['address'],
                                        'contact'         => $data['contact'] ? $data['contact'] : '',
                                        'content'         => $data['content'],
                                        'description'     => $data['description'],
                                        //'facilities'      => $data['facilities'],
                                        'google_map'      => $data['google_map'] ? $data['google_map'] : '',
                                        //'how_to_reach'    => $data['how_to_reach'],
                                        'image'           => $data['image'],
                                        'image_alt'       => $data['image_alt'],
                                        //'introduction'    => $data['introduction'],
                                        //'referral_system' => $data['referral_system'],
                                        //'trauma_services' => $data['trauma_services'],
                                        //'website_url'     => $data['website_url'],
                                        //'meta_id'         => $meta->id,
                                        'city_id'         => $data['city_id'],
                                    ]);

            return Redirect::route('hospital.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in Hospital store.');
        }
    }

    /**
     * Display the Hospital form.
     */
    public function edit(Request $request, $id): View
    {
        $cities = City::select('id','name')->get();
        $hospital  = Hospital::findOrFail($id);
        $meta   = [];
        /*if(!empty($hospital->meta_id)){
            $meta   = Meta::findOrFail($hospital->meta_id);    
        }*/
        
        return view('Hospital.edit',compact('cities', 'hospital', 'meta'));
    }

    /**
     * Update the Hospital information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['hospital_id' => $id]);
            
            $request->validate([
                            'hospital_id'     => 'required|integer|exists:hospitals,id',
                            'name'            => 'required|string',
                            //'slug'            => 'required|string',
                            'address'         => 'sometimes|string',
                            'city_id'         => 'required|integer|exists:cities,id',
                            //'contact'         => 'sometimes|digits:10',
                            //'google_map'      => 'sometimes|string',
                            'image'           => 'sometimes|string|nullable',
                            'image_alt'       => 'sometimes|string|nullable',
                            //'website_url'     => 'sometimes|string',
                            /*'meta_title'      => 'required|string|nullable',
                            'meta_description'=> 'required|string|nullable',
                            'keywords'        => 'required|string|nullable'*/
                        ]);

            $hospital = Hospital::find($id);

            /* Insert Meta */
            /*if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$hospital->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }*/
            
            $response = Hospital::where('id', $hospital->id)
                                ->update([
                                        'name'            => $data['name'],
                                        //'slug'            => $data['slug'],
                                        'address'         => $data['address'],
                                        'contact'         => $data['contact'] ? $data['contact'] : '',
                                        'content'         => $data['content'],
                                        'description'     => $data['description'],
                                        //'facilities'      => $data['facilities'],
                                        'google_map'      => $data['google_map'] ? $data['google_map'] : '',
                                        //'how_to_reach'    => $data['how_to_reach'],
                                        'image'           => $data['image'],
                                        'image_alt'       => $data['image_alt'],
                                        //'introduction'    => $data['introduction'],
                                        //'referral_system' => $data['referral_system'],
                                        //'trauma_services' => $data['trauma_services'],
                                        //'website_url'     => $data['website_url'],
                                        //'meta_id'         => $hospital->meta_id,
                                        'city_id'         => $data['city_id'],
                                    ]);


            return Redirect::route('hospital.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in hospital update.');
        }
    }

    /**
     * Delete the Hospital.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['hospital_id' => $id]);
                
            $request->validate(['hospital_id' => 'required|integer|exists:hospitals,id']);
            $hospital = Hospital::find($id);
            /*if(!empty($hospital->meta_id)){
                Meta::destroy($hospital->meta_id);
            }*/
            $response = $hospital->destroy($id);
            return Redirect::route('hospital.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in hospital destroy.');
        }
    }
}
