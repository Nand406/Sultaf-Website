<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_penjualans', function (Blueprint $table) {
            $table->string('nama_pelanggan')->nullable()->after('user_id');
            $table->string('no_telepon')->nullable()->after('nama_pelanggan');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_penjualans', function (Blueprint $table) {
            $table->dropColumn(['nama_pelanggan', 'no_telepon']);
        });
    }
};
