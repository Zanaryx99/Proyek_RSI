<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class kamar extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara manual
    protected $table = 'kamar';

    protected $fillable = [
        'user_id',
        'kamar_id',
        'nama_kamar',
        'harga_sewa',
        'minimal_waktu_sewa',
        'deskripsi',
        'tipe_kamar',
        'foto_kamar',
        'status_ketersediaan'
    ];

    /**
     * Relasi ke user (pemilik)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke kos
     */
    public function kos()
    {
        return $this->belongsTo(Kos::class);
    }

    /**
     * Accessor untuk format harga
     */
    public function getHargaSewaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_sewa, 0, ',', '.');
    }
}
