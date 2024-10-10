<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingSpot extends Model
{
    protected $fillable = [
        'plate_number', 'is_occupied', 'entry_time', 'exit_time'
    ];

    // Usar casting para fechas
    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
    ];
}

