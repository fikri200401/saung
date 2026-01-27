<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Saung;
use App\Models\Menu;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'saung', 'menus', 'deposit']);

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

        $reservations = $query->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->paginate(20);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function show($id)
    {
        $reservation = Reservation::with(['user', 'saung', 'menus', 'deposit', 'feedback'])
            ->findOrFail($id);
        
        return view('admin.reservations.show', compact('reservation'));
    }

    public function create()
    {
        $saungs = Saung::active()->get();
        $menus = Menu::active()->get();
        $users = User::where('role', 'customer')
                     ->orderBy('name')
                     ->get();
        
        return view('admin.reservations.create', compact('saungs', 'menus', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'saung_id' => 'required|exists:saungs,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required',
            'guest_count' => 'required|integer|min:1',
            'duration' => 'required|integer|min:1|max:12',
            'menu_items' => 'nullable|array',
            'menu_items.*.menu_id' => 'required_with:menu_items|exists:menus,id',
            'menu_items.*.quantity' => 'required_with:menu_items|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Transform menu_items to format expected by service
        $menus = [];
        if ($request->menu_items) {
            foreach ($request->menu_items as $item) {
                if (!empty($item['menu_id']) && !empty($item['quantity'])) {
                    $menus[] = [
                        'id' => $item['menu_id'],
                        'quantity' => $item['quantity']
                    ];
                }
            }
        }

        $result = $this->reservationService->createReservation($request->user_id, [
            'saung_id' => $request->saung_id,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'duration' => $request->duration ?? 2,
            'number_of_people' => $request->guest_count,
            'menus' => $menus,
            'notes' => $request->notes,
        ]);

        if ($result['success']) {
            return redirect()
                ->route('admin.reservations.show', $result['reservation']->id)
                ->with('success', 'Reservasi manual berhasil dibuat!');
        }

        return back()
            ->withErrors(['error' => $result['message']])
            ->withInput();
    }

    public function cancel(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $reservation = Reservation::findOrFail($id);
        $result = $this->reservationService->cancelReservation($reservation->id, $request->admin_notes);

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }

    public function complete($id)
    {
        $reservation = Reservation::findOrFail($id);
        $result = $this->reservationService->completeReservation($reservation->id);

        return back()->with('success', 'Reservasi berhasil diselesaikan.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled,completed',
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => $request->status]);

        return back()->with('success', 'Status reservasi berhasil diupdate.');
    }

    public function updateNotes(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update(['admin_notes' => $request->admin_notes]);

        return back()->with('success', 'Catatan berhasil diupdate.');
    }
}
