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
}
