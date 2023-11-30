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
            $table->unsignedBigInteger('cold water previous readings');
            $table->unsignedBigInteger('cold water new readings');
            $table->unsignedBigInteger('hot water previous readings');
            $table->unsignedBigInteger('hot water new readings');
            $table->unsignedBigInteger('hot water difference');
            $table->unsignedBigInteger('cold water difference');
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
