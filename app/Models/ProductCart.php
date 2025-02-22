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

    //relation with product table
    public function products(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
