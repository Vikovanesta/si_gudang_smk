<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboran extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'nip',
        'date_of_birth',
    ];

    public function scopeFilterByQuery($q, array $filters)
    {
        return $q->when(isset($filters['nip']), function ($q) use ($filters) {
            $q->where('nip', $filters['nip']);
        })
        ->when(isset($filters['name']), function ($q) use ($filters) {
            $q->where('name', 'like', "%{$filters['name']}%");
        })
        ->when(isset($filters['min_date_of_birth']), function ($q) use ($filters) {
            $q->where('date_of_birth', '>=', $filters['min_date_of_birth']);
        })
        ->when(isset($filters['max_date_of_birth']), function ($q) use ($filters) {
            $q->where('date_of_birth', '<=', $filters['max_date_of_birth']);
        })
        ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_direction'] ?? 'DESC');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
