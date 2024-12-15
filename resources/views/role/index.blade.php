@extends('layouts.app')

@section('content')
    <h1>Daftar Role</h1>
    <a href="{{ route('role.create') }}" class="btn btn-primary">Tambah Role</a>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($role as $item)
            <tr>
                <td>{{ $item->nama_role }}</td>
                <td>
                    <a href="{{ route('role.edit', $item->idrole) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('role.destroy', $item->idrole) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus role ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
