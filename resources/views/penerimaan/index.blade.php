@extends('layouts.app')

@section('content')
    <h1>Daftar Penerimaan</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Penerimaan</th>
                <th>User</th>
                <th>ID Pengadaan</th>
                <th>Status Pengadaan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penerimaans as $penerimaan)
                <tr>
                    <td>{{ $penerimaan->idpenerimaan }}</td>
                    <td>{{ $penerimaan->iduser }}</td>
                    <td>{{ $penerimaan->idpengadaan }}</td>
                    {{-- <td>{{ $penerimaan->pengadaan_status }}</td> --}}
                    <td>
                        <a href="{{ route('penerimaan.show', $penerimaan->idpenerimaan) }}" class="btn btn-info btn-sm">Detail</a>
                        <a href="{{ route('penerimaan.edit', $penerimaan->idpenerimaan) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('penerimaan.destroy', $penerimaan->idpenerimaan) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda Ingin Returr Barang Ini?')">Retur</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
