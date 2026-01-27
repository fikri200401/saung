<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Legacy Model - Tidak digunakan untuk sistem saung
 * Saung menggunakan model SaungSchedule
 */
class DoctorSchedule extends Model
{
    use HasFactory;

    protected $table = 'doctor_schedules'; // Tabel tidak ada di database
    
    // Model ini tidak digunakan lagi
}
