<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role sesuai aktor pada Use Case Diagram laporan:
            // Pelanggan, Member, Dapur/Koki, Kasir, Admin, Owner
            $table->enum('role', ['pelanggan', 'member', 'dapur', 'kasir', 'admin', 'owner'])
                ->default('pelanggan')
                ->after('email');

            $table->string('phone')->nullable()->after('role');

            // Untuk fitur Poin Pembelian (Benefit) khusus Member
            $table->unsignedInteger('points')->default(0)->after('phone');
            $table->string('tier')->default('Bronze')->after('points'); // Silver/Gold dst
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'points', 'tier']);
        });
    }
};
