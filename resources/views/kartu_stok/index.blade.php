@extends('layouts.app')

@section('content')
    <h1>Daftar Barang dengan Stok Terkini</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th>Jenis</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Stok Terakhir</th>
                <th>Waktu Stok Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangStok as $index => $barang)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->jenis }}</td>
                    <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                    <td>{{ $barang->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                    <td>{{ $barang->stok_terakhir }}</td>
                    <td>{{ \Carbon\Carbon::parse($barang->waktu_stok_terakhir)->format('d-m-Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data stok barang</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
