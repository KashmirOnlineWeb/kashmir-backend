<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Booking;

class BookingController extends Controller
{
    /*
    * Get list of bookings 
    **/
    public function index(Request $request)
    {
        try {
            $bookings = Booking::with(['package:id,name','user:id,name'])
                            ->orderBy('id','desc')
                            ->paginate(12);
            
            return view('Booking.index')->with(['bookings' => $bookings]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in booking index.');
        }
    }
    /* End of index */
    
}
