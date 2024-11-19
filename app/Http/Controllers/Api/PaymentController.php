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
use App\Models\Payment;


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
                                    'package_id' => 'required|exists:packages,id',
                                    'amount'     => 'required|numeric',
                                    'adults'     => 'required|integer|min:0',
                                    'children'   => 'required|integer|min:0'
                                ]);

            /* Get package */
            $package = Package::where('id',$data['package_id'])->first();

            /* Get user */
            $user = $request->user();

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
                }
                
                return ApiResponse::send(200, null, ['id' => $paymentData->id]);
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
