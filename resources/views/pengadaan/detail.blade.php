@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Proses Penerimaan Pengadaan</h1>

        <div class="card">
            <div class="card-body">
                <!-- Menampilkan ID Pengadaan -->
                <h3>ID Pengadaan: {{ $pengadaanData->idpengadaan }}</h3>
                <p><strong>Nama Vendor:</strong> {{ $pengadaanData->nama_vendor }}</p>

                <!-- Menampilkan detail barang -->
                <h4>Detail Barang:</h4>
                @if ($detailPengadaan->isNotEmpty())
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailPengadaan as $detail)
                                <tr>
                                    <td>{{ $detail->nama }}</td>
                                    <td>{{ $detail->nama_satuan }}</td>
                                    <td>{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Detail barang tidak ditemukan.</p>
                @endif
            </div>
        </div>

        <!-- Form untuk menerima barang -->
        <form id="terimaBarangForm" action="{{ route('pengadaan.terima', ['idpengadaan' => $pengadaanData->idpengadaan]) }}" method="POST" class="mt-3">
            @csrf
            <input type="hidden" name="idpengadaan" value="{{ $pengadaanData->idpengadaan }}">
            <input type="hidden" name="iduser" value="{{ $pengadaanData->iduser }}">

            <div class="mb-3">
                <button type="submit" class="btn btn-success">Terima Barang</button>
            </div>
        </form>

        <!-- Menampilkan response dari server -->
        <div id="responseMessage" class="mt-3"></div>
    </div>

    <!-- JavaScript untuk menangani form submission menggunakan AJAX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('terimaBarangForm');
            const responseMessage = document.getElementById('responseMessage');

            if (form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault(); // Mencegah reload halaman

                    const formData = new FormData(form);

                    fetch(form.action, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json()) // Mengubah respons menjadi JSON
                        .then(data => {
                            if (data.status === 'success') {
                                responseMessage.innerHTML =
                                    `<div class="alert alert-success">${data.message}</div>`;
                            } else {
                                responseMessage.innerHTML =
                                    `<div class="alert alert-danger">${data.message}</div>`;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            responseMessage.innerHTML =
                                `<div class="alert alert-danger">Terjadi kesalahan saat mengirim data.</div>`;
                        });
                });
            } else {
                console.error('Form tidak ditemukan.');
            }
        });
    </script>
@endsection
