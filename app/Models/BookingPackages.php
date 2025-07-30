<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPackages extends Model
{
    use HasFactory;

    protected $table   = 'booking_packages';
    protected $guarded = [];

    /*
    * Get category.
    **/
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /*
    * Get destination.
    **/
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

}
