<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // makanan, minuman, snack, dll
            $table->decimal('price', 10, 2);
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false); // untuk tampil di landing page
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
