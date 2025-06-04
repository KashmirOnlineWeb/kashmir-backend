<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\ApiResponse;

use App\Models\Package;

class PackageController extends Controller
{
    /**
     * Display list of packages
     */
    public function index()
    {
        try {
            $packages = Package::with(['city:id,name', 'destination:id,name,slug']) // Ensure destination relationship is included
                            ->select([ 'id',
                                      'destination_id',
                                      'name',
                                      'price',
                                      'image',
                                      'image_alt',
                                      'slug',
                                      'city_id',
                                      'days',
                                      'nights'
                                    ])
                                    ->paginate(12);
            // Prepare unique combinations of days and nights
            $uniqueTabs = collect($packages->items())->groupBy(function ($item) {
                return $item->days . '-' . $item->nights;
            })->map(function ($group) {
                $item = $group->first(); // Get the first item from the group
                return [
                    'days' => $item->days,
                    'nights' => $item->nights,
                    'label' => "{$item->nights}N - {$item->days}D"
                ];
            })->values()->all();

            return ApiResponse::send(200, null, ['packages' => $packages, 'tabs' => $uniqueTabs]);
        } catch (Exception $e) {
            return ApiResponse::send(400, 'Something went wrong.'); 
        }
    }
}
