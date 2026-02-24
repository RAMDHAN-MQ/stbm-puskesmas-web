@extends('layouts.master')
@section('title', 'Pegawai | Lihat')

@section('content')

<a class="btn btn-outline-success mb-3" href="{{ route('admin.pegawai.index') }}"><i class="bi bi-chevron-left"></i> Kembali</a>

<div class="bg-white p-4 rounded-3 shadow-sm">

    <div class="d-flex align-items-center mb-4">
        <img src="{{ $user->foto
            ? asset('storage/profile/' . $user->foto)
            : asset('storage/images/default.png') }}"
            class="rounded-circle border"
            width="80"
            height="80"
            style="object-fit: cover;">

        <div class="ms-3">
            <h5 class="mb-1">{{ $user->nama }}</h5>
            <span class="badge rounded-pill
                {{ $user->role === 'admin'
                    ? 'bg-success bg-opacity-25 text-success'
                    : 'bg-primary bg-opacity-25 text-primary' }}">
                {{ $user->role }}
            </span>
        </div>
    </div>

    <table class="table table-borderless">
        <tr>
            <th width="150">NIDN</th>
            <td>: {{ $user->nidn }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>: {{ $user->email }}</td>
        </tr>
    </table>

</div>

@endsection