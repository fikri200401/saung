<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Legacy Model - Tidak digunakan untuk sistem saung
 * Saung menggunakan model Menu untuk produk/layanan
 */
class Treatment extends Model
{
    use HasFactory;

    protected $table = 'treatments'; // Tabel tidak ada di database
    
    // Model ini tidak digunakan lagi
}
