<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
                            'name',
                            'slug',
                            'description',
                            'highlights_content',
                            'zip_code',
                            'image',
                            'image_alt',
                            'meta_id',
                            'time_to_visit'
                        ];
}
