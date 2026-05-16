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

<div class="row g-3 mt-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <strong>STBM Terbaru</strong>
            </div>

            <div class="card-body p-1" style="height: 200px;">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Desa</th>
                            <th>Petugas</th>
                            <th>Status</th>
                            <th></th>
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
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <strong>Pegawai Pengisian STBM Terbanyak</strong>
            </div>
            <div class="card-body" style="height: 200px;">
                <canvas id="pegawaiChart"></canvas>
            </div>
        </div>
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
    // chart pegawai input terbanyak
    const pegawaiLabels = @json($pegawaiChart->pluck('nama'));

    const pegawaiTotal = @json($pegawaiChart->pluck('stbm_count'));

    const pegawaiCtx = document.getElementById('pegawaiChart').getContext('2d');

    new Chart(pegawaiCtx, {

        type: 'bar',

        data: {
            labels: pegawaiLabels,

            datasets: [{
                label: 'Jumlah Isi STBM',

                data: pegawaiTotal,

                backgroundColor: 'rgba(25, 135, 84, 0.8)',

                borderRadius: 8
            }]
        },

        options: {
            
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                }
            },

            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    // Kondisi STBM per Desa
    const desaLabels = @json(array_values(array_keys($rekomendasi)));

    const rasioLayak = @json(
        array_values(array_map(function($d) {
            if (($d['total_kk'] ?? 0) == 0) return 0;
            return round(($d['kk_layak'] / $d['total_kk']) * 100, 2);
        }, $rekomendasi))
    );

    const statusDesa = @json(
        array_values(array_map(fn($d) => $d['status'] ?? 'Tidak Diketahui', $rekomendasi))
    );


    // Warna batang berdasarkan status
    const barColors = statusDesa.map(status => {
        if (status === 'Layak') return 'rgba(25, 135, 84, 0.8)';
        if (status === 'Cukup') return 'rgba(255, 193, 7, 0.8)';
        if (status === 'Tidak Layak') return 'rgba(220, 53, 69, 0.8)';
        return 'rgba(108, 117, 125, 0.8)'; // abu
    });

    const ctx = document.getElementById('desaChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: desaLabels,
            datasets: [{
                label: 'Rasio KK Layak (%)',
                data: rasioLayak,
                backgroundColor: barColors
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
    // PIE CHART PILAR 
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

                        const label = ctx.chart.data.labels[ctx.dataIndex];

                        const data = ctx.chart.data.datasets[0].data;

                        const total = data.reduce((a, b) => a + b, 0);

                        const percent = total
                            ? ((value / total) * 100).toFixed(1)
                            : 0;

                        return label + '\n' + percent + '%';
                    }
                },
                legend: {
                    display: false
                },
            }
        },
        plugins: [ChartDataLabels]
    });
</script>
@endpush