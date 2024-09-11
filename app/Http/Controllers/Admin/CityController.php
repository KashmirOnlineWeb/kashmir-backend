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

class CityController extends Controller
{
    /**
     * Display list of cities
     */
    public function index()
    {
        try {
            $cities = City::orderBy('id','desc')->paginate(12);
            return view('city.index')->with(['cities' => $cities]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in city index.');
        }
    }

    /**
     * Create the city form.
     */
    public function create(Request $request): View
    {
        return view('city.edit');
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

            $response = City::create([
                                    'name'               => $data['name'],
                                    'slug'               => $data['slug'],
                                    'description'        => $data['description'],
                                    'highlights_content' => $data['highlights_content'],
                                    'zip_code'           => $data['zip_code'],
                                    'image'              => $data['image'],
                                    'image_alt'          => $data['image_alt'],
                                    //'time_to_visit'      => $data['time_to_visit'],
                                ]);

            return Redirect::route('city.edit',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in city store.');
        }
    }

    /**
     * Display the city form.
     */
    public function edit(Request $request): View
    {
        return view('city.edit');
    }

    /**
     * Update the city information.
     */
    public function update(): RedirectResponse
    {
        
    }

    /**
     * Delete the city.
     */
    public function destroy(Request $request): RedirectResponse
    {
        
    }
}
