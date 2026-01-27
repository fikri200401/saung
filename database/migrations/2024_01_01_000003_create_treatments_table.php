<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel tidak dibuat - sistem saung tidak menggunakan treatments
        // Saung menggunakan tabel 'menus' untuk produk/menu
    }

    public function down(): void
    {
        // No action needed
    }
};
