<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'vat',
        'discount',
        'shipping_charges',
        'coupon_code',
        'coupon_discount',
        'grand_total',
        'customer_details',
        'shipping_details',
        'transection_id',
        'validation_id',
        'delivary_status',
        'payment_status',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'vat' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_charges' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'customer_details' => 'array',
        'shipping_details' => 'array',
    ];


    //relation with invoice_product table
    public function invoice_products(){
        return $this->hasMany(InvoiceProduct::class,'invoice_id','id');
    }
}
