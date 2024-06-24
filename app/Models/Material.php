<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_category_id',
        'name',
    ];

    public function scopeFilterByQuery($q, array $filters)
    {
        return $q->when(isset($filters['material_category_id']), function ($q) use ($filters) {
            $q->where('material_category_id', $filters['material_category_id']);
        })
        ->when(isset($filters['name']), function ($q) use ($filters) {
            $q->where('name', 'like', '%'.$filters['name'].'%');
        })
        ->orderBy($filters['sort_by'] ?? 'name', $filters['sort_direction'] ?? 'ASC');
    }

    public function category()
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
