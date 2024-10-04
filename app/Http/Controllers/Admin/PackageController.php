<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Package;
use App\Models\City;
use App\Models\Meta;

class PackageController extends Controller
{
    /**
     * Display list of packages
     */
    public function index()
    {
        try {
            $packages = Package::orderBy('id','desc')->paginate(12);
            return view('Package.index')->with(['packages' => $packages]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in Package index.');
        }
    }
}
