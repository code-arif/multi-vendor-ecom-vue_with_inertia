<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name', 'status', 'logo', 'description'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    //relation with product table
    public function products(){
        return $this->hasMany(Product::class);
    }
}
