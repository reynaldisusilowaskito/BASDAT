<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan
    protected $table = 'pengadaan';
    protected $primaryKey = 'idpengadaan';

    // Nonaktifkan timestamps
    public $timestamps = false;

    // Kolom yang dapat diisi
    protected $fillable = [
        'user_iduser', 'vendor_idvendor', 'subtotal_nilai', 'ppn', 'total_nilai', 'status'
    ];

    public function detailPengadaan()
    {
        return $this->hasMany(DetailPengadaan::class, 'idpengadaan');
    }
    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_iduser', 'iduser');
    }

    // Relasi ke Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_idvendor', 'idvendor');
    }
}
