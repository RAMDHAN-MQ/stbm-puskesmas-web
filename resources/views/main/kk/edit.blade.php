@extends('layouts.master')
@section('title', 'KK | Edit')
@section('content')

<a class="btn btn-outline-success mb-3" href="{{ route('admin.kk.index') }}"><i class="bi bi-chevron-left"></i> Kembali</a>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Terjadi kesalahan!</strong>
    <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif


<form action="{{ route('admin.kk.update', $kk->no_kk) }}" method="POST" class="card p-4 shadow-sm">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">NO KK <span class="text-danger">*</span></label>
        <input type="number"
            name="no_kk"
            class="form-control"
            placeholder="Masukkan Nomor KK"
            value="{{ old('no_kk', $kk->no_kk) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Nama Kepala <span class="text-danger">*</span></label>
        <input type="text"
            name="nama_kepala_kk"
            class="form-control"
            placeholder="Masukkan Nama Kepala Keluarga"
            value="{{ old('nama_kepala_kk', $kk->nama_kepala_kk) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Wilayah <span class="text-danger">*</span></label>
        <select name="wilayah" id="wilayah" class="form-select">
            <option value="">-- Pilih Wilayah --</option>
            @foreach($wilayah as $item)
            <option value="{{ $item->id }}"
                {{ old('wilayah', $kk->wilayah_id) == $item->id ? 'selected' : '' }}>
                {{ $item->desa }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">RT <span class="text-danger">*</span></label>
        <input type="number"
            name="rt"
            class="form-control"
            placeholder="Masukkan Nomor RT"
            value="{{ old('rt', $kk->rt) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">RW <span class="text-danger">*</span></label>
        <input type="number"
            name="rw"
            class="form-control"
            placeholder="Masukkan Nomor RW"
            value="{{ old('rw', $kk->rw) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Jumlah Jiwa <span class="text-danger">*</span></label>
        <input type="number"
            name="jumlah_jiwa"
            class="form-control"
            placeholder="Masukkan Jumlah Jiwa"
            value="{{ old('jumlah_jiwa',$kk->jumlah_jiwa) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Jumlah Jiwa Menetap <span class="text-danger">*</span></label>
        <input type="number"
            name="jumlah_jiwa_menetap"
            class="form-control"
            placeholder="Masukkan Jumlah Jiwa Menetap"
            value="{{ old('jumlah_jiwa_menetap', $kk->jumlah_jiwa_menetap) }}">
    </div>


    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-outline-success">
            Simpan
        </button>
    </div>
</form>


@endsection