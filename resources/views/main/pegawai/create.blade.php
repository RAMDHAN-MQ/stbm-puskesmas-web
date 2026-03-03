@extends('layouts.master')
@section('title', 'Pegawai | Create')
@section('content')

<a class="btn btn-outline-success mb-3" href="{{ route('admin.pegawai.index') }}"><i class="bi bi-chevron-left"></i> Kembali</a>

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


<form action="{{ route('admin.pegawai.store') }}" method="POST" class="card p-4 shadow-sm" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text"
            name="nama"
            class="form-control"
            placeholder="Masukkan nama lengkap"
            value="{{ old('nama') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">NIP <span class="text-danger">*</span></label>
        <input type="number"
            name="nip"
            class="form-control"
            placeholder="Masukkan NIP"
            value="{{ old('nip') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email"
            name="email"
            class="form-control"
            placeholder="Masukkan email"
            value="{{ old('email') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Password <span class="text-danger">*</span></label>

        <input type="password"
            name="password"
            id="password"
            class="form-control"
            placeholder="Masukkan password">

        <div class="form-check mt-2">
            <input class="form-check-input"
                type="checkbox"
                id="showPassword"
                onclick="togglePasswordCheckbox(this)">
            <label class="form-check-label" for="showPassword">
                Tampilkan Password
            </label>
        </div>
    </div>


    <div class="mb-3">
        <label class="form-label d-block">Role <span class="text-danger">*</span></label>

        <div class="form-check form-check-inline">
            <input class="form-check-input"
                type="radio"
                name="role"
                id="admin"
                value="admin"
                {{ old('role') == 'admin' ? 'checked' : '' }}>
            <label class="form-check-label" for="admin">Admin</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input"
                type="radio"
                name="role"
                id="pegawai"
                value="pegawai"
                {{ old('role') == 'pegawai' ? 'checked' : '' }}>
            <label class="form-check-label" for="pegawai">Pegawai</label>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Foto</label>

        <img id="preview-foto"
            src="#"
            alt="Preview Foto"
            class="img-thumbnail mb-2"
            style="display:none; max-width: 200px;">

        <input type="file"
            name="foto"
            class="form-control"
            accept="image/*"
            onchange="previewImage(this)">
    </div>


    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-outline-success">
            Simpan
        </button>
    </div>
</form>


@endsection

@push('scripts')

<script>
    function previewImage(input) {
        const preview = document.getElementById('preview-foto');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<script>
    function togglePasswordCheckbox(checkbox) {
        const password = document.getElementById('password');
        password.type = checkbox.checked ? 'text' : 'password';
    }
</script>



@endpush