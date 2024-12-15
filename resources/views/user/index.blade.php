@extends('layouts.app')

@section('content')
    <h1>Daftar User</h1>
    <a href="{{ route('user.create') }}" class="btn btn-primary">Tambah User</a>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->username }}</td>
                <td>{{ $user->role->nama_role }}</td> <!-- Menampilkan nama role -->
                <td>
                    <a href="{{ route('user.edit', $user->iduser) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('user.destroy', $user->iduser) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
