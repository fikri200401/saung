@extends('layouts.admin')

@section('title', 'Tambah Reservasi Manual')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('admin.reservations.index') }}" class="text-sm text-emerald-600 hover:text-emerald-800">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Reservasi
        </a>
    </div>

    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Tambah Reservasi Manual (dari WhatsApp)
            </h3>

            <form action="{{ route('admin.reservations.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Customer</label>
                        <select name="user_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            <option value="">Pilih Customer</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->whatsapp_number }})</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Saung</label>
                        <select name="saung_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            <option value="">Pilih Saung</option>
                            @foreach($saungs as $saung)
                                <option value="{{ $saung->id }}">{{ $saung->name }} (Kapasitas: {{ $saung->capacity }} orang)</option>
                            @endforeach
                        </select>
                        @error('saung_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Reservasi</label>
                            <input type="date" name="reservation_date" required min="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('reservation_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Waktu</label>
                            <input type="time" name="reservation_time" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('reservation_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Tamu</label>
                            <input type="number" name="guest_count" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('guest_count')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Durasi (Jam)</label>
                            <input type="number" name="duration" required min="1" max="12" value="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('duration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Menu (Opsional)</label>
                        <div id="menu-items" class="space-y-2">
                            <!-- Menu items will be added here -->
                        </div>
                        <button type="button" onclick="addMenuItem()" class="mt-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            <i class="fas fa-plus mr-2"></i> Tambah Menu
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan Admin</label>
                        <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"></textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <i class="fas fa-save mr-2"></i> Simpan Reservasi
                    </button>
                    <a href="{{ route('admin.reservations.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let menuItemIndex = 0;
const menus = @json($menus);

function addMenuItem() {
    const container = document.getElementById('menu-items');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <select name="menu_items[${menuItemIndex}][menu_id]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
            <option value="">Pilih Menu</option>
            ${menus.map(menu => `<option value="${menu.id}">${menu.name} - Rp ${menu.price.toLocaleString('id-ID')}</option>`).join('')}
        </select>
        <input type="number" name="menu_items[${menuItemIndex}][quantity]" min="1" placeholder="Qty" class="w-24 rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(div);
    menuItemIndex++;
}
</script>
@endsection
