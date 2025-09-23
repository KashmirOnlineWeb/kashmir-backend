<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\ThingsToDo;
use App\Models\City;
use App\Models\Meta;

class ThingsToDoController extends Controller
{
    /**
     * Display list of thingstodo
     */
    public function index()
    {
        try {
            $thingstodo = ThingsToDo::orderBy('id','desc')->paginate(12);
            return view('Thingstodo.index')->with(['thingstodo' => $thingstodo]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in thingstodo index.');
        }
    }

    /**
     * Create thingstodo form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('Thingstodo.edit',compact('cities'));
    }

    /**
     * Store thingstodo form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            
            $request->validate([
                            //'name'             => 'required|string',
                            //'title'            => 'required|string',
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
            
            $response = ThingsToDo::create([
                                        'name'             => isset($data['name']) ? $data['name'] : NULL,
                                        'title'            => isset($data['title']) ? $data['title'] : NULL,
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        //'meta_id'          => $meta->id,
                                        'city_id'          => $data['city_id'],
                                    ]);

            return Redirect::route('thingstodo.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in thingstodo store.');
        }
    }

    /**
     * Display the thingstodo form.
     */
    public function edit(Request $request, $id): View
    {
        $cities   = City::select('id','name')->get();
        $thingstodo = ThingsToDo::findOrFail($id);
        $meta     = [];
        if(!empty($thingstodo->meta_id)){
            $meta = Meta::findOrFail($thingstodo->meta_id);
        }
        
        $thingstodo->repeater_content = json_decode($thingstodo->repeater_content);
        
        return view('Thingstodo.edit',compact('cities', 'thingstodo','meta'));
    }

    /**
     * Update the thingstodo information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['thingstodo_id' => $id]);
            
            $request->validate([
                            'thingstodo_id'    => 'required|integer|exists:things_to_dos,id',
                            //'name'             => 'required|string',
                            //'title'            => 'required|string',
                            'image'            => 'sometimes|string|nullable',
                            'image_alt'        => 'sometimes|string|nullable',
                            'repeater_content' => 'sometimes|array',
                            'city_id'          => 'required|integer|exists:cities,id',
                            // 'meta_title'       => 'required|string|nullable',
                            // 'meta_description' => 'required|string|nullable',
                            // 'keywords'         => 'required|string|nullable'
                        ]);

            $thingstodo = ThingsToDo::find($id);

            /* Insert Meta */
            // if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
            //     $meta = Meta::where('id',$thingstodo->meta_id)
            //                 ->update([
            //                     'meta_title'       => $data['meta_title'],
            //                     'meta_description' => $data['meta_description'],
            //                     'keywords'         => $data['keywords'],
            //                     'status'           => 1,
            //                 ]);
            // }
            
            $response = ThingsToDo::where('id', $thingstodo->id)
                                ->update([
                                        'name'             => isset($data['name']) ? $data['name'] : NULL,
                                        'title'            => isset($data['title']) ? $data['title'] : NULL,
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        'repeater_content' => json_encode($data['repeater_content']),
                                        //'meta_id'          => $thingstodo->meta_id,
                                        'city_id'          => $data['city_id']
                                    ]);


            return Redirect::route('thingstodo.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in thingstodo update.');
        }
    }

    /**
     * Delete the thingstodo.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['thingstodo_id' => $id]);
                
            $request->validate(['thingstodo_id' => 'required|integer|exists:things_to_dos,id']);
            $thingstodo = ThingsToDo::find($id);
            if(!empty($thingstodo->meta_id)){
                Meta::destroy($thingstodo->meta_id);
            }
            $response = $thingstodo->destroy($id);
            return Redirect::route('thingstodo.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in thingstodo destroy.');
        }
    }
}
