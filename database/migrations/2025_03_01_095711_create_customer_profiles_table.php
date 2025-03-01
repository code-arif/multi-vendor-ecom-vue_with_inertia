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
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('cus_name',100);
            $table->string('cus_address',500);
            $table->string('cus_country',50);
            $table->string('cus_city',50);
            $table->string('cus_state',50)->nullable();
            $table->string('cus_zip', 20)->nullable();
            $table->string('cus_phone',20)->nullable();
            $table->string('cus_fax',50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_profiles');
    }
};
