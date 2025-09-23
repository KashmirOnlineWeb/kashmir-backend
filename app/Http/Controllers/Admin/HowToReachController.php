<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\HowToReach;
use App\Models\City;
use App\Models\Meta;

class HowToReachController extends Controller
{
    /**
     * Display list of howtoreach
     */
    public function index()
    {
        try {
            $howtoreach = HowToReach::orderBy('id','desc')->paginate(12);
            return view('Howtoreach.index')->with(['howtoreach' => $howtoreach]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in howtoreach index.');
        }
    }

    /**
     * Create howtoreach form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('Howtoreach.edit',compact('cities'));
    }

    /**
     * Store howtoreach form.
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
                            // 'meta_title'       => 'required|string|nullable',
                            // 'meta_description' => 'required|string|nullable',
                            // 'keywords'         => 'required|string|nullable'
                        ]);

            /* Insert Meta */
            // if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
            //     $meta = Meta::create([
            //                 'meta_title'       => $data['meta_title'],
            //                 'meta_description' => $data['meta_description'],
            //                 'keywords'         => $data['keywords'],
            //                 'status'           => 1,
            //             ]);
            // }
            
            $response = HowToReach::create([
                                        'name'             => $data['name'],
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        //'meta_id'          => $meta->id,
                                        'city_id'          => $data['city_id'],
                                    ]);

            return Redirect::route('howtoreach.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in howtoreach store.');
        }
    }

    /**
     * Display the howtoreach form.
     */
    public function edit(Request $request, $id): View
    {
        $cities             = City::select('id','name')->get();
        $howtoreach = HowToReach::findOrFail($id);
        $meta               = [];
        if(!empty($howtoreach->meta_id)){
            $meta = Meta::findOrFail($howtoreach->meta_id);    
        }
        
        $howtoreach->repeater_content = json_decode($howtoreach->repeater_content);
        
        return view('Howtoreach.edit',compact('cities', 'howtoreach','meta'));
    }

    /**
     * Update the howtoreach information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['reach_id' => $id]);
            
            $request->validate([
                            'reach_id'       => 'required|integer|exists:how_to_reachs,id',
                            'name'             => 'required|string',
                            'title'            => 'required|string',
                            'image'            => 'sometimes|string|nullable',
                            'image_alt'        => 'sometimes|string|nullable',
                            'repeater_content' => 'sometimes|array',
                            'city_id'          => 'required|integer|exists:cities,id',
                            // 'meta_title'       => 'required|string|nullable',
                            // 'meta_description' => 'required|string|nullable',
                            // 'keywords'         => 'required|string|nullable'
                        ]);

            $howtoreach = HowToReach::find($id);

            /* Insert Meta */
            // if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
            //     $meta = Meta::where('id',$howtoreach->meta_id)
            //                 ->update([
            //                     'meta_title'       => $data['meta_title'],
            //                     'meta_description' => $data['meta_description'],
            //                     'keywords'         => $data['keywords'],
            //                     'status'           => 1,
            //                 ]);
            // }
            
            $response = HowToReach::where('id', $howtoreach->id)
                                ->update([
                                        'name'             => $data['name'],
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        //'meta_id'          => $howtoreach->meta_id,
                                        'city_id'          => $data['city_id']
                                    ]);


            return Redirect::route('howtoreach.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in howtoreach update.');
        }
    }

    /**
     * Delete the howtoreach.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['reach_id' => $id]);
                
            $request->validate(['reach_id' => 'required|integer|exists:how_to_reachs,id']);
            $howtoreach = HowToReach::find($id);
            if(!empty($howtoreach->meta_id)){
                Meta::destroy($howtoreach->meta_id);
            }
            $response = $howtoreach->destroy($id);
            return Redirect::route('howtoreach.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in howtoreach destroy.');
        }
    }
}
