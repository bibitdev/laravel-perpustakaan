<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'user_id', 'book_id', 'borrowed_date', 'due_date',
        'returned_date', 'status', 'fine_amount', 'notes', 'approved_by'
    ];

    protected $casts = [
        'borrowed_date' => 'date',
        'due_date' => 'date',
        'returned_date' => 'date',
        'fine_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($borrowing) {
            $borrowing->code = 'BRW-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isOverdue()
    {
        return $this->status === 'borrowed' && Carbon::now()->gt($this->due_date);
    }

    public function getDaysOverdueAttribute()
    {
        if ($this->status === 'borrowed' && Carbon::now()->gt($this->due_date)) {
            return Carbon::now()->diffInDays($this->due_date);
        }
        return 0;
    }

    public function calculateFine()
    {
        if ($this->isOverdue()) {
            return $this->days_overdue * 1000; // Rp 1.000 per hari
        }
        return 0;
    }
}
