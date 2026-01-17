<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Saung;
use App\Models\Menu;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Models\Deposit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationService
{
    /**
     * Get available time slots for a specific date
     */
    public function getAvailableTimeSlots($date)
    {
        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
        
        // Get all active saungs with schedules for this day
        $saungs = Saung::active()
            ->whereHas('schedules', function($query) use ($dayOfWeek) {
                $query->where('day_of_week', $dayOfWeek)
                      ->where('is_active', true);
            })
            ->with(['schedules' => function($query) use ($dayOfWeek) {
                $query->where('day_of_week', $dayOfWeek)
                      ->where('is_active', true);
            }])
            ->get();

        if ($saungs->isEmpty()) {
            return [];
        }

        // Find common operating hours
        $earliestStart = '09:00';
        $latestEnd = '21:00';
        
        foreach ($saungs as $saung) {
            foreach ($saung->schedules as $schedule) {
                $start = Carbon::parse($schedule->start_time)->format('H:i');
                $end = Carbon::parse($schedule->end_time)->format('H:i');
                
                if ($start < $earliestStart) $earliestStart = $start;
                if ($end > $latestEnd) $latestEnd = $end;
            }
        }

        // Generate time slots (every hour)
        $slots = [];
        $current = Carbon::createFromFormat('H:i', $earliestStart);
        $end = Carbon::createFromFormat('H:i', $latestEnd);

        while ($current < $end) {
            $slots[] = $current->format('H:i');
            $current->addHour();
        }

        return $slots;
    }

    /**
     * Get available saungs for specific date, time, and duration
     */
    public function getAvailableSaungs($date, $time, $duration)
    {
        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
        
        // Calculate end time
        $startTime = Carbon::createFromFormat('H:i', $time);
        $endTime = $startTime->copy()->addHours($duration);
        
        $startTimeStr = $startTime->format('H:i');
        $endTimeStr = $endTime->format('H:i');

        // Get all active saungs
        $saungs = Saung::active()
            ->with('schedules')
            ->get();

        $availableSaungs = [];

        foreach ($saungs as $saung) {
            if ($saung->isAvailable($date, $startTimeStr, $endTimeStr)) {
                $availableSaungs[] = [
                    'id' => $saung->id,
                    'name' => $saung->name,
                    'capacity' => $saung->capacity,
                    'location' => $saung->location,
                    'description' => $saung->description,
                    'price_per_hour' => $saung->price_per_hour,
                    'formatted_price' => $saung->formatted_price,
                    'image' => $saung->image,
                ];
            }
        }

        return $availableSaungs;
    }

    /**
     * Create a new reservation
     */
    public function createReservation($userId, $data)
    {
        DB::beginTransaction();

        try {
            // Get saung
            $saung = Saung::findOrFail($data['saung_id']);

            // Calculate end time
            $startTime = Carbon::createFromFormat('H:i', $data['reservation_time']);
            $duration = (int) $data['duration'];
            $endTime = $startTime->copy()->addHours($duration);

            // Check availability
            if (!$saung->isAvailable($data['reservation_date'], $startTime->format('H:i'), $endTime->format('H:i'))) {
                return [
                    'success' => false,
                    'message' => 'Saung tidak tersedia pada waktu yang dipilih.',
                ];
            }

            // Calculate saung price
            $saungPrice = $saung->price_per_hour * $duration;
            $totalPrice = $saungPrice;

            // Create reservation
            $reservation = Reservation::create([
                'user_id' => $userId,
                'saung_id' => $data['saung_id'],
                'reservation_date' => $data['reservation_date'],
                'reservation_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'number_of_people' => $data['number_of_people'],
                'status' => 'auto_approved',
                'total_price' => $totalPrice,
                'discount_amount' => 0,
                'final_price' => $totalPrice,
                'customer_notes' => $data['notes'] ?? null,
            ]);

            // Add menus if any
            if (!empty($data['menus'])) {
                $menuTotal = 0;
                foreach ($data['menus'] as $menuData) {
                    $menu = Menu::find($menuData['id']);
                    if ($menu) {
                        $reservation->menus()->attach($menu->id, [
                            'quantity' => $menuData['quantity'],
                            'price' => $menu->price,
                        ]);
                        $menuTotal += $menu->price * $menuData['quantity'];
                    }
                }
                
                // Update total price
                $totalPrice += $menuTotal;
                $reservation->update([
                    'total_price' => $totalPrice,
                    'final_price' => $totalPrice,
                ]);
            }

            // Apply voucher if provided
            if (!empty($data['voucher_code'])) {
                $voucherResult = $this->applyVoucher($reservation, $data['voucher_code'], $userId);
                if ($voucherResult['success']) {
                    $reservation->update([
                        'discount_amount' => $voucherResult['discount_amount'],
                        'final_price' => $totalPrice - $voucherResult['discount_amount'],
                    ]);
                }
            }

            // Check if deposit is needed (7 days or more in advance)
            $reservationDate = Carbon::parse($data['reservation_date']);
            $daysDifference = now()->diffInDays($reservationDate, false);

            Log::info('Deposit check', [
                'reservation_id' => $reservation->id,
                'days_difference' => $daysDifference,
                'deposit_proof_received' => !empty($data['deposit_proof']),
                'deposit_proof_value' => $data['deposit_proof'] ?? 'null',
            ]);

            if ($daysDifference >= 7) {
                $depositAmount = max(50000, $reservation->final_price * 0.3);
                
                $depositData = [
                    'reservation_id' => $reservation->id,
                    'amount' => $depositAmount,
                    'status' => 'pending',
                    'deadline_at' => now()->addHours(24),
                ];

                // If deposit proof uploaded, set status to pending verification
                if (!empty($data['deposit_proof'])) {
                    $depositData['proof_image'] = $data['deposit_proof'];
                    $depositData['status'] = 'pending'; // Admin will verify
                    $depositData['uploaded_at'] = now();
                }

                Deposit::create($depositData);

                $reservation->update(['status' => 'waiting_deposit']);
            } else {
                // If deposit proof uploaded for non-deposit reservation, still create deposit
                if (!empty($data['deposit_proof'])) {
                    $depositAmount = max(50000, $reservation->final_price * 0.3);
                    
                    Deposit::create([
                        'reservation_id' => $reservation->id,
                        'amount' => $depositAmount,
                        'proof_image' => $data['deposit_proof'],
                        'status' => 'pending',
                        'uploaded_at' => now(),
                        'deadline_at' => now()->addHours(24),
                    ]);

                    $reservation->update(['status' => 'waiting_deposit']);
                }
            }

            DB::commit();

            // Send WhatsApp notification
            $this->sendReservationNotification($reservation);

            return [
                'success' => true,
                'reservation' => $reservation->load(['saung', 'menus', 'deposit']),
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reservation creation error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat reservasi: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check voucher validity
     */
    public function checkVoucher($code, $userId)
    {
        $voucher = Voucher::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$voucher) {
            return [
                'success' => false,
                'message' => 'Voucher tidak ditemukan atau tidak aktif.',
            ];
        }

        if ($voucher->valid_from && now()->lt($voucher->valid_from)) {
            return [
                'success' => false,
                'message' => 'Voucher belum dapat digunakan.',
            ];
        }

        if ($voucher->valid_until && now()->gt($voucher->valid_until)) {
            return [
                'success' => false,
                'message' => 'Voucher sudah kadaluarsa.',
            ];
        }

        if ($voucher->max_usage && $voucher->used_count >= $voucher->max_usage) {
            return [
                'success' => false,
                'message' => 'Voucher sudah mencapai batas penggunaan.',
            ];
        }

        $userUsage = VoucherUsage::where('voucher_id', $voucher->id)
            ->where('user_id', $userId)
            ->count();

        if ($voucher->max_usage_per_user && $userUsage >= $voucher->max_usage_per_user) {
            return [
                'success' => false,
                'message' => 'Anda sudah mencapai batas penggunaan voucher ini.',
            ];
        }

        return [
            'success' => true,
            'voucher' => [
                'code' => $voucher->code,
                'type' => $voucher->type,
                'value' => $voucher->value,
                'description' => $voucher->description,
            ],
        ];
    }

    /**
     * Apply voucher to reservation
     */
    protected function applyVoucher($reservation, $code, $userId)
    {
        $voucher = Voucher::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$voucher) {
            return ['success' => false];
        }

        // Calculate discount
        if ($voucher->type === 'percentage') {
            $discountAmount = $reservation->total_price * ($voucher->value / 100);
        } else {
            $discountAmount = $voucher->value;
        }

        $discountAmount = min($discountAmount, $reservation->total_price);

        // Record usage
        VoucherUsage::create([
            'voucher_id' => $voucher->id,
            'user_id' => $userId,
            'reservation_id' => $reservation->id,
            'discount_amount' => $discountAmount,
        ]);

        $voucher->increment('used_count');

        return [
            'success' => true,
            'discount_amount' => $discountAmount,
        ];
    }

    /**
     * Send WhatsApp notification
     */
    protected function sendReservationNotification($reservation)
    {
        try {
            $whatsappService = app(WhatsAppService::class);
            $user = $reservation->user;

            if ($user->whatsapp_verified && $user->phone) {
                $whatsappService->sendReservationConfirmation($reservation);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
        }
    }

    /**
     * Cancel reservation
     */
    public function cancelReservation($reservationId, $adminNotes = null)
    {
        $reservation = Reservation::findOrFail($reservationId);
        
        $reservation->update([
            'status' => 'cancelled',
            'admin_notes' => $adminNotes,
        ]);

        return [
            'success' => true,
            'reservation' => $reservation,
        ];
    }

    /**
     * Complete reservation
     */
    public function completeReservation($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        
        $reservation->update(['status' => 'completed']);

        return [
            'success' => true,
            'reservation' => $reservation,
        ];
    }
}
