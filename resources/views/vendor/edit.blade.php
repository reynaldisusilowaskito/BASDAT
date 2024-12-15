@extends('layouts.app')

@section('content')
    <h1>Edit Vendor</h1>
    <form action="{{ route('vendor.update', $vendor->idvendor) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nama_vendor">Nama Vendor:</label>
            <input type="text" name="nama_vendor" id="nama_vendor" class="form-control" value="{{ $vendor->nama_vendor }}" required>
        </div>
        <div class="form-group">
            <label for="badan_hukum">Badan Hukum:</label>
            <select name="badan_hukum" id="badan_hukum" class="form-control" required>
                <option value="Y" {{ $vendor->badan_hukum == 'Y' ? 'selected' : '' }}>Ya</option>
                <option value="N" {{ $vendor->badan_hukum == 'N' ? 'selected' : '' }}>Tidak</option>
            </select>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="A" {{ $vendor->status == 'A' ? 'selected' : '' }}>Aktif</option>
                <option value="N" {{ $vendor->status == 'N' ? 'selected' : '' }}>Non-aktif</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
@endsection
