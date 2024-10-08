<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [ 'name',
                            'slug',
                            'title',
                            'short_description',
                            'description',
                            'highlights_content',
                            'image',
                            'image_alt',
                            'image_gallery',
                            'destination_type',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function meta()
    {
        return $this->belongsTo(Meta::class);
    }
    
    /* Get count of packages */                        
    public function packages()
    {
        return $this->hasMany(Package::class);
    }  
}
