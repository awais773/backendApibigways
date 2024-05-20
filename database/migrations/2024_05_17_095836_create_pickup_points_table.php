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
        Schema::create('pickup_points', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('pickup_name')->nullable();
            $table->string('pickup_longitude')->nullable();
            $table->string('pickup_latitude')->nullable();
            $table->string('drop_name')->nullable();
            $table->string('drop_longitude')->nullable();
            $table->string('drop_latitude')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_points');
    }
};
