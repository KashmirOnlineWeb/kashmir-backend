<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Activity;
use App\Models\City;
use App\Models\Destination;
use App\Models\Category;

use App\Models\Hotel;
use App\Models\Meta;

class ActivityController extends Controller
{
    /**
     * Display list of activities
     */
    public function index()
    {
        try {
            $activities = Activity::orderBy('id','desc')->paginate(12);
            return view('Activity.index')->with(['activities' => $activities]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in activity index.');
        }
    }

    /**
     * Create activity form.
     */
    public function create(Request $request): View
    {
        $destinations = Destination::select('id','name')->get();
        $categories   = Category::select('id','name')->get();
        return view('Activity.edit',compact('categories','destinations'));
    }

    /**
     * Store activity form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            
            $request->validate([
                            'name'            => 'required|string',
                            'slug'            => 'sometimes|string',
                            'image'           => 'sometimes|string|nullable',
                            'image_alt'       => 'sometimes|string|nullable',
                            'season'          => 'sometimes|string',
                            'category_id'     => 'required|integer|exists:categories,id',
                            'destination_id'  => 'required|integer|exists:destinations,id',
                            //'status'          => 'required|integer|digits_between:0,1',
                            //'city_id'         => 'required|integer|exists:cities,id',
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
            
            $response = Activity::create([
                                        'name'           => $data['name'],
                                        'slug'           => $data['slug'],
                                        'image'          => $data['image'],
                                        'image_alt'      => $data['image_alt'],
                                        'description'    => (isset($data['description']) ? $data['description'] : NULL),
                                        'season'         => (isset($data['season']) ? $data['season']: NULL),
                                        //'status'       => $data['status'],
                                        'destination_id' => $data['destination_id'],
                                        'category_id'    => $data['category_id'],
                                        'meta_id'        => $meta->id,
                                    ]);


            return Redirect::route('activity.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in activity store.');
        }
    }

    /**
     * Display the activity form.
     */
    public function edit(Request $request, $id): View
    {
        $destinations = Destination::select('id','name')->get();
        $categories   = Category::select('id','name')->get();
        $activity     = Activity::findOrFail($id);
        $meta         = [];
        if(!empty($activity->meta_id)){
            $meta   = Meta::findOrFail($activity->meta_id);    
        }
        
        return view('Activity.edit',compact('categories', 'activity', 'destinations', 'meta'));
    }

    /**
     * Update the activity information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['activity_id' => $id]);
            
            $request->validate([
                            'activity_id'     => 'required|integer|exists:activities,id',
                            'name'            => 'required|string',
                            'slug'            => 'sometimes|string',
                            'image'           => 'sometimes|string|nullable',
                            'image_alt'       => 'sometimes|string|nullable',
                            'season'          => 'sometimes|string',
                            'category_id'     => 'required|integer|exists:categories,id',
                            'destination_id'  => 'required|integer|exists:destinations,id',
                            //'status'          => 'required|integer|digits_between:0,1',
                            //'city_id'         => 'required|integer|exists:cities,id',
                            'meta_title'      => 'required|string|nullable',
                            'meta_description'=> 'required|string|nullable',
                            'keywords'        => 'required|string|nullable'
                        ]);

            $activity = Activity::find($id);

            /* Insert Meta */
            if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$activity->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }
            
            $response = Activity::where('id', $activity->id)
                                ->update([
                                        'name'           => $data['name'],
                                        'slug'           => $data['slug'],
                                        'image'          => $data['image'],
                                        'image_alt'      => $data['image_alt'],
                                        'description'    => (isset($data['description']) ? $data['description'] : NULL),
                                        'season'         => (isset($data['season']) ? $data['season']: NULL),
                                        //'status'       => $data['status'],
                                        'destination_id' => $data['destination_id'],
                                        'category_id'    => $data['category_id'],
                                        'meta_id'        => (isset($activity->meta_id) ? $activity->meta_id: NULL),
                                    ]);


            return Redirect::route('activity.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in activity update.');
        }
    }

    /**
     * Delete the activity.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['activity_id' => $id]);
                
            $request->validate(['activity_id'        => 'required|integer|exists:activities,id']);       
            $activity = Activity::find($id);
            if(!empty($activity->meta_id)){
                Meta::destroy($activity->meta_id);
            }
            $response = $activity->destroy($id);
            return Redirect::route('activity.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in activity destroy.');
        }
    }
}
