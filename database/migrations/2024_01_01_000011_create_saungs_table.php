<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saungs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('capacity'); // Kapasitas orang
            $table->string('location')->nullable(); // Lokasi saung
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->decimal('price_per_hour', 10, 2)->default(0); // Harga per jam
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saungs');
    }
};
