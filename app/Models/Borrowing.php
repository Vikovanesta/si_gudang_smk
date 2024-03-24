<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'status_id',
        'quantity',
        'borrowed_at',
        'returned_at',
    ];

    public function scopeFilterByQuery($q, array $filters)
    {
        return $q->when(isset($filters['status_id']), function ($q) use ($filters) {
            $q->where('status_id', $filters['status_id']);
        })->orderBy($filters['sort_by'] ?? 'borrowed_at', $filters['sort_direction'] ?? 'DESC');
    }

    public function status()
    {
        return $this->belongsTo(BorrowingStatus::class);
    }

    public function request()
    {
        return $this->belongsTo(BorrowingRequest::class);
    }
}
