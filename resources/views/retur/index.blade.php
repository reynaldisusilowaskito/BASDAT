@extends('layouts.app')

@section('content')
    <h1>Daftar Penerimaan</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Retur</th>
                <th>Create At</th>
                <th>ID Penerimaan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($retur as $ret)
                <tr>
                    <td>{{ $ret->idretur }}</td>
                    <td>{{ $ret->created_at }}</td>
                    <td>{{ $ret->idpenerimaan }}</td>
                    {{-- <td>{{ $ret->pengadaan_status }}</td> --}}
                    <td>
                        
                        <a href="{{ route('retur.show', $ret->idretur) }}" class="btn btn-info btn-sm">Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
