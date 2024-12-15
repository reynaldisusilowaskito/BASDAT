<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tentukan tabel yang digunakan
    protected $table = 'user';
    protected $primaryKey = 'iduser';
    public $timestamps = false;

    // Kolom yang dapat diisi
    protected $fillable = [
        'username', 'password', 'idrole',
    ];

    // Relasi ke Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'idrole', 'idrole');
    }

    // Relasi ke Pengadaan
    public function pengadaan()
    {
        return $this->hasMany(Pengadaan::class, 'user_iduser', 'iduser');
    }
}
