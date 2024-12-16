<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KartuStokController extends Controller
{
    public function index()
    {
        // Query untuk mendapatkan semua barang, termasuk yang tidak memiliki stok
        $barangStok = DB::select('
            SELECT 
                b.idbarang, 
                b.nama AS nama_barang,
                b.jenis,
                b.harga,
                b.status,
                COALESCE(ks.stock, 0) AS stok_terakhir,
                ks.create_at AS waktu_stok_terakhir
            FROM barang b
            LEFT JOIN (
                SELECT idbarang, MAX(idkartu_stok) AS last_kartu_stok
                FROM kartu_stok
                GROUP BY idbarang
            ) latest_kartu ON b.idbarang = latest_kartu.idbarang
            LEFT JOIN kartu_stok ks ON ks.idkartu_stok = latest_kartu.last_kartu_stok
        ');

        // Return view dengan data barang dan stok
        return view('kartu_stok.index', compact('barangStok'));
    }
}
