<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('stok', 10, 2);
            $table->string('satuan', 20); // kg, gram, botol, liter, dst
            $table->decimal('ambang_batas', 10, 2); // batas minimum sebelum dianggap "low stock"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
