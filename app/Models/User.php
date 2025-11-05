<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'username',
        'email',
        'password',
        'foto_profile',
        'nama_lengkap', // Pastikan ada
        'jenis_kelamin', // Pastikan ada
        'no_telepon', // Pastikan ada
        'peran',
        'id_kos',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function kos()
    {
        return $this->hasMany(Kos::class, 'user_id');
    }

    // In app/Models/User.php
    public function kamar()
    {
        return $this->hasOne(Kamar::class, 'user_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'user_id');
    }


}
