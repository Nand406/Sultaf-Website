<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // Harga modal (HPP) per menu — dipakai untuk hitung keuntungan (revenue - modal)
            // di grafik keuntungan Owner. Boleh dibiarkan 0 kalau belum diisi Admin.
            $table->decimal('harga_modal', 12, 2)->default(0)->after('harga_makanan');
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('harga_modal');
        });
    }
};
