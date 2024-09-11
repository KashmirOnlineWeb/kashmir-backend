<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Destination;

class DestinationController extends Controller
{
    /**
     * Display list of destinations
     */
    public function index()
    {
        try {
            $destinations = Destination::orderBy('id','desc')->paginate(12);
            return view('destination.index')->with(['destinations' => $destinations]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in destination index.');
        }
    }

    /**
     * Create the destination form.
     */
    public function create(Request $request): View
    {
        return view('destination.edit');
    }

    /**
     * Store the destination form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->validate([
                            'name' => 'required|string',
                            'slug' => 'required|string',
                            'title' => 'required|string',
                            'destination_type' => 'required|integer|min:1|digits_between:1,2',
                            //'city_id' => 'required|string',
                            //'meta_id' => 'required|string',
                        ]);

            $result = Destination::create([
                            'name'              => $data['name'],
                            'slug'              => $data['slug'],
                            'title'             => $data['title'],
                            'short_description' => $data['short_description'],
                            'description'       => $data['description'],
                            'highlights_content' => $data['highlights_content'],
                            //'image'           => $data['image'],
                            'image_alt'         => $data['image_alt'],
                            //'image_gallery'   => $data['image_gallery'],
                            'destination_type'  => $data['destination_type'],
                        ]);

            return Redirect::route('destination.edit',$result->id);

        } catch (Exception $e) {
            Log::error('Somethinng went wrong in destination store.');
        }
    }

    /**
     * Display the destination form.
     */
    public function edit(Request $request): View
    {
        return view('destination.edit');
    }

    /**
     * Update the destination information.
     */
    public function update(): RedirectResponse
    {
        
    }

    /**
     * Delete the destination.
     */
    public function destroy(Request $request): RedirectResponse
    {
        
    }
}
