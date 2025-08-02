<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Classes\ApiResponse;
use Illuminate\Validation\Rule;

use Razorpay\Api\Api;
use App\Models\Package;
use App\Models\Booking;
use App\Models\BookingPackages;
use App\Models\BookingHotels;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingEmail;
use App\Mail\BookingHotelEmail;


class PaymentController extends Controller
{
    protected $razorpay;

    function __construct()
    {
        $this->razorpay = new Api(config('settings.razorpay_key'), config('settings.razorpay_secret'));
    }

    /*
    * Create razorpay order.
    **/
    public function createRazorpayOrder(Request $request)
    {
        try {
            $data = $request->validate([
                                    'booking_type' => 'required|in:package,hotel',
                                    'package_id'   => 'required_if:booking_type,package|exists:packages,id',
                                    'hotel_id'     => 'required_if:booking_type,hotel|exists:hotels,id|nullable',
                                    'room_type'    => 'required_if:booking_type,hotel|string|nullable',
                                    'room_price'   => 'required_if:booking_type,hotel|numeric|nullable',
                                    'start_date'   => 'required_if:booking_type,hotel|date_format:Y-m-d|nullable',
                                    'end_date'     => 'required_if:booking_type,hotel|date_format:Y-m-d|nullable',
                                    'no_of_rooms'  => 'required_if:booking_type,hotel|integer|nullable',
                                    'amount'       => 'required|numeric',
                                    'adults'     => 'required|integer|min:0',
                                    'children'   => 'required|integer|min:0'
                                ]);

            /* Get user */
            $user = $request->user();

            /* If package is booked */
            if($data['booking_type'] == 'package'){
                /* Get package */
                $package = Package::where('id',$data['package_id'])->first();

                /* Create razorpay order */
                $order = $this->razorpay
                                ->order
                                ->create(array('amount'   => ($data['amount']*100),
                                               'currency' => 'INR',
                                               'notes'    => array('package_name'=> $package->name)
                                           )
                                );

                $booking = Booking::create([
                                        'package_id'        => $package->id,
                                        'user_id'           => $user->id,
                                        'razorpay_order_id' => $order['id'],
                                        'status'            => $order['status'],
                                        'booking_type'      => $data['booking_type'],
                                        'adults'            => $data['adults'],
                                        'children'          => $data['children'],
                                        'amount'            => $data['amount'],
                                        //'gst' => $package->id,
                                    ]);

                $booking_packages = BookingPackages::create([
                                        'booking_id'      => $booking->id,
                                        'package_id'      => $package->id,
                                        'name'            => $package->name,
                                        'package_content' => $package->package_content,
                                        'price'           => $package->price,
                                        'slug'            => $package->slug,
                                        'season'          => $package->season,
                                        'category_id'     => $package->category_id,
                                        'city_id'         => $package->city_id,
                                        
                                        'accommodations'  => $package->accommodations,
                                        'status'          => $package->status,
                                        'content'         => $package->content,
                                        
                                        'start_date'      => $package->start_date,
                                        'end_date'        => $package->end_date,
                                        'available_slots' => $package->available_slots,
                                        'budget_type'     => $package->budget_type,
                                        'currency'        => $package->currency,
                                        'destination_id'  => $package->destination_id,
                                        'days'            => $package->days,
                                        'nights'          => $package->nights,
                                        'itenery_content' => $package->itenery_content,
                                        'hotel_star'      => $package->hotel_star,
                                        'max_capacity'    => $package->max_capacity,
                                        'is_special'      => $package->is_special,
                                    ]);

                if($booking){
                    $mailData = ['name'    => (!empty($user->name))? $user->name : strtok($user->email, '@'),
                                 'message' => 'Your package has been booked.',
                                 'booking' => [ 'status'            => $booking->status,
                                                'adults'            => $booking->adults,
                                                'children'          => $booking->children,
                                                'amount'            => $booking->amount,
                                                'package_name'      => $package->name,
                                                'packageStartDate'  => $package->start_date,
                                                'packageEndDate'    => $package->end_date,
                                            ]
                            ];
                    Mail::to($user->email)->send(new BookingEmail($mailData));
                }

            } else if($data['booking_type'] == 'hotel'){
                /* Get hotel */
                $hotel = Hotel::where('id',$data['hotel_id'])->first();

                /* Set rooms json data according to booking */
                $bookingRoomsData = [];
                if(isset($hotel['rooms'])){
                    $roomsData = json_decode($hotel['rooms']);
                    if(in_array($data['room_type'], $roomsData)){

                    }
                }
                /** End of set rooms json data **/

                /* Create razorpay order */
                $order = $this->razorpay
                                ->order
                                ->create(array('amount'   => ($data['amount']*100),
                                               'currency' => 'INR',
                                               'notes'    => array('hotel_name'=> $hotel->name)
                                           )
                                );

                $booking = Booking::create([
                                        'hotel_id'          => $hotel->id,
                                        'user_id'           => $user->id,
                                        'razorpay_order_id' => $order['id'],
                                        'status'            => $order['status'],
                                        'booking_type'      => $data['booking_type'],

                                        'hotel_id'          => $data['hotel_id'],
                                        'rooms'             => $bookingRoomsData,
                                        'start_date'        => $data['start_date'],
                                        'end_date'          => $data['end_date'],
                                        'no_of_rooms'       => $data['no_of_rooms'],

                                        'adults'            => $data['adults'],
                                        'children'          => $data['children'],
                                        'amount'            => $data['amount'],
                                        //'gst' => $package->id,
                                    ]);

                $booking_hotels = BookingHotels::create([
                                        'booking_id'         => $booking->id,
                                        'hotel_id'           => $hotel->id,
                                        'name'               => $hotel->name,
                                        'slug'               => $slug->slug,
                                        'amenities'          => $hotel->amenities,
                                        'balcony'            => $hotel->balcony,
                                        'breakfast'          => $hotel->breakfast,
                                        'contact'            => $hotel->contact,
                                        'content'            => $hotel->content,
                                        'image'              => $hotel->image,
                                        'image_alt'          => $hotel->image_alt,
                                        'location'           => $hotel->location,
                                        'min_price'          => $hotel->min_price,
                                        'max_price'          => $hotel->max_price,
                                        'rooms'              => $hotel->rooms,
                                        'star'               => $hotel->star,
                                        'tax'                => $hotel->tax,
                                        'total_lobbys'       => $hotel->total_lobbys,
                                        'total_rooms'        => $hotel->total_rooms,
                                        'total_washrooms'    => $hotel->total_washrooms,
                                        'highlights_content' => $hotel->highlights_content,
                                        'status'             => $hotel->status,
                                        'city_id'            => $hotel->city_id,
                                    ]);

                if($booking){
                    $mailData = ['name'    => (!empty($user->name))? $user->name : strtok($user->email, '@'),
                                 'message' => 'Your hotel has been booked.',
                                 'booking' => [ 'status'     => $booking->status,
                                                'adults'     => $booking->adults,
                                                'children'   => $booking->children,
                                                'amount'     => $booking->amount,
                                                'hotel_name' => $hotel->name,
                                                'start_date' => $booking->start_date,
                                                'end_date'   => $booking->end_date,
                                            ]
                            ];
                    Mail::to($user->email)->send(new BookingHotelEmail($mailData));
                }
            }

            return ApiResponse::send(200, null, ['order_id' => $order['id']]);
        } catch (Exception $e) {
            return ApiResponse::send(400, 'Something went wrong.');
        }
    }
    /* End of create razorpay order */

    /*
    * On razorpay payment success.
    **/
    public function onSuccess(Request $request)
    {
        try {
            $data = $request->validate([
                                    'razorpay_payment_id' => 'required|unique:payments,payment_id',
                                    'razorpay_order_id'   => ['required'],
                                    'razorpay_signature'  => 'required',
                                ]);

            /* Verify payment signature */
            $verifyPaymentSignature = $this->razorpay
                                        ->utility
                                        ->verifyPaymentSignature(array('razorpay_order_id'   => $data['razorpay_order_id'],
                                                                        'razorpay_payment_id' => $data['razorpay_payment_id'],
                                                                        'razorpay_signature'  => $data['razorpay_signature']
                                                                ));

            if(is_null($verifyPaymentSignature)){
                $payment = $this->razorpay->payment->fetch($data['razorpay_payment_id']);
                
                if(($payment->captured == true)){
                    /* Create payment */
                    $paymentData = Payment::create([
                                            'payment_id'            => $payment->id,
                                            'order_id'              => $payment->order_id,
                                            'razorpay_signature_id' => $data['razorpay_signature'],
                                            'status'                => $payment->status,
                                            'captured'              => $payment->captured,

                                            'method'                => $payment->method,
                                            'card_id'               => (isset($payment->card_id) ? $payment->card_id : NULL),
                                            'card'                  => (isset($payment->card) ? json_encode($payment->card->toArray()) : NULL),
                                            'last4'                 => (isset($payment->card->last4) ? $payment->card->last4 : NULL),
                                            'card_network'          => (isset($payment->card->network) ? $payment->card->network : NULL),
                                            'bank'                  => $payment->bank,
                                            'wallet'                => $payment->wallet,
                                            'vpa'                   => $payment->vpa,

                                            'amount'                => ($payment->amount/100),
                                            'fee'                   => ($payment->fee/100),
                                            'tax'                   => ($payment->tax/100),

                                            'email'                 => $payment->email,
                                            'contact'               => $payment->contact,
                                            'notes'                 => (isset($payment->notes) ? json_encode($payment->notes->toArray()):NULL),
                                            'description'           => $payment->description,
                                            'currency'              => $payment->currency,
                                            'acquirer_data'         => (isset($payment->acquirer_data) ? json_encode($payment->acquirer_data->toArray()): NULL),
                                            'upi'                   => (isset($payment->upi) ? json_encode($payment->upi->toArray()) : NULL),
                                        ]);

                    /* Update booking */
                    Booking::where('razorpay_order_id', $payment->order_id)
                                ->update([
                                        'status' => Booking::PAID
                                    ]);
                    $booking = Booking::where('razorpay_order_id',$payment->order_id)
                                    ->get();                                
                }
                return ApiResponse::send(200, null, ['booking' => $booking,'payment_id' => $paymentData->id]);
            }
            
        } catch (Exception $e) {
            return ApiResponse::send(400, 'Something went wrong.');
        }
    }
    /*End of onSuccess.*/

    /*
    * On razorpay payment failed.
    **/
    public function onFailed(Request $request)
    {
        try {
            $data = $request->validate([
                                    'razorpay_payment_id' => 'required|unique:payments,payment_id',
                                    'razorpay_order_id'   => 'required'
                                ]);

            $payment = $this->razorpay->payment->fetch($data['razorpay_payment_id']);
                
            if(($payment->captured == false)){
                /* Create payment */
                $paymentData = Payment::create([
                                        'payment_id'            => $payment->id,
                                        'order_id'              => $payment->order_id,
                                        
                                        'status'                => $payment->status,
                                        'captured'              => $payment->captured,

                                        'method'                => $payment->method,
                                        'card_id'               => (isset($payment->card_id) ? $payment->card_id : NULL),
                                        'card'                  => (isset($payment->card) ? json_encode($payment->card->toArray()) : NULL),
                                        'last4'                 => (isset($payment->card->last4) ? $payment->card->last4 : NULL),
                                        'card_network'          => (isset($payment->card->network) ? $payment->card->network : NULL),
                                        'bank'                  => $payment->bank,
                                        'wallet'                => $payment->wallet,
                                        'vpa'                   => $payment->vpa,

                                        'amount'                => ($payment->amount/100),
                                        'fee'                   => ($payment->fee/100),
                                        'tax'                   => ($payment->tax/100),

                                        'email'                 => $payment->email,
                                        'contact'               => $payment->contact,
                                        'notes'                 => (isset($payment->notes) ? json_encode($payment->notes->toArray()):NULL),
                                        'description'           => $payment->error_description,
                                        'currency'              => $payment->currency,
                                        'acquirer_data'         => (isset($payment->acquirer_data) ? json_encode($payment->acquirer_data->toArray()): NULL),
                                        'upi'                   => (isset($payment->upi) ? json_encode($payment->upi->toArray()) : NULL),
                                    ]);
            }
            
            return ApiResponse::send(200, null, ['id' => $paymentData->id]);
        } catch (Exception $e) {
            return ApiResponse::send(400, 'Something went wrong.');
        }
    }
    /*End of onFailed.*/
}
