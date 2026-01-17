<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saung_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saung_id')->constrained('saungs')->onDelete('cascade');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['saung_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saung_schedules');
    }
};
