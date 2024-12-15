@extends('layouts.app')

@section('content')
    <h1>Edit Satuan</h1>
    <form action="{{ route('satuan.update', $satuan->idsatuan) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nama_satuan">Nama Satuan:</label>
            <input type="text" name="nama_satuan" id="nama_satuan" class="form-control" value="{{ $satuan->nama_satuan }}" required>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="1" {{ $satuan->status == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $satuan->status == 0 ? 'selected' : '' }}>Non-aktif</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
@endsection
