<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display list of categories
     */
    public function index()
    {
        try {
            $categories = Category::orderBy('id','desc')->paginate(12);
            return view('category.index')->with(['categories' => $categories]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in category index.');
        }
    }

    /**
     * Create category form.
     */
    public function create(Request $request): View
    {
        return view('category.edit');
    }

    /**
     * Store category form.
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

            $response = Category::create([
                                        'name'      => $data['name'],
                                        'image'     => $data['image'],
                                        'image_alt' => $data['image_alt'],
                                        'status'    => $data['status'],
                                        'slug'      => $data['slug'],
                                        'min_price' => $data['min_price']
                                    ]);

            return Redirect::route('category.edit',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in category store.');
        }
    }

    /**
     * Display the category form.
     */
    public function edit(Request $request, $id): View
    {
        $category  = Category::findOrFail($id);
        return view('Category.edit',compact('category'));
    }

    /**
     * Update the category information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['category_id' => $id]);
            
            $request->validate([
                            'category_id' => 'required|integer|exists:categories,id',
                            'name'        => 'required|string',
                            'status'      => 'required|integer|digits_between:0,1',
                            'slug'        => 'required|string',
                            'min_price'   => 'numeric',
                        ]);

            $category = Category::find($id);
            
            $response = Category::where('id', $category->id)
                                ->update([
                                        'name'      => $data['name'],
                                        'image'     => $data['image'],
                                        'image_alt' => $data['image_alt'],
                                        'status'    => $data['status'],
                                        'slug'      => $data['slug'],
                                        'min_price' => $data['min_price']
                                    ]);


            return Redirect::route('category.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in category update.');
        }
    }

    /**
     * Delete the category.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['category_id' => $id]);
                
            $request->validate(['category_id' => 'required|integer|exists:categories,id']);
            $category = Category::find($id);
            
            $response = $category->destroy($id);
            return Redirect::route('category.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in category destroy.');
        }
    }
}
