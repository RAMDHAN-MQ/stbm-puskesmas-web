@extends('layouts.master')
@section('title', 'Dashboard')

@section('content')

<h2 class="mb-4">Dashboard</h2>

<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <small class="text-muted">Total</small>
                <h3 class="mb-0">{{ $total }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <small class="text-muted">Proses</small>
                <h3 class="mb-0 text-primary">{{ $proses }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <small class="text-muted">Selesai</small>
                <h3 class="mb-0 text-success">{{ $selesai }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <small class="text-muted">Rumah Layak</small>
                <h3 class="mb-0 text-success">{{ $layak }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <small class="text-muted">Rumah Tidak Layak</small>
                <h3 class="mb-0 text-danger">{{ $tidakLayak }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <strong>STBM Terbaru</strong>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Desa</th>
                    <th>Petugas</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($terbaru as $item)
                <tr>
                    <td>{{ $item->wilayah->desa ?? '-' }}</td>
                    <td>{{ $item->pegawai->nama ?? '-' }}</td>
                    <td>
                        @if($item->status === 'selesai')
                        <span class="badge rounded-pill text-bg-success bg-opacity-25 text-success">Selesai</span>
                        @else
                        <span class="badge rounded-pill text-bg-primary bg-opacity-25 text-primary">Proses</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.stbm.view', $item->id) }}"
                            class="btn btn-sm btn-outline-success">
                            Lihat
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-3">
                        Belum ada data
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row g-3 mt-3">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <strong>Kondisi STBM per Desa</strong>
            </div>
            <div class="card-body" style="height: 400px;">
                <canvas id="desaChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <strong>Pilar Layak</strong>
            </div>
            <div class="card-body" style="height: 400px;">
                <canvas id="pilarChart"></canvas>
            </div>
        </div>
    </div>

</div>

@endsection
@push('scripts')
<script>
    // ===== BAR CHART DESA =====
    const desaLabels = @json($desaData->pluck('desa'));
    const desaLayak = @json($desaData->pluck('layak'));
    const desaTidakLayak = @json($desaData->pluck('tidak_layak'));

    new Chart(document.getElementById('desaChart'), {
        type: 'bar',
        data: {
            labels: desaLabels,
            datasets: [
                {
                    label: 'Layak',
                    data: desaLayak,
                    backgroundColor: '#198754'
                },
                {
                    label: 'Tidak Layak',
                    data: desaTidakLayak,
                    backgroundColor: '#dc3545'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });

    // ===== PIE CHART PILAR =====
    new Chart(document.getElementById('pilarChart'), {
        type: 'pie',
        data: {
            labels: [
                'Pilar 1',
                'Pilar 2',
                'Pilar 3',
                'Pilar 4',
                'Pilar 5'
            ],
            datasets: [{
                data: @json(array_values($pilarLayak)),
                backgroundColor: [
                    '#198754',
                    '#20c997',
                    '#0d6efd',
                    '#ffc107',
                    '#6f42c1'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                datalabels: {
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    formatter: (value, ctx) => {
                        const data = ctx.chart.data.datasets[0].data;
                        const total = data.reduce((a, b) => a + b, 0);
                        const percent = total ? ((value / total) * 100).toFixed(1) : 0;
                        return percent + '%';
                    }
                },
                legend: {
                    position: 'bottom'
                }
            }
        },
        plugins: [ChartDataLabels]
    });

</script>
@endpush
