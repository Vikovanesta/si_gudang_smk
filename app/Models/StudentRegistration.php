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
        'password',
        'phone',
        'nisn',
        'year_in',
        'date_of_birth',
        'is_verified',
        'verifier_id',
        'verified_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }
}
