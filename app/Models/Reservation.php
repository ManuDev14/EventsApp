<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'room_id',
        'event_date',
        'start_time',
        'end_time',
        'status',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
