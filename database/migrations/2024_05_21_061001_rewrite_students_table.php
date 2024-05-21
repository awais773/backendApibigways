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
            Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->string('amount')->nullable();
            $table->enum('payments_status', ['PAID', 'UNPAID'])->default('UNPAID');
            $table->enum('signed_status', ['SIGNED', 'UNSIGNED'])->default('UNSIGNED');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('vehicle_id');
            $table->dropColumn('zone_id');
            $table->dropColumn('school_id');
            $table->dropColumn('amount');
            $table->dropColumn('payments_status');
            $table->dropColumn('signed_status');
        });
    }
};
