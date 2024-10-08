<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
                            'name',
                            'package_content',
                            'price',
                            'slug',
                            'season',
                            'category_id',
                            'city_id',
                            'meta_id',
                            'accommodations',
                            'status',
                            'content',
                            'addons_editor',
                            'start_time',
                            'end_time',
                            'available_slots',
                            'budget_type',
                            'currency',
                            'destination_id',
                            'days',
                            'nights',
                            'exclusions_editor',
                            'faqs_content',
                            'highlight_editor',
                            'illusions_content',
                            'itenery_content',
                            'image',
                            'image_alt',
                            'image_gallery',
                            'hotel_star',
                            'max_capacity',
                        ];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }  
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }  
}
