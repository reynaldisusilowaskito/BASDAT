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
            'penerimaans' => $penerimaans,
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
            'detailPenerimaan' => $detailPenerimaan,
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

            // Update status penerimaan menjadi 'B' setelah retur
            DB::table('penerimaan')
                ->where('idpenerimaan', $idpenerimaan)
                ->update([
                    'status' => 'B', // Status 'B' berarti telah direturn
                ]);

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
