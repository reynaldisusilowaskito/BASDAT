@extends('layouts.app')

@section('content')
    <h1>Detail Pengadaan</h1>

    <div class="card">
        <div class="card-header">
            Pengadaan ID: {{ $pengadaan->idpengadaan }}
        </div>
        <div class="card-body">
            <h5 class="card-title">Informasi Pengadaan</h5>
            <p class="card-text">
                <strong>User yang Membuat Pengadaan:</strong> {{ $pengadaan->user->username }}<br>
                <strong>Vendor:</strong> {{ $pengadaan->vendor->nama_vendor }}<br>
                <strong>Subtotal:</strong> Rp {{ number_format($pengadaan->subtotal_nilai, 0, ',', '.') }}<br>
                <strong>PPN:</strong> Rp {{ number_format($pengadaan->ppn, 0, ',', '.') }}<br>
                <strong>Total Nilai:</strong> Rp {{ number_format($pengadaan->total_nilai, 0, ',', '.') }}<br>
                <strong>Status:</strong> {{ $pengadaan->status == '1' ? 'Aktif' : 'Tidak Aktif' }}<br>
                <strong>Tanggal Pengadaan:</strong> {{ $pengadaan->created_at->format('d M Y') }}
            </p>

            <h5 class="card-title">Detail Barang Pengadaan</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengadaan->detailPengadaan as $detail)
                    <tr>
                        <td>{{ $detail->barang->nama_barang }}</td>
                        <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('pengadaan.index') }}" class="btn btn-primary">Kembali ke Daftar Pengadaan</a>
            <a href="{{ route('pengadaan.edit', $pengadaan->idpengadaan) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('pengadaan.destroy', $pengadaan->idpengadaan) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengadaan ini?')">Hapus</button>
            </form>
        </div>
    </div>
@endsection
