@extends('layouts.master')
@section('title', 'Pegawai')
@section('content')

<h2 class="mb-3">Pegawai</h2>

<div class="bg-white p-4 rounded-3 shadow-sm">

    <a class="btn btn-outline-success" href="{{ route('admin.pegawai.create') }}">+ Tambah</a>
    <hr>

    <table id="pegawaiTable" class="table table-striped table-hover align-middle">
        <thead class="table-success">
            <tr>
                <th class="text-center">NIP</th>
                <th>Nama</th>
                <th>Email</th>
                <th class="text-center">Role</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $item)
            <tr>
                <td class="text-center">{{ $item->nip }}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        @if($item->foto)
                        <img src="{{ asset('storage/profile/' . $item->foto) }}"
                            class="rounded-circle border"
                            width="40"
                            height="40"
                            style="object-fit: cover;">
                        @else
                        <img src="{{ asset('storage/images/default.png') }}"
                            class="rounded-circle border"
                            width="40"
                            height="40"
                            style="object-fit: cover;">
                        @endif

                        <span>{{ $item->nama }}</span>
                    </div>
                </td>

                <td>{{ $item->email }}</td>
                @if($item->role === 'admin')
                <td class="text-center"><span class="badge rounded-pill text-bg-success bg-opacity-25 text-success">{{ $item->role }}</span></td>
                @else
                <td class="text-center"><span class="badge rounded-pill text-bg-primary bg-opacity-25 text-primary">{{ $item->role }}</span></td>
                @endif
                <td class="text-center">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border-0"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-three-dots"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.pegawai.view', $item->id) }}">
                                    <i class="bi bi-eye me-2"></i> Lihat
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.pegawai.edit', $item->id) }}">
                                    <i class="bi bi-pencil-square me-2"></i> Edit
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <button type="button"
                                    class="dropdown-item text-danger btn-delete"
                                    data-id="{{ $item->id }}"
                                    data-nama="{{ $item->nama }}">
                                    <i class="bi bi-trash me-2"></i> Hapus
                                </button>
                            </li>
                        </ul>
                    </div>
                    <form id="delete-form-{{ $item->id }}"
                        action="{{ route('admin.pegawai.destroy', $item->id) }}"
                        method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td class="text-center text-muted">-</td>
                <td class="text-center text-muted">-</td>
                <td class="text-center text-muted">-</td>
                <td class="text-center text-muted">-</td>
                <td class="text-center text-muted">-</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#pegawaiTable').DataTable({
            pagingType: "simple_numbers",
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Cari...",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    next: "›",
                    previous: "‹"
                },
                zeroRecords: "Data tidak ditemukan",
                emptyTable: "Belum ada data"
            }
        });
    });
</script>

<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const nama = this.dataset.nama;

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                html: `
                <p>
                    Data pegawai
                    <strong style="color:#dc3545">${nama}</strong>
                    akan dihapus permanen!
                </p>
            `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        });
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: @json(session('success')),
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

@endpush