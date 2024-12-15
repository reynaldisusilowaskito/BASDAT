<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $role = Role::all();
        return view('role.index', compact('role'));
    }

    public function create()
    {
        return view('role.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_role' => 'required',
        ]);

        Role::create($request->all());

        return redirect()->route('role.index')->with('success', 'Role berhasil ditambahkan');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('role.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_role' => 'required',
        ]);

        $role = Role::findOrFail($id);
        $role->update($request->all());

        return redirect()->route('role.index')->with('success', 'Role berhasil diperbarui');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('role.index')->with('success', 'Role berhasil dihapus');
    }
}

