<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',   
        'title',   
        'status',  
        'content1',
        'content2',
        'content3',
        'meta_id'
    ];
}
