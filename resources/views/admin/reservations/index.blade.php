@extends('layouts.admin')

@section('title', 'Kelola Reservasi')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Kelola Reservasi Saung</h1>
            <p class="mt-2 text-sm text-gray-700">Lihat dan kelola semua reservasi saung</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.reservations.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 sm:w-auto">
                <i class="fas fa-plus mr-2"></i> Tambah Reservasi Manual
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="mt-6 bg-white shadow rounded-lg p-4">
        <form method="GET" action="{{ route('admin.reservations.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="waiting_deposit" {{ request('status') == 'waiting_deposit' ? 'selected' : '' }}>Waiting Deposit</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode atau nama customer..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
            </div>
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Kode</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Customer</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Saung</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tanggal & Waktu</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tamu</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($reservations as $reservation)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                    <div class="font-medium text-gray-900">{{ $reservation->reservation_code }}</div>
                                    @if($reservation->is_manual_entry)
                                        <span class="text-xs text-blue-600"><i class="fas fa-user-edit"></i> Manual</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="font-medium text-gray-900">{{ $reservation->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $reservation->user->whatsapp_number }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $reservation->saung->name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}</div>
                                    <div class="text-xs">{{ $reservation->reservation_time }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $reservation->guest_count }} orang
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    Rp {{ number_format($reservation->final_price, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
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
                                    <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $statusColors[$reservation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="{{ route('admin.reservations.show', $reservation->id) }}" class="text-emerald-600 hover:text-emerald-900">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-3 py-8 text-center text-sm text-gray-500">
                                    <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                                    <p>Tidak ada reservasi ditemukan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $reservations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
