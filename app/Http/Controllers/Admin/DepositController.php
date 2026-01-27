<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function index(Request $request)
    {
        $query = Deposit::with(['reservation.user', 'reservation.saung']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deposits = $query->orderBy('deadline_at', 'asc')->paginate(20);

        return view('admin.deposits.index', compact('deposits'));
    }

    public function show(Deposit $deposit)
    {
        $deposit->load(['reservation.user', 'reservation.saung', 'verifier']);
        
        return view('admin.deposits.show', compact('deposit'));
    }

    /**
     * Approve deposit
     */
    public function approve(Deposit $deposit)
    {
        if ($deposit->status !== 'pending') {
            return back()->withErrors(['error' => 'Deposit tidak dapat diapprove.']);
        }

        $deposit->approve(Auth::id());
        
        // Update booking/reservation status
        if ($deposit->reservation) {
            $deposit->reservation->update(['status' => 'deposit_confirmed']);
            // Send notification
            $this->whatsappService->sendDepositApproved($deposit->reservation);
        } elseif ($deposit->booking) {
            $deposit->booking->update(['status' => 'deposit_confirmed']);
            // Send notification
            $this->whatsappService->sendDepositApproved($deposit->booking);
        }

        return back()->with('success', 'Deposit berhasil diapprove.');
    }

    /**
     * Reject deposit
     */
    public function reject(Request $request, Deposit $deposit)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        if ($deposit->status !== 'pending') {
            return back()->withErrors(['error' => 'Deposit tidak dapat direject.']);
        }

        $deposit->reject(Auth::id(), $request->rejection_reason);
        
        // Update booking/reservation status
        if ($deposit->reservation) {
            $deposit->reservation->update(['status' => 'deposit_rejected']);
            // Send notification
            $this->whatsappService->sendDepositRejected($deposit->reservation, $deposit);
        } elseif ($deposit->booking) {
            $deposit->booking->update(['status' => 'deposit_rejected']);
            // Send notification
            $this->whatsappService->sendDepositRejected($deposit->booking, $deposit);
        }

        return back()->with('success', 'Deposit berhasil direject.');
    }
}
