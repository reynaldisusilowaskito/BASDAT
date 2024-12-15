@extends('layouts.app')

@section('content')
    <h1>Edit Role</h1>
    <form action="{{ route('role.update', $role->idrole) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nama_role">Nama Role:</label>
            <input type="text" name="nama_role" id="nama_role" class="form-control" value="{{ $role->nama_role }}" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
@endsection
