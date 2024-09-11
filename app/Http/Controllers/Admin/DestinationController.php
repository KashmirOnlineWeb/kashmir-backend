<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Destination;
use App\Models\Meta;
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
     * Store the destination form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->validate([
                            'name' => 'required|string',
                            'slug' => 'required|string',
                            'description' => 'required',
                            'highlights_content' => 'required',
                            //'title' => 'required|string',
                            'destination_type' => 'required|integer|min:1|digits_between:1,2',
                            //'meta_id' => 'required|string',
                        ]);
            
                        // save meta data
            $meta = Meta::create([
                'meta_title' => $data['meta_title'],
                'meta_description' => $data['meta_description'],
                'keywords' => $data['keywords'],
            ]);

            $result = Destination::create([
                            'name'              => $data['name'],
                            'slug'              => $data['slug'],
                            //'title'             => $data['title'],
                            //'short_description' => $data['short_description'],
                            'description'       => $data['description'],
                            'highlights_content' => $data['highlights_content'],
                            //'image'           => $data['image'],
                            'image_alt'         => $data['image_alt'],
                            //'image_gallery'   => $data['image_gallery'],
                            'destination_type'  => $data['destination_type'],
                            'meta_id'           => $meta->id,
                        ]);

            return Redirect::route('destination.edit',$result->id);

        } catch (Exception $e) {
            Log::error('Somethinng went wrong in destination store.');
        }
    }

    /**
     * Display the destination form.
     */
    public function edit(Request $request, $id): View
    {   
        $destination = Destination::findOrFail($id);
        $meta = Meta::findOrFail($destination->meta_id);

        return view('Destination.edit', compact('destination', 'meta'));
    }

    /**
     * Create the destination information.
     */
    public function create(Request $request): View
    {
        $meta = (object) [
            'meta_title' => '',
            'meta_description' => '',
            'keywords' => ''
        ];

        return view('Destination.edit', compact('meta'));
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
