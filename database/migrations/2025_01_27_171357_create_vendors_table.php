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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->index();
            $table->string('email', 255)->unique();
            $table->string('phone', 20);
            $table->string('address', 255);
            $table->string('city', 255)->index();
            $table->string('state', 255)->nullable();
            $table->string('country', 255);
            $table->string('zip', 20)->nullable();
            $table->string('pin', 20);
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
