<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'handler_id',
        'purpose',
    ];

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

    public function requestDetails()
    {
        return $this->hasMany(RequestDetail::class);
    }
}
