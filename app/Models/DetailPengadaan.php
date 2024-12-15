<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengadaan extends Model
{
    use HasFactory;

    protected $table = 'detail_pengadaan';

    protected $fillable = ['idbarang', 'jumlah', 'harga_satuan', 'sub_total', 'idpengadaan'];

    public $timestamps = true; // Aktifkan jika Anda memiliki kolom `created_at` dan `updated_at`

    public function pengadaan()
    {
        return $this->belongsTo(Pengadaan::class, 'idpengadaan');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang');
    }
}
