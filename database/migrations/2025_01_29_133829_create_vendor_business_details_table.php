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
        Schema::create('vendor_business_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade')->onUpdate('restrict');
            $table->string('shop_name');
            $table->string('shop_address');
            $table->string('shop_zip');
            $table->string('shop_city')->nullable()->index();
            $table->string('shop_mobile')->nullable();
            $table->string('shop_email')->nullable();
            $table->string('shop_website')->nullable();
            $table->string('shop_pin');
            $table->string('shop_image')->nullable();
            $table->string('shop_license');
            $table->string('shop_pan');
            $table->string('shop_gst');
            $table->longText('shop_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_business_details');
    }
};
