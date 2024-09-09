<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationController extends Controller
{
    /**
     * Display list of destinations
     */
    public function index()
    {
        return view('destination.index');
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
