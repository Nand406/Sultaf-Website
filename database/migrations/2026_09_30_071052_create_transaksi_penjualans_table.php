<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_penjualans', function (Blueprint $table) {
            // Primary Key sesuai ERD
            $table->id('id_transaksi');
            
            // Foreign Keys
            // Dibuat nullable() karena ada transaksi "Guest" yang tidak memiliki id_user
            $table->unsignedBigInteger('id_user')->nullable(); 
            
            // Dibuat nullable() karena tidak semua transaksi menggunakan promo
            $table->unsignedBigInteger('id_promo')->nullable(); 
            
            // Atribut sesuai ER Diagram
            $table->string('tipe_pesanan'); // Contoh: Dine In, Take Away
            $table->string('status_pesanan')->default('Pending'); // Contoh: Pending, Selesai
            $table->string('metode_pembayaran'); // Contoh: Tunai, E-Wallet
            $table->string('diverifikasi_oleh')->nullable(); // <-- INI YANG MEMBUAT ERROR SEBELUMNYA
            $table->string('no_meja')->nullable(); // Nullable untuk Take Away
            $table->string('status_pembayaran')->default('Belum Bayar'); // Contoh: Lunas, Belum Bayar
            
            // Tipe data decimal untuk uang (15 digit total, 2 angka di belakang koma)
            $table->decimal('total_bayar', 15, 2)->default(0);
            $table->decimal('uang_bayar', 15, 2)->default(0);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2)->default(0);
            
            $table->timestamps();

            // Relasi ke tabel users
            // Pastikan tabel users sudah dimigrasi sebelum tabel ini
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
            
            // Relasi ke tabel promos
            // Pastikan tabel promos sudah dimigrasi sebelum tabel ini
            $table->foreign('id_promo')->references('id_promo')->on('promos')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_penjualan');
    }
};