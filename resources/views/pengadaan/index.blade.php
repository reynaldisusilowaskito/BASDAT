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
                <td>{{ $pengadaan->status == '1' ? 'Aktif' : 'Tidak Aktif' }}</td>
                <td>
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

                    <!-- Tombol Terima dan Tolak -->
                    {{-- <form action="{{ route('penerimaan.index', $pengadaan->idpengadaan) }}" method="POST" style="display:inline-block;"> --}}
                        @csrf
                        
                            <button type="submit" class="btn btn-success btn-sm">Terima</button>
        
                    </form>
                    
                    {{-- <form action="{{ route('pengadaan.tolak', $pengadaan->idpengadaan) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @if($pengadaan->status == '1')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menolak pengadaan ini?')">Tolak</button>
                        @else
                            <button class="btn btn-danger btn-sm" disabled>Tolak</button>
                        @endif
                    </form> --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
