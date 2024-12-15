@extends('layouts.app')

@section('content')
    <h1>Tambah Role</h1>
    <form action="{{ route('role.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama_role">Nama Role:</label>
            <input type="text" name="nama_role" id="nama_role" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
@endsection