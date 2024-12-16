@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Buat Pengadaan</h1>
        <form id="pengadaanForm">
            @csrf <!-- CSRF Token untuk keamanan -->

            <!-- Pilih Vendor -->
            <div class="form-group">
                <label for="id_vendor">Vendor</label>
                <select name="id_vendor" id="id_vendor" class="form-control" required>
                    <option value="" disabled selected>Pilih Vendor</option>
                    @foreach ($vendors as $vendor)
                        <option value="{{ $vendor->idvendor }}">{{ $vendor->nama_vendor }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="id_user">Pilih User</label>
                <select name="id_user" id="id_user" class="form-control" required>
                    <option value="" disabled selected>Pilih User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->iduser }}">{{ $user->username }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Subtotal -->
            <div class="form-group">
                <label for="subtotal">Subtotal</label>
                <input type="number" name="subtotal" id="subtotal" class="form-control" step="0.01" readonly>
            </div>

            <!-- PPN (11%) -->
            <div class="form-group">
                <label for="ppn">PPN (11%)</label>
                <input type="number" id="ppn" class="form-control" readonly>
            </div>

            <!-- Total -->
            <div class="form-group">
                <label for="total">Total</label>
                <input type="number" id="total" class="form-control" readonly>
            </div>

            <!-- Pilih Barang -->
            <div class="form-group">
                <label for="id_barang">Pilih Barang</label>
                <select id="id_barang" class="form-control">
                    <option value="" disabled selected>Pilih Barang</option>
                    @foreach ($barang as $item)
                        <option value="{{ $item->idbarang }}" data-harga="{{ $item->harga }}" data-nama="{{ $item->nama }}">
                            {{ $item->nama }} - Rp{{ number_format($item->harga, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="harga_barang">Harga Barang</label>
                <input type="text" id="harga_barang" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="quantity">Jumlah</label>
                <input type="number" id="quantity" class="form-control" value="1" min="1">
            </div>
            <button type="button" id="addBarang" class="btn btn-primary">Tambah Barang</button>

            <!-- Daftar Barang -->
            <h4>Daftar Barang</h4>
            <table class="table" id="tableList">
                <thead>
                    <tr>
                        <th>ID Barang</th>
                        <th>Satuan</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Barang akan diisi dengan JavaScript -->
                </tbody>
            </table>

            <!-- Total -->
            <div class="form-group">
                <label>PPN (11%)</label>
                <p id="displayPPN"></p>
            </div>
            <div class="form-group">
                <label>Total</label>
                <p id="displayTotal"></p>
            </div>

            <!-- Submit Button -->
            <button type="button" id="simpan" class="btn btn-success mt-3">Simpan Pengadaan</button>
        </form>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <script>
        $(document).ready(function () {
            let barangPilih = [];
    
            // Event untuk memilih barang dari dropdown
            $('#id_barang').on('change', function () {
                const selectedOption = $(this).find(':selected');
                const harga = selectedOption.data('harga');
                const namaBarang = selectedOption.data('nama');
    
                if (namaBarang && harga) {
                    $('#harga_barang').val(harga); // Update harga barang
                } else {
                    console.error("Data barang tidak lengkap!");
                }
            });
    
            // Event untuk menambahkan barang ke daftar
            $('#addBarang').on('click', function () {
                const idBarang = $('#id_barang').val();
                const namaBarang = $('#id_barang option:selected').data('nama');
                const hargaBarang = parseFloat($('#harga_barang').val());
                const quantity = parseInt($('#quantity').val());
    
                // Validasi data
                if (!idBarang || isNaN(hargaBarang) || isNaN(quantity) || quantity <= 0) {
                    alert('Lengkapi data barang sebelum menambahkan.');
                    return;
                }
    
                // Cek apakah barang sudah ada di daftar
                const existingItem = barangPilih.find((item) => item.id_barang === idBarang);
                if (existingItem) {
                    existingItem.quantity += quantity;
                    existingItem.subtotal = existingItem.quantity * existingItem.harga;
                } else {
                    barangPilih.push({
                        id_barang: idBarang,
                        nama_barang: namaBarang,
                        harga: hargaBarang,
                        quantity: quantity,
                        subtotal: hargaBarang * quantity,
                        status: 'belum ditangani' // Status awal barang adalah belum ditangani
                    });
                }
    
                // Reset input barang
                resetBarang();
                // Update tabel dan total
                updateTable();
                totalNilai();
            });
    
            // Fungsi untuk update tabel daftar barang
            function updateTable() {
                $('#tableList tbody').empty();
                barangPilih.forEach((item) => {
                    const statusLabel = item.status.charAt(0).toUpperCase() + item.status.slice(1);
                    
                    const row = `
                        <tr id="row-${item.id_barang}">
                            <td>${item.id_barang}</td>
                            <td>${item.nama_satuan || 'N/A'}</td>
                            <td>${item.nama_barang}</td>
                            <td>${item.harga.toLocaleString('id-ID')}</td>
                            <td>
                                <input type="number" class="form-control" value="${item.quantity}" min="1"
                                    onchange="updateQuantity('${item.id_barang}', this.value)">
                            </td>
                            <td id="subtotal-${item.id_barang}">${item.subtotal.toLocaleString('id-ID')}</td>
                            <td>
                                <button class="btn btn-danger" onclick="hapusBarang('${item.id_barang}')">Hapus</button>
                
                            </td>
                            <td>
                                <span class="badge badge-info">${statusLabel}</span>
                            </td>
                        </tr>`;
                    $('#tableList tbody').append(row);
                });
            }
    
            // Fungsi untuk menghitung total nilai
            function totalNilai() {
                const subtotal = barangPilih.reduce((total, item) => total + item.subtotal, 0);
                const ppn = subtotal * 0.11;
                const total = subtotal + ppn;
    
                $('#subtotal').val(subtotal.toFixed(2));
                $('#ppn').val(ppn.toFixed(2));
                $('#total').val(total.toFixed(2));
    
                $('#displayPPN').text(ppn.toLocaleString('id-ID'));
                $('#displayTotal').text(total.toLocaleString('id-ID'));
            }
    
            // Fungsi untuk reset input barang
            function resetBarang() {
                $('#id_barang').val('');
                $('#harga_barang').val('');
                $('#quantity').val(1);
            }
    
            // Fungsi untuk update quantity barang
            window.updateQuantity = function (idBarang, quantity) {
                quantity = parseInt(quantity);
                if (quantity < 1) return;
    
                const item = barangPilih.find((item) => item.id_barang === idBarang);
                if (item) {
                    item.quantity = quantity;
                    item.subtotal = item.harga * quantity;
                }
    
                updateTable();
                totalNilai();
            };
    
            // Fungsi untuk menghapus barang
            window.hapusBarang = function (idBarang) {
                barangPilih = barangPilih.filter((item) => item.id_barang !== idBarang);
                updateTable();
                totalNilai();
            };
    
            // Fungsi untuk menangani aksi Terima
            window.terimaBarang = function (idBarang) {
                const item = barangPilih.find((item) => item.id_barang === idBarang);
                if (item) {
                    item.status = 'terima'; // Set status barang sebagai terima
                }
    
                updateTable(); // Update tabel setelah status diubah
                totalNilai();  // Update total setelah ada perubahan
                Swal.fire({
                    icon: 'success',
                    title: 'Barang Diterima',
                    text: `Barang ${item.nama_barang} telah diterima.`,
                });
            };
    
            // Fungsi untuk menangani aksi Tolak
            window.tolakBarang = function (idBarang) {
                const item = barangPilih.find((item) => item.id_barang === idBarang);
                if (item) {
                    item.status = 'tolak'; // Set status barang sebagai tolak
                }
    
                updateTable(); // Update tabel setelah status diubah
                totalNilai();  // Update total setelah ada perubahan
                Swal.fire({
                    icon: 'warning',
                    title: 'Barang Ditolak',
                    text: `Barang ${item.nama_barang} telah ditolak.`,
                });
            };
    
            // Event untuk menyimpan pengadaan
            $('#simpan').on('click', function () {
                const idVendor = $('#id_vendor').val();
                const idUser = $('#id_user').val();
                const subtotal = parseFloat($('#subtotal').val());
    
                // Validasi data pengadaan
                if (!idVendor || !idUser || barangPilih.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Tidak Lengkap',
                        text: 'Pastikan semua data terisi dengan benar sebelum menyimpan.',
                    });
                    return;
                }
    
                // Kirim data ke server
                $.ajax({
                    type: 'POST',
                    url: '/pengadaan/store',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id_vendor: idVendor,
                        id_user: idUser,
                        subtotal: subtotal,
                        barang: barangPilih,
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Pengadaan berhasil disimpan!',
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function (xhr) {
                        let errorMessage = 'Terjadi kesalahan saat menyimpan.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errorDetails = Object.values(xhr.responseJSON.errors)
                                .map((error) => error.join(', '))
                                .join('<br>');
                            errorMessage += `<br>${errorDetails}`;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            html: errorMessage,
                        });
                    },
                });
                // Menggunakan jQuery untuk Ajax request (misalnya ketika tombol Terima ditekan)
$(document).on('click', '#terimaButton', function(e) {
    e.preventDefault();
    
    // Ambil data yang diperlukan dari elemen HTML
    let idpengadaan = $('#idpengadaan').val(); // ID pengadaan
    let iduser = $('#iduser').val();           // ID user
    let status = 'A';                          // Status penerimaan barang (contoh: 'A' = diterima)
    let barang_id = $('#barang_id').val();     // ID barang yang diterima
    let jumlah = $('#jumlah').val();           // Jumlah barang yang diterima
    let tanggal = $('#tanggal').val();         // Tanggal penerimaan barang
    
    // Kirim data melalui Ajax
    $.ajax({
        url: '/insert-penerimaan', // URL route ke controller yang menangani penerimaan
        type: 'POST',
        data: {
            idpengadaan: idpengadaan,
            iduser: iduser,
            status: status,
            barang_id: barang_id,
            jumlah: jumlah,
            tanggal: tanggal
        },
        success: function(response) {
            alert(response.message); // Beri feedback jika berhasil
        },
        error: function(xhr) {
            alert('Terjadi kesalahan: ' + xhr.responseText); // Tangani error
        }
    });
});

            });
        });
    </script>
@endsection
