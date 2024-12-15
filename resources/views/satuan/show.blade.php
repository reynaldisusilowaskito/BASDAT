@extends('layouts.app')

@section('content')
    <h1>Detail Satuan</h1>
    <p><strong>Nama Satuan:</strong> {{ $satuan->nama_satuan }}</p>
    <p><strong>Status:</strong> {{ $satuan->status == 1 ? 'Aktif' : 'Non-aktif' }}</p>
    <p><a href="{{ url('/satuan') }}" class="btn btn-primary">Kembali ke Daftar Satuan</a></p>
@endsection
