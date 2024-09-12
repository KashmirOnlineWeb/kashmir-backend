<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Hospital;
use App\Models\Meta;

class HospitalController extends Controller
{
    /**
     * Display list of hospitals
     */
    public function index()
    {
        try {
            $hospitals = Hospital::orderBy('id','desc')->paginate(12);
            return view('Hospital.index')->with(['hospitals' => $hospitals]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in Hospital index.');
        }
    }

    /**
     * Create Hospital form.
     */
    public function create(Request $request): View
    {
        return view('Hospital.edit');
    }

    /**
     * Store Hospital form.
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

            $response = Hospital::create([
                                        'name'      => $data['name'],
                                        'image'     => $data['image'],
                                        'image_alt' => $data['image_alt'],
                                        'status'    => $data['status'],
                                        'slug'      => $data['slug'],
                                        'min_price' => $data['min_price']
                                    ]);

            return Redirect::route('Hospital.edit',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in Hospital store.');
        }
    }

    /**
     * Display the Hospital form.
     */
    public function edit(Request $request): View
    {
        return view('Hospital.edit');
    }

    /**
     * Update the Hospital information.
     */
    public function update(): RedirectResponse
    {
        
    }

    /**
     * Delete the Hospital.
     */
    public function destroy(Request $request): RedirectResponse
    {
        
    }
}
