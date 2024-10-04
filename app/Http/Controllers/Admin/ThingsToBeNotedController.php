<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\ThingsToBeNoted;
use App\Models\City;
use App\Models\Meta;

class ThingsToBeNotedController extends Controller
{
    /**
     * Display list of thingstobenoted
     */
    public function index()
    {
        try {
            $thingstobenoted = ThingsToBeNoted::orderBy('id','desc')->paginate(12);
            return view('Thingstobenoted.index')->with(['thingstobenoted' => $thingstobenoted]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in thingstobenoted index.');
        }
    }

    /**
     * Create thingstobenoted form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('Thingstobenoted.edit',compact('cities'));
    }

    /**
     * Store thingstobenoted form.
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
            
            $response = ThingsToBeNoted::create([
                                        'name'             => $data['name'],
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        'meta_id'          => $meta->id,
                                        'city_id'          => $data['city_id'],
                                    ]);

            return Redirect::route('thingstobenoted.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in thingstobenoted store.');
        }
    }

    /**
     * Display the thingstobenoted form.
     */
    public function edit(Request $request, $id): View
    {
        $cities             = City::select('id','name')->get();
        $thingstobenoted          = ThingsToBeNoted::findOrFail($id);
        $meta               = [];
        if(!empty($thingstobenoted->meta_id)){
            $meta = Meta::findOrFail($thingstobenoted->meta_id);
        }
        
        $thingstobenoted->repeater_content = json_decode($thingstobenoted->repeater_content);
        
        return view('Thingstobenoted.edit',compact('cities', 'thingstobenoted'));
    }

    /**
     * Update the thingstobenoted information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['thingstobenoted_id' => $id]);
            
            $request->validate([
                            'thingstobenoted_id'=> 'required|integer|exists:things_to_be_noted,id',
                            'name'              => 'required|string',
                            'title'             => 'required|string',
                            'image'             => 'sometimes|string|nullable',
                            'image_alt'         => 'sometimes|string|nullable',
                            'repeater_content'  => 'sometimes|array',
                            'city_id'           => 'required|integer|exists:cities,id',
                            'meta_title'        => 'required|string|nullable',
                            'meta_description'  => 'required|string|nullable',
                            'keywords'          => 'required|string|nullable'
                        ]);

            $thingstobenoted = ThingsToBeNoted::find($id);

            /* Insert Meta */
            if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$thingstobenoted->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }
            
            $response = ThingsToBeNoted::where('id', $thingstobenoted->id)
                                ->update([
                                        'name'             => $data['name'],
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        'meta_id'          => $thingstobenoted->meta_id,
                                        'city_id'          => $data['city_id']
                                    ]);


            return Redirect::route('thingstobenoted.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in thingstobenoted update.');
        }
    }

    /**
     * Delete the thingstobenoted.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['thingstobenoted_id' => $id]);
                
            $request->validate(['thingstobenoted_id' => 'required|integer|exists:things_to_be_noteds,id']);
            $thingstobenoted = ThingsToBeNoted::find($id);
            if(!empty($thingstobenoted->meta_id)){
                Meta::destroy($thingstobenoted->meta_id);
            }
            $response = $thingstobenoted->destroy($id);
            return Redirect::route('thingstobenoted.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in thingstobenoted destroy.');
        }
    }
}
