@extends('layouts.admin')

@section('title', 'Edit Saung')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Saung</h1>
        <p class="text-gray-600">Update informasi saung {{ $saung->name }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.saungs.update', $saung) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Saung -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Saung *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $saung->name) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kapasitas -->
                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">Kapasitas (orang) *</label>
                    <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $saung->capacity) }}" required min="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                    @error('capacity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lokasi -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $saung->location) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                        placeholder="Misal: Lantai 1, Area Taman">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga per Jam -->
                <div>
                    <label for="price_per_hour" class="block text-sm font-medium text-gray-700 mb-2">Harga per Jam (Rp) *</label>
                    <input type="number" name="price_per_hour" id="price_per_hour" value="{{ old('price_per_hour', $saung->price_per_hour) }}" required min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                    @error('price_per_hour')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                    placeholder="Deskripsikan fasilitas dan keunggulan saung ini...">{{ old('description', $saung->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Gambar -->
            <div class="mt-6">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Saung</label>
                
                @if($saung->image)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                        <img src="{{ asset('storage/' . $saung->image) }}" alt="{{ $saung->name }}" class="w-48 h-48 object-cover rounded-lg">
                    </div>
                @endif

                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                    onchange="previewImage(event)">
                <p class="text-sm text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengganti.</p>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                
                <!-- Image Preview -->
                <div id="imagePreview" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview gambar baru:</p>
                    <img id="preview" src="" alt="Preview" class="w-48 h-48 object-cover rounded-lg">
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex gap-3">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-save mr-2"></i>
                    Update
                </button>
                <a href="{{ route('admin.saungs.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
