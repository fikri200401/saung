<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Get reservation statistics (Saung Nyonyah)
        $totalReservations = $user->reservations()->count();
        $completedReservations = $user->reservations()->where('status', 'completed')->count();
        $pendingReservations = $user->reservations()
            ->whereIn('status', ['auto_approved', 'waiting_deposit', 'deposit_confirmed', 'confirmed'])
            ->count();
        
        $recentReservations = $user->reservations()
            ->with(['saung', 'menus'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact('user', 'totalReservations', 'completedReservations', 'pendingReservations', 'recentReservations'));
    }
}
