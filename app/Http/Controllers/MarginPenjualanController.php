<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarginPenjualanController extends Controller
{
    // Menampilkan daftar margin penjualan
    public function index()
    {
        $margins = DB::select('SELECT * FROM margin_penjualan');
        return view('margin_penjualan.index', compact('margins'));
    }

    // Menampilkan form untuk menambah margin penjualan
    public function create()
    {
        return view('margin_penjualan.create');
    }

    // Menyimpan margin penjualan baru
    public function store(Request $request)
    {
        $request->validate([
            'persen' => 'required|numeric',
            'status' => 'required|in:0,1',
            'iduser' => 'required|integer'
        ]);

        DB::insert('INSERT INTO margin_penjualan (created_at, persen, status, updated_at, iduser) VALUES (NOW(), ?, ?, NOW(), ?)', [
            $request->persen,
            $request->status,
            $request->iduser
        ]);

        return redirect()->route('margin-penjualan.index')->with('success', 'Margin Penjualan berhasil ditambahkan');
    }

    // Menampilkan form untuk mengedit margin penjualan
    public function edit($id)
    {
        $margin = DB::select('SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?', [$id]);
        if (empty($margin)) {
            return redirect()->route('margin-penjualan.index')->with('error', 'Margin Penjualan tidak ditemukan');
        }
        return view('margin_penjualan.edit', compact('margin'));
    }

    // Mengupdate margin penjualan
    public function update(Request $request, $id)
    {
        $request->validate([
            'persen' => 'required|numeric',
            'status' => 'required|in:0,1',
            'iduser' => 'required|integer'
        ]);

        DB::update('UPDATE margin_penjualan SET persen = ?, status = ?, updated_at = NOW(), iduser = ? WHERE idmargin_penjualan = ?', [
            $request->persen,
            $request->status,
            $request->iduser,
            $id
        ]);

        return redirect()->route('margin-penjualan.index')->with('success', 'Margin Penjualan berhasil diperbarui');
    }

    // Menghapus margin penjualan
    public function destroy($id)
    {
        DB::delete('DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?', [$id]);

        return redirect()->route('margin-penjualan.index')->with('success', 'Margin Penjualan berhasil dihapus');
    }
}
