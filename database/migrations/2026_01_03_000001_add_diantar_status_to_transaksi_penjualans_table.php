<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Alur status pesanan setelah perubahan ini:
     * pending -> cooking -> ready -> diantar -> selesai
     *
     * - pending  -> cooking : diubah oleh DAPUR (mulai masak)
     * - cooking  -> ready   : diubah oleh DAPUR (selesai masak)
     * - ready    -> diantar : diubah oleh KASIR (pesanan diantar ke meja/diambil)
     * - diantar  -> selesai : diubah oleh KASIR (pesanan selesai)
     *
     * MySQL tidak mendukung ALTER COLUMN enum lewat Doctrine DBAL,
     * jadi pakai raw SQL langsung.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE transaksi_penjualans
            MODIFY status_pesanan ENUM(
                'menunggu_pembayaran', 'pending', 'cooking', 'ready', 'diantar', 'selesai', 'dibatalkan'
            ) DEFAULT 'menunggu_pembayaran'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE transaksi_penjualans
            MODIFY status_pesanan ENUM(
                'menunggu_pembayaran', 'pending', 'cooking', 'ready', 'selesai', 'dibatalkan'
            ) DEFAULT 'menunggu_pembayaran'
        ");
    }
};
