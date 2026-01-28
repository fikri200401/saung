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
            ['name' => 'Paket Nila Goreng', 'category' => 'Makanan Utama', 'price' => 38000, 'description' => 'Paket nila goreng dengan nasi dan lauk', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Paket Lele Goreng', 'category' => 'Makanan Utama', 'price' => 30000, 'description' => 'Paket lele goreng dengan nasi dan lauk', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Paket Ayam Goreng', 'category' => 'Makanan Utama', 'price' => 35000, 'description' => 'Paket ayam goreng dengan nasi dan lauk', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Paket Ayam Goreng Komplit', 'category' => 'Makanan Utama', 'price' => 38000, 'description' => 'Paket ayam goreng komplit dengan nasi dan lauk', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Paket Ayam Sambal Matah', 'category' => 'Makanan Utama', 'price' => 38000, 'description' => 'Paket ayam sambal matah dengan nasi dan lauk', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Paket Ayam Goreng Penyet', 'category' => 'Makanan Utama', 'price' => 38000, 'description' => 'Paket ayam goreng penyet dengan nasi dan lauk', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Paket Bebek Goreng', 'category' => 'Makanan Utama', 'price' => 50000, 'description' => 'Paket bebek goreng dengan nasi dan lauk', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Paket Bebek Goreng Cabe Ijo', 'category' => 'Makanan Utama', 'price' => 50000, 'description' => 'Paket bebek goreng cabe ijo dengan nasi dan lauk', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Paket Gurame Goreng', 'category' => 'Makanan Utama', 'price' => 65000, 'description' => 'Paket gurame goreng dengan nasi dan lauk', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Paket Pepes Ikan Gurame', 'category' => 'Makanan Utama', 'price' => 65000, 'description' => 'Paket pepes ikan gurame dengan nasi dan lauk', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Paket Pecak Ikan Gabus', 'category' => 'Makanan Utama', 'price' => 45000, 'description' => 'Paket pecak ikan gabus dengan nasi dan lauk', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Paket Pecak Ikan Nila', 'category' => 'Makanan Utama', 'price' => 38000, 'description' => 'Paket pecak ikan nila dengan nasi dan lauk', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Paket Pecak Ayam', 'category' => 'Makanan Utama', 'price' => 38000, 'description' => 'Paket pecak ayam dengan nasi dan lauk', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Paket Empal Goreng', 'category' => 'Makanan Utama', 'price' => 40000, 'description' => 'Paket empal goreng dengan nasi dan lauk', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Paket Empal Cabe Ijo', 'category' => 'Makanan Utama', 'price' => 40000, 'description' => 'Paket empal cabe ijo dengan nasi dan lauk', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Paket Iga Penyet', 'category' => 'Makanan Utama', 'price' => 55000, 'description' => 'Paket iga penyet dengan nasi dan lauk', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Paket Sop Iga', 'category' => 'Makanan Utama', 'price' => 55000, 'description' => 'Paket sop iga dengan nasi dan lauk', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Paket Sop Kepala Kakap', 'category' => 'Makanan Utama', 'price' => 55000, 'description' => 'Paket sop kepala kakap dengan nasi dan lauk', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Paket Soto Betawi Daging', 'category' => 'Makanan Utama', 'price' => 40000, 'description' => 'Paket soto betawi daging dengan nasi', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Paket Soto Betawi Jeroan', 'category' => 'Makanan Utama', 'price' => 38000, 'description' => 'Paket soto betawi jeroan dengan nasi', 'is_active' => true, 'is_popular' => false],

            // Makanan Pendamping
            ['name' => 'Indomie Kornet Spesial', 'category' => 'Makanan Pendamping', 'price' => 20000, 'description' => 'Indomie dengan kornet spesial', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Karedok', 'category' => 'Makanan Pendamping', 'price' => 20000, 'description' => 'Sayuran segar dengan bumbu kacang', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Tumis Kangkung', 'category' => 'Makanan Pendamping', 'price' => 20000, 'description' => 'Kangkung tumis', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Tumis Toge', 'category' => 'Makanan Pendamping', 'price' => 20000, 'description' => 'Toge tumis', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Tumis Genjer', 'category' => 'Makanan Pendamping', 'price' => 20000, 'description' => 'Genjer tumis', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Jengkol Pecak', 'category' => 'Makanan Pendamping', 'price' => 20000, 'description' => 'Jengkol dengan bumbu pecak', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Jengkol Sambelan', 'category' => 'Makanan Pendamping', 'price' => 15000, 'description' => 'Jengkol sambal', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Jengkol Goreng', 'category' => 'Makanan Pendamping', 'price' => 15000, 'description' => 'Jengkol goreng', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Pete Goreng / Rebus', 'category' => 'Makanan Pendamping', 'price' => 15000, 'description' => 'Pete goreng atau rebus', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Perda Goreng', 'category' => 'Makanan Pendamping', 'price' => 13000, 'description' => 'Perda goreng', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Sayur Asem', 'category' => 'Makanan Pendamping', 'price' => 10000, 'description' => 'Sayur asem', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Kuah Pecak', 'category' => 'Makanan Pendamping', 'price' => 10000, 'description' => 'Kuah pecak', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Sambal Dadak', 'category' => 'Makanan Pendamping', 'price' => 10000, 'description' => 'Sambal dadak', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Kerupuk', 'category' => 'Makanan Pendamping', 'price' => 8000, 'description' => 'Kerupuk', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Nasi', 'category' => 'Makanan Pendamping', 'price' => 6000, 'description' => 'Nasi putih', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Tempe', 'category' => 'Makanan Pendamping', 'price' => 2000, 'description' => 'Tempe goreng', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Tahu', 'category' => 'Makanan Pendamping', 'price' => 2000, 'description' => 'Tahu goreng', 'is_active' => true, 'is_popular' => false],

            // Dessert
            ['name' => 'Roti Bakar Cokelat Keju', 'category' => 'Dessert', 'price' => 20000, 'description' => 'Roti bakar dengan topping cokelat dan keju', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Waffel', 'category' => 'Dessert', 'price' => 20000, 'description' => 'Waffel', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Pancake', 'category' => 'Dessert', 'price' => 20000, 'description' => 'Pancake', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Pisang Goreng Keju', 'category' => 'Dessert', 'price' => 20000, 'description' => 'Pisang goreng dengan topping keju', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Buah Campur', 'category' => 'Dessert', 'price' => 20000, 'description' => 'Buah campur segar', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Rujak', 'category' => 'Dessert', 'price' => 20000, 'description' => 'Rujak buah', 'is_active' => true, 'is_popular' => false],

            // Cemilan
            ['name' => 'Tempe Mendoan', 'category' => 'Cemilan', 'price' => 20000, 'description' => 'Tempe mendoan', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Bakwan Sayur', 'category' => 'Cemilan', 'price' => 20000, 'description' => 'Bakwan sayur', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Bakwan Jagung', 'category' => 'Cemilan', 'price' => 20000, 'description' => 'Bakwan jagung', 'is_active' => true, 'is_popular' => false],
            ['name' => 'French Fries', 'category' => 'Cemilan', 'price' => 20000, 'description' => 'Kentang goreng', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Combo Snack', 'category' => 'Cemilan', 'price' => 35000, 'description' => 'Sosis, nugget, kentang, otak-otak (2)', 'is_active' => true, 'is_popular' => true],

            // Kopi
            ['name' => 'Espresso', 'category' => 'Kopi', 'price' => 18000, 'description' => 'Espresso', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Americano', 'category' => 'Kopi', 'price' => 18000, 'description' => 'Americano', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Hot Cappucino', 'category' => 'Kopi', 'price' => 20000, 'description' => 'Cappucino panas', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Hot Chocolate', 'category' => 'Kopi', 'price' => 20000, 'description' => 'Cokelat panas', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Kopi Bombon', 'category' => 'Kopi', 'price' => 20000, 'description' => 'Kopi bombon', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Kopi Kapal Api', 'category' => 'Kopi', 'price' => 10000, 'description' => 'Kopi kapal api', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Kopi Mix', 'category' => 'Kopi', 'price' => 10000, 'description' => 'Kopi mix', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Kopi ABC Susu', 'category' => 'Kopi', 'price' => 10000, 'description' => 'Kopi ABC susu', 'is_active' => true, 'is_popular' => false],
            ['name' => 'White Coffee', 'category' => 'Kopi', 'price' => 10000, 'description' => 'White coffee', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Ice Americano', 'category' => 'Kopi', 'price' => 20000, 'description' => 'Americano dingin', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Ice Chocolate', 'category' => 'Kopi', 'price' => 20000, 'description' => 'Cokelat dingin', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Ice Cappucino', 'category' => 'Kopi', 'price' => 20000, 'description' => 'Cappucino dingin', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Es Kopi Susu Gula Aren', 'category' => 'Kopi', 'price' => 20000, 'description' => 'Es kopi susu dengan gula aren', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Es Kopi Latte', 'category' => 'Kopi', 'price' => 20000, 'description' => 'Es kopi latte', 'is_active' => true, 'is_popular' => true],

            // Hot Drink
            ['name' => 'Chamomile Tea', 'category' => 'Hot Drink', 'price' => 15000, 'description' => 'Teh chamomile panas', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Pappermint Tea', 'category' => 'Hot Drink', 'price' => 15000, 'description' => 'Teh peppermint panas', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Jeruk Nipis Panas', 'category' => 'Hot Drink', 'price' => 15000, 'description' => 'Jeruk nipis panas', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Jeruk Lemon Panas', 'category' => 'Hot Drink', 'price' => 15000, 'description' => 'Jeruk lemon panas', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Jeruk Panas', 'category' => 'Hot Drink', 'price' => 15000, 'description' => 'Jeruk panas', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Teh Sereh Jahe', 'category' => 'Hot Drink', 'price' => 10000, 'description' => 'Teh sereh jahe', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Wedang Uwuh', 'category' => 'Hot Drink', 'price' => 10000, 'description' => 'Wedang uwuh', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Wedang Mataram', 'category' => 'Hot Drink', 'price' => 10000, 'description' => 'Wedang mataram', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Wedang Secang', 'category' => 'Hot Drink', 'price' => 10000, 'description' => 'Wedang secang', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Teh Manis Panas', 'category' => 'Hot Drink', 'price' => 5000, 'description' => 'Teh manis panas', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Teh Tawar Panas', 'category' => 'Hot Drink', 'price' => 3000, 'description' => 'Teh tawar panas', 'is_active' => true, 'is_popular' => false],

            // Juice
            ['name' => 'Jus Mangga', 'category' => 'Juice', 'price' => 20000, 'description' => 'Jus mangga', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Jus Semangka', 'category' => 'Juice', 'price' => 20000, 'description' => 'Jus semangka', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Jus Strawberry', 'category' => 'Juice', 'price' => 20000, 'description' => 'Jus strawberry', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Jus Alpukat', 'category' => 'Juice', 'price' => 20000, 'description' => 'Jus alpukat', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Jus Jambu', 'category' => 'Juice', 'price' => 20000, 'description' => 'Jus jambu', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Jus Melon', 'category' => 'Juice', 'price' => 20000, 'description' => 'Jus melon', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Jus Buah Naga', 'category' => 'Juice', 'price' => 20000, 'description' => 'Jus buah naga', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Jus Anggur', 'category' => 'Juice', 'price' => 20000, 'description' => 'Jus anggur', 'is_active' => true, 'is_popular' => false],

            // Cold Drink
            ['name' => 'Ice Strawberry Squash', 'category' => 'Cold Drink', 'price' => 22000, 'description' => 'Es strawberry squash', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Ice Lime Squash', 'category' => 'Cold Drink', 'price' => 20000, 'description' => 'Es lime squash', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Ice lychee yakult', 'category' => 'Cold Drink', 'price' => 20000, 'description' => 'Es lychee yakult', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Ice Lemon Yakult', 'category' => 'Cold Drink', 'price' => 20000, 'description' => 'Es lemon yakult', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Ice Mango Yakult', 'category' => 'Cold Drink', 'price' => 20000, 'description' => 'Es mango yakult', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Ice Orange Yakult', 'category' => 'Cold Drink', 'price' => 20000, 'description' => 'Es orange yakult', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Ice Jeruk Nipis Yakult', 'category' => 'Cold Drink', 'price' => 20000, 'description' => 'Es jeruk nipis yakult', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Ice Lychee Tea', 'category' => 'Cold Drink', 'price' => 20000, 'description' => 'Es lychee tea', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Ice Lemon Tea', 'category' => 'Cold Drink', 'price' => 20000, 'description' => 'Es lemon tea', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Kelapa Bulat', 'category' => 'Cold Drink', 'price' => 20000, 'description' => 'Kelapa bulat', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Kopi Beer', 'category' => 'Cold Drink', 'price' => 20000, 'description' => 'Kopi beer', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Es Jeruk Kelapa', 'category' => 'Cold Drink', 'price' => 20000, 'description' => 'Es jeruk kelapa', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Es Jeruk', 'category' => 'Cold Drink', 'price' => 15000, 'description' => 'Es jeruk', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Es Jeruk Nipis', 'category' => 'Cold Drink', 'price' => 15000, 'description' => 'Es jeruk nipis', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Es Kelapa', 'category' => 'Cold Drink', 'price' => 15000, 'description' => 'Es kelapa', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Es Teh Manis', 'category' => 'Cold Drink', 'price' => 7000, 'description' => 'Es teh manis', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Es Teh Tawar', 'category' => 'Cold Drink', 'price' => 5000, 'description' => 'Es teh tawar', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Air Mineral', 'category' => 'Cold Drink', 'price' => 7000, 'description' => 'Air mineral', 'is_active' => true, 'is_popular' => true],
            ['name' => 'Es Krim Lilin', 'category' => 'Cold Drink', 'price' => 8000, 'description' => 'Es krim lilin', 'is_active' => true, 'is_popular' => false],
            ['name' => 'Es Batu', 'category' => 'Cold Drink', 'price' => 2000, 'description' => 'Es batu', 'is_active' => true, 'is_popular' => false],
        ];

        foreach ($menus as $menuData) {
            Menu::create($menuData);
        }
    }
}
