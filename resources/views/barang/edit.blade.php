@extends('layouts.app')

@section('content')
    <h1>Edit Barang</h1>
    <form action="{{ route('barang.update', $barang->idbarang) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="jenis">Jenis:</label>
            <input type="text" name="jenis" id="jenis" class="form-control" value="{{ $barang->jenis }}" required>
        </div>
        <div class="form-group">
            <label for="nama">Nama:</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ $barang->nama }}" required>
        </div>
        <div class="form-group">
            <label for="harga">Harga:</label>
            <input type="number" name="harga" id="harga" class="form-control" value="{{ $barang->harga }}" required>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="1" {{ $barang->status == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $barang->status == 0 ? 'selected' : '' }}>Non-aktif</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
@endsection
