@extends('layouts.app')

@section('content')
    <h1>Detail Pengadaan ID: {{ $detailPengadaan->id }}</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Barang: {{ $detailPengadaan->barang->nama }}</h5>
            <p class="card-text">Jumlah: {{ $detailPengadaan->jumlah }}</p>
            <p class="card-text">Harga Satuan: {{ $detailPengadaan->harga_satuan }}</p>
            <p class="card-text">Subtotal: {{ $detailPengadaan->sub_total }}</p>
        </div>
    </div>

    <a href="{{ route('detail_pengadaan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    <a href="{{ route('detail_pengadaan.edit', $detailPengadaan->id) }}" class="btn btn-primary mt-3">Edit</a>
@endsection
