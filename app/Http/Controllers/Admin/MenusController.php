<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\City;
use App\Models\Meta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
//use App\Models\Menu;

class MenusController extends Controller
{
    /**
     * Display list of pages
     */
    public function index()
    {
        try {
            //$menus = Menu::orderBy('id', 'desc')->paginate(12);
            $menus = collect([
                [
                    'id' => 1,
                    'name' => 'Main Menu',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // [
                //     'id' => 2,
                //     'name' => 'Footer Menu',
                //     'created_at' => now(),
                //     'updated_at' => now(),
                // ],
                // [
                //     'id' => 3,
                //     'name' => 'Top Bar Menu',
                //     'created_at' => now(),
                //     'updated_at' => now(),
                // ],
                // [
                //     'id' => 4,
                //     'name' => 'Mobile Menu',
                //     'created_at' => now(),
                //     'updated_at' => now(),
                // ]
            ])->map(function ($menu) {
                return (object) $menu;
            });
            return view('Menus.index')->with(['menus' => $menus]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in Pages index.');
        }
    }

    /**
     * Create pages form.
     */
    public function create(Request $request): View
    {
        //$cities = City::select('id','name')->get();
        $pages = json_decode(Page::select('id', 'name', 'slug', 'title')
                ->orderBy('id', 'desc')
                ->get());
        return view('Menus.edit')->with('pages',$pages);
    }

    /**
     * Store page form.
     */
    public function store(Request $request): RedirectResponse
    {
        $menuItems = json_decode($request->menu_items, true);
        // $response = Page::create([
        //                                 'name'    => $data['name'],
        //                                 'slug'    => $data['slug'],
        //                                 'title'   => (isset($data['title']) ? $data['title']: NULL),
        //                                 //'status'  => $data['status'],
        //                                 'content1'=> (isset($data['content1']) ? json_encode($data['content1']): NULL),
        //                                 'content2'=> (isset($data['content2']) ? json_encode($data['content2']): NULL),
        //                                 'content3'=> (isset($data['content3']) ? json_encode($data['content3']): NULL),
        //                                 'meta_id' => $meta->id,
        //                             ]);

            return Redirect::route('page.index',$response->id);
        // } catch (Exception $e) {
            Log::error('Somethinng went wrong in page store.');
        //}
    }

    /**
     * Display the page form.
     */
    public function edit(Request $request, $id)
    {
        $pages = json_decode(Page::select('id', 'name', 'slug', 'title')
                ->orderBy('id', 'desc')
                ->get());
        return view('Menus.edit')->with('pages',$pages);
    }

    /**
     * Update the page.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $menuItems = json_decode($request->menu_items, true);
        
            
            // $response = Page::where('id', $page->id)
            //                     ->update([
            //                             'name'    => $data['name'],
            //                             'slug'    => $data['slug'],
            //                             'title'   => (isset($data['title']) ? $data['title']: NULL),
            //                             //'status'  => $data['status'],
            //                             'content1'=> (isset($data['content1']) ? json_encode($data['content1']): NULL),
            //                             'content2'=> (isset($data['content2']) ? json_encode($data['content2']): NULL),
            //                             'content3'=> (isset($data['content3']) ? json_encode($data['content3']): NULL),
            //                             'meta_id' => $page->meta_id,
            //                         ]);


            return Redirect::route('page.index',$response);
        // } catch (Exception $e) {
            Log::error('Somethinng went wrong in page update.');
        //}
    }

    /**
     * Delete the page.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        
    }
}
