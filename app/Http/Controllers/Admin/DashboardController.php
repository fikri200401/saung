<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Deposit;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_reservations_today' => \App\Models\Reservation::whereDate('reservation_date', today())->count(),
            'pending_deposits' => Deposit::pending()->count(),
            'expired_deposits' => Deposit::expired()->count(),
            'total_members' => User::members()->count(),
            'active_vouchers' => Voucher::active()->count(),
        ];

        $upcomingReservations = \App\Models\Reservation::active()
            ->with(['user', 'saung', 'menus'])
            ->whereDate('reservation_date', '>=', today())
            ->orderBy('reservation_date')
            ->orderBy('reservation_time')
            ->limit(10)
            ->get();

        $pendingDeposits = Deposit::pending()
            ->with(['reservation.user', 'reservation.saung', 'booking.user', 'booking.treatment'])
            ->orderBy('deadline_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'upcomingReservations', 'pendingDeposits'));
    }
}
