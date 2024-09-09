<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MetaController extends Controller
{
    /**
     * Display list of meta
     */
    public function index()
    {
        return view('meta.index');
    }

    /**
     * Display the meta form.
     */
    public function edit(Request $request): View
    {
        return view('meta.edit');
    }

    /**
     * Update the meta information.
     */
    public function update(): RedirectResponse
    {
        
    }

    /**
     * Delete the meta.
     */
    public function destroy(Request $request): RedirectResponse
    {
        
    }
}
