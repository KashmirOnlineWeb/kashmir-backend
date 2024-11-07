<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Menu;

class MenusController extends Controller
{
    /**
     * Display list of menus
     */
    public function index()
    {
        try {
            $menus = Menu::orderBy('id', 'desc')->paginate(12);
            return view('Menus.index')->with(['menus' => $menus]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in Menus index.');
        }
    }

    /**
     * Create menu form.
     */
    public function create(Request $request): View
    {
        $menuItems = collect([
            ...json_decode(Page::select('id', 'name', 'slug', 'title')
                ->orderBy('id', 'desc')
                ->get()),
            ...json_decode(Destination::select('id', 'name', 'slug', 'title')
                ->orderBy('id', 'desc')
                ->get())
        ]);
        return view('Menus.edit')->with('pages',$menuItems);
    }

    /**
     * Store menu form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();
            
            $request->validate([
                            'name' => 'required|string',
                            'slug' => 'required|string|unique:menus,slug,'.$data->slug,
                            'data' => 'sometimes|array',
                        ]);
            
            $response = Menu::create([
                                        'name' => $data['name'],
                                        'slug' => $data['slug'],
                                        'data' => (isset($data['data']) ? json_encode($data['data']) : NULL),
                                    ]);

            return Redirect::route('menus.index',$response->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in menu store.');
        }
    }

    /**
     * Display the menu form.
     */
    public function edit(Request $request, $id)
    {
        $pages = collect([
            ...json_decode(Page::select('id', 'name', 'slug', 'title')
                ->orderBy('id', 'desc')
                ->get()),
            ...json_decode(Destination::select('id', 'name', 'slug', 'title')
                ->orderBy('id', 'desc')
                ->get())
        ]);

        $menu  = Menu::findOrFail($id);
        $menu->data = json_decode($menu->data);
        
        return view('Menus.edit',compact('menu', 'pages'));
    }

    /**
     * Update the menu.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['menu_id' => $id]);
            
            $request->validate([
                            'menu_id' => 'required|integer|exists:menus,id',
                            'name'    => 'required|string',
                            'slug'    => 'required|string|unique:menus,slug,'.$data->slug,
                            'data'    => 'sometimes|array'
                        ]);

            $menu     = Menu::find($id);
            $response = Menu::where('id', $menu->id)
                                ->update([
                                        'name' => $data['name'],
                                        'slug' => $data['slug'],
                                        'data' => (isset($data['data']) ? json_encode($data['data']) : NULL),
                                    ]);

            return Redirect::route('menus.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in menu update.');
        }    
    }

    /**
     * Delete the menu.
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['menu_id' => $id]);
                
            $request->validate(['menu_id' => 'required|integer|exists:menus,id']);       
            
            $response = $menu->destroy($id);
            return Redirect::route('menus.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in menus destroy.');
        }
    }
}
