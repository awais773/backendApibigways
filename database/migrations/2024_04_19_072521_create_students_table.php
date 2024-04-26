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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_name')->nullable();
            $table->string('school_name')->nullable();
            $table->text('notes')->nullable();
            $table->string('pickup_time')->nullable();
            $table->string('drop_time')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('status')->bydefault('unassigned')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
