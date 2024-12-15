<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    // Menampilkan daftar barang
    public function index()
    {
        $barang = Barang::all();
        return view('barang.index', compact('barang'));
    }

    // Menampilkan form untuk menambah barang
    public function create()
    {
        return view('barang.create');
    }

    // Menyimpan barang baru
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required',
            'nama' => 'required',
            'harga' => 'required|numeric',
            'status' => 'required|in:0,1',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    // Menampilkan form edit barang
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    // Mengupdate barang yang ada
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required',
            'nama' => 'required',
            'harga' => 'required|numeric',
            'status' => 'required|in:0,1',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    // Menghapus barang
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }
    public function show(Request $request)
    {
        try {
            // Raw SQL untuk pencarian barang
            $barang = DB::select(
                "SELECT b.idbarang, b.nama, b.harga, s.nama_satuan
                          FROM barang b
                          JOIN satuan s ON b.idsatuan = s.idsatuan
                          WHERE b.nama LIKE ?",
                ['%' . $request->barang . '%'],
            );

            return response()->json($barang);
        } catch (\Exception $e) {
            // Jika ada error, tampilkan pesan error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
