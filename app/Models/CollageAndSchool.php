<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollageAndSchool extends Model
{
    use HasFactory;
    protected $table = 'collagesandschools';

    protected $fillable = [
                            'name',
                            'slug',
                            'status',
                            'address',
                            'city_id',
                            'meta_id',
                            'board',
                            'content',
                            'description',
                            'image',
                            'image_alt',
                            'website_url'
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
