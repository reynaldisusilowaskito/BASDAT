<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    public function create()
    {
        // Ambil data margin penjualan untuk dropdown
        $marginPenjualan = DB::table('margin_penjualan')
            ->where('status', 1) // Hanya yang aktif
            ->get();
        $barang = DB::select('
    SELECT b.*, ks.stock
    FROM barang b
    JOIN (
        SELECT idbarang, MAX(idkartu_stok) AS last_idkartu_stok
        FROM kartu_stok
        WHERE stock > 0
        GROUP BY idbarang
    ) latest ON b.idbarang = latest.idbarang
    JOIN kartu_stok ks ON ks.idkartu_stok = latest.last_idkartu_stok
    WHERE ks.stock > 0
');
        $users = DB::select('select * from user');

        // @dd($barang);
        return view('penjualan.create', compact('marginPenjualan', 'barang', 'users'));
    }
    public function index()
    {
        $penjualans = DB::select('
        SELECT penjualan.*, user.username AS user_name, margin_penjualan.persen AS margin_penjualan_name
        FROM penjualan
        LEFT JOIN user ON penjualan.iduser = user.iduser
        LEFT JOIN margin_penjualan ON penjualan.idmargin_penjualan = margin_penjualan.idmargin_penjualan
    ');

        return view('penjualan.index', compact('penjualans'));
    }

    public function store(Request $request)
    {
        Log::info('Proses penjualan barang: ' . json_encode($request->all()));

        // Validasi input
        $validatedData = $request->validate([
            'subtotal' => 'required|numeric|min:0', // Sesuaikan key subtotal
            'iduser' => 'required|exists:user,iduser',
            'idmargin_penjualan' => 'required|exists:margin_penjualan,idmargin_penjualan',
            'barang' => 'required|array|min:1',
            'barang.*.id_barang' => 'required|exists:barang,idbarang',
            'barang.*.harga' => 'required|numeric|min:0',
            'barang.*.quantity' => 'required|integer|min:1',
            'barang.*.subtotal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction(); // Mulai transaksi untuk menjaga konsistensi data
        try {
            $createdAt = Carbon::now();
            $subtotalNilai = $validatedData['subtotal']; // Gunakan field 'subtotal'

            // Ambil persen margin berdasarkan idmargin_penjualan
            $margin = DB::table('margin_penjualan')
                ->where('idmargin_penjualan', $validatedData['idmargin_penjualan'])
                ->value('persen');

            if (!$margin) {
                throw new Exception('Margin tidak ditemukan untuk idmargin_penjualan: ' . $validatedData['idmargin_penjualan']);
            }

            // Hitung nilai PPN berdasarkan persen margin
            $ppn = $subtotalNilai * ($margin / 100);
            $totalNilai = $subtotalNilai + $ppn;

            // Simpan data ke tabel penjualan
            $penjualanId = DB::table('penjualan')->insertGetId([
                'created_at' => $createdAt,
                'subtotal_nilai' => $subtotalNilai,
                'ppn' => $ppn,
                'total_nilai' => $totalNilai,
                'iduser' => $validatedData['iduser'],
                'idmargin_penjualan' => $validatedData['idmargin_penjualan'],
            ]);

            // Simpan detail penjualan
            foreach ($validatedData['barang'] as $barang) {
                DB::table('detail_penjualan')->insert([
                    'harga_satuan' => $barang['harga'],
                    'jumlah' => $barang['quantity'],
                    'subtotal' => $barang['subtotal'],
                    'idpenjualan' => $penjualanId,
                    'idbarang' => $barang['id_barang'],
                ]);
            }

            DB::commit(); // Jika semua berhasil, commit transaksi
            return response()->json(['message' => 'Penjualan berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika terjadi error

            Log::error('Error occurred while processing penjualan: ' . $e->getMessage(), [
                'trace' => $e->getTrace(),
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function show($id)
    {
        // Raw SQL query to get penjualan and related details
        $penjualan = DB::select(
            'SELECT p.*, u.username as user_name, m.persen as margin_penjualan_name
         FROM penjualan p
         LEFT JOIN user u ON p.iduser = u.iduser
         LEFT JOIN margin_penjualan m ON p.idmargin_penjualan = m.idmargin_penjualan
         WHERE p.idpenjualan = :id',
            ['id' => $id],
        );

        // Raw SQL query to get the detail_penjualan and related barang
        $details = DB::select(
            'SELECT dp.*, b.nama as barang_name, b.harga
         FROM detail_penjualan dp
         LEFT JOIN barang b ON dp.idbarang = b.idbarang
         WHERE dp.idpenjualan = :id',
            ['id' => $id],
        );

        // Return the data to the view
        return view('penjualan.detail', compact('penjualan', 'details'));
    }
}
