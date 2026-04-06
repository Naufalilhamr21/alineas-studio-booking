<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioSchedule extends Model
{
   use HasFactory;

    protected $fillable = [
        'date', 'is_closed', 'open_time', 'close_time', 'reason'
    ];

    protected $casts = [
        'date' => 'date',
        'is_closed' => 'boolean',
    ];
}
