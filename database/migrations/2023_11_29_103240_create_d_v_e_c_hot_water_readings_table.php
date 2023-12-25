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
        Schema::create('d_v_e_c_hot_water_readings', function (Blueprint $table) {
            $table->id();
            $table->date('transmit_date')->nullable();
            $table->unsignedDecimal('previous_readings', 10, 3)->nullable();
            $table->unsignedDecimal('new_readings', 10, 3)->nullable();
            $table->decimal('difference', 10, 3)->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('d_v_e_c_hot_water_readings');
    }
};
