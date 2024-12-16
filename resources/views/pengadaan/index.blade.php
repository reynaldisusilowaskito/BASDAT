@extends('layouts.app')

@section('content')
    <h1>Daftar Pengadaan</h1>
    <a href="{{ route('pengadaan.create') }}" class="btn btn-primary">Tambah Pengadaan</a>

    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Vendor</th>
                <th>Subtotal</th>
                <th>PPN</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengadaans as $pengadaan)
            <tr>
                <td>{{ $pengadaan->user_name }}</td>
                <td>{{ $pengadaan->nama_vendor }}</td>
                <td>Rp {{ number_format($pengadaan->subtotal_nilai, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($pengadaan->ppn, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($pengadaan->total_nilai, 0, ',', '.') }}</td>
                <td>{{ $pengadaan->status == 'A' ? 'Pending' : 'Success' }}</td>
                <td>
                    @if($pengadaan->status == 'A')
                        <!-- Tombol Lihat Detail -->
                        <a href="{{ route('pengadaan.detail', $pengadaan->idpengadaan) }}" class="btn btn-info btn-sm">Lihat Detail</a>
                    
                        <!-- Tombol Edit -->
                        <a href="{{ route('pengadaan.edit', $pengadaan->idpengadaan) }}" class="btn btn-warning btn-sm">Edit</a>

                        <!-- Tombol Hapus -->
                        <form action="{{ route('pengadaan.destroy', $pengadaan->idpengadaan) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pengadaan ini?')">Hapus</button>
                        </form>
                    @else
                        <!-- Tombol Lihat Detail untuk status B -->
                        <a href="{{ route('pengadaan.detail', $pengadaan->idpengadaan) }}" class="btn btn-info btn-sm">Lihat Detail</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
