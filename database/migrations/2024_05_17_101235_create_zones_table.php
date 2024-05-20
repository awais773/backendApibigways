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
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('vehicles_id')->nullable();
            $table->string('mid_name')->nullable();
            $table->string('mid_latitude')->nullable();
            $table->string('mid_longitude')->nullable();
            $table->unsignedBigInteger('pickup_points_id')->nullable();
            $table->string('pickup_time')->nullable();
            $table->string('return_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zones');
    }
};
