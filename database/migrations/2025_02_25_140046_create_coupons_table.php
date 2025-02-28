<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->string('coupon_code')->unique();
            $table->enum('coupon_type', ['fixed', 'percentage']);
            $table->decimal('coupon_discount', 8, 2);
            $table->date('coupon_start_date');
            $table->date('coupon_end_date');
            $table->boolean('coupon_status')->default(1);
            $table->integer('usage_limit')->nullable();
            $table->decimal('minimum_order_amount', 8, 2)->nullable();
            $table->integer('used_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
