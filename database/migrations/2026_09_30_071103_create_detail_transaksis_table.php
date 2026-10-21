<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_transaksis', function (Blueprint $table) {
            // ERD tidak menyebutkan nama PK di sini, jadi kita pakai standar 'id'
            $table->id(); 
            $table->unsignedBigInteger('id_transaksi');
            $table->unsignedBigInteger('id_menu');
            
            $table->integer('jumlah');
            $table->integer('subtotal');
            $table->integer('harga_satuan');
            
            $table->timestamps();

            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi_penjualans')->onDelete('cascade');
            $table->foreign('id_menu')->references('id_menu')->on('menus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};