<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'name',
        'email',
        'phone',
        'nisn',
        'year_in',
        'date_of_birth'
    ];
}
