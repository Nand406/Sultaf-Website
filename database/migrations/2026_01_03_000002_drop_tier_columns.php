<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sistem member disederhanakan: hanya ada 1 tipe member,
     * semua mendapat benefit & promosi yang sama. Kolom "tier"
     * (Bronze/Silver/Gold) dan filter tier di pesan promo tidak
     * diperlukan lagi.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'tier')) {
                $table->dropColumn('tier');
            }
        });

        Schema::table('promo_messages', function (Blueprint $table) {
            if (Schema::hasColumn('promo_messages', 'target_tier')) {
                $table->dropColumn('target_tier');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tier')->default('Bronze')->after('points');
        });

        Schema::table('promo_messages', function (Blueprint $table) {
            $table->string('target_tier')->nullable()->after('target_role');
        });
    }
};
