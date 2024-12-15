<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendor = Vendor::all();
        return view('vendor.index', compact('vendor'));
    }

    public function create()
    {
        return view('vendor.create');
    }
    public function list()
    {
        try {
            $vendors = Vendor::select('idvendor', 'nama_vendor')->get();
            return response()->json($vendors, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memuat data vendor.'], 500);
        }
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required',
            'badan_hukum' => 'required|in:Y,N',
            'status' => 'required|in:A,N',
        ]);

        Vendor::create($request->all());

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan');
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('vendor.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_vendor' => 'required',
            'badan_hukum' => 'required|in:Y,N',
            'status' => 'required|in:A,N',
        ]);

        $vendor = Vendor::findOrFail($id);
        $vendor->update($request->all());

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil diperbarui');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil dihapus');
    }
}
