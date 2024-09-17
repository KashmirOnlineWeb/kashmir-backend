<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'status',
        'google_map',
        'location',
        'working_hours',
        'contact',
        'city_id',
        'meta_id',
        'content',
        'image',
        'image_alt'
    ];
}
