<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // Gunakan nama kolom yang dipakai di Livewire dan Migrasi Anda
    protected $fillable = [
        'from_user_id', 
        'to_user_id', 
        'message', // Ubah dari 'content'
        'is_read', 
    ];

    // Relasi ke Pengirim (fromUser)
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    // Relasi ke Penerima (toUser)
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
    
    // HAPUS fungsi user() dan recipient() jika Anda hanya menggunakan fromUser/toUser
}