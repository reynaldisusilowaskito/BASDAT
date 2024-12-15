@extends('layouts.app')

@section('content')
    <h1>Detail Vendor</h1>
    <p><strong>Nama Vendor:</strong> {{ $vendor->nama_vendor }}</p>
    <p><strong>Badan Hukum:</strong> {{ $vendor->badan_hukum == 'Y' ? 'Ya' : 'Tidak' }}</p>
    <p><strong>Status:</strong> {{ $vendor->status == 'A' ? 'Aktif' : 'Non-aktif' }}</p>
    <p><a href="{{ url('/vendor') }}" class="btn btn-primary">Kembali ke Daftar Vendor</a></p>
@endsection
