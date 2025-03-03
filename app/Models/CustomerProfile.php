<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    protected $fillable = ['user_id', 'cus_name', 'cus_address', 'cus_country', 'cus_city', 'cus_state', 'cus_zip', 'cus_phone', 'cus_fax'];

    //relation with user table
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
