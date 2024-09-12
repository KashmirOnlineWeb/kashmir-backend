<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\City;
use App\Models\Meta;

class CityController extends Controller
{
    /**
     * Display list of cities
     */
    public function index()
    {
        try {
            $cities = City::orderBy('id','desc')->paginate(12);
            return view('City.index')->with(['cities' => $cities]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in city index.');
        }
    }

    /**
     * Create the city form.
     */
    public function create(Request $request): View
    {
        $meta = (object) [
            'meta_title' => '',
            'meta_description' => '',
            'keywords' => ''
        ];

        return view('City.edit', compact('meta'));
    }

    /**
     * Store city form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->validate([
                            'name'      => 'required|string',
                            'slug'      => 'required|string',
                            'zip_code'  => 'required|string'
                        ]);
            
            $meta = Meta::create([
                'meta_title' => $data['meta_title'],
                'meta_description' => $data['meta_description'],
                'keywords' => $data['keywords'],
            ]);

            $response = City::create([
                                    'name'               => $data['name'],
                                    'slug'               => $data['slug'],
                                    'description'        => $data['description'],
                                    'highlights_content' => $data['highlights_content'],
                                    'zip_code'           => $data['zip_code'],
                                    //'image'              => $data['image'],
                                    'image_alt'          => $data['image_alt'],
                                    'meta_id'            => $meta->id,
                                    //'time_to_visit'      => $data['time_to_visit'],
                                ]);

            return Redirect::route('City.edit',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in city store.');
        }
    }

    /**
     * Display the city form.
     */
    public function edit(Request $request, $id): View
    {
        $city = City::findOrFail($id);
        $meta = Meta::findOrFail($city->meta_id);
        return view('City.edit', compact('city', 'meta'));
    }

    /**
     * Update the city information.
     */
    public function update(): RedirectResponse
    {
        
    }

    /**
     * Delete the City.
     */
    public function destroy(Request $request): RedirectResponse
    {
        
    }
}
