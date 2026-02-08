@extends('layouts.admin')

@section('title', 'Kelola Jadwal - ' . $saung->name)

@section('content')
<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.saungs.index') }}" class="hover:text-green-600">Kelola Saung</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">{{ $saung->name }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Kelola Jadwal - {{ $saung->name }}</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Add Schedule Form -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Tambah Jadwal Baru</h2>
        <form action="{{ route('admin.saungs.schedules.store', $saung) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                    <select name="day_of_week" id="day_of_week" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                        <option value="">Pilih Hari</option>
                        <option value="monday" {{ old('day_of_week') == 'monday' ? 'selected' : '' }}>Senin</option>
                        <option value="tuesday" {{ old('day_of_week') == 'tuesday' ? 'selected' : '' }}>Selasa</option>
                        <option value="wednesday" {{ old('day_of_week') == 'wednesday' ? 'selected' : '' }}>Rabu</option>
                        <option value="thursday" {{ old('day_of_week') == 'thursday' ? 'selected' : '' }}>Kamis</option>
                        <option value="friday" {{ old('day_of_week') == 'friday' ? 'selected' : '' }}>Jumat</option>
                        <option value="saturday" {{ old('day_of_week') == 'saturday' ? 'selected' : '' }}>Sabtu</option>
                        <option value="sunday" {{ old('day_of_week') == 'sunday' ? 'selected' : '' }}>Minggu</option>
                    </select>
                </div>

                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                </div>

                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai</label>
                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i>
                        Tambah
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Schedules List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Mulai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Selesai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $dayNames = [
                        'monday' => 'Senin',
                        'tuesday' => 'Selasa',
                        'wednesday' => 'Rabu',
                        'thursday' => 'Kamis',
                        'friday' => 'Jumat',
                        'saturday' => 'Sabtu',
                        'sunday' => 'Minggu'
                    ];
                @endphp
                @forelse($schedules as $schedule)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $dayNames[$schedule->day_of_week] }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @php
                                $start = \Carbon\Carbon::parse($schedule->start_time);
                                $end = \Carbon\Carbon::parse($schedule->end_time);
                                $diff = $start->diffInHours($end);
                                $minutes = $start->diffInMinutes($end) % 60;
                            @endphp
                            {{ $diff }} jam {{ $minutes > 0 ? $minutes . ' menit' : '' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.saungs.schedules.toggle', [$saung, $schedule]) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs rounded {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $schedule->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <form action="{{ route('admin.saungs.schedules.delete', [$saung, $schedule]) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Belum ada jadwal untuk saung ini. Tambah jadwal menggunakan form di atas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.saungs.index') }}" class="inline-flex items-center gap-2 text-green-600 hover:text-green-700">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Daftar Saung
        </a>
    </div>
</div>
@endsection
