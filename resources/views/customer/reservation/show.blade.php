@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="container mx-auto px-4 max-w-5xl">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('customer.reservations.index') }}" class="inline-flex items-center text-green-600 hover:text-green-700 font-semibold transition group">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Riwayat Reservasi
            </a>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-xl shadow-sm animate-fade-in">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-xl shadow-sm animate-fade-in">
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

        {{-- Header --}}
        <div class="bg-white rounded-2xl shadow-lg border border-green-100 p-6 mb-6">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-transparent mb-2">Reservasi Saung {{ $reservation->saung->name }}</h1>
                    <p class="text-gray-600 font-mono text-sm bg-green-50 border border-green-200 px-3 py-1 rounded-full inline-block">{{ $reservation->reservation_code }}</p>
                </div>
                <div>
                    @if($reservation->status === 'auto_approved')
                        <span class="px-4 py-2 bg-green-100 text-green-700 text-sm font-semibold rounded-full">Auto Approved</span>
                    @elseif($reservation->status === 'waiting_deposit')
                        <span class="px-4 py-2 bg-yellow-100 text-yellow-700 text-sm font-semibold rounded-full">Menunggu Deposit</span>
                    @elseif($reservation->status === 'deposit_confirmed')
                        <span class="px-4 py-2 bg-blue-100 text-blue-700 text-sm font-semibold rounded-full">Deposit Terkonfirmasi</span>
                    @elseif($reservation->status === 'deposit_rejected')
                        <span class="px-4 py-2 bg-red-100 text-red-700 text-sm font-semibold rounded-full">Deposit Ditolak</span>
                    @elseif($reservation->status === 'completed')
                        <span class="px-4 py-2 bg-purple-100 text-purple-700 text-sm font-semibold rounded-full">Selesai</span>
                    @elseif($reservation->status === 'cancelled')
                        <span class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">Dibatalkan</span>
                    @elseif($reservation->status === 'expired')
                        <span class="px-4 py-2 bg-orange-100 text-orange-700 text-sm font-semibold rounded-full">Kadaluarsa</span>
                    @elseif($reservation->status === 'confirmed')
                        <span class="px-4 py-2 bg-blue-100 text-blue-700 text-sm font-semibold rounded-full">Terkonfirmasi</span>
                    @elseif($reservation->status === 'pending')
                        <span class="px-4 py-2 bg-yellow-100 text-yellow-700 text-sm font-semibold rounded-full">Menunggu Konfirmasi</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Reservation Details --}}
            <div class="bg-white rounded-2xl shadow-lg border border-green-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center mr-3 shadow-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    Detail Reservasi Saung
                </h2>
                
                <div class="space-y-5">
                    <div class="pb-5 border-b border-green-100">
                        <label class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-2 block">Tanggal & Waktu</label>
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="font-bold text-gray-900 text-lg">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d F Y') }}</p>
                        </div>
                        <p class="text-gray-600 ml-7">{{ $reservation->reservation_time }} - {{ $reservation->end_time }} WIB</p>
                    </div>

                    <div class="pb-5 border-b border-green-100">
                        <label class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-2 block">Saung</label>
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <p class="font-bold text-gray-900 text-lg">{{ $reservation->saung->name }}</p>
                        </div>
                        <p class="text-sm text-gray-600 ml-7">Kapasitas: {{ $reservation->saung->capacity }} orang</p>
                        @php
                            $startTime = \Carbon\Carbon::parse($reservation->reservation_time);
                            $endTime = \Carbon\Carbon::parse($reservation->end_time);
                            $duration = $startTime->diffInHours($endTime);
                            $saungPrice = $reservation->saung->price_per_hour * $duration;
                        @endphp
                        <p class="text-sm text-gray-600 ml-7">Harga: Rp {{ number_format($reservation->saung->price_per_hour, 0, ',', '.') }}/jam × {{ $duration }} jam = <span class="font-semibold text-green-600">Rp {{ number_format($saungPrice, 0, ',', '.') }}</span></p>
                        @if($reservation->saung->description)
                        <p class="text-sm text-gray-600 ml-7 mt-1">{{ $reservation->saung->description }}</p>
                        @endif
                    </div>

                    <div class="pb-5 border-b border-green-100">
                        <label class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-2 block">Jumlah Tamu</label>
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="font-bold text-gray-900">{{ $reservation->number_of_people }} orang</p>
                        </div>
                    </div>

                    @if($reservation->menus->count() > 0)
                    <div class="pb-5 border-b border-green-100">
                        <label class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-2 block">Menu Pesanan</label>
                        <div class="space-y-2">
                            @foreach($reservation->menus as $menu)
                            @php
                                $price = $menu->pivot->price ?? $menu->price;
                                $quantity = $menu->pivot->quantity;
                                $subtotal = $price * $quantity;
                            @endphp
                            <div class="flex items-center justify-between bg-green-50 rounded-lg p-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="font-semibold text-gray-800">{{ $menu->name }}</span>
                                    <span class="text-sm text-gray-600">x{{ $quantity }}</span>
                                </div>
                                <span class="text-sm font-bold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="pb-5 border-b border-green-100">
                        <label class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-2 block">Dibuat Pada</label>
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="font-bold text-gray-900">{{ $reservation->created_at->diffForHumans() }}</p>
                        </div>
                        <p class="text-sm text-gray-600 ml-7">{{ $reservation->created_at->format('d F Y, H:i') }} WIB</p>
                    </div>

                    @if($reservation->customer_notes)
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                        <label class="text-xs uppercase tracking-wide text-blue-700 font-semibold mb-2 block">Catatan Anda</label>
                        <p class="text-gray-800">{{ $reservation->customer_notes }}</p>
                    </div>
                    @endif

                    @if($reservation->admin_notes)
                    <div class="bg-orange-50 rounded-xl p-4 border border-orange-100">
                        <label class="text-xs uppercase tracking-wide text-orange-700 font-semibold mb-2 block">Catatan Admin</label>
                        <p class="text-gray-800">{{ $reservation->admin_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Payment Details --}}
            <div class="bg-white rounded-2xl shadow-lg border border-green-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-green-600 to-green-800 flex items-center justify-center mr-3 shadow-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    Detail Pembayaran
                </h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between text-gray-700">
                        <span>Total Biaya</span>
                        <span class="font-semibold">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</span>
                    </div>

                    @if($reservation->discount_amount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Diskon</span>
                        <span class="font-semibold">- Rp {{ number_format($reservation->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <div class="border-t-2 border-green-200 pt-4 flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">Total</span>
                        <span class="text-3xl font-bold bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-transparent">
                            Rp {{ number_format($reservation->final_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                {{-- Deposit Info --}}
                @if($reservation->deposit)
                <div class="mt-6 pt-6 border-t-2 border-green-200">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2z"/>
                        </svg>
                        Informasi Deposit
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Jumlah Deposit</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($reservation->deposit->amount, 0, ',', '.') }}</span>
                        </div>

                        {{-- Display Deposit Proof Only --}}
                        @if($reservation->deposit->proof_image)
                        <div class="mt-4">
                            <label class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-2 block">Bukti Transfer</label>
                            <a href="{{ asset('storage/' . $reservation->deposit->proof_image) }}" target="_blank" class="block">
                                <img src="{{ asset('storage/' . $reservation->deposit->proof_image) }}" alt="Bukti Transfer" class="w-full rounded-xl border-2 border-green-200 shadow-lg hover:shadow-xl transition cursor-pointer hover:border-green-400">
                            </a>
                            <p class="text-xs text-gray-500 mt-2 text-center">Klik gambar untuk memperbesar</p>
                        </div>
                        @else
                        <div class="mt-4 bg-amber-50 rounded-xl p-4 border border-amber-200">
                            <div class="text-center text-amber-700">
                                <svg class="w-12 h-12 text-amber-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <p class="text-sm font-medium text-amber-800">Menunggu Upload Bukti Transfer</p>
                                <p class="text-xs text-amber-600 mt-1">Silahkan hubungi admin untuk upload bukti pembayaran deposit</p>
                                <div class="mt-3 text-xs text-amber-700">
                                    <p class="font-semibold">📋 Info Transfer:</p>
                                    <p>BCA: 1234567890</p>
                                    <p>A.n: Saung Nyonyah</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Contact Admin --}}
                <div class="mt-6 pt-6 border-t-2 border-green-200">
                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                        <h4 class="font-bold text-gray-900 mb-2">🏡 Butuh Bantuan?</h4>
                        <p class="text-sm text-gray-600 mb-3">Hubungi Saung Nyonyah untuk informasi lebih lanjut</p>
                        <div class="space-y-2">
                            <a href="https://wa.me/6281234567890" target="_blank" class="block w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded-lg transition text-center">
                                💬 Chat WhatsApp
                            </a>
                            <a href="tel:081234567890" class="block w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition text-center">
                                📞 Telepon Langsung
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection