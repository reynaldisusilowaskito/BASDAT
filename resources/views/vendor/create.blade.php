@extends('layouts.app')

@section('content')
    <h1>Tambah Vendor</h1>
    <form action="{{ route('vendor.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama_vendor">Nama Vendor:</label>
            <input type="text" name="nama_vendor" id="nama_vendor" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="badan_hukum">Badan Hukum:</label>
            <select name="badan_hukum" id="badan_hukum" class="form-control" required>
                <option value="Y">Ya</option>
                <option value="N">Tidak</option>
            </select>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="A">Aktif</option>
                <option value="N">Non-aktif</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
@endsection
