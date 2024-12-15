<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenerimaan extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'detail_penerimaan';

    // Kolom yang dapat diisi (fillable) melalui form
    protected $fillable = [
        'idpenerimaan', // Foreign key ke tabel penerimaan
        'idbarang',     // Foreign key ke tabel barang
        'jumlah',       // Jumlah barang yang diterima
        'harga',        // Harga barang per unit
    ];

    /**
     * Relasi ke model Penerimaan.
     * Setiap DetailPenerimaan berhubungan dengan satu Penerimaan.
     */
    public function penerimaan()
    {
        return $this->belongsTo(Penerimaan::class, 'idpenerimaan', 'idpenerimaan');
    }

    /**
     * Relasi ke model Barang.
     * Setiap DetailPenerimaan berhubungan dengan satu Barang.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'id');
    }
}
