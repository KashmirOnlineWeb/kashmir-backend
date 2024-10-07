<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
                            'name',
                            'image',
                            'image_alt',
                            'status',
                            'slug',
                            'min_price',
                            'meta_id'
                        ];

    /* Get count of packages */                        
    public function packages()
    {
        return $this->hasMany(Package::class);
    }                        
}
