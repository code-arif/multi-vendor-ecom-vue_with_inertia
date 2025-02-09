<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    protected $fillable = ['product_id', 'attribute', 'value', 'additional_price'];

    // ✅ One Specification belongs to One Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
