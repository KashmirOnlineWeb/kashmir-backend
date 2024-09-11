<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DestinationController extends Controller
{
    /**
     * Display list of destinations
     */
    public function index()
    {
        // Static list of destinations with additional information
        $destinations = collect([
            (object) ['id' => 1, 'name' => 'Paris', 'type' => 1, 'city' => 'Paris', 'created_at' => '2023-01-01', 'updated_at' => '2023-01-10'],
            (object) ['id' => 2, 'name' => 'New York', 'type' => 2, 'city' => 'New York', 'created_at' => '2023-02-01', 'updated_at' => '2023-02-10'],
            (object) ['id' => 3, 'name' => 'Tokyo', 'type' => 1, 'city' => 'Tokyo', 'created_at' => '2023-03-01', 'updated_at' => '2023-03-10'],
            (object) ['id' => 4, 'name' => 'London', 'type' => 2, 'city' => 'London', 'created_at' => '2023-04-01', 'updated_at' => '2023-04-10'],
            (object) ['id' => 5, 'name' => 'Sydney', 'type' => 1, 'city' => 'Sydney', 'created_at' => '2023-05-01', 'updated_at' => '2023-05-10'],
            (object) ['id' => 6, 'name' => 'Rome', 'type' => 2, 'city' => 'Rome', 'created_at' => '2023-06-01', 'updated_at' => '2023-06-10'],
            (object) ['id' => 7, 'name' => 'Berlin', 'type' => 1, 'city' => 'Berlin', 'created_at' => '2023-07-01', 'updated_at' => '2023-07-10'],
            (object) ['id' => 8, 'name' => 'Moscow', 'type' => 2, 'city' => 'Moscow', 'created_at' => '2023-08-01', 'updated_at' => '2023-08-10'],
            (object) ['id' => 9, 'name' => 'Dubai', 'type' => 1, 'city' => 'Dubai', 'created_at' => '2023-09-01', 'updated_at' => '2023-09-10'],
            (object) ['id' => 10, 'name' => 'Singapore', 'type' => 2, 'city' => 'Singapore', 'created_at' => '2023-10-01', 'updated_at' => '2023-10-10'],
            (object) ['id' => 11, 'name' => 'Barcelona', 'type' => 1, 'city' => 'Barcelona', 'created_at' => '2023-11-01', 'updated_at' => '2023-11-10'],
            (object) ['id' => 12, 'name' => 'Los Angeles', 'type' => 2, 'city' => 'Los Angeles', 'created_at' => '2023-12-01', 'updated_at' => '2023-12-10'],
        ]);

        // Paginate the static list
        $perPage = 5;
        $page = request()->get('page', 1);
        $paginatedDestinations = new LengthAwarePaginator(
            $destinations->forPage($page, $perPage),
            $destinations->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('Destination.index', ['destinations' => $paginatedDestinations]);
    }

    /**
     * Display the destination form.
     */
    public function edit(Request $request): View
    {
        //$destination = Destination::find($request->id);
        $meta = (object) [
            'meta_title' => 'Sample Meta Title',
            'meta_description' => 'Sample Meta Description',
            'keywords' => 'sample, meta, keywords'
        ];

        return view('Destination.edit', compact( 'meta'));
    }

    /**
     * Create the destination information.
     */
    public function create(Request $request): View
    {
        $meta = (object) [
            'meta_title' => 'Sample Meta Title',
            'meta_description' => 'Sample Meta Description',
            'keywords' => 'sample, meta, keywords'
        ];

        return view('Destination.edit', compact('meta'));
    }

     /**
     * Store the destination information.
     */
    public function store(Request $request): View
    {
        return view('destination.store');
    }

    /**
     * Update the destination information.
     */
    public function update(): RedirectResponse
    {
        
    }

    /**
     * Delete the destination.
     */
    public function destroy(Request $request): RedirectResponse
    {
        
    }
}
