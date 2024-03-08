<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'request_detail_id',
        'quantity',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function requestDetail()
    {
        return $this->belongsTo(RequestDetail::class);
    }
}
