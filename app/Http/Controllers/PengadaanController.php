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

    public function store(Request $request)
    {
        // Log data dari request
        Log::info('Proses pembuatan pengadaan barang: ' . json_encode($request->all()));

        try {
            // Memulai transaksi
            DB::beginTransaction();

            // Ambil data dari request
            $iduser = $request->input('id_user');
            $idvendor = $request->input('id_vendor');
            $subtotal = $request->input('subtotal');
            $ppn = $request->input('ppn', 0); // Default 0 jika PPN tidak dikirim
            $total = $subtotal + $ppn; // Hitung total nilai
            $status = 'Menunggu'; // Status awal pengadaan

            // Masukkan data ke tabel pengadaan
            $idpengadaan = DB::table('pengadaan')->insertGetId([
                'user_iduser' => $iduser,
                'vendor_idvendor' => $idvendor,
                'status' => 'A',
                'subtotal_nilai' => $subtotal,
                'ppn' => $ppn,
                'total_nilai' => $total,
                'timestamp' => now(), // Otomatis menggunakan waktu saat ini
            ]);

            // Masukkan data ke tabel detail_pengadaan
            $barangDetails = $request->input('barang'); // Ambil array barang
            foreach ($barangDetails as $barang) {
                DB::table('detail_pengadaan')->insert([
                    'idpengadaan' => $idpengadaan,
                    'idbarang' => $barang['id_barang'],
                    'harga_satuan' => $barang['harga'],
                    'jumlah' => $barang['quantity'],
                    'sub_total' => $barang['subtotal'],
                ]);
            }

            // Commit transaksi jika semua berhasil
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pengadaan berhasil disimpan',
                'id_pengadaan' => $idpengadaan,
            ]);
        } catch (\Exception $e) {
            // Rollback jika ada error
            DB::rollBack();

            Log::error('Error saat menyimpan pengadaan barang: ' . $e->getMessage());
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat menyimpan pengadaan barang',
                ],
                500,
            );
        }
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
        $detailPengadaan = DB::table('detail_pengadaan')->join('barang', 'detail_pengadaan.idbarang', '=', 'barang.idbarang')->join('satuan', 'barang.idsatuan', '=', 'satuan.idsatuan')->where('detail_pengadaan.idpengadaan', $id)->select('detail_pengadaan.*', 'barang.nama AS barang_nama', 'barang.harga AS barang_harga', 'satuan.nama_satuan')->get();

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
            DB::table('pengadaan')
                ->where('idpengadaan', $id)
                ->update([
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
            return redirect()
                ->route('pengadaan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
            return redirect()
                ->route('pengadaan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Menampilkan detail pengadaan
    public function show($id)
    {
        // Ambil pengadaan dengan join user dan vendor
        $pengadaan = DB::table('pengadaan')->join('user', 'pengadaan.user_iduser', '=', 'user.iduser')->join('vendor', 'pengadaan.vendor_idvendor', '=', 'vendor.idvendor')->select('pengadaan.*', 'user.username', 'vendor.nama_vendor')->where('pengadaan.idpengadaan', $id)->first();

        // Ambil detail pengadaan berdasarkan idpengadaan
        $detailPengadaan = DB::table('detail_pengadaan')->join('barang', 'detail_pengadaan.idbarang', '=', 'barang.idbarang')->join('satuan', 'detail_pengadaan.idsatuan', '=', 'satuan.idsatuan')->select('detail_pengadaan.*', 'barang.nama AS barang_nama', 'satuan.nama AS nama_satuan')->where('detail_pengadaan.pengadaan_id', $id)->get();

        // Kirim data pengadaan dan detail pengadaan ke view
        return view('pengadaan.show', compact('pengadaan', 'detailPengadaan'));
    }

    // Menampilkan detail pengadaan beserta barang
    public function showDetail($id)
    {
        // Mengambil data pengadaan dari tabel pengadaan
        $pengadaanData = DB::table('pengadaan')->join('user', 'pengadaan.user_iduser', '=', 'user.iduser')->join('vendor', 'pengadaan.vendor_idvendor', '=', 'vendor.idvendor')->select('pengadaan.*', 'user.username', 'vendor.nama_vendor')->where('pengadaan.idpengadaan', $id)->first();
        // @dd($pengadaanData);
        // Mengambil data detail pengadaan, pastikan harga juga dipilih
        $detailPengadaan = DB::table('detail_pengadaan')
            ->join('barang', 'detail_pengadaan.idbarang', '=', 'barang.idbarang')
            ->join('satuan', 'barang.idsatuan', '=', 'satuan.idsatuan')
            ->select('detail_pengadaan.*', 'barang.nama as nama', 'satuan.nama_satuan', 'barang.harga') // Menambahkan kolom harga
            ->where('detail_pengadaan.idpengadaan', $id)
            ->get();

        // Cek jika data pengadaan ditemukan
        if (!$pengadaanData) {
            return redirect()->route('pengadaan.index')->with('error', 'Pengadaan tidak ditemukan');
        }

        return view('pengadaan.detail', compact('pengadaanData', 'detailPengadaan'));
    }

    public function penerimaan()
    {
        $pengadaans = DB::table('pengadaan')->join('user', 'pengadaan.user_id', '=', 'user.iduser')->join('vendor', 'pengadaan.vendor_idvendor', '=', 'vendor.idvendor')->select('pengadaan.*', 'user.username as user_name', 'vendor.nama_vendor')->get();

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
            DB::table('pengadaan')
                ->where('idpengadaan', $id)
                ->update([
                    'status_pengadaan' => 1, // Status "Tolak"
                ]);

            return redirect()->route('pengadaan.index')->with('success', 'Pengadaan telah ditolak.');
        } catch (\Exception $e) {
            return redirect()
                ->route('pengadaan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Metode untuk menandai pengadaan sebagai "Terima"

    public function terima(Request $request)
    {
        Log::info('Proses penerimaan: ' . json_encode($request->all()));

        // Ambil data yang dikirim dari form
        $idpengadaan = $request->idpengadaan;
        $iduser = $request->iduser;
        $statusPenerimaan = 'A'; // Status penerimaan misalnya '1' untuk diterima
        $statusPengadaanSelesai = 'B'; // Status pengadaan selesai
        $tanggal = now(); // Mengambil waktu saat ini

        try {
            // Mulai transaksi untuk memastikan data konsisten
            DB::beginTransaction();

            // Insert data penerimaan baru ke tabel penerimaan
            DB::insert(
                'INSERT INTO penerimaan (status, idpengadaan, iduser, created_at)
             VALUES (?, ?, ?, ?)',
                [$statusPenerimaan, $idpengadaan, $iduser, now()],
            );

            // Ambil idpenerimaan yang baru dibuat
            $idPenerimaan = DB::getPdo()->lastInsertId();

            // Ambil semua detail pengadaan untuk idpengadaan tertentu
            $detailPengadaan = DB::select(
                'SELECT dp.iddetail_pengadaan, dp.idbarang, dp.harga_satuan, dp.jumlah, dp.sub_total
             FROM detail_pengadaan dp
             WHERE dp.idpengadaan = ?',
                [$idpengadaan],
            );

            // Proses memasukkan data ke detail penerimaan
            foreach ($detailPengadaan as $detail) {
                // Insert detail penerimaan untuk setiap barang
                DB::insert(
                    'INSERT INTO detail_penerimaan (idpenerimaan, idbarang, jumlah_terima, harga_satuan_terima, sub_total_terima)
                 VALUES (?, ?, ?, ?, ?)',
                    [$idPenerimaan, $detail->idbarang, $detail->jumlah, $detail->harga_satuan, $detail->sub_total],
                );
            }

            // Update status pengadaan menjadi 'B' (selesai)
            DB::update(
                'UPDATE pengadaan
             SET status = ?
             WHERE idpengadaan = ?',
                [$statusPengadaanSelesai, $idpengadaan],
            );

            // Commit transaksi jika semua query berhasil
            DB::commit();

            // Kembalikan respon sukses
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Penerimaan berhasil ditambahkan dan status pengadaan diperbarui.',
                    'idpenerimaan' => $idPenerimaan,
                ],
                200,
            );
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            // Log error untuk debugging
            Log::error('Error penerimaan: ' . $e->getMessage());

            // Kembalikan respon error
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat menambahkan penerimaan.',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }
}
