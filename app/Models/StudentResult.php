<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResult extends Model
{
    use HasFactory;

    protected $table = 'studentresults';

    protected $fillable = [
        'class_name',
        'student_name',
        'total_marks',
    ];

    // Define a one-to-many relationship with subjects
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
