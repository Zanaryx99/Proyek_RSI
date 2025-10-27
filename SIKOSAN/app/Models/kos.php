<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kos extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_kos',
        'foto',
        'jenis',
        'lokasi',
        'fasilitas_umum',
        'peraturan_umum',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kamars()
    {
        return $this->hasMany(Kamar::class);
    }

    protected $table = 'kos';
}
