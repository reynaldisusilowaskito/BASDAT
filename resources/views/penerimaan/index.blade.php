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

                    {{-- Menampilkan status sesuai dengan kondisi --}}
                    <td>
                        @if($penerimaan->status == 'A')
                            Diterima
                        @elseif($penerimaan->status == 'B')
                            Telah Direturn
                        @else
                            Status Tidak Dikenal
                        @endif
                    </td>

                    {{-- Aksi: Tampilkan tombol Return hanya jika status penerimaan adalah A --}}
                    <td>
                        @if($penerimaan->status == 'A')
                            <a href="{{ route('penerimaan.show', $penerimaan->idpenerimaan) }}" class="btn btn-info btn-sm">Detail</a>
                            <form action="{{ route('penerimaan.destroy', $penerimaan->idpenerimaan) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda Ingin Returr Barang Ini?')">Retur</button>
                            </form>
                        @else
                            <!-- Jika status sudah B, tidak tampilkan tombol return -->
                            <span class="btn btn-secondary btn-sm" disabled>Retur Tidak Tersedia</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
