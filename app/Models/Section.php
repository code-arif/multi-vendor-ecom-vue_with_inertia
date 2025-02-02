<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'sec_name',
        'status'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
