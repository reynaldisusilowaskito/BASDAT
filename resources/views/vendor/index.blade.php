@extends('layouts.app')

@section('content')
    <h1>Daftar Vendor</h1>
    <a href="{{ route('vendor.create') }}" class="btn btn-primary">Tambah Vendor</a>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Vendor</th>
                <th>Badan Hukum</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vendor as $item)
            <tr>
                <td>{{ $item->nama_vendor }}</td>
                <td>{{ $item->badan_hukum == 'Y' ? 'Ya' : 'Tidak' }}</td>
                <td>{{ $item->status == 'A' ? 'Aktif' : 'Non-aktif' }}</td>
                <td>
                    <a href="{{ route('vendor.edit', $item->idvendor) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('vendor.destroy', $item->idvendor) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus vendor ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
