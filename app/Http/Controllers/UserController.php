<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index()
    {
        $users = DB::select("
    SELECT user.iduser, user.username, user.email, user.status, user.created_at, user.updated_at, role.nama_role
    FROM user
    LEFT JOIN role ON user.idrole = role.idrole
");

        

        return view('user.index', compact('users'));
    }

    // Menampilkan form untuk menambah user baru
    public function create()
    {
        $roles = Role::all(); // Mengambil semua role untuk dropdown
        return view('user.create', compact('roles'));
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        // Validasi input user
        $request->validate([
            'username' => 'required|string|max:45',
            'password' => 'required|string|min:6',
            'idrole' => 'required|exists:role,idrole',
        ]);

        // Enkripsi password sebelum disimpan ke database
        $user = new User($request->all());
        $user->password = Hash::make($request->password); // Enkripsi password
        $user->save();

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
    }

    // Menampilkan form edit user
    public function edit($id)
    {
        $user = User::findOrFail($id); // Cari user berdasarkan ID
        $roles = Role::all(); // Mengambil semua role untuk dropdown
        return view('user.edit', compact('user', 'roles'));
    }

    // Mengupdate data user
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:45',
            'password' => 'nullable|string|min:6',
        ]);

        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Jika password diisi, enkripsi dan update
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update data lainnya
        $user->update($request->except('password'));

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui');
    }

    // Menghapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
    }
}
