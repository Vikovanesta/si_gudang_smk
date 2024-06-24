<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function scopeFilterByQuery($query, $filters)
    {
        return $query->when(isset($filters['name']), function ($query) use ($filters) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        })
        ->when(isset($filters['item_id']), function ($query) use ($filters) {
            $query->whereHas('items', function ($query) use ($filters) {
                $query->where('id', $filters['item_id']);
            });
        })
        ->orderBy($filters['sort_by'] ?? 'name', $filters['sort_direction'] ?? 'ASC');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
