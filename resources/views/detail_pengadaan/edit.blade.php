<form action="{{ route('detail_pengadaan.update', $detail->id) }}" method="POST">
    @csrf
    @method('PUT')
    <select name="idpengadaan">
        @foreach($pengadaans as $pengadaan)
            <option value="{{ $pengadaan->id }}" {{ $detail->idpengadaan == $pengadaan->id ? 'selected' : '' }}>
                {{ $pengadaan->id }}
            </option>
        @endforeach
    </select>
    <select name="idbarang">
        @foreach($barangs as $barang)
            <option value="{{ $barang->id }}" {{ $detail->idbarang == $barang->id ? 'selected' : '' }}>
                {{ $barang->nama }}
            </option>
        @endforeach
    </select>
    <input type="number" name="jumlah" value="{{ $detail->jumlah }}" placeholder="Jumlah">
    <input type="number" name="harga_satuan" value="{{ $detail->harga_satuan }}" placeholder="Harga Satuan">
    <button type="submit">Update</button>
</form>
