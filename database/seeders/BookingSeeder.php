<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Legacy Seeder - Tidak digunakan untuk sistem saung
 * Saung menggunakan ReservationSeeder
 */
class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Seeder tidak digunakan - sistem saung menggunakan ReservationSeeder
        $this->command->info('⊗ BookingSeeder skipped (tidak digunakan untuk saung)');
    }
}
