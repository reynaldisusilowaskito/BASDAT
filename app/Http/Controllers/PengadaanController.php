<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengadaanController extends Controller
{
    // Menampilkan daftar pengadaan
    public function index()
    {
        // Ambil daftar pengadaan dengan join tabel user dan vendor
        $pengadaans = DB::select('
            SELECT pengadaan.*, user.username AS user_name, vendor.nama_vendor,user.iduser
            FROM pengadaan
            INNER JOIN user ON pengadaan.user_iduser = user.iduser
            INNER JOIN vendor ON pengadaan.vendor_idvendor = vendor.idvendor
        ');

        return view('pengadaan.index', compact('pengadaans'));
    }

    // Menampilkan form untuk menambah pengadaan baru
    public function create()
    {
        $barang = DB::table('barang')->get();
        $users = DB::table('user')->get();
        $vendors = DB::table('vendor')->get();
        $pengadaan = null; // Atau ambil data pengadaan tertentu jika diperlukan

        return view('pengadaan.create', compact('barang', 'users', 'vendors', 'pengadaan'));
    }

    public function store($idpengadaan, $iduser)
    {
        // Proses penerimaan barang
        Log::info('Proses penerimaan barang untuk ID Pengadaan: ' . $idpengadaan . ' dan ID User: ' . $iduser);

        // Menyimpan penerimaan barang atau mengupdate status pengadaan
        // Contoh: Update status pengadaan
        DB::table('pengadaan')
            ->where('idpengadaan', $idpengadaan)
            ->update(['status' => 'Diterima', 'iduser' => $iduser]);

        // Return response
        return response()->json([
            'status' => 'success',
            'message' => 'Barang berhasil diterima',
        ]);
    }

    // Menampilkan form edit pengadaan
    public function edit($id)
    {
        // Ambil data pengadaan
        $pengadaan = DB::table('pengadaan')->where('idpengadaan', $id)->first();

        if (!$pengadaan) {
            return redirect()->route('pengadaan.index')->with('error', 'Pengadaan tidak ditemukan.');
        }

        // Ambil daftar vendor dan user
        $vendors = DB::table('vendor')->get();
        $users = DB::table('user')->get();

        // Ambil detail pengadaan
        $detailPengadaan = DB::table('detail_pengadaan')
            ->join('barang', 'detail_pengadaan.idbarang', '=', 'barang.idbarang')
            ->join('satuan', 'barang.idsatuan', '=', 'satuan.idsatuan')
            ->where('detail_pengadaan.idpengadaan', $id)
            ->select(
                'detail_pengadaan.*',
                'barang.nama AS barang_nama',
                'barang.harga AS barang_harga',
                'satuan.nama_satuan'
            )
            ->get();

        // Ambil daftar barang untuk dropdown
        $barangList = DB::table('barang')->get();

        return view('pengadaan.edit', compact('pengadaan', 'vendors', 'users', 'detailPengadaan', 'barangList'));
    }



    // Mengupdate data pengadaan
    public function update(Request $request, $id)
    {
        // Debugging untuk melihat data yang dikirimkan
        // dd($request->all());

        // Validasi input
        $validated = $request->validate([
            'status' => 'required|in:0,1',
            'detail_pengadaan.*.barang_id' => 'required|exists:barang,idbarang',
            'detail_pengadaan.*.harga_satuan' => 'required|numeric',
            'detail_pengadaan.*.jumlah' => 'required|numeric',
            'detail_pengadaan.*.sub_total' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            // Update data pengadaan utama
            DB::table('pengadaan')->where('idpengadaan', $id)->update([
                'status' => $validated['status'],
            ]);

            // Update detail pengadaan
            foreach ($validated['detail_pengadaan'] as $detail) {
                DB::table('detail_pengadaan')
                    ->where('iddetail_pengadaan', $detail['id'])
                    ->update([
                        'barang_id' => $detail['barang_id'],
                        'harga_satuan' => $detail['harga_satuan'],
                        'jumlah' => $detail['jumlah'],
                        'sub_total' => $detail['sub_total'],
                    ]);
            }

            DB::commit();

            return redirect()->route('pengadaan.index')->with('success', 'Pengadaan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pengadaan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    // Menghapus pengadaan
    public function destroy($id)
    {
        try {
            // Cari data pengadaan berdasarkan ID
            $pengadaan = DB::table('pengadaan')->where('idpengadaan', $id)->first();

            // Jika data tidak ditemukan, tampilkan pesan error
            if (!$pengadaan) {
                return redirect()->route('pengadaan.index')->with('error', 'Pengadaan tidak ditemukan.');
            }

            // Hapus data relasi di detail_pengadaan (jika ada)
            DB::table('detail_pengadaan')->where('idpengadaan', $id)->delete();

            // Hapus data utama di pengadaan
            DB::table('pengadaan')->where('idpengadaan', $id)->delete();

            // Redirect ke index dengan pesan sukses
            return redirect()->route('pengadaan.index')->with('success', 'Pengadaan berhasil dihapus.');
        } catch (\Exception $e) {
            // Tangani kesalahan dan tampilkan pesan error
            return redirect()->route('pengadaan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    // Menampilkan detail pengadaan
    public function show($id)
    {
        // Ambil pengadaan dengan join user dan vendor
        $pengadaan = DB::table('pengadaan')
            ->join('user', 'pengadaan.user_iduser', '=', 'user.iduser')
            ->join('vendor', 'pengadaan.vendor_idvendor', '=', 'vendor.idvendor')
            ->select('pengadaan.*', 'user.username', 'vendor.nama_vendor')
            ->where('pengadaan.idpengadaan', $id)
            ->first();

        // Ambil detail pengadaan berdasarkan idpengadaan
        $detailPengadaan = DB::table('detail_pengadaan')
            ->join('barang', 'detail_pengadaan.idbarang', '=', 'barang.idbarang')
            ->join('satuan', 'detail_pengadaan.idsatuan', '=', 'satuan.idsatuan')
            ->select('detail_pengadaan.*', 'barang.nama AS barang_nama', 'satuan.nama AS nama_satuan')
            ->where('detail_pengadaan.pengadaan_id', $id)
            ->get();

        // Kirim data pengadaan dan detail pengadaan ke view
        return view('pengadaan.show', compact('pengadaan', 'detailPengadaan'));
    }


    // Menampilkan detail pengadaan beserta barang
    public function showDetail($id)
    {
        // Ambil data dari view 'pengadaan_view'
        $pengadaan = DB::table('pengadaan_view')
            ->where('idpengadaan', $id)
            ->get(); // Mengambil data sebagai collection

        // Cek jika data pengadaan ditemukan
        if ($pengadaan->isEmpty()) {
            return redirect()->route('pengadaan.index')->with('error', 'Pengadaan tidak ditemukan');
        }

        // Ambil data pengadaan pertama
        $pengadaanData = $pengadaan->first();
        $detailPengadaan = $pengadaan->whereNotNull('iddetail_pengadaan'); // Ambil data detail pengadaan

        return view('pengadaan.detail', compact('pengadaanData', 'detailPengadaan'));
    }

    public function penerimaan()
    {
        $pengadaans = DB::table('pengadaan')
            ->join('user', 'pengadaan.user_id', '=', 'user.iduser')
            ->join('vendor', 'pengadaan.vendor_idvendor', '=', 'vendor.idvendor')
            ->select('pengadaan.*', 'user.username as user_name', 'vendor.nama_vendor')
            ->get();

        return view('pengadaan.penerimaan', compact('pengadaans'));
    }
    // Metode untuk menandai pengadaan sebagai "Tolak"
    public function tolak($id)
    {
        try {
            // Cari data pengadaan berdasarkan ID
            $pengadaan = DB::table('pengadaan')->where('idpengadaan', $id)->first();

            if (!$pengadaan) {
                return redirect()->route('pengadaan.index')->with('error', 'Pengadaan tidak ditemukan.');
            }

            // Update status pengadaan menjadi "Tolak" (misalnya, 1)
            DB::table('pengadaan')->where('idpengadaan', $id)->update([
                'status_pengadaan' => 1, // Status "Tolak"
            ]);

            return redirect()->route('pengadaan.index')->with('success', 'Pengadaan telah ditolak.');
        } catch (\Exception $e) {
            return redirect()->route('pengadaan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Metode untuk menandai pengadaan sebagai "Terima"

    public function terima(Request $request)
    {
        // Ambil data yang dikirim dari form
        $idpengadaan = $request->idpengadaan;
        $iduser = $request->iduser;
        $statusPenerimaan = '1'; // Status penerimaan misalnya '1' untuk diterima
        $tanggal = now(); // Mengambil waktu saat ini

        try {
            // Mulai transaksi untuk memastikan data konsisten
            DB::beginTransaction();

            // Insert data penerimaan baru ke tabel penerimaan
            $result = DB::insert(
                '
                INSERT INTO penerimaan (status, idpengadaan, iduser) 
                VALUES (?, ?, ?)',
                [$statusPenerimaan, $idpengadaan, $iduser]
            );

            // Ambil idpenerimaan yang baru dibuat
            $idPenerimaan = DB::getPdo()->lastInsertId();

            // Ambil semua detail pengadaan untuk idpengadaan tertentu
            $detailPengadaan = DB::select(
                '
                SELECT dp.iddetail_pengadaan, dp.idbarang, dp.harga_satuan, dp.jumlah, dp.sub_total 
                FROM detail_pengadaan dp 
                WHERE dp.idpengadaan = ?',
                [$idpengadaan]
            );

            // Proses memasukkan data ke detail penerimaan
            foreach ($detailPengadaan as $detail) {
                // Insert detail penerimaan untuk setiap barang
                DB::insert(
                    '
                    INSERT INTO detail_penerimaan (idpenerimaan, barang_idbarang, jumlah_terima, harga_satuan_terima, sub_total_terima) 
                    VALUES (?, ?, ?, ?, ?)',
                    [
                        $idPenerimaan,
                        $detail->idbarang,
                        $detail->jumlah,
                        $detail->harga_satuan,
                        $detail->sub_total
                    ]
                );
            }

            // Commit transaksi jika semua query berhasil
            DB::commit();

            // Kembalikan respon sukses
            return response()->json([
                'status' => 'success',
                'message' => 'Penerimaan berhasil ditambahkan.',
                'idpenerimaan' => $idPenerimaan
            ], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            // Kembalikan respon error
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan penerimaan.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
