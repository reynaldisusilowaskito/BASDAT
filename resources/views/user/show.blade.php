@extends('layouts.app')

@section('content')
    <h1>Detail User</h1>
    <p><strong>Username:</strong> {{ $user->username }}</p>
    <p><strong>Role:</strong> {{ $user->role->nama_role }}</p>
    <p><a href="{{ url('/user') }}" class="btn btn-primary">Kembali ke Daftar User</a></p>
@endsection
