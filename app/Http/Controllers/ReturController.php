<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    // Menampilkan semua penerimaan barang
    public function index()
    {
        // Mengambil data semua penerimaan menggunakan Query Builder
        $retur = DB::table('returr')->get();

        // Return ke view index penerimaan
        return view('retur.index', [
            'retur' => $retur,
        ]);
    }

    // Menampilkan detail penerimaan berdasarkan ID
    public function show($id)
    {
        // Ambil data retur berdasarkan idretur
        $retur = DB::table('returr')->join('user', 'returr.iduser', '=', 'user.iduser')->select('returr.*', 'user.username')->where('returr.idretur', $id)->first();

        // Ambil detail retur yang terkait dengan idretur
        $detailRetur = DB::table('detail_retur')->join('detail_penerimaan', 'detail_retur.iddetail_penerimaan', '=', 'detail_penerimaan.iddetail_penerimaan')->join('barang', 'detail_penerimaan.idbarang', '=', 'barang.idbarang')->select('detail_retur.*', 'barang.nama AS barang_nama', 'detail_penerimaan.jumlah_terima')->where('detail_retur.idretur', $id)->get();

        // Kirim data retur dan detail retur ke view
        return view('retur.detail', compact('retur', 'detailRetur'));
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
            'penerimaan' => $penerimaan,
        ]);
    }

    // Menghapus penerimaan berdasarkan ID
    public function destroy($idpenerimaan)
    {
        // Memastikan data penerimaan yang akan dihapus ada
        $penerimaan = DB::table('penerimaan')->where('idpenerimaan', $idpenerimaan)->first();

        if (!$penerimaan) {
            return redirect('/penerimaan')->with('error', 'Data penerimaan tidak ditemukan.');
        }

        // Ambil iduser dari penerimaan
        $iduser = $penerimaan->iduser;

        try {
            // Mulai transaksi untuk memastikan data konsisten
            DB::beginTransaction();

            // Membuat record retur untuk penerimaan yang akan dihapus
            $idretur = DB::table('returr')->insertGetId([
                'idpenerimaan' => $idpenerimaan,
                'iduser' => $iduser,
                'created_at' => now(),
            ]);

            // Ambil semua detail penerimaan yang terkait dengan idpenerimaan
            $detailPenerimaan = DB::table('detail_penerimaan')->where('idpenerimaan', $idpenerimaan)->get();

            // Masukkan data ke detail_retur untuk setiap detail penerimaan
            foreach ($detailPenerimaan as $detail) {
                DB::table('detail_retur')->insert([
                    'jumlah' => $detail->jumlah_terima,
                    'alasan' => 'Penerimaan dibatalkan', // Bisa disesuaikan sesuai kebutuhan
                    'idretur' => $idretur,
                    'iddetail_penerimaan' => $detail->iddetail_penerimaan,
                ]);
            }

            // Commit transaksi jika semua query berhasil
            DB::commit();

            // Redirect ke halaman daftar penerimaan dengan pesan sukses
            return redirect('/penerimaan')->with('success', 'Data penerimaan dan retur berhasil diproses.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            // Log error untuk debugging
            Log::error('Error saat menghapus penerimaan: ' . $e->getMessage());

            // Kembalikan respon error
            return redirect('/penerimaan')->with('error', 'Terjadi kesalahan saat menghapus penerimaan.');
        }
    }
}
