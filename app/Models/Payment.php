<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'rental_history_id',
        'amount',
        'description',
        'due_date',
        'paid_at',
        'status',
        'type',
        'payment_method',
        'is_penalty',
        'invoice_id',        
        'payment_url'      
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'is_penalty' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function rentalHistory()
    {
        return $this->belongsTo(RentalHistory::class);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function getIsOverdueAttribute()
    {
        return $this->status === 'unpaid' && now()->gt($this->due_date);
    }
}
