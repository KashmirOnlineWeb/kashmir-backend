<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\City;

class PageController extends Controller
{
    public function index()
    {
        //$pages = Page::all();
        // add some dummy collection data
        $pages = collect([
            (object)['id' => 1, 'name' => 'Page 1', 'slug' => 'page-1', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            (object)['id' => 2, 'name' => 'Page 2', 'slug' => 'page-2', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            (object)['id' => 3, 'name' => 'Page 3', 'slug' => 'page-3', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $perPage = 10; // Number of items per page
        $currentPage = request()->get('page', 1); // Get the current page from the request, default to 1
        $pagedData = $pages->forPage($currentPage, $perPage);

        $pages = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedData,
            $pages->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('Page.index', compact('pages'));
    }

    public function create()
    {
        return view('Page.edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'nullable|string',
            'slug' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'status'    => 'required|integer|digits_between:0,1',
        ]);

        //ShoppingPlace::create($request->all());

        return redirect()->route('page.index')->with('success', 'Page created successfully');
    }

    public function edit(Request $request, $slug)
    {
        try {

            $cities = City::select('id','name')->get();
            $page   = Page::where('slug', $slug)->get();
            $meta   = [];

            if((isset($page->meta_id)) AND !empty($page->meta_id)){
                $meta = Meta::findOrFail($page->meta_id);    
            }
            
            return view('Page.edit',compact('meta','page'));

        } catch (Exception $e) {
            Log::error('Somethinng went wrong in page edit.');
        }
    }

    public function update(Request $request, Page $page)
    {
        return redirect()->route('page.index')->with('success', 'Page updated successfully');
    }

    public function destroy(ShoppingPlace $shoppingPlace)
    {
        $page->delete();

        return redirect()->route('page.index')->with('success', 'Page deleted successfully');
    }
}
