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

    public function status()
    {
        return $this->belongsTo(BorrowingStatus::class);
    }

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}
