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
        'school_subject_id',
        'purpose',
        'is_revised'
    ];

    protected $casts = [
        'is_revised' => 'boolean'
    ];

    public function scopeFilterByQuery($q, array $filters)
    {
        return $q->when(isset($filters['status_id']), function ($q) use ($filters) {
            $q->whereHas('details', function ($q) use ($filters) {
                $q->where('status_id', $filters['status_id'])
                  ->where('id', function ($subQuery) {
                      $subQuery->select('id')
                               ->from('request_details')
                               ->whereColumn('borrowing_requests.id', 'request_details.request_id')
                               ->orderByDesc('id')
                               ->limit(1);
                  });
            });
        })
        ->when(isset($filters['sender_id']), function ($q) use ($filters) {
            $q->where('sender_id', $filters['sender_id']);
        })
        ->when(isset($filters['handler_id']), function ($q) use ($filters) {
            $q->where('handler_id', $filters['handler_id']);
        })
        ->when(isset($filters['school_subject_id']), function ($q) use ($filters) {
            $q->where('school_subject_id', $filters['school_subject_id']);
        })
        ->when(isset($filters['is_revised']), function ($q) use ($filters) {
            $q->where('is_revised', $filters['is_revised']);
        })
        ->when(isset($filters['item_id']), function ($q) use ($filters) {
            $q->whereHas('details', function ($q) use ($filters) {
                $q->whereHas('borrowedItems', function ($q) use ($filters) {
                    $q->where('item_id', $filters['item_id']);
                });
            });
        })
        ->when(isset($filters['start_date']), function ($q) use ($filters) {
            $q->whereHas('details', function ($q) use ($filters) {
                $q->where('start_date', '>=', $filters['start_date']);
            });
        })
        ->when(isset($filters['end_date']), function ($q) use ($filters) {
            $q->whereHas('details', function ($q) use ($filters) {
                $q->where('end_date', '<=', $filters['end_date']);
            });
        })
        ->when(isset($filters['q']), function ($q) use ($filters) {
            $q->where(function ($q) use ($filters) {
                $q->where('id', $filters['q'])
                  ->orWhereHas('sender', function ($q) use ($filters) {
                      $q->whereHas('student', function ($q) use ($filters) {
                          $q->where('name', 'like', '%'.$filters['q'].'%');
                      })->orWhereHas('teacher', function ($q) use ($filters) {
                          $q->where('name', 'like', '%'.$filters['q'].'%');
                      });
                  });
            });
        })
        ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_direction'] ?? 'DESC');
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

    public function details()
    {
        return $this->hasMany(RequestDetail::class, 'request_id');
    }

    public function schoolSubject()
    {
        return $this->belongsTo(SchoolSubject::class, 'school_subject_id');
    }
}
