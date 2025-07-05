<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Landboard extends Model
{
    protected $fillable = [
        'account_id',
        'name',
        'phone',
        'kost_name',
        'province',
        'city',
        'postal_code',
        'full_address',
        'late_fee_amount',
        'late_fee_days',
        'moveout_penalty_amount',
        'room_change_penalty_amount',
        'is_penalty_enabled',
        'is_penalty_on_moveout',
        'is_penalty_on_room_change',
        'decision_days_before_end',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

}
