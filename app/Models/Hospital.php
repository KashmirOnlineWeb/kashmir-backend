<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;
    protected $fillable = [
                            'name',
                            'slug',
                            'address',
                            'city_id',
                            'meta_id',
                            'contact',
                            'content',
                            'description',
                            'facilities',
                            'google_map',
                            'how_to_reach',
                            'image',
                            'image_alt',
                            'introduction',
                            'referral_system',
                            'trauma_services',
                            'website_url'
                        ];
}
