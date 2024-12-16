@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Buat Penjualan</h1>
        <form id="penjualanForm">
            @csrf
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
                        <option value="{{ $item->idbarang }}" data-harga="{{ $item->harga }} "
                            data-stok="{{ $item->stock }}" data-nama="{{ $item->nama }}">
                            {{ $item->nama }} - Rp{{ number_format($item->harga, 0, ',', '.') }} (Stok:
                            {{ $item->stock }})
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

            <!-- Pilih Margin Penjualan -->
            <div class="form-group">
                <label for="idmargin_penjualan">Margin Penjualan</label>
                <select name="idmargin_penjualan" id="idmargin_penjualan" class="form-control" required>
                    <option value="" disabled selected>Pilih Margin Penjualan</option>
                    @foreach ($marginPenjualan as $margin)
                        <option value="{{ $margin->idmargin_penjualan }}">{{ $margin->persen }}%</option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih User -->
            <div class="form-group">
                <label for="iduser">Pilih User</label>
                <select name="iduser" id="iduser" class="form-control" required>
                    <option value="" disabled selected>Pilih User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->iduser }}">{{ $user->username }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Simpan Penjualan</button>
        </form>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script>
        $(document).ready(function() {
            let barangPilih = [];

            // Menangani perubahan pilihan barang
            $('#id_barang').on('change', function() {
                const selectedOption = $(this).find(':selected');
                const harga = selectedOption.data('harga');
                const namaBarang = selectedOption.data('nama');
                const stock = selectedOption.data('stok');

                if (namaBarang && harga && stock > 0) {
                    $('#harga_barang').val(harga); // Update harga barang
                } else {
                    console.error("Data barang tidak lengkap atau stock habis!");
                }
            });

            // Menambahkan barang ke daftar
            $('#addBarang').on('click', function() {
                const idBarang = $('#id_barang').val();
                const namaBarang = $('#id_barang option:selected').data('nama');
                const hargaBarang = parseFloat($('#harga_barang').val());
                const quantity = parseInt($('#quantity').val());

                if (!idBarang || isNaN(hargaBarang) || isNaN(quantity) || quantity <= 0) {
                    alert('Lengkapi data barang sebelum menambahkan.');
                    return;
                }

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
                    });
                }

                resetBarang();
                updateTable();
                totalNilai();
            });
            // Fungsi untuk mereset form dan data barang yang dipilih
            function resetBarang() {
                // Mengatur ulang pilihan barang dan harga barang
                $('#id_barang').val('');
                $('#harga_barang').val('');
                $('#quantity').val(1); // Atur jumlah barang kembali ke 1
            }

            // Mengupdate tabel barang yang dipilih
            function updateTable() {
                $('#tableList tbody').empty();
                barangPilih.forEach((item) => {
                    const row = `
                    <tr id="row-${item.id_barang}">
                        <td>${item.id_barang}</td>
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
                    </tr>`;
                    $('#tableList tbody').append(row);
                });
            }

            // Mengupdate jumlah barang yang dipilih
            function updateQuantity(idBarang, quantity) {
                const item = barangPilih.find(item => item.id_barang === idBarang);
                if (item) {
                    item.quantity = quantity;
                    item.subtotal = item.harga * quantity;
                    updateTable();
                    totalNilai();
                }
            }

            // Menghapus barang dari daftar
            function hapusBarang(idBarang) {
                barangPilih = barangPilih.filter(item => item.id_barang !== idBarang);
                updateTable();
                totalNilai();
            }

            // Menghitung total, PPN dan subtotal
            function totalNilai() {
                const subtotal = barangPilih.reduce((total, item) => total + item.subtotal, 0);
                const ppn = subtotal * 0.11;
                const total = subtotal + ppn;

                $('#subtotal').val(subtotal.toFixed(2));
                $('#ppn').val(ppn.toFixed(2));
                $('#total').val(total.toFixed(2));
            }

            // Mengirim data ke backend melalui AJAX
            $('#penjualanForm').submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                // Append barang data to formData
                barangPilih.forEach(function(item, index) {
                    formData.append(`barang[${index}][id_barang]`, item.id_barang);
                    formData.append(`barang[${index}][nama_barang]`, item.nama_barang);
                    formData.append(`barang[${index}][harga]`, item.harga);
                    formData.append(`barang[${index}][quantity]`, item.quantity);
                    formData.append(`barang[${index}][subtotal]`, item.subtotal);
                });

                // Log FormData contents to check if barang data is added
                for (let [key, value] of formData.entries()) {
                    console.log(key + ": " + value);
                }

                $.ajax({
                    url: '/penjualan',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response.message);
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan');
                    }
                });
            });

        });
    </script>
@endsection