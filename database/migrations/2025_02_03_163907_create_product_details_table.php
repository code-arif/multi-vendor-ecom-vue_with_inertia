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
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->json('extra_images')->nullable(); // ["extra1.jpg", "extra2.jpg"]
            $table->text('long_description')->nullable();
            $table->json('videos')->nullable(); // ["video1.mp4", "youtube_link"]
            $table->json('policies')->nullable(); // {"warranty": "1 year", "return": "7 days"}
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
