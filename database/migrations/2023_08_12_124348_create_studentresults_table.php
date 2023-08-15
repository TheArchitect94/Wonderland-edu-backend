<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('studentresults', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->string('student_name');
            $table->string('subject')->nullable();
            $table->integer('marks')->default(0);
            $table->integer('total_marks')->default(0);
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('studentresults');
    }
};
