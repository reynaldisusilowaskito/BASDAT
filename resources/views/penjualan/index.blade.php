@extends('layouts.app')

@section('content')
    <h1>Daftar Penjualan</h1>
    <a href="{{ route('penjualan.create') }}" class="btn btn-primary">Tambah Penjualan</a>

    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Margin Penjualan</th>
                <th>Subtotal</th>
                <th>PPN</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualans as $penjualan)
            <tr>
                {{-- @dd($penjualan); --}}
                <td>{{ $penjualan->user_name }}</td>
                <td>{{ $penjualan->margin_penjualan_name }}</td>
                <td>Rp {{ number_format($penjualan->subtotal_nilai, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($penjualan->ppn, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($penjualan->total_nilai, 0, ',', '.') }}</td>
                
                <td>
                    <!-- Tombol Lihat Detail -->
                    <form action="{{ route('penjualan.detail', $penjualan->idpenjualan) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-info btn-sm">Lihat Detail</button>
                    </form>
                </td>
                
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
