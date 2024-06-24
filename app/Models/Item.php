<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'warehouse_id',
        'material_id',
        'category_id',
        'name',
        'stock',
        'max_stock',
        'image',
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
        ->when(isset($filters['q']), function ($q) use ($filters) {
            $q->where(function ($q) use ($filters) {
                $q->where('id', $filters['q'])
                  ->orWhere('name', 'like', '%'.$filters['q'].'%')
                  ->orWhere('stock', 'like', '%'.$filters['q'].'%')
                  ->orWhereHas('warehouse', function ($q) use ($filters) {
                      $q->where('name', 'like', '%'.$filters['q'].'%');
                  })
                  ->orWhereHas('material', function ($q) use ($filters) {
                      $q->where('name', 'like', '%'.$filters['q'].'%');
                  })
                  ->orWhereHas('category', function ($q) use ($filters) {
                      $q->where('name', 'like', '%'.$filters['q'].'%');
                  });
            });
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

    public function category()
    {
        return $this->belongsTo(ItemCategory::class);
    }
}
