@extends('layouts.app')

@section('content')
        <!-- Main Content -->
        <div class="col-md-10">
            <h1>Daftar Detail Pengadaan</h1>
            <a href="{{ route('detail_pengadaan.create') }}" class="btn btn-primary mb-3">Tambah Detail Pengadaan</a>

            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Pengadaan</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $detail)
                        <tr>
                            <td>{{ $detail->pengadaan->id ?? '-' }}</td>
                            <td>{{ $detail->barang->nama ?? '-' }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>{{ $detail->harga_satuan }}</td>
                            <td>{{ $detail->sub_total }}</td>
                            <td>
                                <a href="{{ route('detail_pengadaan.show', $detail->id) }}" class="btn btn-info btn-sm">Show</a>
                                <a href="{{ route('detail_pengadaan.edit', $detail->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('detail_pengadaan.destroy', $detail->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
