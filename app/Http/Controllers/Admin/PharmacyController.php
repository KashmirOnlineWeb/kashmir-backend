<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Pharmacy;
use App\Models\City;
use App\Models\Meta;

class PharmacyController extends Controller
{
    /**
     * Display list of pharmacies
     */
    public function index()
    {
        try {
            $pharmacies = Pharmacy::orderBy('id','desc')->paginate(12);
            return view('Pharmacy.index')->with(['pharmacies' => $pharmacies]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in pharmacy index.');
        }
    }

    /**
     * Create pharmacy form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('Pharmacy.edit',compact('cities'));
    }

    /**
     * Store pharmacy form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->validate([
                            'name'            => 'required|string',
                            'status'          => 'required|integer|digits_between:0,1',
                            'slug'            => 'required|string',
                            'google_map'      => 'sometimes|string',
                            'location'        => 'required|string',
                            'working_hours'   => 'required|string',
                            //'contact'         => 'sometimes|digits:10',
                            'image'           => 'sometimes|string|nullable',
                            'image_alt'       => 'sometimes|string|nullable',
                            'city_id'         => 'required|integer|exists:cities,id',
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

            $response = Pharmacy::create([
                                        'name'            => $data['name'],
                                        'slug'            => $data['slug'],
                                        'status'          => $data['status'],
                                        //'content'         => $data['content'],
                                        'google_map'      => $data['google_map'],
                                        'location'        => $data['location'],
                                        'working_hours'   => $data['working_hours'],
                                        'contact'         => $data['contact'] ? $data['contact'] : '',
                                        'image'             => $data['image'],
                                        'image_alt'         => $data['image_alt'],
                                        //'meta_id'           => $meta->id,
                                        'city_id'           => $data['city_id'],
                                    ]);

            return Redirect::route('pharmacy.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in pharmacy store.');
        }
    }

    /**
     * Display the pharmacy form.
     */
    public function edit(Request $request, $id): View
    {
        $cities    = City::select('id','name')->get();
        $pharmacy  = Pharmacy::findOrFail($id);
        $meta      = [];
        /*if(!empty($pharmacy->meta_id)){
            $meta  = Meta::findOrFail($pharmacy->meta_id);    
        }*/
        return view('Pharmacy.edit',compact('cities', 'pharmacy', 'meta'));
    }

    /**
     * Update the pharmacy information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['pharmacy_id' => $id]);
            
            $request->validate([
                            'pharmacy_id'     => 'required|integer|exists:pharmacies,id',
                            'name'            => 'required|string',
                            'status'          => 'required|integer|digits_between:0,1',
                            'slug'            => 'required|string',
                            'google_map'      => 'sometimes|string',
                            'location'        => 'required|string',
                            'working_hours'   => 'required|string',
                            //'contact'         => 'sometimes|digits:10',
                            'image'           => 'sometimes|string|nullable',
                            'image_alt'       => 'sometimes|string|nullable',
                            'city_id'         => 'required|integer|exists:cities,id',
                            //'meta_title'      => 'required|string|nullable',
                            //'meta_description'=> 'required|string|nullable',
                            //'keywords'        => 'required|string|nullable'
                        ]);

            $pharmacy = Pharmacy::find($id);

            /* Insert Meta */
            /*if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$pharmacy->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }*/
            
            $response = pharmacy::where('id', $pharmacy->id)
                                ->update([
                                        'name'            => $data['name'],
                                        'slug'            => $data['slug'],
                                        'status'          => $data['status'],
                                        //'content'         => $data['content'],
                                        'google_map'      => $data['google_map'],
                                        'location'        => $data['location'],
                                        'working_hours'   => $data['working_hours'],
                                        'contact'         => $data['contact'] ? $data['contact'] : '',
                                        'image'           => $data['image'],
                                        'image_alt'       => $data['image_alt'],
                                        //'meta_id'           => $pharmacy->meta_id,
                                        'city_id'         => $data['city_id'],
                                    ]);

            return Redirect::route('pharmacy.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in pharmacy update.');
        }
    }

    /**
     * Delete the pharmacy.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['pharmacy_id' => $id]);
                
            $request->validate(['pharmacy_id'        => 'required|integer|exists:pharmacies,id']);       
            $pharmacy = Pharmacy::find($id);
            /*if(!empty($pharmacy->meta_id)){
                Meta::destroy($pharmacy->meta_id);
            }*/
            $response = $pharmacy->destroy($id);
            return Redirect::route('pharmacy.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in pharmacy destroy.');
        }
    }
}
