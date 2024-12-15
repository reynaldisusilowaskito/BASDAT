@extends('layouts.app')

@section('content')
    <h1>Tambah User</h1>
    <form action="{{ route('user.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="username" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="idrole">Role:</label>
            <select name="idrole" id="idrole" class="form-control" required>
                <option value="">Pilih Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->idrole }}">{{ $role->nama_role }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
@endsection
