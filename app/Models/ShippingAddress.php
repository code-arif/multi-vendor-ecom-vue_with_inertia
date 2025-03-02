<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $fillable = [
        'user_id',
        'ship_name',
        'ship_add',
        'ship_city',
        'ship_state',
        'zip',
        'ship_country',
        'ship_phone',
    ];
}
