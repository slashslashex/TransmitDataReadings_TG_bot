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
        Schema::create('e_r_k_c_readings', function (Blueprint $table) {
            $table->id();
            $table->date('transmit_date')->nullable();
            $table->unsignedDecimal('cold_water_previous_readings', 10, 3)->nullable();
            $table->unsignedDecimal('cold_water_new_readings', 10, 3)->nullable();
            $table->unsignedDecimal('hot_water_previous_readings', 10, 3)->nullable();
            $table->unsignedDecimal('hot_water_new_readings', 10, 3)->nullable();
            $table->decimal('cold_water_difference', 10, 3)->nullable();
            $table->decimal('hot_water_difference', 10, 3)->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_r_k_c_readings');
    }
};
