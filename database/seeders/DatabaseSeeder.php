<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DoctorSeeder::class,
            SaungSeeder::class,
            MenuSeeder::class,
            SettingSeeder::class,
            VoucherSeeder::class,
            ReservationSeeder::class, // Data reservasi saung (bukan booking salon)
            FeedbackSeeder::class,
        ]);
    }
}
