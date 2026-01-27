@extends('layouts.admin')

@section('title', 'Detail Reservasi')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('admin.reservations.index') }}" class="text-sm text-emerald-600 hover:text-emerald-800">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Reservasi
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Reservasi {{ $reservation->reservation_code }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Detail lengkap reservasi saung</p>
            </div>
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'waiting_deposit' => 'bg-blue-100 text-blue-800',
                    'confirmed' => 'bg-green-100 text-green-800',
                    'completed' => 'bg-purple-100 text-purple-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                    'expired' => 'bg-gray-100 text-gray-800',
                ];
            @endphp
            <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $statusColors[$reservation->status] ?? 'bg-gray-100 text-gray-800' }}">
                {{ ucfirst($reservation->status) }}
            </span>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Customer</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="font-semibold">{{ $reservation->user->name }}</div>
                        <div class="text-xs text-gray-600">{{ $reservation->user->email }}</div>
                        <div class="text-xs text-gray-600">{{ $reservation->user->whatsapp_number }}</div>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Saung</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="font-semibold">{{ $reservation->saung->name }}</div>
                        @php
                            $startTime = \Carbon\Carbon::parse($reservation->reservation_time);
                            $endTime = \Carbon\Carbon::parse($reservation->end_time);
                            $duration = $startTime->diffInHours($endTime);
                            $saungPrice = $reservation->saung->price_per_hour * $duration;
                        @endphp
                        <div class="text-xs text-gray-600 mt-1">
                            Rp {{ number_format($reservation->saung->price_per_hour, 0, ',', '.') }}/jam × {{ $duration }} jam = Rp {{ number_format($saungPrice, 0, ',', '.') }}
                        </div>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Tanggal & Waktu</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d F Y') }} - {{ $reservation->reservation_time }} s/d {{ $reservation->end_time }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Jumlah Tamu</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $reservation->guest_count }} orang</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Menu Pesanan</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($reservation->menus->count() > 0)
                            <ul class="space-y-1">
                                @foreach($reservation->menus as $menu)
                                    @php
                                        $price = $menu->pivot->price ?? $menu->price;
                                        $quantity = $menu->pivot->quantity;
                                        $subtotal = $price * $quantity;
                                    @endphp
                                    <li>{{ $menu->name }} - {{ $quantity }}x @ Rp {{ number_format($price, 0, ',', '.') }} = Rp {{ number_format($subtotal, 0, ',', '.') }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-500">Tidak ada menu yang dipesan</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Harga</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div>Total Harga: Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</div>
                        @if($reservation->discount_amount > 0)
                            <div class="text-green-600">Diskon: -Rp {{ number_format($reservation->discount_amount, 0, ',', '.') }}</div>
                        @endif
                        <div class="font-bold text-lg">Final: Rp {{ number_format($reservation->final_price, 0, ',', '.') }}</div>
                    </dd>
                </div>
                @if($reservation->customer_notes)
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Catatan Customer</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $reservation->customer_notes }}</dd>
                </div>
                @endif
                @if($reservation->admin_notes)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Catatan Admin</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $reservation->admin_notes }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Deposit Information -->
    @if($reservation->deposit)
    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Deposit</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Detail pembayaran deposit</p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Jumlah Deposit</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-bold">
                        Rp {{ number_format($reservation->deposit->amount, 0, ',', '.') }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status Deposit</dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                        @if($reservation->deposit->status == 'pending')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @elseif($reservation->deposit->status == 'approved')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Approved</span>
                        @elseif($reservation->deposit->status == 'rejected')
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Rejected</span>
                        @endif
                    </dd>
                </div>
                @if($reservation->deposit->proof_image)
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Bukti Pembayaran</dt>
                    <dd class="mt-1 sm:mt-0 sm:col-span-2">
                        <a href="{{ asset('storage/' . $reservation->deposit->proof_image) }}" target="_blank" class="block">
                            <img src="{{ asset('storage/' . $reservation->deposit->proof_image) }}" 
                                 alt="Bukti Pembayaran" 
                                 class="w-64 h-auto rounded-lg border-2 border-gray-300 hover:border-emerald-500 transition cursor-pointer">
                        </a>
                        <p class="text-xs text-gray-500 mt-2">Klik untuk memperbesar</p>
                    </dd>
                </div>
                @endif
                @if($reservation->deposit->status == 'pending' && $reservation->deposit->proof_image)
                <div class="bg-white px-4 py-5 sm:px-6">
                    <div class="flex gap-3">
                        <form action="{{ route('admin.deposits.approve', $reservation->deposit->id) }}" method="POST" onsubmit="return confirm('Approve deposit ini?')">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                                <i class="fas fa-check mr-2"></i> Approve Deposit
                            </button>
                        </form>
                        <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700">
                            <i class="fas fa-times mr-2"></i> Reject Deposit
                        </button>
                    </div>
                </div>
                @endif
            </dl>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="mt-6 flex gap-3 flex-wrap">
        @if($reservation->status == 'auto_approved' || $reservation->status == 'pending')
            <form action="{{ route('admin.reservations.update-status', $reservation->id) }}" method="POST" class="inline-flex gap-2">
                @csrf
                <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">-- Ubah Status --</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-sync mr-2"></i> Update Status
                </button>
            </form>
        @endif

        @if($reservation->status == 'confirmed')
            <form action="{{ route('admin.reservations.complete', $reservation->id) }}" method="POST" onsubmit="return confirm('Tandai reservasi sebagai selesai?')">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700">
                    <i class="fas fa-check-circle mr-2"></i> Tandai Selesai
                </button>
            </form>
        @endif
        
        @if(in_array($reservation->status, ['pending', 'waiting_deposit', 'confirmed', 'auto_approved']))
            <form action="{{ route('admin.reservations.cancel', $reservation->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan reservasi?')">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700">
                    <i class="fas fa-times-circle mr-2"></i> Batalkan Reservasi
                </button>
            </form>
        @endif
    </div>

    <!-- Reject Deposit Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Alasan Penolakan Deposit</h3>
                <form action="{{ $reservation->deposit ? route('admin.deposits.reject', $reservation->deposit->id) : '#' }}" method="POST">
                    @csrf
                    <textarea name="rejection_reason" rows="4" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Masukkan alasan penolakan..."></textarea>
                    <div class="flex gap-3 mt-4">
                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Reject
                        </button>
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
