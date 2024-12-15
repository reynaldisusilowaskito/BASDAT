@extends('layouts.app')

@section('content')
    <h1>Daftar Barang</h1>
    <a href="{{ route('barang.create') }}" class="btn btn-primary">Tambah Barang</a>
    <table class="table">
        <thead>
            <tr>
                <th>Jenis</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $item)
            <tr>
                <td>{{ $item->jenis }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->harga }}</td>
                <td>{{ $item->status == 1 ? 'Aktif' : 'Non-aktif' }}</td>
                <td>
                    <a href="{{ route('barang.edit', $item->idbarang) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('barang.destroy', $item->idbarang) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
