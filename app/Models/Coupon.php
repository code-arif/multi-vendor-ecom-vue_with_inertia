<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['vendor_id', 'user_id', 'category_id', 'coupon_code', 'coupon_type', 'coupon_discount', 'coupon_start_date', 'coupon_end_date', 'coupon_status', 'usage_limit', 'minimum_order_amount', 'used_count'];

    //relation with vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    // Define Many-to-Many Relationship
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'coupon_category');
    }
}
