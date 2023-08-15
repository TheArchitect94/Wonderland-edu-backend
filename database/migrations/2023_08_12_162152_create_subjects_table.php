<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('subject_name');
            $table->integer('marks');
            $table->integer('total_marks');
            $table->unsignedBigInteger('student_result_id'); // Foreign key for student result
            $table->timestamps();

            // Define the foreign key relationship
            $table->foreign('student_result_id')
                  ->references('id')
                  ->on('studentresults')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subjects');
    }
}
