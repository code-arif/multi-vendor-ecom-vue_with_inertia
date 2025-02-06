<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDetails extends Model
{
    protected $fillable = [
        'product_id',
        'extra_images',
        'long_description',
        'videos',
        'policies'
    ];
}
