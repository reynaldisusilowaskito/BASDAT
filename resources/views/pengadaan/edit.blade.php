@extends('layouts.app')

@section('content')
    <h1>Edit Pengadaan</h1>
    <form action="{{ route('pengadaan.update', $pengadaan->idpengadaan) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Form Pengadaan Status -->
        <div class="mb-4">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="1" {{ $pengadaan->status == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $pengadaan->status == '0' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <!-- Loop untuk Detail Pengadaan -->
        @foreach ($detailPengadaan as $detail)
        <div class="card mb-4 p-3">
            <h5>Detail Barang {{ $loop->iteration }}</h5>
            
            <!-- Dropdown Barang -->
            <div class="mb-3">
                <label for="barang_id_{{ $detail->iddetail_pengadaan }}" class="form-label">Barang</label>
                <select name="detail_pengadaan[{{ $detail->iddetail_pengadaan }}][barang_id]" 
                        id="barang_{{ $detail->iddetail_pengadaan }}" class="form-control">
                    <option value="" disabled>Pilih Barang</option>
                    @foreach ($barangList as $barang)
                        <option value="{{ $barang->idbarang }}" 
                                data-harga="{{ $barang->harga }}" 
                                {{ $detail->idbarang == $barang->idbarang ? 'selected' : '' }}>
                            {{ $barang->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Harga Satuan -->
            <div class="mb-3">
                <label for="harga_satuan_{{ $detail->iddetail_pengadaan }}" class="form-label">Harga Satuan</label>
                <input type="number" name="detail_pengadaan[{{ $detail->iddetail_pengadaan }}][harga_satuan]" 
                       id="harga_satuan_{{ $detail->iddetail_pengadaan }}" class="form-control" 
                       value="{{ $detail->harga_satuan }}" readonly>
            </div>

            <!-- Jumlah -->
            <div class="mb-3">
                <label for="jumlah_{{ $detail->iddetail_pengadaan }}" class="form-label">Jumlah</label>
                <input type="number" name="detail_pengadaan[{{ $detail->iddetail_pengadaan }}][jumlah]" 
                       id="jumlah_{{ $detail->iddetail_pengadaan }}" class="form-control" 
                       value="{{ $detail->jumlah }}">
            </div>

            <!-- Sub Total -->
            <div class="mb-3">
                <label for="sub_total_{{ $detail->iddetail_pengadaan }}" class="form-label">Sub Total</label>
                <input type="number" name="detail_pengadaan[{{ $detail->iddetail_pengadaan }}][sub_total]" 
                       id="sub_total_{{ $detail->iddetail_pengadaan }}" class="form-control" 
                       value="{{ $detail->sub_total }}" readonly>
            </div>
        </div>
        @endforeach

        <!-- Button Save -->
        <div class="mb-3">
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </div>
    </form>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        @foreach ($detailPengadaan as $detail)
            const barangSelect{{ $detail->iddetail_pengadaan }} = document.getElementById('barang_{{ $detail->iddetail_pengadaan }}');
            const hargaSatuanInput{{ $detail->iddetail_pengadaan }} = document.getElementById('harga_satuan_{{ $detail->iddetail_pengadaan }}');
            const jumlahInput{{ $detail->iddetail_pengadaan }} = document.getElementById('jumlah_{{ $detail->iddetail_pengadaan }}');
            const subTotalInput{{ $detail->iddetail_pengadaan }} = document.getElementById('sub_total_{{ $detail->iddetail_pengadaan }}');

            // Ketika barang dipilih, update harga satuan dan sub total
            barangSelect{{ $detail->iddetail_pengadaan }}.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const harga = selectedOption.getAttribute('data-harga');
                hargaSatuanInput{{ $detail->iddetail_pengadaan }}.value = harga;

                // Update sub total jika jumlah sudah diisi
                const jumlah = jumlahInput{{ $detail->iddetail_pengadaan }}.value;
                subTotalInput{{ $detail->iddetail_pengadaan }}.value = harga * jumlah;
            });

            // Jika jumlah diubah, update sub total
            jumlahInput{{ $detail->iddetail_pengadaan }}.addEventListener('input', function () {
                const harga = hargaSatuanInput{{ $detail->iddetail_pengadaan }}.value;
                const jumlah = this.value;
                subTotalInput{{ $detail->iddetail_pengadaan }}.value = harga * jumlah;
            });
        @endforeach
    });
    </script>
@endsection
