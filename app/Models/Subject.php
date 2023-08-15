<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_name',
        'marks',
        'total_marks',
    ];

    // No need to define relationships for the Subject model in this context.
    // The relationship is defined in the StudentResult model.

    // You can add other methods or attributes specific to the Subject model if needed.
}
