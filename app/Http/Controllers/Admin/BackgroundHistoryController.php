<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\BackgroundHistory;
use App\Models\City;
use App\Models\Meta;

class BackgroundHistoryController extends Controller
{
    /**
     * Display list of backgroundhistories
     */
    public function index()
    {
        try {
            $backgroundhistories = BackgroundHistory::orderBy('id','desc')->paginate(12);
            return view('BackgroundHistory.index')->with(['backgroundhistories' => $backgroundhistories]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in BackgroundHistory index.');
        }
    }

    /**
     * Create backgroundhistory form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('BackgroundHistory.edit',compact('cities'));
    }

    /**
     * Store backgroundhistory form.
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
            
            $response = BackgroundHistory::create([
                                        'name'             => $data['name'],
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        'meta_id'          => $meta->id,
                                        'city_id'          => $data['city_id'],
                                    ]);

            return Redirect::route('backgroundhistory.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in BackgroundHistory store.');
        }
    }

    /**
     * Display the backgroundhistory form.
     */
    public function edit(Request $request, $id): View
    {
        $cities            = City::select('id','name')->get();
        $backgroundhistory = BackgroundHistory::findOrFail($id);
        $meta              = [];
        if(!empty($backgroundhistory->meta_id)){
            $meta = Meta::findOrFail($backgroundhistory->meta_id);    
        }
        
        $backgroundhistory->repeater_content = json_decode($backgroundhistory->repeater_content);
        
        return view('Backgroundhistory.edit',compact('cities', 'backgroundhistory','meta'));
    }

    /**
     * Update the backgroundhistory information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['background_id' => $id]);
            
            $request->validate([
                            'background_id'    => 'required|integer|exists:background_histories,id',
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

            $backgroundhistory = BackgroundHistory::find($id);

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
            
            $response = BackgroundHistory::where('id', $backgroundhistory->id)
                                ->update([
                                        'name'             => $data['name'],
                                        'title'            => $data['title'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        'meta_id'          => $backgroundhistory->meta_id,
                                        'city_id'          => $data['city_id']
                                    ]);


            return Redirect::route('backgroundhistory.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in BackgroundHistory update.');
        }
    }

    /**
     * Delete the backgroundhistory.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['background_id' => $id]);
                
            $request->validate(['background_id' => 'required|integer|exists:background_histories,id']);
            $backgroundhistory = BackgroundHistory::find($id);
            if(!empty($backgroundhistory->meta_id)){
                Meta::destroy($backgroundhistory->meta_id);
            }
            $response = $backgroundhistory->destroy($id);
            return Redirect::route('backgroundhistory.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in BackgroundHistory destroy.');
        }
    }
}
