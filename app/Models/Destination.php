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
}
