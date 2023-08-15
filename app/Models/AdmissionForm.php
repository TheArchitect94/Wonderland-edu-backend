<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionForm extends Model
{
    use HasFactory;

    protected $table = 'admission_forms';

    protected $fillable = [
        'student_name',
        'previous_class',
        'previous_school',
        'apply_class',
        'religion',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'father_name',
        'father_cnic_no',
        'father_cell_no',
        'father_whatsapp_no',
        'father_email',
        'father_education',
        'father_occupation',
        'mother_name',
        'mother_cnic_no',
        'mother_cell_no',
        'mother_education',
        'mother_whatsapp_no',
        'mother_email',
        'mother_occupation',
        'guardian_name',
        'guardian_cnic_no',
        'guardian_cell_no',
        'guardian_whatsapp_no',
        'guardian_email',
        'guardian_education',
        'guardian_occupation',
        'address',
        'postal_code',
    ];

    // You can define additional methods or relationships here if needed
}
