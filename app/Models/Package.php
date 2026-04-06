<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'thumbnail',
        'benefit',
        'price',
        'max_pax',
        'extra_price_per_pax',
        'duration_minutes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'max_pax' => 'integer',
        'extra_price_per_pax' => 'integer',
    ];

    // --- TAMBAHKAN RELASI INI ---
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}