<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Legacy Seeder - Tidak digunakan untuk sistem saung
 */
class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        // Seeder tidak digunakan - sistem saung tidak menggunakan doctors
        $this->command->info('⊗ DoctorSeeder skipped (tidak digunakan untuk saung)');
    }
}
