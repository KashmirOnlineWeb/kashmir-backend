<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Destination;
use App\Models\Meta;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/destination/{slug}', function (Request $request, $slug) {
    $destination = Destination::where('slug', $slug)->first();
    if ($destination) {
        $meta = Meta::find($destination->meta_id);
        $destination->meta = $meta;
    }
    return response()->json($destination);
});