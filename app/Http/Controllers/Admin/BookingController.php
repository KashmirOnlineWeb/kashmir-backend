<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

use App\Models\Booking;

class BookingController extends Controller
{
    /*
    * Get list of bookings 
    **/
    public function index(Request $request)
    {
        try {
            $bookings = Booking::with(['bookingPackages:id,booking_id,name','user:id,name', 'bookingHotels:id,booking_id,name'])
                            ->orderBy('id','desc')
                            ->paginate(12);
            
            return view('Booking.index')->with(['bookings' => $bookings]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in booking index.');
        }
    }
    /* End of index */
    
    /*
    * Show booked booking.
    **/
    public function show(Request $request, $id): View
    {
        try {
            $booking = Booking::with(['bookingPackages' => function($booking){
                                                $booking->with(['category:id,name','destination:id,name']);
                                            },
                                        'bookingHotels', 
                                        'payment'
                                    ])
                                ->findOrFail($id);
            return view('Booking.show',compact('booking'));

        } catch (Exception $e) {
            Log::error('Somethinng went wrong in booking show.');   
        }
    }
}
