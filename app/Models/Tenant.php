<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'room_id',
        'status',
        'name',
        'phone',
        'address',
        'gender',
        'activity_type',
        'institution_name',
        'identity_photo',
        'selfie_photo',
    ];



    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function rentalHistories()
    {
        return $this->hasMany(RentalHistory::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }

    public function landboard()
     {
        return $this->belongsTo(Landboard::class);
    }
}
