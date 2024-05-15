<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'class_id',
        'name',
        'nisn',
        'year_in',
        'date_of_birth',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function scopeFilterByQuery($q, array $filters)
    {
        return $q->when(isset($filters['class_id']), function ($q) use ($filters) {
            $q->where('class_id', $filters['class_id']);
        })
        ->when(isset($filters['nisn']), function ($q) use ($filters) {
            $q->where('nisn', $filters['nisn']);
        })
        ->when(isset($filters['min_year_in']), function ($q) use ($filters) {
            $q->where('year_in', '>=', $filters['min_year_in']);
        })
        ->when(isset($filters['max_year_in']), function ($q) use ($filters) {
            $q->where('year_in', '<=', $filters['max_year_in']);
        })
        ->when(isset($filters['min_date_of_birth']), function ($q) use ($filters) {
            $q->where('date_of_birth', '>=', $filters['min_date_of_birth']);
        })
        ->when(isset($filters['max_date_of_birth']), function ($q) use ($filters) {
            $q->where('date_of_birth', '<=', $filters['max_date_of_birth']);
        })
        ->when(isset($filters['name']), function ($q) use ($filters) {
            $q->where('name', 'like', "%{$filters['name']}%");
        })
        ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_direction'] ?? 'DESC');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
