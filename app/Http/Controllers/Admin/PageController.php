<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\City;
use App\Models\Meta;

class PageController extends Controller
{
    /**
     * Display list of pages
     */
    public function index()
    {
        try {
            $pages = Page::orderBy('id','desc')->paginate(12);
            return view('Page.index')->with(['pages' => $pages]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in Pages index.');
        }
    }

    /**
     * Create pages form.
     */
    public function create(Request $request): View
    {
        $cities = City::select('id','name')->get();
        return view('Page.edit',compact('cities'));
    }

    /**
     * Store page form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->validate([
                            'name'              => 'required|string',
                            'slug'              => 'required|string',
                            'title'             => 'sometimes|string',
                            'status'            => 'required|integer|digits_between:0,1',
                            'city_id'           => 'required|integer|exists:cities,id',
                            'content1'          => 'sometimes|array',
                            'content2'          => 'sometimes|array',
                            'content3'          => 'sometimes|array',
                            'meta_title'        => 'required|string|nullable',
                            'meta_description'  => 'required|string|nullable',
                            'keywords'          => 'required|string|nullable'
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

            $response = Page::create([
                                        'name'    => $data['name'],
                                        'slug'    => $data['slug'],
                                        'title'   => (isset($data['title']) ? $data['title']: NULL),
                                        'status'  => $data['status'],
                                        'content1'=> (isset($data['content1']) ? json_encode($data['content1']): NULL),
                                        'content2'=> (isset($data['content2']) ? json_encode($data['content2']): NULL),
                                        'content3'=> (isset($data['content3']) ? json_encode($data['content3']): NULL),
                                        'meta_id' => $meta->id,
                                    ]);

            return Redirect::route('page.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in page store.');
        }
    }

    /**
     * Display the page form.
     */
    public function edit(Request $request, $id)
    {
        try {
            $cities = City::select('id','name')->get();
            $page   = Page::findOrFail($id);
            $meta   = [];

            if((isset($page->meta_id)) AND !empty($page->meta_id)){
                $meta = Meta::findOrFail($page->meta_id);    
            }

            $page->content1 = json_decode($page->content1);
            $page->content2 = json_decode($page->content2);
            $page->content3 = json_decode($page->content3);
            
            return view('Page.edit',compact('meta','page'));

        } catch (Exception $e) {
            Log::error('Somethinng went wrong in page edit.');
        }
    }

    /**
     * Update the page.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['page_id' => $id]);
            
            $request->validate([
                            'page_id'           => 'required|integer|exists:pages,id',
                            'name'              => 'required|string',
                            'slug'              => 'required|string',
                            'title'             => 'sometimes|string',
                            'status'            => 'required|integer|digits_between:0,1',
                            'city_id'           => 'required|integer|exists:cities,id',
                            'content1'          => 'sometimes|array',
                            'content2'          => 'sometimes|array',
                            'content3'          => 'sometimes|array',
                            'meta_title'        => 'required|string|nullable',
                            'meta_description'  => 'required|string|nullable',
                            'keywords'          => 'required|string|nullable'
                        ]);

            $page = Page::find($id);

            /* Insert Meta */
            if((!empty($data['meta_title'])) || (!empty($data['meta_description'])) || (!empty($data['keywords']))){
                $meta = Meta::where('id',$page->meta_id)
                            ->update([
                                'meta_title'       => $data['meta_title'],
                                'meta_description' => $data['meta_description'],
                                'keywords'         => $data['keywords'],
                                'status'           => 1,
                            ]);
            }
            
            $response = Page::where('id', $page->id)
                                ->update([
                                        'name'    => $data['name'],
                                        'slug'    => $data['slug'],
                                        'title'   => (isset($data['title']) ? $data['title']: NULL),
                                        'status'  => $data['status'],
                                        'content1'=> (isset($data['content1']) ? json_encode($data['content1']): NULL),
                                        'content2'=> (isset($data['content2']) ? json_encode($data['content2']): NULL),
                                        'content3'=> (isset($data['content3']) ? json_encode($data['content3']): NULL),
                                        'meta_id' => $page->meta_id,
                                    ]);


            return Redirect::route('page.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in page update.');
        }
    }

    /**
     * Delete the page.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['page_id' => $id]);
                
            $request->validate(['page_id' => 'required|integer|exists:pages,id']);
            $page = Page::find($id);
            if(!empty($page->meta_id)){
                Meta::destroy($page->meta_id);
            }
            $response = $page->destroy($id);
            return Redirect::route('page.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in page destroy.');
        }
    }
}
