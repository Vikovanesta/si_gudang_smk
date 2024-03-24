<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'handler_id',
        'purpose',
    ];

    public function scopeFilterByQuery($q, array $filters)
    {
        return $q->when(isset($filters['status_id']), function ($q) use ($filters) {
            $q->where('status_id', $filters['status_id']);
        })->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_direction'] ?? 'DESC');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handler_id');
    }

    public function borrowing()
    {
        return $this->hasOne(Borrowing::class);
    }

    public function details()
    {
        return $this->hasMany(RequestDetail::class, 'request_id');
    }
}
