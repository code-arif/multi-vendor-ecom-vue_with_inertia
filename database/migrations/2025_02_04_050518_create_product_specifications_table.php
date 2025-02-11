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
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->string('material')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('volume', 10, 2)->nullable();
            $table->enum('weight_unit', ['kg', 'g', 'lb'])->default('kg');
            $table->enum('length_unit', ['cm', 'm', 'in', 'ft'])->default('cm');
            $table->enum('width_unit', ['cm', 'm', 'in', 'ft'])->default('cm');
            $table->enum('height_unit', ['cm', 'm', 'in', 'ft'])->default('cm');
            $table->enum('volume_unit', ['cm3', 'm3', 'L'])->default('cm3');
            $table->decimal('additional_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_specifications');
    }
};
