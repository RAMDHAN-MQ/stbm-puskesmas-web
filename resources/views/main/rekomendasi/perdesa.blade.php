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

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Rasio Tahun Terakhir</h6>
                <h3 class="text-success">{{ $lastRasio ?? 0 }}%</h3>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Perubahan</h6>
                @if($lastRasio !== null && $prevRasio !== null)
                    @php $selisih = $lastRasio - $prevRasio; @endphp
                    <h3 class="{{ $selisih >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $selisih >= 0 ? '+' : '' }}{{ $selisih }}%
                    </h3>
                @else
                    <h3>-</h3>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white">
        <strong>Chart Timeseries Rasio Layak (%) - {{ $desaDipilih->desa }}</strong>
    </div>
    <div class="card-body" style="height:300px;">
        <canvas id="timeSeriesChart"></canvas>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-body p-4">
        <table class="table table-bordered mb-0">
            <thead class="table-success">
                <tr>
                    <th>Tahun</th>
                    <th>Total KK</th>
                    <th>KK Layak</th>
                    <th>Rasio (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detailPerTahun as $tahun => $data)
                <tr>
                    <td>{{ $tahun }}</td>
                    <td>{{ $data['total'] }}</td>
                    <td>{{ $data['layak'] }}</td>
                    <td>{{ $data['rasio'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="alert alert-warning mt-3">
    @if($lastRasio !== null && $prevRasio !== null)
        @php $selisih = $lastRasio - $prevRasio; @endphp

        @if($selisih > 0)
            Rasio kelayakan meningkat sebesar {{ $selisih }}% dibanding tahun sebelumnya.
        @elseif($selisih < 0)
            Rasio kelayakan menurun sebesar {{ abs($selisih) }}% dibanding tahun sebelumnya.
        @else
            Rasio kelayakan stabil dibanding tahun sebelumnya.
        @endif
    @endif
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