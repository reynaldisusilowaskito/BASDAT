<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    use HasFactory;

    protected $table = 'penerimaan';
    protected $fillable = ['status', 'idpengadaan', 'iduser'];

    public function pengadaan()
    {
        return $this->belongsTo(Pengadaan::class, 'idpengadaan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }
}
