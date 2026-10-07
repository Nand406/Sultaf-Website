<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_penjualans', function (Blueprint $table) {
            $table->id('id_transaksi');
            // Nullable karena customer mungkin tidak login (guest)
            $table->unsignedBigInteger('id_user')->nullable(); 
            $table->unsignedBigInteger('id_promo')->nullable(); 
            
            $table->string('tipe_pesanan'); // misal: 'dine_in', 'take_away'
            $table->string('status_pesanan'); // misal: 'pending', 'proses', 'selesai'
            $table->string('metode_pembayaran'); // misal: 'dana', 'cash', 'qris'
            $table->string('status_pembayaran'); // misal: 'menunggu', 'lunas'
            
            // Perhatikan: Di ERD tertulis 'total_bayar', tapi di error sebelumnya 'total_harga'.
            // Saya gunakan 'total_bayar' sesuai ERD. Anda bisa menyesuaikannya.
            $table->integer('total_bayar'); 
            $table->integer('uang_bayar')->nullable();
            $table->string('no_meja')->nullable(); // Nullable jika take_away
            $table->integer('diskon')->default(0);
            
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
            $table->foreign('id_promo')->references('id_promo')->on('promos')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_penjualans');
    }
};