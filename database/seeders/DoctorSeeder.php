<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Doctor 1
        Doctor::create([
            'name' => 'Dr. Sarah Wijaya',
            'specialization' => 'Dermatologi Estetik',
            'phone' => '081234567800',
            'email' => 'sarah@klinik.com',
            'bio' => 'Spesialis kulit dengan pengalaman 10 tahun di bidang dermatologi estetik',
        ]);

        // Doctor 2
        Doctor::create([
            'name' => 'Dr. Amanda Putri',
            'specialization' => 'Aesthetic Medicine',
            'phone' => '081234567801',
            'email' => 'amanda@klinik.com',
            'bio' => 'Dokter estetik bersertifikat internasional',
        ]);

        // Doctor 3
        Doctor::create([
            'name' => 'Dr. Lisa Hernandez',
            'specialization' => 'Skin Care Specialist',
            'phone' => '081234567802',
            'email' => 'lisa@klinik.com',
            'bio' => 'Ahli perawatan kulit dengan fokus pada anti-aging',
        ]);

        $this->command->info('✓ Created ' . Doctor::count() . ' doctors (legacy data untuk backward compatibility)');
    }
}
