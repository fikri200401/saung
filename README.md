# Sistem Reservasi Saung Nyonyah Ciledug

Sistem reservasi berbasis Laravel untuk Saung Nyonyah Ciledug - Restoran dengan konsep saung tradisional di tepi sungai.

**Judul Penelitian:** "Pengembangan Sistem Reservasi Berbasis Web dengan Notifikasi WhatsApp pada Saung Nyonyah Ciledug"

Repository ini berisi backend Laravel, frontend dengan Tailwind CSS dan Vue 3, serta layanan untuk mengelola reservasi, deposit, dan notifikasi WhatsApp.

## Fitur Utama

- **Customer Reservation Flow:**
  - Pilih tanggal kunjungan
  - Pilih jam & durasi
  - Pilih saung yang tersedia
  - Input jumlah orang
  - Pilih menu makanan/minuman (opsional)
  - Konfirmasi reservasi
  
- **Manajemen Saung:**
  - Kelola data saung (nama, kapasitas, lokasi, harga per jam)
  - Jadwal operasional saung
  - Status ketersediaan real-time
  
- **Manajemen Menu:**
  - Menu makanan & minuman
  - Kategori (makanan utama, minuman, snack)
  - Harga & ketersediaan
  
- **Sistem Deposit:**
  - Auto-approved untuk reservasi < 7 hari
  - Deposit required untuk reservasi >= 7 hari
  - Upload & verifikasi bukti pembayaran
  
- **Notifikasi WhatsApp:**
  - Konfirmasi reservasi
  - Reminder H-1
  - Status deposit
  - Integrasi Fonnte API
  
- **Admin Dashboard:**
  - Kelola reservasi
  - Kelola saung & menu
  - Verifikasi deposit
  - Laporan & statistik

## Tech Stack

- **Backend:** PHP (Laravel 11)
- **Database:** MySQL / MariaDB / SQLite
- **Frontend:** Tailwind CSS + Vue 3 (Composition API)
- **Build Tools:** Vite
- **WhatsApp API:** Fonnte
- **Node.js:** v18+

## Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+ dan npm
- MySQL / MariaDB
- Git

Untuk Windows, disarankan menggunakan **Laragon** atau **XAMPP** untuk setup lokal yang cepat.

## Quick Setup (Development)

1. **Clone repository**

```bat
git clone https://github.com/fikri200401/Reservasi.git
cd Reservasi
```

2. **Copy environment file**

```bat
copy .env.example .env
```

Edit `.env` dan sesuaikan:
- Database credentials
- WhatsApp API (Fonnte) credentials
- Mail settings (opsional)

3. **Install PHP dependencies**

```bat
composer install
```

4. **Generate application key**

```bat
php artisan key:generate
```

5. **Run migrations dan seeders**

```bat
php artisan migrate --seed
```

Seeder akan membuat:
- Admin user (admin@saungnyonyah.com / password)
- 5 Saung dengan berbagai kapasitas
- Menu makanan & minuman
- Sample data

6. **Install frontend dependencies dan build assets**

```bat
npm install
npm run dev   # atau npm run build untuk production
```

7. **Create storage symlink**

```bat
php artisan storage:link
```

8. **Start development server**

```bat
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

Atau gunakan Laragon/XAMPP untuk menjalankan server.

## Login Credentials

**Admin:**
- Email: admin@saungnyonyah.com
- Password: password

**Customer:** (daftar melalui halaman registrasi)

## Deposit Policy

- Reservasi dengan `reservation_date` < 7 hari dari sekarang: **Auto-approved** (tidak perlu deposit)
- Reservasi dengan `reservation_date` >= 7 hari: **Memerlukan deposit** (30% dari total atau minimal Rp 50.000)
- Batas upload deposit: 24 jam setelah reservasi dibuat

## Testing

```bat
php artisan test
```

## Kontribusi Penelitian

Sistem ini dikembangkan sebagai bagian dari penelitian tentang implementasi sistem reservasi online dengan notifikasi WhatsApp pada bisnis kuliner berbasis saung tradisional.

**Fitur Penelitian:**
- Analisis kebutuhan sistem reservasi real-time
- Implementasi WhatsApp Business API untuk notifikasi
- Manajemen ketersediaan saung dengan algoritma slot waktu
- User experience flow: tanggal → jam → saung → konfirmasi

## Notes on theme and UI

The project uses a pink/magenta theme (primary: `#EC4899`) and gradients. Dropdowns and components are implemented as Blade components (see `resources/views/components/`). If you see dark borders around dropdowns, those are controlled via Tailwind classes (e.g., `ring-black`) and can be adjusted in the component files.

## Contributing

We welcome contributions! Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

Quick guidelines:
- Fork the repository and create a feature branch
- Follow PSR-12 coding standards
- Write tests for new features
- Keep the pink/magenta theme consistent
- Open a pull request with a clear description

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Troubleshooting

If you run into issues, please provide:

```bat
php -v
composer -V
node -v
npm -v
```

And any relevant Laravel log output from `storage/logs/laravel.log`.

---

**Built with ❤️ for Beauty Skin from fikr and ghz**

<img width="1146" height="4547" alt="Image" src="https://github.com/user-attachments/assets/a0ee7ade-f375-4f03-a42a-b64b07a33943" />
