<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tagihans', function (Blueprint $table) {
            // Primary Key
            $table->id('id_tagihan'); 
            
            // Foreign Keys (FK) - DISESUAIKAN
            
            // 1. FK ke Kamar (Referensi ke kolom 'id' di tabel 'kamar')
            // Menggunakan foreignId('kamar_id') yang merujuk ke 'id' di tabel 'kamar'
            $table->foreignId('kamar_id')->constrained('kamar')->onDelete('cascade');
            
            // 2. FK ke User (Menghubungkan tagihan ke penghuni tertentu)
            // Menggunakan foreignId('user_id') yang merujuk ke 'id' di tabel 'users'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Data Tagihan
            $table->string('jenis_tagihan', 100);
            $table->unsignedBigInteger('nominal'); 
            $table->date('tanggal_tagihan');
            $table->date('tanggal_jatuh_tempo');
            $table->enum('status', ['LUNAS', 'BELUM BAYAR', 'JATUH TEMPO'])->default('BELUM BAYAR');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};