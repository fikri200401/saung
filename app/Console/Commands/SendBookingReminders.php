<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendBookingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp reminders for upcoming reservations (H-1)';

    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        parent::__construct();
        $this->whatsappService = $whatsappService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending reservation reminders...');

        // Get reservations for tomorrow
        $tomorrow = Carbon::tomorrow()->toDateString();

        $reservations = Reservation::whereIn('status', ['confirmed', 'deposit_confirmed'])
            ->whereDate('reservation_date', $tomorrow)
            ->with(['user', 'saung', 'menus'])
            ->get();

        $count = 0;

        foreach ($reservations as $reservation) {
            $this->whatsappService->sendReservationReminder($reservation);
            
            $count++;
            $this->info("Reminder sent: Reservation #{$reservation->reservation_code} - {$reservation->user->name}");
        }

        $this->info("Total reminders sent: {$count}");

        return 0;
    }
}
