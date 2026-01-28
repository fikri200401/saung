<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('whatsapp_number', '081234567892')->first();

if ($user) {
    echo "====================================\n";
    echo "Customer: {$user->name}\n";
    echo "WhatsApp: {$user->whatsapp_number}\n";
    echo "Member: " . ($user->is_member ? 'Ya' : 'Tidak') . "\n";
    echo "====================================\n\n";
    
    $totalReservations = $user->reservations()->count();
    $completed = $user->reservations()->where('status', 'completed')->count();
    $cancelled = $user->reservations()->where('status', 'cancelled')->count();
    $confirmed = $user->reservations()->where('status', 'confirmed')->count();
    $depositConfirmed = $user->reservations()->where('status', 'deposit_confirmed')->count();
    $waitingDeposit = $user->reservations()->where('status', 'waiting_deposit')->count();
    
    $totalSpent = $user->reservations()->where('status', 'completed')->sum('final_price');
    
    echo "Total Transaksi: {$totalReservations}\n";
    echo "- Completed: {$completed}\n";
    echo "- Confirmed: {$confirmed}\n";
    echo "- Deposit Confirmed: {$depositConfirmed}\n";
    echo "- Waiting Deposit: {$waitingDeposit}\n";
    echo "- Cancelled: {$cancelled}\n";
    echo "\nTotal Spent: Rp " . number_format($totalSpent, 0, ',', '.') . "\n";
    echo "====================================\n";
} else {
    echo "Customer tidak ditemukan!\n";
}
