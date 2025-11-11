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
        Schema::create('messages', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('from_user_id')->constrained('users')
                  ->onDelete('cascade')
                  ->comment('ID Pengirim Pesan');
            $table->foreignId('to_user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('ID Penerima Pesan');
            $table->text('message');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};