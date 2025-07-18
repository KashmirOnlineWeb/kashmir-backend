<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Payment;

class PaymentController extends Controller
{
    /*
    * Get list of payments.
    **/
    public function index(Request $request)
    {
        try {
            $select = [ 'id',
                        'payment_id',
                        'order_id',
                        'status',
                        'amount',
                        'method',
                        'email',
                        'created_at',
                        'updated_at'
                    ];

            $payments = Payment::select($select)
                            ->orderBy('id','desc')
                            ->paginate(12);
            
            return view('Payment.index')->with(['payments' => $payments]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in payment index.');
        }
    }
    /* End of index */

    /**
     * Display payment.
     */
    public function show(Request $request, $id)
    {
        $select = [ 'id',
                    'payment_id',
                    'order_id',
                    'status',
                    'method',
                    'last4',
                    'card_network',
                    'bank',
                    'wallet',
                    'vpa',
                    'amount',
                    'fee',
                    'tax',
                    'email',
                    'contact',
                    'description',
                    'created_at',
                    'updated_at'
                ];   
        $payment  = Payment::select($select)->findOrFail($id);
        
        return view('Payment.show',compact('payment'));
    }
}
