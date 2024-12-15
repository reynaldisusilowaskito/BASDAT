<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    // Menampilkan semua penerimaan barang
    public function index()
    {
        // Mengambil data semua penerimaan menggunakan Query Builder
        $penerimaans = DB::table('penerimaan')->get();

        // Return ke view index penerimaan
        return view('penerimaan.index', [
            'penerimaans' => $penerimaans
        ]);
    }

    // Menampilkan detail penerimaan berdasarkan ID
    public function show($idpenerimaan)
    {
        // @dd($idpenerimaan);
        // Mengambil data penerimaan berdasarkan ID
        $penerimaan = DB::table('penerimaan')->where('idpenerimaan', $idpenerimaan)->first();

        // Mengambil data detail penerimaan berdasarkan ID penerimaan
        $detailPenerimaan = DB::table('detail_penerimaan')->where('idpenerimaan', $idpenerimaan)->get();

        // Validasi apakah data penerimaan ditemukan
        if (!$penerimaan) {
            return redirect('/penerimaan')->with('error', 'Data penerimaan tidak ditemukan.');
        }

        // Return ke view detail penerimaan
        return view('penerimaan.detail', [
            'penerimaan' => $penerimaan,
            'detailPenerimaan' => $detailPenerimaan
        ]);
    }

    // Menampilkan halaman edit penerimaan berdasarkan ID
    public function edit($idpenerimaan)
    {
        // Mengambil data penerimaan berdasarkan ID
        $penerimaan = DB::table('penerimaan')->where('id', $idpenerimaan)->first();

        // Validasi apakah data penerimaan ditemukan
        if (!$penerimaan) {
            return redirect('/penerimaan')->with('error', 'Data penerimaan tidak ditemukan.');
        }

        // Return ke view edit penerimaan
        return view('penerimaan.edit', [
            'penerimaan' => $penerimaan
        ]);
    }

    // Menghapus penerimaan berdasarkan ID
    public function destroy($idpenerimaan)
    {
        // Memastikan data penerimaan yang akan dihapus ada
        $penerimaan = DB::table('penerimaan')->where('id', $idpenerimaan)->first();

        if (!$penerimaan) {
            return redirect('/penerimaan')->with('error', 'Data penerimaan tidak ditemukan.');
        }

        // Hapus data penerimaan
        DB::table('penerimaan')->where('id', $idpenerimaan)->delete();

        // Hapus detail penerimaan terkait
        DB::table('detail_penerimaan')->where('idpenerimaan', $idpenerimaan)->delete();

        // Redirect ke halaman daftar penerimaan dengan pesan sukses
        return redirect('/penerimaan')->with('success', 'Data penerimaan berhasil dihapus.');
    }
}
