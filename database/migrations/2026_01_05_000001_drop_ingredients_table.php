<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fitur bahan baku dihapus sementara — akan dikembangkan lagi di tahap berikutnya
        Schema::dropIfExists('ingredients');
    }

    public function down(): void
    {
        Schema::create('ingredients', function ($table) {
            $table->id();
            $table->string('nama');
            $table->decimal('stok', 10, 2);
            $table->string('satuan', 20);
            $table->decimal('ambang_batas', 10, 2);
            $table->timestamps();
        });
    }
};
