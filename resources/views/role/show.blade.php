@extends('layouts.app')

@section('content')
    <h1>Detail Role</h1>
    <p><strong>Nama Role:</strong> {{ $role->nama_role }}</p>
    <p><a href="{{ url('/role') }}" class="btn btn-primary">Kembali ke Daftar Role</a></p>
@endsection
