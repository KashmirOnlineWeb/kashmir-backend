<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\Meta;

class CategoryController extends Controller
{
    /**
     * Display list of categories
     */
    public function index()
    {
        try {
            $categories = Category::orderBy('id','desc')->paginate(12);
            return view('Category.index')->with(['categories' => $categories]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in category index.');
        }
    }

    /**
     * Create category form.
     */
    public function create(Request $request): View
    {
        return view('Category.edit');
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
                            //'status'    => 'required|integer|digits_between:0,1',
                            'slug'      => 'required|string',
                            'min_price' => 'numeric',
                        ]);
            /* Insert Meta */
            if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::create([
                            'meta_title'       => $data['meta_title'],
                            'meta_description' => $data['meta_description'],
                            'keywords'         => $data['keywords'],
                            'status'           => 1,
                        ]);
            }
            $response = Category::create([
                                        'name'      => $data['name'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        //'status'    => $data['status'],
                                        'slug'      => $data['slug'],
                                        'min_price' => isset($data['min_price']) ? $data['min_price'] : 0,
                                        'meta_id' => $meta->id,
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
        $meta               = [];
        if(!empty($category->meta_id)){
            $meta = Meta::findOrFail($category->meta_id);    
        }
        return view('Category.edit',compact('category','meta'));
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
                            //'status'      => 'required|integer|digits_between:0,1',
                            'slug'        => 'required|string',
                            'min_price'   => 'numeric',
                        ]);

            $category = Category::find($id);
            
            $response = Category::where('id', $category->id)
                                ->update([
                                        'name'      => $data['name'],
                                        'image'            => isset($data['image']) ? $data['image'] : NULL,
                                        'image_alt'        => isset($data['image_alt']) ? $data['image_alt'] : NULL,
                                        //'status'    => $data['status'],
                                        'slug'      => $data['slug'],
                                        'min_price' => isset($data['min_price']) ? $data['min_price'] : 0,
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
