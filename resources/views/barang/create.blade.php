@extends('layouts.app')

@section('content')
    <h1>Tambah Barang</h1>
    <form action="{{ route('barang.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="jenis">Jenis:</label>
            <input type="text" name="jenis" id="jenis" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="nama">Nama:</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="harga">Harga:</label>
            <input type="number" name="harga" id="harga" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="1">Aktif</option>
                <option value="0">Non-aktif</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
@endsection
