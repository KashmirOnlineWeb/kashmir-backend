<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Pharmacy;
use App\Models\Meta;

class PharmacyController extends Controller
{
    /**
     * Display list of pharmacies
     */
    public function index()
    {
        try {
            $pharmacies = Pharmacy::orderBy('id','desc')->paginate(12);
            return view('Pharmacy.index')->with(['pharmacies' => $pharmacies]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in pharmacy index.');
        }
    }

    /**
     * Create pharmacy form.
     */
    public function create(Request $request): View
    {
        return view('Pharmacy.edit');
    }

    /**
     * Store pharmacy form.
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

            $response = Pharmacy::create([
                                        'name'      => $data['name'],
                                        'image'     => $data['image'],
                                        'image_alt' => $data['image_alt'],
                                        'status'    => $data['status'],
                                        'slug'      => $data['slug'],
                                        'min_price' => $data['min_price']
                                    ]);

            return Redirect::route('Pharmacy.edit',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in pharmacy store.');
        }
    }

    /**
     * Display the pharmacy form.
     */
    public function edit(Request $request): View
    {
        return view('Pharmacy.edit');
    }

    /**
     * Update the pharmacy information.
     */
    public function update(): RedirectResponse
    {
        
    }

    /**
     * Delete the pharmacy.
     */
    public function destroy(Request $request): RedirectResponse
    {
        
    }
}
