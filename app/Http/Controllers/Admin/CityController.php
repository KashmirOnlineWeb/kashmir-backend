<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CityController extends Controller
{
    /**
     * Display list of cities
     */
    public function index()
    {
        return view('city.index');
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
