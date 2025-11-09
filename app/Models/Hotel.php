<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [ 'name',
                            'slug',
                            'amenities',
                            'balcony',
                            'breakfast',
                            'contact',
                            'content',
                            'image',
                            'image_alt',
                            'gallery_images',
                            'location',
                            'min_price',
                            'max_price',
                            'rooms',
                            'star',
                            'tax',
                            'total_lobbys',
                            'total_rooms',
                            'total_washrooms',
                            'highlights_content',
                            'status',
                            'meta_id',
                            'city_id'
                        ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function meta()
    {
        return $this->belongsTo(Meta::class);
    }       
}
