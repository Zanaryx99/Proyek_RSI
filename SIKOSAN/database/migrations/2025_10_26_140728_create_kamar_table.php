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
        Schema::create('kamar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kos_id')->constrained()->onDelete('cascade');
            $table->string('nama_kamar');
            $table->string('harga_sewa');
            $table->string('minimal_waktu_sewa'); // dalam bulan
            $table->text('deskripsi')->nullable();
            $table->string('tipe_kamar')->nullable(); // standard, deluxe, premium
            $table->string('foto_kamar')->nullable();
            $table->boolean('status_ketersediaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
