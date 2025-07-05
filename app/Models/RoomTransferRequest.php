<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTransferRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 
        'current_room_id', 
        'new_room_id',
        'manual_refund', 
        'status', 
        'note'
    ];

    public function tenant() {
        return $this->belongsTo(Tenant::class);
    }

    public function currentRoom() {
        return $this->belongsTo(Room::class, 'current_room_id');
    }

    public function newRoom() {
        return $this->belongsTo(Room::class, 'new_room_id');
    }
}
