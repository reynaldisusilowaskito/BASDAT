@extends('layouts.app')

@section('content')
    <h1>Detail Barang</h1>
    <p><strong>Jenis:</strong> {{ $barang->jenis }}</p>
    <p><strong>Nama:</strong> {{ $barang->nama }}</p>
    <p><strong>Harga:</strong> {{ $barang->harga }}</p>
    <p><strong>Status:</strong> {{ $barang->status == 1 ? 'Aktif' : 'Non-aktif' }}</p>
    <p><a href="{{ url('/barang') }}" class="btn btn-primary">Kembali ke Daftar Barang</a></p>
@endsection
