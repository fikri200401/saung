<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Legacy Model - Tidak digunakan untuk sistem saung
 */
class BeforeAfterPhoto extends Model
{
    use HasFactory;

    protected $table = 'before_after_photos'; // Tabel tidak ada di database
    
    // Model ini tidak digunakan lagi
}
