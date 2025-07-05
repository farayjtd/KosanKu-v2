<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'rental_history_id',
        'type',
        'amount',
        'reason',
        'issued_at',
        'resolved',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'resolved' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function rentalHistory()
    {
        return $this->belongsTo(RentalHistory::class);
    }

    public function scopeUnresolved($query)
    {
        return $query->where('resolved', false);
    }
}
