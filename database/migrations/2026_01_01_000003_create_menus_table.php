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
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nama_makanan');          // sesuai atribut di Diagram Kelas
            $table->text('deskripsi')->nullable();
            $table->string('foto_makanan')->nullable();
            $table->decimal('harga_makanan', 12, 2);
            $table->decimal('rating', 2, 1)->default(0);
            $table->unsignedTinyInteger('spice_level')->default(0); // 0-3 (icon cabai)
            $table->boolean('is_halal')->default(true);
            $table->boolean('habis')->default(false);   // status menu habis/tidak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
