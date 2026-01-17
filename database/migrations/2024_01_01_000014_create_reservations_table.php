<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_code')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('saung_id')->constrained('saungs')->onDelete('cascade');
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->time('end_time')->nullable(); // waktu selesai
            $table->integer('duration')->default(2); // durasi dalam jam
            $table->integer('number_of_people'); // jumlah orang
            $table->enum('status', [
                'auto_approved', 
                'waiting_deposit', 
                'deposit_confirmed', 
                'deposit_rejected', 
                'confirmed',
                'expired', 
                'completed', 
                'cancelled'
            ])->default('auto_approved');
            $table->decimal('total_price', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2);
            $table->boolean('is_manual_entry')->default(false); // reservasi via WhatsApp
            $table->text('admin_notes')->nullable();
            $table->text('customer_notes')->nullable();
            $table->timestamps();
            
            $table->index(['reservation_date', 'reservation_time']);
            $table->index(['saung_id', 'reservation_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
