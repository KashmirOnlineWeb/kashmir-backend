<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
//use App\Models\ShoppingPlace;
use Illuminate\Http\Request;
use App\Models\City;

class ShoppingPlaceController extends Controller
{
    public function index()
    {
        //$shoppingPlaces = ShoppingPlace::all();
        // add some dummy collection data
        $shoppingPlaces = collect([
            (object)['id' => 1, 'name' => 'Shopping Place 1', 'city' => (object)['name' => 'City 1'], 'status' => 1, 'contact' => '1234567890', 'created_at' => now(), 'updated_at' => now()],
            (object)['id' => 2, 'name' => 'Shopping Place 2', 'city' => (object)['name' => 'City 2'], 'status' => 0, 'contact' => '1234567890', 'created_at' => now(), 'updated_at' => now()],
            (object)['id' => 3, 'name' => 'Shopping Place 3', 'city' => (object)['name' => 'City 3'], 'status' => 1, 'contact' => '1234567890', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $perPage = 10; // Number of items per page
        $currentPage = request()->get('page', 1); // Get the current page from the request, default to 1
        $pagedData = $shoppingPlaces->forPage($currentPage, $perPage);

        $shoppingPlaces = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedData,
            $shoppingPlaces->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('Shoppingplace.index', compact('shoppingPlaces'));
    }

    public function create()
    {
        $cities = City::all();
        return view('Shoppingplace.edit', compact('cities'));
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

        return redirect()->route('shoppingplace.index')->with('success', 'Shopping place created successfully');
    }

    public function edit(ShoppingPlace $shoppingPlace)
    {
        $cities = City::all();      
        return view('Shoppingplace.edit', compact('shoppingPlace','cities'));
    }

    public function update(Request $request, ShoppingPlace $shoppingPlace)
    {
        return redirect()->route('shoppingplace.index')->with('success', 'Shopping place updated successfully');
    }

    public function destroy(ShoppingPlace $shoppingPlace)
    {
        $shoppingPlace->delete();

        return redirect()->route('shoppingplace.index')->with('success', 'Shopping place deleted successfully');
    }
}
