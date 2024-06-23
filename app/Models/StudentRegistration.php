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

    public function scopeFilterByQuery($q, array $filters)
    {
        return $q->when(isset($filters['is_verified']), function ($q) use ($filters) {
            $q->where('is_verified', $filters['is_verified']);
        })
        ->when(isset($filters['verifier_id']), function ($q) use ($filters) {
            $q->where('verifier_id', $filters['verifier_id']);
        })
        ->when(isset($filters['class_id']), function ($q) use ($filters) {
            $q->where('class_id', $filters['class_id']);
        })
        ->when(isset($filters['status']), function ($q) use ($filters) {
            if ($filters['status'] === 'Approved') {
                $q->where('is_verified', 1)->whereNotNull('verified_at');
            } else if ($filters['status'] === 'Rejected') {
                $q->where('is_verified', 0)->whereNotNull('verified_at');
            } else {
                $q->whereNull('verified_at');
            }
        })
        ->when(isset($filters['q']), function ($q) use ($filters) {
            $q->where(function ($q) use ($filters) {
                $q->where('id', $filters['q'])
                  ->orWhere('name', 'like', '%'.$filters['q'].'%')
                    ->orWhere('email', 'like', '%'.$filters['q'].'%')
                    ->orWhere('phone', 'like', '%'.$filters['q'].'%')
                    ->orWhere('nisn', 'like', '%'.$filters['q'].'%')
                    ->orWhere('year_in', 'like', '%'.$filters['q'].'%')
                    ->orWhereHas('schoolClass', function ($q) use ($filters) {
                        $q->where('name', 'like', '%'.$filters['q'].'%');
                    });
            });
        })
        ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_direction'] ?? 'DESC')
        ;
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }
}
