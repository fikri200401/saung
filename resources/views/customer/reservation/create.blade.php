@extends('layouts.app')

@section('title', 'Buat Reservasi Saung')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Buat Reservasi Saung</h1>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <form id="reservationForm" method="POST" action="{{ route('customer.reservations.store') }}">
                @csrf

                <!-- Step 1: Pilih Tanggal -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">1. Pilih Tanggal</h3>
                    <input type="date" name="reservation_date" id="reservation_date" 
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                        required>
                    @error('reservation_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Step 2: Pilih Jam -->
                <div id="timeSlotSection" class="mb-6 hidden">
                    <h3 class="text-lg font-semibold mb-3">2. Pilih Waktu</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai</label>
                            <select name="start_time" id="start_time" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                required>
                                <option value="">Pilih Jam Mulai</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai</label>
                            <select name="end_time" id="end_time" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                required>
                                <option value="">Pilih Jam Selesai</option>
                            </select>
                        </div>
                    </div>
                    <!-- Hidden fields for controller -->
                    <input type="hidden" name="reservation_time" id="reservation_time">
                    <input type="hidden" name="duration" id="duration">
                </div>

                <!-- Step 3: Pilih Saung -->
                <div id="saungSection" class="mb-6 hidden">
                    <h3 class="text-lg font-semibold mb-3">3. Pilih Saung</h3>
                    <div id="saungList" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Saung cards will be loaded here -->
                    </div>
                    <input type="hidden" name="saung_id" id="saung_id">
                </div>

                <!-- Step 4: Jumlah Orang -->
                <div id="guestSection" class="mb-6 hidden">
                    <h3 class="text-lg font-semibold mb-3">4. Jumlah Tamu</h3>
                    <input type="number" name="number_of_people" id="number_of_people" 
                        min="1" max="50"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                        placeholder="Masukkan jumlah orang"
                        required>
                </div>

                <!-- Step 5: Pilih Menu (Optional) -->
                <div id="menuSection" class="mb-6 hidden">
                    <h3 class="text-lg font-semibold mb-3">5. Pilih Menu (Opsional)</h3>
                    @foreach($menus as $category => $items)
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 mb-2 capitalize">{{ $category }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($items as $menu)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <div class="flex items-center gap-3">
                                        @if($menu->image)
                                            <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" 
                                                class="w-16 h-16 object-cover rounded">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                                <i class="fas fa-utensils text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" class="menu-checkbox mr-2" 
                                                    data-menu-id="{{ $menu->id }}" 
                                                    data-menu-name="{{ $menu->name }}"
                                                    data-price="{{ $menu->price }}">
                                                <div>
                                                    <div class="font-semibold">{{ $menu->name }}</div>
                                                    <div class="text-sm text-green-600">{{ $menu->formatted_price }}</div>
                                                </div>
                                            </label>
                                            <input type="number" class="menu-quantity w-20 px-2 py-1 border border-gray-300 rounded mt-2 hidden"
                                                data-menu-id="{{ $menu->id }}"
                                                min="1" value="1" placeholder="Qty">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    <div id="menuInputs"></div>
                </div>

                <!-- Step 6: Voucher -->
                <div id="voucherSection" class="mb-6 hidden">
                    <h3 class="text-lg font-semibold mb-3">6. Kode Voucher (Opsional)</h3>
                    <div class="flex gap-2">
                        <input type="text" name="voucher_code" id="voucher_code" 
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                            placeholder="Masukkan kode voucher">
                        <button type="button" id="checkVoucherBtn" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Cek
                        </button>
                    </div>
                    <div id="voucherMessage" class="mt-2"></div>
                </div>

                <!-- Step 7: Catatan & Informasi Pembayaran -->
                <div id="notesSection" class="mb-6 hidden">
                    <h3 class="text-lg font-semibold mb-3">7. Catatan & Pembayaran</h3>
                    
                    <!-- No Rekening -->
                    <div class="bg-green-50 border border-green-300 rounded-lg p-4 mb-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-university text-green-600 mt-1"></i>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 mb-1">Transfer DP ke Rekening:</div>
                                <div class="text-2xl font-bold text-green-700 mb-1">55447760</div>
                                <div class="text-sm text-gray-600">BCA a/n Saung Nyonyah</div>
                                <div class="text-xs text-gray-500 mt-2">💡 Upload bukti transfer di bawah ini</div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Bukti DP -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Bukti Transfer DP (Opsional)
                        </label>
                        <div class="relative">
                            <input type="file" name="deposit_proof" id="deposit_proof" 
                                accept="image/jpeg,image/jpg,image/png"
                                class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-green-50 file:text-green-700
                                    hover:file:bg-green-100
                                    cursor-pointer border border-gray-300 rounded-lg p-2">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB. Upload sekarang atau nanti setelah transfer.</p>
                        <div id="depositPreview" class="mt-3 hidden">
                            <img id="depositPreviewImg" class="max-w-xs rounded-lg border border-gray-300" alt="Preview bukti DP">
                        </div>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Tambahan (Opsional)
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                            placeholder="Permintaan khusus, alergi makanan, dll..."></textarea>
                    </div>
                </div>

                <!-- Summary -->
                <div id="summarySection" class="mb-6 hidden">
                    <h3 class="text-lg font-semibold mb-3">Ringkasan</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div id="summaryContent"></div>
                        <div class="border-t border-gray-300 mt-3 pt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span id="totalPrice">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div id="submitSection" class="hidden">
                    <button type="submit" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg">
                        Buat Reservasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reservationForm');
    const dateInput = document.getElementById('reservation_date');
    const startTimeSelect = document.getElementById('start_time');
    const endTimeSelect = document.getElementById('end_time');
    const saungList = document.getElementById('saungList');
    const saungIdInput = document.getElementById('saung_id');
    const numberOfPeople = document.getElementById('number_of_people');

    // Form submit handler
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Memproses...';
        
        // Use FormData directly for file upload support
        const formData = new FormData(form);

        fetch('{{ route("customer.reservations.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                // Don't set Content-Type, let browser set it with boundary for multipart
            },
            body: formData
        })
        .then(res => {
            console.log('Response status:', res.status);
            return res.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                console.log('Redirecting to reservations list...');
                
                // Show temporary success message
                const successDiv = document.createElement('div');
                successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50';
                successDiv.innerHTML = '✅ ' + data.message + '<br><small>Mengalihkan...</small>';
                document.body.appendChild(successDiv);
                
                // Redirect after short delay
                setTimeout(function() {
                    window.location.replace('{{ route("customer.reservations.index") }}?success=' + encodeURIComponent(data.message));
                }, 500);
            } else {
                alert('❌ ' + data.message);
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });

    // Step 1: Load time slots when date selected
    dateInput.addEventListener('change', function() {
        const date = this.value;
        if (!date) return;

        fetch('{{ route("customer.reservations.available-time-slots") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ date: date })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                startTimeSelect.innerHTML = '<option value="">Pilih Jam Mulai</option>';
                data.slots.forEach(slot => {
                    startTimeSelect.innerHTML += `<option value="${slot}">${slot}</option>`;
                });
                document.getElementById('timeSlotSection').classList.remove('hidden');
            }
        });
    });

    // Step 2: Load end time options
    startTimeSelect.addEventListener('change', function() {
        const startTime = this.value;
        if (!startTime) return;

        const startHour = parseInt(startTime.split(':')[0]);
        endTimeSelect.innerHTML = '<option value="">Pilih Jam Selesai</option>';
        
        for (let i = startHour + 1; i <= 21; i++) {
            const time = i.toString().padStart(2, '0') + ':00';
            endTimeSelect.innerHTML += `<option value="${time}">${time}</option>`;
        }
    });

    // Step 3: Load available saungs
    endTimeSelect.addEventListener('change', function() {
        updateHiddenFields();
        loadAvailableSaungs();
    });

    function updateHiddenFields() {
        const startTime = startTimeSelect.value;
        const endTime = endTimeSelect.value;
        
        if (startTime && endTime) {
            const startHour = parseInt(startTime.split(':')[0]);
            const endHour = parseInt(endTime.split(':')[0]);
            const duration = endHour - startHour;
            
            document.getElementById('reservation_time').value = startTime;
            document.getElementById('duration').value = duration;
        }
    }

    function loadAvailableSaungs() {
        const date = dateInput.value;
        const startTime = startTimeSelect.value;
        const endTime = endTimeSelect.value;

        if (!date || !startTime || !endTime) return;

        const startHour = parseInt(startTime.split(':')[0]);
        const endHour = parseInt(endTime.split(':')[0]);
        const duration = endHour - startHour;

        fetch('{{ route("customer.reservations.available-saungs") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                date: date,
                time: startTime,
                duration: duration
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                saungList.innerHTML = '';
                if (data.saungs && data.saungs.length > 0) {
                    data.saungs.forEach(saung => {
                        const imageHtml = saung.image 
                            ? `<img src="/storage/${saung.image}" alt="${saung.name}" class="w-full h-32 object-cover">`
                            : `<div class="w-full h-32 bg-gray-200 flex items-center justify-center"><i class="fas fa-umbrella-beach text-gray-400 text-3xl"></i></div>`;
                        
                        saungList.innerHTML += `
                            <div class="border border-gray-300 rounded-lg overflow-hidden cursor-pointer saung-card hover:border-green-500" data-saung-id="${saung.id}" data-price="${saung.price_per_hour}">
                                ${imageHtml}
                                <div class="p-4">
                                    <h4 class="font-semibold">${saung.name}</h4>
                                    <p class="text-sm text-gray-600">Kapasitas: ${saung.capacity} orang</p>
                                    <p class="text-green-600 font-bold">${saung.formatted_price}/jam</p>
                                </div>
                            </div>
                        `;
                    });
                    document.getElementById('saungSection').classList.remove('hidden');
                    attachSaungClickHandlers();
                } else {
                    saungList.innerHTML = '<div class="col-span-2 text-center p-8 text-gray-500"><i class="fas fa-exclamation-circle text-4xl mb-3"></i><p>Tidak ada saung tersedia untuk waktu yang dipilih. Silakan pilih waktu lain.</p></div>';
                    document.getElementById('saungSection').classList.remove('hidden');
                }
            } else {
                console.error('Error:', data.message);
                alert('Error: ' + (data.message || 'Gagal memuat saung'));
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan saat memuat saung. Silakan coba lagi.');
        });
    }

    function attachSaungClickHandlers() {
        document.querySelectorAll('.saung-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.saung-card').forEach(c => c.classList.remove('border-green-500', 'bg-green-50'));
                this.classList.add('border-green-500', 'bg-green-50');
                saungIdInput.value = this.dataset.saungId;
                
                document.getElementById('guestSection').classList.remove('hidden');
                document.getElementById('menuSection').classList.remove('hidden');
                document.getElementById('voucherSection').classList.remove('hidden');
                document.getElementById('summarySection').classList.remove('hidden');
                document.getElementById('submitSection').classList.remove('hidden');
                
                updateSummary();
            });
        });
    }

    // Menu checkbox handlers
    document.querySelectorAll('.menu-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const qtyInput = this.closest('.border').querySelector('.menu-quantity');
            if (this.checked) {
                qtyInput.classList.remove('hidden');
            } else {
                qtyInput.classList.add('hidden');
            }
            updateMenuInputs();
            updateSummary();
        });
    });

    // Quantity input handlers
    document.querySelectorAll('.menu-quantity').forEach(input => {
        input.addEventListener('input', function() {
            updateMenuInputs();
            updateSummary();
        });
    });

    function updateMenuInputs() {
        const menuInputsDiv = document.getElementById('menuInputs');
        menuInputsDiv.innerHTML = '';
        
        let index = 0;
        document.querySelectorAll('.menu-checkbox:checked').forEach(checkbox => {
            const menuId = checkbox.dataset.menuId;
            const qtyInput = checkbox.closest('.border').querySelector('.menu-quantity');
            const quantity = qtyInput.value || 1;
            
            menuInputsDiv.innerHTML += `
                <input type="hidden" name="menus[${index}][id]" value="${menuId}">
                <input type="hidden" name="menus[${index}][quantity]" value="${quantity}">
            `;
            index++;
        });
    }

    numberOfPeople.addEventListener('input', updateSummary);

    function updateSummary() {
        const startTime = startTimeSelect.value;
        const endTime = endTimeSelect.value;
        const selectedSaung = document.querySelector('.saung-card.border-green-500');
        
        if (!startTime || !endTime || !selectedSaung) return;

        const startHour = parseInt(startTime.split(':')[0]);
        const endHour = parseInt(endTime.split(':')[0]);
        const hours = endHour - startHour;
        const pricePerHour = parseFloat(selectedSaung.dataset.price);
        const saungTotal = hours * pricePerHour;

        let menuTotal = 0;
        document.querySelectorAll('.menu-checkbox:checked').forEach(checkbox => {
            const price = parseFloat(checkbox.dataset.price);
            const qty = parseInt(checkbox.closest('.border').querySelector('.menu-quantity').value) || 1;
            menuTotal += price * qty;
        });

        const total = saungTotal + menuTotal;

        const summaryHtml = `
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span>Saung (${hours} jam)</span>
                    <span>Rp ${saungTotal.toLocaleString('id-ID')}</span>
                </div>
                ${menuTotal > 0 ? `
                <div class="flex justify-between">
                    <span>Menu</span>
                    <span>Rp ${menuTotal.toLocaleString('id-ID')}</span>
                </div>
                ` : ''}
            </div>
        `;

        document.getElementById('summaryContent').innerHTML = summaryHtml;
        document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
        
        // Show notes section after summary
        document.getElementById('notesSection').classList.remove('hidden');
    }

    // Image preview for deposit proof
    const depositProofInput = document.getElementById('deposit_proof');
    if (depositProofInput) {
        depositProofInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB');
                    this.value = '';
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('depositPreviewImg').src = event.target.result;
                    document.getElementById('depositPreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('depositPreview').classList.add('hidden');
            }
        });
    }
});
</script>
@endsection
