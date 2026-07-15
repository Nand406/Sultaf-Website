<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique(); // e.g. SLT-8924
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Order Logistics (Checkout step 2)
            $table->enum('tipe_pesanan', ['dine_in', 'takeaway', 'online'])->default('online');
            $table->string('nomor_meja')->nullable();

            // Ringkasan harga
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('pajak', 12, 2)->default(0);
            $table->decimal('service_charge', 12, 2)->default(0);
            $table->decimal('diskon_member', 12, 2)->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);

            // Status pesanan: mengikuti Kitchen Display (Pending/Cooking/Ready) + tracking customer
            $table->enum('status_pesanan', [
                'menunggu_pembayaran', 'pending', 'cooking', 'ready', 'selesai', 'dibatalkan',
            ])->default('menunggu_pembayaran');

            // Status pembayaran: mengikuti Use Case "Pembayaran Online (Kasir)"
            $table->enum('status_pembayaran', ['menunggu', 'terverifikasi', 'ditolak'])->default('menunggu');
            $table->string('metode_pembayaran')->nullable(); // gopay/ovo/dana/qris/cash/card
            $table->string('bukti_pembayaran')->nullable();  // path upload bukti transfer/QRIS

            $table->text('catatan')->nullable(); // special instructions
            $table->timestamp('tgl_transaksi')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_penjualans');
    }
};
