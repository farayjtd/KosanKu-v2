<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'room_id',
        'start_date',
        'end_date',
        'duration_months',
        'status',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function getCalculatedEndDateAttribute()
    {
        if ($this->end_date) {
            return $this->end_date;
        }

        return Carbon::parse($this->start_date)->addMonths($this->duration_months)->format('Y-m-d');
    }

    public function getIsRenewalRequestAttribute()
    {
        return $this->decision_status === 'extend' && $this->approval_status === 'pending';
    }

    public function getIsApprovedAttribute()
    {
        return $this->approval_status === 'approved';
    }

    public function getIsRejectedAttribute()
    {
        return $this->approval_status === 'rejected';
    }

    public function getIsPendingApprovalAttribute()
    {
        return $this->approval_status === 'pending';
    }
}
