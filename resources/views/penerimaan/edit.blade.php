@extends('layouts.app')

@section('content')
    <h1>Edit Detail Pengadaan</h1>
    <form action="{{ route('detail_pengadaan.update', $detailPengadaan->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="idbarang">Pilih Barang:</label>
            <select name="idbarang" id="idbarang" class="form-control" required>
                @foreach($barangs as $barang)
                    <option value="{{ $barang->id }}" {{ $detailPengadaan->idbarang == $barang->id ? 'selected' : '' }}>
                        {{ $barang->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah">Jumlah:</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ $detailPengadaan->jumlah }}" required>
        </div>

        <div class="form-group">
            <label for="harga_satuan">Harga Satuan:</label>
            <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" value="{{ $detailPengadaan->harga_satuan }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
