<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('benefits', function (Blueprint $table) {
            $table->id();
            $table->string('nama_benefit');             // e.g. "5% Off Total Bill"
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('poin_dibutuhkan');
            $table->string('tipe')->default('diskon');   // diskon | gratis_menu
            $table->decimal('nilai_diskon', 8, 2)->nullable(); // persen atau nominal
            $table->foreignId('menu_id')->nullable()->constrained()->nullOnDelete(); // jika reward = menu gratis
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('benefits');
    }
};
