<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::paginate(10);
        return view('restaurant.index', compact('restaurants'));
    }
    public function create()
    {
        return view('restaurant.edit');
    }
    public function store(Request $request)
    {
        $restaurant = Restaurant::create($request->all());
        return redirect()->route('restaurant.index')->with('success', 'Restaurant created successfully');
    }
    public function edit(Restaurant $restaurant)
    {
        return view('restaurant.edit', compact('restaurant'));
    }
    public function update(Request $request, Restaurant $restaurant)
    {
        $restaurant->update($request->all());
        return redirect()->route('restaurant.index')->with('success', 'Restaurant updated successfully');
    }
}
