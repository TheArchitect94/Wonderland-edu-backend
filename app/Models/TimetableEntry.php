<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetableEntry extends Model
{
    use HasFactory;

protected $fillable = [
    'classname',
    'day',
    'start_time',
    'end_time',
    'subject',
];

}
