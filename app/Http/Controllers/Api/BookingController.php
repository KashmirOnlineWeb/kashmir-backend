<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Classes\ApiResponse;
use App\Models\Booking;

class BookingController extends Controller
{
    /*
    * Get bookings list by logged in user.
    **/
    public function index(Request $request)
    {
        try {
            $user     = $request->user();
            $bookings = Booking::where('user_id', $user->id)
                                ->with('package')
                                ->paginate(12);

            return ApiResponse::send(200, null, ['bookings' => $bookings]);
        } catch (Exception $e) {
            return ApiResponse::send(400, 'Something went wrong.');   
        }
    }

    /*
    * Get single booking by booking id.
    **/
    public function getBookingByID(Request $request, $booking_id = NULL)
    {
        try {
            $request->merge(['booking_id' => $booking_id]);
            $data     = $request->validate(['booking_id' => 'required|exists:bookings,id']);
            $user     = $request->user();
            $booking  = Booking::where('user_id', $user->id)
                            ->where('id', $data['booking_id'])
                            ->with('package')
                            ->get();

            return ApiResponse::send(200, null, ['booking' => $booking]);
        } catch (Exception $e) {
            return ApiResponse::send(400, 'Something went wrong.');   
        }
    }
}
