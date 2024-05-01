<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'status_id',
        'start_date',
        'end_date',
        'is_revision',
        'note',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_revision' => 'boolean',
    ];

    public function request()
    {
        return $this->belongsTo(BorrowingRequest::class, 'request_id');
    }

    public function borrowedItems()
    {
        return $this->hasMany(BorrowedItem::class);
    }

    public function status()
    {
        return $this->belongsTo(RequestStatus::class, 'status_id');
    }
}
