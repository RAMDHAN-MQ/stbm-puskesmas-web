@extends('layouts.master')
@section('title', 'STBM')
@section('content')

<h2 class="mb-3">STBM</h2>

<div class="bg-white p-4 rounded-3 shadow-sm">
    <a class="btn btn-outline-success"
        data-bs-toggle="modal"
        data-bs-target="#modalExportExcel">
        <i class="bi bi-file-earmark-spreadsheet me-2"></i>Excel
    </a>
    <hr>

    <table id="stbmTable" class="table table-striped table-hover align-middle">
        <thead class="table-success">
            <tr>
                <th class="text-center">No</th>
                <th>Desa</th>
                <th>RT/RW</th>
                <th>Petugas</th>
                <th>Kepala Keluarga</th>
                <th class="text-center">Status</th>
                <th>Tanggal</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stbm as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item->wilayah->desa ?? '-' }}</td>
                <td>
                    {{ str_pad($item->rt, 3, '0', STR_PAD_LEFT) }}
                    /
                    {{ str_pad($item->rw, 3, '0', STR_PAD_LEFT) }}
                </td>
                <td>{{ $item->pegawai->nama ?? '-' }}</td>
                <td>{{ $item->nama_kepala_kk ?? '-' }}</td>
                @if($item->status === 'selesai')
                <td class="text-center"><span class="badge rounded-pill text-bg-success bg-opacity-25 text-success">{{ $item->status }}</span></td>
                @else
                <td class="text-center"><span class="badge rounded-pill text-bg-primary bg-opacity-25 text-primary">{{ $item->status }}</span></td>
                @endif
                <td>{{ $item->created_at->format('d-m-Y') }}</td>
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
                                <a class="dropdown-item" href="{{ route('admin.stbm.view', $item->id) }}">
                                    <i class="bi bi-eye me-2"></i> Lihat
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <button type="button"
                                    class="dropdown-item text-danger btn-delete"
                                    data-id="{{ $item->id }}"
                                    data-wilayah="{{ $item->wilayah }}">
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

<!-- modal excel -->
<div class="modal fade" id="modalExportExcel" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.stbm.export') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-file-earmark-spreadsheet me-2"></i>
                        Export Excel STBM
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <!-- Desa -->
                        <div class="col-md-6">
                            <label class="form-label">Desa</label>
                            <select name="desa_id" class="form-select">
                                <option value="">Semua Desa</option>
                                @foreach($desa as $d)
                                    <option value="{{ $d->id }}">{{ $d->desa }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="proses">Proses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>

                        <!-- Tanggal -->
                        <div class="col-md-6">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="tanggal_mulai" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="tanggal_selesai" class="form-control">
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit"
                        class="btn btn-success">
                        <i class="bi bi-download me-1"></i> Export Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#stbmTable').DataTable({
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
                    <strong style="color:#dc3545">${wilayah}</strong>
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