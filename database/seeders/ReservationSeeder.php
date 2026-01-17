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
        $customer = User::where('role', 'customer')->first();
        $saungs = Saung::all();
        $menus = Menu::all();

        if (!$customer || $saungs->isEmpty() || $menus->isEmpty()) {
            $this->command->warn('⚠️  Pastikan UserSeeder, SaungSeeder, dan MenuSeeder sudah dijalankan terlebih dahulu');
            return;
        }

        // 1. RESERVASI COMPLETED (sudah selesai - kemarin)
        $reservation1 = Reservation::create([
            'reservation_code' => 'RSV-' . strtoupper(substr(md5(uniqid()), 0, 10)),
            'user_id' => $customer->id,
            'saung_id' => $saungs->where('name', 'Saung Bambu')->first()?->id ?? $saungs->first()->id,
            'reservation_date' => Carbon::yesterday()->format('Y-m-d'),
            'reservation_time' => '18:00',
            'end_time' => '21:00',
            'duration' => 3, // 3 jam
            'number_of_people' => 8,
            'status' => 'completed',
            'total_price' => 850000,
            'discount_amount' => 0,
            'final_price' => 850000,
            'customer_notes' => 'Acara ulang tahun keluarga',
            'admin_notes' => 'Acara berjalan lancar, customer sangat puas',
            'is_manual_entry' => false,
            'created_at' => Carbon::now()->subHours(30), // Booked 30 jam lalu (kemarin siang)
            'updated_at' => Carbon::yesterday()->addHours(21), // Updated kemarin jam 9 malam
        ]);

        // Attach menus untuk reservation 1
        $menuIds = $menus->whereIn('name', ['Nasi Timbel Komplit', 'Ayam Bakar Madu', 'Sop Iga Sapi', 'Es Kelapa Muda'])->pluck('id')->toArray();
        if (!empty($menuIds)) {
            foreach ($menuIds as $menuId) {
                $reservation1->menus()->attach($menuId, [
                    'quantity' => rand(2, 5),
                    'price' => Menu::find($menuId)->price ?? 0,
                ]);
            }
        }

        // 2. RESERVASI DEPOSIT_CONFIRMED (hari ini sore)
        $reservation2 = Reservation::create([
            'reservation_code' => 'RSV-' . strtoupper(substr(md5(uniqid()), 0, 10)),
            'user_id' => $customer->id,
            'saung_id' => $saungs->where('name', 'Saung VIP')->first()?->id ?? $saungs->skip(1)->first()->id,
            'reservation_date' => Carbon::today()->format('Y-m-d'),
            'reservation_time' => '18:00',
            'end_time' => '22:00',
            'duration' => 4,
            'number_of_people' => 15,
            'status' => 'deposit_confirmed',
            'total_price' => 2500000,
            'discount_amount' => 250000, // pakai voucher 10%
            'final_price' => 2250000,
            'customer_notes' => 'Acara meeting kantor, mohon tempat yang tenang',
            'admin_notes' => null,
            'is_manual_entry' => false,
            'created_at' => Carbon::now()->subHours(5), // Booked 5 jam lalu
        ]);

        // Attach menus
        $menuIds = $menus->whereIn('name', ['Paket Nasi Liwet Komplit', 'Gurame Bakar', 'Bebek Goreng', 'Es Teh Manis'])->pluck('id')->toArray();
        if (!empty($menuIds)) {
            foreach ($menuIds as $menuId) {
                $reservation2->menus()->attach($menuId, [
                    'quantity' => rand(5, 10),
                    'price' => Menu::find($menuId)->price ?? 0,
                ]);
            }
        }

        // Create deposit for reservation2
        Deposit::create([
            'reservation_id' => $reservation2->id,
            'amount' => 500000, // DP 500rb
            'proof_image' => 'deposits/sample-deposit.jpg',
            'status' => 'approved',
            'verified_by' => 1, // admin
            'verified_at' => Carbon::now()->subHours(3), // Verified 3 jam lalu
            'deadline_at' => Carbon::now()->addDay(), // Deadline besok
            'created_at' => Carbon::now()->subHours(4), // Upload 4 jam lalu
        ]);

        // 3. RESERVASI waiting_deposit (besok pagi)
        $reservation3 = Reservation::create([
            'reservation_code' => 'RSV-' . strtoupper(substr(md5(uniqid()), 0, 10)),
            'user_id' => $customer->id,
            'saung_id' => $saungs->where('name', 'Saung Keluarga')->first()?->id ?? $saungs->skip(2)->first()->id,
            'reservation_date' => Carbon::tomorrow()->format('Y-m-d'),
            'reservation_time' => '11:00',
            'end_time' => '14:00',
            'duration' => 2,
            'number_of_people' => 6,
            'status' => 'waiting_deposit',
            'total_price' => 450000,
            'discount_amount' => 0,
            'final_price' => 450000,
            'customer_notes' => 'Makan siang keluarga',
            'admin_notes' => null,
            'is_manual_entry' => false,
            'created_at' => Carbon::now()->subMinutes(45), // Booked 45 menit lalu
        ]);

        // Attach menus
        $menuIds = $menus->whereIn('name', ['Nasi Bakar Ayam', 'Sate Maranggi', 'Jus Alpukat'])->pluck('id')->toArray();
        if (!empty($menuIds)) {
            foreach ($menuIds as $menuId) {
                $reservation3->menus()->attach($menuId, [
                    'quantity' => rand(2, 4),
                    'price' => Menu::find($menuId)->price ?? 0,
                ]);
            }
        }

        // 4. RESERVASI CONFIRMED (3 hari lagi - tanpa DP karena harga kecil)
        $reservation4 = Reservation::create([
            'reservation_code' => 'RSV-' . strtoupper(substr(md5(uniqid()), 0, 10)),
            'user_id' => $customer->id,
            'saung_id' => $saungs->first()->id,
            'reservation_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'reservation_time' => '15:00',
            'end_time' => '18:00',
            'duration' => 2,
            'number_of_people' => 4,
            'status' => 'confirmed',
            'total_price' => 280000,
            'discount_amount' => 0,
            'final_price' => 280000,
            'customer_notes' => null,
            'admin_notes' => 'Langsung confirmed tanpa DP karena harga kecil',
            'is_manual_entry' => false,
            'created_at' => Carbon::now()->subHours(2), // Booked 2 jam lalu
        ]);

        // Attach menus
        $menuIds = $menus->whereIn('name', ['Nasi Goreng Kampung', 'Es Jeruk'])->pluck('id')->toArray();
        if (!empty($menuIds)) {
            foreach ($menuIds as $menuId) {
                $reservation4->menus()->attach($menuId, [
                    'quantity' => rand(2, 3),
                    'price' => Menu::find($menuId)->price ?? 0,
                ]);
            }
        }

        $this->command->info('✅ ReservationSeeder selesai! Dibuat 4 reservasi sample.');
    }
}
