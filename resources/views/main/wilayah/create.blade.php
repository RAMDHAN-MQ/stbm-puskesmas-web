@extends('layouts.master')
@section('title', 'Wilayah | Create')
@section('content')

<a class="btn btn-outline-success mb-3" href="{{ route('admin.wilayah.index') }}"><i class="bi bi-chevron-left"></i> Kembali</a>

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


<form action="{{ route('admin.wilayah.store') }}" method="POST" class="card p-4 shadow-sm">
    @csrf

    <div class="mb-3">
        <label class="form-label">Kota / Kabupaten <span class="text-danger">*</span></label>
        <input type="text"
            name="kota"
            class="form-control"
            placeholder="Masukkan Kota / Kabupaten"
            value="{{ old('kota') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
        <input type="text"
            name="kecamatan"
            class="form-control"
            placeholder="Masukkan Kecamatan"
            value="{{ old('kecamatan') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Desa <span class="text-danger">*</span></label>
        <input type="text"
            name="desa"
            class="form-control"
            placeholder="Masukkan desa"
            value="{{ old('desa') }}">
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-outline-success">
            Simpan
        </button>
    </div>
</form>


@endsection