<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // Menggunakan custom primary key sesuai ERD
            $table->id('id_user'); 
            $table->string('username');
            $table->string('password');
            $table->string('no_hp');
            $table->string('role'); // misal: 'admin', 'kasir', 'customer'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};