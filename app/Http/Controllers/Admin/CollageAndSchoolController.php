<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\CollageAndSchool;
use App\Models\Meta;
use App\Models\City;

class CollageAndSchoolController extends Controller
{
    /**
     * Display list of CollagesAndSchools
     */
    public function index()
    {
        try {
            $CollagesAndSchools = CollageAndSchool::orderBy('id','desc')->paginate(12);
            return view('Collageandschool.index')->with(['CollagesAndSchools' => $CollagesAndSchools]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in CollageAndSchool index.');
        }
    }

    /**
     * Create Collageandschool form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('Collageandschool.edit',compact('cities'));
    }

    /**
     * Store Collageandschool form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            
            $request->validate([
                            'name'            => 'required|string',
                            'slug'            => 'required|string',
                            'status'          => 'required|integer|digits_between:0,1',
                            'city_id'         => 'required|integer|exists:cities,id',
                            'board'           => 'required|string',
                            'image'           => 'sometimes|string',
                            'image_alt'       => 'sometimes|string',
                            'website_url'     => 'sometimes|string',
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
            
            $response = CollageAndSchool::create([
                                        'name'        => $data['name'],
                                        'slug'        => $data['slug'],
                                        'status'      => $data['status'],
                                        'address'     => $data['address'],
                                        'board'       => $data['board'],
                                        'content'     => $data['content'],
                                        'description' => $data['description'],
                                        'image'       => $data['image'],
                                        'image_alt'   => $data['image_alt'],
                                        'website_url' => $data['website_url'],
                                        'meta_id'     => $meta->id,
                                        'city_id'     => $data['city_id'],
                                    ]);


            return Redirect::route('Collageandschool.index',$response->id)->with('success', 'Record created successfully');;
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in Collageandschool store.');
        }
    }

    /**
     * Display the Collageandschool form.
     */
    public function edit(Request $request): View
    {
        $cities = City::select('id','name')->get();
        $CollageAndSchool = CollageAndSchool::findOrFail($id);
        $meta   = [];
        if(!empty($CollageAndSchool->meta_id)){
            $meta = Meta::findOrFail($CollageAndSchool->meta_id);    
        }
        
        return view('Collageandschool.edit',compact('cities', 'CollageAndSchool', 'meta'));

    }

    /**
     * Update the Collageandschool information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['collage_id' => $id]);
            
            $request->validate([
                            'collage_id'      => 'required|integer|exists:collagesandschools,id',
                            'name'            => 'required|string',
                            'slug'            => 'required|string',
                            'status'          => 'required|integer|digits_between:0,1',
                            'city_id'         => 'required|integer|exists:cities,id',
                            'board'           => 'required|string',
                            'image'           => 'sometimes|string',
                            'image_alt'       => 'sometimes|string',
                            'website_url'     => 'sometimes|string',
                            'meta_title'      => 'required|string|nullable',
                            'meta_description'=> 'required|string|nullable',
                            'keywords'        => 'required|string|nullable'
                        ]);

            $CollageAndSchool = CollageAndSchool::find($id);

            /* Insert Meta */
            if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$CollageAndSchool->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }
            
            $response = CollageAndSchool::where('id', $CollageAndSchool->id)
                                ->update([
                                        'name'        => $data['name'],
                                        'slug'        => $data['slug'],
                                        'status'      => $data['status'],
                                        'address'     => $data['address'],
                                        'board'       => $data['board'],
                                        'content'     => $data['content'],
                                        'description' => $data['description'],
                                        'image'       => $data['image'],
                                        'image_alt'   => $data['image_alt'],
                                        'website_url' => $data['website_url'],
                                        'meta_id'     => $CollageAndSchool->id,
                                        'city_id'     => $data['city_id'],
                                    ]);

            return Redirect::route('Collageandschool.index',$response)->with('success', 'Record updated successfully');
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in Collageandschool update.');
        }
    }

    /**
     * Delete the Collageandschool.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['collage_id' => $id]);
                
            $request->validate(['collage_id' => 'required|integer|exists:collagesandschools,id']);       
            $CollageAndSchool = CollageAndSchool::find($id);
            if(!empty($CollageAndSchool->meta_id)){
                Meta::destroy($CollageAndSchool->meta_id);
            }
            $response = $CollageAndSchool->destroy($id);
            return Redirect::route('Collageandschool.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in Collageandschool destroy.');
        }
    }
}
