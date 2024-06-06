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
        Schema::table('users', function (Blueprint $table) {
            $table->string('proof_image')->nullable();
            $table->enum('payments_status', ['PAID', 'UNPAID'])->default('UNPAID');
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->string('pickup_time')->nullable();
            $table->string('dropoff_time')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->string('request_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('proof_image');
            $table->dropColumn('payments_status');
            $table->dropColumn('zone_id');
            $table->dropColumn('pickup_time');
            $table->dropColumn('dropoff_time');
            $table->dropColumn('school_id');
            $table->dropColumn('request_type');
        });
    }
};
