@extends('layouts.app')

@section('content')
    <h1>Tambah Satuan</h1>
    <form action="{{ route('satuan.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama_satuan">Nama Satuan:</label>
            <input type="text" name="nama_satuan" id="nama_satuan" class="form-control" required>
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
