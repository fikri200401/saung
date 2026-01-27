<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Legacy Model - Tidak digunakan untuk sistem saung
 */
class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors'; // Tabel tidak ada di database
    
    // Model ini tidak digunakan lagi
}
