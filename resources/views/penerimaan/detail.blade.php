@extends('layouts.app')

@section('content')
    <h1>Detail Penerimaan: {{ $penerimaan->idpenerimaan }}</h1>
    <p>User: {{ $penerimaan->iduser }}</p>
    <p>ID Pengadaan: {{ $penerimaan->idpengadaan }}</p>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Detail Penerimaan</th>
                <th>ID Penerimaan</th>
                <th>ID Barang</th>
                <th>Jumlah Terima</th>
                <th>Harga Satuan</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($detailPenerimaan as $item)
                <tr>
                    <td>{{ $item->iddetail_penerimaan }}</td>
                    <td>{{ $item->idpenerimaan }}</td>
                    <td>{{ $item->idbarang }}</td>
                    <td>{{ $item->jumlah_terima }}</td>
                    <td>{{ number_format($item->harga_satuan_terima, 2) }}</td>
                    <td>{{ number_format($item->sub_total_terima, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Tidak ada detail penerimaan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <a href="{{ url('/penerimaan') }}">Kembali</a>
@endsection