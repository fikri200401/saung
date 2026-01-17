<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Feedback;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get completed reservations
        $completedReservations = Reservation::where('status', 'completed')->get();

        if ($completedReservations->count() >= 1) {
            $reservation = $completedReservations[0];
            
            // Feedback 1 - Excellent rating
            Feedback::create([
                'reservation_id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'saung_id' => $reservation->saung_id,
                'rating' => 5,
                'comment' => 'Saungnya bagus banget! Suasana asri dan tenang, cocok untuk acara keluarga. Menu makanannya enak-enak, terutama ayam bakar madunya juara. Pelayanan juga ramah. Pasti balik lagi!',
                'is_visible' => true,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ]);
        }

        if ($completedReservations->count() >= 2) {
            $reservation = $completedReservations[1];
            
            // Feedback 2 - Very good rating  
            Feedback::create([
                'reservation_id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'saung_id' => $reservation->saung_id,
                'rating' => 5,
                'comment' => 'Tempatnya recommended banget! Saung VIP nya luas, bisa muat 15 orang dengan nyaman. Makanannya porsi besar dan rasanya autentik. Staff nya helpful banget. Worth it!',
                'is_visible' => true,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ]);
        }

        $this->command->info('✅ Created ' . Feedback::count() . ' feedback untuk reservasi saung');
    }
}
