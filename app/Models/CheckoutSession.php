<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutSession extends Model
{
    protected $fillable = [
        'user_id',
        'selected_cart_items',
        'total',
        'expires_at'
    ];
}
