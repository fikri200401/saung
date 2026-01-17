<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // Makanan Utama
            [
                'name' => 'Nasi Goreng Kampung',
                'description' => 'Nasi goreng dengan bumbu tradisional, telur, ayam, dan kerupuk',
                'category' => 'Makanan Utama',
                'price' => 25000,
                'is_active' => true,
                'is_popular' => true,
            ],
            [
                'name' => 'Mie Goreng Spesial',
                'description' => 'Mie goreng dengan topping ayam, sayuran, dan telur',
                'category' => 'Makanan Utama',
                'price' => 23000,
                'is_active' => true,
                'is_popular' => true,
            ],
            [
                'name' => 'Ayam Goreng Kremes',
                'description' => 'Ayam goreng renyah dengan sambal dan lalapan',
                'category' => 'Makanan Utama',
                'price' => 30000,
                'is_active' => true,
                'is_popular' => true,
            ],
            [
                'name' => 'Ayam Bakar Madu',
                'description' => 'Ayam bakar dengan saus madu spesial',
                'category' => 'Makanan Utama',
                'price' => 32000,
                'is_active' => true,
                'is_popular' => false,
            ],
            [
                'name' => 'Gurame Goreng',
                'description' => 'Ikan gurame goreng crispy dengan sambal',
                'category' => 'Makanan Utama',
                'price' => 45000,
                'is_active' => true,
                'is_popular' => true,
            ],
            [
                'name' => 'Pepes Ikan Mas',
                'description' => 'Ikan mas pepes dengan bumbu tradisional',
                'category' => 'Makanan Utama',
                'price' => 35000,
                'is_active' => true,
                'is_popular' => false,
            ],
            [
                'name' => 'Sate Ayam',
                'description' => '10 tusuk sate ayam dengan bumbu kacang',
                'category' => 'Makanan Utama',
                'price' => 28000,
                'is_active' => true,
                'is_popular' => true,
            ],

            // Minuman
            [
                'name' => 'Es Teh Manis',
                'description' => 'Teh manis dingin segar',
                'category' => 'Minuman',
                'price' => 5000,
                'is_active' => true,
                'is_popular' => true,
            ],
            [
                'name' => 'Es Jeruk',
                'description' => 'Jus jeruk segar dengan es',
                'category' => 'Minuman',
                'price' => 8000,
                'is_active' => true,
                'is_popular' => true,
            ],
            [
                'name' => 'Es Kelapa Muda',
                'description' => 'Kelapa muda segar dengan es',
                'category' => 'Minuman',
                'price' => 12000,
                'is_active' => true,
                'is_popular' => true,
            ],
            [
                'name' => 'Jus Alpukat',
                'description' => 'Jus alpukat creamy',
                'category' => 'Minuman',
                'price' => 15000,
                'is_active' => true,
                'is_popular' => false,
            ],
            [
                'name' => 'Es Campur',
                'description' => 'Es campur dengan berbagai topping',
                'category' => 'Minuman',
                'price' => 18000,
                'is_active' => true,
                'is_popular' => true,
            ],
            [
                'name' => 'Kopi Hitam',
                'description' => 'Kopi hitam panas/dingin',
                'category' => 'Minuman',
                'price' => 7000,
                'is_active' => true,
                'is_popular' => false,
            ],
            [
                'name' => 'Kopi Susu',
                'description' => 'Kopi susu panas/dingin',
                'category' => 'Minuman',
                'price' => 10000,
                'is_active' => true,
                'is_popular' => true,
            ],

            // Snack
            [
                'name' => 'Pisang Goreng',
                'description' => 'Pisang goreng crispy',
                'category' => 'Snack',
                'price' => 10000,
                'is_active' => true,
                'is_popular' => true,
            ],
            [
                'name' => 'Tahu Isi',
                'description' => 'Tahu isi sayuran goreng',
                'category' => 'Snack',
                'price' => 12000,
                'is_active' => true,
                'is_popular' => false,
            ],
            [
                'name' => 'Bakwan Jagung',
                'description' => 'Bakwan jagung crispy',
                'category' => 'Snack',
                'price' => 8000,
                'is_active' => true,
                'is_popular' => false,
            ],
        ];

        foreach ($menus as $menuData) {
            Menu::create($menuData);
        }
    }
}
