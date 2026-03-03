<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_price' => 'integer',
        'dp_amount' => 'integer',
        'remaining_balance' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    // Menambahkan 'timezone' otomatis untuk start_time
    public function getStartTimeWibAttribute()
    {
        return \Carbon\Carbon::parse($this->start_time)
                ->setTimezone('Asia/Jakarta')
                ->format('H:i');
    }

    // Menambahkan 'timezone' otomatis untuk end_time
    public function getEndTimeWibAttribute()
    {
        return \Carbon\Carbon::parse($this->end_time)
                ->setTimezone('Asia/Jakarta')
                ->format('H:i');
    }

    // Menambahkan 'timezone' otomatis untuk tanggal jika diperlukan
    public function getDateWibAttribute()
    {
        return \Carbon\Carbon::parse($this->start_time)
                ->setTimezone('Asia/Jakarta')
                ->format('d M Y');
    }
}
