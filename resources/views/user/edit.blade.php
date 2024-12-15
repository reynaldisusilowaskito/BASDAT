@extends('layouts.app')

@section('content')
    <h1>Edit User</h1>
    <form action="{{ route('user.update', $user->iduser) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="form-group">
            <label for="password">Password (Biarkan kosong jika tidak ingin mengubah):</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>
        <div class="form-group">
            <label for="idrole">Role:</label>
            <select name="idrole" id="idrole" class="form-control" required>
                @foreach($roles as $role)
                    <option value="{{ $role->idrole }}" {{ $role->idrole == $user->idrole ? 'selected' : '' }}>{{ $role->nama_role }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
@endsection
