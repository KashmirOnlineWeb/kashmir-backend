<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\CollageAndSchool;
use App\Models\Meta;

class CollageAndSchoolController extends Controller
{
    /**
     * Display list of CollagesAndSchools
     */
    public function index()
    {
        try {
            $CollagesAndSchools = CollageAndSchool::orderBy('id','desc')->paginate(12);
            return view('Collageandschool.index')->with(['CollagesAndSchools' => $CollagesAndSchools]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in CollageAndSchool index.');
        }
    }

    /**
     * Create Collageandschool form.
     */
    public function create(Request $request): View
    {
        return view('Collageandschool.edit');
    }

    /**
     * Store Collageandschool form.
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

            $response = CollageAndSchool::create([
                                        'name'      => $data['name'],
                                        'image'     => $data['image'],
                                        'image_alt' => $data['image_alt'],
                                        'status'    => $data['status'],
                                        'slug'      => $data['slug'],
                                        'min_price' => $data['min_price']
                                    ]);

            return Redirect::route('Collageandschool.edit',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in Collageandschool store.');
        }
    }

    /**
     * Display the Collageandschool form.
     */
    public function edit(Request $request): View
    {
        return view('Collageandschool.edit');
    }

    /**
     * Update the Collageandschool information.
     */
    public function update(): RedirectResponse
    {
        
    }

    /**
     * Delete the Collageandschool.
     */
    public function destroy(Request $request): RedirectResponse
    {
        
    }
}
