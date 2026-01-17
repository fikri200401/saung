<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Treatment;
use App\Models\Doctor;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        // Query hanya reservations (data baru)
        $query = \App\Models\Reservation::with(['user', 'saung', 'menus', 'deposit']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('reservation_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('reservation_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('reservation_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q2) use ($request) {
                      $q2->where('name', 'like', '%' . $request->search . '%')
                         ->orWhere('whatsapp_number', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $bookings = $query->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        // Try Reservation first (new system)
        $reservation = \App\Models\Reservation::with(['user', 'saung', 'menus', 'deposit'])->find($id);
        
        if ($reservation) {
            // New system: pass as $booking for view compatibility
            $booking = $reservation;
            return view('admin.bookings.show', compact('booking'));
        }
        
        // Fallback to Booking (old system)
        $booking = Booking::with(['user', 'treatment', 'doctor', 'deposit', 'feedback', 'beforeAfterPhotos'])->findOrFail($id);
        
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Manual booking entry (from WhatsApp)
     */
    public function create()
    {
        $treatments = Treatment::active()->get();
        $doctors = Doctor::active()->get();
        $users = User::where('role', 'customer')
                     ->orderBy('name')
                     ->get();
        
        return view('admin.bookings.create', compact('treatments', 'doctors', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'treatment_id' => 'required|exists:treatments,id',
            'doctor_id' => 'required|exists:doctors,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        $result = $this->bookingService->createBooking($request->user_id, [
            'treatment_id' => $request->treatment_id,
            'doctor_id' => $request->doctor_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'admin_notes' => $request->notes, // Changed from 'notes' to 'admin_notes'
            'is_manual_entry' => true,
        ]);

        if ($result['success']) {
            return redirect()
                ->route('admin.bookings.show', $result['booking']->id)
                ->with('success', 'Booking manual berhasil dibuat!');
        }

        return back()
            ->withErrors(['error' => $result['message']])
            ->withInput();
    }

    /**
     * Reschedule booking
     */
    public function reschedule(Request $request, Booking $booking)
    {
        $request->validate([
            'booking_date' => 'required|date',
            'booking_time' => 'required',
            'doctor_id' => 'nullable|exists:doctors,id',
        ]);

        $result = $this->bookingService->rescheduleBooking(
            $booking->id,
            $request->booking_date,
            $request->booking_time,
            $request->doctor_id
        );

        if ($result['success']) {
            return back()->with('success', 'Booking berhasil direschedule.');
        }

        return back()->withErrors(['error' => $result['message']]);
    }

    /**
     * Cancel booking (dual system support)
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        // Try Reservation first, then Booking
        $reservation = \App\Models\Reservation::find($id);
        if ($reservation) {
            $reservationService = app(\App\Services\ReservationService::class);
            $result = $reservationService->cancelReservation($reservation->id, $request->admin_notes);
        } else {
            $booking = \App\Models\Booking::findOrFail($id);
            $result = $this->bookingService->cancelBooking($booking->id, $request->admin_notes);
        }

        return back()->with('success', 'Booking/Reservasi berhasil dibatalkan.');
    }

    /**
     * Complete booking (dual system support)
     */
    public function complete($id)
    {
        // Try Reservation first, then Booking
        $reservation = \App\Models\Reservation::find($id);
        if ($reservation) {
            $reservationService = app(\App\Services\ReservationService::class);
            $result = $reservationService->completeReservation($reservation->id);
        } else {
            $booking = \App\Models\Booking::findOrFail($id);
            $result = $this->bookingService->completeBooking($booking->id);
        }

        return back()->with('success', 'Booking/Reservasi berhasil diselesaikan.');

        return back()->with('success', 'Booking berhasil diselesaikan.');
    }

    /**

     * Mark booking as no-show and forfeit deposit (for testing)
     */
    public function markAsNoShow(Request $request, Booking $booking)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        // Update booking status to no-show
        $booking->update([
            'status' => 'no-show',
            'admin_notes' => $request->reason ?? 'Customer tidak datang (Testing)',
        ]);

        // Hanguskan deposit jika ada
        if ($booking->deposit && $booking->deposit->status === 'approved') {
            $booking->deposit->update([
                'status' => 'forfeited',
                'admin_notes' => 'DP hangus karena customer tidak datang',
            ]);

            // Create no-show note
            $booking->noShowNote()->create([
                'notes' => $request->reason ?? 'Customer tidak datang pada jadwal yang ditentukan',
                'recorded_by' => Auth::id(),
            ]);
        }

        return back()->with('success', 'Booking ditandai sebagai No-Show dan DP telah dihanguskan.');
    }

    /**

     * Update admin notes
     */
    public function updateNotes(Request $request, Booking $booking)
    {
        $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $booking->update(['admin_notes' => $request->admin_notes]);

        return back()->with('success', 'Catatan berhasil diupdate.');
    }
}
