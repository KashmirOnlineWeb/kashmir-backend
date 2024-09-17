<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingPlace extends Model
{
    use HasFactory;

    protected $fillable = [
                            'name',
                            'title',
                            'city_id',
                            'meta_id',
                            'image',
                            'image_alt',
                            'repeater_content'
                        ];
}
