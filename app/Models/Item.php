<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'material_id',
        'name',
        'stock',
        'max_stock',
    ];

    public function scopeFilterByQuery($q, array $filters)
    {
        return $q->when(isset($filters['warehouse_id']), function ($q) use ($filters) {
            $q->where('warehouse_id', $filters['warehouse_id']);
        })
        ->when(isset($filters['material_id']), function ($q) use ($filters) {
            $q->where('material_id', $filters['material_id']);
        })
        ->when(isset($filters['name']), function ($q) use ($filters) {
            $q->where('name', 'like', '%'.$filters['name'].'%');
        })
        ->when(isset($filters['min_current_stock']), function ($q) use ($filters) {
            $q->where('stock', '>=', $filters['min_current_stock']);
        })
        ->when(isset($filters['max_current_stock']), function ($q) use ($filters) {
            $q->where('stock', '<=', $filters['max_current_stock']);
        })
        ->when(isset($filters['max_stock']), function ($q) use ($filters) {
            $q->where('max_stock', $filters['max_stock']);
        })
        ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_direction'] ?? 'DESC');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function borrowedItems()
    {
        return $this->hasMany(BorrowedItem::class);
    }
}
