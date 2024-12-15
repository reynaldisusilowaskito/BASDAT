@extends('layouts.app')

@section('content')
    <h1>Daftar Satuan</h1>
    <a href="{{ route('satuan.create') }}" class="btn btn-primary">Tambah Satuan</a>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Satuan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($satuan as $item)
            <tr>
                <td>{{ $item->nama_satuan }}</td>
                <td>{{ $item->status == 1 ? 'Aktif' : 'Non-aktif' }}</td>
                <td>
                    <a href="{{ route('satuan.edit', $item->idsatuan) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('satuan.destroy', $item->idsatuan) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus satuan ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
