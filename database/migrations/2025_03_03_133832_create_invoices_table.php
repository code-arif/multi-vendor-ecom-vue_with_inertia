<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('total');
            $table->string('vat')->nullable();
            $table->string('discount')->nullable();
            $table->string('shipping_charges')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('coupon_discount')->nullable();
            $table->string('grand_total')->nullable();
            $table->string('customer_details')->nullable();
            $table->string('shipping_details')->nullable();
            $table->string('transection_id')->nullable();
            $table->string('validation_id')->nullable();
            $table->enum('delivary_status',['pending', 'processing', 'delivered','cancelled'])->default('pending');
            $table->enum('payment_status',['pending', 'processing', 'paid','cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
