<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Package;
use App\Models\Category;
use App\Models\City;
use App\Models\Meta;
use Illuminate\View\View;
use App\Models\Destination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class PackageController extends Controller
{
    /**
     * Display list of packages
     */
    public function index()
    {
        try {
            $packages = Package::orderBy('id','desc')->paginate(12);
            return view('Package.index')->with(['packages' => $packages]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in Package index.');
        }
    }

    /**
     * Create package form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        $destinations = Destination::select('id','name')->get();
        $categories = Category::select('id','name')->get();
        return view('Package.edit',compact('cities','categories','destinations'));
    }

    /**
     * Store Categories form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->validate([
                            'name'              => 'required|string',
                            'package_content'   => 'sometimes|array',
                            'price'             => 'sometimes|numeric',
                            'slug'              => 'sometimes|string',
                            'season'            => 'sometimes|string',
                            'category_id'       => 'required|integer|exists:categories,id',
                            'city_id'           => 'required|integer|exists:cities,id',
                            'accommodations'    => 'sometimes|string',
                            //'status'          => 'required|integer|digits_between:0,1',
                            'content'           => 'sometimes',
                            'addons_editor'     => 'sometimes',
                            'start_date'        => 'required|date_format:Y-m-d|before:end_date',
                            'end_date'          => 'required|date_format:Y-m-d|after:start_date',
                            'available_slots'   => 'sometimes|string',
                            'budget_type'       => 'sometimes|string',
                            //'currency'        => 'sometimes',
                            'destination'       => 'sometimes|string',
                            'days'              => 'sometimes|integer',
                            'nights'            => 'sometimes|integer',
                            'exclusions_editor' => 'sometimes',
                            'faqs_content'      => 'sometimes|array',
                            'highlight_editor'  => 'sometimes',
                            'illusions_content' => 'sometimes|array',
                            'itenery_content'   => 'sometimes|array',
                            'image'             => 'sometimes|string|nullable',
                            'image_alt'         => 'sometimes|string|nullable',
                            'image_gallery'     => 'required|array',
                            'hotel_star'        => 'sometimes|string|nullable',
                            'max_capacity'      => 'sometimes|integer|nullable',
                            'meta_title'        => 'required|string|nullable',
                            'meta_description'  => 'required|string|nullable',
                            'keywords'          => 'required|string|nullable'
                        ],[
                            'image_gallery' => 'Please upload at least one Slider image.'
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

            $response = Package::create([
                                        'name'              => $data['name'],
                                        'package_content'   => (isset($data['package_content']) ? json_encode($data['package_content']): NULL),
                                        'price'             => (isset($data['price']) ? $data['price']: NULL),
                                        'slug'              => (isset($data['slug']) ? $data['slug']: NULL),
                                        'season'            => (isset($data['season']) ? $data['season']: NULL),
                                        'category_id'       => $data['category_id'],
                                        'city_id'           => $data['city_id'],
                                        'meta_id'           => (isset($meta->id) ? $meta->id: NULL),
                                        'accommodations'    => (isset($data['accommodations']) ? $data['accommodations']: NULL),
                                        //'status'          => (isset($data['status']) ? $data['status']: NULL),
                                        'content'           => (isset($data['content']) ? $data['content']: NULL),
                                        'addons_editor'     => (isset($data['addons_editor']) ? $data['addons_editor']: NULL),
                                        'start_date'        => (isset($data['start_date']) ? $data['start_date']: NULL),
                                        'end_date'          => (isset($data['end_date']) ? $data['end_date']: NULL),
                                        'available_slots'   => (isset($data['available_slots']) ? $data['available_slots']: NULL),
                                        'budget_type'       => (isset($data['budget_type']) ? $data['budget_type']: NULL),
                                        //'currency'        => (isset($data['currency']) ? $data['currency']: NULL),
                                        'destination'       => (isset($data['destination']) ? $data['destination']: NULL),
                                        'days'              => (isset($data['days']) ? $data['days']: NULL),
                                        'nights'            => (isset($data['nights']) ? $data['nights']: NULL),
                                        'exclusions_editor' => (isset($data['exclusions_editor']) ? $data['exclusions_editor']: NULL),
                                        'faqs_content'      => (isset($data['faqs_content']) ? json_encode($data['faqs_content']): NULL),
                                        'highlight_editor'  => (isset($data['highlight_editor']) ? $data['highlight_editor']: NULL),
                                        'illusions_content' => (isset($data['illusions_content']) ? json_encode($data['illusions_content']): NULL),
                                        'itenery_content'   => (isset($data['itenery_content']) ? json_encode($data['itenery_content']): NULL),
                                        'image'             => (isset($data['image']) ? $data['image']: NULL),
                                        'image_alt'         => (isset($data['image_alt']) ? $data['image_alt']: NULL),
                                        'image_gallery'     => (isset($data['image_gallery']) ? json_encode($data['image_gallery']): NULL),
                                        'hotel_star'        => (isset($data['hotel_star']) ? $data['hotel_star']: NULL),
                                        'max_capacity'      => (isset($data['max_capacity']) ? $data['max_capacity']: NULL),
                                        'is_special'        => (isset($data['is_special']) ? $data['is_special']: 0)
                                    ]);

            return Redirect::route('package.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in package store.');
        }
    }

    /**
     * Display the Package form.
     */
    public function edit(Request $request, $id): View
    {
        $cities     = City::select('id','name')->get();
        $package    = Package::findOrFail($id);
        $destinations = Destination::select('id','name')->get();
        $categories = Category::select('id','name')->get();
        $meta       = [];
        if(!empty($package->meta_id)){
            $meta   = Meta::findOrFail($package->meta_id);    
        }
        $category   = [];
        if(!empty($package->category_id)){
            $category = Category::findOrFail($package->category_id);    
        }
        $destination   = [];
        if(!empty($package->destination)){
            $destination = Destination::findOrFail($package->destination);    
        }

        $package->package_content = json_decode($package->package_content);
        $package->faqs_content = json_decode($package->faqs_content);
        $package->illusions_content = json_decode($package->illusions_content);
        $package->itenery_content = json_decode($package->itenery_content);
        $package->image_gallery = json_decode($package->image_gallery);
        
        return view('Package.edit',compact('cities', 'package', 'meta', 'category','destinations','categories'));
    }

    /**
     * Update the Package.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['package_id' => $id]);
            
            $request->validate([
                            'package_id'        => 'required|integer|exists:packages,id',
                            'name'              => 'required|string',
                            'package_content'   => 'sometimes|array',
                            'price'             => 'sometimes|numeric',
                            'slug'              => 'sometimes|string',
                            'season'            => 'sometimes|string',
                            'category_id'       => 'required|integer|exists:categories,id',
                            'city_id'           => 'required|integer|exists:cities,id',
                            'accommodations'    => 'sometimes|string',
                            //'status'          => 'required|integer|digits_between:0,1',
                            'content'           => 'sometimes',
                            'addons_editor'     => 'sometimes',
                            'start_date'        => 'required|date_format:Y-m-d|before:end_date',
                            'end_date'          => 'required|date_format:Y-m-d|after:start_date',
                            'available_slots'   => 'sometimes|string',
                            'budget_type'       => 'sometimes|string',
                            //'currency'        => 'sometimes',
                            'destination'       => 'sometimes|string',
                            'days'              => 'sometimes|integer',
                            'nights'            => 'sometimes|integer',
                            'exclusions_editor' => 'sometimes',
                            'faqs_content'      => 'sometimes|array',
                            'highlight_editor'  => 'sometimes',
                            'illusions_content' => 'sometimes|array',
                            'itenery_content'   => 'sometimes|array',
                            'image'             => 'sometimes|string|nullable',
                            'image_alt'         => 'sometimes|string|nullable',
                            'image_gallery'     => 'required|array',
                            'hotel_star'        => 'sometimes|string|nullable',
                            'max_capacity'      => 'sometimes|integer|nullable',
                            'meta_title'        => 'required|string|nullable',
                            'meta_description'  => 'required|string|nullable',
                            'keywords'          => 'required|string|nullable'
                        ],[
                            'image_gallery' => 'Please upload at least one Slider image.'
                        ]);

            $package = Package::find($id);

            /* Insert Meta */
            if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$package->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }
            $response = Package::where('id', $package->id)
                                ->update([
                                        'name'              => $data['name'],
                                        'package_content'   => (isset($data['package_content']) ? json_encode($data['package_content']): NULL),
                                        'price'             => (isset($data['price']) ? $data['price']: NULL),
                                        'slug'              => (isset($data['slug']) ? $data['slug']: NULL),
                                        'season'            => (isset($data['season']) ? $data['season']: NULL),
                                        'category_id'       => $data['category_id'],
                                        'city_id'           => $data['city_id'],
                                        'meta_id'           => (isset($package->meta_id) ? $package->meta_id: NULL),
                                        'accommodations'    => (isset($data['accommodations']) ? $data['accommodations']: NULL),
                                        //'status'          => (isset($data['status']) ? $data['status']: NULL),
                                        'content'           => (isset($data['content']) ? $data['content']: NULL),
                                        'addons_editor'     => (isset($data['addons_editor']) ? $data['addons_editor']: NULL),
                                        'start_date'        => (isset($data['start_date']) ? $data['start_date']: NULL),
                                        'end_date'          => (isset($data['end_date']) ? $data['end_date']: NULL),
                                        'available_slots'   => (isset($data['available_slots']) ? $data['available_slots']: NULL),
                                        'budget_type'       => (isset($data['budget_type']) ? $data['budget_type']: NULL),
                                        //'currency'        => (isset($data['currency']) ? $data['currency']: NULL),
                                        'destination'       => (isset($data['destination']) ? $data['destination']: NULL),
                                        'days'              => (isset($data['days']) ? $data['days']: NULL),
                                        'nights'            => (isset($data['nights']) ? $data['nights']: NULL),
                                        'exclusions_editor' => (isset($data['exclusions_editor']) ? $data['exclusions_editor']: NULL),
                                        'faqs_content'      => (isset($data['faqs_content']) ? json_encode($data['faqs_content']): NULL),
                                        'highlight_editor'  => (isset($data['highlight_editor']) ? $data['highlight_editor']: NULL),
                                        'illusions_content' => (isset($data['illusions_content']) ? json_encode($data['illusions_content']): NULL),
                                        'itenery_content'   => (isset($data['itenery_content']) ? json_encode($data['itenery_content']): NULL),
                                        'image'             => (isset($data['image']) ? $data['image']: NULL),
                                        'image_alt'         => (isset($data['image_alt']) ? $data['image_alt']: NULL),
                                        'image_gallery'     => (isset($data['image_gallery']) ? json_encode($data['image_gallery']): NULL),
                                        'hotel_star'        => (isset($data['hotel_star']) ? $data['hotel_star']: NULL),
                                        'max_capacity'      => (isset($data['max_capacity']) ? $data['max_capacity']: NULL),
                                        'is_special'        => (isset($data['is_special']) ? $data['is_special']: 0)
                                    ]);


            return Redirect::route('package.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in package update.');
        }
    }

    /**
     * Delete the Package.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['package_id' => $id]);
                
            $request->validate(['package_id' => 'required|integer|exists:packages,id']);
            $package = Package::find($id);
            if(!empty($package->meta_id)){
                Meta::destroy($package->meta_id);
            }
            $response = $package->destroy($id);
            return Redirect::route('package.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in package destroy.');
        }
    }
}
