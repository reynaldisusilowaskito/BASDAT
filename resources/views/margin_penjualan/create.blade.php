@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Margin Penjualan</h1>
    <form action="{{ route('margin-penjualan.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="persen">Persen</label>
            <input type="number" name="persen" id="persen" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="form-group">
            <label for="iduser">User ID</label>
            <input type="number" name="iduser" id="iduser" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
