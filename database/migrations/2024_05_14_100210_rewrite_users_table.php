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
            $table->string('other_expense')->nullable();
            $table->string('motorway_tool_plaza')->nullable();
            $table->string('GT_road_tool_plaza')->nullable();
            $table->string('national_highway')->nullable();
            $table->string('net_amount')->nullable();
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
            $table->dropColumn('other_expense');
            $table->dropColumn('motorway_tool_plaza');
            $table->dropColumn('GT_road_tool_plaza');
            $table->dropColumn('national_highway');
            $table->dropColumn('net_amount');
        });
    }
};
