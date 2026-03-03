<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'image_path',
        'caption',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    // Relasi: Setiap foto galeri mungkin milik satu paket tertentu
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}