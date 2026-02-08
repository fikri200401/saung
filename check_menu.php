<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "====================================\n";
echo "CHECKING MENU DATA\n";
echo "====================================\n\n";

// Get all menus
$menus = App\Models\Menu::all();

echo "Total Menus: " . $menus->count() . "\n\n";

foreach ($menus as $menu) {
    echo "Menu: {$menu->name}\n";
    echo "  Category: {$menu->category}\n";
    echo "  Price: {$menu->formatted_price}\n";
    echo "  Active: " . ($menu->is_active ? 'Yes' : 'No') . "\n";
    echo "  Popular: " . ($menu->is_popular ? 'Yes' : 'No') . "\n";
    echo "  Image: " . ($menu->image ?? 'No image') . "\n";
    if ($menu->image) {
        $fullPath = storage_path('app/public/' . $menu->image);
        echo "  Image exists: " . (file_exists($fullPath) ? 'Yes' : 'No') . "\n";
    }
    echo "\n";
}

echo "====================================\n";
echo "POPULAR MENUS\n";
echo "====================================\n\n";

$popularMenus = App\Models\Menu::active()->popular()->get();
echo "Total Popular Active Menus: " . $popularMenus->count() . "\n";

foreach ($popularMenus as $menu) {
    echo "- {$menu->name} ({$menu->category})\n";
}
