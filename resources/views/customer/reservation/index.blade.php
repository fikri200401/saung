@extends('layouts.app')

@section('title', 'Riwayat Reservasi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Riwayat Reservasi Saung</h1>
            <a href="{{ route('customer.reservations.create') }}" 
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold">
                + Buat Reservasi Baru
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(request('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">
                ✅ {{ request('success') }}
            </div>
        @endif

        @if($reservations->count() > 0)
            <div class="space-y-4">
                @foreach($reservations as $reservation)
                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <h3 class="text-xl font-bold text-gray-800">{{ $reservation->saung->name }}</h3>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if($reservation->status === 'auto_approved') bg-green-100 text-green-800
                                    @elseif($reservation->status === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($reservation->status === 'cancelled') bg-red-100 text-red-800
                                    @elseif($reservation->status === 'completed') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar text-green-600"></i>
                                    <span>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-clock text-green-600"></i>
                                    <span>{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }} - 
                                          {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user-clock text-blue-600"></i>
                                    <span class="text-xs">Dibuat {{ $reservation->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-users text-green-600"></i>
                                    <span>{{ $reservation->number_of_people }} orang</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-money-bill text-green-600"></i>
                                    <span class="font-semibold text-gray-800">{{ $reservation->formatted_final_price }}</span>
                                </div>
                            </div>

                            @if($reservation->menus->count() > 0)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-sm text-gray-600 font-semibold mb-2">Menu yang dipesan:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($reservation->menus as $menu)
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">
                                        {{ $menu->name }} ({{ $menu->pivot->quantity }}x)
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="ml-4">
                            <a href="{{ route('customer.reservations.show', $reservation->id) }}" 
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $reservations->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg mb-4">Belum ada riwayat reservasi</p>
                <a href="{{ route('customer.reservations.create') }}" 
                    class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold">
                    Buat Reservasi Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
