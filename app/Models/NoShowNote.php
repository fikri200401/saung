<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Legacy Model - Tidak digunakan untuk sistem saung
 */
class NoShowNote extends Model
{
    use HasFactory;

    protected $table = 'no_show_notes'; // Tabel tidak ada di database
    
    // Model ini tidak digunakan lagi
}
