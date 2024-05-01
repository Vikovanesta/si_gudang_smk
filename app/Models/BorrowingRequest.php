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
        'is_revised'
    ];

    protected $casts = [
        'is_revised' => 'boolean'
    ];

    public function scopeFilterByQuery($q, array $filters)
    {
        return $q->when(isset($filters['status_id']), function ($q) use ($filters) {
            $q->where('status_id', $filters['status_id']);
        })->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_direction'] ?? 'DESC');
    }

    public function isHandled()
    {
        if ($this->details->last()->status_id != 1)
        {
            return true;
        }
        return false;
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
