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
        Schema::table('care_takers', function (Blueprint $table) {
            $table->string('otp_number')->nullable();
            $table->string('otp_verify')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('care_takers', function (Blueprint $table) {
            $table->dropColumn('otp_number');
            $table->dropColumn('otp_verify');
        });
    }
};
