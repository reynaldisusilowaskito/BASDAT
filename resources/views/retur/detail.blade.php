@extends('layouts.app')

@section('content')
    <h1>Detail Retur #{{ $retur->idretur }}</h1>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Informasi Retur</h4>
            <p><strong>ID Retur:</strong> {{ $retur->idretur }}</p>
            <p><strong>Created At:</strong> {{ $retur->created_at }}</p>
            <p><strong>Nama Pengguna:</strong> {{ $retur->username }}</p>
            <p><strong>ID Penerimaan:</strong> {{ $retur->idpenerimaan }}</p>
        </div>
    </div>

    <h3>Detail Barang yang Diretur</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah yang Diterima</th>
                <th>Jumlah yang Dikirim</th>
                <th>Alasan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detailRetur as $detail)
                <tr>
                    <td>{{ $detail->barang_nama }}</td>
                    <td>{{ $detail->jumlah_terima }}</td>
                    <td>{{ $detail->jumlah }}</td>
                    <td>{{ $detail->alasan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('retur.index') }}" class="btn btn-primary">Kembali ke Daftar Retur</a>
@endsection
