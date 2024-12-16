@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Margin Penjualan</h1>
    <a href="{{ route('margin-penjualan.create') }}" class="btn btn-primary mb-3">Tambah Margin Penjualan</a>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Persen</th>
                <th>Status</th>
                <th>User ID</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($margins as $margin)
                <tr>
                    <td>{{ $margin->idmargin_penjualan }}</td>
                    <td>{{ $margin->persen }}%</td>
                    <td>{{ $margin->status == 1 ? 'Active' : 'Inactive' }}</td>
                    <td>{{ $margin->iduser }}</td>
                    <td>
                        <a href="{{ route('margin-penjualan.edit', $margin->idmargin_penjualan) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('margin-penjualan.destroy', $margin->idmargin_penjualan) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
