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
        Schema::create('admission_forms', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('previous_class');
            $table->string('previous_school');
            $table->string('apply_class');
            $table->string('religion');
            $table->string('gender');
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->string('father_name');
            $table->string('father_cnic_no');
            $table->string('father_cell_no');
            $table->string('father_whatsapp_no');
            $table->string('father_email')->nullable(); // Nullable for father_email
            $table->string('father_education');
            $table->string('father_occupation');
            $table->string('mother_name');
            $table->string('mother_cnic_no');
            $table->string('mother_cell_no');
            $table->string('mother_education');
            $table->string('mother_whatsapp_no');
            $table->string('mother_email')->nullable(); // Nullable for mother_email
            $table->string('mother_occupation')->nullable(); // Nullable for mother_occupation
            $table->string('guardian_name')->nullable(); // Nullable for guardian_name
            $table->string('guardian_cnic_no')->nullable(); // Nullable for guardian_cnic_no
            $table->string('guardian_cell_no')->nullable(); // Nullable for guardian_cell_no
            $table->string('guardian_whatsapp_no')->nullable(); // Nullable for guardian_whatsapp_no
            $table->string('guardian_email')->nullable(); // Nullable for guardian_email
            $table->string('guardian_education')->nullable(); // Nullable for guardian_education
            $table->string('guardian_occupation')->nullable(); // Nullable for guardian_occupation
            $table->text('address');
            $table->string('postal_code')->nullable(); // Nullable for postal_code
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_forms');
    }
};
