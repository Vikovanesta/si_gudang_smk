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
        'returned_quantity',
        'borrowed_at',
        'returned_at',
        'is_cancelled',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function scopeFilterByQuery($q, array $filters)
    {
        return $q->when(isset($filters['request_detail_id']), function ($q) use ($filters) {
            $q->where('request_detail_id', $filters['request_detail_id']);
        })
        ->when(isset($filters['item_id']), function ($q) use ($filters) {
            $q->where('item_id', $filters['item_id']);
        })
        ->when(isset($filters['min_borrowed_at']), function ($q) use ($filters) {
            $q->whereDate('borrowed_at', '>=', $filters['min_borrowed_at']);
        })
        ->when(isset($filters['max_borrowed_at']), function ($q) use ($filters) {
            $q->whereDate('borrowed_at', '<=', $filters['max_borrowed_at']);
        })
        ->when(isset($filters['min_returned_at']), function ($q) use ($filters) {
            $q->whereDate('returned_at', '>=', $filters['min_returned_at']);
        })
        ->when(isset($filters['max_returned_at']), function ($q) use ($filters) {
            $q->whereDate('returned_at', '<=', $filters['max_returned_at']);
        })
        ->when(isset($filters['sender_id']), function ($q) use ($filters) {
            $q->whereHas('requestDetail.request', function ($q) use ($filters) {
                $q->where('sender_id', $filters['sender_id']);
            });
        })
        ->when(isset($filters['handler_id']), function ($q) use ($filters) {
            $q->whereHas('requestDetail.request', function ($q) use ($filters) {
                $q->where('handler_id', $filters['handler_id']);
            });
        })
        ->when(isset($filters['status']), function ($q) use ($filters) {
            $q->ofStatus($filters['status']);
        })
        ->when(isset($filters['request_status']), function ($q) use ($filters) {
            $q->ofRequestStatus($filters['request_status']);
        })
        ->when(isset($filters['q']), function ($q) use ($filters) {
            $q->whereHas('item', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['q'] . '%')
                ->orWhereHas('warehouse', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['q'] . '%');
                });
            })
            ->orWhereHas('requestDetail.request.sender', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['q'] . '%');
            })
            ->orWhere('quantity', 'like', '%' . $filters['q'] . '%')
            ->orWhere('id', 'like', '%' . $filters['q'] . '%');
        })
        ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_direction'] ?? 'DESC');
    }

    public function scopeOfStatus($q, $status)
    {
        if ($status == 'pending') {
            return $q->pending();
        }

        if ($status == 'borrowed') {
            return $q->borrowed();
        }

        if ($status == 'returned') {
            return $q->returned();
        }

        if ($status == 'cancelled') {
            return $q->cancelled();
        }
    }

    public function scopeOfRequestStatus($q, $status)
    {
        if ($status == 'approved') {
            return $q->approved();
        }

        if ($status == 'pending') {
            return $q->whereHas('requestDetail', function ($q) {
                $q->where('status_id', 1); // 'Pending'
            });
        }

        if ($status == 'rejected') {
            return $q->whereHas('requestDetail', function ($q) {
                $q->where('status_id', 3); // 'Rejected'
            });
        }
    }

    public function scopeApproved($q)
    {
        return $q->whereHas('requestDetail', function ($q) {
            $q->where('status_id', 2); // 'Approved'
        });
    }

    public function scopePending($q)
    {
        return $q->where('borrowed_at', null)->where('returned_at', null)->approved();
    }

    public function scopeBorrowed($q)
    {
        return $q->whereNotNull('borrowed_at')->where('returned_at', null);
    }

    public function scopeReturned($q)
    {
        return $q->whereNotNull('returned_at');
    }

    public function scopeCancelled($q)
    {
        return $q->where('is_cancelled', true);
    }

    public function scopeNotCancelled($q)
    {
        return $q->where('is_cancelled', false)->approved();
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function requestDetail()
    {
        return $this->belongsTo(RequestDetail::class);
    }

    public function status()
    {
        return $this->belongsTo(BorrowingStatus::class, 'status_id');
    }
}
