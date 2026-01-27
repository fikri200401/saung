@extends('layouts.admin')

@php
    // Detect if this deposit belongs to Reservation (new) or Booking (old)
    $isReservation = $deposit->reservation_id !== null;
    $bookingCode = $isReservation ? $deposit->reservation->reservation_code : $deposit->booking->booking_number;
@endphp

@section('title', 'Detail Deposit - ' . $bookingCode)

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('admin.deposits.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
            ← Kembali ke Daftar Deposit
        </a>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-red-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="flex-1">
                @foreach($errors->all() as $error)
                <p class="text-sm font-medium text-red-800">{{ $error }}</p>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Detail Deposit
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    @if($isReservation)
                        Reservasi #{{ $deposit->reservation->reservation_code }}
                    @else
                        Booking #{{ $deposit->booking->booking_number }}
                    @endif
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                @if($deposit->status === 'pending')
                    <form action="{{ route('admin.deposits.approve', $deposit) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Yakin approve deposit ini?')"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            ✓ Approve Deposit
                        </button>
                    </form>
                    <button type="button" 
                            onclick="showRejectModal()"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        ✗ Reject Deposit
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Deposit Information -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Informasi Deposit
                    </h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                @if($deposit->status === 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif($deposit->status === 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Approved
                                    </span>
                                @elseif($deposit->status === 'rejected')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ ucfirst($deposit->status) }}
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jumlah DP</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                Rp {{ number_format($deposit->amount, 0, ',', '.') }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dibuat Pada</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="font-medium text-blue-600">{{ $deposit->created_at->diffForHumans() }}</div>
                                <div class="text-xs text-gray-500">{{ $deposit->created_at->format('d/m/Y H:i') }}</div>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Deadline</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="font-medium text-orange-600">{{ $deposit->deadline_at->diffForHumans() }}</div>
                                <div class="text-xs text-gray-500">{{ $deposit->deadline_at->format('d/m/Y H:i') }}</div>
                                @if($deposit->deadline_at < now() && $deposit->status === 'pending')
                                    <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        EXPIRED
                                    </span>
                                @elseif($deposit->deadline_at < now()->addHours(6) && $deposit->status === 'pending')
                                    <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        Segera Expired
                                    </span>
                                @endif
                            </dd>
                        </div>

                        @if($deposit->proof_image)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Bukti Transfer</dt>
                            <dd class="mt-1">
                                <a href="{{ asset('storage/' . $deposit->proof_image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $deposit->proof_image) }}" 
                                         alt="Bukti Transfer" 
                                         class="max-w-full h-auto rounded-lg border-2 border-gray-300 shadow-lg hover:shadow-xl transition cursor-pointer hover:border-indigo-500">
                                </a>
                                <p class="text-xs text-gray-500 mt-2 text-center">Klik gambar untuk memperbesar</p>
                            </dd>
                        </div>
                        @endif

                        @if($deposit->verified_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Diverifikasi Pada</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="font-medium text-green-600">{{ $deposit->verified_at->diffForHumans() }}</div>
                                <div class="text-xs text-gray-500">{{ $deposit->verified_at->format('d/m/Y H:i') }}</div>
                            </dd>
                        </div>
                        @endif

                        @if($deposit->verifier)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Diverifikasi Oleh</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $deposit->verifier->name }}
                            </dd>
                        </div>
                        @endif

                        @if($deposit->status === 'rejected' && $deposit->rejection_reason)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Alasan Penolakan</dt>
                            <dd class="mt-1">
                                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <p class="text-sm text-red-800 font-medium">{{ $deposit->rejection_reason }}</p>
                                    </div>
                                </div>
                            </dd>
                        </div>
                        @endif

                        @if($deposit->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Catatan Customer</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $deposit->notes }}
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Booking Information -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        @if($isReservation)
                            Informasi Reservasi
                        @else
                            Informasi Booking
                        @endif
                    </h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                @if($isReservation)
                                    Kode Reservasi
                                @else
                                    No. Booking
                                @endif
                            </dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                @if($isReservation)
                                    {{ $deposit->reservation->reservation_code }}
                                @else
                                    {{ $deposit->booking->booking_number }}
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal & Waktu</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($isReservation)
                                    {{ \Carbon\Carbon::parse($deposit->reservation->reservation_date)->format('d/m/Y') }} 
                                    {{ $deposit->reservation->reservation_time }}
                                    @if($deposit->reservation->end_time)
                                        - {{ $deposit->reservation->end_time }}
                                    @endif
                                @else
                                    {{ \Carbon\Carbon::parse($deposit->booking->booking_date)->format('d/m/Y') }} 
                                    {{ $deposit->booking->booking_time }}
                                @endif
                            </dd>
                        </div>

                        @if($isReservation)
                            {{-- RESERVATION SYSTEM --}}
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Saung</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $deposit->reservation->saung->name ?? '-' }}
                                    @if($deposit->reservation->saung)
                                        <span class="text-gray-500">({{ $deposit->reservation->saung->capacity }} orang)</span>
                                    @endif
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jumlah Tamu</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $deposit->reservation->number_of_people }} orang
                                </dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Menu Pesanan</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($deposit->reservation->menus && $deposit->reservation->menus->count() > 0)
                                        <ul class="list-disc list-inside">
                                            @foreach($deposit->reservation->menus as $menu)
                                                <li>{{ $menu->name }} ({{ $menu->pivot->quantity }}x) - Rp {{ number_format($menu->pivot->price * $menu->pivot->quantity, 0, ',', '.') }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-500">Belum ada pesanan menu</span>
                                    @endif
                                </dd>
                            </div>
                        @else
                            {{-- BOOKING SYSTEM (OLD) --}}
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Treatment</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $deposit->booking->treatment->name }}
                                    <span class="text-gray-500">({{ $deposit->booking->treatment->duration }} menit)</span>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dokter</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $deposit->booking->doctor->name }}
                                    @if($deposit->booking->doctor->specialization)
                                        <span class="text-gray-500">- {{ $deposit->booking->doctor->specialization }}</span>
                                    @endif
                                </dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Harga</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                @if($isReservation)
                                    Rp {{ number_format($deposit->reservation->final_price, 0, ',', '.') }}
                                    @if($deposit->reservation->discount_amount > 0)
                                        <div class="text-xs text-gray-500">
                                            <span class="line-through">Rp {{ number_format($deposit->reservation->total_price, 0, ',', '.') }}</span>
                                            <span class="text-green-600 ml-1">Diskon: Rp {{ number_format($deposit->reservation->discount_amount, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                @else
                                    Rp {{ number_format($deposit->booking->treatment->price, 0, ',', '.') }}
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status 
                                @if($isReservation)
                                    Reservasi
                                @else
                                    Booking
                                @endif
                            </dt>
                            <dd class="mt-1">
                                @php
                                    $status = $isReservation ? $deposit->reservation->status : $deposit->booking->status;
                                @endphp
                                @if($status === 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif($status === 'waiting_deposit' || $status === 'waiting_dp')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        Waiting DP
                                    </span>
                                @elseif($status === 'deposit_confirmed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        DP Confirmed
                                    </span>
                                @elseif($status === 'confirmed' || $status === 'auto_approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Confirmed
                                    </span>
                                @elseif($status === 'completed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ ucfirst($status) }}
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Customer Info -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Customer
                    </h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                @if($isReservation)
                                    {{ $deposit->reservation->user->name }}
                                @else
                                    {{ $deposit->booking->user->name }}
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">WhatsApp</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @php
                                    $whatsapp = $isReservation ? $deposit->reservation->user->whatsapp_number : $deposit->booking->user->whatsapp_number;
                                @endphp
                                <a href="https://wa.me/{{ $whatsapp }}" 
                                   target="_blank"
                                   class="text-indigo-600 hover:text-indigo-900">
                                    {{ $whatsapp }}
                                </a>
                            </dd>
                        </div>

                        @php
                            $isMember = $isReservation ? $deposit->reservation->user->is_member : $deposit->booking->user->is_member;
                        @endphp
                        @if($isMember)
                        <div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                Member
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Proof Preview -->
            @if($deposit->payment_proof)
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Bukti Transfer
                    </h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <img src="{{ Storage::url($deposit->payment_proof) }}" 
                         alt="Bukti Transfer" 
                         class="w-full rounded-lg border border-gray-200">
                    <a href="{{ Storage::url($deposit->payment_proof) }}" 
                       target="_blank"
                       class="mt-3 block text-center text-sm text-indigo-600 hover:text-indigo-900">
                        Lihat Ukuran Penuh →
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">Reject Deposit</h3>
            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.deposits.reject', $deposit) }}">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Penolakan *</label>
                <textarea name="rejection_reason" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Jelaskan alasan penolakan deposit ini..."></textarea>
                <p class="text-xs text-gray-500 mt-1">Alasan akan dikirim ke customer via WhatsApp</p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 px-5 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-5 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-700 hover:to-red-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Reject Deposit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('rejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endsection

