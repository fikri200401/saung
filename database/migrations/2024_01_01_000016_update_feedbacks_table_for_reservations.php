<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->nullable()->constrained('reservations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_id')->nullable()->constrained('menus')->onDelete('cascade');
            $table->foreignId('saung_id')->nullable()->constrained('saungs')->onDelete('cascade');
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
            
            $table->index('rating');
            $table->index(['menu_id', 'saung_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
