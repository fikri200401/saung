<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Saung;
use App\Models\Menu;
use App\Models\Deposit;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CustomerDemoReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat 50+ transaksi untuk customer demo (081234567892)
     */
    public function run(): void
    {
        // Cari customer dengan WhatsApp 081234567892
        $customer = User::where('whatsapp_number', '081234567892')->first();
        
        if (!$customer) {
            $this->command->error('❌ Customer dengan WhatsApp 081234567892 tidak ditemukan!');
            return;
        }

        $saungs = Saung::all();
        $menus = Menu::all();

        if ($saungs->isEmpty() || $menus->isEmpty()) {
            $this->command->warn('⚠️  Pastikan SaungSeeder dan MenuSeeder sudah dijalankan terlebih dahulu');
            return;
        }

        $times = ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
        $customerNotes = [
            'Acara ulang tahun keluarga',
            'Meeting keluarga besar',
            'Gathering teman kantor',
            'Arisan bulanan',
            'Acara reuni sekolah',
            'Makan siang keluarga',
            'Perayaan anniversary',
            'Syukuran promosi',
            'Kumpul keluarga',
            'Acara makan malam bersama',
            null,
        ];

        $reservationCount = 0;

        // Generate 60 transaksi (dari 60 hari lalu sampai hari ini)
        // Fokus pada transaksi completed (masa lalu)
        for ($i = -60; $i <= 0; $i++) {
            // Buat transaksi secara random (60-70% dari hari akan ada transaksi)
            if (rand(1, 100) <= 70) {
                $reservationCount++;
                
                $date = Carbon::now()->addDays($i);
                
                // Tentukan status berdasarkan tanggal
                if ($i < -2) {
                    // Masa lalu (lebih dari 2 hari lalu) - mostly completed
                    $status = rand(1, 100) <= 90 ? 'completed' : 'cancelled';
                } elseif ($i >= -2 && $i < 0) {
                    // 2 hari lalu sampai kemarin - completed atau cancelled
                    $status = rand(1, 100) <= 95 ? 'completed' : 'cancelled';
                } else {
                    // Hari ini
                    $status = 'completed';
                }

                $saung = $saungs->random();
                $numberOfPeople = rand(4, 15);
                $duration = rand(2, 4);
                $startTime = $times[array_rand($times)];
                $endTime = Carbon::parse($startTime)->addHours($duration)->format('H:i');
                
                // Pilih menu random
                $selectedMenus = $menus->random(rand(4, 10));
                $totalPrice = 0;
                
                $menuData = [];
                foreach ($selectedMenus as $menu) {
                    $quantity = rand(2, ceil($numberOfPeople / 1.5));
                    $totalPrice += $menu->price * $quantity;
                    $menuData[] = [
                        'menu_id' => $menu->id,
                        'quantity' => $quantity,
                        'price' => $menu->price,
                    ];
                }
                
                // Tambah biaya saung
                $saungFee = 100000 * $duration;
                $totalPrice += $saungFee;
                
                // Member discount 10%
                $discountAmount = $customer->is_member ? round($totalPrice * 0.1) : 0;
                
                // Random extra discount (10% chance)
                if (rand(1, 100) <= 10) {
                    $discountAmount += rand(30000, 100000);
                }
                
                $finalPrice = $totalPrice - $discountAmount;

                // Tentukan kapan booking dibuat (1-7 hari sebelum tanggal reservasi)
                $createdAt = Carbon::parse($date)->subDays(rand(1, 7))->subHours(rand(0, 23));

                $reservation = Reservation::create([
                    'reservation_code' => 'RSV-DEMO-' . strtoupper(substr(md5(uniqid() . $reservationCount), 0, 8)),
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
                    'admin_notes' => $status == 'cancelled' ? 'Dibatalkan karena ada keperluan mendadak' : ($status == 'completed' ? 'Acara berjalan lancar' : null),
                    'is_manual_entry' => false,
                    'created_at' => $createdAt,
                    'updated_at' => $status == 'completed' ? Carbon::parse($date)->addHours(22) : $createdAt,
                ]);

                // Attach menus
                foreach ($menuData as $data) {
                    $reservation->menus()->attach($data['menu_id'], [
                        'quantity' => $data['quantity'],
                        'price' => $data['price'],
                    ]);
                }

                // Jika harga besar (> 1jt), buat deposit yang sudah approved
                if ($finalPrice > 1000000 && $status != 'cancelled') {
                    Deposit::create([
                        'reservation_id' => $reservation->id,
                        'amount' => round($finalPrice * 0.3), // DP 30%
                        'proof_image' => 'deposits/demo-customer-' . $reservationCount . '.jpg',
                        'status' => 'approved',
                        'verified_by' => 1, // admin
                        'verified_at' => $createdAt->copy()->addHours(rand(2, 12)),
                        'deadline_at' => Carbon::parse($date)->subDay(),
                        'created_at' => $createdAt->copy()->addMinutes(rand(30, 180)),
                    ]);
                }
            }
        }

        $this->command->info("✅ CustomerDemoReservationSeeder selesai!");
        $this->command->info("   📱 Customer: {$customer->name} ({$customer->whatsapp_number})");
        $this->command->info("   📊 Total Transaksi: {$reservationCount}");
        
        $completedCount = Reservation::where('user_id', $customer->id)->where('status', 'completed')->count();
        $cancelledCount = Reservation::where('user_id', $customer->id)->where('status', 'cancelled')->count();
        $totalSpent = Reservation::where('user_id', $customer->id)->where('status', 'completed')->sum('final_price');
        
        $this->command->info("   ✅ Completed: {$completedCount}");
        $this->command->info("   ❌ Cancelled: {$cancelledCount}");
        $this->command->info("   💰 Total Spent: Rp " . number_format($totalSpent, 0, ',', '.'));
    }
}
