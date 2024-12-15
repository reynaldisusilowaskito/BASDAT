<form action="{{ route('penerimaan.index') }}" method="POST">
    @csrf
    <select name="idpengadaan">
        @foreach($pengadaans as $pengadaan)
            <option value="{{ $pengadaan->id }}">{{ $pengadaan->id }}</option>
        @endforeach
    </select>
    <select name="iduser">
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->username }}</option>
        @endforeach
    </select>
    <input type="text" name="status" placeholder="Status">
    <button type="submit">Simpan</button>
</form>
