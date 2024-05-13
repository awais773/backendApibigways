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
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('type')->after('amount')->nullable();
            $table->unsignedBigInteger('vehicle_id')->after('type')->nullable();
            $table->unsignedBigInteger('driver_id')->after('vehicle_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('vehicle_id');
            $table->dropColumn('driver_id');
        });
    }
};
