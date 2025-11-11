<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';

    protected $fillable = [
        'user_id',
        'kos_id',
        'kode_unik',
        'nama_kamar',
        'harga_sewa',
        'minimal_waktu_sewa',
        'deskripsi',
        'tipe_kamar',
        'foto_kamar',
        'status',
        'review',
        'rating',
    ];

    protected $attributes = [
        'status' => 'tersedia'
    ];

    /**
     * Relasi ke user (penghuni)
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

    /**
     * Boot method untuk generate kode unik
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kamar) {
            if (empty($kamar->kode_unik)) {
                $kamar->kode_unik = Str::upper(Str::random(8));
            }
        });
    }

    // Scope untuk kamar yang tersedia
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    // Scope untuk kamar yang terisi
    public function scopeTerisi($query)
    {
        return $query->where('status', 'terisi');
    }

    // Scope untuk kamar dalam renovasi
    public function scopeRenovasi($query)
    {
        return $query->where('status', 'renovasi');
    }

    // Method untuk mengecek ketersediaan
    public function isTersedia()
    {
        return $this->status === 'tersedia';
    }

    public function isTerisi()
    {
        return $this->status === 'terisi';
    }

    public function isRenovasi()
    {
        return $this->status === 'renovasi';
    }

    // Method untuk mengubah status
    public function setTersedia()
    {
        $this->update([
            'status' => 'tersedia',
            'user_id' => null
        ]);
    }

    public function setTerisi($userId = null)
    {
        $this->update([
            'status' => 'terisi',
            'user_id' => $userId
        ]);
    }

    public function setRenovasi()
    {
        $this->update([
            'status' => 'renovasi',
            'user_id' => null
        ]);
    }

    // Accessor untuk badge status
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'tersedia' => 'bg-green-500 text-white',
            'terisi' => 'bg-blue-500 text-white',
            'renovasi' => 'bg-yellow-500 text-white'
        ];

        return $badges[$this->status] ?? 'bg-gray-500 text-white';
    }

    // Accessor untuk label status
    public function getStatusLabelAttribute()
    {
        $labels = [
            'tersedia' => 'Tersedia',
            'terisi' => 'Terisi',
            'renovasi' => 'Renovasi'
        ];

        return $labels[$this->status] ?? 'Unknown';
    }
}
