<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display list of categories
     */
    public function index()
    {
        return view('category.index');
    }

    /**
     * Display the category form.
     */
    public function edit(Request $request): View
    {
        return view('category.edit');
    }

    /**
     * Update the category information.
     */
    public function update(): RedirectResponse
    {
        
    }

    /**
     * Delete the category.
     */
    public function destroy(Request $request): RedirectResponse
    {
        
    }
}
