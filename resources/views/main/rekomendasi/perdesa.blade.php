@extends('layouts.master')
@section('title', 'Rekomendasi Per Desa')

@section('content')

<h2 class="mb-3">Rekomendasi Detail Wilayah STBM</h2>

<div class="mb-4">
    <a href="{{ route('admin.rekomendasi.index') }}"
        class="btn {{ request()->routeIs('admin.rekomendasi.index') ? 'btn-success' : 'btn-outline-success' }}">
        Semua Desa
    </a>

    <a href="{{ route('admin.rekomendasi.perdesa') }}"
        class="btn {{ request()->routeIs('admin.rekomendasi.perdesa') ? 'btn-success' : 'btn-outline-success' }}">
        Per Desa
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white">
        <strong>Filter Data Rekomendasi</strong>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.rekomendasi.perdesa') }}">
            <div class="row g-3">

                <div class="col-md-4">
                    <select name="desa" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Pilih Desa --</option>
                        @foreach($daftarDesa as $desa)
                        <option value="{{ $desa->id }}"
                            {{ $desaId == $desa->id ? 'selected' : '' }}>
                            {{ $desa->desa }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

@if($desaDipilih)

<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white">
        <strong>Chart Timeseries Rasio Layak (%) - {{ $desaDipilih->desa }}</strong>
    </div>
    <div class="card-body" style="height:300px;">
        <canvas id="timeSeriesChart"></canvas>
    </div>
</div>

@endif

@endsection

@push('scripts')
@if($desaDipilih)
<script>
    const labels = @json(array_keys($timeseries));
    const dataRasio = @json(array_values($timeseries));

    const ctx = document.getElementById('timeSeriesChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Rasio KK Layak (%)',
                data: dataRasio,
                fill: false,
                tension: 0.3,
                borderColor: 'rgba(25, 135, 84, 1)',
                backgroundColor: 'rgba(25, 135, 84, 0.2)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    min: 0,
                    max: 100
                }
            }
        }
    });
</script>
@endif
@endpush