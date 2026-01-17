<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('reservation_id')->nullable()->constrained('reservations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('treatment_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('menu_id')->nullable()->constrained('menus')->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('saung_id')->nullable()->constrained('saungs')->onDelete('cascade');
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->boolean('is_visible')->default(true); // admin bisa hide/show
            $table->timestamps();
            
            $table->index('rating');
            $table->index(['treatment_id', 'doctor_id']);
            $table->index(['menu_id', 'saung_id']);
        });

        // Copy data from old feedbacks table if exists
        if (Schema::hasTable('feedbacks')) {
            DB::statement('INSERT INTO feedbacks_new (id, booking_id, user_id, treatment_id, doctor_id, rating, comment, is_visible, created_at, updated_at) SELECT id, booking_id, user_id, treatment_id, doctor_id, rating, comment, is_visible, created_at, updated_at FROM feedbacks');
            Schema::drop('feedbacks');
        }

        Schema::rename('feedbacks_new', 'feedbacks');
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
