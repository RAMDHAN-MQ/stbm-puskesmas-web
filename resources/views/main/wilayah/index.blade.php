@extends('layouts.master')
@section('title', 'Wilayah')
@section('content')

<h2 class="mb-3">Wilayah</h2>

<div class="bg-white p-4 rounded-3 shadow-sm">

    <a class="btn btn-outline-success" href="{{ route('admin.wilayah.create') }}">+ Tambah</a>
    <hr>

    <table id="wilayahTable" class="table table-striped table-hover align-middle">
        <thead class="table-success">
            <tr>
                <th class="text-center">No</th>
                <th>Kota / Kabupaten</th>
                <th>Kecamatan</th>
                <th>Desa</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($wilayah as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item->kota }}</td>
                <td>{{ $item->kecamatan }}</td>
                <td>{{ $item->desa }}</td>
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
                                <a class="dropdown-item" href="{{ route('admin.wilayah.edit', $item->id) }}">
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
                                    data-wilayah="{{ $item->desa }}">
                                    <i class="bi bi-trash me-2"></i> Hapus
                                </button>
                            </li>
                        </ul>
                    </div>
                    <form id="delete-form-{{ $item->id }}"
                        action="{{ route('admin.wilayah.destroy', $item->id) }}"
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
        $('#wilayahTable').DataTable({
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
            const wilayah = this.dataset.wilayah;

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                html: `
                <p>
                    Data STBM
                    <strong style="color:#dc3545">Desa ${wilayah}</strong>
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