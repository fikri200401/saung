<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Legacy Model - Tidak digunakan untuk sistem saung
 * Sistem saung menggunakan model Reservation
 */
class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings'; // Tabel tidak ada di database
    
    // Model ini tidak digunakan lagi
    // Tetap ada untuk backward compatibility
}
