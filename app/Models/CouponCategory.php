<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponCategory extends Model
{
    protected $fillable = [ 'category_id', 'coupon_id' ];
    protected $table = 'coupon_category';
}
