<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Migration tidak diperlukan - reservation_id sudah ada di create_voucher_usages_table
    }

    public function down(): void
    {
        // No action needed
    }
};
