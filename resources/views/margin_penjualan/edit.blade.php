@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Margin Penjualan</h1>
    <form action="{{ route('margin-penjualan.update', $margin[0]->idmargin_penjualan) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="persen">Persen</label>
            <input type="number" name="persen" id="persen" class="form-control" value="{{ $margin[0]->persen }}" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="1" {{ $margin[0]->status == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $margin[0]->status == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="form-group">
            <label for="iduser">User ID</label>
            <input type="number" name="iduser" id="iduser" class="form-control" value="{{ $margin[0]->iduser }}" required>
        </div>
        <button type="submit" class="btn btn-success">Perbarui</button>
    </form>
</div>
@endsection
