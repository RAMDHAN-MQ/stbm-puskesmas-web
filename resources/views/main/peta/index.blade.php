@extends('layouts.master')
@section('title', 'PETA SEBARAN STBM')

@section('content')

<h2 class="mb-3">Peta Sebaran STBM Kecamatan Banyakan</h2>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-success text-white">
        <strong>Filter Data Rekomendasi</strong>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.peta.index') }}">
            <div class="row align-items-end g-3">
                <div class="col-md-4">
                    <select name="filter"
                        class="form-select shadow-sm"
                        onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($tahun as $t)
                        <option value="{{ $t }}"
                            {{ $filterTahun == $t ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="bg-white p-4 rounded-3 shadow-sm">
    <!-- map -->
    <div class="row">
        <div class="col">
            <div id="map" class="mt-3"
                style="height: 520px; border-radius: 10px;"></div>
        </div>
    </div>

    <!-- keterangan -->
    <div class="bg-warning bg-opacity-10 text-dark p-3 rounded-3 mt-4">
        <h4><i class="bi bi-circle-fill text-warning me-2"></i>Keterangan</h4>
        <div class="row mb-3">
            <div class="col">
                <small class="text-muted">
                    Data diambil dari STBM yang <b>sudah diverifikasi selesai</b>
                </small>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-6">
                <div class="d-flex gap-3">
                    <span><span class="badge bg-success">&nbsp;</span> Layak</span>
                    <span><span class="badge bg-warning">&nbsp;</span> Cukup</span>
                    <span><span class="badge bg-danger">&nbsp;</span> Tidak Layak</span>
                    <span><span class="badge bg-secondary">&nbsp;</span> Belum ada data</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Kirim data status desa ke JS
    const statusDesa = @json($statusDesa);
</script>
<script src="{{ asset('js/peta.js') }}"></script>
@endpush