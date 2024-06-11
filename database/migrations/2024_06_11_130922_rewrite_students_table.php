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
            $table->string('national_highway')->nullable();
            $table->string('GT_road_tool_plaza')->nullable();
            $table->string('motorway_tool_plaza')->nullable();
            $table->string('other_expense')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('national_highway');
            $table->dropColumn('GT_road_tool_plaza');
            $table->dropColumn('motorway_tool_plaza');
            $table->dropColumn('other_expense');
        });
    }
};
