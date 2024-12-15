<h1>Detail Pengadaan ID: {{ $detail->id }}</h1>
<p>Pengadaan: {{ $detail->pengadaan->id }}</p>
<p>Barang: {{ $detail->barang->nama }}</p>
<p>Jumlah: {{ $detail->jumlah }}</p>
<p>Harga Satuan: {{ $detail->harga_satuan }}</p>
<p>Subtotal: {{ $detail->sub_total }}</p>
<a href="{{ route('detail_pengadaan.edit', $detail->id) }}">Edit</a>
