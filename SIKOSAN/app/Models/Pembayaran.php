<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    
    // 1. Nama Tabel (Opsional jika menggunakan konvensi jamak: 'pembayarans')
    protected $table = 'pembayarans'; 
    
    // 2. Primary Key (Opsional jika menggunakan konvensi 'id')
    protected $primaryKey = 'id_pembayaran'; 
    
    // 3. Kolom yang Dapat Diisi Massal (Mass Assignable)
    // Kolom ini sesuai dengan kolom di migration Anda (kecuali id_tagihan yang sementara diabaikan di controller)
    protected $fillable = [
        // 'id_tagihan', // Dibiarkan kosong/dihapus karena sementara ini diabaikan di controller
        'nominal',
        'tanggal_bayar',
        'metode_pembayaran',
        'bukti_pembayaran', 
    ];

    // 4. Casting: Menentukan tipe data kolom
    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public $timestamps = false;

}