<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Saung;
use App\Models\SaungSchedule;

class SaungSeeder extends Seeder
{
    public function run(): void
    {
        $saungs = [
            [
                'name' => 'Saung Bambu 1',
                'capacity' => 10,
                'location' => 'Area Depan',
                'description' => 'Saung dengan pemandangan kolam, cocok untuk keluarga kecil',
                'price_per_hour' => 50000,
                'is_active' => true,
            ],
            [
                'name' => 'Saung Bambu 2',
                'capacity' => 15,
                'location' => 'Area Tengah',
                'description' => 'Saung luas dengan fasilitas kipas angin, cocok untuk acara keluarga',
                'price_per_hour' => 75000,
                'is_active' => true,
            ],
            [
                'name' => 'Saung Bambu 3',
                'capacity' => 20,
                'location' => 'Area Belakang',
                'description' => 'Saung premium dengan pemandangan sungai, cocok untuk gathering',
                'price_per_hour' => 100000,
                'is_active' => true,
            ],
            [
                'name' => 'Saung VIP',
                'capacity' => 30,
                'location' => 'Area VIP',
                'description' => 'Saung terbesar dengan fasilitas lengkap, AC, sound system',
                'price_per_hour' => 150000,
                'is_active' => true,
            ],
            [
                'name' => 'Saung Romantis',
                'capacity' => 6,
                'location' => 'Area Pojok',
                'description' => 'Saung kecil privat untuk pasangan atau keluarga kecil',
                'price_per_hour' => 60000,
                'is_active' => true,
            ],
        ];

        foreach ($saungs as $saungData) {
            $saung = Saung::create($saungData);

            // Buat jadwal untuk setiap hari (Senin - Minggu)
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            
            foreach ($days as $day) {
                SaungSchedule::create([
                    'saung_id' => $saung->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '21:00:00',
                    'is_active' => true,
                ]);
            }
        }
    }
}
