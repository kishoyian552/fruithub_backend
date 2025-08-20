<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category',
        'in_stock',  // snake_case
        'rating',
        'reviews'
    ];
}
