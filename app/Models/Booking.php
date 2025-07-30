<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    const CREATED   = 'created';
    const PAID      = 'paid';
    const CONFIRMED = 'confirmed';
    const CANCELLED = 'cancelled';
    const REFUNDED  = 'refunded';

    /* Get package */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /* Get user */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    * Get booked package.
    **/
    public function bookingPackages()
    {
        return $this->hasOne(BookingPackages::class);
    }

    /*
    * Get payment detail.
    **/
    public function payment()
    {
        return $this->hasOne(Payment::class,'order_id','razorpay_order_id');
    }
}
