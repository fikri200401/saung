<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Saung;
use App\Models\Menu;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    /**
     * Show reservation form
     * Flow: tanggal → jam → saung tersedia → jumlah orang → konfirmasi
     */
    public function create()
    {
        $menus = Menu::active()->get()->groupBy('category');
        return view('customer.reservation.create', compact('menus'));
    }

    /**
     * Get available time slots (AJAX)
     * Step 1: Pilih tanggal → return available jam
     */
    public function getAvailableTimeSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        try {
            $slots = $this->reservationService->getAvailableTimeSlots($request->date);

            return response()->json([
                'success' => true,
                'slots' => $slots,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get available saungs for specific time slot (AJAX)
     * Step 2: Pilih jam → return saung yang tersedia
     */
    public function getAvailableSaungs(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'duration' => 'required|integer|min:1',
        ]);

        try {
            $saungs = $this->reservationService->getAvailableSaungs(
                $request->date,
                $request->time,
                $request->duration
            );

            return response()->json([
                'success' => true,
                'saungs' => $saungs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store reservation
     * Step 3: Submit dengan saung_id, number_of_people, menu items
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'saung_id' => 'required|exists:saungs,id',
                'reservation_date' => 'required|date|after_or_equal:today',
                'reservation_time' => 'required',
                'duration' => 'required|integer|min:1',
                'number_of_people' => 'required|integer|min:1',
                'menus' => 'nullable|array',
                'menus.*.id' => 'required|exists:menus,id',
                'menus.*.quantity' => 'required|integer|min:1',
                'voucher_code' => 'nullable|string',
                'notes' => 'nullable|string|max:500',
                'deposit_proof' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // 2MB max
            ]);

            // Handle deposit proof upload
            $depositProofPath = null;
            if ($request->hasFile('deposit_proof')) {
                $depositProofPath = $request->file('deposit_proof')->store('deposits', 'public');
                Log::info('Deposit proof uploaded', [
                    'path' => $depositProofPath,
                    'original_name' => $request->file('deposit_proof')->getClientOriginalName(),
                ]);
            } else {
                Log::info('No deposit proof file in request');
            }

            $result = $this->reservationService->createReservation(Auth::id(), [
                'saung_id' => (int) $request->saung_id,
                'reservation_date' => $request->reservation_date,
                'reservation_time' => $request->reservation_time,
                'duration' => (int) $request->duration,
                'number_of_people' => (int) $request->number_of_people,
                'menus' => $request->menus ?? [],
                'voucher_code' => $request->voucher_code,
                'notes' => $request->notes,
                'deposit_proof' => $depositProofPath,
            ]);

            Log::info('Reservation creation result', [
                'success' => $result['success'],
                'deposit_proof_sent' => $depositProofPath,
            ]);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Reservasi berhasil dibuat!',
                    'reservation_id' => $result['reservation']->id,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 422);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all()),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Reservation creation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show reservation list
     */
    public function index()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with(['saung', 'menus', 'deposit'])
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->paginate(10);

        return view('customer.reservation.index', compact('reservations'));
    }

    /**
     * Show reservation detail
     */
    public function show($id)
    {
        $reservation = Reservation::where('user_id', Auth::id())
            ->with(['saung', 'menus', 'deposit', 'feedback'])
            ->findOrFail($id);

        return view('customer.reservation.show', compact('reservation'));
    }

    /**
     * Upload deposit proof
     */
    public function uploadDepositProof(Request $request, $id)
    {
        $request->validate([
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $reservation = Reservation::where('user_id', Auth::id())->findOrFail($id);

        if (!$reservation->deposit) {
            return back()->withErrors(['error' => 'Deposit tidak ditemukan.']);
        }

        if ($reservation->deposit->status !== 'pending') {
            return back()->withErrors(['error' => 'Deposit sudah diverifikasi atau expired.']);
        }

        $path = $request->file('proof_of_payment')->store('deposits', 'public');
        $reservation->deposit->update([
            'proof_image' => $path,
            'uploaded_at' => now()
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

    /**
     * Check voucher (AJAX)
     */
    public function checkVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
        ]);

        try {
            $result = $this->reservationService->checkVoucher(
                $request->voucher_code,
                Auth::id()
            );

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
