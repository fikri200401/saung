<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Saung;
use App\Models\Menu;
use App\Models\Deposit;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $saungs = Saung::all();
        $menus = Menu::all();

        if ($customers->isEmpty() || $saungs->isEmpty() || $menus->isEmpty()) {
            $this->command->warn('⚠️  Pastikan UserSeeder, SaungSeeder, dan MenuSeeder sudah dijalankan terlebih dahulu');
            return;
        }

        $statuses = ['completed', 'confirmed', 'deposit_confirmed', 'waiting_deposit', 'cancelled'];
        $times = ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
        $customerNotes = [
            'Acara ulang tahun',
            'Meeting keluarga',
            'Gathering kantor',
            'Arisan RT',
            'Acara reuni',
            'Makan siang keluarga',
            'Acara pernikahan kecil',
            'Syukuran',
            null,
        ];

        $reservationCount = 0;

        // Generate 60 transaksi reservasi (30 hari terakhir sampai 30 hari ke depan)
        for ($i = -30; $i <= 30; $i++) {
            // Buat 1-3 reservasi per hari (lebih banyak di weekend)
            $date = Carbon::now()->addDays($i);
            $isWeekend = $date->isWeekend();
            $reservationsPerDay = $isWeekend ? rand(2, 3) : rand(1, 2);

            for ($j = 0; $j < $reservationsPerDay; $j++) {
                $reservationCount++;
                
                // Tentukan status berdasarkan tanggal
                if ($i < -2) {
                    // Masa lalu (lebih dari 2 hari lalu) - mostly completed atau cancelled
                    $status = rand(1, 10) <= 8 ? 'completed' : 'cancelled';
                } elseif ($i >= -2 && $i < 0) {
                    // 2 hari lalu sampai kemarin - completed atau cancelled
                    $status = rand(1, 10) <= 9 ? 'completed' : 'cancelled';
                } elseif ($i == 0) {
                    // Hari ini - confirmed atau deposit_confirmed
                    $status = rand(1, 2) == 1 ? 'confirmed' : 'deposit_confirmed';
                } else {
                    // Masa depan - berbagai status
                    $randStatus = rand(1, 10);
                    if ($randStatus <= 4) {
                        $status = 'confirmed';
                    } elseif ($randStatus <= 7) {
                        $status = 'deposit_confirmed';
                    } elseif ($randStatus <= 9) {
                        $status = 'waiting_deposit';
                    } else {
                        $status = 'cancelled';
                    }
                }

                $customer = $customers->random();
                $saung = $saungs->random();
                $numberOfPeople = rand(2, 20);
                $duration = rand(2, 5);
                $startTime = $times[array_rand($times)];
                $endTime = Carbon::parse($startTime)->addHours($duration)->format('H:i');
                
                // Hitung total price berdasarkan menu
                $selectedMenus = $menus->random(rand(3, 8));
                $totalPrice = 0;
                foreach ($selectedMenus as $menu) {
                    $quantity = rand(2, $numberOfPeople);
                    $totalPrice += $menu->price * $quantity;
                }
                
                // Tambah biaya saung (misal 100k per jam)
                $totalPrice += 100000 * $duration;
                
                // Random discount (20% chance ada diskon)
                $discountAmount = rand(1, 100) <= 20 ? rand(50000, 200000) : 0;
                $finalPrice = $totalPrice - $discountAmount;

                // Tentukan kapan booking dibuat
                if ($i < 0) {
                    // Transaksi masa lalu - dibuat beberapa hari sebelum reservation_date
                    $createdAt = Carbon::parse($date)->subDays(rand(3, 14));
                } elseif ($i == 0) {
                    // Hari ini - dibuat beberapa jam/hari yang lalu
                    $createdAt = Carbon::now()->subHours(rand(5, 48));
                } else {
                    // Masa depan - dibuat beberapa hari yang lalu
                    $createdAt = Carbon::now()->subDays(rand(1, 5));
                }

                $reservation = Reservation::create([
                    'reservation_code' => 'RSV-' . strtoupper(substr(md5(uniqid() . $reservationCount), 0, 10)),
                    'user_id' => $customer->id,
                    'saung_id' => $saung->id,
                    'reservation_date' => $date->format('Y-m-d'),
                    'reservation_time' => $startTime,
                    'end_time' => $endTime,
                    'duration' => $duration,
                    'number_of_people' => $numberOfPeople,
                    'status' => $status,
                    'total_price' => $totalPrice,
                    'discount_amount' => $discountAmount,
                    'final_price' => $finalPrice,
                    'customer_notes' => $customerNotes[array_rand($customerNotes)],
                    'admin_notes' => $status == 'cancelled' ? 'Dibatalkan oleh customer' : null,
                    'is_manual_entry' => false,
                    'created_at' => $createdAt,
                    'updated_at' => $status == 'completed' ? Carbon::parse($date)->addHours(22) : $createdAt,
                ]);

                // Attach menus dengan quantity
                foreach ($selectedMenus as $menu) {
                    $reservation->menus()->attach($menu->id, [
                        'quantity' => rand(1, ceil($numberOfPeople / 2)),
                        'price' => $menu->price,
                    ]);
                }

                // Buat deposit jika status deposit_confirmed
                if ($status == 'deposit_confirmed') {
                    Deposit::create([
                        'reservation_id' => $reservation->id,
                        'amount' => round($finalPrice * 0.3), // DP 30%
                        'proof_image' => 'deposits/sample-deposit-' . $reservationCount . '.jpg',
                        'status' => 'approved',
                        'verified_by' => 1, // admin
                        'verified_at' => $createdAt->copy()->addHours(rand(1, 12)),
                        'deadline_at' => Carbon::parse($date)->subDay(),
                        'created_at' => $createdAt->copy()->addMinutes(rand(30, 120)),
                    ]);
                }

                // Buat deposit pending jika status waiting_deposit
                if ($status == 'waiting_deposit') {
                    // 50% chance sudah upload bukti tapi belum diverifikasi
                    if (rand(1, 2) == 1) {
                        Deposit::create([
                            'reservation_id' => $reservation->id,
                            'amount' => round($finalPrice * 0.3),
                            'proof_image' => 'deposits/sample-deposit-' . $reservationCount . '.jpg',
                            'status' => 'pending',
                            'verified_by' => null,
                            'verified_at' => null,
                            'deadline_at' => Carbon::parse($date)->subDay(),
                            'created_at' => $createdAt->copy()->addHours(rand(1, 24)),
                        ]);
                    }
                }
            }
        }

        $this->command->info("✅ ReservationSeeder selesai! Dibuat {$reservationCount} reservasi dengan berbagai status.");
        $this->command->info("   - Completed: " . Reservation::where('status', 'completed')->count());
        $this->command->info("   - Confirmed: " . Reservation::where('status', 'confirmed')->count());
        $this->command->info("   - Deposit Confirmed: " . Reservation::where('status', 'deposit_confirmed')->count());
        $this->command->info("   - Waiting Deposit: " . Reservation::where('status', 'waiting_deposit')->count());
        $this->command->info("   - Cancelled: " . Reservation::where('status', 'cancelled')->count());
    }
}
