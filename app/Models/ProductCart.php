<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCart extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'qty',
        'price',
        'color',
        'size',
        'subtotal'
    ];
}
