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
        Schema::create('studentresult', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->string('student_name');
            $table->string('subject');
            $table->string('marks');
            $table->string('total_marks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studentresult');
    }
};
