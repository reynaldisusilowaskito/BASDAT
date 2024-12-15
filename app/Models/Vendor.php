<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendor';
    protected $primaryKey = 'idvendor';
    public $timestamps = false;

    protected $fillable = ['nama_vendor', 'badan_hukum', 'status'];

    // Relasi ke Pengadaan
    public function pengadaans()
    {
        return $this->hasMany(Pengadaan::class, 'vendor_idvendor', 'idvendor');
    }
}
