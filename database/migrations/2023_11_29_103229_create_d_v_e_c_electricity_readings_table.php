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
        Schema::create('d_v_e_c_electricity_readings', function (Blueprint $table) {
            $table->id();
            $table->date('transmit_date')->nullable();
            $table->unsignedBigInteger('previous_readings')->nullable();
            $table->unsignedBigInteger('new_readings')->nullable();
            $table->bigInteger('difference')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('d_v_e_c_electricity_readings');
    }
};
