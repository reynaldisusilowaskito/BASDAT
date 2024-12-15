<?php

namespace App\Http\Controllers;

use App\Models\DetailPengadaan;
use App\Models\Pengadaan;
use App\Models\Barang;
use Illuminate\Http\Request;

class DetailPengadaanController extends Controller
{
    // Menampilkan daftar Detail Pengadaan
    public function index()
    {
        $details = DetailPengadaan::with('pengadaan', 'barang')->get();
        return view('detail_pengadaan.index', compact('details'));
    }

    // Menampilkan form untuk menambah Detail Pengadaan
    public function create()
    {
        $pengadaans = Pengadaan::all();
        $barangs = Barang::all();
        return view('detail_pengadaan.create', compact('pengadaans', 'barangs'));
    }

    // Menyimpan Detail Pengadaan baru
    public function store(Request $request)
{
    try {
        $request->validate([
            'idbarang' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|integer|min:1'
        ]);

        DetailPengadaan::create([
            'idbarang' => $request->idbarang,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
            'sub_total' => $request->jumlah * $request->harga_satuan
        ]);

        return redirect()->route('detail_pengadaan.index')->with('success', 'Data berhasil disimpan.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
    }
}


    // Menampilkan form edit Detail Pengadaan
    public function edit($id)
    {
        $detail = DetailPengadaan::findOrFail($id);
        $pengadaans = Pengadaan::all();
        $barangs = Barang::all();
        return view('detail_pengadaan.edit', compact('detail', 'pengadaans', 'barangs'));
    }

    // Mengupdate Detail Pengadaan yang ada
    public function update(Request $request, $id)
    {
        $request->validate([
            'idbarang' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|integer|min:1'
        ]);

        $detail = DetailPengadaan::findOrFail($id);
        $detail->update([
            'idbarang' => $request->idbarang,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
            'sub_total' => $request->jumlah * $request->harga_satuan
        ]);

        return redirect()->route('detail_pengadaan.index')->with('success', 'Data berhasil diperbarui.');
    }

    // Menghapus Detail Pengadaan
    public function destroy($id)
    {
        DetailPengadaan::destroy($id);
        return redirect()->route('detail_pengadaan.index')->with('success', 'Data berhasil dihapus.');
    }

    // Menampilkan detail satu data Detail Pengadaan
    public function show($id)
    {
        $detail = DetailPengadaan::with('pengadaan', 'barang')->findOrFail($id);
        return view('detail_pengadaan.show', compact('detail'));
    }
}
