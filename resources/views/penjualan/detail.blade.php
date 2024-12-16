@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detail Penjualan</h1>

        <!-- Display Penjualan Information -->
        <div>
            <h3>Penjualan Information</h3>
            <p><strong>User:</strong> {{ $penjualan[0]->user_name }}</p>
            <p><strong>Margin Penjualan:</strong> {{ $penjualan[0]->margin_penjualan_name }}</p>
            <p><strong>Subtotal:</strong> Rp {{ number_format($penjualan[0]->subtotal_nilai, 0, ',', '.') }}</p>
            <p><strong>PPN (11%):</strong> Rp {{ number_format($penjualan[0]->ppn, 0, ',', '.') }}</p>
            <p><strong>Total:</strong> Rp {{ number_format($penjualan[0]->total_nilai, 0, ',', '.') }}</p>
        </div>

        <!-- Display Detail Penjualan -->
        <h3>Detail Penjualan</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $detail)
                    <tr>
                        <td>{{ $detail->idbarang }}</td>
                        <td>{{ $detail->barang_name }}</td>
                        <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
