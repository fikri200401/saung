@extends('layouts.admin')

@section('title', 'Reservasi Management')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Reservasi Management</h1>
            <p class="mt-2 text-sm text-gray-700">Kelola semua reservasi saung dari customer</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.bookings.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">
                + Reservasi Manual (dari WhatsApp)
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="mt-6 bg-white shadow sm:rounded-lg p-4">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                    <option value="">Semua Status</option>
                    <option value="auto_approved" {{ request('status') == 'auto_approved' ? 'selected' : '' }}>Auto Approved</option>
                    <option value="waiting_deposit" {{ request('status') == 'waiting_deposit' ? 'selected' : '' }}>Menunggu DP</option>
                    <option value="deposit_confirmed" {{ request('status') == 'deposit_confirmed' ? 'selected' : '' }}>DP Terkonfirmasi</option>
                    <option value="deposit_rejected" {{ request('status') == 'deposit_rejected' ? 'selected' : '' }}>DP Ditolak</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
            </div>

            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Filter
                </button>
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
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Kode Reservasi</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Customer</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Saung</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Menu</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tanggal & Jam</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Dibuat</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Bukti DP</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Harga</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($bookings as $reservation)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    {{ $reservation->reservation_code }}
                                    @if($reservation->is_manual_entry ?? false)
                                        <span class="ml-2 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">WhatsApp</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="font-medium text-gray-900">{{ $reservation->user->name }}</div>
                                    <div class="text-gray-500">{{ $reservation->user->whatsapp_number }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $reservation->saung->name }}
                                    <div class="text-xs text-gray-400">{{ $reservation->duration }} jam</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    @if($reservation->menus && $reservation->menus->count() > 0)
                                        <div class="text-xs">
                                            @foreach($reservation->menus->take(2) as $menu)
                                                <div>• {{ $menu->name }}</div>
                                            @endforeach
                                            @if($reservation->menus->count() > 2)
                                                <div class="text-gray-400">+{{ $reservation->menus->count() - 2 }} lainnya</div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400">Tanpa menu</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $reservation->reservation_time }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="font-medium text-blue-600">{{ $reservation->created_at->diffForHumans() }}</div>
                                    <div class="text-xs text-gray-400">{{ $reservation->created_at->format('d/m H:i') }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    @php
                                        $statusColors = [
                                            'auto_approved' => 'bg-green-100 text-green-800',
                                            'waiting_deposit' => 'bg-yellow-100 text-yellow-800',
                                            'deposit_confirmed' => 'bg-blue-100 text-blue-800',
                                            'deposit_rejected' => 'bg-red-100 text-red-800',
                                            'expired' => 'bg-gray-100 text-gray-800',
                                            'completed' => 'bg-emerald-100 text-emerald-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusLabels = [
                                            'auto_approved' => 'Approved',
                                            'waiting_deposit' => 'Menunggu DP',
                                            'deposit_confirmed' => 'DP OK',
                                            'deposit_rejected' => 'DP Ditolak',
                                            'expired' => 'Expired',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan',
                                        ];
                                    @endphp
                                    <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $statusColors[$reservation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$reservation->status] ?? $reservation->status }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    @if($reservation->deposit && $reservation->deposit->proof_image)
                                        <a href="{{ asset('storage/' . $reservation->deposit->proof_image) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $reservation->deposit->proof_image) }}" 
                                                 alt="Bukti DP" 
                                                 class="w-16 h-16 object-cover rounded border border-gray-300 hover:opacity-75 transition">
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">Belum upload</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    Rp {{ number_format($reservation->total_price, 0, ',', '.') }}
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 space-x-2">
                                    @if($reservation->deposit && $reservation->deposit->proof_image && $reservation->deposit->status === 'pending')
                                        <form action="{{ route('admin.deposits.approve', $reservation->deposit) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Approve bukti DP ini?')"
                                                    class="text-green-600 hover:text-green-900 font-semibold"
                                                    title="Approve DP">
                                                ✓
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.deposits.reject', $reservation->deposit) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Reject bukti DP ini?')"
                                                    class="text-red-600 hover:text-red-900 font-semibold"
                                                    title="Reject DP">
                                                ✗
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.bookings.show', $reservation->id) }}" class="text-blue-600 hover:text-blue-900">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-3 py-8 text-center text-sm text-gray-500">
                                    Tidak ada data reservasi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
