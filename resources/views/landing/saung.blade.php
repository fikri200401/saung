<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saung Nyonyah Ciledug - Reservasi Saung Lesehan</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            light: '#86efac',
                            DEFAULT: '#22c55e',
                            dark: '#16a34a',
                        },
                        wood: {
                            light: '#d4a574',
                            DEFAULT: '#8b6f47',
                            dark: '#6b5635',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }
        .hero-bg {
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), 
                        url('{{ asset('images/saung-hero.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-700 antialiased">

    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-sm py-4 shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-brand rounded-full flex items-center justify-center text-white font-serif font-bold">
                    <i class="fas fa-umbrella-beach"></i>
                </div>
                <div>
                    <span class="text-xl font-serif font-bold text-gray-800 block">Saung Nyonyah</span>
                    <span class="text-xs text-gray-500">Ciledug, Tangerang</span>
                </div>
            </a>

            <div class="hidden md:flex space-x-8 text-sm font-medium text-gray-600">
                <a href="#home" class="hover:text-brand transition">Beranda</a>
                <a href="#saungs" class="hover:text-brand transition">Saung</a>
                <a href="#menu" class="hover:text-brand transition">Menu</a>
                <a href="#promo" class="hover:text-brand transition">Promo</a>
                <a href="#location" class="hover:text-brand transition">Lokasi</a>
            </div>

            <div class="hidden md:flex space-x-3">
                @auth
                    <a href="{{ route('customer.dashboard') }}" class="px-5 py-2 border border-brand text-brand rounded-full text-sm font-medium hover:bg-green-50 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2 border border-brand text-brand rounded-full text-sm font-medium hover:bg-green-50 transition">Masuk</a>
                @endauth
                <a href="{{ route('customer.reservations.create') }}" class="px-5 py-2 bg-brand text-white rounded-full text-sm font-medium hover:bg-brand-dark transition shadow-lg shadow-green-200">
                    <i class="fas fa-calendar-check mr-1"></i> Reservasi Sekarang
                </a>
            </div>
            
            <button class="md:hidden text-gray-600 text-2xl"><i class="fas fa-bars"></i></button>
        </div>
    </nav>

    <!-- Hero Section with Background -->
    <header id="home" class="relative h-[600px] flex items-center justify-center overflow-hidden hero-bg">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 to-black/20"></div>

        <div class="relative z-10 text-center px-4 max-w-4xl">
            <span class="inline-block px-4 py-2 bg-brand/20 backdrop-blur-sm text-white rounded-full text-sm mb-4 border border-white/30">
                🏞️ Suasana Asri Tepi Sungai
            </span>
            <h1 class="text-5xl md:text-7xl font-serif font-bold text-white mb-6 leading-tight drop-shadow-lg">
                Saung Nyonyah Ciledug
            </h1>
            <p class="text-white/90 mb-8 text-xl font-light drop-shadow-md max-w-2xl mx-auto">
                Nikmati hidangan lezat di saung tradisional dengan pemandangan sungai yang menenangkan. Cocok untuk keluarga, gathering, dan acara spesial Anda.
            </p>
            <div class="flex gap-4 justify-center flex-wrap">
                <a href="{{ route('customer.reservations.create') }}" class="px-8 py-4 bg-brand text-white rounded-full font-semibold hover:bg-brand-dark transition shadow-2xl transform hover:-translate-y-1 flex items-center gap-2">
                    <i class="fas fa-calendar-alt"></i> Reservasi Saung
                </a>
                <a href="#menu" class="px-8 py-4 bg-white/20 backdrop-blur-sm text-white rounded-full font-semibold hover:bg-white/30 transition border-2 border-white/50 flex items-center gap-2">
                    <i class="fas fa-utensils"></i> Lihat Menu
                </a>
            </div>
        </div>

        <!-- Decorative Wave -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z" fill="white"/>
            </svg>
        </div>
    </header>

    <!-- Saung Section -->
    <section id="saungs" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-1 bg-brand/10 text-brand rounded-full text-sm font-semibold mb-3">
                    🏠 Pilihan Saung
                </span>
                <h2 class="text-4xl font-serif font-bold text-gray-900">Saung Kami</h2>
                <p class="text-gray-500 mt-3 text-lg max-w-2xl mx-auto">
                    Berbagai pilihan saung dengan kapasitas dan fasilitas yang berbeda, sesuai kebutuhan acara Anda
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $saungs = \App\Models\Saung::active()->get();
                @endphp
                @forelse($saungs as $saung)
                <div class="group bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 border border-gray-100">
                    <div class="h-56 overflow-hidden relative bg-gradient-to-br from-green-100 to-emerald-200">
                        @if($saung->image)
                            <img src="{{ asset('storage/' . $saung->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="{{ $saung->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-umbrella-beach text-6xl text-brand/30"></i>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full">
                            <span class="text-brand font-bold text-sm">{{ $saung->formatted_price }}/jam</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="font-serif font-bold text-xl text-gray-900">{{ $saung->name }}</h3>
                            <span class="bg-brand/10 text-brand text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                                <i class="fas fa-users"></i> {{ $saung->capacity }} orang
                            </span>
                        </div>
                        <p class="text-gray-500 text-sm mb-3 leading-relaxed">{{ $saung->description }}</p>
                        <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                            <i class="fas fa-map-marker-alt text-brand"></i>
                            <span>{{ $saung->location }}</span>
                        </div>
                        <a href="{{ route('customer.reservations.create') }}?saung={{ $saung->id }}" class="w-full block text-center py-3 bg-brand text-white rounded-lg hover:bg-brand-dark transition font-semibold">
                            Reservasi Saung Ini
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <i class="fas fa-home text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Belum ada saung tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="py-20 bg-gradient-to-b from-green-50 to-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-1 bg-brand/10 text-brand rounded-full text-sm font-semibold mb-3">
                    🍽️ Menu Favorit
                </span>
                <h2 class="text-4xl font-serif font-bold text-gray-900">Menu Populer Kami</h2>
                <p class="text-gray-500 mt-3 text-lg">Hidangan lezat yang wajib Anda coba</p>
            </div>

            @php
                $menus = \App\Models\Menu::active()->popular()->take(6)->get()->groupBy('category');
            @endphp

            @foreach($menus as $category => $categoryMenus)
            <div class="mb-12">
                <h3 class="text-2xl font-serif font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-utensils text-brand"></i> {{ $category }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($categoryMenus as $menu)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition p-5 flex gap-4 items-center border border-gray-100">
                        <div class="w-20 h-20 rounded-lg bg-gradient-to-br from-green-100 to-emerald-200 flex-shrink-0 overflow-hidden">
                            @if($menu->image)
                                <img src="{{ asset('storage/' . $menu->image) }}" class="w-full h-full object-cover" alt="{{ $menu->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-utensils text-2xl text-brand/40"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">{{ $menu->name }}</h4>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $menu->description }}</p>
                            <p class="text-brand font-bold mt-2">{{ $menu->formatted_price }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="text-center mt-10">
                <a href="#" class="inline-block px-8 py-3 border-2 border-brand text-brand rounded-full font-semibold hover:bg-brand hover:text-white transition">
                    Lihat Semua Menu
                </a>
            </div>
        </div>
    </section>

    <!-- Promo Section -->
    <section id="promo" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-1 bg-red-100 text-red-600 rounded-full text-sm font-semibold mb-3">
                    🎉 Promo Spesial
                </span>
                <h2 class="text-4xl font-serif font-bold text-gray-900">Promo Bulan Ini</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $vouchers = \App\Models\Voucher::where('is_active', true)
                        ->where('valid_until', '>=', now())
                        ->take(3)
                        ->get();
                @endphp
                @forelse($vouchers as $voucher)
                <div class="bg-gradient-to-br from-brand/5 to-emerald-50 p-6 rounded-2xl shadow-sm border-2 border-brand/20 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-brand/10 rounded-full -mr-12 -mt-12"></div>
                    <div class="relative">
                        <div class="w-16 h-16 rounded-full bg-brand flex items-center justify-center mb-4">
                            <i class="fas fa-percent text-white text-2xl"></i>
                        </div>
                        <h4 class="font-serif font-bold text-xl text-gray-800 mb-2">{{ $voucher->name }}</h4>
                        <p class="text-sm text-gray-600 mb-4">{{ $voucher->description }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Kode: <strong class="text-brand">{{ $voucher->code }}</strong></span>
                            <span class="text-xs text-gray-500">s/d {{ $voucher->valid_until->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-8 text-gray-500">
                    Belum ada promo tersedia saat ini
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Location Section -->
    <section id="location" class="py-20 bg-gray-100">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-1 bg-brand/10 text-brand rounded-full text-sm font-semibold mb-3">
                    📍 Lokasi
                </span>
                <h2 class="text-4xl font-serif font-bold text-gray-900">Temukan Kami</h2>
                <p class="text-gray-500 mt-3">Saung Nyonyah Ciledug, Tangerang</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="grid md:grid-cols-2">
                    <div class="p-10">
                        <h3 class="text-2xl font-serif font-bold text-gray-900 mb-6">Informasi Kontak</h3>
                        <div class="space-y-5">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-brand/10 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-brand"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Alamat</h4>
                                    <p class="text-gray-600 text-sm">Jl. Raya Ciledug, Tangerang, Banten</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-brand/10 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-phone text-brand"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Telepon</h4>
                                    <p class="text-gray-600 text-sm">0812-3456-7890</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-brand/10 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-clock text-brand"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Jam Operasional</h4>
                                    <p class="text-gray-600 text-sm">Setiap hari: 09:00 - 21:00 WIB</p>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('customer.reservations.create') }}" class="mt-8 inline-block w-full text-center px-8 py-4 bg-brand text-white rounded-xl font-semibold hover:bg-brand-dark transition shadow-lg">
                            <i class="fas fa-calendar-check mr-2"></i> Reservasi Sekarang
                        </a>
                    </div>
                    <div class="h-full min-h-[400px] bg-gray-200">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0!2d106.7!3d-6.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDInMDAuMCJF!5e0!3m2!1sen!2sid!4v1234567890" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-white font-serif font-bold text-xl mb-4">Saung Nyonyah</h3>
                    <p class="text-sm text-gray-400">Tempat makan dengan suasana asri di tepi sungai, cocok untuk keluarga dan acara spesial.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Menu</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#saungs" class="hover:text-brand transition">Saung</a></li>
                        <li><a href="#menu" class="hover:text-brand transition">Menu Makanan</a></li>
                        <li><a href="#promo" class="hover:text-brand transition">Promo</a></li>
                        <li><a href="{{ route('customer.reservations.create') }}" class="hover:text-brand transition">Reservasi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Layanan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-brand transition">Private Event</a></li>
                        <li><a href="#" class="hover:text-brand transition">Gathering</a></li>
                        <li><a href="#" class="hover:text-brand transition">Catering</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Ikuti Kami</h4>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand transition">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 text-center text-sm text-gray-500">
                <p>&copy; 2026 Saung Nyonyah Ciledug. Sistem Reservasi Berbasis Web dengan Notifikasi WhatsApp.</p>
            </div>
        </div>
    </footer>

</body>
</html>
