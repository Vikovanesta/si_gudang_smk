<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'nip',
    ];

    public function scopeFilterByQuery($q, array $filters)
    {
        return $q->when(isset($filters['nip']), function ($q) use ($filters) {
            $q->where('nip', $filters['nip']);
        })
        ->when(isset($filters['name']), function ($q) use ($filters) {
            $q->where('name', 'like', "%{$filters['name']}%");
        })
        ->when(isset($filters['subject_id']), function ($q) use ($filters) {
            $q->whereHas('subjects', function ($q) use ($filters) {
                $q->where('subject_id', $filters['subject_id']);
            });
        })
        ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_direction'] ?? 'DESC');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(SchoolSubject::class);
    }
}
