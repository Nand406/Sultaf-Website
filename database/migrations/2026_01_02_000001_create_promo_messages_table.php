<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_messages', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('pesan');

            // 'semua'  = tampil ke semua pengguna yang login (pelanggan & member)
            // 'member' = hanya tampil ke user dengan role member
            $table->enum('target_role', ['semua', 'member'])->default('member');

            // Opsional: filter tambahan berdasarkan tier member (Bronze/Silver/Gold).
            // Biarkan NULL supaya berlaku untuk semua tier.
            $table->string('target_tier')->nullable();

            $table->foreignId('dikirim_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_messages');
    }
};
