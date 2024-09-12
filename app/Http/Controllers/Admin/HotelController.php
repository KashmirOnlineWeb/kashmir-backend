<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Hotel;
use App\Models\Meta;

class HotelController extends Controller
{
    /**
     * Display list of hotels
     */
    public function index()
    {
        try {
            $hotels = Hotel::orderBy('id','desc')->paginate(12);
            return view('Hotel.index')->with(['hotels' => $hotels]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in hotel index.');
        }
    }

    /**
     * Create hotel form.
     */
    public function create(Request $request): View
    {
        return view('Hotel.edit');
    }

    /**
     * Store hotel form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->validate([
                            'name'      => 'required|string',
                            'status'    => 'required|integer|digits_between:0,1',
                            'slug'      => 'required|string',
                            'min_price' => 'numeric',
                        ]);

            $response = Hotel::create([
                                        'name'      => $data['name'],
                                        'image'     => $data['image'],
                                        'image_alt' => $data['image_alt'],
                                        'status'    => $data['status'],
                                        'slug'      => $data['slug'],
                                        'min_price' => $data['min_price']
                                    ]);

            return Redirect::route('Hotel.edit',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in hotel store.');
        }
    }

    /**
     * Display the hotel form.
     */
    public function edit(Request $request): View
    {
        return view('hotel.edit');
    }

    /**
     * Update the hotel information.
     */
    public function update(): RedirectResponse
    {
        
    }

    /**
     * Delete the hotel.
     */
    public function destroy(Request $request): RedirectResponse
    {
        
    }
}
