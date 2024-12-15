<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuan = Satuan::all();
        return view('satuan.index', compact('satuan'));
    }

    public function create()
    {
        return view('satuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required',
            'status' => 'required|in:1,0',
        ]);

        Satuan::create($request->all());

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $satuan = Satuan::findOrFail($id);
        return view('satuan.edit', compact('satuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_satuan' => 'required',
            'status' => 'required|in:1,0',
        ]);

        $satuan = Satuan::findOrFail($id);
        $satuan->update($request->all());

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $satuan = Satuan::findOrFail($id);
        $satuan->delete();

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil dihapus');
    }
}
