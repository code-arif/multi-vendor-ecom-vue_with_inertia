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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('restrict');
            $table->string('email')->unique();
            $table->string('mobile');
            $table->string('password');
            $table->string('image')->nullable();
            $table->string('address')->nullable();
            $table->string('zip')->nullable();
            $table->tinyInteger('status');
            $table->enum('confirm', ['yes', 'no'])->default('no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
