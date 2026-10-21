<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            // Primary Key sesuai ERD
            $table->id('id_member');
            
            // Foreign Key ke tabel users
            $table->unsignedBigInteger('id_user');
            
            // Atribut sesuai ER Diagram
            $table->integer('total_poin')->default(0);
            $table->date('tanggal_daftar');
            
            $table->timestamps();

            // Relasi ke tabel users (pastikan tabel users sudah dimigrasi sebelumnya)
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};