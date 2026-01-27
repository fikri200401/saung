<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Show feedback form
     */
    public function create($reservationId)
    {
        $reservation = Reservation::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->whereDoesntHave('feedback')
            ->with(['saung', 'menus'])
            ->findOrFail($reservationId);

        return view('customer.feedback.create', compact('reservation'));
    }

    /**
     * Store feedback
     */
    public function store(Request $request, $reservationId)
    {
        $reservation = Reservation::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->whereDoesntHave('feedback')
            ->findOrFail($reservationId);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'menu_id' => 'nullable|exists:menus,id',
            'saung_id' => 'nullable|exists:saungs,id',
        ]);

        Feedback::create([
            'reservation_id' => $reservation->id,
            'user_id' => Auth::id(),
            'menu_id' => $request->menu_id,
            'saung_id' => $request->saung_id ?? $reservation->saung_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()
            ->route('customer.reservations.show', $reservation->id)
            ->with('success', 'Terima kasih atas feedback Anda!');
    }
}
