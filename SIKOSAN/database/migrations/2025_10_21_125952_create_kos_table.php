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
        Schema::create('kos', function (Blueprint $table) {
            $table->id();
            // Menghubungkan kos dengan pemiliknya (user)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_kos');
            $table->string('foto')->nullable();
            $table->enum('jenis', ['Pria', 'Wanita', 'Campur']);
            $table->text('lokasi');
            $table->text('fasilitas_umum');
            $table->text('peraturan_umum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kos');
    }
};
