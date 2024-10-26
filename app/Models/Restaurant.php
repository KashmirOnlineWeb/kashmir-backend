<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
                            'name',
                            'status',
                            'description',
                            'restaurant_type',
                            'address',
                            'contact',
                            'google_map',
                            'slug',
                            'image',
                            'image_alt',
                            'city_id',
                            'meta_id'
                        ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
