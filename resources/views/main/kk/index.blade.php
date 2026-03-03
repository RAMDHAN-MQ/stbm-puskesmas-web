@extends('layouts.master')
@section('title', 'Kartu Keluarga')
@section('content')

<h2 class="mb-3">Kelola KK</h2>

<div class="bg-white p-4 rounded-3 shadow-sm">
    <a class="btn btn-outline-success" href="{{ route('admin.kk.create') }}">+ Tambah</a>
    <hr>

    <table id="kkTable" class="table table-striped table-hover align-middle">
        <thead class="table-success">
            <tr>
                <th class="text-center">No KK</th>
                <th class="text-center">Nama Kepala</th>
                <th class="text-center">Wilayah</th>
                <th class="text-center">RT / RW</th>
                <th class="text-center">Jumlah Jiwa</th>
                <th class="text-center">Jumlah Jiwa Menetap</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kk as $item)
            <tr>
                <td class="text-center">{{ $item->no_kk }}</td>
                <td class="text-center">{{ $item->nama_kepala_kk }}</td>
                <td class="text-center">{{ $item->wilayah->desa }}</td>
                <td class="text-center">
                    {{ str_pad($item->rt, 3, '0', STR_PAD_LEFT) }}
                    /
                    {{ str_pad($item->rw, 3, '0', STR_PAD_LEFT) }}
                </td>
                <td class="text-center">{{ $item->jumlah_jiwa }}</td>
                <td class="text-center">{{ $item->jumlah_jiwa_menetap }}</td>
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
                                <a class="dropdown-item" href="{{ route('admin.kk.edit', $item->no_kk) }}">
                                    <i class="bi bi-pencil-square me-2"></i> Edit
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <button type="button"
                                    class="dropdown-item text-danger btn-delete"
                                    data-id="{{ $item->no_kk }}">
                                    <i class="bi bi-trash me-2"></i> Hapus
                                </button>
                            </li>
                        </ul>
                    </div>
                    <form id="delete-form-{{ $item->no_kk }}"
                        action="{{ route('admin.kk.destroy', $item->no_kk) }}"
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
        $('#kkTable').DataTable({
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

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                html: `
                <p>
                    Data KK
                    <strong style="color:#dc3545">${id}</strong>
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