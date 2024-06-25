<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function scopeFilterByQuery($query, $filters)
    {
        return $query->when($filters['name'] ?? null, function ($query, $name) {
            return $query->where('name', 'like', "%$name%");
        })
        ->when($filters['q'] ?? null, function ($query, $q) {
            return $query->where('name', 'like', "%$q%");
        })
        ->orderBy($filters['sort_by'] ?? 'name', $filters['sort_direction'] ?? 'asc');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
